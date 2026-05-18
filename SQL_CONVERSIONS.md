# Complete Eloquent to Raw SQL Conversion Guide

## Table of Contents
1. [Model Relationships](#model-relationships)
2. [Employee Controller](#employee-controller)
3. [Attendance Controller](#attendance-controller)
4. [Leave Controller](#leave-controller)
5. [Dashboard Controller](#dashboard-controller)
6. [Department Controller](#department-controller)
7. [Shift Controller](#shift-controller)
8. [User Controller](#user-controller)

---

## Model Relationships

### Employee Model
**Eloquent Relations:**
```php
$employee->department()        // belongsTo Department
$employee->attendances()       // hasMany Attendance
$employee->leaves()            // hasMany Leave
$employee->shift()             // hasOne Shift
$employee->user()              // hasOne User
```

**Raw SQL to Load Relations:**
```sql
-- Get employee with department
SELECT e.* FROM employees e
LEFT JOIN departments d ON e.department_id = d.id;

-- Get employee's attendances
SELECT a.* FROM attendances a
WHERE a.employee_id = ?;

-- Get employee's leaves
SELECT l.* FROM leaves l
WHERE l.employee_id = ?;

-- Get employee's shift
SELECT s.* FROM shifts s
WHERE s.employee_id = ?;

-- Get employee's user
SELECT u.* FROM users u
WHERE u.employee_id = ?;
```

**Laravel DB Version:**
```php
// Get employee with department
$employee = DB::select("
    SELECT e.* FROM employees e
    LEFT JOIN departments d ON e.department_id = d.id
    WHERE e.id = ?
", [$employeeId]);

// Get employee's attendances
$attendances = DB::select("
    SELECT a.* FROM attendances a
    WHERE a.employee_id = ?
", [$employeeId]);

// Get employee's leaves
$leaves = DB::select("
    SELECT l.* FROM leaves l
    WHERE l.employee_id = ?
", [$employeeId]);

// Get employee's shift
$shift = DB::selectOne("
    SELECT s.* FROM shifts s
    WHERE s.employee_id = ?
", [$employeeId]);

// Get employee's user
$user = DB::selectOne("
    SELECT u.* FROM users u
    WHERE u.employee_id = ?
", [$employeeId]);
```

---

## Employee Controller

### 1. Index - Get All Employees with Department
**Eloquent:**
```php
$employees = Employee::with('department')->get();
$departments = Department::all();
```

**Raw SQL:**
```sql
SELECT e.*, d.id as dept_id, d.name as dept_name, d.description 
FROM employees e
LEFT JOIN departments d ON e.department_id = d.id
ORDER BY e.id;

SELECT * FROM departments;
```

**Laravel DB Version:**
```php
$employees = DB::select("
    SELECT e.*, d.id as dept_id, d.name as dept_name, d.description 
    FROM employees e
    LEFT JOIN departments d ON e.department_id = d.id
    ORDER BY e.id
");

$departments = DB::select("SELECT * FROM departments");
```

---

### 2. Store - Create New Employee
**Eloquent:**
```php
Employee::create($validated);
```

**Raw SQL:**
```sql
INSERT INTO employees 
(first_name, last_name, employee_code, email, phone_number, department_id, 
 position, date_of_hire, salary_rate, employment_type, status, address, shift_id, created_at, updated_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());
```

**Laravel DB Version:**
```php
DB::insert("
    INSERT INTO employees 
    (first_name, last_name, employee_code, email, phone_number, department_id, 
     position, date_of_hire, salary_rate, employment_type, status, address, shift_id, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
", [
    $validated['first_name'],
    $validated['last_name'],
    $validated['employee_code'],
    $validated['email'],
    $validated['phone_number'],
    $validated['department_id'],
    $validated['position'],
    $validated['date_of_hire'],
    $validated['salary_rate'],
    $validated['employment_type'],
    'active',
    $validated['address'],
    $validated['shift_id'] ?? null
]);
```

---

### 3. Edit - Get Single Employee for Editing
**Eloquent:**
```php
$employee = Employee::find($id); // Implicit route binding
$departments = Employee::pluck('department')->unique();
```

**Raw SQL:**
```sql
SELECT * FROM employees WHERE id = ?;

SELECT DISTINCT department_id 
FROM employees 
WHERE department_id IS NOT NULL;
```

**Laravel DB Version:**
```php
$employee = DB::selectOne("SELECT * FROM employees WHERE id = ?", [$employeeId]);

$departments = DB::select("
    SELECT DISTINCT department_id 
    FROM employees 
    WHERE department_id IS NOT NULL
");
```

---

### 4. Update - Modify Employee
**Eloquent:**
```php
$employee->update($validatedData);
```

**Raw SQL:**
```sql
UPDATE employees 
SET first_name = ?, last_name = ?, employee_code = ?, email = ?, 
    phone = ?, department = ?, position = ?, hire_date = ?, 
    salary = ?, employment_type = ?, address = ?, updated_at = NOW()
WHERE id = ?;
```

**Laravel DB Version:**
```php
DB::update("
    UPDATE employees 
    SET first_name = ?, last_name = ?, employee_code = ?, email = ?, 
        phone = ?, department = ?, position = ?, hire_date = ?, 
        salary = ?, employment_type = ?, address = ?, updated_at = NOW()
    WHERE id = ?
", [
    $validatedData['first_name'],
    $validatedData['last_name'],
    $validatedData['employee_code'],
    $validatedData['email'],
    $validatedData['phone'],
    $validatedData['department'],
    $validatedData['position'],
    $validatedData['hire_date'],
    $validatedData['salary'],
    $validatedData['employment_type'],
    $validatedData['address'],
    $employee->id
]);
```

---

### 5. Delete - Remove Employee
**Eloquent:**
```php
$employee->delete();
```

**Raw SQL:**
```sql
DELETE FROM employees WHERE id = ?;
```

**Laravel DB Version:**
```php
DB::delete("DELETE FROM employees WHERE id = ?", [$employee->id]);
```

---

## Attendance Controller

### 1. Index - Get All Attendances with Employee and Department
**Eloquent:**
```php
$attendances = Attendance::with('employee.department')
    ->orderBy('attendance_date', 'desc')
    ->get();

$employees = Employee::where('status', 'active')
    ->orderBy('first_name')
    ->get();

$departments = Department::orderBy('name')->get();
```

**Raw SQL:**
```sql
SELECT a.*, e.id as emp_id, e.first_name, e.last_name, e.employee_code,
       d.id as dept_id, d.name as dept_name
FROM attendances a
LEFT JOIN employees e ON a.employee_id = e.id
LEFT JOIN departments d ON e.department_id = d.id
ORDER BY a.attendance_date DESC;

SELECT * FROM employees 
WHERE status = 'active'
ORDER BY first_name ASC;

SELECT * FROM departments ORDER BY name ASC;
```

**Laravel DB Version:**
```php
$attendances = DB::select("
    SELECT a.*, e.id as emp_id, e.first_name, e.last_name, e.employee_code,
           d.id as dept_id, d.name as dept_name
    FROM attendances a
    LEFT JOIN employees e ON a.employee_id = e.id
    LEFT JOIN departments d ON e.department_id = d.id
    ORDER BY a.attendance_date DESC
");

$employees = DB::select("
    SELECT * FROM employees 
    WHERE status = 'active'
    ORDER BY first_name ASC
");

$departments = DB::select("
    SELECT * FROM departments ORDER BY name ASC
");
```

---

### 2. Store - Create Attendance Record
**Eloquent:**
```php
Attendance::create($validated);
```

**Raw SQL:**
```sql
INSERT INTO attendances 
(employee_id, attendance_date, clock_in, clock_out, total_hours, status, notes, created_at, updated_at)
VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW());
```

**Laravel DB Version:**
```php
DB::insert("
    INSERT INTO attendances 
    (employee_id, attendance_date, clock_in, clock_out, total_hours, status, notes, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
", [
    $validated['employee_id'],
    $validated['attendance_date'],
    $validated['clock_in'] ?? null,
    $validated['clock_out'] ?? null,
    $validated['total_hours'] ?? null,
    $validated['status'],
    $validated['notes'] ?? null
]);
```

---

### 3. Update - Modify Attendance Record
**Eloquent:**
```php
$attendance->update($validated);
```

**Raw SQL:**
```sql
UPDATE attendances 
SET attendance_date = ?, clock_in = ?, clock_out = ?, 
    total_hours = ?, status = ?, notes = ?, updated_at = NOW()
WHERE id = ?;
```

**Laravel DB Version:**
```php
DB::update("
    UPDATE attendances 
    SET attendance_date = ?, clock_in = ?, clock_out = ?, 
        total_hours = ?, status = ?, notes = ?, updated_at = NOW()
    WHERE id = ?
", [
    $validated['attendance_date'],
    $validated['clock_in'] ?? null,
    $validated['clock_out'] ?? null,
    $validated['total_hours'] ?? null,
    $validated['status'],
    $validated['notes'] ?? null,
    $attendance->id
]);
```

---

### 4. Delete - Remove Attendance Record
**Eloquent:**
```php
$attendance->delete();
```

**Raw SQL:**
```sql
DELETE FROM attendances WHERE id = ?;
```

**Laravel DB Version:**
```php
DB::delete("DELETE FROM attendances WHERE id = ?", [$attendance->id]);
```

---

## Leave Controller

### 1. Index - Get All Leave Requests with Employee and Department
**Eloquent:**
```php
$leaves = Leave::with('employee.department')
    ->orderBy('created_at', 'desc')
    ->get();

$employees = Employee::where('status', 'active')
    ->orderBy('first_name')
    ->get();
```

**Raw SQL:**
```sql
SELECT l.*, e.id as emp_id, e.first_name, e.last_name, e.employee_code,
       d.id as dept_id, d.name as dept_name
FROM leaves l
LEFT JOIN employees e ON l.employee_id = e.id
LEFT JOIN departments d ON e.department_id = d.id
ORDER BY l.created_at DESC;

SELECT * FROM employees 
WHERE status = 'active'
ORDER BY first_name ASC;
```

**Laravel DB Version:**
```php
$leaves = DB::select("
    SELECT l.*, e.id as emp_id, e.first_name, e.last_name, e.employee_code,
           d.id as dept_id, d.name as dept_name
    FROM leaves l
    LEFT JOIN employees e ON l.employee_id = e.id
    LEFT JOIN departments d ON e.department_id = d.id
    ORDER BY l.created_at DESC
");

$employees = DB::select("
    SELECT * FROM employees 
    WHERE status = 'active'
    ORDER BY first_name ASC
");
```

---

### 2. Store - Create Leave Request
**Eloquent:**
```php
Leave::create($validated);
```

**Raw SQL:**
```sql
INSERT INTO leaves 
(employee_id, leave_type, start_date, end_date, total_days, reason, status, created_at, updated_at)
VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW());
```

**Laravel DB Version:**
```php
DB::insert("
    INSERT INTO leaves 
    (employee_id, leave_type, start_date, end_date, total_days, reason, status, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW())
", [
    $validated['employee_id'],
    $validated['leave_type'],
    $validated['start_date'],
    $validated['end_date'],
    $validated['total_days'],
    $validated['reason']
]);
```

---

### 3. Approve - Approve Leave Request
**Eloquent:**
```php
$leave->update([
    'status' => 'approved',
    'approved_by' => auth()->id(),
    'approved_at' => now()
]);

// Create notification
UserNotification::create([
    'user_id' => $recipient->id,
    'type' => 'leave_status_changed',
    'message' => "Your leave request ({$leave->leave_type}) was approved.",
    'url' => route('employee.request-leave'),
]);
```

**Raw SQL:**
```sql
UPDATE leaves 
SET status = 'approved', approved_by = ?, approved_at = NOW(), updated_at = NOW()
WHERE id = ?;

INSERT INTO user_notifications (user_id, type, message, url, created_at, updated_at)
VALUES (?, 'leave_status_changed', ?, ?, NOW(), NOW());
```

**Laravel DB Version:**
```php
DB::update("
    UPDATE leaves 
    SET status = 'approved', approved_by = ?, approved_at = NOW(), updated_at = NOW()
    WHERE id = ?
", [
    auth()->id(),
    $leave->id
]);

DB::insert("
    INSERT INTO user_notifications (user_id, type, message, url, created_at, updated_at)
    VALUES (?, 'leave_status_changed', ?, ?, NOW(), NOW())
", [
    $recipient->id,
    "Your leave request ({$leave->leave_type}) was approved.",
    route('employee.request-leave')
]);
```

---

### 4. Reject - Reject Leave Request
**Eloquent:**
```php
$leave->update([
    'status' => 'rejected',
    'approved_by' => auth()->id(),
    'approved_at' => now(),
    'approval_notes' => $validated['approval_notes'] ?? 'Rejected'
]);

UserNotification::create([
    'user_id' => $recipient->id,
    'type' => 'leave_status_changed',
    'message' => "Your leave request ({$leave->leave_type}) was rejected.",
    'url' => route('employee.request-leave'),
]);
```

**Raw SQL:**
```sql
UPDATE leaves 
SET status = 'rejected', approved_by = ?, approved_at = NOW(), 
    approval_notes = ?, updated_at = NOW()
WHERE id = ?;

INSERT INTO user_notifications (user_id, type, message, url, created_at, updated_at)
VALUES (?, 'leave_status_changed', ?, ?, NOW(), NOW());
```

**Laravel DB Version:**
```php
DB::update("
    UPDATE leaves 
    SET status = 'rejected', approved_by = ?, approved_at = NOW(), 
        approval_notes = ?, updated_at = NOW()
    WHERE id = ?
", [
    auth()->id(),
    $validated['approval_notes'] ?? 'Rejected',
    $leave->id
]);

DB::insert("
    INSERT INTO user_notifications (user_id, type, message, url, created_at, updated_at)
    VALUES (?, 'leave_status_changed', ?, ?, NOW(), NOW())
", [
    $recipient->id,
    "Your leave request ({$leave->leave_type}) was rejected.",
    route('employee.request-leave')
]);
```

---

### 5. Delete - Remove Leave Request
**Eloquent:**
```php
$leave->delete();
```

**Raw SQL:**
```sql
DELETE FROM leaves WHERE id = ?;
```

**Laravel DB Version:**
```php
DB::delete("DELETE FROM leaves WHERE id = ?", [$leave->id]);
```

---

### 6. Resolve Employee User - Find User Associated with Employee
**Eloquent:**
```php
// Method 1: Direct relationship
$user = $employee->user;

// Method 2: Query by employee_id
$user = User::where('employee_id', $employee->id)->first();

// Method 3: Query by email
$user = User::where('email', $employee->email)->first();
```

**Raw SQL:**
```sql
-- Method 1: Get user via hasOne relationship
SELECT u.* FROM users u
WHERE u.employee_id = ?;

-- Method 2: Query by employee_id
SELECT u.* FROM users u
WHERE u.employee_id = ?
LIMIT 1;

-- Method 3: Query by email
SELECT u.* FROM users u
WHERE u.email = ?
LIMIT 1;
```

**Laravel DB Version:**
```php
// Method 1: Get user via hasOne relationship
$user = DB::selectOne("
    SELECT u.* FROM users u
    WHERE u.employee_id = ?
", [$employee->id]);

// Method 2: Query by employee_id
$user = DB::selectOne("
    SELECT u.* FROM users u
    WHERE u.employee_id = ?
    LIMIT 1
", [$employee->id]);

// Method 3: Query by email
$user = DB::selectOne("
    SELECT u.* FROM users u
    WHERE u.email = ?
    LIMIT 1
", [$employee->email]);
```

---

## Dashboard Controller

### 1. Get Total Active Employees
**Eloquent:**
```php
$totalEmployees = Employee::where('status', 'active')->count();
```

**Raw SQL:**
```sql
SELECT COUNT(*) as count FROM employees WHERE status = 'active';
```

**Laravel DB Version:**
```php
$result = DB::selectOne("SELECT COUNT(*) as count FROM employees WHERE status = 'active'");
$totalEmployees = $result->count;
```

---

### 2. Get Present Today Count
**Eloquent:**
```php
$presentToday = Attendance::whereDate('attendance_date', Carbon::today())
                          ->where('status', 'present')
                          ->count();
```

**Raw SQL:**
```sql
SELECT COUNT(*) as count FROM attendances 
WHERE DATE(attendance_date) = CURDATE() 
  AND status = 'present';
```

**Laravel DB Version:**
```php
$result = DB::selectOne("
    SELECT COUNT(*) as count FROM attendances 
    WHERE DATE(attendance_date) = CURDATE() 
      AND status = 'present'
");
$presentToday = $result->count;
```

---

### 3. Get Pending Leaves Count
**Eloquent:**
```php
$pendingLeaves = Leave::where('status', 'pending')->count();
```

**Raw SQL:**
```sql
SELECT COUNT(*) as count FROM leaves WHERE status = 'pending';
```

**Laravel DB Version:**
```php
$result = DB::selectOne("SELECT COUNT(*) as count FROM leaves WHERE status = 'pending'");
$pendingLeaves = $result->count;
```

---

### 4. Get Total Departments Count
**Eloquent:**
```php
$totalDepartments = Department::count();
```

**Raw SQL:**
```sql
SELECT COUNT(*) as count FROM departments;
```

**Laravel DB Version:**
```php
$result = DB::selectOne("SELECT COUNT(*) as count FROM departments");
$totalDepartments = $result->count;
```

---

### 5. Get Today's Attendance (Last 5 Records)
**Eloquent:**
```php
$todaysAttendance = Attendance::with('employee')
                             ->whereDate('attendance_date', Carbon::today())
                             ->latest()
                             ->limit(5)
                             ->get();
```

**Raw SQL:**
```sql
SELECT a.*, e.id as emp_id, e.first_name, e.last_name, e.employee_code
FROM attendances a
LEFT JOIN employees e ON a.employee_id = e.id
WHERE DATE(a.attendance_date) = CURDATE()
ORDER BY a.created_at DESC
LIMIT 5;
```

**Laravel DB Version:**
```php
$todaysAttendance = DB::select("
    SELECT a.*, e.id as emp_id, e.first_name, e.last_name, e.employee_code
    FROM attendances a
    LEFT JOIN employees e ON a.employee_id = e.id
    WHERE DATE(a.attendance_date) = CURDATE()
    ORDER BY a.created_at DESC
    LIMIT 5
");
```

---

### 6. Get Recent Leave Requests (Last 5 Records)
**Eloquent:**
```php
$recentLeaves = Leave::with('employee')
                    ->latest()
                    ->limit(5)
                    ->get();
```

**Raw SQL:**
```sql
SELECT l.*, e.id as emp_id, e.first_name, e.last_name, e.employee_code
FROM leaves l
LEFT JOIN employees e ON l.employee_id = e.id
ORDER BY l.created_at DESC
LIMIT 5;
```

**Laravel DB Version:**
```php
$recentLeaves = DB::select("
    SELECT l.*, e.id as emp_id, e.first_name, e.last_name, e.employee_code
    FROM leaves l
    LEFT JOIN employees e ON l.employee_id = e.id
    ORDER BY l.created_at DESC
    LIMIT 5
");
```

---

## Department Controller

### 1. Index - Get Departments with Employee Count
**Eloquent:**
```php
$departments = Department::withCount('employees')->get();
```

**Raw SQL:**
```sql
SELECT d.*, COUNT(e.id) as employees_count
FROM departments d
LEFT JOIN employees e ON d.id = e.department_id
GROUP BY d.id
ORDER BY d.id;
```

**Laravel DB Version:**
```php
$departments = DB::select("
    SELECT d.*, COUNT(e.id) as employees_count
    FROM departments d
    LEFT JOIN employees e ON d.id = e.department_id
    GROUP BY d.id
    ORDER BY d.id
");
```

---

### 2. Store - Create Department
**Eloquent:**
```php
Department::create($validated);
```

**Raw SQL:**
```sql
INSERT INTO departments (name, description, created_at, updated_at)
VALUES (?, ?, NOW(), NOW());
```

**Laravel DB Version:**
```php
DB::insert("
    INSERT INTO departments (name, description, created_at, updated_at)
    VALUES (?, ?, NOW(), NOW())
", [
    $validated['name'],
    $validated['description'] ?? null
]);
```

---

### 3. Update - Modify Department
**Eloquent:**
```php
$department->update($validated);
```

**Raw SQL:**
```sql
UPDATE departments 
SET name = ?, description = ?, updated_at = NOW()
WHERE id = ?;
```

**Laravel DB Version:**
```php
DB::update("
    UPDATE departments 
    SET name = ?, description = ?, updated_at = NOW()
    WHERE id = ?
", [
    $validated['name'],
    $validated['description'] ?? null,
    $department->id
]);
```

---

### 4. Delete - Remove Department (with validation)
**Eloquent:**
```php
if ($department->employees()->count() > 0) {
    return back()->with('error', 'Cannot delete department with employees!');
}
$department->delete();
```

**Raw SQL:**
```sql
-- Check if department has employees
SELECT COUNT(*) as emp_count FROM employees WHERE department_id = ?;

-- Delete if no employees
DELETE FROM departments WHERE id = ?;
```

**Laravel DB Version:**
```php
$result = DB::selectOne("
    SELECT COUNT(*) as emp_count FROM employees WHERE department_id = ?
", [$department->id]);

if ($result->emp_count > 0) {
    return back()->with('error', 'Cannot delete department with employees!');
}

DB::delete("DELETE FROM departments WHERE id = ?", [$department->id]);
```

---

## Shift Controller

### 1. Index - Get All Shifts with Employee
**Eloquent:**
```php
$shifts = Shift::with('employee')->get();
$employees = Employee::orderBy('first_name')->get();
$availableEmployees = Employee::whereDoesntHave('shift')
    ->orderBy('first_name')
    ->get();
```

**Raw SQL:**
```sql
-- Get all shifts with employee data
SELECT s.*, e.id as emp_id, e.first_name, e.last_name, e.employee_code
FROM shifts s
LEFT JOIN employees e ON s.employee_id = e.id;

-- Get all employees
SELECT * FROM employees ORDER BY first_name;

-- Get employees without shifts
SELECT e.* FROM employees e
WHERE e.id NOT IN (SELECT DISTINCT employee_id FROM shifts WHERE employee_id IS NOT NULL)
ORDER BY e.first_name;
```

**Laravel DB Version:**
```php
$shifts = DB::select("
    SELECT s.*, e.id as emp_id, e.first_name, e.last_name, e.employee_code
    FROM shifts s
    LEFT JOIN employees e ON s.employee_id = e.id
");

$employees = DB::select("
    SELECT * FROM employees ORDER BY first_name
");

$availableEmployees = DB::select("
    SELECT e.* FROM employees e
    WHERE e.id NOT IN (SELECT DISTINCT employee_id FROM shifts WHERE employee_id IS NOT NULL)
    ORDER BY e.first_name
");
```

---

### 2. Store - Create Shift
**Eloquent:**
```php
Shift::create($validated);
```

**Raw SQL:**
```sql
INSERT INTO shifts (employee_id, shift_name, start_time, end_time, created_at, updated_at)
VALUES (?, ?, ?, ?, NOW(), NOW());
```

**Laravel DB Version:**
```php
DB::insert("
    INSERT INTO shifts (employee_id, shift_name, start_time, end_time, created_at, updated_at)
    VALUES (?, ?, ?, ?, NOW(), NOW())
", [
    $validated['employee_id'],
    $validated['shift_name'],
    $validated['start_time'],
    $validated['end_time']
]);
```

---

### 3. Update - Modify Shift
**Eloquent:**
```php
$shift->update($validated);
```

**Raw SQL:**
```sql
UPDATE shifts 
SET employee_id = ?, shift_name = ?, start_time = ?, end_time = ?, description = ?, updated_at = NOW()
WHERE id = ?;
```

**Laravel DB Version:**
```php
DB::update("
    UPDATE shifts 
    SET employee_id = ?, shift_name = ?, start_time = ?, end_time = ?, description = ?, updated_at = NOW()
    WHERE id = ?
", [
    $validated['employee_id'],
    $validated['shift_name'],
    $validated['start_time'],
    $validated['end_time'],
    $validated['description'] ?? null,
    $shift->id
]);
```

---

### 4. Delete - Remove Shift
**Eloquent:**
```php
$shift->delete();
```

**Raw SQL:**
```sql
DELETE FROM shifts WHERE id = ?;
```

**Laravel DB Version:**
```php
DB::delete("DELETE FROM shifts WHERE id = ?", [$shift->id]);
```

---

## User Controller

### 1. Index - Get All Users and Available Employees
**Eloquent:**
```php
$users = User::latest()->get();
$employees = Employee::doesntHave('user')->get();
```

**Raw SQL:**
```sql
-- Get all users
SELECT * FROM users ORDER BY created_at DESC;

-- Get employees without associated user
SELECT e.* FROM employees e
WHERE e.id NOT IN (SELECT DISTINCT employee_id FROM users WHERE employee_id IS NOT NULL);
```

**Laravel DB Version:**
```php
$users = DB::select("
    SELECT * FROM users ORDER BY created_at DESC
");

$employees = DB::select("
    SELECT e.* FROM employees e
    WHERE e.id NOT IN (SELECT DISTINCT employee_id FROM users WHERE employee_id IS NOT NULL)
");
```

---

### 2. Store - Create User
**Eloquent:**
```php
User::create($validated);
```

**Raw SQL:**
```sql
INSERT INTO users (name, email, password, role, employee_id, created_at, updated_at)
VALUES (?, ?, ?, ?, ?, NOW(), NOW());
```

**Laravel DB Version:**
```php
DB::insert("
    INSERT INTO users (name, email, password, role, employee_id, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, NOW(), NOW())
", [
    $validated['name'],
    $validated['email'],
    $validated['password'], // Already hashed with Hash::make()
    $validated['role'],
    $validated['employee_id'] ?? null
]);
```

---

### 3. Edit - Get Single User with Compatible Employees
**Eloquent:**
```php
$employees = Employee::where(function($query) use ($user) {
    $query->doesntHave('user')
          ->orWhereHas('user', function($q) use ($user) {
              $q->where('users.id', $user->id);
          });
})->get();
```

**Raw SQL:**
```sql
SELECT e.* FROM employees e
WHERE e.id NOT IN (SELECT DISTINCT employee_id FROM users WHERE employee_id IS NOT NULL)
   OR e.id IN (SELECT employee_id FROM users WHERE users.id = ?)
ORDER BY e.first_name;
```

**Laravel DB Version:**
```php
$employees = DB::select("
    SELECT e.* FROM employees e
    WHERE e.id NOT IN (SELECT DISTINCT employee_id FROM users WHERE employee_id IS NOT NULL)
       OR e.id IN (SELECT employee_id FROM users WHERE users.id = ?)
    ORDER BY e.first_name
", [$user->id]);
```

---

### 4. Update - Modify User
**Eloquent:**
```php
$user->update($validated);
```

**Raw SQL:**
```sql
UPDATE users 
SET name = ?, email = ?, role = ?, employee_id = ?, is_active = ?, 
    password = CASE WHEN ? IS NOT NULL THEN ? ELSE password END,
    updated_at = NOW()
WHERE id = ?;
```

**Laravel DB Version:**
```php
// If password is being updated
if ($request->filled('password')) {
    DB::update("
        UPDATE users 
        SET name = ?, email = ?, role = ?, employee_id = ?, is_active = ?, 
            password = ?, updated_at = NOW()
        WHERE id = ?
    ", [
        $validated['name'],
        $validated['email'],
        $validated['role'],
        $validated['employee_id'] ?? null,
        $validated['is_active'] ?? false,
        Hash::make($request->password),
        $user->id
    ]);
} else {
    DB::update("
        UPDATE users 
        SET name = ?, email = ?, role = ?, employee_id = ?, is_active = ?, updated_at = NOW()
        WHERE id = ?
    ", [
        $validated['name'],
        $validated['email'],
        $validated['role'],
        $validated['employee_id'] ?? null,
        $validated['is_active'] ?? false,
        $user->id
    ]);
}
```

---

### 5. Delete - Remove User
**Eloquent:**
```php
$user->delete();
```

**Raw SQL:**
```sql
DELETE FROM users WHERE id = ?;
```

**Laravel DB Version:**
```php
DB::delete("DELETE FROM users WHERE id = ?", [$user->id]);
```

---

## Summary of Key Patterns

| Eloquent Method | SQL Equivalent |
|---|---|
| `Model::all()` | `SELECT * FROM table` |
| `Model::where('col', value)->get()` | `SELECT * FROM table WHERE col = ?` |
| `Model::where('col', value)->first()` | `SELECT * FROM table WHERE col = ? LIMIT 1` |
| `Model::with('relation')->get()` | `SELECT ... FROM table LEFT JOIN relation_table ...` |
| `Model::withCount('relation')->get()` | `SELECT ..., COUNT(relation_id) as count FROM table LEFT JOIN ...` |
| `Model::whereDoesntHave('relation')->get()` | `SELECT ... FROM table WHERE id NOT IN (SELECT distinct ...` |
| `Model::create($data)` | `INSERT INTO table (...) VALUES (?, ?, ...)` |
| `model->update($data)` | `UPDATE table SET ... WHERE id = ?` |
| `model->delete()` | `DELETE FROM table WHERE id = ?` |
| `Model::count()` | `SELECT COUNT(*) FROM table` |
| `model->relation()` | `SELECT * FROM relation_table WHERE ... = ?` |

---

## Using DB::select() vs DB::statement()

- **DB::select()** - Used for SELECT queries that return results
- **DB::selectOne()** - Used for SELECT queries that return a single row
- **DB::statement()** - Used for any statement (rarely needed for SELECTs)
- **DB::insert()** - Used for INSERT statements
- **DB::update()** - Used for UPDATE statements
- **DB::delete()** - Used for DELETE statements

All support parameter binding with `?` placeholders to prevent SQL injection.

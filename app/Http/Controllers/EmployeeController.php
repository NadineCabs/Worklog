<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    $employees = Employee::with('department')->get(); 
    $departments = Department::all(); 

    return view('dashboard.employees', [
        'employees' => $employees,
        'departments' => $departments,
    ]);
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // NOTE: This currently returns 'employees.create', which should point to a separate view.
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage (handles form submission).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'first_name'      => 'required|string|max:100',
        'last_name'       => 'required|string|max:100',
        'employee_code'   => 'required|string|max:50|unique:employees,employee_code',
        'email'           => 'required|email|unique:employees,email',
        'phone_number'    => 'required|string|max:20',
        'department_id'   => 'required|exists:departments,id',
        'position'        => 'required|string|max:50',
        'date_of_hire'    => 'required|date',
        'salary_rate'     => 'required|numeric|min:0',
        'employment_type' => 'nullable|string',
        'status'          => 'required|string|max:20',
        'address'         => 'nullable|string',
    ]);

    Employee::create($validated);

    return redirect()->route('employees.index')
        ->with('success', 'Employee has been successfully added!');
}

    
    // ----------------------------------------------------------------------
    // ⬇️ EDIT FUNCTIONALITY (SHOW FORM) 
    // ----------------------------------------------------------------------

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        // Re-fetch departments to populate any potential dropdowns in the edit form
        $departments = Employee::pluck('department')->unique();

        return view('dashboard.employees-edit', [ 
            'employee' => $employee,    
            'departments' => $departments,
        ]);
    }
    
    // ----------------------------------------------------------------------
    // ⬇️ UPDATE FUNCTIONALITY (HANDLE SUBMISSION) 
    // ----------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        // 1. --- Input Validation (Unique rules ignore the current employee ID) ---
        $validatedData = $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            // Ignore the current employee's code and email when checking unique constraint
            'employee_code'   => 'required|string|max:50|unique:employees,employee_code,' . $employee->id,
            'email'           => 'required|email|unique:employees,email,' . $employee->id,
            'phone'           => 'required|string|max:20',
            'department'      => 'required|string|max:255', 
            'position'        => 'required|string|max:255',
            'hire_date'       => 'required|date',
            'salary'          => 'nullable|numeric|min:0',
            'employment_type' => 'required|in:full-time,part-time,contract',
            'status'          => 'required|in:active,inactive',
            'address'         => 'nullable|string', 
        ]);

        // 2. --- Handle 'name' Conflict ---
        $validatedData['name'] = $validatedData['first_name'] . ' ' . $validatedData['last_name'];


        // 3. --- Database Update ---
        $employee->update($validatedData);

       // 4. --- Success Redirection ---
        return redirect()->route('employees.index')->with('success', 'Employee details for ' . $validatedData['name'] . ' have been updated!');
    }
    
    // ----------------------------------------------------------------------
    // ⬇️ DELETE FUNCTIONALITY 
    // ----------------------------------------------------------------------
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

    // Redirect back to the index page with a success message
        return redirect()->route('employees.index')->with('success', 'Employee has been successfully deleted.');
    }
} 
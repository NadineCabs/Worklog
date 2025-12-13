<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
{
    $attendances = Attendance::with('employee.department')
        ->orderBy('attendance_date', 'desc')
        ->get();
    
    $employees = Employee::all();
    
    return view('dashboard.attendance', compact('attendances', 'employees'));
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'attendance_date' => 'required|date',
        'clock_in' => 'nullable|date_format:H:i',
        'clock_out' => 'nullable|date_format:H:i',
        'total_hours' => 'nullable|numeric',
        'status' => 'required|in:present,absent,late,half-day',
        'notes' => 'nullable|string'
    ]);

    // Auto-calculate total_hours if clock_in and clock_out are provided
    if (!isset($validated['total_hours']) && isset($validated['clock_in']) && isset($validated['clock_out'])) {
        $clockIn = Carbon::parse($validated['clock_in']);
        $clockOut = Carbon::parse($validated['clock_out']);
        $validated['total_hours'] = $clockOut->diffInHours($clockIn, true);
    }

    Attendance::create($validated);

    return redirect()->route('attendance.index')
        ->with('success', 'Attendance recorded successfully!');
}

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'total_hours' => 'nullable|numeric',
            'status' => 'required|in:present,absent,late,half-day',
            'notes' => 'nullable|string'
        ]);

        // Auto-calculate total_hours if clock_in and clock_out are provided
        if (!isset($validated['total_hours']) && isset($validated['clock_in']) && isset($validated['clock_out'])) {
            $clockIn = Carbon::parse($validated['clock_in']);
            $clockOut = Carbon::parse($validated['clock_out']);
            $validated['total_hours'] = $clockOut->diffInHours($clockIn, true);
        }

        $attendance->update($validated);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance updated successfully!');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance deleted successfully!');
    }
}
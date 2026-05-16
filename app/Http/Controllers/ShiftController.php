<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShiftController extends Controller
{
    public function index()
{
    $shifts = Shift::with('employee')->get(); // Load employee relationship
    $employees = Employee::orderBy('first_name')->get(); // All employees for edit modal
    $availableEmployees = Employee::whereDoesntHave('shift')
        ->orderBy('first_name')
        ->get();
    
    return view('dashboard.shift', compact('shifts', 'employees', 'availableEmployees'));
}
    public function store(Request $request)
{
    $validated = $request->validate([
        'employee_id' => [
            'required',
            'exists:employees,id',
            Rule::unique('shifts', 'employee_id'),
        ],
        'shift_name' => 'required|string|max:50',  // Remove unique constraint
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ]);

    Shift::create($validated);

    return redirect()->route('shifts.index')->with('success', 'Shift created successfully!');
}

    public function edit(Shift $shift)
    {
        $shifts = Shift::all();
        return view('dashboard.shift', compact('shifts', 'shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'exists:employees,id',
                Rule::unique('shifts', 'employee_id')->ignore($shift->id),
            ],
            'shift_name' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
        ]);

        $shift->update($validated);

        return redirect()->route('shifts.index')
            ->with('success', 'Shift updated successfully!');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();

        return redirect()->route('shifts.index')
            ->with('success', 'Shift deleted successfully!');
    }
}
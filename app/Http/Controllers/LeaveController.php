<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('employee.department')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        return view('dashboard.leave', compact('leaves', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'total_days' => 'nullable|integer'
        ]);

        // Auto-calculate total_days if not provided
        if (!isset($validated['total_days'])) {
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);
            $validated['total_days'] = $startDate->diffInDays($endDate) + 1;
        }

        // Set default status
        $validated['status'] = 'pending';

        Leave::create($validated);

        return redirect()->route('leave.index')
            ->with('success', 'Leave request submitted successfully!');
    }

    public function approve(Leave $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->route('leave.index')
            ->with('success', 'Leave request approved!');
    }

    public function reject(Request $request, Leave $leave)
    {
        $validated = $request->validate([
            'approval_notes' => 'nullable|string'
        ]);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $validated['approval_notes'] ?? 'Rejected'
        ]);

        return redirect()->route('leave.index')
            ->with('success', 'Leave request rejected!');
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();

        return redirect()->route('leave.index')
            ->with('success', 'Leave request deleted successfully!');
    }
}
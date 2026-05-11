<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployeePageController extends Controller
{
    /**
     * Show employee attendance page
     */
    public function attendance()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }

        $attendances = $employee->attendances()->latest()->paginate(20);

        return view('employee.attendance', compact('employee', 'attendances'));
    }

    /**
     * Show request leave page
     */
    public function requestLeave()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }

        $leaves = $employee->leaves()->latest()->take(10)->get();

        return view('employee.request-leave', compact('employee', 'leaves'));
    }

    /**
     * Store leave request
     */
    public function storeLeaveRequest(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }

        $validated = $request->validate([
            'leave_type' => 'required|in:sick,casual,earned,emergency',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10|max:500',
        ]);

        $employee->leaves()->create([
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('employee.request-leave')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Show employee profile page
     */
    public function profile()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }

        return view('employee.profile', compact('employee'));
    }

    /**
     * Show change password page
     */
    public function changePassword()
    {
        return view('employee.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,}$/',
        ], [
            'current_password.current_password' => 'The current password is incorrect.',
            'new_password.regex' => 'The password must contain uppercase, lowercase, number, and special character.',
            'new_password.confirmed' => 'The passwords do not match.',
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('employee.change-password')
            ->with('success', 'Password updated successfully.');
    }
}

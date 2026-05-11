<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeDashboardController extends Controller
{
    /**
     * Show employee dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Redirect to login if no associated employee
        if (!$employee) {
            return redirect()->route('login')
                ->with('error', 'Your employee record could not be found.');
        }

        // Get today's attendance
        try {
            $todayAttendance = $employee->attendances()
                ->whereDate('attendance_date', Carbon::today())
                ->first();
        } catch (\Exception $e) {
            $todayAttendance = null;
        }

        // Get recent attendance records
        try {
            $recentAttendances = $employee->attendances()
                ->latest()
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $recentAttendances = collect([]);
        }

        // Get pending leaves count
        try {
            $pendingLeavesCount = $employee->leaves()
                ->where('status', 'pending')
                ->count();
        } catch (\Exception $e) {
            $pendingLeavesCount = 0;
        }

        // Get approved leaves count
        try {
            $approvedLeavesCount = $employee->leaves()
                ->where('status', 'approved')
                ->count();
        } catch (\Exception $e) {
            $approvedLeavesCount = 0;
        }

        // Get recent leave requests
        try {
            $recentLeaves = $employee->leaves()
                ->latest()
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $recentLeaves = collect([]);
        }

        return view('dashboard.employee', compact(
            'employee',
            'todayAttendance',
            'recentAttendances',
            'pendingLeavesCount',
            'approvedLeavesCount',
            'recentLeaves'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics with safe defaults (0 if tables don't exist yet)
        try {
            $totalEmployees = Employee::where('status', 'active')->count();
        } catch (\Exception $e) {
            $totalEmployees = 0;
        }
        
        try {
            $presentToday = Attendance::whereDate('attendance_date', Carbon::today())
                                      ->where('status', 'present')
                                      ->count();
        } catch (\Exception $e) {
            $presentToday = 0;
        }
        
        try {
            $pendingLeaves = Leave::where('status', 'pending')->count();
        } catch (\Exception $e) {
            $pendingLeaves = 0;
        }
        
        try {
            $totalDepartments = Department::count();
        } catch (\Exception $e) {
            $totalDepartments = 0;
        }
        
        // Get today's attendance (with fallback)
        try {
            $todaysAttendance = Attendance::with('employee')
                                         ->whereDate('attendance_date', Carbon::today())
                                         ->latest()
                                         ->limit(5)
                                         ->get();
        } catch (\Exception $e) {
            $todaysAttendance = collect([]);
        }
        
        // Get recent leave requests (with fallback)
        try {
            $recentLeaves = Leave::with('employee')
                                ->latest()
                                ->limit(5)
                                ->get();
        } catch (\Exception $e) {
            $recentLeaves = collect([]);
        }
        
        return view('dashboard.index', compact(
            'totalEmployees',
            'presentToday',
            'pendingLeaves',
            'totalDepartments',
            'todaysAttendance',
            'recentLeaves'
        ));
    }
}
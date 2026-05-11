@extends('layouts.employee')

@section('title', 'My Profile')

@section('content')

<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">My Profile</h1>
        <p class="text-gray-600">View and manage your personal information</p>
    </div>
    
    <!-- Profile Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 lg:col-span-1">
            <div class="flex flex-col items-center">
                <div class="w-24 h-24 bg-gradient-to-br from-teal-400 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-4xl mb-4">
                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                </div>
                <h2 class="text-2xl font-bold text-gray-800 text-center">{{ $employee->first_name }} {{ $employee->last_name }}</h2>
                <p class="text-gray-600 text-center mt-2">{{ $employee->position }}</p>
                <p class="text-sm text-gray-500 text-center mt-1">{{ $employee->employee_code }}</p>
                
                <div class="mt-6 w-full pt-6 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-600 text-center">Status</p>
                    <div class="flex justify-center mt-2">
                        @if($employee->status === 'active')
                            <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($employee->status) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 lg:col-span-2">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Personal Information</h3>
            
            <div class="grid grid-cols-2 gap-6">
                
                <!-- First Name -->
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">First Name</p>
                    <p class="text-gray-900 text-lg">{{ $employee->first_name }}</p>
                </div>
                
                <!-- Last Name -->
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Last Name</p>
                    <p class="text-gray-900 text-lg">{{ $employee->last_name }}</p>
                </div>
                
                <!-- Email -->
                <div class="col-span-2">
                    <p class="text-sm font-medium text-gray-600 mb-1">Email Address</p>
                    <p class="text-gray-900 text-lg break-words">{{ $employee->email }}</p>
                </div>
                
                <!-- Phone -->
                <div class="col-span-2">
                    <p class="text-sm font-medium text-gray-600 mb-1">Phone Number</p>
                    <p class="text-gray-900 text-lg">{{ $employee->phone_number ?? 'Not provided' }}</p>
                </div>
                
                <!-- Address -->
                <div class="col-span-2">
                    <p class="text-sm font-medium text-gray-600 mb-1">Address</p>
                    <p class="text-gray-900 text-lg">{{ $employee->address ?? 'Not provided' }}</p>
                </div>
                
            </div>
        </div>
        
    </div>
    
    <!-- Employment Information -->
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Employment Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Employee Code -->
            <div>
                <p class="text-sm font-medium text-gray-600 mb-2">Employee Code</p>
                <p class="text-gray-900 font-mono text-lg">{{ $employee->employee_code }}</p>
            </div>
            
            <!-- Position -->
            <div>
                <p class="text-sm font-medium text-gray-600 mb-2">Position</p>
                <p class="text-gray-900 text-lg">{{ $employee->position }}</p>
            </div>
            
            <!-- Department -->
            <div>
                <p class="text-sm font-medium text-gray-600 mb-2">Department</p>
                <p class="text-gray-900 text-lg">{{ $employee->department->name ?? 'N/A' }}</p>
            </div>
            
            <!-- Employment Type -->
            <div>
                <p class="text-sm font-medium text-gray-600 mb-2">Employment Type</p>
                <p class="text-gray-900 text-lg">{{ $employee->employment_type ?? 'N/A' }}</p>
            </div>
            
            <!-- Date of Hire -->
            <div>
                <p class="text-sm font-medium text-gray-600 mb-2">Date of Hire</p>
                <p class="text-gray-900 text-lg">{{ $employee->date_of_hire ? \Carbon\Carbon::parse($employee->date_of_hire)->format('M d, Y') : 'N/A' }}</p>
            </div>
            
            <!-- Salary Rate -->
            <div>
                <p class="text-sm font-medium text-gray-600 mb-2">Salary Rate</p>
                <p class="text-gray-900 text-lg">{{ $employee->salary_rate ? '$' . number_format($employee->salary_rate, 2) : 'N/A' }}</p>
            </div>
            
            <!-- Shift -->
            <div>
                <p class="text-sm font-medium text-gray-600 mb-2">Shift</p>
                <p class="text-gray-900 text-lg">{{ $employee->shift->name ?? 'N/A' }}</p>
            </div>
            
            <!-- Status -->
            <div>
                <p class="text-sm font-medium text-gray-600 mb-2">Status</p>
                <div class="mt-2">
                    @if($employee->status === 'active')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($employee->status) }}</span>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    
</div>

@endsection

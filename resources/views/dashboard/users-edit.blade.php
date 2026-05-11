@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="max-w-2xl mx-auto">
    
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Edit User</h1>
        <p class="text-gray-600 mt-2">Update user account details</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        
        <!-- Form -->
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="John Doe"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="john@example.com"
                    required
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Role -->
            <div class="mb-6">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                <select 
                    name="role" 
                    id="role"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('role') ? 'border-red-500' : 'border-gray-300' }}"
                    required
                    onchange="toggleEmployeeField()"
                >
                    <option value="">Select a role</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="employee" {{ old('role', $user->role) === 'employee' ? 'selected' : '' }}>Employee</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Employee Selection (visible only for employee role) -->
            <div id="employeeField" class="mb-6" style="display: none;">
                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Link to Employee</label>
                <select 
                    name="employee_id" 
                    id="employee_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('employee_id') ? 'border-red-500' : 'border-gray-300' }}"
                >
                    <option value="">-- Select an employee (optional) --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id', $user->employee_id) == $employee->id ? 'selected' : '' }}>
                            {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_code }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password (Optional) -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password (leave blank to keep current)</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password Confirmation -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="••••••••"
                >
            </div>
            
            <!-- Active Status -->
            <div class="mb-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1"
                        {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-teal-600"
                    >
                    <span class="text-sm font-medium text-gray-700">User is active</span>
                </label>
            </div>
            
            <!-- Form Actions -->
            <div class="flex gap-3">
                <button 
                    type="submit"
                    class="flex-1 bg-teal-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-teal-700 transition-colors"
                >
                    Update User
                </button>
                <a 
                    href="{{ route('users.index') }}"
                    class="flex-1 bg-gray-200 text-gray-800 px-6 py-2.5 rounded-lg font-medium hover:bg-gray-300 transition-colors text-center"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleEmployeeField() {
    const role = document.getElementById('role').value;
    const employeeField = document.getElementById('employeeField');
    employeeField.style.display = role === 'employee' ? 'block' : 'none';
}

// On page load, check if employee role was pre-selected
document.addEventListener('DOMContentLoaded', function() {
    toggleEmployeeField();
});
</script>

@endsection

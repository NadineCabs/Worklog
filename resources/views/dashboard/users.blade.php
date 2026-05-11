@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<div x-data="{ addModal: false }">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">User Management</h1>
            <p class="mt-1 text-sm text-gray-600">Manage system users and access</p>
        </div>
        <a 
            @click="addModal = true"
            class="px-4 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition-colors cursor-pointer"
        >
            + Add User
        </a>
    </div>
    
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border">
            <p class="text-sm text-gray-600">Total Users</p>
            <p class="text-2xl font-bold mt-2">{{ $users->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border">
            <p class="text-sm text-gray-600">Active Users</p>
            <p class="text-2xl font-bold mt-2 text-green-600">{{ $users->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border">
            <p class="text-sm text-gray-600">Admins</p>
            <p class="text-2xl font-bold mt-2 text-blue-600">{{ $users->where('role', 'admin')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border">
            <p class="text-sm text-gray-600">Employees</p>
            <p class="text-2xl font-bold mt-2 text-purple-600">{{ $users->where('role', 'employee')->count() }}</p>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($user->employee)
                            <span class="font-medium">{{ $user->employee->first_name }} {{ $user->employee->last_name }}</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_active)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">✏️</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Add User Modal -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div @click="addModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            
            <div class="relative bg-white rounded-lg max-w-2xl w-full p-8 shadow-xl">
                <!-- Modal Header -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-800">Create New User</h3>
                    <p class="text-gray-600 text-sm mt-1">Add a new employee user account</p>
                </div>
                
                <!-- Form -->
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    
                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            value="{{ old('name') }}"
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
                            value="{{ old('email') }}"
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
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
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
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_code }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-gray-500 text-sm mt-1">Only employees without existing user accounts are shown</p>
                        @error('employee_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                            placeholder="••••••••"
                            required
                        >
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password Confirmation -->
                    <div class="mb-8">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                    
                    <!-- Modal Actions -->
                    <div class="flex gap-3 pt-6 border-t border-gray-200">
                        <button 
                            type="submit"
                            class="flex-1 bg-teal-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-teal-700 transition-colors"
                        >
                            Create User
                        </button>
                        <button 
                            type="button"
                            @click="addModal = false"
                            class="flex-1 bg-gray-200 text-gray-800 px-6 py-2.5 rounded-lg font-medium hover:bg-gray-300 transition-colors"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
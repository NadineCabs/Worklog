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
        <button 
            @click="addModal = true"
            class="px-4 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition-colors"
        >
            + Add User
        </button>
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
                                <div class="text-sm text-gray-500">{{ $user->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $user->email }}</td>
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
                        <button class="text-indigo-600 hover:text-indigo-900">✏️</button>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Add Modal Placeholder -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click="addModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="relative bg-white rounded-lg max-w-lg w-full p-6">
                <h3 class="text-lg font-medium mb-4">Add User</h3>
                <p class="text-sm text-gray-600 mb-4">Full form coming soon...</p>
                <button @click="addModal = false" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg">Close</button>
            </div>
        </div>
    </div>
    
</div>

@endsection
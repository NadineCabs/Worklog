@extends('layouts.employee')

@section('title', 'Change Password')

@section('content')

<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Change Password</h1>
        <p class="text-gray-600">Update your account password</p>
    </div>
    
    <!-- Change Password Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl">
        <form method="POST" action="{{ route('employee.change-password.update') }}">
            @csrf
            
            <!-- Current Password -->
            <div class="mb-6">
                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Current Password <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    id="current_password" 
                    name="current_password" 
                    class="w-full px-4 py-2 border {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required
                >
                @if($errors->has('current_password'))
                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('current_password') }}</p>
                @endif
            </div>
            
            <!-- New Password -->
            <div class="mb-6">
                <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                    New Password <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    id="new_password" 
                    name="new_password" 
                    class="w-full px-4 py-2 border {{ $errors->has('new_password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required
                >
                <p class="text-xs text-gray-500 mt-2">Must be at least 8 characters long</p>
                @if($errors->has('new_password'))
                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('new_password') }}</p>
                @endif
            </div>
            
            <!-- Confirm New Password -->
            <div class="mb-8">
                <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                    Confirm New Password <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    id="new_password_confirmation" 
                    name="new_password_confirmation" 
                    class="w-full px-4 py-2 border {{ $errors->has('new_password_confirmation') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required
                >
                @if($errors->has('new_password_confirmation'))
                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('new_password_confirmation') }}</p>
                @endif
            </div>
            
            <!-- Password Requirements Info -->
            <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-semibold text-blue-900 mb-2">Password Requirements:</h4>
                <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                    <li>At least 8 characters long</li>
                    <li>Mix of uppercase and lowercase letters</li>
                    <li>Include at least one number</li>
                    <li>Include at least one special character (!@#$%^&*)</li>
                </ul>
            </div>
            
            <!-- Submit Button -->
            <div class="flex gap-3">
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition-colors"
                >
                    Update Password
                </button>
                <a 
                    href="{{ route('employee-dashboard') }}" 
                    class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
    
</div>

@endsection

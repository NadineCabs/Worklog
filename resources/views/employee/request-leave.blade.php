@extends('layouts.employee')

@section('title', 'Request Leave')

@section('content')

<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Request Leave</h1>
        <p class="text-gray-600">Submit a new leave request</p>
    </div>
    
    <!-- Leave Request Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl">
        <form method="POST" action="{{ route('employee.request-leave.store') }}">
            @csrf
            
            <!-- Leave Type -->
            <div class="mb-6">
                <label for="leave_type" class="block text-sm font-semibold text-gray-700 mb-2">
                    Leave Type <span class="text-red-500">*</span>
                </label>
                <select 
                    id="leave_type" 
                    name="leave_type" 
                    class="w-full px-4 py-2 border {{ $errors->has('leave_type') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required
                >
                    <option value="">Select a leave type</option>
                    <option value="sick" {{ old('leave_type') === 'sick' ? 'selected' : '' }}>Sick Leave</option>
                    <option value="casual" {{ old('leave_type') === 'casual' ? 'selected' : '' }}>Casual Leave</option>
                    <option value="earned" {{ old('leave_type') === 'earned' ? 'selected' : '' }}>Earned Leave</option>
                    <option value="emergency" {{ old('leave_type') === 'emergency' ? 'selected' : '' }}>Emergency Leave</option>
                </select>
                @if($errors->has('leave_type'))
                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('leave_type') }}</p>
                @endif
            </div>
            
            <!-- Start Date -->
            <div class="mb-6">
                <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                    Start Date <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    id="start_date" 
                    name="start_date" 
                    value="{{ old('start_date') }}"
                    class="w-full px-4 py-2 border {{ $errors->has('start_date') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required
                >
                @if($errors->has('start_date'))
                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('start_date') }}</p>
                @endif
            </div>
            
            <!-- End Date -->
            <div class="mb-6">
                <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                    End Date <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    id="end_date" 
                    name="end_date" 
                    value="{{ old('end_date') }}"
                    class="w-full px-4 py-2 border {{ $errors->has('end_date') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required
                >
                @if($errors->has('end_date'))
                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('end_date') }}</p>
                @endif
            </div>
            
            <!-- Reason -->
            <div class="mb-6">
                <label for="reason" class="block text-sm font-semibold text-gray-700 mb-2">
                    Reason <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="reason" 
                    name="reason" 
                    rows="4"
                    class="w-full px-4 py-2 border {{ $errors->has('reason') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required
                >{{ old('reason') }}</textarea>
                @if($errors->has('reason'))
                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('reason') }}</p>
                @endif
            </div>
            
            <!-- Submit Button -->
            <div class="flex gap-3">
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition-colors"
                >
                    Submit Request
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
    
    <!-- Recent Leave Requests -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Your Leave Requests</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="pb-3 text-left text-xs font-semibold text-gray-600 uppercase">From</th>
                            <th class="pb-3 text-left text-xs font-semibold text-gray-600 uppercase">To</th>
                            <th class="pb-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                            <th class="pb-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($leaves as $leave)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 text-sm text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}
                            </td>
                            <td class="py-4 text-sm text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                            </td>
                            <td class="py-4 text-sm text-gray-600">
                                {{ ucfirst($leave->leave_type ?? 'N/A') }}
                            </td>
                            <td class="py-4">
                                @if($leave->status === 'pending')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($leave->status === 'approved')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500 text-sm">
                                No leave requests submitted yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>

@endsection

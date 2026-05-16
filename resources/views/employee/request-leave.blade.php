@extends('layouts.employee')

@section('title', 'Request Leave')

@section('content')

<div x-data="{ 
    viewModal: false, 
    viewLeave: {} 
}">
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
                            <th class="pb-3 text-left text-xs font-semibold text-gray-600 uppercase">Days</th>
                            <th class="pb-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="pb-3 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
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
                            <td class="py-4 text-sm text-gray-600">
                                {{ $leave->total_days ?? 1 }} {{ ($leave->total_days ?? 1) > 1 ? 'days' : 'day' }}
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
                            <td class="py-4 text-right">
                                <button 
                                    @click="viewModal = true; viewLeave = {
                                        leave_type: '{{ $leave->leave_type }}',
                                        start_date: '{{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }}',
                                        end_date: '{{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}',
                                        total_days: '{{ $leave->total_days }}',
                                        reason: '{{ addslashes($leave->reason) }}',
                                        status: '{{ ucfirst($leave->status) }}'
                                    }"
                                    class="text-blue-600 hover:text-blue-900 transition-colors" title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 text-sm">
                                No leave requests submitted yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- VIEW LEAVE REQUEST DETAILS MODAL -->
    <div 
        x-show="viewModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
        style="display: none;"
        @click.self="viewModal = false">

        <div x-show="viewModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden"
             @click.stop>

            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white">Leave Request Details</h2>
                    <button @click="viewModal = false" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-8 py-6">
                <div class="space-y-4">
                    <div class="border-t border-gray-200 pt-4 space-y-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Leave Type</p>
                            <p class="text-lg text-gray-900" x-text="viewLeave.leave_type"></p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Start Date</p>
                                <p class="text-gray-900" x-text="viewLeave.start_date"></p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-600">End Date</p>
                                <p class="text-gray-900" x-text="viewLeave.end_date"></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Total Days</p>
                            <p class="text-lg text-gray-900" x-text="viewLeave.total_days + ' days'"></p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Status</p>
                            <p class="text-lg font-semibold" x-text="viewLeave.status"
                               :class="{
                                   'text-yellow-600': viewLeave.status === 'Pending',
                                   'text-green-600': viewLeave.status === 'Approved',
                                   'text-red-600': viewLeave.status === 'Rejected'
                               }"></p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Reason</p>
                            <p class="text-gray-900" x-text="viewLeave.reason"></p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button @click="viewModal = false"
                            class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition-all duration-200">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

@endsection

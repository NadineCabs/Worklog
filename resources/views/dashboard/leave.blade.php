@extends('layouts.app')

@section('content')

<div x-data="{ 
    viewModal: false, 
    viewLeave: {} 
}">

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <div>
                <h2 class="text-3xl font-semibold text-gray-800">Leave Requests</h2>
                <p class="text-sm text-gray-600 mt-1">Manage employee leave requests</p>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                <option>Filter by Status: All</option>
                <option>Pending</option>
                <option>Approved</option>
                <option>Rejected</option>
            </select>
            <input type="date" placeholder="From Date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            <input type="date" placeholder="To Date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            <input type="text" placeholder="Search employee name..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
        </div>

        <!-- Leave Requests Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Leave Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($leaves ?? [] as $leave)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-indigo-600 font-semibold text-sm">
                                        {{ strtoupper(substr($leave->employee->first_name ?? 'N', 0, 1) . substr($leave->employee->last_name ?? 'A', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $leave->employee->employee_code ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $leave->leave_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden sm:table-cell">
                            {{ $leave->total_days }} {{ $leave->total_days > 1 ? 'days' : 'day' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($leave->status === 'pending')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @elseif($leave->status === 'approved')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Approved
                                </span>
                            @elseif($leave->status === 'rejected')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                @if($leave->status === 'pending')
                                    <form action="{{ route('leave.approve', $leave->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 transition-colors" title="Approve">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('leave.reject', $leave->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to reject this leave request?')">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Reject">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                                <button 
                                    @click="viewModal = true; viewLeave = {
                                        employee_name: '{{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}',
                                        employee_code: '{{ $leave->employee->employee_code ?? '' }}',
                                        leave_type: '{{ $leave->leave_type }}',
                                        start_date: '{{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }}',
                                        end_date: '{{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}',
                                        total_days: '{{ $leave->total_days }}',
                                        reason: '{{ $leave->reason }}',
                                        status: '{{ ucfirst($leave->status) }}'
                                    }"
                                    class="text-blue-600 hover:text-blue-900 transition-colors" title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('leave.destroy', $leave->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this leave request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">No leave requests found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- VIEW LEAVE MODAL -->
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
                    <div class="flex items-start">
                        <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center mr-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900" x-text="viewLeave.employee_name"></h3>
                            <p class="text-sm text-gray-500" x-text="'Employee Code: ' + viewLeave.employee_code"></p>
                        </div>
                    </div>

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
                            class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all duration-200">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
@extends('layouts.employee')

@section('title', 'My Attendance')

@section('content')

<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">My Attendance</h1>
        <p class="text-gray-600">View your attendance history and records</p>
    </div>
    
    <!-- Attendance Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Attendance Records</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="pb-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                            <th class="pb-3 text-left text-xs font-semibold text-gray-600 uppercase">Check-In</th>
                            <th class="pb-3 text-left text-xs font-semibold text-gray-600 uppercase">Check-Out</th>
                            <th class="pb-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="pb-3 text-left text-xs font-semibold text-gray-600 uppercase">Hours</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($attendances as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 text-sm text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y') }}
                            </td>
                            <td class="py-4 text-sm text-gray-600">
                                {{ $record->clock_in ? \Carbon\Carbon::parse($record->clock_in)->format('h:i A') : '—' }}
                            </td>
                            <td class="py-4 text-sm text-gray-600">
                                {{ $record->clock_out ? \Carbon\Carbon::parse($record->clock_out)->format('h:i A') : '—' }}
                            </td>
                            <td class="py-4">
                                @if($record->status === 'present')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                                @elseif($record->status === 'absent')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Absent</span>
                                @elseif($record->status === 'late')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Late</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($record->status) }}</span>
                                @endif
                            </td>
                            <td class="py-4 text-sm text-gray-600">
                                @if($record->clock_in && $record->clock_out)
                                    {{ \Carbon\Carbon::parse($record->clock_in)->diffInHours(\Carbon\Carbon::parse($record->clock_out)) }}h
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 text-sm">
                                No attendance records found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($attendances instanceof \Illuminate\Pagination\Paginator)
    <div class="mt-6">
        {{ $attendances->links() }}
    </div>
    @endif
    
</div>

@endsection

@extends('layouts.app')

@section('content')

<div x-data="{ 
    addModal: false,
    viewModal: false, 
    editModal: false, 
    viewAttendance: {}, 
    editAttendance: {} 
}">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Attendance Log</h1>

        <!-- Open Modal Button -->
        <button @click="addModal = true"
            class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-200 hover:shadow-xl flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="font-semibold">Add Attendance</span>
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                    <option>Filter by Department</option>
                    @foreach($departments ?? [] as $department)
                        <option>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" placeholder="Search employee name..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Check-In</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Check-Out</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Work Hours</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Overtime</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendances ?? [] as $attendance)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center mr-3">
                                    <span class="text-teal-700 font-semibold text-sm">
                                        {{ strtoupper(substr($attendance->employee->first_name ?? 'N', 0, 1) . substr($attendance->employee->last_name ?? 'A', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $attendance->employee->first_name ?? '' }} {{ $attendance->employee->last_name ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $attendance->employee->employee_code ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('h:i A') : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('h:i A') : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 hidden lg:table-cell">
                            {{ $attendance->total_hours ? number_format($attendance->total_hours, 2) . 'h' : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 hidden lg:table-cell">
                            -
                        </td>
                        <td class="px-6 py-4">
                            @if($attendance->status === 'present')
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Present
                                </span>
                            @elseif($attendance->status === 'absent')
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Absent
                                </span>
                            @elseif($attendance->status === 'late')
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Late
                                </span>
                            @elseif($attendance->status === 'half-day')
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Half Day
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <button 
                                    @click="viewModal = true; viewAttendance = {
                                        employee_name: '{{ $attendance->employee->first_name ?? '' }} {{ $attendance->employee->last_name ?? '' }}',
                                        employee_code: '{{ $attendance->employee->employee_code ?? '' }}',
                                        department: '{{ $attendance->employee->department->name ?? '' }}',
                                        date: '{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('F d, Y') }}',
                                        clock_in: '{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('h:i A') : '-' }}',
                                        clock_out: '{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('h:i A') : '-' }}',
                                        total_hours: '{{ $attendance->total_hours ? number_format($attendance->total_hours, 2) : '-' }}',
                                        status: '{{ ucfirst($attendance->status) }}',
                                        notes: '{{ $attendance->notes ?? 'No notes' }}'
                                    }"
                                    class="text-blue-600 hover:text-blue-800 transition-colors" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <button 
                                    @click="editModal = true; editAttendance = {
                                        id: '{{ $attendance->id }}',
                                        employee_id: '{{ $attendance->employee_id }}',
                                        attendance_date: '{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('Y-m-d') }}',
                                        clock_in: '{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}',
                                        clock_out: '{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}',
                                        total_hours: '{{ $attendance->total_hours ?? '' }}',
                                        status: '{{ $attendance->status }}',
                                        notes: '{{ $attendance->notes ?? '' }}'
                                    }"
                                    class="text-teal-600 hover:text-teal-800 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this attendance record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Delete">
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
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">No attendance records found</p>
                                <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add new attendance records</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ADD ATTENDANCE MODAL -->
    <div 
        x-show="addModal" 
        x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
        @click.self="addModal = false">

        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden">

            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white">Add New Attendance</h2>
                    <button @click="addModal = false" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-8 py-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                
                <form action="{{ route('attendance.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Employee Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Employee <span class="text-red-500">*</span>
                        </label>
                        <select name="employee_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} - {{ $emp->employee_code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Attendance Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="attendance_date" required value="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>

                    <!-- Time Fields Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Clock In Time</label>
                            <input type="time" name="clock_in" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Clock Out Time</label>
                            <input type="time" name="clock_out" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>

                    <!-- Total Hours & Status Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Hours</label>
                            <input type="number" name="total_hours" step="0.01" min="0" placeholder="8.00"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-gray-50">
                            <p class="text-xs text-gray-500 mt-1">Leave blank to auto-calculate</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="half-day">Half Day</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" rows="3" placeholder="Add any additional notes here..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 resize-none"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" @click="addModal = false"
                            class="px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 shadow-lg">
                            <span>Save Attendance</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- VIEW ATTENDANCE MODAL -->
    <div 
        x-show="viewModal" 
        x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
        @click.self="viewModal = false">

        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden">

            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white">Attendance Details</h2>
                    <button @click="viewModal = false" class="text-white hover:text-gray-200">
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
                        <div class="w-16 h-16 rounded-full bg-teal-100 flex items-center justify-center mr-4">
                            <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900" x-text="viewAttendance.employee_name"></h3>
                            <p class="text-sm text-gray-500" x-text="'Employee Code: ' + viewAttendance.employee_code"></p>
                            <p class="text-sm text-gray-500" x-text="'Department: ' + viewAttendance.department"></p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Date</p>
                            <p class="text-lg text-gray-900" x-text="viewAttendance.date"></p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Status</p>
                            <p class="text-lg text-gray-900" x-text="viewAttendance.status"></p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Clock In</p>
                            <p class="text-lg text-gray-900" x-text="viewAttendance.clock_in"></p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Clock Out</p>
                            <p class="text-lg text-gray-900" x-text="viewAttendance.clock_out"></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm font-semibold text-gray-600">Total Hours</p>
                            <p class="text-lg text-gray-900" x-text="viewAttendance.total_hours + ' hours'"></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm font-semibold text-gray-600">Notes</p>
                            <p class="text-gray-900" x-text="viewAttendance.notes"></p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button @click="viewModal = false"
                            class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-red-700">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT ATTENDANCE MODAL -->
    <div 
        x-show="editModal" 
        x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
        @click.self="editModal = false">

        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden">

            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white">Edit Attendance</h2>
                    <button @click="editModal = false" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-8 py-6">
                <form :action="'/attendance/' + editAttendance.id" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Attendance Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="attendance_date" required x-model="editAttendance.attendance_date"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Clock In Time</label>
                            <input type="time" name="clock_in" x-model="editAttendance.clock_in"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Clock Out Time</label>
                            <input type="time" name="clock_out" x-model="editAttendance.clock_out"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Hours</label>
                            <input type="number" name="total_hours" step="0.01" min="0" x-model="editAttendance.total_hours"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required x-model="editAttendance.status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="half-day">Half Day</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" rows="3" x-model="editAttendance.notes"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 resize-none"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" @click="editModal = false"
                            class="px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 shadow-lg">
                            Update Attendance
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>

@endsection
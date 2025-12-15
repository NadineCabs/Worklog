@extends('layouts.app')

@section('title', 'Manage Shifts')

@section('content')

<div x-data="{ addModal: {{ $errors->any() && !isset($shift) ? 'true' : 'false' }}, editModal: {{ $errors->any() && isset($shift) ? 'true' : 'false' }}, editShift: {} }">

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Shift Management</h2>
            <p class="text-gray-500 mt-1">Manage and define employee work shifts.</p>
        </div>
        
        <button 
            @click="addModal = true"
            type="button" 
            class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-all duration-200 hover:shadow-xl flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            <span>Add New Shift</span>
        </button>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Employee
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Shift Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Start Time
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            End Time
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Duration
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
    
                    @forelse($shifts ?? [] as $shift)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-indigo-600 font-semibold text-sm">
                                        @if(isset($shift->employee))
                                            {{ strtoupper(substr($shift->employee->first_name, 0, 1) . substr($shift->employee->last_name, 0, 1)) }}
                                        @else
                                            NA
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        @if(isset($shift->employee))
                                            {{ $shift->employee->first_name }} {{ $shift->employee->last_name }}
                                        @else
                                            No Employee Assigned
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @if(isset($shift->employee))
                                            {{ $shift->employee->employee_code }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $shift->shift_name }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $shift->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @php
                                $start = \Carbon\Carbon::parse($shift->start_time);
                                $end = \Carbon\Carbon::parse($shift->end_time);
                                $duration = $start->diffInHours($end);
                            @endphp
                            {{ $duration }} hours
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center space-x-3">
                                
                                <button 
                                    @click="editModal = true; editShift = {
                                        id: '{{ $shift->id }}',
                                        employee_id: '{{ $shift->employee_id }}',
                                        shift_name: '{{ $shift->shift_name }}',
                                        start_time: '{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}',
                                        end_time: '{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}'
                                    }"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors" 
                                    title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                
                                <form action="{{ route('shifts.destroy', $shift->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this shift assignment?')" class="inline">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">No shifts assigned yet</p>
                                <p class="text-gray-400 text-sm mt-1">Click "Add New Shift" to assign a shift to an employee</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        
    </div>

    <!-- ADD SHIFT MODAL -->
    <div 
        x-show="addModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
        style="display: none;"
        @click.self="addModal = false">

        <div x-show="addModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden"
             @click.stop>

            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white">Add New Shift</h2>
                    <button @click="addModal = false" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-8 py-6">
                
                @if($errors->any() && !isset($shift))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="font-semibold">Please fix the following errors:</p>
                                <ul class="list-disc list-inside mt-1 text-sm">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('shifts.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Employee Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Select Employee <span class="text-red-500">*</span>
                        </label>
                        <select name="employee_id" required
                                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all @error('employee_id') border-red-500 @enderror">
                            <option value="">Select Employee</option>
                            @if(isset($employees) && $employees->count() > 0)
                                @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }} - {{ $employee->employee_code }}
                                </option>
                                @endforeach
                            @else
                                <option value="" disabled>No employees available</option>
                            @endif
                        </select>
                        @error('employee_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Shift Name <span class="text-red-500">*</span>
                        </label>
                        <select name="shift_name" required
                                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all @error('shift_name') border-red-500 @enderror">
                            <option value="">Select Shift</option>
                            <option value="Morning Shift" {{ old('shift_name') == 'Morning Shift' ? 'selected' : '' }}>Morning Shift</option>
                            <option value="Night Shift" {{ old('shift_name') == 'Night Shift' ? 'selected' : '' }}>Night Shift</option>
                        </select>
                        @error('shift_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="start_time" required value="{{ old('start_time') }}"
                                   class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all @error('start_time') border-red-500 @enderror">
                            @error('start_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                End Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="end_time" required value="{{ old('end_time') }}"
                                   class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all @error('end_time') border-red-500 @enderror">
                            @error('end_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" @click="addModal = false"
                            class="px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-200">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 shadow-lg hover:shadow-xl transition-all duration-200">
                            Create Shift
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- EDIT SHIFT MODAL -->
    <div 
        x-show="editModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
        style="display: none;"
        @click.self="editModal = false">

        <div x-show="editModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden"
             @click.stop>

            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white">Edit Shift</h2>
                    <button @click="editModal = false" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-8 py-6">
                
                <form :action="'/shifts/' + editShift.id" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Employee Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Select Employee <span class="text-red-500">*</span>
                        </label>
                        <select name="employee_id" required x-model="editShift.employee_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            <option value="">Select Employee</option>
                            @if(isset($employees) && $employees->count() > 0)
                                @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->first_name }} {{ $employee->last_name }} - {{ $employee->employee_code }}
                                </option>
                                @endforeach
                            @else
                                <option value="" disabled>No employees available</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Shift Name <span class="text-red-500">*</span>
                        </label>
                        <select name="shift_name" required x-model="editShift.shift_name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            <option value="">Select Shift</option>
                            <option value="Morning Shift">Morning Shift</option>
                            <option value="Night Shift">Night Shift</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="start_time" required x-model="editShift.start_time"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                End Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="end_time" required x-model="editShift.end_time"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" @click="editModal = false"
                            class="px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-200">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 shadow-lg hover:shadow-xl transition-all duration-200">
                            Update Shift
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>

@endsection
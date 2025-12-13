<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Worklog - @yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-poppins">
    
    <div x-data="{ sidebarOpen: true, sidebarCollapsed: false }" class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside 
            :class="[
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                sidebarCollapsed ? 'lg:w-20' : 'lg:w-64'
            ]"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-teal-700 text-white transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col"
        >
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 bg-teal-800">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-teal-700 font-bold text-lg">W</span>
                    </div>
                    <h1 
                        x-show="!sidebarCollapsed" 
                        x-transition
                        class="text-xl font-bold whitespace-nowrap"
                    >
                        WorkLog
                    </h1>
                </div>
                <button 
                    @click="sidebarCollapsed = !sidebarCollapsed" 
                    class="hidden lg:flex items-center justify-center w-10 h-10 bg-teal-900 text-white hover:bg-teal-950 rounded-lg transition-all duration-300 ml-2"
                >
                    <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('dashboard') }}" 
                   @click="sidebarOpen = false"
                   :title="sidebarCollapsed ? 'Dashboard' : ''"
                   class="group flex items-center px-4 py-3 rounded-lg transition-all duration-300 ease-in-out {{ request()->routeIs('dashboard') ? 'bg-teal-600 text-white' : 'text-teal-100 hover:bg-teal-600 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 transition-all duration-300 ease-in-out group-hover:scale-110" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span 
                        x-show="!sidebarCollapsed" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-x-2"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="font-medium whitespace-nowrap"
                    >
                        Dashboard
                    </span>
                </a>
                
                <a href="{{ route('employees.index') }}" 
                   @click="sidebarOpen = false"
                   :title="sidebarCollapsed ? 'Employees' : ''"
                   class="group flex items-center px-4 py-3 rounded-lg transition-all duration-300 ease-in-out {{ request()->routeIs('employees.*') ? 'bg-teal-600 text-white' : 'text-teal-100 hover:bg-teal-600 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 transition-all duration-300 ease-in-out group-hover:scale-110" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span 
                        x-show="!sidebarCollapsed" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-x-2"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="font-medium whitespace-nowrap"
                    >
                        Employees
                    </span>
                </a>
                
                <a href="{{ route('attendance.index') }}" 
                   @click="sidebarOpen = false"
                   :title="sidebarCollapsed ? 'Attendance' : ''"
                   class="group flex items-center px-4 py-3 rounded-lg transition-all duration-300 ease-in-out {{ request()->routeIs('attendance.*') ? 'bg-teal-600 text-white' : 'text-teal-100 hover:bg-teal-600 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 transition-all duration-300 ease-in-out group-hover:scale-110" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span 
                        x-show="!sidebarCollapsed" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-x-2"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="font-medium whitespace-nowrap"
                    >
                        Attendance
                    </span>
                </a>
                
                <a href="{{ route('leave.index') }}" 
                   @click="sidebarOpen = false"
                   :title="sidebarCollapsed ? 'Leaves' : ''"
                   class="group flex items-center px-4 py-3 rounded-lg transition-all duration-300 ease-in-out {{ request()->routeIs('leave.*') ? 'bg-teal-600 text-white' : 'text-teal-100 hover:bg-teal-600 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 transition-all duration-300 ease-in-out group-hover:scale-110" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span 
                        x-show="!sidebarCollapsed" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-x-2"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="font-medium whitespace-nowrap"
                    >
                        Leaves
                    </span>
                </a>
                
                <a href="{{ route('users.index') }}" 
                   @click="sidebarOpen = false"
                   :title="sidebarCollapsed ? 'Users' : ''"
                   class="group flex items-center px-4 py-3 rounded-lg transition-all duration-300 ease-in-out {{ request()->routeIs('users.*') ? 'bg-teal-600 text-white' : 'text-teal-100 hover:bg-teal-600 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 transition-all duration-300 ease-in-out group-hover:scale-110" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span 
                        x-show="!sidebarCollapsed" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-x-2"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="font-medium whitespace-nowrap"
                    >
                        Users
                    </span>
                </a>
                
                <a href="{{ route('shifts.index') }}" 
                   @click="sidebarOpen = false"
                   :title="sidebarCollapsed ? 'Shifts' : ''"
                   class="group flex items-center px-4 py-3 rounded-lg transition-all duration-300 ease-in-out {{ request()->routeIs('shifts.*') ? 'bg-teal-600 text-white' : 'text-teal-100 hover:bg-teal-600 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 transition-all duration-300 ease-in-out group-hover:scale-110" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span 
                        x-show="!sidebarCollapsed" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-x-2"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="font-medium whitespace-nowrap"
                    >
                        Shifts
                    </span>
                </a>
            </nav>
            
            <!-- Logout Button -->
            <div class="p-4 border-t border-teal-600">
                <div class="flex items-center mb-3" :class="sidebarCollapsed ? 'justify-center' : 'px-4'">
                    <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-teal-700 font-semibold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                    </div>
                    <div 
                        x-show="!sidebarCollapsed" 
                        x-transition
                        class="ml-3"
                    >
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-teal-200">Administrator</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button 
                        type="submit" 
                        :title="sidebarCollapsed ? 'Logout' : ''"
                        class="flex items-center w-full px-4 py-3 text-teal-100 rounded-lg hover:bg-teal-600 hover:text-white transition-colors"
                        :class="sidebarCollapsed ? 'justify-center' : ''"
                    >
                        <svg class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span 
                            x-show="!sidebarCollapsed" 
                            x-transition
                            class="font-medium whitespace-nowrap"
                        >
                            Logout
                        </span>
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Mobile sidebar overlay -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
            style="display: none;"
        ></div>
        
        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
        
                <!-- Toggle Sidebar Button (Mobile) -->
                <button 
                    @click="sidebarOpen = !sidebarOpen" 
                    class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <div class="hidden lg:block"></div>
                
                <!-- User Info -->
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center">
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6">
                
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worklog - Login</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center p-5 font-poppins">
    
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-10">
            
            <!-- Logo/Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-teal-600 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        <path d="M13 7a1 1 0 11-2 0 1 1 0 012 0zM9 7a1 1 0 11-2 0 1 1 0 012 0z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Title -->
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-2">Worklog!</h1>
            <p class="text-center text-gray-500 text-sm mb-8">Secure Login</p>
            
            <!-- Error Messages -->
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    {{ session('error') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                
                <!-- Username/Email Field -->
                <div class="mb-5">
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition text-gray-700 placeholder-gray-400"
                        placeholder="Username"
                        required 
                        autofocus
                    >
                </div>
                
                <!-- Password Field -->
                <div class="mb-4">
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition text-gray-700 placeholder-gray-400"
                        placeholder="Password"
                        required
                    >
                </div>
                
                <!-- Forgot Password Link -->
                <div class="text-right mb-6">
                    <a href="#" onclick="alert('Contact your administrator to reset password'); return false;" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                        Forgot Password?
                    </a>
                </div>
                
                <!-- Login Button -->
                <button 
                    type="submit"
                    class="w-full bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition transform hover:scale-[1.02] active:scale-[0.98]"
                >
                    Login
                </button>
            </form>
            
        </div>
        
        <!-- Footer -->
        <p class="text-center mt-6 text-sm text-gray-500">
            © 2025 Worklog. All rights reserved.
        </p>
    </div>
    
</body>
</html>
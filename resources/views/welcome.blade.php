<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo/logo.png') }}">

        <!-- Styles -->
        <script src="https://cdn.tailwindcss.com"></script>

        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="antialiased" x-data="{ 
        open: false, 
        loginOpen: false, 
        registerOpen: false, 
        forgotPasswordOpen: false,
        loading: false,
        errorModal: false,
        successModal: false,
        errorTitle: '',
        errorMessage: '',
        successTitle: '',
        successMessage: '',
        showError(title, message) {
            this.errorTitle = title;
            this.errorMessage = message;
            this.errorModal = true;
        },
        showSuccess(title, message) {
            this.successTitle = title;
            this.successMessage = message;
            this.successModal = true;
        }
    }">
        <!-- Navbar -->
        <nav class="fixed w-full bg-white/80 backdrop-blur-md z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Logo dan Nama Perusahaan -->
                    <div class="flex items-center">
                        <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" class="h-6 sm:h-8">
                        <span class="ml-2 text-sm sm:text-xl font-bold text-[#0066cc] truncate max-w-[200px] sm:max-w-none">
                            PT. Automotive Fasteners Aoyama Indonesia
                        </span>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden sm:flex items-center gap-4">
                        <button @click="loginOpen = true" class="px-4 py-2 text-[#0066cc] hover:text-[#004d99] transition">
                            Login
                        </button>
                        <button @click="registerOpen = true" class="px-4 py-2 bg-[#0066cc] text-white rounded-lg hover:bg-[#004d99] transition">
                            Register
                        </button>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex sm:hidden">
                        <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-[#0066cc] hover:text-[#004d99] hover:bg-blue-50 focus:outline-none">
                            <svg class="h-6 w-6" x-show="!open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="h-6 w-6" x-show="open" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="open" class="sm:hidden bg-white border-t">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <button @click="loginOpen = true" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-[#0066cc] hover:text-[#004d99] hover:bg-blue-50 transition">
                        Login
                    </button>
                    <button @click="registerOpen = true" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-[#0066cc] hover:text-[#004d99] hover:bg-blue-50 transition">
                        Register
                    </button>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative min-h-screen bg-gradient-to-br from-[#0066cc]/5 via-white to-[#0066cc]/10">
            <!-- Tambahkan overlay pattern untuk tekstur -->
            <div class="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] dark:bg-grid-slate-700/25 dark:[mask-image:linear-gradient(0deg,rgba(255,255,255,0.1),rgba(255,255,255,0.5))]"></div>
            
            <!-- Tambahkan blur effect -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#0066cc]/10 to-transparent blur-3xl"></div>
            
            <!-- Content -->
            <div class="relative"> <!-- Tambahkan relative untuk konten di atas background -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 sm:pt-32 pb-12 sm:pb-20">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                        <div class="text-center lg:text-left">
                            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-4 sm:mb-6">
                                Welcome To <span class="text-[#0066cc]">Material Database Control</span>
                            </h1>
                            <p class="text-base sm:text-xl text-gray-600 mb-6 sm:mb-8 px-4 sm:px-0">
                                Integrated material management and control system to support efficient and accurate automotive fastener production processes
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                <button @click="registerOpen = true" 
                                        class="px-6 sm:px-8 py-3 bg-[#0066cc] text-white rounded-lg transition shadow-lg hover:shadow-xl text-sm sm:text-base transform hover:scale-105 hover:bg-[#004d99] duration-300">
                                    Get Started
                                </button>
                                <a href="#features" class="px-6 sm:px-8 py-3 border border-[#0066cc] text-[#0066cc] rounded-lg hover:bg-blue-50 transition text-sm sm:text-base">
                                    Learn More
                                </a>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <img src="{{ asset('images/logo/logo.png') }}" alt="Hero Image" class="w-full max-w-md mx-auto">
                        </div>
                    </div>
                </div>

                <!-- Features Section dengan background yang sedikit berbeda -->
                <div id="features" class="relative bg-gradient-to-t from-white via-[#0066cc]/5 to-transparent">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
                        <div class="text-center mb-12 sm:mb-16">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 sm:mb-4">System Features</h2>
                            <p class="text-base sm:text-xl text-gray-600 px-4 sm:px-0">Complete solution for automotive fastener material control</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                            <div class="p-4 sm:p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition">
                                <div class="w-10 sm:w-12 h-10 sm:h-12 bg-[#e6f0ff] rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-5 sm:w-6 h-5 sm:h-6 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Controll Fortcast</h3>
                                <p class="text-sm sm:text-base text-gray-600">Control fastener material stock with accurate and structured recording system</p>
                            </div>

                            <div class="p-4 sm:p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition">
                                <div class="w-10 sm:w-12 h-10 sm:h-12 bg-[#e6f0ff] rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-5 sm:w-6 h-5 sm:h-6 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Controll PO</h3>
                                <p class="text-sm sm:text-base text-gray-600">Monitor material movement and analyze data in real-time for precise decision making</p>
                            </div>

                            <div class="p-4 sm:p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition">
                                <div class="w-10 sm:w-12 h-10 sm:h-12 bg-[#e6f0ff] rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-5 sm:w-6 h-5 sm:h-6 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Issue Lot Id Tag</h3>
                                <p class="text-sm sm:text-base text-gray-600">Automate documentation and reporting processes for efficient material management</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gradient-to-b from-[#0066cc] to-[#004d99] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="text-center sm:text-left">
                        <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" class="h-6 sm:h-8 mb-4 mx-auto sm:mx-0">
                        <p class="text-sm sm:text-base text-gray-300">Material database control system for automotive fastener industry</p>
                    </div>
                    <div class="text-center sm:text-left">
                        <h4 class="text-base sm:text-lg font-semibold mb-4">Contact</h4>
                        <p class="text-sm sm:text-base text-gray-300">Email: contact@afi.co.id</p>
                        <p class="text-sm sm:text-base text-gray-300">Phone: (021) 898-0456</p>
                    </div>
                    <div class="text-center lg:text-left">
                        <h4 class="text-base sm:text-lg font-semibold mb-4">Address</h4>
                        <p class="text-sm sm:text-base text-gray-300">
                            MM2100 Industrial Town<br>
                            Jl. Sulawesi II Blok F-4<br>
                            Cikarang Barat, Bekasi 17530
                        </p>
                    </div>
                </div>
                <div class="border-t border-blue-800 mt-8 pt-8 text-center">
                    <p class="text-sm sm:text-base text-gray-300">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Pastikan Alpine.js dimuat -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Tambahkan style untuk grid pattern -->
        <style>
            .bg-grid-slate-100 {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(51 65 85 / 0.1)'%3E%3Cpath d='M0 .5H31.5V32'/%3E%3C/svg%3E");
            }
        </style>

        <!-- Login Modal -->
        <div x-show="loginOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             @keydown.escape.window="loginOpen = false">
            
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <!-- Background Image -->
                    <div class="absolute inset-0 bg-cover bg-center z-0" 
                         style="background-image: url('{{ asset('images/bg/bg.jpg') }}');">
                        <div class="absolute inset-0 bg-gradient-to-b from-blue-900/90 to-black/90 backdrop-blur-sm"></div>
                    </div>

                    <!-- Content -->
                    <div class="relative z-10 p-8">
                        <div class="text-center mb-6">
                            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
                            <h2 class="text-2xl font-bold text-white">Welcome Back!</h2>
                            <p class="text-blue-200 mt-1">Please sign in to your account</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="nik" class="block text-sm font-medium text-white mb-1">NIK</label>
                                <input type="text" name="nik" id="nik" 
                                       class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/50"
                                       placeholder="Enter your NIK" required>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-white mb-1">Password</label>
                                <input type="password" name="password" id="password" 
                                       class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/50"
                                       placeholder="Enter your password" required>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <label class="flex items-center text-white">
                                    <input type="checkbox" name="remember" class="rounded border-white/20 bg-white/10 text-blue-600">
                                    <span class="ml-2">Remember me</span>
                                </label>
                                <button type="button" @click="loginOpen = false; forgotPasswordOpen = true" 
                                        class="text-blue-300 hover:text-blue-200">
                                    Forgot password?
                                </button>
                            </div>

                            <button type="submit" 
                                    class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200">
                                Sign in
                            </button>
                        </form>

                        <div class="mt-6 text-center text-white">
                            <p class="text-sm">
                                Don't have an account? 
                                <button @click="loginOpen = false; registerOpen = true" 
                                        class="text-blue-300 hover:text-blue-200 font-medium">
                                    Create account
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Register Modal -->
        <div x-show="registerOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             @keydown.escape.window="registerOpen = false">
            
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <!-- Background Image -->
                    <div class="absolute inset-0 bg-cover bg-center z-0" 
                         style="background-image: url('{{ asset('images/bg/bg.jpg') }}');">
                        <div class="absolute inset-0 bg-gradient-to-b from-blue-900/90 to-black/90 backdrop-blur-sm"></div>
                    </div>

                    <!-- Content -->
                    <div class="relative z-10 p-8">
                        <div class="text-center mb-6">
                            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
                            <h2 class="text-2xl font-bold text-white">Create Account</h2>
                            <p class="text-blue-200 mt-1">Join us today!</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="space-y-4">
                            @csrf
                            <!-- Form fields similar to login but with additional fields -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-white mb-1">Name</label>
                                <input type="text" name="name" id="name" 
                                       class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/50"
                                       required>
                            </div>

                            <div>
                                <label for="nik" class="block text-sm font-medium text-white mb-1">NIK</label>
                                <input type="text" name="nik" id="nik" 
                                       class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/50"
                                       required>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-white mb-1">Email</label>
                                <input type="email" name="email" id="email" 
                                       class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/50"
                                       required>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-white mb-1">Password</label>
                                <input type="password" name="password" id="password" 
                                       class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/50"
                                       required>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-white mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/50"
                                       required>
                            </div>

                            <button type="submit" 
                                    class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200">
                                Register
                            </button>
                        </form>

                        <div class="mt-6 text-center text-white">
                            <p class="text-sm">
                                Already have an account? 
                                <button @click="registerOpen = false; loginOpen = true" 
                                        class="text-blue-300 hover:text-blue-200 font-medium">
                                    Sign in
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forgot Password Modal -->
        <div x-show="forgotPasswordOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             @keydown.escape.window="forgotPasswordOpen = false">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 transform transition-all">
                    <!-- Close Button -->
                    <div class="absolute right-4 top-4">
                        <button @click="forgotPasswordOpen = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" class="h-12 mx-auto mb-2">
                        <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
                        <p class="text-gray-600 mt-2 text-sm">
                            Enter your email and we'll send you instructions to reset your password
                        </p>
                    </div>
                    
                    <!-- Form -->
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="forgot-email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address
                            </label>
                            <input 
                                id="forgot-email" 
                                type="email" 
                                name="email" 
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-[#0066cc] focus:ring focus:ring-[#0066cc] focus:ring-opacity-50 transition-colors"
                                placeholder="Enter your email address"
                                required 
                                autofocus
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Message -->
                        @if (session('status'))
                            <div class="p-4 bg-green-50 border border-green-200 text-green-600 rounded-lg text-sm">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Buttons -->
                        <div class="flex flex-col gap-4">
                            <button type="submit" 
                                    class="w-full py-3 px-4 bg-[#0066cc] text-white rounded-lg hover:bg-[#004d99] focus:outline-none focus:ring-2 focus:ring-[#0066cc] focus:ring-opacity-50 transition-all transform hover:scale-[1.02] active:scale-[0.98] font-medium">
                                Send Reset Link
                            </button>

                            <button type="button" 
                                    @click="forgotPasswordOpen = false; loginOpen = true"
                                    class="text-center text-sm text-[#0066cc] hover:text-[#004d99] transition-colors">
                                Back to Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Loading Modal -->
        <div x-show="loading" 
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 flex flex-col items-center transform transition-all">
                    <!-- Loading Animation -->
                    <div class="w-16 h-16 mb-4">
                        <svg class="animate-spin w-full h-full text-[#0066cc]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-900 font-medium">Loading...</p>
                    <p class="text-sm text-gray-600 mt-1">Please wait while we process your request</p>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div x-show="errorModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;"
             @keydown.escape.window="errorModal = false">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full transform transition-all">
                    <div class="absolute right-4 top-4">
                        <button @click="errorModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                            <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2" x-text="errorTitle">Error</h3>
                        <p class="text-gray-600" x-text="errorMessage">An error occurred.</p>
                        
                        <div class="mt-6">
                            <button @click="errorModal = false" 
                                    class="w-full py-3 px-4 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div x-show="successModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;"
             @keydown.escape.window="successModal = false">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full transform transition-all">
                    <div class="absolute right-4 top-4">
                        <button @click="successModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                            <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2" x-text="successTitle">Success</h3>
                        <p class="text-gray-600" x-text="successMessage">Operation completed successfully.</p>
                        
                        <div class="mt-6">
                            <button @click="successModal = false" 
                                    class="w-full py-3 px-4 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                                Continue
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

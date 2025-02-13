<div class="w-64 bg-gray-800 min-h-screen p-4 fixed">
    <div class="flex items-center justify-center mb-8">
        <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" class="w-32">
    </div>

    <!-- Navigation Links -->
    <nav class="space-y-2">
        <a href="{{ route('suplayer.dashboard') }}"
            class="flex items-center space-x-2 py-2 px-4 rounded-lg hover:bg-gray-700 text-white {{ request()->routeIs('suplayer.dashboard') ? 'bg-gray-700' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('suplayer.material') }}"
            class="flex items-center space-x-2 py-2 px-4 rounded-lg hover:bg-gray-700 text-white {{ request()->routeIs('suplayer.material') ? 'bg-gray-700' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            <span>Control Database Material</span>
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit"
                class="w-full flex items-center space-x-2 py-2 px-4 rounded-lg hover:bg-gray-700 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</div>

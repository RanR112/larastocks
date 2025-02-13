<aside class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <!-- User Profile Section -->
        <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                        {{ auth()->user()->email }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="space-y-2 font-medium">
            <!-- Dashboard -->
            <li>
                <a href="/" class="flex items-center p-2 text-gray-900 rounded-lg group {{ request()->routeIs('staff.home') ? 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800' : 'hover:bg-gray-50' }}">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg {{ request()->routeIs('staff.home') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white' }} transition-colors duration-200">
                        <i class="fas fa-home text-lg"></i>
                    </div>
                    <span class="ml-3 flex-1">Dashboard</span>
                </a>
            </li>

            <!-- Ordering -->
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-gray-900 rounded-lg group {{ request()->routeIs('staff.ordering.*') || request()->routeIs('staff.deliv-note') || request()->routeIs('staff.forecast-supplier') ? 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800' : 'hover:bg-gray-50' }}"
                    aria-controls="dropdown-ordering"
                    data-collapse-toggle="dropdown-ordering">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg {{ request()->routeIs('staff.ordering.*') || request()->routeIs('staff.deliv-note') || request()->routeIs('staff.forecast-supplier') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white' }} transition-colors duration-200">
                        <i class="fas fa-shopping-cart text-lg"></i>
                    </div>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Ordering</span>
                    <i class="fas fa-chevron-down ml-2 text-sm transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </button>
                <ul id="dropdown-ordering" class="py-2 space-y-1 {{ request()->routeIs('staff.ordering.*') || request()->routeIs('staff.deliv-note') || request()->routeIs('staff.forecast-supplier') ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ route('staff.deliv-note') }}"
                            class="flex items-center p-2 pl-14 text-gray-900 rounded-lg {{ request()->routeIs('staff.deliv-note') ? 'bg-blue-50 text-blue-800' : 'hover:bg-gray-50' }} group">
                            <i class="fas fa-file-download w-4 h-4 {{ request()->routeIs('staff.delivery-note.download') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"></i>
                            <span class="ml-3">Download Delivery Note</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.forecast-supplier') }}"
                            class="flex items-center p-2 pl-14 text-gray-900 rounded-lg {{ request()->routeIs('staff.forecast-supplier') ? 'bg-blue-50 text-blue-800' : 'hover:bg-gray-50' }} group">
                            <i class="fas fa-file-download w-4 h-4 {{ request()->routeIs('staff.monthly-forecast.download') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"></i>
                            <span class="ml-3">Download Monthly Forecast</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Fraction -->
            <li>
                <a href="fraction" class="flex items-center p-2 text-gray-900 rounded-lg group {{ request()->routeIs('staff.fraction') ? 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800' : 'hover:bg-gray-50' }}">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg {{ request()->routeIs('staff.fraction') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white' }} transition-colors duration-200">
                        <i class="fas fa-puzzle-piece text-lg"></i>
                    </div>
                    <span class="ml-3 flex-1">Fraction</span>
                </a>
            </li>

            <!-- Material Section -->
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-gray-900 rounded-lg group {{ request()->routeIs('staff.material.*') || request()->is('po-no') ? 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800' : 'hover:bg-gray-50' }}"
                    aria-controls="dropdown-material"
                    data-collapse-toggle="dropdown-material">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg {{ request()->routeIs('staff.material.*') || request()->is('po-no') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white' }} transition-colors duration-200">
                        <i class="fas fa-boxes-stacked text-lg"></i>
                    </div>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Material</span>
                    <i class="fas fa-chevron-down ml-2 text-sm transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </button>
                <ul id="dropdown-material" class="py-2 space-y-1 {{ request()->routeIs('staff.material.*') || request()->is('po-no') ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ route('staff.material.list') }}"
                            class="flex items-center p-2 pl-14 text-gray-900 rounded-lg {{ request()->routeIs('staff.material.list') ? 'bg-blue-50 text-blue-800' : 'hover:bg-gray-50' }} group">
                            <i class="fas fa-box-open w-4 h-4 {{ request()->routeIs('staff.material.list') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"></i>
                            <span class="ml-3">Material List</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.po-no') }}"
                            class="flex items-center p-2 pl-14 text-gray-900 rounded-lg {{ request()->is('po-no') ? 'bg-blue-50 text-blue-800' : 'hover:bg-gray-50' }} group">
                            <i class="fas fa-file-invoice w-4 h-4 {{ request()->is('po-no') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"></i>
                            <span class="ml-3">PO No</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Master Data -->
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-gray-900 rounded-lg group {{ request()->routeIs('staff.supplier') || request()->routeIs('staff.user') || request()->routeIs('staff.controll-po') || request()->routeIs('staff.actual-receive') || request()->routeIs('staff.forecast') ? 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800' : 'hover:bg-gray-50' }}"
                    aria-controls="dropdown-master"
                    data-collapse-toggle="dropdown-master">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg {{ request()->routeIs('staff.supplier') || request()->routeIs('staff.user') || request()->routeIs('staff.controll-po') || request()->routeIs('staff.actual-receive') || request()->routeIs('staff.forecast') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white' }} transition-colors duration-200">
                        <i class="fas fa-database text-lg"></i>
                    </div>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Master Data</span>
                    <i class="fas fa-chevron-down ml-2 text-sm transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </button>
                <ul id="dropdown-master" class="py-2 space-y-1 {{ request()->routeIs('staff.supplier') || request()->routeIs('staff.user') || request()->routeIs('staff.controll-po') || request()->routeIs('staff.actual-receive') || request()->routeIs('staff.forecast') ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ route('staff.supplier') }}"
                            class="flex items-center p-2 pl-14 text-gray-900 rounded-lg {{ request()->routeIs('staff.supplier') ? 'bg-blue-50 text-blue-800' : 'hover:bg-gray-50' }} group">
                            <i class="fas fa-truck w-4 h-4 {{ request()->routeIs('staff.supplier') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"></i>
                            <span class="ml-3">Supplier</span>
                        </a>
                    </li>
                    <!-- Other menu items with similar styling -->
                    <li>
                        <a href="{{ route('staff.user') }}"
                            class="flex items-center p-2 pl-14 text-gray-900 rounded-lg {{ request()->routeIs('staff.user') ? 'bg-blue-50 text-blue-800' : 'hover:bg-gray-50' }} group">
                            <i class="fas fa-user w-4 h-4 {{ request()->routeIs('staff.user') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"></i>
                            <span class="ml-3">Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.controll-po') }}"
                            class="flex items-center p-2 pl-14 text-gray-900 rounded-lg {{ request()->routeIs('staff.controll-po') ? 'bg-blue-50 text-blue-800' : 'hover:bg-gray-50' }} group">
                            <i class="fas fa-file-contract w-4 h-4 {{ request()->routeIs('staff.controll-po') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"></i>
                            <span class="ml-3">Control PO</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.actual-receive') }}"
                            class="flex items-center p-2 pl-14 text-gray-900 rounded-lg {{ request()->routeIs('staff.actual-receive') ? 'bg-blue-50 text-blue-800' : 'hover:bg-gray-50' }} group">
                            <i class="fas fa-truck-ramp-box w-4 h-4 {{ request()->routeIs('staff.actual-receive') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"></i>
                            <span class="ml-3">Actual Receive</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.forecast') }}"
                            class="flex items-center p-2 pl-14 text-gray-900 rounded-lg {{ request()->routeIs('staff.forecast') ? 'bg-blue-50 text-blue-800' : 'hover:bg-gray-50' }} group">
                            <i class="fas fa-chart-line w-4 h-4 {{ request()->routeIs('staff.forecast') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"></i>
                            <span class="ml-3">Forecast</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Logout Button -->
        <div class="mt-6 px-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center p-2 text-gray-900 rounded-lg hover:bg-red-50 group">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-red-100 text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors duration-200">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                    </div>
                    <span class="ml-3 flex-1 text-left">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
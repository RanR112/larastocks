<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('suplayer.deliv-note') }}"
                    class="flex items-center p-2 text-gray-900 rounded-lg {{ request()->routeIs('suplayer.deliv-note') ? 'bg-blue-100' : 'hover:bg-blue-50' }} group">
                    <i class="fas fa-file-download w-5 h-5 text-blue-600"></i>
                    <span class="ml-3">Download Delivery Note</span>
                </a>
            </li>
            <li>
                <a href="{{ route('suplayer.forecast') }}"
                    class="flex items-center p-2 text-gray-900 rounded-lg {{ request()->routeIs('suplayer.forecast') ? 'bg-blue-100' : 'hover:bg-blue-50' }} group">
                    <i class="fas fa-chart-line w-5 h-5 text-blue-600"></i>
                    <span class="ml-3">Download Forecast</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

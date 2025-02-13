@extends('staff.index')

@section('main')
<div class="p-6 space-y-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-700 to-blue-600 rounded-2xl shadow-xl">
        <div class="p-8">
            <div class="flex items-center justify-between">
                <div class="text-white">
                    <h1 class="text-3xl font-bold">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                    <p class="mt-2 text-blue-100">Here's what's happening with your materials today.</p>
                </div>
                <div class="hidden md:block">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" class="h-16 w-auto">
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Materials -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-4 bg-blue-500 rounded-lg">
                        <i class="fas fa-boxes text-2xl text-white"></i>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-semibold text-gray-900">Total Materials</h3>
                        <div class="flex items-center mt-2">
                            <span class="text-3xl font-bold text-gray-900">{{ $material_count }}</span>
                            <span class="ml-2 text-sm text-green-600">
                                <i class="fas fa-arrow-up"></i> 12%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active PO -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-4 bg-indigo-500 rounded-lg">
                        <i class="fas fa-file-invoice text-2xl text-white"></i>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-semibold text-gray-900">Active PO</h3>
                        <div class="flex items-center mt-2">
                            <span class="text-3xl font-bold text-gray-900">{{ $po_count }}</span>
                            <span class="ml-2 text-sm text-green-600">
                                <i class="fas fa-arrow-up"></i> 8%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Suppliers -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-4 bg-purple-500 rounded-lg">
                        <i class="fas fa-truck-field text-2xl text-white"></i>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-semibold text-gray-900">Total Suppliers</h3>
                        <div class="flex items-center mt-2">
                            <span class="text-3xl font-bold text-gray-900">{{ $supplier_count }}</span>
                            <span class="ml-2 text-sm text-green-600">
                                <i class="fas fa-arrow-up"></i> 5%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Materials -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Recent Materials</h2>
                    <a href="{{ route('staff.material.list') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recent_materials as $material)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-box text-blue-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $material->material_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $material->supplier->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $material->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    No materials found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Upcoming Deliveries -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Upcoming Deliveries</h2>
                    <a href="{{ route('staff.actual-receive') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($upcoming_deliveries as $delivery)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-file-invoice text-indigo-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $delivery->noPo->po_name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $delivery->created_at->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $delivery->supplier->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    No upcoming deliveries
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('staff.index')

@section('main')
<div class="p-4 space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-700 to-blue-600 rounded-xl shadow-lg">
        <div class="p-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-white">
                    <h2 class="text-3xl font-bold">{{ $title ?? 'Forecast Management' }}</h2>
                    <p class="mt-2 text-blue-100">Monitor and analyze your material forecasts</p>
                </div>
                <div class="flex gap-2 mt-4 md:mt-0">
                    <a href="{{ route('staff.forecast.export.excel') }}?{{ http_build_query(request()->all()) }}" 
                        class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 font-semibold shadow-md">
                        <i class="fas fa-file-excel"></i>
                        <span>Export Excel</span>
                    </a>
                    <a href="{{ route('staff.forecast.export.pdf') }}?{{ http_build_query(request()->all()) }}" 
                        class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 font-semibold shadow-md">
                        <i class="fas fa-file-pdf"></i>
                        <span>Export PDF</span>
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-blue-800 bg-opacity-50 rounded-lg p-4 text-white">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-500 rounded-lg">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-200">Total Materials</p>
                            <h4 class="text-2xl font-bold">{{ count($fortcasts) }}</h4>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-800 bg-opacity-50 rounded-lg p-4 text-white">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-500 rounded-lg">
                            <i class="fas fa-balance-scale text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-200">Average Balance</p>
                            <h4 class="text-2xl font-bold">
                                @php
                                    $avgBalance = collect($fortcasts)->avg(function($item) {
                                        return floatval(str_replace(',', '', $item['balance']));
                                    });
                                @endphp
                                {{ number_format($avgBalance ?? 0, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-800 bg-opacity-50 rounded-lg p-4 text-white">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-500 rounded-lg">
                            <i class="fas fa-percentage text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-200">Average Completion</p>
                            <h4 class="text-2xl font-bold">
                                @php
                                    $avgPercentage = collect($fortcasts)->avg(function($item) {
                                        return floatval(str_replace(['%', ','], '', $item['percentage']));
                                    });
                                @endphp
                                {{ number_format($avgPercentage ?? 0, 2) }}%
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <!-- Filter Section -->
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <form action="{{ route('staff.forecast') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Search by material name, diameter..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <select name="supplier_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <select name="month" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Months</option>
                            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Material Name</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Ø</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Std QTY</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Month</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">PO</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">ACTUAL</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">BALANCE</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">%</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Kanban Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($fortcasts as $fortcast)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $fortcast['material_name'] }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $fortcast['diameter'] }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ number_format($fortcast['std_qty'], 0) }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $fortcast['month'] }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ number_format($fortcast['po'], 0) }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ number_format($fortcast['actual'], 0) }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ number_format($fortcast['balance'], 0) }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $fortcast['percentage'] }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ number_format($fortcast['kanban'], 0) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-chart-line text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No forecast data found</p>
                                    <p class="text-sm text-gray-400">Try adjusting your search or filter to find what you're looking for.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

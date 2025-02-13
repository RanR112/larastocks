@extends('staff.index')

@section('main')
<div class="p-4 space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-700 to-blue-600 rounded-xl shadow-lg">
        <div class="p-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-white">
                    <h2 class="text-3xl font-bold">Actual Receive Management</h2>
                    <p class="mt-2 text-blue-100">Track and manage your material receiving process</p>
                </div>
                <div class="flex gap-2 mt-4 md:mt-0">
                    <button onclick="openCreateModal()" 
                        class="px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 font-semibold shadow-md">
                        <i class="fas fa-plus"></i>
                        <span>Add New Receive</span>
                    </button>
                    <a href="{{ route('staff.actual-receive.export.excel') }}?{{ http_build_query(request()->all()) }}" 
                        class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 font-semibold shadow-md">
                        <i class="fas fa-file-excel"></i>
                        <span>Export Excel</span>
                    </a>
                    <a href="{{ route('staff.actual-receive.export.pdf') }}?{{ http_build_query(request()->all()) }}" 
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
                            <i class="fas fa-box-open text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-200">Total Receives</p>
                            <h4 class="text-2xl font-bold">{{ $actualReceives->total() }}</h4>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-800 bg-opacity-50 rounded-lg p-4 text-white">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-500 rounded-lg">
                            <i class="fas fa-weight-hanging text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-200">Total Weight</p>
                            <h4 class="text-2xl font-bold">{{ number_format($actualReceives->sum('weight')) }} Kg</h4>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-800 bg-opacity-50 rounded-lg p-4 text-white">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-500 rounded-lg">
                            <i class="fas fa-layer-group text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-200">Total Coils</p>
                            <h4 class="text-2xl font-bold">{{ number_format($actualReceives->sum('total_coil')) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <form action="{{ route('staff.actual-receive') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search by delivery number, material..."
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

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">In Date</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Delivery Number</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Material</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Ø</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Weight</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Coils</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">PO No.</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($actualReceives as $actualReceive)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 py-4 text-sm text-gray-900">
                                <div class="font-medium">{{ $actualReceive->in_date->format('d M Y') }}</div>
                                <div class="text-gray-500 text-xs">{{ $actualReceive->in_date->format('l') }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $actualReceive->supplier->name }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                    {{ $actualReceive->delivery_number }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $actualReceive->material->material_name }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $actualReceive->materialDetail->diameter }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($actualReceive->weight) }} Kg</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($actualReceive->total_coil) }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $actualReceive->noPo->po_name }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <button onclick='openUpdateModal(@json($actualReceive))' 
                                        class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteActualReceive('{{ $actualReceive->id }}')" 
                                        class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No actual receives found</p>
                                    <p class="text-sm text-gray-400">Try adjusting your search or filter to find what you're looking for.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($actualReceives->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $actualReceives->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-[800px] shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-xl font-medium text-gray-900">Add New Actual Receive</h3>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="createForm" class="overflow-y-auto max-h-[70vh]">
            @csrf
                        <!-- Section: PO Information -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="text-md font-medium text-gray-700 mb-3">PO Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="no_po_id" class="block mb-2 text-sm font-medium text-gray-900">
                            PO Number <span class="text-red-500">*</span>
                        </label>
                        <select id="no_po_id" name="no_po_id" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select PO Number</option>
                            @foreach($noPos as $noPo)
                                <option value="{{ $noPo->id }}">{{ $noPo->po_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="control_po_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Control PO <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <select id="filter_month" 
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 w-1/3 p-2.5">
                                <option value="">Filter Month</option>
                                @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                    <option value="{{ $month }}">{{ $month }}</option>
                                @endforeach
                            </select>
                            <select id="control_po_id" name="control_po_id" required
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 flex-1 p-2.5">
                                <option value="">Select Control PO</option>
                                @foreach($controlPos as $controlPo)
                                    <option value="{{ $controlPo->id }}" data-month="{{ $controlPo->month }}">
                                        {{ $controlPo->noPo->po_name }} - {{ $controlPo->qty_coil }} Coil - {{ $controlPo->qty_kg }} Kg
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Basic Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="text-md font-medium text-gray-700 mb-3">Basic Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="in_date" class="block mb-2 text-sm font-medium text-gray-900">
                            In Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="in_date" name="in_date" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="delivery_number" class="block mb-2 text-sm font-medium text-gray-900">
                            Delivery Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="delivery_number" name="delivery_number" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>
                </div>
            </div>

            <!-- Section: Material Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="text-md font-medium text-gray-700 mb-3">Material Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="supplier_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Supplier <span class="text-red-500">*</span>
                        </label>
                        <select id="supplier_id" name="supplier_id" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="material_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Material <span class="text-red-500">*</span>
                        </label>
                        <select id="material_id" name="material_id" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Material</option>
                        </select>
                    </div>

                    <div>
                        <label for="material_detail_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Material Detail (Ø) <span class="text-red-500">*</span>
                        </label>
                        <select id="material_detail_id" name="material_detail_id" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Material Detail</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section: Quantity Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="text-md font-medium text-gray-700 mb-3">Quantity Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="weight" class="block mb-2 text-sm font-medium text-gray-900">
                            Weight (Kgs) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="weight" name="weight" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="total_coil" class="block mb-2 text-sm font-medium text-gray-900">
                            Total (Coil) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="total_coil" name="total_coil" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>
                </div>
            </div>

            <!-- Section: Additional Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="text-md font-medium text-gray-700 mb-3">Additional Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="charge_number" class="block mb-2 text-sm font-medium text-gray-900">
                            Charge Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="charge_number" name="charge_number" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="coil_no" class="block mb-2 text-sm font-medium text-gray-900">
                            Coil No. <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="coil_no" name="coil_no" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div class="col-span-2">
                        <label for="note" class="block mb-2 text-sm font-medium text-gray-900">
                            Note
                        </label>
                        <textarea id="note" name="note" rows="2"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
                <button type="button" onclick="closeCreateModal()" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Update Modal -->
<div id="updateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-[800px] shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-xl font-medium text-gray-900">Update Actual Receive</h3>
            <button type="button" onclick="closeUpdateModal()" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="updateForm" class="overflow-y-auto max-h-[70vh]">
            @csrf
            <input type="hidden" id="update_actual_receive_id">

                        <!-- Section: PO Information -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="text-md font-medium text-gray-700 mb-3">PO Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="update_no_po_id" class="block mb-2 text-sm font-medium text-gray-900">
                            PO Number <span class="text-red-500">*</span>
                        </label>
                        <select id="update_no_po_id" name="no_po_id" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select PO Number</option>
                            @foreach($noPos as $noPo)
                                <option value="{{ $noPo->id }}">{{ $noPo->po_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="update_control_po_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Control PO <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <select id="update_filter_month" 
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 w-1/3 p-2.5">
                                <option value="">Filter Month</option>
                                @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                    <option value="{{ $month }}">{{ $month }}</option>
                                @endforeach
                            </select>
                            <select id="update_control_po_id" name="control_po_id" required
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 flex-1 p-2.5">
                                <option value="">Select Control PO</option>
                                @foreach($controlPos as $controlPo)
                                    <option value="{{ $controlPo->id }}" data-month="{{ $controlPo->month }}">
                                        {{ $controlPo->noPo->po_name }} - {{ $controlPo->qty_coil }} Coil - {{ $controlPo->qty_kg }} Kg
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Basic Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="text-md font-medium text-gray-700 mb-3">Basic Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="update_in_date" class="block mb-2 text-sm font-medium text-gray-900">
                            In Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="update_in_date" name="in_date" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="update_delivery_number" class="block mb-2 text-sm font-medium text-gray-900">
                            Delivery Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="update_delivery_number" name="delivery_number" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>
                </div>
            </div>

            <!-- Section: Material Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="text-md font-medium text-gray-700 mb-3">Material Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="update_supplier_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Supplier <span class="text-red-500">*</span>
                        </label>
                        <select id="update_supplier_id" name="supplier_id" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="update_material_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Material <span class="text-red-500">*</span>
                        </label>
                        <select id="update_material_id" name="material_id" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Material</option>
                        </select>
                    </div>

                    <div>
                        <label for="update_material_detail_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Material Detail (Ø) <span class="text-red-500">*</span>
                        </label>
                        <select id="update_material_detail_id" name="material_detail_id" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Material Detail</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section: Quantity Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="text-md font-medium text-gray-700 mb-3">Quantity Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="update_weight" class="block mb-2 text-sm font-medium text-gray-900">
                            Weight (Kgs) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="update_weight" name="weight" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="update_total_coil" class="block mb-2 text-sm font-medium text-gray-900">
                            Total (Coil) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="update_total_coil" name="total_coil" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>
                </div>
            </div>

            <!-- Section: Additional Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="text-md font-medium text-gray-700 mb-3">Additional Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="update_charge_number" class="block mb-2 text-sm font-medium text-gray-900">
                            Charge Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="update_charge_number" name="charge_number" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="update_coil_no" class="block mb-2 text-sm font-medium text-gray-900">
                            Coil No. <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="update_coil_no" name="coil_no" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div class="col-span-2">
                        <label for="update_note" class="block mb-2 text-sm font-medium text-gray-900">
                            Note
                        </label>
                        <textarea id="update_note" name="note" rows="2"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
                <button type="button" onclick="closeUpdateModal()" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Filter material berdasarkan supplier yang dipilih
document.getElementById('supplier_id').addEventListener('change', function() {
    const supplierId = this.value;
    const materialSelect = document.getElementById('material_id');
    const materialDetailSelect = document.getElementById('material_detail_id');
    
    // Reset material dan material detail
    materialSelect.innerHTML = '<option value="">Select Material</option>';
    materialDetailSelect.innerHTML = '<option value="">Select Material Detail</option>';
    
    if (supplierId) {
        // Filter material berdasarkan supplier
        const filteredMaterials = @json($materials).filter(m => m.supplier_id === supplierId);
        filteredMaterials.forEach(material => {
            const option = new Option(material.material_name, material.id);
            materialSelect.add(option);
        });
    }
});

// Filter material detail berdasarkan material yang dipilih
document.getElementById('material_id').addEventListener('change', function() {
    const materialId = this.value;
    const materialDetailSelect = document.getElementById('material_detail_id');
    materialDetailSelect.innerHTML = '<option value="">Select Material Detail</option>';
    
    if (materialId) {
        const material = @json($materials).find(m => m.id === materialId);
        if (material && material.details) {
            material.details.forEach(detail => {
                const option = new Option(detail.diameter, detail.id);
                materialDetailSelect.add(option);
            });
        }
    }
});

// Handle form submission
document.getElementById('createForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    try {
        const response = await fetch('/actual-receive', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                in_date: document.getElementById('in_date').value,
                supplier_id: document.getElementById('supplier_id').value,
                material_id: document.getElementById('material_id').value,
                material_detail_id: document.getElementById('material_detail_id').value,
                delivery_number: document.getElementById('delivery_number').value,
                weight: document.getElementById('weight').value,
                total_coil: document.getElementById('total_coil').value,
                no_po_id: document.getElementById('no_po_id').value,
                control_po_id: document.getElementById('control_po_id').value,
                charge_number: document.getElementById('charge_number').value,
                coil_no: document.getElementById('coil_no').value,
                note: document.getElementById('note').value
            })
        });

        const data = await response.json();
        if (data.status === 'success') {
            closeCreateModal();
            window.location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to create Actual Receive');
    }
});

// Fungsi untuk membuka modal update
function openUpdateModal(actualReceive) {
    document.getElementById('update_in_date').value = actualReceive.in_date.split('T')[0];
    document.getElementById('update_supplier_id').value = actualReceive.supplier_id;
    document.getElementById('update_delivery_number').value = actualReceive.delivery_number;
    
    // Populate material options berdasarkan supplier
    const materialSelect = document.getElementById('update_material_id');
    materialSelect.innerHTML = '<option value="">Select Material</option>';
    const filteredMaterials = @json($materials).filter(m => m.supplier_id === actualReceive.supplier_id);
    filteredMaterials.forEach(material => {
        const option = new Option(material.material_name, material.id);
        materialSelect.add(option);
    });
    
    // Set selected material
    materialSelect.value = actualReceive.material_id;
    
    // Populate material detail options berdasarkan material
    const materialDetailSelect = document.getElementById('update_material_detail_id');
    materialDetailSelect.innerHTML = '<option value="">Select Material Detail</option>';
    const material = @json($materials).find(m => m.id === actualReceive.material_id);
    if (material && material.details) {
        material.details.forEach(detail => {
            const option = new Option(detail.diameter, detail.id);
            materialDetailSelect.add(option);
        });
    }
    
    // Set nilai-nilai lainnya
    document.getElementById('update_material_detail_id').value = actualReceive.material_detail_id;
    document.getElementById('update_weight').value = actualReceive.weight;
    document.getElementById('update_total_coil').value = actualReceive.total_coil;
    document.getElementById('update_no_po_id').value = actualReceive.no_po_id;
    document.getElementById('update_control_po_id').value = actualReceive.control_po_id;
    document.getElementById('update_charge_number').value = actualReceive.charge_number;
    document.getElementById('update_coil_no').value = actualReceive.coil_no;
    document.getElementById('update_note').value = actualReceive.note;
    document.getElementById('update_actual_receive_id').value = actualReceive.id;
    
    document.getElementById('updateModal').classList.remove('hidden');
}

// Fungsi untuk menutup modal update
function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
}

// Fungsi untuk menutup modal create
function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

// Filter material berdasarkan supplier untuk form update
document.getElementById('update_supplier_id').addEventListener('change', function() {
    const supplierId = this.value;
    const materialSelect = document.getElementById('update_material_id');
    const materialDetailSelect = document.getElementById('update_material_detail_id');
    
    materialSelect.innerHTML = '<option value="">Select Material</option>';
    materialDetailSelect.innerHTML = '<option value="">Select Material Detail</option>';
    
    if (supplierId) {
        const filteredMaterials = @json($materials).filter(m => m.supplier_id === supplierId);
        filteredMaterials.forEach(material => {
            const option = new Option(material.material_name, material.id);
            materialSelect.add(option);
        });
    }
});

// Filter material detail berdasarkan material untuk form update
document.getElementById('update_material_id').addEventListener('change', function() {
    const materialId = this.value;
    const materialDetailSelect = document.getElementById('update_material_detail_id');
    materialDetailSelect.innerHTML = '<option value="">Select Material Detail</option>';
    
    if (materialId) {
        const material = @json($materials).find(m => m.id === materialId);
        if (material && material.details) {
            material.details.forEach(detail => {
                const option = new Option(detail.diameter, detail.id);
                materialDetailSelect.add(option);
            });
        }
    }
});

// Handle update form submission
document.getElementById('updateForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const actualReceiveId = document.getElementById('update_actual_receive_id').value;
    
    try {
        const response = await fetch(`/actual-receive/${actualReceiveId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                in_date: document.getElementById('update_in_date').value,
                supplier_id: document.getElementById('update_supplier_id').value,
                material_id: document.getElementById('update_material_id').value,
                material_detail_id: document.getElementById('update_material_detail_id').value,
                delivery_number: document.getElementById('update_delivery_number').value,
                weight: document.getElementById('update_weight').value,
                total_coil: document.getElementById('update_total_coil').value,
                no_po_id: document.getElementById('update_no_po_id').value,
                control_po_id: document.getElementById('update_control_po_id').value,
                charge_number: document.getElementById('update_charge_number').value,
                coil_no: document.getElementById('update_coil_no').value,
                note: document.getElementById('update_note').value
            })
        });

        const data = await response.json();
        if (data.status === 'success') {
            closeUpdateModal();
            window.location.reload();
        } else {
            alert(data.message || 'Failed to update Actual Receive');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update Actual Receive: ' + error.message);
    }
});

// Fungsi untuk delete
async function deleteActualReceive(id) {
    if (confirm('Are you sure you want to delete this actual receive?')) {
        try {
            const response = await fetch(`/actual-receive/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });

            const data = await response.json();
            if (data.status === 'success') {
                window.location.reload();
            } else {
                alert(data.message || 'Failed to delete Actual Receive');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to delete Actual Receive: ' + error.message);
        }
    }
}

function openCreateModal() {
    // Reset form
    document.getElementById('createForm').reset();
    
    // Reset material dan material detail dropdowns
    document.getElementById('material_id').innerHTML = '<option value="">Select Material</option>';
    document.getElementById('material_detail_id').innerHTML = '<option value="">Select Material Detail</option>';
    
    // Tampilkan modal
    document.getElementById('createModal').classList.remove('hidden');
}

// Event listener untuk no_po_id di form create
document.getElementById('no_po_id').addEventListener('change', function() {
    const noPoId = this.value;
    const controlPoSelect = document.getElementById('control_po_id');
    controlPoSelect.innerHTML = '<option value="">Select Control PO</option>';
    
    if (noPoId) {
        const filteredControlPos = @json($controlPos)
            .filter(cp => cp.no_po_id === noPoId)
            .sort((a, b) => {
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                              'July', 'August', 'September', 'October', 'November', 'December'];
                return months.indexOf(a.month) - months.indexOf(b.month);
            });

        // Group by month
        const groupedByMonth = {};
        filteredControlPos.forEach(cp => {
            if (!groupedByMonth[cp.month]) {
                groupedByMonth[cp.month] = [];
            }
            groupedByMonth[cp.month].push(cp);
        });

        // Create optgroup for each month
        Object.keys(groupedByMonth).forEach(month => {
            const optgroup = document.createElement('optgroup');
            optgroup.label = getMonthAbbreviation(month);
            
            groupedByMonth[month].forEach(cp => {
                const option = new Option(
                    `[${getMonthAbbreviation(cp.month)}] ${cp.material.material_name} - ${cp.no_po.po_name} - ${cp.qty_coil} Coil - ${cp.qty_kg} Kg`,
                    cp.id
                );
                option.dataset.month = cp.month;
                optgroup.appendChild(option);
            });
            
            controlPoSelect.appendChild(optgroup);
        });
    }
});

// Event listener untuk no_po_id di form update
document.getElementById('update_no_po_id').addEventListener('change', function() {
    const noPoId = this.value;
    const controlPoSelect = document.getElementById('update_control_po_id');
    controlPoSelect.innerHTML = '<option value="">Select Control PO</option>';
    
    if (noPoId) {
        const filteredControlPos = @json($controlPos)
            .filter(cp => cp.no_po_id === noPoId)
            .sort((a, b) => {
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                              'July', 'August', 'September', 'October', 'November', 'December'];
                return months.indexOf(a.month) - months.indexOf(b.month);
            });

        // Group by month
        const groupedByMonth = {};
        filteredControlPos.forEach(cp => {
            if (!groupedByMonth[cp.month]) {
                groupedByMonth[cp.month] = [];
            }
            groupedByMonth[cp.month].push(cp);
        });

        // Create optgroup for each month
        Object.keys(groupedByMonth).forEach(month => {
            const optgroup = document.createElement('optgroup');
            optgroup.label = getMonthAbbreviation(month);
            
            groupedByMonth[month].forEach(cp => {
                const option = new Option(
                    `[${getMonthAbbreviation(cp.month)}] ${cp.material.material_name} - ${cp.no_po.po_name} - ${cp.qty_coil} Coil - ${cp.qty_kg} Kg`,
                    cp.id
                );
                option.dataset.month = cp.month;
                optgroup.appendChild(option);
            });
            
            controlPoSelect.appendChild(optgroup);
        });
    }
});

// Filter Control PO berdasarkan bulan di form create
document.getElementById('filter_month').addEventListener('change', function() {
    const selectedMonth = this.value;
    const noPoId = document.getElementById('no_po_id').value;
    const controlPoSelect = document.getElementById('control_po_id');
    
    // Reset dan populate ulang control PO berdasarkan filter
    controlPoSelect.innerHTML = '<option value="">Select Control PO</option>';
    
    if (noPoId) {
        const filteredControlPos = @json($controlPos)
            .filter(cp => cp.no_po_id === noPoId)
            .filter(cp => !selectedMonth || cp.month === selectedMonth)
            .sort((a, b) => {
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                              'July', 'August', 'September', 'October', 'November', 'December'];
                return months.indexOf(a.month) - months.indexOf(b.month);
            });

        // Group by month
        const groupedByMonth = {};
        filteredControlPos.forEach(cp => {
            if (!groupedByMonth[cp.month]) {
                groupedByMonth[cp.month] = [];
            }
            groupedByMonth[cp.month].push(cp);
        });

        // Create optgroup for each month
        Object.keys(groupedByMonth).forEach(month => {
            const optgroup = document.createElement('optgroup');
            optgroup.label = getMonthAbbreviation(month);
            
            groupedByMonth[month].forEach(cp => {
                const option = new Option(
                    `[${getMonthAbbreviation(cp.month)}] ${cp.material.material_name} - ${cp.no_po.po_name} - ${cp.qty_coil} Coil - ${cp.qty_kg} Kg`,
                    cp.id
                );
                option.dataset.month = cp.month;
                optgroup.appendChild(option);
            });
            
            controlPoSelect.appendChild(optgroup);
        });
    }
});

// Filter Control PO berdasarkan bulan di form update
document.getElementById('update_filter_month').addEventListener('change', function() {
    const selectedMonth = this.value;
    const noPoId = document.getElementById('update_no_po_id').value;
    const controlPoSelect = document.getElementById('update_control_po_id');
    
    // Reset dan populate ulang control PO berdasarkan filter
    controlPoSelect.innerHTML = '<option value="">Select Control PO</option>';
    
    if (noPoId) {
        const filteredControlPos = @json($controlPos)
            .filter(cp => cp.no_po_id === noPoId)
            .filter(cp => !selectedMonth || cp.month === selectedMonth)
            .sort((a, b) => {
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                              'July', 'August', 'September', 'October', 'November', 'December'];
                return months.indexOf(a.month) - months.indexOf(b.month);
            });

        // Group by month
        const groupedByMonth = {};
        filteredControlPos.forEach(cp => {
            if (!groupedByMonth[cp.month]) {
                groupedByMonth[cp.month] = [];
            }
            groupedByMonth[cp.month].push(cp);
        });

        // Create optgroup for each month
        Object.keys(groupedByMonth).forEach(month => {
            const optgroup = document.createElement('optgroup');
            optgroup.label = getMonthAbbreviation(month);
            
            groupedByMonth[month].forEach(cp => {
                const option = new Option(
                    `[${getMonthAbbreviation(cp.month)}] ${cp.material.material_name} - ${cp.no_po.po_name} - ${cp.qty_coil} Coil - ${cp.qty_kg} Kg`,
                    cp.id
                );
                option.dataset.month = cp.month;
                optgroup.appendChild(option);
            });
            
            controlPoSelect.appendChild(optgroup);
        });
    }
});

// Tambahkan fungsi helper untuk mendapatkan singkatan bulan
function getMonthAbbreviation(month) {
    const abbreviations = {
        'January': 'Jan',
        'February': 'Feb',
        'March': 'Mar',
        'April': 'Apr',
        'May': 'May',
        'June': 'Jun',
        'July': 'Jul',
        'August': 'Aug',
        'September': 'Sep',
        'October': 'Oct',
        'November': 'Nov',
        'December': 'Dec'
    };
    return abbreviations[month] || month;
}

// Event listener untuk control_po_id di form create
document.getElementById('control_po_id').addEventListener('change', function() {
    const controlPoId = this.value;
    const supplierSelect = document.getElementById('supplier_id');
    const materialSelect = document.getElementById('material_id');
    const materialDetailSelect = document.getElementById('material_detail_id');
    
    // Reset disabled state
    supplierSelect.disabled = !controlPoId;
    materialSelect.disabled = !controlPoId;
    materialDetailSelect.disabled = !controlPoId;
    
    if (controlPoId) {
        const controlPo = @json($controlPos).find(cp => cp.id === controlPoId);
        if (controlPo) {
            // Auto fill supplier
            supplierSelect.value = controlPo.material.supplier_id;
            supplierSelect.disabled = true;
            
            // Trigger change event untuk supplier_id untuk memuat materials
            supplierSelect.dispatchEvent(new Event('change'));
            
            // Tunggu sebentar untuk memastikan material options sudah dimuat
            setTimeout(() => {
                // Auto fill material
                materialSelect.value = controlPo.material_id;
                materialSelect.disabled = true;
                
                // Trigger change event untuk material_id untuk memuat material details
                materialSelect.dispatchEvent(new Event('change'));
                
                // Tunggu sebentar untuk memastikan material detail options sudah dimuat
                setTimeout(() => {
                    materialDetailSelect.value = controlPo.material_detail_id;
                    materialDetailSelect.disabled = true;
                }, 100);
            }, 100);
        }
    }
});

// Event listener untuk update_control_po_id di form update
document.getElementById('update_control_po_id').addEventListener('change', function() {
    const controlPoId = this.value;
    const supplierSelect = document.getElementById('update_supplier_id');
    const materialSelect = document.getElementById('update_material_id');
    const materialDetailSelect = document.getElementById('update_material_detail_id');
    
    // Reset disabled state
    supplierSelect.disabled = !controlPoId;
    materialSelect.disabled = !controlPoId;
    materialDetailSelect.disabled = !controlPoId;
    
    if (controlPoId) {
        const controlPo = @json($controlPos).find(cp => cp.id === controlPoId);
        if (controlPo) {
            // Auto fill supplier
            supplierSelect.value = controlPo.material.supplier_id;
            supplierSelect.disabled = true;
            
            // Trigger change event untuk supplier_id untuk memuat materials
            supplierSelect.dispatchEvent(new Event('change'));
            
            // Tunggu sebentar untuk memastikan material options sudah dimuat
            setTimeout(() => {
                // Auto fill material
                materialSelect.value = controlPo.material_id;
                materialSelect.disabled = true;
                
                // Trigger change event untuk material_id untuk memuat material details
                materialSelect.dispatchEvent(new Event('change'));
                
                // Tunggu sebentar untuk memastikan material detail options sudah dimuat
                setTimeout(() => {
                    materialDetailSelect.value = controlPo.material_detail_id;
                    materialDetailSelect.disabled = true;
                }, 100);
            }, 100);
        }
    }
});

// Tambahkan event listener untuk reset form ketika modal dibuka
function openCreateModal() {
    // Reset form
    document.getElementById('createForm').reset();
    
    // Reset material dan material detail dropdowns
    const materialSelect = document.getElementById('material_id');
    const materialDetailSelect = document.getElementById('material_detail_id');
    const supplierSelect = document.getElementById('supplier_id');
    
    materialSelect.innerHTML = '<option value="">Select Material</option>';
    materialDetailSelect.innerHTML = '<option value="">Select Material Detail</option>';
    
    // Enable semua select
    supplierSelect.disabled = false;
    materialSelect.disabled = false;
    materialDetailSelect.disabled = false;
    
    // Tampilkan modal
    document.getElementById('createModal').classList.remove('hidden');
}
</script>
@endpush
@endsection

@extends('staff.index')

@section('main')
<div class="p-4 space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-700 to-blue-600 rounded-xl shadow-lg">
        <div class="p-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-white">
                    <h2 class="text-3xl font-bold">Control PO Management</h2>
                    <p class="mt-2 text-blue-100">Track and manage your purchase orders</p>
                </div>
                <div class="flex gap-2 mt-4 md:mt-0">
                    <button onclick="openCreateModal()" 
                        class="px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 font-semibold shadow-md">
                        <i class="fas fa-plus"></i>
                        <span>Add Control PO</span>
                    </button>
                    <a href="{{ route('staff.controll-po.export.excel') }}?{{ http_build_query(request()->all()) }}" 
                        class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 font-semibold shadow-md">
                        <i class="fas fa-file-excel"></i>
                        <span>Export Excel</span>
                    </a>
                    <a href="{{ route('staff.controll-po.export.pdf') }}?{{ http_build_query(request()->all()) }}" 
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
                            <i class="fas fa-file-invoice text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-200">Total PO</p>
                            <h4 class="text-2xl font-bold">{{ $controlPos->total() }}</h4>
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
                            <h4 class="text-2xl font-bold">{{ number_format($controlPos->sum('qty_kg')) }} Kg</h4>
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
                            <h4 class="text-2xl font-bold">{{ number_format($controlPos->sum('total_coil')) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <form action="{{ route('staff.controll-po') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                        id="searchInput"
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search POs..."
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
            <div class="flex-1">
                <select name="material_receiving_status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="waiting" {{ request('material_receiving_status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                    <option value="received" {{ request('material_receiving_status') == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="rejected" {{ request('material_receiving_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            <a href="{{ route('staff.controll-po') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200 flex items-center gap-2">
                <i class="fas fa-undo"></i>
                <span>Reset</span>
            </a>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">PO Date</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Material Name</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Ø</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">STD Wire (Kg)</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">PO No.</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Schedule Incoming</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Order Qty (Coil)</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Order Qty (Kg)</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Month</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Total Coil</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Total KG</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Note</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($controlPos as $controlPo)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 py-4 text-sm text-gray-900">
                                <div class="font-medium">{{ date('d M Y', strtotime($controlPo->created_at)) }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $controlPo->supplier->name }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $controlPo->material->material_name }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $controlPo->materialDetail->diameter }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $controlPo->materialDetail->kg_coil }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $controlPo->noPo->po_name }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $controlPo->schedule_incoming }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ number_format($controlPo->qty_coil) }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ number_format($controlPo->qty_kg) }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $controlPo->month }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ number_format($controlPo->total_coil) }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ number_format($controlPo->total_kg) }}</td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($controlPo->material_receiving_status === 'waiting') bg-yellow-100 text-yellow-800
                                    @elseif($controlPo->material_receiving_status === 'received') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($controlPo->material_receiving_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $controlPo->notes }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-4">
                                    <button onclick='openUpdateModal(@json($controlPo))' 
                                        class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteControlPo('{{ $controlPo->id }}')" 
                                        class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="15" class="px-4 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-500 text-lg">No control POs found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adding a new control PO</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($controlPos->hasPages())
            <div class="mt-4">
                {{ $controlPos->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-start justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900">
                    Add New Control PO
                </h3>
                <button type="button" onclick="closeCreateModal()" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="createForm" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4">
                    <div>
                        <label for="supplier_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Supplier
                        </label>
                        <select id="supplier_id" name="supplier_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="material_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Material
                        </label>
                        <select id="material_id" name="material_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Material</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->material_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="material_detail_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Material Detail
                        </label>
                        <select id="material_detail_id" name="material_detail_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Material Detail</option>
                        </select>
                    </div>

                    <div>
                        <label for="no_po_id" class="block mb-2 text-sm font-medium text-gray-900">
                            PO Number
                        </label>
                        <select id="no_po_id" name="no_po_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select PO Number</option>
                            @foreach($noPos as $noPo)
                                <option value="{{ $noPo->id }}">{{ $noPo->po_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="schedule_incoming" class="block mb-2 text-sm font-medium text-gray-900">
                            Schedule Incoming
                        </label>
                        <input type="date" id="schedule_incoming" name="schedule_incoming" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="qty_coil" class="block mb-2 text-sm font-medium text-gray-900">
                            Quantity (Coil)
                        </label>
                        <input type="number" id="qty_coil" name="qty_coil" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="qty_kg" class="block mb-2 text-sm font-medium text-gray-900">
                            Quantity (KG)
                        </label>
                        <input type="number" id="qty_kg" name="qty_kg" 
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            readonly>
                    </div>

                    <div>
                        <label for="material_receiving_status" class="block mb-2 text-sm font-medium text-gray-900">
                            Material Receiving Status
                        </label>
                        <select id="material_receiving_status" name="material_receiving_status" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="waiting">Waiting</option>
                            <option value="received">Received</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div>
                        <label for="note" class="block mb-2 text-sm font-medium text-gray-900">
                            Note
                        </label>
                        <textarea id="note" name="note" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-4">
                    <button type="button" onclick="closeCreateModal()" class="text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div id="updateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-start justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900">
                    Update Control PO
                </h3>
                <button type="button" onclick="closeUpdateModal()" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateForm" class="p-4 md:p-5">
                @csrf
                <input type="hidden" id="update_control_po_id">
                <div class="grid gap-4 mb-4">
                    <div>
                        <label for="update_supplier_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Supplier
                        </label>
                        <select id="update_supplier_id" name="supplier_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="update_material_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Material
                        </label>
                        <select id="update_material_id" name="material_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Material</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->material_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="update_material_detail_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Material Detail
                        </label>
                        <select id="update_material_detail_id" name="material_detail_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select Material Detail</option>
                        </select>
                    </div>

                    <div>
                        <label for="update_no_po_id" class="block mb-2 text-sm font-medium text-gray-900">
                            PO Number
                        </label>
                        <select id="update_no_po_id" name="no_po_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="">Select PO Number</option>
                            @foreach($noPos as $noPo)
                                <option value="{{ $noPo->id }}">{{ $noPo->po_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="update_schedule_incoming" class="block mb-2 text-sm font-medium text-gray-900">
                            Schedule Incoming
                        </label>
                        <input type="date" id="update_schedule_incoming" name="schedule_incoming" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="update_qty_coil" class="block mb-2 text-sm font-medium text-gray-900">
                            Quantity (Coil)
                        </label>
                        <input type="number" id="update_qty_coil" name="qty_coil" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="update_qty_kg" class="block mb-2 text-sm font-medium text-gray-900">
                            Quantity (KG)
                        </label>
                        <input type="number" id="update_qty_kg" name="qty_kg" 
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            readonly>
                    </div>

                    <div>
                        <label for="update_material_receiving_status" class="block mb-2 text-sm font-medium text-gray-900">
                            Status
                        </label>
                        <select id="update_material_receiving_status" name="material_receiving_status" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                            <option value="waiting">Waiting</option>
                            <option value="received">Received</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-4">
                    <button type="button" onclick="closeUpdateModal()" class="text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// JavaScript untuk handle CRUD operations
function openCreateModal() {
    // Reset form
    document.getElementById('createForm').reset();
    // Reset material dan material detail dropdowns
    document.getElementById('material_id').innerHTML = '<option value="">Select Material</option>';
    document.getElementById('material_detail_id').innerHTML = '<option value="">Select Material Detail</option>';
    // Tampilkan modal
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openUpdateModal(controlPo) {
    document.getElementById('update_supplier_id').value = controlPo.supplier_id;
    document.getElementById('update_material_id').value = controlPo.material_id;
    
    // Populate material options berdasarkan supplier
    const materialSelect = document.getElementById('update_material_id');
    materialSelect.innerHTML = '<option value="">Select Material</option>';
    const filteredMaterials = @json($materials).filter(m => m.supplier_id === controlPo.supplier_id);
    filteredMaterials.forEach(material => {
        const option = new Option(material.material_name, material.id);
        materialSelect.add(option);
    });
    
    // Set selected material
    materialSelect.value = controlPo.material_id;
    
    // Populate material detail options berdasarkan material
    const materialDetailSelect = document.getElementById('update_material_detail_id');
    materialDetailSelect.innerHTML = '<option value="">Select Material Detail</option>';
    const material = @json($materials).find(m => m.id === controlPo.material_id);
    if (material && material.details) {
        material.details.forEach(detail => {
            const option = new Option(detail.diameter, detail.id);
            materialDetailSelect.add(option);
        });
    }
    
    // Format tanggal untuk input date
    const scheduleDate = new Date(controlPo.schedule_incoming);
    const formattedDate = scheduleDate.toISOString().split('T')[0];
    
    // Set nilai-nilai lainnya
    document.getElementById('update_material_detail_id').value = controlPo.material_detail_id;
    document.getElementById('update_no_po_id').value = controlPo.no_po_id;
    document.getElementById('update_schedule_incoming').value = formattedDate;
    document.getElementById('update_qty_coil').value = controlPo.qty_coil;
    document.getElementById('update_qty_kg').value = controlPo.qty_kg;
    document.getElementById('update_control_po_id').value = controlPo.id;
    
    document.getElementById('updateModal').classList.remove('hidden');
}

function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
}

// Handle create form submission
document.getElementById('createForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    try {
        const formData = new FormData(this);
        const requestData = {
            supplier_id: formData.get('supplier_id'),
            material_id: formData.get('material_id'),
            material_detail_id: formData.get('material_detail_id'),
            no_po_id: formData.get('no_po_id'),
            schedule_incoming: formData.get('schedule_incoming'),
            qty_coil: formData.get('qty_coil'),
            qty_kg: formData.get('qty_kg'),
            month: new Date(formData.get('schedule_incoming')).toLocaleString('en-US', { month: 'long' })
        };

        console.log('Request Data:', requestData); // Debugging

        const response = await fetch('/controll-po', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(requestData)
        });

        const data = await response.json();
        console.log('Response:', data); // Debugging

        if (data.status === 'success') {
            closeCreateModal();
            window.location.reload();
        } else {
            // Tampilkan pesan error yang lebih detail
            alert(data.message || 'Failed to create Control PO: ' + JSON.stringify(data.errors));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to create Control PO: ' + error.message);
    }
});

// Handle update form submission
document.getElementById('updateForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const controlPoId = document.getElementById('update_control_po_id').value;
    
    try {
        const requestData = {
            supplier_id: document.getElementById('update_supplier_id').value,
            material_id: document.getElementById('update_material_id').value,
            material_detail_id: document.getElementById('update_material_detail_id').value,
            no_po_id: document.getElementById('update_no_po_id').value,
            schedule_incoming: document.getElementById('update_schedule_incoming').value,
            qty_coil: document.getElementById('update_qty_coil').value,
            qty_kg: document.getElementById('update_qty_kg').value,
            month: new Date(document.getElementById('update_schedule_incoming').value).toLocaleString('en-US', { month: 'long' })
        };

        console.log('Request Data:', requestData); // Debugging

        const response = await fetch(`/controll-po/${controlPoId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(requestData)
        });

        const data = await response.json();
        console.log('Response:', data); // Debugging

        if (response.ok) {
            if (data.status === 'success') {
                closeUpdateModal();
                window.location.reload();
            } else {
                alert(data.message || 'Failed to update Control PO');
            }
        } else {
            // Handle validation errors
            if (data.errors) {
                const errorMessages = Object.values(data.errors).flat().join('\n');
                alert('Validation Error:\n' + errorMessages);
            } else {
                alert(data.message || 'Failed to update Control PO');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update Control PO: ' + error.message);
    }
});

async function deleteControlPo(id) {
    if (confirm('Are you sure you want to delete this control PO?')) {
        try {
            const response = await fetch(`/controll-po/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });

            const data = await response.json();
            if (data.status === 'success') {
                window.location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    const searchText = e.target.value.toLowerCase();
    const tableRows = document.querySelectorAll('tbody tr:not(.empty-row)');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchText) ? '' : 'none';
    });
});

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

// Filter untuk form update
document.getElementById('update_supplier_id').addEventListener('change', function() {
    const supplierId = this.value;
    const materialSelect = document.getElementById('update_material_id');
    const materialDetailSelect = document.getElementById('update_material_detail_id');
    
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

// Fungsi untuk menghitung qty_kg
function calculateQtyKg(materialDetailId, qtyCoil) {
    if (materialDetailId && qtyCoil) {
        const materials = @json($materials);
        let kgCoil = 0;
        
        // Cari kg_coil dari material detail yang dipilih
        materials.forEach(material => {
            if (material.details) {
                const detail = material.details.find(d => d.id === materialDetailId);
                if (detail) {
                    kgCoil = detail.kg_coil;
                }
            }
        });
        
        // Hitung qty_kg
        return kgCoil * qtyCoil;
    }
    return 0;
}

// Event listener untuk form create
document.getElementById('qty_coil').addEventListener('input', function() {
    const materialDetailId = document.getElementById('material_detail_id').value;
    const qtyCoil = this.value;
    document.getElementById('qty_kg').value = calculateQtyKg(materialDetailId, qtyCoil);
});

document.getElementById('material_detail_id').addEventListener('change', function() {
    const qtyCoil = document.getElementById('qty_coil').value;
    const materialDetailId = this.value;
    document.getElementById('qty_kg').value = calculateQtyKg(materialDetailId, qtyCoil);
});

// Event listener untuk form update
document.getElementById('update_qty_coil').addEventListener('input', function() {
    const materialDetailId = document.getElementById('update_material_detail_id').value;
    const qtyCoil = this.value;
    document.getElementById('update_qty_kg').value = calculateQtyKg(materialDetailId, qtyCoil);
});

document.getElementById('update_material_detail_id').addEventListener('change', function() {
    const qtyCoil = document.getElementById('update_qty_coil').value;
    const materialDetailId = this.value;
    document.getElementById('update_qty_kg').value = calculateQtyKg(materialDetailId, qtyCoil);
});
</script>
@endpush
@endsection

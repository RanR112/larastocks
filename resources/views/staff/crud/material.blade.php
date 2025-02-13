@extends('staff.index')

@section('main')
<div class="p-4">
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl md:text-2xl font-semibold text-gray-800">Material Management</h2>
                <p class="text-sm text-gray-600 mt-1">Manage your materials and their specifications</p>
            </div>
            <div class="flex gap-2">
                <!-- Export buttons -->
                <a href="{{ route('staff.material.export.pdf', ['supplier_id' => request('supplier_id')]) }}" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
                    <i class="fas fa-file-pdf"></i>
                    <span>Export PDF</span>
                </a>
                <a href="{{ route('staff.material.export.excel', ['supplier_id' => request('supplier_id')]) }}" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
                    <i class="fas fa-file-excel"></i>
                    <span>Export Excel</span>
                </a>
                <button onclick="openCreateModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Add Material</span>
                </button>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="searchInput" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" 
                    placeholder="Search materials...">
            </div>

            <!-- Filter Supplier -->
            <div>
                <form id="filterForm" class="flex gap-2">
                    <select name="supplier_id" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>
                    @if(request()->has('supplier_id') && request('supplier_id') != '')
                        <a href="{{ route('staff.material.list') }}" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Materials Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 md:px-6">Material Name</th>
                        <th scope="col" class="px-4 py-3 md:px-6">Supplier</th>
                        <th scope="col" class="px-4 py-3 md:px-6">Specifications</th>
                        <th scope="col" class="px-4 py-3 md:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-3 md:px-6">
                            <div class="font-medium text-gray-900">{{ $material->material_name }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-6">
                            <div class="text-gray-600">{{ $material->supplier->name }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-6">
                            <div class="flex flex-wrap gap-2">
                                @foreach($material->details as $detail)
                                <div class="group relative">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                                        {{ $detail->diameter }} - {{ $detail->kg_coil }}kg
                                    </span>
                                    <div class="absolute hidden group-hover:flex gap-1 top-0 right-0 -mt-1 -mr-1">
                                        <button onclick='openUpdateDetailModal(@json($detail))' 
                                            class="p-1 text-xs bg-blue-500 text-white rounded-full hover:bg-blue-600">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteDetail('{{ $detail->id }}')" 
                                            class="p-1 text-xs bg-red-500 text-white rounded-full hover:bg-red-600">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                <button onclick='openDetailModal(@json($material))' 
                                    class="px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 rounded-full border border-blue-600 hover:border-blue-800">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3 md:px-6">
                            <div class="flex items-center space-x-4">
                                <button onclick='openUpdateModal(@json($material))' 
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteMaterial('{{ $material->id }}')" 
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 md:px-6 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-box-open text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-500 text-lg">No materials found</p>
                                <p class="text-gray-400 text-sm mt-1">Try adding a new material</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if($materials->hasPages())
            <div class="px-4 py-3 bg-white border-t border-gray-200">
                {{ $materials->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Create Material -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-start justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900">
                    Add New Material
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
                        <label for="material_name" class="block mb-2 text-sm font-medium text-gray-900">
                            Material Name
                        </label>
                        <input type="text" id="material_name" name="material_name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Enter material name" required>
                    </div>
                    <div>
                        <label for="supplier_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Supplier
                        </label>
                        <select id="supplier_id" name="supplier_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-4 border-t">
                    <button type="button" onclick="closeCreateModal()" 
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Update Material -->
<div id="updateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-start justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900">
                    Edit Material
                </h3>
                <button type="button" onclick="closeUpdateModal()" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateForm" class="p-4 md:p-5">
                @csrf
                <input type="hidden" id="update_material_id">
                <div class="grid gap-4 mb-4">
                    <div>
                        <label for="update_material_name" class="block mb-2 text-sm font-medium text-gray-900">
                            Material Name
                        </label>
                        <input type="text" id="update_material_name" name="material_name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Enter material name" required>
                    </div>
                    <div>
                        <label for="update_supplier_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Supplier
                        </label>
                        <select id="update_supplier_id" name="supplier_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-4 border-t">
                    <button type="button" onclick="closeUpdateModal()" 
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Detail -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-start justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900">
                    Add Material Specification
                </h3>
                <button type="button" onclick="closeDetailModal()" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="detailForm" class="p-4 md:p-5">
                @csrf
                <input type="hidden" id="detail_material_id">
                <div class="grid gap-4 mb-4">
                    <div>
                        <label for="diameter" class="block mb-2 text-sm font-medium text-gray-900">
                            Diameter
                        </label>
                        <input type="text" id="diameter" name="diameter" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Enter diameter" required>
                    </div>
                    <div>
                        <label for="kg_coil" class="block mb-2 text-sm font-medium text-gray-900">
                            KG/Coil
                        </label>
                        <input type="number" id="kg_coil" name="kg_coil" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Enter KG/Coil" required>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-4 border-t">
                    <button type="button" onclick="closeDetailModal()" 
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Update Detail -->
<div id="updateDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-start justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900">
                    Edit Material Specification
                </h3>
                <button type="button" onclick="closeUpdateDetailModal()" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateDetailForm" class="p-4 md:p-5">
                @csrf
                <input type="hidden" id="update_detail_id">
                <div class="grid gap-4 mb-4">
                    <div>
                        <label for="update_diameter" class="block mb-2 text-sm font-medium text-gray-900">
                            Diameter
                        </label>
                        <input type="text" id="update_diameter" name="diameter" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Enter diameter" required>
                    </div>
                    <div>
                        <label for="update_kg_coil" class="block mb-2 text-sm font-medium text-gray-900">
                            KG/Coil
                        </label>
                        <input type="number" id="update_kg_coil" name="kg_coil" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Enter KG/Coil" required>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-4 border-t">
                    <button type="button" onclick="closeUpdateDetailModal()" 
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Modal functions
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('createForm').reset();
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openUpdateModal(material) {
    document.getElementById('update_material_id').value = material.id;
    document.getElementById('update_material_name').value = material.material_name;
    document.getElementById('update_supplier_id').value = material.supplier_id;
    document.getElementById('updateModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openDetailModal(material) {
    document.getElementById('detail_material_id').value = material.id;
    document.getElementById('detailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openUpdateDetailModal(detail) {
    document.getElementById('update_detail_id').value = detail.id;
    document.getElementById('update_diameter').value = detail.diameter;
    document.getElementById('update_kg_coil').value = detail.kg_coil;
    document.getElementById('updateDetailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUpdateDetailModal() {
    document.getElementById('updateDetailModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Form submissions
document.getElementById('createForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    try {
        const response = await fetch('/material', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                material_name: document.getElementById('material_name').value,
                supplier_id: document.getElementById('supplier_id').value,
            })
        });

        const data = await response.json();
        if (data.status === 'success') {
            closeCreateModal();
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

document.getElementById('updateForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const materialId = document.getElementById('update_material_id').value;
    
    try {
        const response = await fetch(`/material/${materialId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                material_name: document.getElementById('update_material_name').value,
                supplier_id: document.getElementById('update_supplier_id').value,
            })
        });

        const data = await response.json();
        if (data.status === 'success') {
            closeUpdateModal();
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

document.getElementById('detailForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const materialId = document.getElementById('detail_material_id').value;
    
    try {
        const response = await fetch(`/material/${materialId}/details`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                diameter: document.getElementById('diameter').value,
                kg_coil: document.getElementById('kg_coil').value,
            })
        });

        const data = await response.json();
        if (data.status === 'success') {
            closeDetailModal();
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

document.getElementById('updateDetailForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const detailId = document.getElementById('update_detail_id').value;
    
    try {
        const response = await fetch(`/material/details/${detailId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                diameter: document.getElementById('update_diameter').value,
                kg_coil: document.getElementById('update_kg_coil').value,
            })
        });

        const data = await response.json();
        if (data.status === 'success') {
            closeUpdateDetailModal();
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

async function deleteMaterial(id) {
    if (confirm('Are you sure you want to delete this material?')) {
        try {
            const response = await fetch(`/material/${id}`, {
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

async function deleteDetail(id) {
    if (confirm('Are you sure you want to delete this specification?')) {
        try {
            const response = await fetch(`/material/details/${id}`, {
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
    let hasResults = false;
    
    tableRows.forEach(row => {
        const materialName = row.querySelector('td:first-child').textContent.toLowerCase();
        const supplierName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        
        if (materialName.includes(searchText) || supplierName.includes(searchText)) {
            row.style.display = '';
            hasResults = true;
        } else {
            row.style.display = 'none';
        }
    });

    // Show/hide empty message
    const emptyMessage = document.querySelector('.empty-message');
    if (emptyMessage) {
        emptyMessage.style.display = hasResults ? 'none' : '';
    }
});

// Close modals when clicking outside
document.querySelectorAll('.fixed').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
});

// Close modals with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.fixed').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    }
});

// Instant filter when supplier changes
document.querySelector('select[name="supplier_id"]').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});
</script>
@endpush
@endsection

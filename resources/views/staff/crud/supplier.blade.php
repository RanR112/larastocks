@extends('staff.index')

@section('main')
<div class="p-4">
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl md:text-2xl font-semibold text-gray-800">Supplier Management</h2>
                <p class="text-sm text-gray-600 mt-1">Manage your suppliers here</p>
            </div>
            <div class="flex gap-2">
                <!-- Export buttons -->
                <a href="{{ route('staff.supplier.export.pdf') }}" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
                    <i class="fas fa-file-pdf"></i>
                    <span>Export PDF</span>
                </a>
                <a href="{{ route('staff.supplier.export.excel') }}" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
                    <i class="fas fa-file-excel"></i>
                    <span>Export Excel</span>
                </a>
                <button onclick="openCreateModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Add Supplier</span>
                </button>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="searchInput" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" 
                    placeholder="Search suppliers...">
            </div>
        </div>

        <!-- Table Container with Responsive Scroll -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 md:px-6">Supplier Name</th>
                        <th scope="col" class="px-4 py-3 md:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-3 md:px-6">
                            <div class="font-medium text-gray-900">{{ $supplier->name }}</div>
                        </td>
                        <td class="px-4 py-3 md:px-6">
                            <div class="flex items-center space-x-4">
                                <button onclick='openUpdateModal({!! json_encode($supplier) !!})' 
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteSupplier('{{ $supplier->id }}')" 
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
                                <p class="text-gray-500 text-lg">No suppliers found</p>
                                <p class="text-gray-400 text-sm mt-1">Try adding a new supplier</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if($suppliers->hasPages())
            <div class="px-4 py-3 bg-white border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $suppliers->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $suppliers->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $suppliers->total() }}</span>
                            results
                        </p>
                    </div>
                    <div class="flex justify-between flex-1 sm:justify-end">
                        @if($suppliers->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                                Previous
                            </span>
                        @else
                            <a href="{{ $suppliers->previousPageUrl() }}" 
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Previous
                            </a>
                        @endif

                        <div class="hidden md:flex mx-2">
                            @foreach ($suppliers->getUrlRange(1, $suppliers->lastPage()) as $page => $url)
                                <a href="{{ $url }}" 
                                    class="relative inline-flex items-center px-4 py-2 mx-1 text-sm font-medium {{ $page == $suppliers->currentPage() 
                                        ? 'text-blue-600 bg-blue-50 border border-blue-300' 
                                        : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50' }} rounded-md">
                                    {{ $page }}
                                </a>
                            @endforeach
                        </div>

                        @if($suppliers->hasMorePages())
                            <a href="{{ $suppliers->nextPageUrl() }}" 
                                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Next
                            </a>
                        @else
                            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                                Next
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Create -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-start justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900">
                    Add New Supplier
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
                        <label for="create_name" class="block mb-2 text-sm font-medium text-gray-900">
                            Supplier Name
                        </label>
                        <input type="text" id="create_name" name="name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Enter supplier name" required>
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

<!-- Modal Update -->
<div id="updateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-start justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900">
                    Edit Supplier
                </h3>
                <button type="button" onclick="closeUpdateModal()" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateForm" class="p-4 md:p-5">
                @csrf
                <input type="hidden" id="update_supplier_id">
                <div class="grid gap-4 mb-4">
                    <div>
                        <label for="update_name" class="block mb-2 text-sm font-medium text-gray-900">
                            Supplier Name
                        </label>
                        <input type="text" id="update_name" name="name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Enter supplier name" required>
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

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchText = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr:not(.empty-row)');
        let hasResults = false;
        
        tableRows.forEach(row => {
            const supplierName = row.querySelector('td:first-child').textContent.toLowerCase();
            if (supplierName.includes(searchText)) {
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

        // Hide/show pagination based on search results
        const paginationContainer = document.querySelector('.pagination-container');
        if (paginationContainer) {
            paginationContainer.style.display = searchText ? 'none' : '';
        }
    });

    // Fungsi untuk modal create
    function openCreateModal() {
        const modal = document.getElementById('createModal');
        modal.classList.remove('hidden');
        document.getElementById('createForm').reset();
        document.body.style.overflow = 'hidden';
        
        // Fokus ke input setelah modal muncul
        setTimeout(() => {
            document.getElementById('create_name').focus();
        }, 100);
    }

    function closeCreateModal() {
        const modal = document.getElementById('createModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Fungsi untuk modal update
    function openUpdateModal(supplier) {
        try {
            console.log('Opening update modal with supplier:', supplier);
            
            // Pastikan supplier adalah objek
            if (typeof supplier === 'string') {
                supplier = JSON.parse(supplier);
            }
            
            const modal = document.getElementById('updateModal');
            if (!modal) {
                console.error('Update modal not found');
                return;
            }

            const supplierIdInput = document.getElementById('update_supplier_id');
            const nameInput = document.getElementById('update_name');
            
            if (supplierIdInput && nameInput) {
                supplierIdInput.value = supplier.id;
                nameInput.value = supplier.name;
                
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                
                // Fokus ke input setelah modal muncul
                setTimeout(() => {
                    nameInput.focus();
                }, 100);
            } else {
                console.error('Form inputs not found');
            }
        } catch (error) {
            console.error('Error opening update modal:', error);
        }
    }

    function closeUpdateModal() {
        const modal = document.getElementById('updateModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Event listeners untuk close modal ketika klik di luar
    document.getElementById('createModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });

    document.getElementById('updateModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeUpdateModal();
        }
    });

    // Close modal dengan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('createModal').classList.contains('hidden')) {
                closeCreateModal();
            }
            if (!document.getElementById('updateModal').classList.contains('hidden')) {
                closeUpdateModal();
            }
        }
    });

    // Handle create form
    document.getElementById('createForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        try {
            const response = await fetch('/supplier', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    name: document.getElementById('create_name').value,
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

    // Handle update form
    document.getElementById('updateForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const supplierId = document.getElementById('update_supplier_id').value;
        
        try {
            const response = await fetch(`/supplier/${supplierId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    name: document.getElementById('update_name').value,
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

    async function deleteSupplier(id) {
        if (confirm('Are you sure you want to delete this supplier?')) {
            try {
                const response = await fetch(`/supplier/${id}`, {
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

    // Tambahkan animasi CSS di head
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        .animate-fadeIn {
            animation: fadeIn 0.15s ease-in;
        }
        .animate-fadeOut {
            animation: fadeOut 0.15s ease-out;
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
@endsection

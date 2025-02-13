@extends('staff.index')

@section('main')
<div class="p-4">
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl md:text-2xl font-semibold text-gray-800">PO Number Management</h2>
                <p class="text-sm text-gray-600 mt-1">Manage your Purchase Order numbers</p>
            </div>
            <button onclick="openCreateModal()" 
                class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
                <i class="fas fa-plus"></i>
                <span>Add PO</span>
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="searchInput" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" 
                    placeholder="Search PO...">
            </div>
        </div>

        <!-- Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3">PO Name</th>
                        <th scope="col" class="px-4 py-3">PO Date</th>
                        <th scope="col" class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($po_numbers as $po)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $po->po_name }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-gray-600">{{ $po->po_date }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-3">
                                <button onclick='openUpdateModal(@json($po))' 
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deletePo('{{ $po->id }}')" 
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-file-invoice text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-500 text-lg">No PO numbers found</p>
                                <p class="text-gray-400 text-sm mt-1">Try adding a new PO</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if($po_numbers->hasPages())
            <div class="px-4 py-3 bg-white border-t border-gray-200">
                {{ $po_numbers->links() }}
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
                    Add New PO
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
                        <label for="po_name" class="block mb-2 text-sm font-medium text-gray-900">
                            PO Name
                        </label>
                        <input type="text" id="po_name" name="po_name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Enter PO name" required>
                    </div>
                    <div>
                        <label for="po_date" class="block mb-2 text-sm font-medium text-gray-900">
                            PO Date
                        </label>
                        <input type="date" id="po_date" name="po_date" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            required>
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
                    Edit PO
                </h3>
                <button type="button" onclick="closeUpdateModal()" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateForm" class="p-4 md:p-5">
                @csrf
                <input type="hidden" id="update_po_id">
                <div class="grid gap-4 mb-4">
                    <div>
                        <label for="update_po_name" class="block mb-2 text-sm font-medium text-gray-900">
                            PO Name
                        </label>
                        <input type="text" id="update_po_name" name="po_name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Enter PO name" required>
                    </div>
                    <div>
                        <label for="update_po_date" class="block mb-2 text-sm font-medium text-gray-900">
                            PO Date
                        </label>
                        <input type="date" id="update_po_date" name="po_date" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            required>
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

function openUpdateModal(po) {
    document.getElementById('update_po_id').value = po.id;
    document.getElementById('update_po_name').value = po.po_name;
    document.getElementById('update_po_date').value = po.po_date;
    document.getElementById('updateModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Form submissions
document.getElementById('createForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    try {
        const response = await fetch('/po-no', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                po_name: document.getElementById('po_name').value,
                po_date: document.getElementById('po_date').value,
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
    const poId = document.getElementById('update_po_id').value;
    
    try {
        const response = await fetch(`/po-no/${poId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                po_name: document.getElementById('update_po_name').value,
                po_date: document.getElementById('update_po_date').value,
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

async function deletePo(id) {
    if (confirm('Are you sure you want to delete this PO?')) {
        try {
            const response = await fetch(`/po-no/${id}`, {
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
        const poName = row.querySelector('td:first-child').textContent.toLowerCase();
        const poDate = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        
        if (poName.includes(searchText) || poDate.includes(searchText)) {
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
</script>
@endpush
@endsection

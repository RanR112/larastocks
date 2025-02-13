@extends('staff.index')

@section('main')
<div class="p-4">
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl md:text-2xl font-semibold text-gray-800">User Management</h2>
                <p class="text-sm text-gray-600 mt-1">Manage your system users</p>
            </div>
            <div class="flex gap-2">
                <!-- Export buttons -->
                <a href="{{ route('staff.user.export.pdf', ['supplier_id' => request('supplier_id')]) }}" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
                    <i class="fas fa-file-pdf"></i>
                    <span>Export PDF</span>
                </a>
                <a href="{{ route('staff.user.export.excel', ['supplier_id' => request('supplier_id')]) }}" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
                    <i class="fas fa-file-excel"></i>
                    <span>Export Excel</span>
                </a>
                <button onclick="openCreateModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Add User</span>
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
                    placeholder="Search users...">
            </div>

            <!-- Filter Supplier -->
            <div>
                <form id="filterForm" class="flex gap-2">
                    <select name="supplier_id" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        onchange="this.form.submit()">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3">Name</th>
                        <th scope="col" class="px-4 py-3">NIK</th>
                        <th scope="col" class="px-4 py-3">Email</th>
                        <th scope="col" class="px-4 py-3">Role</th>
                        <th scope="col" class="px-4 py-3">Supplier</th>
                        <th scope="col" class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-gray-600">{{ $user->nik }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-gray-600">{{ $user->email }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-gray-600">
                                @foreach($roles as $role)
                                    @if($role->id === $user->role_id)
                                        {{ $role->name }}
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-gray-600">{{ $user->supplier ? $user->supplier->name : '-' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-3">
                                <button onclick='openUpdateModal(@json($user))' 
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button onclick="deleteUser('{{ $user->id }}')" 
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-users text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-500 text-lg">No users found</p>
                                <p class="text-gray-400 text-sm mt-1">Try adding a new user</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="px-4 py-3 bg-white border-t border-gray-200">
                {{ $users->links() }}
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
                    Add New User
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
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">
                            Name
                        </label>
                        <input type="text" id="name" name="name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            required>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">
                            Email
                        </label>
                        <input type="email" id="email" name="email" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            required>
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">
                            Password
                        </label>
                        <input type="password" id="password" name="password" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            required>
                    </div>
                    <div>
                        <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Role
                        </label>
                        <select id="role_id" name="role_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            onchange="toggleSupplierField(this.value)" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="supplier_field" class="hidden">
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
                    <div class="mb-4">
                        <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                        <input type="text" name="nik" id="nik" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
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
                    Edit User
                </h3>
                <button type="button" onclick="closeUpdateModal()" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateForm" class="p-4 md:p-5">
                @csrf
                <input type="hidden" id="update_user_id">
                <div class="grid gap-4 mb-4">
                    <div>
                        <label for="update_name" class="block mb-2 text-sm font-medium text-gray-900">
                            Name
                        </label>
                        <input type="text" id="update_name" name="name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            required>
                    </div>
                    <div>
                        <label for="update_email" class="block mb-2 text-sm font-medium text-gray-900">
                            Email
                        </label>
                        <input type="email" id="update_email" name="email" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            required>
                    </div>
                    <div>
                        <label for="update_password" class="block mb-2 text-sm font-medium text-gray-900">
                            Password <span class="text-sm text-gray-500">(leave empty to keep current password)</span>
                        </label>
                        <input type="password" id="update_password" name="password" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>
                    <div>
                        <label for="update_role_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Role
                        </label>
                        <select id="update_role_id" name="role_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            onchange="toggleUpdateSupplierField(this.value)" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="update_supplier_field" class="hidden">
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
                    <div class="mb-4">
                        <label for="edit_nik" class="block text-sm font-medium text-gray-700">NIK</label>
                        <input type="text" name="nik" id="edit_nik" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
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
const SUPPLIER_ROLE_ID = '{{ $supplierRoleId }}';

function toggleSupplierField(roleId) {
    const supplierField = document.getElementById('supplier_field');
    const supplierSelect = document.getElementById('supplier_id');
    
    if (roleId == SUPPLIER_ROLE_ID) {
        supplierField.classList.remove('hidden');
        supplierSelect.required = true;
    } else {
        supplierField.classList.add('hidden');
        supplierSelect.required = false;
        supplierSelect.value = '';
    }
}

// Modal functions
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('createForm').reset();
    document.body.style.overflow = 'hidden';
    document.getElementById('supplier_field').classList.add('hidden');
    document.getElementById('supplier_id').required = false;
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openUpdateModal(user) {
    document.getElementById('update_user_id').value = user.id;
    document.getElementById('update_name').value = user.name;
    document.getElementById('update_email').value = user.email;
    document.getElementById('edit_nik').value = user.nik;
    document.getElementById('update_role_id').value = user.role_id;
    document.getElementById('update_supplier_id').value = user.supplier_id || '';
    
    toggleUpdateSupplierField(user.role_id);
    
    document.getElementById('updateModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function toggleUpdateSupplierField(roleId) {
    const supplierField = document.getElementById('update_supplier_field');
    const supplierSelect = document.getElementById('update_supplier_id');
    
    if (roleId == SUPPLIER_ROLE_ID) {
        supplierField.classList.remove('hidden');
        supplierSelect.required = true;
    } else {
        supplierField.classList.add('hidden');
        supplierSelect.required = false;
        supplierSelect.value = '';
    }
}

// Form submissions
document.getElementById('createForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    try {
        const response = await fetch('/user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                role_id: document.getElementById('role_id').value,
                supplier_id: document.getElementById('supplier_id').value || null,
                nik: document.getElementById('nik').value,
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
    const userId = document.getElementById('update_user_id').value;
    
    try {
        const response = await fetch(`/user/${userId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                name: document.getElementById('update_name').value,
                email: document.getElementById('update_email').value,
                password: document.getElementById('update_password').value || undefined,
                role_id: document.getElementById('update_role_id').value,
                supplier_id: document.getElementById('update_supplier_id').value || null,
                nik: document.getElementById('edit_nik').value,
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

async function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        try {
            const response = await fetch(`/user/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });

            const data = await response.json();
            if (data.status === 'success') {
                window.location.reload();
            } else {
                alert(data.message);
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
        const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const role = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const supplier = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        
        if (name.includes(searchText) || email.includes(searchText) || 
            role.includes(searchText) || supplier.includes(searchText)) {
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
</script>
@endpush
@endsection

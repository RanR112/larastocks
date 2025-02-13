@extends('staff.index')

@section('main')
<div class="p-4">
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-800">{{ $title }}</h2>
            <button onclick="openCreateModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Upload Delivery Note</span>
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Supplier</th>
                        <th class="px-6 py-3">PDF</th>
                        <th class="px-6 py-3">Uploaded By</th>
                        <th class="px-6 py-3">Upload Date</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($controlls as $controll)
                    @if($controll->pdf_supplier != '-')
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4">{{ $controll->supplier->name }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ asset('pdfs/supplier/'.$controll->pdf_supplier) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-900">
                                View PDF
                            </a>
                        </td>
                        <td class="px-6 py-4">{{ $controll->user->name }}</td>
                        <td class="px-6 py-4">{{ $controll->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <button onclick="deleteDelivNote('{{ $controll->id }}')"
                                    class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Upload Delivery Note</h3>
            <form id="createForm" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Supplier</label>
                    <select name="supplier_id" required
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">PDF File</label>
                    <input type="file" name="pdf_supplier" accept=".pdf" required
                           class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.getElementById('createForm').reset();
}

document.getElementById('createForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    try {
        const response = await fetch('/deliv-note', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
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

async function deleteDelivNote(id) {
    if (confirm('Are you sure you want to delete this delivery note?')) {
        try {
            const response = await fetch(`/deliv-note/${id}`, {
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
</script>
@endpush
@endsection

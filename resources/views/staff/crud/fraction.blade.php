@extends('staff.index')

@section('main')
<div class="p-4 space-y-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">{{ $title }}</h1>
        <button onclick="openCreateModal()" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Add New Fraction</span>
        </button>
    </div>

    <!-- Filter Form -->
    <div class="mb-4">
        <div class="flex items-center gap-2">
            <div class="flex-1">
                <input type="text" 
                       id="location_code_filter" 
                       placeholder="Filter by Location Code" 
                       value="{{ request('location_code') }}"
                       class="form-input rounded-md w-full"
                       oninput="filterFractions(this.value, document.getElementById('plat_number_filter').value)">
            </div>
            <div class="flex-1">
                <input type="text" 
                       id="plat_number_filter" 
                       placeholder="Filter by Part Number" 
                       value="{{ request('plat_number') }}"
                       class="form-input rounded-md w-full"
                       oninput="filterFractions(document.getElementById('location_code_filter').value, this.value)">
            </div>
            <a href="{{ route('staff.fraction.index') }}" 
               class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                Reset
            </a>
        </div>
    </div>

    <!-- Table of Fractions -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($fractions as $fraction)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $fraction->location_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $fraction->plat_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="{{ asset('images/Fraction/'.$fraction->img) }}" 
                             alt="{{ $fraction->location_code }}" 
                             class="h-10 w-10 rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                             onclick="openImageModal('{{ asset('images/Fraction/'.$fraction->img) }}')">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <button onclick="openUpdateModal('{{ $fraction->id }}', '{{ $fraction->location_code }}', '{{ $fraction->plat_number }}')" 
                                    class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="printImage('{{ asset('images/Fraction/'.$fraction->img) }}')"
                                    class="text-green-600 hover:text-green-900">
                                <i class="fas fa-print"></i>
                            </button>
                            <form action="{{ route('staff.fraction.destroy', $fraction) }}" 
                                  method="POST" 
                                  class="inline-block"
                                  onsubmit="return confirm('Are you sure you want to delete this fraction?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Add New Fraction</h3>
            <form id="createForm" action="{{ route('staff.fraction.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="location_code">Location Code</label>
                    <input type="text" name="location_code" id="location_code" class="form-input rounded-md w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="plat_number">Part Number</label>
                    <input type="text" name="plat_number" id="plat_number" class="form-input rounded-md w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="img">Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer" 
                         id="dropZone"
                         ondrop="dropHandler(event)"
                         ondragover="dragOverHandler(event)"
                         onclick="document.getElementById('img').click()"
                         tabindex="0"
                         onkeydown="handleKeyDown(event, 'create')">
                        <p class="text-gray-600">Drag and drop image here, click to select, or press Ctrl+V to paste</p>
                        <input type="file" name="img" id="img" class="hidden" required accept="image/*" onchange="handleFileSelect(this)">
                        <img id="preview" class="mt-4 mx-auto max-h-40 hidden">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeCreateModal()" class="mr-2 px-4 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div id="updateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Update Fraction</h3>
            <form id="updateForm" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="update_fraction_id">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="update_location_code">Location Code</label>
                    <input type="text" name="location_code" id="update_location_code" class="form-input rounded-md w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="update_plat_number">Part Number</label>
                    <input type="text" name="plat_number" id="update_plat_number" class="form-input rounded-md w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="update_img">Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer" 
                         id="updateDropZone"
                         ondrop="dropHandler(event, 'update')"
                         ondragover="dragOverHandler(event)"
                         onclick="document.getElementById('update_img').click()"
                         tabindex="0"
                         onkeydown="handleKeyDown(event, 'update')">
                        <p class="text-gray-600">Drag and drop image here, click to select, or press Ctrl+V to paste</p>
                        <input type="file" name="img" id="update_img" class="hidden" accept="image/*" onchange="handleFileSelect(this, 'update')">
                        <img id="update_preview" class="mt-4 mx-auto max-h-40 hidden">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeUpdateModal()" class="mr-2 px-4 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-end">
                <button onclick="closeImageModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <img id="previewImage" src="" alt="Preview" class="w-full h-auto">
        </div>
    </div>
</div>

@push('scripts')
<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('dropZone').focus();
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.getElementById('createForm').reset();
    document.getElementById('preview').classList.add('hidden');
}

function openUpdateModal(id, locationCode, platNumber) {
    const modal = document.getElementById('updateModal');
    const form = document.getElementById('updateForm');
    form.action = `/fraction/${id}`;
    document.getElementById('update_location_code').value = locationCode;
    document.getElementById('update_plat_number').value = platNumber;
    modal.classList.remove('hidden');
    document.getElementById('updateDropZone').focus();
}

function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
    document.getElementById('updateForm').reset();
    document.getElementById('update_preview').classList.add('hidden');
}

function handleKeyDown(event, type) {
    // Handle Ctrl+V
    if (event.ctrlKey && event.key === 'v') {
        handlePaste(event, type);
    }
}

function handlePaste(event, type = '') {
    const items = (event.clipboardData || window.clipboardData).items;
    
    for (let item of items) {
        if (item.type.indexOf('image') === 0) {
            const file = item.getAsFile();
            const targetInput = document.getElementById(type ? `${type}_img` : 'img');
            
            const dt = new DataTransfer();
            dt.items.add(file);
            targetInput.files = dt.files;
            handleFileSelect(targetInput, type);
            
            event.preventDefault();
            break;
        }
    }
}

// Add global paste event listener
document.addEventListener('paste', function(event) {
    const activeModal = document.querySelector('#createModal:not(.hidden)') ? 'create' : 
                       document.querySelector('#updateModal:not(.hidden)') ? 'update' : null;
    
    if (activeModal) {
        handlePaste(event, activeModal === 'create' ? '' : 'update');
    }
});

function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const previewImage = document.getElementById('previewImage');
    previewImage.src = imageSrc;
    modal.classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function dragOverHandler(event) {
    event.preventDefault();
    event.currentTarget.classList.add('border-blue-500');
}

function dropHandler(event, type = '') {
    event.preventDefault();
    event.currentTarget.classList.remove('border-blue-500');
    
    const files = event.dataTransfer.files;
    if (files.length) {
        const fileInput = document.getElementById(type ? `${type}_img` : 'img');
        fileInput.files = files;
        handleFileSelect(fileInput, type);
    }
}

function handleFileSelect(input, type = '') {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const previewId = type ? `${type}_preview` : 'preview';
        
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function printImage(imageSrc) {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title></title>
                <style>
                    @page { 
                        size: landscape;
                        margin: 0;
                    }
                    body { 
                        margin: 0; 
                        padding: 0;
                    }
                    img { 
                        width: 100%;
                        height: 100vh;
                        object-fit: contain;
                    }
                </style>
            </head>
            <body>
                <img src="${imageSrc}" onload="setTimeout(function() { window.print(); window.close(); }, 100)">
            </body>
        </html>
    `);
    printWindow.document.close();
}

function filterFractions(locationCode, platNumber) {
    clearTimeout(window.filterTimeout);
    window.filterTimeout = setTimeout(() => {
        const url = new URL(window.location.href);
        
        if (locationCode) {
            url.searchParams.set('location_code', locationCode);
        } else {
            url.searchParams.delete('location_code');
        }
        
        if (platNumber) {
            url.searchParams.set('plat_number', platNumber);
        } else {
            url.searchParams.delete('plat_number');
        }
        
        window.location.href = url.toString();
    }, 500);
}
</script>
@endpush

@endsection

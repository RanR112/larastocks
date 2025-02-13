<div id="updateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Update Control PO</h3>
            <form id="updateForm" class="mt-4">
                @csrf
                <input type="hidden" id="update_control_po_id">
                
                <!-- Sama seperti form create, tapi dengan id yang berbeda -->
                <!-- Contoh: update_supplier_id, update_material_id, dll -->
                <!-- Copy semua field dari form create dan tambahkan prefix 'update_' -->
                
                <div class="flex justify-end">
                    <button type="button" onclick="closeUpdateModal()" class="mr-2 px-4 py-2 text-gray-500 hover:text-gray-700">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 
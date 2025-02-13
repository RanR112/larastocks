<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Control PO</h3>
            <form id="createForm" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="supplier_id">
                        Supplier
                    </label>
                    <select id="supplier_id" name="supplier_id" class="form-select rounded-md w-full">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="material_id">
                        Material
                    </label>
                    <select id="material_id" name="material_id" class="form-select rounded-md w-full">
                        <option value="">Select Material</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}">{{ $material->material_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="material_detail_id">
                        Material Detail
                    </label>
                    <select id="material_detail_id" name="material_detail_id" class="form-select rounded-md w-full">
                        <option value="">Select Material Detail</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="no_po_id">
                        PO Number
                    </label>
                    <select id="no_po_id" name="no_po_id" class="form-select rounded-md w-full">
                        <option value="">Select PO Number</option>
                        @foreach($noPos as $noPo)
                            <option value="{{ $noPo->id }}">{{ $noPo->po_no }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="schedule_incoming">
                        Schedule Incoming
                    </label>
                    <input type="date" id="schedule_incoming" name="schedule_incoming" class="form-input rounded-md w-full">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="qty_coil">
                        Quantity (Coil)
                    </label>
                    <input type="number" id="qty_coil" name="qty_coil" class="form-input rounded-md w-full">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="qty_kg">
                        Quantity (KG)
                    </label>
                    <input type="number" id="qty_kg" name="qty_kg" class="form-input rounded-md w-full">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Status
                    </label>
                    <select id="status" name="status" class="form-select rounded-md w-full">
                        <option value="pending">Pending</option>
                        <option value="received">Received</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeCreateModal()" class="mr-2 px-4 py-2 text-gray-500 hover:text-gray-700">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 
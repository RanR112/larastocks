@extends('suplayer.index')

@section('main')
    <div class="p-4">
        <div class="p-4">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="flex items-center h-24 rounded bg-gradient-to-r from-blue-800 to-blue-600 shadow-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-blue-500 bg-opacity-50 rounded-lg">
                                <i class="fas fa-boxes text-2xl text-white"></i>
                            </div>
                            <div class="flex-1 ml-4">
                                <p class="text-2xl font-semibold text-white">{{ $material_count ?? 0 }}</p>
                                <p class="text-sm text-blue-100">Total Material</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center h-24 rounded bg-gradient-to-r from-blue-900 to-blue-700 shadow-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-blue-600 bg-opacity-50 rounded-lg">
                                <i class="fas fa-file-invoice text-2xl text-white"></i>
                            </div>
                            <div class="flex-1 ml-4">
                                <p class="text-2xl font-semibold text-white">{{ $po_count ?? 0 }}</p>
                                <p class="text-sm text-blue-100">Active PO</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Material List Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Your Materials</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Material Name</th>
                                    <th class="px-6 py-3">PO Number</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materials ?? [] as $material)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium">{{ $material->material_name }}</td>
                                        <td class="px-6 py-4">{{ $material->po_number }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">
                                                {{ $material->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No materials found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('suplayer.index')

@section('main')
<div class="p-4">
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-lg">
        <div class="mb-6">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-800">{{ $title }}</h2>
            <p class="text-gray-600 mt-1">Download your forecast documents</p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">PDF</th>
                        <th class="px-6 py-3">Upload Date</th>
                        <th class="px-6 py-3">Last Download</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($controlls as $controll)
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4">{{ $controll->pdf_fortcast }}</td>
                        <td class="px-6 py-4">{{ $controll->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4">
                            {{ $controll->updated_at != $controll->created_at ? 
                                $controll->updated_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') : 
                                'Not downloaded yet' }}
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="openPDF('{{ route('suplayer.forecast.download', $controll->id) }}')" 
                                    class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openPDF(url) {
    window.open(url, '_blank');
}
</script>
@endpush
@endsection

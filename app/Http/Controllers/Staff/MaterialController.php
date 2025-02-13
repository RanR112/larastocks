<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Material;
use App\Models\MaterialDetail;
use App\Models\Supplier;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaterialsExport;

class MaterialController extends Controller
{
    public function __construct()
    {
        if (!Auth::check()) {
            abort(404, 'Page not found.');
        }

        $role = Roles::where('name', 'staff')->first();
        if (Auth::user()->role_id != $role->id) {
            abort(404, 'Page not found.');
        }
    }

    public function index(Request $request)
    {
        $query = Material::with(['supplier', 'details']);
        
        // Filter by supplier if selected
        if ($request->has('supplier_id') && $request->supplier_id != '') {
            $query->where('supplier_id', $request->supplier_id);
        }

        $data = [
            'title' => 'Material List',
            'materials' => $query->paginate(10)->withQueryString(), // Tambahkan withQueryString() untuk mempertahankan parameter URL saat paginasi
            'suppliers' => Supplier::all()
        ];
        return view('staff.crud.material', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:supplier,id',
        ]);

        $material = Material::create([
            'supplier_id' => $request->supplier_id,
            'material_name' => $request->material_name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Material created successfully',
            'material' => $material
        ]);
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'material_name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:supplier,id',
        ]);

        $material->update([
            'supplier_id' => $request->supplier_id,
            'material_name' => $request->material_name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Material updated successfully',
            'material' => $material
        ]);
    }

    public function destroy(Material $material)
    {
        $material->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Material deleted successfully'
        ]);
    }

    public function storeDetail(Request $request, Material $material)
    {
        $request->validate([
            'diameter' => 'required|string|max:255',
            'kg_coil' => 'required|numeric'
        ]);

        $detail = $material->details()->create([
            'material_id' => $material->id,
            'diameter' => $request->diameter,
            'kg_coil' => $request->kg_coil
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Material detail added successfully',
            'detail' => $detail
        ]);
    }

    public function updateDetail(Request $request, MaterialDetail $materialDetail)
    {
        $request->validate([
            'diameter' => 'required|string|max:255',
            'kg_coil' => 'required|numeric'
        ]);

        $materialDetail->update([
            'diameter' => $request->diameter,
            'kg_coil' => $request->kg_coil
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Material detail updated successfully',
            'detail' => $materialDetail
        ]);
    }

    public function destroyDetail(MaterialDetail $materialDetail)
    {
        $materialDetail->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Material detail deleted successfully'
        ]);
    }

    public function exportPDF(Request $request)
    {
        $query = Material::with(['supplier', 'details']);
        
        // Filter by supplier if selected
        if ($request->has('supplier_id') && $request->supplier_id != '') {
            $query->where('supplier_id', $request->supplier_id);
        }

        $materials = $query->get();
        
        $pdf = PDF::loadView('staff.exports.materials-pdf', [
            'materials' => $materials,
            'supplier' => $request->supplier_id ? Supplier::find($request->supplier_id) : null
        ]);
        
        return $pdf->download('materials-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new MaterialsExport($request->supplier_id), 'materials-' . date('Y-m-d') . '.xlsx');
    }
}

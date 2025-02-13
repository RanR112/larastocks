<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Supplier;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuppliersExport;

class SupplierController extends Controller
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

    public function index()
    {
        $data = [
            'title' => 'Supplier Management',
            'suppliers' => Supplier::with('users')->paginate(10)
        ];
        return view('staff.crud.supplier', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Supplier created successfully',
            'supplier' => $supplier
        ]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $supplier->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Supplier updated successfully',
            'supplier' => $supplier
        ]);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Supplier deleted successfully'
        ]);
    }

    public function exportPDF()
    {
        $suppliers = Supplier::with('users')->get();
        $pdf = PDF::loadView('staff.exports.suppliers-pdf', [
            'suppliers' => $suppliers
        ]);
        
        return $pdf->download('suppliers-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new SuppliersExport, 'suppliers-' . date('Y-m-d') . '.xlsx');
    }
}

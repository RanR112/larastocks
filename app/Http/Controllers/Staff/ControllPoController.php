<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\ControlPo;
use App\Models\Supplier;
use App\Models\Material;
use App\Models\No_Po;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ControlPosExport;

class ControllPoController extends Controller
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
        $query = ControlPo::with([
            'supplier', 
            'material.details',
            'materialDetail', 
            'noPo',
            'actualReceives'
        ]);
        
        // Apply filters
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('schedule_incoming')) {
            $query->whereDate('schedule_incoming', $request->schedule_incoming);
        }
        if ($request->filled('material_receiving_status')) {
            $query->where('material_receiving_status', $request->material_receiving_status);
        }
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        // Tambahkan ordering untuk bulan
        $query->orderByRaw("FIELD(month, 
            'January', 
            'February', 
            'March', 
            'April', 
            'May', 
            'June', 
            'July', 
            'August', 
            'September', 
            'October', 
            'November', 
            'December'
        )");

        $data = [
            'title' => 'Control PO',
            'controlPos' => $query->paginate(10)->withQueryString(),
            'suppliers' => Supplier::all(),
            'materials' => Material::with(['details', 'supplier'])->get(),
            'noPos' => No_Po::all(),
            'months' => [
                'January', 'February', 'March', 'April', 
                'May', 'June', 'July', 'August',
                'September', 'October', 'November', 'December'
            ]
        ];
        return view('staff.crud.controll_po', $data);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'supplier_id' => 'required|exists:supplier,id',
                'material_id' => 'required|exists:material,id',
                'material_detail_id' => 'required|exists:material_details,id',
                'no_po_id' => 'required|exists:po_no,id',
                'schedule_incoming' => 'required|date',
                'qty_coil' => 'required|string',
                'qty_kg' => 'required|string',
                'month' => 'required|string'
            ]);

            $controlPo = ControlPo::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Control PO created successfully',
                'controlPo' => $controlPo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create Control PO',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, ControlPo $controlPo)
    {
        try {
            $validated = $request->validate([
                'supplier_id' => 'required|exists:supplier,id',
                'material_id' => 'required|exists:material,id',
                'material_detail_id' => 'required|exists:material_details,id',
                'no_po_id' => 'required|exists:po_no,id',
                'schedule_incoming' => 'required|date',
                'qty_coil' => 'required|string',
                'qty_kg' => 'required|string',
                'month' => 'required|string'
            ]);

            $controlPo->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Control PO updated successfully',
                'controlPo' => $controlPo
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Update Control PO Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Control PO',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(ControlPo $controlPo)
    {
        $controlPo->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Control PO deleted successfully'
        ]);
    }

    public function exportPDF(Request $request)
    {
        $filters = $request->only(['supplier_id', 'schedule_incoming', 'material_receiving_status', 'month']);
        
        $query = ControlPo::with(['supplier', 'material', 'materialDetail', 'noPo', 'actualReceives']);
        
        // Apply filters
        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }
        if (!empty($filters['schedule_incoming'])) {
            $query->whereDate('schedule_incoming', $filters['schedule_incoming']);
        }
        if (!empty($filters['material_receiving_status'])) {
            $query->where('material_receiving_status', $filters['material_receiving_status']);
        }
        if (!empty($filters['month'])) {
            $query->where('month', $filters['month']);
        }

        // Tambahkan ordering untuk bulan
        $query->orderByRaw("FIELD(month, 
            'January', 
            'February', 
            'March', 
            'April', 
            'May', 
            'June', 
            'July', 
            'August', 
            'September', 
            'October', 
            'November', 
            'December'
        )");

        $controlPos = $query->get();
        
        $pdf = PDF::loadView('staff.exports.control-pos-pdf', [
            'controlPos' => $controlPos,
            'filters' => $filters
        ]);
        
        return $pdf->download('control-pos-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['supplier_id', 'schedule_incoming', 'material_receiving_status', 'month']);
        return Excel::download(new ControlPosExport($filters), 'control-pos-' . date('Y-m-d') . '.xlsx');
    }
}

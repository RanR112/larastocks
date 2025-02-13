<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;
use App\Models\Supplier;
use App\Models\Material;
use App\Models\MaterialDetail;
use App\Models\No_Po;
use App\Models\ControlPo;
use App\Models\ActualReceive;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActualReceiveExport;

class ActualReciveController extends Controller
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

    private function getFilteredQuery($request)
    {
        $query = ActualReceive::with(['supplier', 'material', 'materialDetail', 'noPo', 'controlPo'])
            ->latest();

        // Filter berdasarkan pencarian
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('delivery_number', 'like', "%{$search}%")
                  ->orWhereHas('material', function($q) use ($search) {
                      $q->where('material_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan supplier
        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter berdasarkan bulan
        if ($request->month) {
            $query->whereHas('controlPo', function($q) use ($request) {
                $q->where('month', $request->month);
            });
        }

        return $query;
    }

    public function index()
    {
        $data = [
            'title' => 'Actual Receive',
            'actualReceives' => ActualReceive::with(['supplier', 'material', 'materialDetail', 'noPo', 'controlPo'])->paginate(10),
            'suppliers' => Supplier::all(),
            'materials' => Material::with(['details', 'supplier'])->get(),
            'noPos' => No_Po::all(),
            'controlPos' => ControlPo::with([
                'material.supplier', 
                'material.details', 
                'materialDetail', 
                'noPo'
            ])->get()
        ];
        return view('staff.crud.actual', $data);
    }

    public function exportPDF(Request $request)
    {
        $query = $this->getFilteredQuery($request);
        $actualReceives = $query->get();

        $pdf = PDF::loadView('staff.exports.actual-receive-pdf', [
            'actualReceives' => $actualReceives,
            'title' => 'Actual Receive Report',
            'filters' => [
                'search' => $request->search,
                'supplier' => $request->supplier_id ? Supplier::find($request->supplier_id)->name : 'All',
                'month' => $request->month ?: 'All'
            ]
        ]);

        return $pdf->download('actual-receive-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new ActualReceiveExport($request), 'actual-receive-report.xlsx');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'in_date' => 'required|date',
                'supplier_id' => 'required|exists:supplier,id',
                'material_id' => 'required|exists:material,id',
                'material_detail_id' => 'required|exists:material_details,id',
                'no_po_id' => 'required|exists:po_no,id',
                'delivery_number' => 'required|string',
                'weight' => 'required|string',
                'total_coil' => 'required|string',
                'control_po_id' => 'required|exists:control_po,id',
                'charge_number' => 'required|string',
                'coil_no' => 'required|string',
                'note' => 'nullable|string'
            ]);

            $actualReceive = ActualReceive::create($validated);

            // Update status di control_po
            $controlPo = ControlPo::find($request->control_po_id);
            $controlPo->touch(); // Trigger updating event untuk update status

            return response()->json([
                'status' => 'success',
                'message' => 'Actual Receive created successfully',
                'actualReceive' => $actualReceive
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create Actual Receive',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, ActualReceive $actualReceive)
    {
        try {
            $validated = $request->validate([
                'in_date' => 'required|date',
                'supplier_id' => 'required|exists:supplier,id',
                'material_id' => 'required|exists:material,id',
                'material_detail_id' => 'required|exists:material_details,id',
                'no_po_id' => 'required|exists:po_no,id',
                'delivery_number' => 'required|string',
                'weight' => 'required|string',
                'total_coil' => 'required|string',
                'control_po_id' => 'required|exists:control_po,id',
                'charge_number' => 'required|string',
                'coil_no' => 'required|string',
                'note' => 'nullable|string'
            ]);

            $actualReceive->update($validated);

            // Update status di control_po
            $controlPo = ControlPo::find($request->control_po_id);
            $controlPo->touch(); // Trigger updating event untuk update status

            return response()->json([
                'status' => 'success',
                'message' => 'Actual Receive updated successfully',
                'actualReceive' => $actualReceive
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Actual Receive',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(ActualReceive $actualReceive)
    {
        try {
            $controlPoId = $actualReceive->control_po_id;
            $actualReceive->delete();

            // Update status di control_po
            $controlPo = ControlPo::find($controlPoId);
            $controlPo->touch(); // Trigger updating event untuk update status

            return response()->json([
                'status' => 'success',
                'message' => 'Actual Receive deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete Actual Receive',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

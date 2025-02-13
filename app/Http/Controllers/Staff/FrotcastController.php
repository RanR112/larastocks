<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Material;
use App\Models\MaterialDetail;
use App\Models\ControlPo;
use App\Models\ActualReceive;
use App\Models\Fortcast;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FortcastExport;

class FrotcastController extends Controller
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
        $title = 'Control Order vs Forecast';
        $suppliers = Supplier::all();
        $fortcasts = $this->getFortcastData($request);

        return view('staff.crud.fortcast', compact('fortcasts', 'title', 'suppliers'));
    }

    public function exportPDF()
    {
        $request = request();
        $title = 'Control Order vs Fortcast Report';
        $fortcasts = $this->getFortcastData($request);
        $pdf = PDF::loadView('staff.exports.fortcast-pdf', [
            'fortcasts' => $fortcasts,
            'title' => $title,
            'filters' => [
                'search' => $request->search,
                'supplier' => $request->supplier_id ? Supplier::find($request->supplier_id)->name : 'All',
                'month' => $request->month ?: 'All'
            ]
        ]);
        return $pdf->download('fortcast-report.pdf');
    }

    public function exportExcel()
    {
        $request = request();
        return Excel::download(new FortcastExport($request), 'fortcast-report.xlsx');
    }

    public function getFortcastData(Request $request)
    {
        $query = Material::with([
            'details', 
            'controlPos' => function($query) use ($request) {
                if ($request->month) {
                    $query->where('month', $request->month);
                }
            },
            'controlPos.actualReceives'
        ]);

        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('material_name', 'like', "%{$search}%")
                  ->orWhereHas('details', function($q) use ($search) {
                      $q->where('diameter', 'like', "%{$search}%");
                  });
            });
        }

        $materials = $query->get();
        
        $fortcasts = $materials->flatMap(function ($material) {
            return $material->details->map(function ($detail) use ($material) {
                $controlPo = $material->controlPos
                    ->where('material_detail_id', $detail->id)
                    ->first();

                if ($controlPo) {
                    $actualReceive = $controlPo->actualReceives->first();
                    
                    $po = (int)($controlPo->qty_kg ?? 0);
                    $actual = $actualReceive ? (int)floatval($actualReceive->weight) : 0;
                    $balance = $po - $actual;
                    $percentage = $po > 0 ? ($actual / $po * 100) : 0;
                    $stdQty = (int)floatval($detail->kg_coil);
                    $kanban = $stdQty > 0 ? ceil($balance / $stdQty) : 0;

                    return [
                        'material_name' => $material->material_name,
                        'diameter' => $detail->diameter,
                        'std_qty' => $stdQty,
                        'month' => $controlPo->month,
                        'po' => $po,
                        'actual' => $actual,
                        'balance' => $balance,
                        'percentage' => number_format($percentage, 0) . '%',
                        'kanban' => $kanban
                    ];
                }
                return null;
            })->filter();
        })->values();

        // Urutkan berdasarkan bulan
        $monthOrder = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];

        return $fortcasts->sortBy(function ($item) use ($monthOrder) {
            return $monthOrder[$item['month']] ?? 13;
        })->values();
    }
}

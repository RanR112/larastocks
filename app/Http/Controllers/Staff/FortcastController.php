<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Roles;
use App\Models\Material;
use App\Models\MaterialDetail;
use App\Models\ControlPo;
use App\Models\ActualReceive;
use App\Models\Fortcast;

class FortcastController extends Controller
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
        $fortcasts = Material::with([
            'details', 
            'controlPos' => function($query) {
                $query->latest();
            },
            'controlPos.actualReceives'
        ])
        ->get()
        ->flatMap(function ($material) {
            return $material->details->map(function ($detail) use ($material) {
                $controlPo = $material->controlPos
                    ->where('material_detail_id', $detail->id)
                    ->first();

                if ($controlPo) {
                    $actualReceive = $controlPo->actualReceives->first();
                    
                    // Debug info
                    Log::info('Processing material:', [
                        'material_id' => $material->id,
                        'detail_id' => $detail->id,
                        'control_po' => $controlPo->toArray(),
                        'actual_receive' => $actualReceive ? $actualReceive->toArray() : null
                    ]);
                    
                    // Hitung balance
                    $po = $controlPo->qty_kg ?? 0;
                    $actual = $actualReceive ? floatval($actualReceive->weight) : 0;
                    $balance = $po - $actual;
                    
                    // Hitung persentase
                    $percentage = $po > 0 ? ($actual / $po * 100) : 0;
                    
                    // Hitung kanban
                    $stdQty = floatval($detail->kg_coil);
                    $kanban = $stdQty > 0 ? ceil($balance / $stdQty) : 0;

                    return [
                        'material_name' => $material->material_name,
                        'diameter' => $detail->diameter,
                        'std_qty' => $detail->kg_coil,
                        'month' => $controlPo->month,
                        'po' => $po,
                        'actual' => $actual,
                        'balance' => $balance,
                        'percentage' => number_format($percentage, 2) . '%',
                        'kanban' => $kanban
                    ];
                }
                return null;
            })->filter();
        });

        // Debug collection
        Log::info('Fortcast collection:', ['count' => $fortcasts->count()]);

        return view('staff.crud.fortcast', compact('fortcasts'));
    }
} 
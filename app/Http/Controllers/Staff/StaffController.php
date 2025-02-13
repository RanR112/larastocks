<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Roles;
use App\Models\Material;
use App\Models\Supplier;
use App\Models\ControlPo;
use App\Models\No_Po;
use App\Models\ActualReceive;

class StaffController extends Controller
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
        return view('staff.layouts.home', [
            'material_count' => Material::count(),
            'po_count' => No_Po::count(),
            'supplier_count' => Supplier::count(),
            'title' => 'Dashboard',
            'recent_materials' => Material::with('supplier')->latest()->take(5)->get(),
            'upcoming_deliveries' => ActualReceive::with(['supplier', 'noPo'])
                ->where('in_date', '>=', now())
                ->orderBy('in_date')
                ->take(5)
                ->get()
        ]);
    }
}
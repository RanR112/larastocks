<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\No_Po;

class Po_NoController extends Controller
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
            'title' => 'PO Number Management',
            'po_numbers' => No_Po::paginate(10)
        ];
        return view('staff.crud.po_no', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_date' => 'required|date',
            'po_name' => 'required|string|max:255',
        ]);

        $po = No_Po::create([
            'po_date' => $request->po_date,
            'po_name' => $request->po_name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'PO created successfully',
            'po' => $po
        ]);
    }

    public function update(Request $request, No_Po $po_no)
    {
        $request->validate([
            'po_date' => 'required|date',
            'po_name' => 'required|string|max:255',
        ]);

        $po_no->update([
            'po_date' => $request->po_date,
            'po_name' => $request->po_name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'PO updated successfully',
            'po' => $po_no
        ]);
    }

    public function destroy(No_Po $po_no)
    {
        $po_no->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'PO deleted successfully'
        ]);
    }
}

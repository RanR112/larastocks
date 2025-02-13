<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Fraction;
use Illuminate\Support\Facades\Storage;

class FractionController extends Controller
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
        $query = Fraction::query();

        if ($request->has('location_code') && $request->location_code != '') {
            $query->where('location_code', 'like', '%' . $request->location_code . '%');
        }

        if ($request->has('plat_number') && $request->plat_number != '') {
            $query->where('plat_number', 'like', '%' . $request->plat_number . '%');
        }

        $fractions = $query->get();
        $title = 'Kanban Fraction';
        return view('staff.crud.fraction', compact('fractions', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_code' => 'required|string|max:255',
            'plat_number' => 'required|string|max:255',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = time().'.'.$request->img->extension();  
        $request->img->move(public_path('images/Fraction'), $imageName);

        Fraction::create([
            'location_code' => $request->location_code,
            'plat_number' => $request->plat_number,
            'img' => $imageName,
        ]);

        return redirect()->route('staff.fraction.index')->with('success', 'Fraction created successfully.');
    }

    public function update(Request $request, Fraction $fraction)
    {
        $request->validate([
            'location_code' => 'required|string|max:255',
            'plat_number' => 'required|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $imageName = time().'.'.$request->img->extension();  
            $request->img->move(public_path('images/Fraction'), $imageName);
            $fraction->img = $imageName;
        }

        $fraction->location_code = $request->location_code;
        $fraction->plat_number = $request->plat_number;
        $fraction->save();

        return redirect()->route('staff.fraction.index')->with('success', 'Fraction updated successfully.');
    }

    public function destroy(Fraction $fraction)
    {
        if ($fraction->img) {
            $imagePath = public_path('images/Fraction/'.$fraction->img);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $fraction->delete();
        return redirect()->route('staff.fraction.index')->with('success', 'Fraction deleted successfully.');
    }
}

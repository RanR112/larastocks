<?php

namespace App\Http\Controllers\Suplayer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuplayerController extends Controller
{
    public function index()
    {
        $title = 'Supplier Dashboard';
        $material_count = 0; // Ganti dengan query sesuai kebutuhan
        $po_count = 0; // Ganti dengan query sesuai kebutuhan
        $materials = []; // Ganti dengan query sesuai kebutuhan

        return view('suplayer.index', compact('title', 'material_count', 'po_count', 'materials'));
    }

    public function material()
    {
        $title = 'Control Database Material';
        return view('suplayer.material.index', compact('title'));
    }
}
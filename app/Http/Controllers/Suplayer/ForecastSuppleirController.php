<?php

namespace App\Http\Controllers\Suplayer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Controll;

class ForecastSuppleirController extends Controller
{
    public function __construct()
    {
        if (!Auth::check()) {
            abort(404, 'Page not found.');
        }

        $role = Roles::where('name', 'supplier')->first();
        if (Auth::user()->role_id != $role->id) {
            abort(404, 'Page not found.');
        }
    }

    public function index()
    {
        $controlls = Controll::where('supplier_id', Auth::user()->supplier_id)
                            ->where('pdf_fortcast', '!=', '-')
                            ->get();
        $title = 'Forecast Supplier';
        return view('suplayer.crud.forecast_supp', compact('controlls', 'title'));
    }

    public function download($id)
    {
        $controll = Controll::where('supplier_id', Auth::user()->supplier_id)
                           ->where('id', $id)
                           ->firstOrFail();

        // Update waktu download
        $controll->touch();

        $path = public_path('pdfs/forecast/' . $controll->pdf_fortcast);
        
        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->stream(
            function () use ($path) {
                $stream = fopen($path, 'rb');
                fpassthru($stream);
                fclose($stream);
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$controll->pdf_fortcast.'"',
                'Content-Length' => filesize($path),
                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]
        );
    }
}

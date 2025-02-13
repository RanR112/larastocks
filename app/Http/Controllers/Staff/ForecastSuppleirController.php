<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Controll;
use App\Models\Supplier;
use App\Models\User;
use App\Mail\ForecastUploadMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ForecastSuppleirController extends Controller
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
        $controlls = Controll::with(['supplier', 'user'])->get();
        $suppliers = Supplier::all();
        $title = 'Forecast Supplier';
        return view('staff.crud.forecast_supplier', compact('controlls', 'suppliers', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:supplier,id',
            'pdf_fortcast' => 'required|mimes:pdf|max:2048',
        ]);

        try {
            $pdf = $request->file('pdf_fortcast');
            $pdfName = time() . '_' . $pdf->getClientOriginalName();
            $pdf->move(public_path('pdfs/forecast'), $pdfName);

            $controll = Controll::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => Auth::id(),
                'pdf_supplier' => '-',
                'pdf_fortcast' => $pdfName
            ]);

            // Kirim email ke semua user dengan supplier yang sama
            $supplier = Supplier::find($request->supplier_id);
            $users = User::where('supplier_id', $request->supplier_id)->get();
            
            Log::info('Mencoba mengirim email forecast ke ' . count($users) . ' users');
            
            foreach ($users as $user) {
                try {
                    Log::info('Mengirim email ke: ' . $user->email);
                    Mail::to($user->email)->send(new ForecastUploadMail(
                        $supplier,
                        Auth::user(),
                        $pdfName
                    ));
                    Log::info('Email berhasil dikirim ke: ' . $user->email);
                } catch (\Exception $e) {
                    Log::error('Gagal mengirim email ke ' . $user->email . ': ' . $e->getMessage());
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Forecast uploaded successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat upload forecast: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload Forecast: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Controll $controll)
    {
        try {
            if ($controll->pdf_fortcast != '-') {
                $path = public_path('pdfs/forecast/' . $controll->pdf_fortcast);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            
            $controll->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Forecast deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete Forecast'
            ], 500);
        }
    }
}

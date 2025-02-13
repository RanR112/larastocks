<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Controll;
use App\Models\Supplier;
use App\Models\User;
use App\Mail\DeliveryNoteUploadMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class DelivNoteController extends Controller
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
        $title = 'Delivery Note';
        return view('staff.crud.deliv_note', compact('controlls', 'suppliers', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:supplier,id',
            'pdf_supplier' => 'required|mimes:pdf|max:2048',
        ]);

        try {
            $pdf = $request->file('pdf_supplier');
            $pdfName = time() . '_' . $pdf->getClientOriginalName();
            
            // Simpan ke folder public/pdfs/supplier
            $pdf->move(public_path('pdfs/supplier'), $pdfName);

            $controll = Controll::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => Auth::id(),
                'pdf_supplier' => $pdfName,
                'pdf_fortcast' => '-'
            ]);

            // Kirim email ke semua user dengan supplier yang sama
            $supplier = Supplier::find($request->supplier_id);
            $users = User::where('supplier_id', $request->supplier_id)->get();
            
            Log::info('Mencoba mengirim email delivery note ke ' . count($users) . ' users');
            
            foreach ($users as $user) {
                try {
                    Log::info('Mengirim email ke: ' . $user->email);
                    Mail::to($user->email)->send(new DeliveryNoteUploadMail(
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
                'message' => 'Delivery Note uploaded successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat upload delivery note: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload Delivery Note: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Controll $controll)
    {
        try {
            if ($controll->pdf_supplier != '-') {
                $path = public_path('pdfs/supplier/' . $controll->pdf_supplier);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            
            $controll->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Delivery Note deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete Delivery Note'
            ], 500);
        }
    }
}

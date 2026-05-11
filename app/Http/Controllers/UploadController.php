<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    // Menampilkan form upload
    // public function index()
    // {
    //     return view('upload.index');
    // }

    // // Memproses upload file (sengaja rentan untuk uji path traversal)
    // public function upload(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:jpg,png,pdf,txt|max:2048'
    //     ]);

    //     $file = $request->file('file');
        
    //     // --- INI SENGAYA RENTAN (nama file tidak dibersihkan) ---
    //     // Nama file asli dari user bisa mengandung path traversal seperti "../../../.env"
    //     $originalName = $file->getClientOriginalName();
        
    //     // Simpan ke storage/app/public/uploads/ (bisa diakses via public/storage/uploads)
    //     $path = $file->storeAs('public/uploads', $originalName);
        
    //     return back()->with('success', 'File berhasil diupload: ' . $originalName)
    //                  ->with('path', Storage::url($path));
    // }
}
<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    
    public function index()
    {
        
        $kategori = Kategori::with('bukus')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Kategori Buku',
            'data' => $kategori
        ]);
    }
}

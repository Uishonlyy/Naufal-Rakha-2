<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
{
    $buku = Buku::with('kategori')
        ->when($request->filled('q'), function ($query) use ($request) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('penulis', 'like', "%{$q}%")
                    ->orWhere('penerbit', 'like', "%{$q}%")
                    ->orWhereHas('kategori', fn ($k) => $k->where('nama_kategori', 'like', "%{$q}%"));
            });
        })
        ->when($request->filled('kategori'), fn ($query) => $query->where('kategoris_id', $request->kategori))
        ->when($request->boolean('tersedia'), fn ($query) => $query->where('stok', '>', 0))
        ->orderBy('judul')
        ->get();

    $kategori = Kategori::orderBy('nama_kategori')->get();

    return view('dashboard', compact('buku', 'kategori'));
}

    public function create()
    {
        return view('buku.create', [
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Buku::create($this->validated($request));

        return redirect()->route('dashboard')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('buku.edit', [
            'buku'     => $buku,
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
        ]);
    }

    public function update(Request $request, Buku $buku)
    {
        $buku->update($this->validated($request));

        return redirect()->route('dashboard')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        try {
            $buku->delete();
        } catch (QueryException $e) {
            
            return redirect()->route('dashboard')
                ->with('error', 'Buku tidak bisa dihapus karena masih punya riwayat peminjaman.');
        }

        return redirect()->route('dashboard')->with('success', 'Buku berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'kategoris_id' => ['required', 'exists:kategoris,id'],
            'judul'        => ['required', 'string', 'max:255'],
            'penulis'      => ['required', 'string', 'max:255'],
            'penerbit'     => ['nullable', 'string', 'max:255'],
            'tahun_terbit' => ['nullable', 'integer', 'between:1901,' . date('Y')],
            'stok'         => ['required', 'integer', 'min:0'],
        ]);
    }
}
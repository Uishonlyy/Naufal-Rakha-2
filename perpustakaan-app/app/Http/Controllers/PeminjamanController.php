<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    private const LAMA_PINJAM_HARI = 7;
    private const DENDA_PER_HARI   = 1000;

    
    public function index()
    {
        $query = Peminjaman::with(['buku', 'user', 'pengembalian'])->latest();

        if (auth()->user()->peran !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return view('peminjaman.index', ['peminjaman' => $query->get()]);
    }

    // Pinjam buku
    public function store(Buku $buku)
    {
        $sudahPinjam = Peminjaman::where('user_id', auth()->id())
            ->where('buku_id', $buku->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Kamu masih meminjam buku ini.');
        }

        $berhasil = DB::transaction(function () use ($buku) {
            $buku = Buku::lockForUpdate()->findOrFail($buku->id);

            if ($buku->stok < 1) {
                return false;
            }

            Peminjaman::create([
                'user_id'         => auth()->id(),
                'buku_id'         => $buku->id,
                'tanggal_pinjam'  => today(),
                'tanggal_kembali' => today()->addDays(self::LAMA_PINJAM_HARI),
                'status'          => 'dipinjam',
            ]);

            $buku->decrement('stok');

            return true;
        });

        if (! $berhasil) {
            return back()->with('error', 'Stok buku habis.');
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Buku berhasil dipinjam. Batas kembali: ' . today()->addDays(self::LAMA_PINJAM_HARI)->format('d M Y') . '.');
    }

    // Kembalikan buku
    public function kembalikan(Peminjaman $peminjaman)
    {
        $user = auth()->user();

        if ($user->peran !== 'admin' && $peminjaman->user_id !== $user->id) {
            abort(403);
        }

        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        DB::transaction(function () use ($peminjaman) {
            $hariTerlambat = 0;
            if (today()->gt($peminjaman->tanggal_kembali)) {
                $hariTerlambat = (int) $peminjaman->tanggal_kembali->diffInDays(today());
            }

            Pengembalian::create([
                'peminjaman_id'        => $peminjaman->id,
                'tanggal_dikembalikan' => today(),
                'denda'                => $hariTerlambat * self::DENDA_PER_HARI,
            ]);

            $peminjaman->update(['status' => 'dikembalikan']);
            $peminjaman->buku()->increment('stok');
        });

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }
}
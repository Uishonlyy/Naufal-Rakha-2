<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Buku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //asmin
        User::create([
            'name' => 'admin',
            'email' => 'adminperpus@email.com',
            'password' => Hash::make('password'),
            'peran' => 'admin',
            'kelas' => 'Guru/Staff',
        ]);

        //siswa
        User::create([
            'name' => 'naufal',
            'email' => 'naufal@email.com',
            'password' => Hash::make('password'),
            'peran' => 'siswa',
            'kelas' => 'XII RPL 3'
        ]);

        //kategori buku
        $kategoriTeknologi = Kategori::create(['nama_kategori' => 'Teknologi & Pemrograman']);
        $kategoriFiksi = Kategori::create(['nama_kategori' => 'Novel & Fiksi']);
        $kategoriSains = Kategori::create(['nama_kategori' => 'Sains & Pengetahuan']);

        //data buku
        Buku::create([
            'kategoris_id' => $kategoriFiksi->id,
            'judul' => 'Laut Bercerita',
            'penulis' => 'Leila S. Chudori',
            'penerbit' => 'KPG',
            'tahun_terbit' => 2017,
            'stok' => 10,
        ]);

        Buku::create([
            'kategoris_id' => $kategoriTeknologi->id,
            'judul' => 'To Kill a Mockingbird',
            'penulis' => 'Harper Lee',
            'penerbit' => 'Penerbit Qanita',
            'tahun_terbit' => 1960,
            'stok' => 10,
        ]);

        Buku::create([
            'kategoris_id' => $kategoriSains->id,
            'judul' => 'Qorzology',
            'penulis' => 'Naufal Rakha',
            'penerbit' => 'Qoliber',
            'tahun_terbit' => 2026,
            'stok' => 10,
        ]);
    }
}
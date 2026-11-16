<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $anggota = User::where('peran', 'siswa')
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->q;
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('kelas', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->get();

        return view('anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('anggota.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'kelas'    => ['nullable', 'string', 'max:255'],
            'password' => ['required', Password::defaults()],
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'kelas'    => $data['kelas'] ?? null,
            'password' => Hash::make($data['password']),
            'peran'    => 'siswa',
        ]);

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(User $anggota)
    {
        abort_if($anggota->peran !== 'siswa', 404);

        return view('anggota.edit', compact('anggota'));
    }

    public function update(Request $request, User $anggota)
    {
        abort_if($anggota->peran !== 'siswa', 404);

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($anggota->id)],
            'kelas'    => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', Password::defaults()],
        ]);

        $anggota->name  = $data['name'];
        $anggota->email = $data['email'];
        $anggota->kelas = $data['kelas'] ?? null;

        if (! empty($data['password'])) {
            $anggota->password = Hash::make($data['password']);
        }

        $anggota->save();

        return redirect()->route('anggota.index')->with('success', 'Data anggota diperbarui.');
    }

    public function destroy(User $anggota)
    {
        abort_if($anggota->peran !== 'siswa', 404);

        $anggota->delete();

        return redirect()->route('anggota.index')->with('success', 'Anggota dihapus.');
    }
}
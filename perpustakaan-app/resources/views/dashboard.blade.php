<x-app-layout>
    {{-- Top bar --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Halaman Buku
            </h2>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700">
                {{ ucfirst(Auth::user()->peran) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Koleksi Buku</h3>

                    @if (Auth::user()->peran === 'admin')
                        <a href="{{ route('buku.create') }}"
                           class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700">
                            + Tambah Buku
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-end gap-3 mb-4">
        <div class="flex-1 min-w-[200px]">
            <label for="q" class="block text-xs font-medium text-gray-500 mb-1">Cari</label>
            <input id="q" name="q" type="text" value="{{ request('q') }}"
                   placeholder="Judul, penulis, penerbit, kategori..."
                   class="w-full border-gray-300 rounded-md shadow-sm text-sm">
        </div>

        <div>
            <label for="kategori" class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
            <select id="kategori" name="kategori" class="border-gray-300 rounded-md shadow-sm text-sm">
                <option value="">Semua kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-700 pb-2">
            <input type="checkbox" name="tersedia" value="1" {{ request('tersedia') ? 'checked' : '' }}
                   class="rounded border-gray-300">
            Stok tersedia
        </label>

        <div class="flex items-center gap-2">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">
                Cari
            </button>
            @if (request()->hasAny(['q', 'kategori', 'tersedia']))
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:underline">Reset</a>
            @endif
        </div>
    </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Judul</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3">Penulis</th>
                                <th class="px-6 py-3">Penerbit</th>
                                <th class="px-6 py-3">Tahun</th>
                                <th class="px-6 py-3">Stok</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse ($buku as $item)
                                <tr>
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->judul }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $item->penulis }}</td>
                                    <td class="px-6 py-4">{{ $item->penerbit ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $item->tahun_terbit ?? '-' }}</td>
                                    <td class="px-6 py-4 font-semibold">{{ $item->stok }}</td>

                                    <td class="px-6 py-4">
                                        @if (Auth::user()->peran === 'admin')
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('buku.edit', $item) }}"
                                                   class="px-3 py-1 bg-yellow-500 text-white text-xs font-semibold rounded hover:bg-yellow-600">
                                                    Edit
                                                </a>

                                                <form method="POST" action="{{ route('buku.destroy', $item) }}"
                                                      onsubmit="return confirm('Hapus buku ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @else
    @if ($item->stok > 0)
        <form method="POST" action="{{ route('peminjaman.store', $item) }}"
              onsubmit="return confirm('Pinjam buku ini?')">
            @csrf
            <button type="submit"
                    class="px-3 py-1 bg-indigo-600 text-white text-xs font-semibold rounded hover:bg-indigo-700">
                Pinjam
            </button>
        </form>
    @else
        <span class="text-xs text-red-500 font-semibold">Stok Habis</span>
    @endif
@endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data buku.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
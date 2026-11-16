<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Anggota</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Anggota</h3>
                    <a href="{{ route('anggota.create') }}"
                       class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700">
                        + Tambah Anggota
                    </a>
                </div>

                <form method="GET" action="{{ route('anggota.index') }}" class="flex items-end gap-3 mb-4">
                    <div class="flex-1">
                        <label for="q" class="block text-xs font-medium text-gray-500 mb-1">Cari</label>
                        <input id="q" name="q" type="text" value="{{ request('q') }}"
                               placeholder="Nama, email, kelas..."
                               class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    </div>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">
                        Cari
                    </button>
                    @if (request()->filled('q'))
                        <a href="{{ route('anggota.index') }}" class="text-sm text-gray-600 hover:underline pb-2">Reset</a>
                    @endif
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Kelas</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse ($anggota as $item)
                                <tr>
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->name }}</td>
                                    <td class="px-6 py-4">{{ $item->email }}</td>
                                    <td class="px-6 py-4">{{ $item->kelas ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('anggota.edit', $item) }}"
                                               class="px-3 py-1 bg-yellow-500 text-white text-xs font-semibold rounded hover:bg-yellow-600">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('anggota.destroy', $item) }}"
                                                  onsubmit="return confirm('Hapus anggota ini beserta riwayat peminjamannya?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        {{ request()->filled('q') ? 'Tidak ada anggota yang cocok.' : 'Belum ada anggota.' }}
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
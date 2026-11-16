<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->peran === 'admin' ? ' Semua Transaksi' : ' Peminjaman Saya' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                                <th class="px-6 py-3">No</th>
                                @if (Auth::user()->peran === 'admin')
                                    <th class="px-6 py-3">Peminjam</th>
                                @endif
                                <th class="px-6 py-3">Buku</th>
                                <th class="px-6 py-3">Tgl Pinjam</th>
                                <th class="px-6 py-3">Batas Kembali</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Denda</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse ($peminjaman as $item)
                                <tr>
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                    @if (Auth::user()->peran === 'admin')
                                        <td class="px-6 py-4">
                                            {{ $item->user->name }}
                                            <div class="text-xs text-gray-400">{{ $item->user->kelas }}</div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->buku->judul }}</td>
                                    <td class="px-6 py-4">{{ $item->tanggal_pinjam->format('d M Y') }}</td>
                                    <td class="px-6 py-4">{{ $item->tanggal_kembali->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        @if ($item->status === 'dipinjam')
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Dipinjam</span>
                                            @if (today()->gt($item->tanggal_kembali))
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">Terlambat</span>
                                            @endif
                                        @else
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">Dikembalikan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($item->pengembalian)
                                            Rp {{ number_format($item->pengembalian->denda, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($item->status === 'dipinjam')
                                            <form method="POST" action="{{ route('peminjaman.kembali', $item) }}"
                                                  onsubmit="return confirm('Kembalikan buku ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="px-3 py-1 bg-indigo-600 text-white text-xs font-semibold rounded hover:bg-indigo-700">
                                                    Kembalikan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data peminjaman.
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
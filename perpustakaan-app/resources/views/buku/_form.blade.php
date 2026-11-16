<div class="space-y-4">
    <div>
        <x-input-label for="judul" value="Judul" />
        <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full"
                      :value="old('judul', $buku->judul ?? '')" required />
        <x-input-error :messages="$errors->get('judul')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="kategoris_id" value="Kategori" />
        <select id="kategoris_id" name="kategoris_id" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">-- Pilih kategori --</option>
            @foreach ($kategori as $k)
                <option value="{{ $k->id }}"
                    {{ old('kategoris_id', $buku->kategoris_id ?? '') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kategori }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('kategoris_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="penulis" value="Penulis" />
        <x-text-input id="penulis" name="penulis" type="text" class="mt-1 block w-full"
                      :value="old('penulis', $buku->penulis ?? '')" required />
        <x-input-error :messages="$errors->get('penulis')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="penerbit" value="Penerbit" />
        <x-text-input id="penerbit" name="penerbit" type="text" class="mt-1 block w-full"
                      :value="old('penerbit', $buku->penerbit ?? '')" />
        <x-input-error :messages="$errors->get('penerbit')" class="mt-2" />
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <x-input-label for="tahun_terbit" value="Tahun Terbit" />
            <x-text-input id="tahun_terbit" name="tahun_terbit" type="number" class="mt-1 block w-full"
                          :value="old('tahun_terbit', $buku->tahun_terbit ?? '')" />
            <x-input-error :messages="$errors->get('tahun_terbit')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="stok" value="Stok" />
            <x-text-input id="stok" name="stok" type="number" min="0" class="mt-1 block w-full"
                          :value="old('stok', $buku->stok ?? 0)" required />
            <x-input-error :messages="$errors->get('stok')" class="mt-2" />
        </div>
    </div>
</div>
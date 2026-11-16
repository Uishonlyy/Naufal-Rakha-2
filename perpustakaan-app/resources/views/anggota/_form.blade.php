<div class="space-y-4">
    <div>
        <x-input-label for="name" value="Nama" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                      :value="old('name', $anggota->name ?? '')" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                      :value="old('email', $anggota->email ?? '')" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="kelas" value="Kelas" />
        <x-text-input id="kelas" name="kelas" type="text" class="mt-1 block w-full"
                      :value="old('kelas', $anggota->kelas ?? '')" />
        <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password" value="Password" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                      :required="! isset($anggota)" />
        @isset($anggota)
            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
        @endisset
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>
</div>
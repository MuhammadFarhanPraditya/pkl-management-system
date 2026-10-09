<x-filament-panels::page>
    <div style="max-width: 56rem; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

        @php
            $isComplete = !empty($this->nip) && !empty($this->phone_number);
            $user = auth()->user();
            $teacher = $user?->teacher;
        @endphp

        {{-- Status Alert --}}
        @if ($isComplete)
            <x-filament::section icon="heroicon-o-check-badge" icon-color="success">
                <x-slot name="heading">Profil & Kontak Guru Lengkap</x-slot>
                <x-slot name="description">
                    Nomor WhatsApp dan NIP sudah terisi. Siswa bimbingan dapat langsung menghubungi Anda melalui tombol WhatsApp di dashboard mereka.
                </x-slot>
                @if ($teacher)
                    <x-filament::button
                        tag="a"
                        href="{{ route('reports.teacher.letter', $teacher) }}"
                        target="_blank"
                        color="gray"
                        icon="heroicon-o-document-text"
                        size="sm"
                    >
                        Cetak Surat Tugas Pembimbing
                    </x-filament::button>
                @endif
            </x-filament::section>
        @else
            <x-filament::section icon="heroicon-o-exclamation-triangle" icon-color="warning">
                <x-slot name="heading">Profil Belum Lengkap</x-slot>
                <x-slot name="description">
                    Harap lengkapi NIP dan Nomor WhatsApp Anda agar siswa bimbingan dapat menghubungi dan melihat data pembimbing mereka.
                </x-slot>
                @if ($teacher)
                    <x-filament::button
                        tag="a"
                        href="{{ route('reports.teacher.letter', $teacher) }}"
                        target="_blank"
                        color="gray"
                        icon="heroicon-o-document-text"
                        size="sm"
                    >
                        Cetak Surat Tugas Pembimbing
                    </x-filament::button>
                @endif
            </x-filament::section>
        @endif

        {{-- Form Edit Profil --}}
        <form wire:submit.prevent="save" style="display: flex; flex-direction: column; gap: 1.5rem;">

            <x-filament::section>
                <x-slot name="heading">Informasi Pribadi & Kontak Bimbingan</x-slot>
                <x-slot name="description">
                    Informasi ini akan ditampilkan kepada siswa kelas 11 yang Anda bimbing selama periode PKL.
                </x-slot>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-top: 1rem;">

                    {{-- Nama --}}
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.375rem;">
                            Nama Lengkap & Gelar <span style="color: #ef4444;">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="name"
                            style="width: 100%; padding: 0.5rem 0.875rem; font-size: 0.875rem; border-radius: 0.5rem; border: 1px solid rgba(156,163,175,0.5); background: transparent; box-sizing: border-box;"
                            placeholder="Contoh: Budi Santoso, S.Kom."
                        >
                        @error('name') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    {{-- NIP --}}
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.375rem;">
                            NIP (Nomor Induk Pegawai) <span style="color: #ef4444;">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="nip"
                            style="width: 100%; padding: 0.5rem 0.875rem; font-size: 0.875rem; border-radius: 0.5rem; border: 1px solid rgba(156,163,175,0.5); background: transparent; box-sizing: border-box;"
                            placeholder="Contoh: 19850215 201001 1 008"
                        >
                        @error('nip') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    {{-- WhatsApp --}}
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.375rem;">
                            Nomor WhatsApp / HP Aktif <span style="color: #ef4444;">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="phone_number"
                            style="width: 100%; padding: 0.5rem 0.875rem; font-size: 0.875rem; border-radius: 0.5rem; border: 1px solid rgba(156,163,175,0.5); background: transparent; box-sizing: border-box;"
                            placeholder="Contoh: 081234567890"
                        >
                        <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">Digunakan oleh siswa untuk konsultasi jurnal PKL.</p>
                        @error('phone_number') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.375rem;">
                            Alamat Email Akun <span style="color: #ef4444;">*</span>
                        </label>
                        <input
                            type="email"
                            wire:model="email"
                            style="width: 100%; padding: 0.5rem 0.875rem; font-size: 0.875rem; border-radius: 0.5rem; border: 1px solid rgba(156,163,175,0.5); background: transparent; box-sizing: border-box;"
                            placeholder="email@sekolah.sch.id"
                        >
                        @error('email') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">Keamanan Akun & Ganti Password</x-slot>
                <x-slot name="description">Kosongkan jika Anda tidak ingin mengubah password akun Anda.</x-slot>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-top: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.375rem;">Password Baru</label>
                        <input
                            type="password"
                            wire:model="password"
                            style="width: 100%; padding: 0.5rem 0.875rem; font-size: 0.875rem; border-radius: 0.5rem; border: 1px solid rgba(156,163,175,0.5); background: transparent; box-sizing: border-box;"
                            placeholder="Minimal 6 karakter"
                        >
                        @error('password') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.375rem;">Konfirmasi Password Baru</label>
                        <input
                            type="password"
                            wire:model="password_confirmation"
                            style="width: 100%; padding: 0.5rem 0.875rem; font-size: 0.875rem; border-radius: 0.5rem; border: 1px solid rgba(156,163,175,0.5); background: transparent; box-sizing: border-box;"
                            placeholder="Ketik ulang password baru"
                        >
                    </div>
                </div>
            </x-filament::section>

            {{-- Tombol Simpan --}}
            <div style="display: flex; justify-content: flex-end;">
                <x-filament::button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    color="primary"
                    icon="heroicon-o-check"
                    size="md"
                >
                    <span wire:loading.remove wire:target="save">Simpan Perubahan Profil</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </x-filament::button>
            </div>

        </form>
    </div>
</x-filament-panels::page>

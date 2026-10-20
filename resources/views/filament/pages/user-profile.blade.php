<x-filament-panels::page>
    <div style="max-width: 52rem; margin: 0 auto; display: flex; flex-direction: column; gap: 1.75rem;">

        {{-- Neo-Brutalism Profile Header Card --}}
        <div style="
            background: var(--nb-bg-card);
            border: var(--nb-border);
            border-radius: var(--nb-radius-md);
            box-shadow: var(--nb-shadow-md);
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        ">
            <div style="display: flex; align-items: center; gap: 20px;">
                {{-- Avatar Image / Initial --}}
                <div style="
                    width: 76px;
                    height: 76px;
                    border-radius: var(--nb-radius-md);
                    background: var(--nb-primary);
                    border: 2px solid var(--nb-border-color);
                    box-shadow: 3px 3px 0px var(--nb-border-color);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                    flex-shrink: 0;
                ">
                    @if ($current_avatar_url)
                        <img src="{{ $current_avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="
                            font-family: 'Space Grotesk', sans-serif;
                            font-size: 2rem;
                            font-weight: 800;
                            color: #0D0D0D;
                        ">
                            {{ strtoupper(substr($name, 0, 1)) }}
                        </span>
                    @endif
                </div>

                <div>
                    <span style="
                        background: var(--nb-primary);
                        color: #FFFFFF;
                        border: 1px solid var(--nb-border-color);
                        border-radius: 4px;
                        padding: 2px 8px;
                        font-family: 'Space Grotesk', sans-serif;
                        font-size: 0.72rem;
                        font-weight: 700;
                        text-transform: uppercase;
                        letter-spacing: 0.05em;
                    ">
                        {{ $role }}
                    </span>
                    <h2 style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.4rem; margin: 6px 0 2px 0; color: var(--nb-text);">
                        {{ $name }}
                    </h2>
                    <p style="font-family: 'DM Sans', sans-serif; font-size: 0.875rem; color: var(--nb-text); opacity: 0.75; margin: 0;">
                        {{ $email }}
                    </p>
                </div>
            </div>

            <div style="
                background: rgba(59, 130, 246, 0.1);
                border: 2px solid var(--nb-primary);
                border-radius: var(--nb-radius-sm);
                padding: 10px 16px;
                box-shadow: 2px 2px 0px var(--nb-border-color);
            ">
                <span style="font-family: 'Space Grotesk', sans-serif; font-size: 0.8rem; font-weight: 700; color: var(--nb-primary);">
                    🛡️ Akun Aktif Terverifikasi
                </span>
            </div>
        </div>

        {{-- Section 1: Informasi Profil & Foto --}}
        <form wire:submit.prevent="updateProfile">
            <x-filament::section>
                <x-slot name="heading">Informasi Profil & Foto</x-slot>
                <x-slot name="description">Perbarui nama pengguna, email aktif, dan foto profil yang muncul di sistem.</x-slot>

                <div style="display: flex; flex-direction: column; gap: 1.5rem; margin-top: 1rem;">
                    
                    {{-- Upload Foto Profil --}}
                    <div>
                        <label class="fi-field-label" style="display: block; margin-bottom: 0.5rem;">
                            Foto Profil
                        </label>
                        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                            <div style="
                                width: 64px;
                                height: 64px;
                                border-radius: var(--nb-radius-sm);
                                border: 2px solid var(--nb-border-color);
                                box-shadow: 2px 2px 0px var(--nb-border-color);
                                overflow: hidden;
                                background: var(--nb-bg-main);
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                @if ($avatar)
                                    <img src="{{ $avatar->temporaryUrl() }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                                @elseif ($current_avatar_url)
                                    <img src="{{ $current_avatar_url }}" alt="Current" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="font-size: 1.5rem;">👤</span>
                                @endif
                            </div>

                            <div style="flex: 1; min-width: 220px;">
                                <input
                                    type="file"
                                    wire:model="avatar"
                                    accept="image/png, image/jpeg, image/jpg, image/webp"
                                    style="
                                        font-family: 'DM Sans', sans-serif;
                                        font-size: 0.85rem;
                                        color: var(--nb-text);
                                    "
                                />
                                <div wire:loading wire:target="avatar" style="font-size: 0.8rem; color: var(--nb-primary); font-weight: 600; margin-top: 4px;">
                                    ⏳ Mengunggah foto...
                                </div>
                                <p style="font-size: 0.75rem; color: var(--nb-text); opacity: 0.65; margin: 4px 0 0 0;">
                                    Format: JPG, PNG, WEBP. Maksimal 2 MB.
                                </p>
                            </div>

                            @if ($current_avatar_url)
                                <button
                                    type="button"
                                    wire:click="removeAvatar"
                                    wire:confirm="Yakin ingin menghapus foto profil Anda?"
                                    style="
                                        background: rgba(255, 77, 77, 0.1);
                                        color: var(--nb-red);
                                        border: 2px solid var(--nb-red);
                                        border-radius: var(--nb-radius-sm);
                                        box-shadow: 2px 2px 0px var(--nb-border-color);
                                        padding: 6px 14px;
                                        font-family: 'Space Grotesk', sans-serif;
                                        font-weight: 700;
                                        font-size: 0.78rem;
                                        cursor: pointer;
                                    "
                                >
                                    🗑️ Hapus Foto
                                </button>
                            @endif
                        </div>
                        @error('avatar')
                            <p style="color: var(--nb-red); font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        {{-- Nama --}}
                        <div>
                            <label for="name" class="fi-field-label" style="display: block; margin-bottom: 0.375rem;">
                                Nama Lengkap <span style="color: var(--nb-red);">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                wire:model.defer="name"
                                class="fi-input"
                                style="width: 100%; padding: 0.625rem 0.875rem;"
                                required
                            />
                            @error('name')
                                <p style="color: var(--nb-red); font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="fi-field-label" style="display: block; margin-bottom: 0.375rem;">
                                Alamat Email <span style="color: var(--nb-red);">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                wire:model.defer="email"
                                class="fi-input"
                                style="width: 100%; padding: 0.625rem 0.875rem;"
                                required
                            />
                            @error('email')
                                <p style="color: var(--nb-red); font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button
                            type="submit"
                            class="fi-btn fi-btn-color-primary"
                            style="padding: 10px 24px; cursor: pointer;"
                        >
                            💾 Simpan Perubahan Profil
                        </button>
                    </div>

                </div>
            </x-filament::section>
        </form>

        {{-- Section 2: Ganti Password (Tanpa Logout Paksa) --}}
        <form wire:submit.prevent="updatePassword">
            <x-filament::section>
                <x-slot name="heading">Keamanan & Ubah Password</x-slot>
                <x-slot name="description">
                    Perbarui kata sandi akun Anda. Anda akan tetap masuk setelah mengubah kata sandi tanpa perlu login ulang.
                </x-slot>

                <div style="display: flex; flex-direction: column; gap: 1.25rem; margin-top: 1rem;">
                    
                    {{-- Alert Opsi Kirim Email Reset jika Lupa Password Lama --}}
                    <div style="
                        background: rgba(59, 130, 246, 0.08);
                        border: 1px dashed var(--nb-primary);
                        border-radius: var(--nb-radius-sm);
                        padding: 12px 16px;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        flex-wrap: wrap;
                        gap: 12px;
                    ">
                        <div>
                            <p style="font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 0.85rem; color: var(--nb-text); margin: 0 0 2px 0;">
                                🔑 Lupa Kata Sandi Saat Ini?
                            </p>
                            <p style="font-size: 0.78rem; color: var(--nb-text); opacity: 0.75; margin: 0;">
                                Kirim tautan pembuatan kata sandi baru langsung ke kotak masuk email Anda (<strong>{{ $email }}</strong>).
                            </p>
                        </div>
                        <button
                            type="button"
                            wire:click="sendPasswordResetEmail"
                            wire:loading.attr="disabled"
                            style="
                                background: var(--nb-bg-card);
                                color: var(--nb-primary);
                                border: 2px solid var(--nb-primary);
                                border-radius: var(--nb-radius-sm);
                                box-shadow: 2px 2px 0px var(--nb-border-color);
                                padding: 6px 14px;
                                font-family: 'Space Grotesk', sans-serif;
                                font-weight: 700;
                                font-size: 0.8rem;
                                cursor: pointer;
                                white-space: nowrap;
                            "
                        >
                            <span wire:loading.remove wire:target="sendPasswordResetEmail">✉️ Kirim Tautan Reset ke Email</span>
                            <span wire:loading wire:target="sendPasswordResetEmail">⏳ Mengirim Tautan...</span>
                        </button>
                    </div>

                    {{-- Current Password --}}
                    <div>
                        <label for="current_password" class="fi-field-label" style="display: block; margin-bottom: 0.375rem;">
                            Kata Sandi Saat Ini <span style="color: var(--nb-red);">*</span>
                        </label>
                        <input
                            type="password"
                            id="current_password"
                            wire:model.defer="current_password"
                            class="fi-input"
                            style="width: 100%; max-width: 28rem; padding: 0.625rem 0.875rem;"
                            placeholder="Masukkan password akun Anda sekarang"
                            required
                        />
                        @error('current_password')
                            <p style="color: var(--nb-red); font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        {{-- New Password --}}
                        <div>
                            <label for="password" class="fi-field-label" style="display: block; margin-bottom: 0.375rem;">
                                Kata Sandi Baru <span style="color: var(--nb-red);">*</span>
                            </label>
                            <input
                                type="password"
                                id="password"
                                wire:model.defer="password"
                                class="fi-input"
                                style="width: 100%; padding: 0.625rem 0.875rem;"
                                placeholder="Minimal 6 karakter"
                                required
                            />
                            @error('password')
                                <p style="color: var(--nb-red); font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Confirmation --}}
                        <div>
                            <label for="password_confirmation" class="fi-field-label" style="display: block; margin-bottom: 0.375rem;">
                                Konfirmasi Kata Sandi Baru <span style="color: var(--nb-red);">*</span>
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                wire:model.defer="password_confirmation"
                                class="fi-input"
                                style="width: 100%; padding: 0.625rem 0.875rem;"
                                placeholder="Ulangi kata sandi baru"
                                required
                            />
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-top: 0.5rem;">
                        <button
                            type="submit"
                            class="fi-btn"
                            style="
                                background: var(--nb-text);
                                color: var(--nb-bg-main);
                                padding: 10px 24px;
                                cursor: pointer;
                                border: 2px solid var(--nb-border-color);
                                box-shadow: var(--nb-shadow-sm);
                            "
                        >
                            🔒 Perbarui Kata Sandi
                        </button>
                    </div>

                </div>
            </x-filament::section>
        </form>

    </div>
</x-filament-panels::page>

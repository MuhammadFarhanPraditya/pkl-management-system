<x-filament-panels::page>
    @if (! $hasPlacement)
        <x-filament::section icon="heroicon-o-building-office" icon-color="primary">
            <x-slot name="heading">
                Formulir Penempatan PKL
            </x-slot>
            <x-slot name="description">
                Silakan isi data tempat pelaksanaan Praktik Kerja Lapangan (PKL) Anda di bawah ini.
            </x-slot>
            
            <form wire:submit="savePlacement" style="margin-top: 1rem;">
                {{ $this->form }}

                <div style="margin-top: 1.5rem;">
                    <x-filament::button type="submit" color="primary" size="md">
                        Simpan Informasi PKL
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>
    @else
        {{-- 1. Kartu Ringkasan Status & Durasi PKL --}}
        <x-filament::section>
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: 1rem; border-bottom: 1px solid rgba(156, 163, 175, 0.2); padding-bottom: 1.25rem;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <x-filament::badge color="success">
                            Status: PKL Aktif Berjalan
                        </x-filament::badge>
                        <span style="font-size: 0.75rem; color: #9ca3af;">•</span>
                        <span style="font-size: 0.875rem; color: #6b7280;">{{ $student->class_name }} &mdash; {{ $student->major }}</span>
                    </div>
                    <h2 style="font-size: 1.375rem; font-weight: 700; margin: 0 0 0.25rem 0;">
                        {{ $company->name ?? 'Mitra DU-DI' }}
                    </h2>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">
                        Siswa Magang: <strong style="color: inherit;">{{ $student->user->name ?? '-' }}</strong> (NISN: {{ $student->nisn }})
                    </p>
                </div>
                <div style="background: rgba(156, 163, 175, 0.1); border-radius: 0.5rem; padding: 0.75rem 1rem; text-align: right;">
                    <div style="font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">
                        Periode Pelaksanaan
                    </div>
                    <div style="font-size: 0.95rem; font-weight: 700; margin-top: 0.25rem;">
                        {{ $startDate }} &mdash; {{ $endDate }}
                    </div>
                </div>
            </div>

            {{-- Progress Bar Waktu --}}
            <div style="margin-top: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8125rem; font-weight: 500; margin-bottom: 0.5rem;">
                    <span>
                        Progres Waktu: <strong>Hari ke-{{ $daysPassed }}</strong> dari total {{ $totalDays }} hari
                    </span>
                    <span style="font-weight: 700; color: #3b82f6;">
                        {{ $progressPercent }}% Selesai (Sisa {{ $daysRemaining }} hari)
                    </span>
                </div>
                <div style="width: 100%; height: 10px; background-color: rgba(156, 163, 175, 0.2); border-radius: 9999px; overflow: hidden;">
                    <div style="width: {{ $progressPercent }}%; height: 100%; background: linear-gradient(90deg, #3b82f6, #10b981); border-radius: 9999px; transition: width 0.4s ease;"></div>
                </div>
            </div>
        </x-filament::section>

        {{-- 2. Grid Kontak Pembimbing & Tempat PKL --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
            {{-- Kartu Guru Pembimbing --}}
            <x-filament::section icon="heroicon-o-academic-cap" icon-color="primary">
                <x-slot name="heading">
                    Guru Pembimbing Sekolah
                </x-slot>
                <x-slot name="description">
                    Pembimbing Internal SMK
                </x-slot>

                <div style="display: flex; flex-direction: column; gap: 0.875rem; margin-top: 0.75rem; min-height: 160px;">
                    <div>
                        <div style="font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; font-weight: 600;">Nama Guru</div>
                        <div style="font-size: 1rem; font-weight: 700; margin-top: 0.125rem;">
                            {{ $teacher->user->name ?? 'Belum Ditentukan' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; font-weight: 600;">NIP</div>
                        <div style="font-size: 0.875rem; font-weight: 500; margin-top: 0.125rem;">
                            {{ $teacher->nip ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; font-weight: 600;">Email</div>
                        <div style="font-size: 0.875rem; font-weight: 500; margin-top: 0.125rem;">
                            {{ $teacher->user->email ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; font-weight: 600;">No. WhatsApp / HP</div>
                        <div style="font-size: 0.875rem; font-weight: 500; margin-top: 0.125rem;">
                            {{ $teacher->phone_number ?? '-' }}
                        </div>
                    </div>
                </div>

                <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid rgba(156, 163, 175, 0.2);">
                    @if ($waTeacherUrl)
                        <x-filament::button
                            tag="a"
                            href="{{ $waTeacherUrl }}"
                            target="_blank"
                            color="success"
                            icon="heroicon-o-chat-bubble-left-ellipsis"
                            size="lg"
                            style="width: 100%; justify-content: center;"
                        >
                            Hubungi Guru via WhatsApp
                        </x-filament::button>
                    @else
                        <div style="margin-bottom: 0.75rem; padding: 0.5rem 0.75rem; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 0.375rem; font-size: 0.75rem; color: #b45309;">
                            ⚠️ Kontak WhatsApp guru sedang diperbarui. Hubungi via email di atas jika ada keperluan mendesak.
                        </div>
                        <x-filament::button color="gray" disabled style="width: 100%; justify-content: center;">
                            Nomor WhatsApp Belum Tersedia
                        </x-filament::button>
                    @endif
                </div>
            </x-filament::section>

            {{-- Kartu Mitra DU-DI & Mentor Lapangan --}}
            <x-filament::section icon="heroicon-o-building-office-2" icon-color="primary">
                <x-slot name="heading">
                    Mitra DU-DI & Mentor Lapangan
                </x-slot>
                <x-slot name="description">
                    Tempat Pelaksanaan Magang / PKL
                </x-slot>

                <div style="display: flex; flex-direction: column; gap: 0.875rem; margin-top: 0.75rem; min-height: 160px;">
                    <div>
                        <div style="font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; font-weight: 600;">Perusahaan / Instansi</div>
                        <div style="font-size: 1rem; font-weight: 700; margin-top: 0.125rem;">
                            {{ $company->name ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; font-weight: 600;">Alamat Kantor</div>
                        <div style="font-size: 0.875rem; font-weight: 500; margin-top: 0.125rem;">
                            {{ $company->address ?? '-' }}, {{ $company->city ?? '' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; font-weight: 600;">PIC / Pembimbing Industri</div>
                        <div style="font-size: 0.875rem; font-weight: 700; color: #10b981; margin-top: 0.125rem;">
                            {{ $company->pic_name ?? 'Belum Ditentukan' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; font-weight: 600;">Kontak PIC Kantor</div>
                        <div style="font-size: 0.875rem; font-weight: 500; margin-top: 0.125rem;">
                            {{ $company->pic_contact ?? '-' }}
                        </div>
                    </div>
                </div>

                <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid rgba(156, 163, 175, 0.2);">
                    @if ($waPicUrl)
                        <x-filament::button
                            tag="a"
                            href="{{ $waPicUrl }}"
                            target="_blank"
                            color="success"
                            icon="heroicon-o-chat-bubble-left-ellipsis"
                            size="lg"
                            style="width: 100%; justify-content: center;"
                        >
                            Hubungi PIC Industri via WhatsApp
                        </x-filament::button>
                    @else
                        <x-filament::button color="gray" disabled style="width: 100%; justify-content: center;">
                            Nomor PIC Belum Tersedia
                        </x-filament::button>
                    @endif
                </div>
            </x-filament::section>
        </div>

        {{-- 3. Panduan Penting & Prosedur Siswa Selama PKL --}}
        <div style="margin-top: 1.5rem;">
            <x-filament::section icon="heroicon-o-information-circle" icon-color="info">
                <x-slot name="heading">
                    Panduan & Prosedur Penting Siswa Selama PKL
                </x-slot>
                <x-slot name="description">
                    Pedoman operasional harian, tata tertib, dan alur perizinan di lokasi magang.
                </x-slot>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-top: 1rem;">
                    {{-- Poin 1 --}}
                    <div style="border: 1px solid rgba(156, 163, 175, 0.2); background: rgba(156, 163, 175, 0.05); border-radius: 0.75rem; padding: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.625rem; margin-bottom: 0.5rem;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem; border-radius: 9999px; background: #3b82f6; color: #fff; font-size: 0.75rem; font-weight: 700;">1</span>
                            <h4 style="font-size: 0.9375rem; font-weight: 700; margin: 0;">Presensi = Jurnal Harian</h4>
                        </div>
                        <p style="font-size: 0.8125rem; color: #6b7280; line-height: 1.5; margin: 0;">
                            Wajib mengisi jurnal kegiatan setiap hari kerja sebelum pukul 17:00 WIB. Mengisi jurnal otomatis merekam kehadiran harian Anda di sistem.
                        </p>
                    </div>

                    {{-- Poin 2 --}}
                    <div style="border: 1px solid rgba(156, 163, 175, 0.2); background: rgba(156, 163, 175, 0.05); border-radius: 0.75rem; padding: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.625rem; margin-bottom: 0.5rem;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem; border-radius: 9999px; background: #eab308; color: #fff; font-size: 0.75rem; font-weight: 700;">2</span>
                            <h4 style="font-size: 0.9375rem; font-weight: 700; margin: 0;">SOP Sakit / Izin</h4>
                        </div>
                        <p style="font-size: 0.8125rem; color: #6b7280; line-height: 1.5; margin: 0;">
                            Wajib konfirmasi ke PIC Industri & Guru Pembimbing via WA sebelum jam 08:00 WIB. Di aplikasi, pilih status presensi "Sakit" atau "Izin" dan upload surat dokter/keterangan.
                        </p>
                    </div>

                    {{-- Poin 3 --}}
                    <div style="border: 1px solid rgba(156, 163, 175, 0.2); background: rgba(156, 163, 175, 0.05); border-radius: 0.75rem; padding: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.625rem; margin-bottom: 0.5rem;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem; border-radius: 9999px; background: #10b981; color: #fff; font-size: 0.75rem; font-weight: 700;">3</span>
                            <h4 style="font-size: 0.9375rem; font-weight: 700; margin: 0;">Etika & Tata Tertib</h4>
                        </div>
                        <p style="font-size: 0.8125rem; color: #6b7280; line-height: 1.5; margin: 0;">
                            Patuhi jam kerja dan standar seragam DU-DI. Jaga kerahasiaan data internal perusahaan, dan jaga nama baik almamater SMK.
                        </p>
                    </div>

                    {{-- Poin 4 --}}
                    <div style="border: 1px solid rgba(156, 163, 175, 0.2); background: rgba(156, 163, 175, 0.05); border-radius: 0.75rem; padding: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.625rem; margin-bottom: 0.5rem;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem; border-radius: 9999px; background: #ef4444; color: #fff; font-size: 0.75rem; font-weight: 700;">4</span>
                            <h4 style="font-size: 0.9375rem; font-weight: 700; margin: 0;">Bantuan & Situasi Darurat</h4>
                        </div>
                        <p style="font-size: 0.8125rem; color: #6b7280; line-height: 1.5; margin: 0;">
                            Jika mengalami kendala berat, intimidasi, ketidaksesuaian tugas, atau bahaya kerja, segera hubungi Guru Pembimbing atau Koordinator PKL sekolah.
                        </p>
                    </div>
                </div>
            </x-filament::section>
        </div>
    @endif
</x-filament-panels::page>

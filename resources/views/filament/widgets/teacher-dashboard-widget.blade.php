<x-filament-widgets::widget>
    <div style="display: flex; flex-direction: column; gap: 1rem;">

        {{-- ===== HEADER ===== --}}
        <x-filament::section>
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                <div>
                    <p style="font-size: 0.75rem; color: #9ca3af; margin: 0;">Selamat datang kembali,</p>
                    <h2 style="font-size: 1.375rem; font-weight: 700; margin: 0.125rem 0;">{{ $teacherName }}</h2>
                    <p style="font-size: 0.8125rem; color: #9ca3af; margin: 0;">
                        Guru Pembimbing PKL &mdash; {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <x-filament::button tag="a" href="{{ $profileUrl }}" color="gray" icon="heroicon-o-user-circle" size="sm">
                        Profil Saya
                    </x-filament::button>
                    <x-filament::button tag="a" href="{{ $journalListUrl }}" color="primary" icon="heroicon-o-document-text" size="sm">
                        Lihat Semua Jurnal
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>

        {{-- ===== ALERT PROFIL BELUM LENGKAP ===== --}}
        @if (! $profileComplete)
        <x-filament::section icon="heroicon-o-exclamation-triangle" icon-color="warning">
            <x-slot name="heading">Profil Belum Lengkap</x-slot>
            <x-slot name="description">
                NIP dan/atau Nomor WhatsApp belum diisi. Siswa tidak dapat melihat kontak Anda.
            </x-slot>
            <x-filament::button tag="a" href="{{ $profileUrl }}" color="warning" icon="heroicon-o-pencil-square" size="sm">
                Lengkapi Profil Sekarang
            </x-filament::button>
        </x-filament::section>
        @endif

        {{-- ===== 3 STAT CARDS ===== --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">

            <x-filament::section icon="heroicon-o-user-group" icon-color="info">
                <x-slot name="heading">Siswa Bimbingan</x-slot>
                <p style="font-size: 2.25rem; font-weight: 800; line-height: 1; margin: 0.5rem 0;">{{ $studentsCount }}</p>
                @if ($studentsNoPlacement->count() > 0)
                    <x-filament::badge color="warning">{{ $studentsNoPlacement->count() }} belum ada tempat PKL</x-filament::badge>
                @else
                    <x-filament::badge color="success">Semua sudah ada tempat PKL ✓</x-filament::badge>
                @endif
            </x-filament::section>

            <x-filament::section
                icon="{{ $pendingCount > 0 ? 'heroicon-o-clock' : 'heroicon-o-check-circle' }}"
                icon-color="{{ $pendingCount > 0 ? 'warning' : 'success' }}"
            >
                <x-slot name="heading">Jurnal Perlu Direview</x-slot>
                <p style="font-size: 2.25rem; font-weight: 800; line-height: 1; margin: 0.5rem 0;">{{ $pendingCount }}</p>
                @if ($pendingCount > 0)
                    <x-filament::badge color="warning">Segera tinjau jurnal masuk</x-filament::badge>
                @else
                    <x-filament::badge color="success">Semua sudah ditinjau ✓</x-filament::badge>
                @endif
            </x-filament::section>

            <x-filament::section
                icon="{{ $profileComplete ? 'heroicon-o-shield-check' : 'heroicon-o-shield-exclamation' }}"
                icon-color="{{ $profileComplete ? 'success' : 'warning' }}"
            >
                <x-slot name="heading">Status Profil</x-slot>
                <div style="margin-top: 0.5rem;">
                    @if ($profileComplete)
                        <x-filament::badge color="success">Lengkap ✓</x-filament::badge>
                    @else
                        <x-filament::badge color="warning">Belum Lengkap</x-filament::badge>
                    @endif
                </div>
                <div style="margin-top: 0.75rem;">
                    <x-filament::button tag="a" href="{{ $profileUrl }}" color="gray" size="xs">
                        {{ $profileComplete ? 'Lihat profil' : 'Lengkapi sekarang' }}
                    </x-filament::button>
                </div>
            </x-filament::section>

        </div>

        {{-- ===== JURNAL MASUK + DAFTAR SISWA ===== --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">

            {{-- Jurnal masuk --}}
            <x-filament::section icon="heroicon-o-inbox-arrow-down" icon-color="warning">
                <x-slot name="heading">Jurnal Masuk &mdash; Perlu Review</x-slot>

                @forelse ($pendingJournals as $journal)
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid rgba(156,163,175,0.15);">
                    <div style="width: 2rem; height: 2rem; flex-shrink: 0; border-radius: 9999px; background: rgba(99,102,241,0.15); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #6366f1; font-size: 0.875rem;">
                        {{ strtoupper(substr($journal->student->user?->name ?? '?', 0, 1)) }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="font-size: 0.875rem; font-weight: 600; margin: 0; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                            {{ $journal->student->user?->name ?? 'Siswa' }}
                        </p>
                        <p style="font-size: 0.75rem; color: #9ca3af; margin: 0; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                            {{ \Carbon\Carbon::parse($journal->date)->format('d/m/Y') }}
                        </p>
                    </div>
                    <x-filament::button
                        tag="a"
                        href="{{ route('filament.admin.resources.daily-journals.index', ['tableFilters[status][value]' => 'submitted']) }}"
                        color="warning"
                        size="xs"
                    >
                        Tinjau di Daftar
                    </x-filament::button>
                </div>
                @empty
                <div style="text-align: center; padding: 1.5rem 0; color: #9ca3af;">
                    <p style="font-size: 0.875rem; font-weight: 600; margin: 0;">Semua jurnal sudah ditinjau ✓</p>
                    <p style="font-size: 0.75rem; margin: 0.25rem 0 0;">Tidak ada jurnal baru saat ini.</p>
                </div>
                @endforelse

                @if ($pendingJournals->count() > 0)
                <div style="margin-top: 0.75rem;">
                    <x-filament::button tag="a" href="{{ $journalListUrl }}" color="gray" size="sm" style="width: 100%;">
                        Lihat Semua Jurnal Masuk →
                    </x-filament::button>
                </div>
                @endif
            </x-filament::section>

            {{-- Daftar siswa --}}
            <x-filament::section icon="heroicon-o-user-group" icon-color="info">
                <x-slot name="heading">Siswa Bimbingan Saya ({{ $studentsCount }})</x-slot>

                @forelse ($students as $student)
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid rgba(156,163,175,0.15);">
                    <div style="width: 2rem; height: 2rem; flex-shrink: 0; border-radius: 9999px; background: rgba(99,102,241,0.15); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #6366f1; font-size: 0.875rem;">
                        {{ strtoupper(substr($student->user?->name ?? '?', 0, 1)) }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="font-size: 0.875rem; font-weight: 600; margin: 0; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                            {{ $student->user?->name ?? '–' }}
                        </p>
                        <p style="font-size: 0.75rem; color: #9ca3af; margin: 0;">
                            {{ $student->class_name }}
                        </p>
                    </div>
                    @if ($student->company)
                        <x-filament::badge color="success">{{ Str::limit($student->company->name, 15) }}</x-filament::badge>
                    @else
                        <x-filament::badge color="gray">Belum PKL</x-filament::badge>
                    @endif
                </div>
                @empty
                <div style="text-align: center; padding: 1.5rem 0; color: #9ca3af;">
                    <p style="font-size: 0.875rem; font-weight: 600; margin: 0;">Belum ada siswa bimbingan</p>
                    <p style="font-size: 0.75rem; margin: 0.25rem 0 0;">Hubungi Admin untuk penugasan siswa.</p>
                </div>
                @endforelse
            </x-filament::section>

        </div>

    </div>
</x-filament-widgets::widget>

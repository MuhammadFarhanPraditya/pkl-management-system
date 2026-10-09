<x-filament-widgets::widget>
    @if (! $hasPlacement)
        <x-filament::section icon="heroicon-o-exclamation-triangle" icon-color="warning">
            <x-slot name="heading">
                Status Presensi: {{ $todayDate }}
            </x-slot>
            <x-slot name="description">
                Akun Anda belum memiliki penempatan PKL aktif. Silakan hubungi Guru Pembimbing atau Koordinator PKL.
            </x-slot>
        </x-filament::section>
    @elseif (! $todayJournal)
        <x-filament::section icon="heroicon-o-clock" icon-color="warning">
            <x-slot name="heading">
                Presensi Hari Ini: {{ $todayDate }}
            </x-slot>
            <x-slot name="description">
                Tempat PKL: {{ $student->company->name ?? '-' }}
            </x-slot>

            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-top: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <x-filament::badge color="warning">
                        BELUM MENGISI PRESENSI
                    </x-filament::badge>
                    <span style="font-size: 0.875rem; color: #9ca3af;">
                        Silakan catat kegiatan harian Anda sebelum jam kerja PKL berakhir.
                    </span>
                </div>
                <div>
                    <x-filament::button
                        tag="a"
                        href="{{ $createJournalUrl }}"
                        color="primary"
                        icon="heroicon-o-plus-circle"
                        size="md"
                    >
                        Isi Presensi & Jurnal Sekarang
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>
    @else
        <x-filament::section icon="heroicon-o-check-circle" icon-color="success">
            <x-slot name="heading">
                Presensi Hari Ini: {{ $todayDate }}
            </x-slot>
            <x-slot name="description">
                Tempat PKL: {{ $student->company->name ?? '-' }}
            </x-slot>

            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-top: 0.5rem;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.375rem;">
                        <x-filament::badge color="success">
                            SUDAH PRESENSI
                        </x-filament::badge>
                        @php
                            $att = $todayJournal->attendance_status ?? 'hadir';
                            $attBadgeColor = match($att) {
                                'hadir' => 'success',
                                'izin' => 'warning',
                                'sakit' => 'danger',
                                default => 'gray',
                            };
                            $reviewStatus = match($todayJournal->status) {
                                'approved' => 'Disetujui Guru',
                                'revision' => 'Perlu Revisi',
                                'submitted' => 'Menunggu Review',
                                default => ucfirst($todayJournal->status),
                            };
                            $reviewBadgeColor = match($todayJournal->status) {
                                'approved' => 'success',
                                'revision' => 'danger',
                                'submitted' => 'warning',
                                default => 'gray',
                            };
                        @endphp
                        <x-filament::badge :color="$attBadgeColor">
                            Status: {{ ucfirst($att) }}
                        </x-filament::badge>
                        <x-filament::badge :color="$reviewBadgeColor">
                            {{ $reviewStatus }}
                        </x-filament::badge>
                    </div>
                    <div style="font-size: 0.875rem; color: #9ca3af; margin-top: 0.25rem;">
                        Aktivitas: <span style="font-style: italic; color: inherit;">"{{ Str::limit($todayJournal->work_description, 100) }}"</span>
                    </div>
                    @if ($todayJournal->teacher_note)
                        <div style="margin-top: 0.5rem; padding: 0.5rem 0.75rem; background: rgba(234, 179, 8, 0.15); border-left: 3px solid #eab308; border-radius: 0.25rem; font-size: 0.8125rem;">
                            <strong>Catatan Guru:</strong> {{ $todayJournal->teacher_note }}
                        </div>
                    @endif
                </div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">

                    <x-filament::button
                        tag="a"
                        href="{{ $editJournalUrl }}"
                        color="gray"
                        icon="heroicon-o-pencil-square"
                        size="sm"
                    >
                        Lihat / Edit Jurnal
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>
    @endif
</x-filament-widgets::widget>

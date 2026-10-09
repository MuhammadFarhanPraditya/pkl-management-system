<x-filament-widgets::widget>
    {{-- ============================================
         DASHBOARD SISWA — NEO-BRUTALISM EDITION
         ============================================ --}}

    @php
        $userName = auth()->user()?->name ?? 'Siswa';
        $firstName = explode(' ', $userName)[0];
    @endphp

    {{-- STATE 1: Belum ada penempatan PKL --}}
    @if (! $hasPlacement)
        <div style="
            background: var(--nb-bg-main);
            border: 2px solid var(--nb-border-color);
            border-radius: 8px;
            box-shadow: 4px 4px 0px var(--nb-border-color);
            padding: 24px;
        ">
            <div style="display: flex; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                <div style="
                    background: #3B82F6;
                    border: 2px solid var(--nb-border-color);
                    border-radius: 8px;
                    width: 56px;
                    height: 56px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                    font-size: 1.75rem;
                ">⚠️</div>
                <div style="flex: 1; min-width: 240px;">
                    <p style="font-size: 0.75rem; font-family: 'Space Grotesk', sans-serif; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin: 0 0 4px 0;">
                        {{ $todayDate }}
                    </p>
                    <h2 style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.25rem; margin: 0 0 8px 0; color: var(--nb-text);">
                        Hei {{ $firstName }}, Kamu Belum Punya Tempat PKL!
                    </h2>
                    <p style="font-size: 0.875rem; color: #4b5563; margin: 0 0 16px 0; line-height: 1.6;">
                        Akun kamu belum memiliki penempatan PKL aktif. Segera isi data tempat PKL-mu agar bisa mulai mengisi presensi dan jurnal harian.
                    </p>
                    <a href="{{ route('filament.admin.pages.student-internship-info') }}" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        background: var(--nb-border-color);
                        color: #3B82F6;
                        font-family: 'Space Grotesk', sans-serif;
                        font-weight: 700;
                        font-size: 0.875rem;
                        padding: 10px 20px;
                        border: 2px solid var(--nb-border-color);
                        border-radius: 4px;
                        box-shadow: 4px 4px 0px #3B82F6;
                        text-decoration: none;
                        transition: all 150ms ease;
                    ">
                        → Isi Data Penempatan PKL
                    </a>
                </div>
            </div>
        </div>

    {{-- STATE 2: Sudah ada PKL tapi belum isi jurnal hari ini --}}
    @elseif (! $todayJournal)
        <div style="
            background: var(--nb-bg-main);
            border: 2px solid var(--nb-border-color);
            border-radius: 8px;
            box-shadow: 4px 4px 0px var(--nb-border-color);
            overflow: hidden;
        ">
            {{-- Header Banner --}}
            <div style="
                background: #3B82F6;
                border-bottom: 2px solid var(--nb-border-color);
                padding: 12px 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 8px;
            ">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 1.25rem;">📋</span>
                    <span style="
                        font-family: 'Space Grotesk', sans-serif;
                        font-weight: 800;
                        font-size: 0.8rem;
                        text-transform: uppercase;
                        letter-spacing: 0.08em;
                        color: var(--nb-text);
                    ">PRESENSI HARI INI BELUM DIISI</span>
                </div>
                <span style="font-family: 'DM Sans', sans-serif; font-size: 0.8rem; font-weight: 500; color: var(--nb-text);">
                    {{ $todayDate }}
                </span>
            </div>

            {{-- Body --}}
            <div style="padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                <div>
                    <p style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.25rem; margin: 0 0 6px 0; color: var(--nb-text);">
                        Hei {{ $firstName }}, jangan lupa presensi! 👋
                    </p>
                    <p style="font-family: 'DM Sans', sans-serif; font-size: 0.875rem; color: #4b5563; margin: 0; line-height: 1.5;">
                        📍 <strong>{{ $student->company->name ?? 'Tempat PKL' }}</strong> &mdash;
                        Catat kegiatan harianmu sebelum jam kerja berakhir ya!
                    </p>
                </div>
                <a href="{{ $createJournalUrl }}" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    background: var(--nb-border-color);
                    color: #3B82F6;
                    font-family: 'Space Grotesk', sans-serif;
                    font-weight: 700;
                    font-size: 0.875rem;
                    padding: 12px 24px;
                    border: 2px solid var(--nb-border-color);
                    border-radius: 4px;
                    box-shadow: 4px 4px 0px #6b7280;
                    text-decoration: none;
                    white-space: nowrap;
                    flex-shrink: 0;
                    transition: all 150ms ease;
                ">
                    ✏️ Isi Jurnal Sekarang
                </a>
            </div>
        </div>

    {{-- STATE 3: Sudah isi jurnal hari ini --}}
    @else
        @php
            $att = $todayJournal->attendance_status ?? 'hadir';
            $attEmoji = match($att) {
                'hadir' => '✅',
                'izin'  => '📝',
                'sakit' => '🤒',
                default => '📋',
            };
            $attLabel = match($att) {
                'hadir' => 'Hadir',
                'izin'  => 'Izin',
                'sakit' => 'Sakit',
                default => ucfirst($att),
            };
            $reviewStatus = $todayJournal->status;
            $reviewLabel = match($reviewStatus) {
                'approved'  => 'Disetujui Guru',
                'revision'  => 'Perlu Revisi',
                'submitted' => 'Menunggu Review',
                'draft'     => 'Draft',
                default     => ucfirst($reviewStatus),
            };
            $bannerBg = match($reviewStatus) {
                'approved'  => '#3B82F6',
                'revision'  => '#FF4D4D',
                'submitted' => '#3B82F6',
                default     => '#D1D5DB',
            };
            $bannerTextColor = $reviewStatus === 'revision' ? '#FFFFFF' : 'var(--nb-border-color)';
            $bannerIcon = match($reviewStatus) {
                'approved'  => '🎉',
                'revision'  => '🔄',
                'submitted' => '⏳',
                default     => '📄',
            };
        @endphp

        <div style="
            background: var(--nb-bg-card);
            border: 2px solid var(--nb-border-color);
            border-radius: 8px;
            box-shadow: 4px 4px 0px var(--nb-border-color);
            overflow: hidden;
        ">
            {{-- Status Banner --}}
            <div style="
                background: {{ $bannerBg }};
                border-bottom: 2px solid var(--nb-border-color);
                padding: 10px 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 8px;
            ">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 1.1rem;">{{ $bannerIcon }}</span>
                    <span style="
                        font-family: 'Space Grotesk', sans-serif;
                        font-weight: 800;
                        font-size: 0.8rem;
                        text-transform: uppercase;
                        letter-spacing: 0.08em;
                        color: {{ $bannerTextColor }};
                    ">{{ $reviewLabel }}</span>
                </div>
                <span style="font-family: 'DM Sans', sans-serif; font-size: 0.8rem; color: {{ $bannerTextColor }}; font-weight: 500;">
                    {{ $todayDate }}
                </span>
            </div>

            {{-- Body --}}
            <div style="padding: 20px 24px;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                    <div style="flex: 1; min-width: 240px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px; flex-wrap: wrap;">
                            {{-- Attendance Badge --}}
                            <span style="
                                background: #F5F0E8;
                                border: 2px solid var(--nb-border-color);
                                border-radius: 4px;
                                padding: 3px 10px;
                                font-family: 'Space Grotesk', sans-serif;
                                font-weight: 700;
                                font-size: 0.75rem;
                                color: var(--nb-text);
                                box-shadow: 2px 2px 0px var(--nb-border-color);
                            ">{{ $attEmoji }} {{ $attLabel }}</span>
                            <span style="
                                background: {{ $bannerBg }};
                                border: 2px solid var(--nb-border-color);
                                border-radius: 4px;
                                padding: 3px 10px;
                                font-family: 'Space Grotesk', sans-serif;
                                font-weight: 700;
                                font-size: 0.75rem;
                                color: {{ $bannerTextColor }};
                                box-shadow: 2px 2px 0px var(--nb-border-color);
                            ">{{ $reviewLabel }}</span>
                        </div>

                        <p style="font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1rem; margin: 0 0 6px 0; color: var(--nb-text);">
                            📍 {{ $student->company->name ?? 'Tempat PKL' }}
                        </p>
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 0.875rem; color: #4b5563; margin: 0; line-height: 1.5;">
                            "{{ Str::limit($todayJournal->work_description, 100) }}"
                        </p>

                        {{-- Catatan Guru (jika ada revisi) --}}
                        @if ($todayJournal->teacher_note)
                            <div style="
                                margin-top: 12px;
                                padding: 10px 14px;
                                background: var(--nb-bg-main);
                                border: 2px solid #FB923C;
                                border-radius: 4px;
                                box-shadow: 2px 2px 0px var(--nb-border-color);
                            ">
                                <p style="font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 4px 0; color: #FB923C;">
                                    💬 Catatan Guru Pembimbing:
                                </p>
                                <p style="font-family: 'DM Sans', sans-serif; font-size: 0.875rem; color: var(--nb-text); margin: 0; line-height: 1.5;">
                                    {{ $todayJournal->teacher_note }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Action Button --}}
                    <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-end; flex-shrink: 0;">
                        @if ($reviewStatus === 'revision')
                            <a href="{{ $editJournalUrl }}" style="
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                background: #FF4D4D;
                                color: white;
                                font-family: 'Space Grotesk', sans-serif;
                                font-weight: 700;
                                font-size: 0.8125rem;
                                padding: 10px 18px;
                                border: 2px solid var(--nb-border-color);
                                border-radius: 4px;
                                box-shadow: 4px 4px 0px var(--nb-border-color);
                                text-decoration: none;
                                white-space: nowrap;
                            ">🔄 Perbaiki Jurnal</a>
                        @else
                            <a href="{{ $editJournalUrl }}" style="
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                background: #F5F0E8;
                                color: var(--nb-text);
                                font-family: 'Space Grotesk', sans-serif;
                                font-weight: 700;
                                font-size: 0.8125rem;
                                padding: 10px 18px;
                                border: 2px solid var(--nb-border-color);
                                border-radius: 4px;
                                box-shadow: 4px 4px 0px var(--nb-border-color);
                                text-decoration: none;
                                white-space: nowrap;
                            ">👁 Lihat Jurnal</a>
                        @endif

                        @if ($printReportUrl)
                            <a href="{{ $printReportUrl }}" target="_blank" style="
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                background: transparent;
                                color: var(--nb-text);
                                font-family: 'Space Grotesk', sans-serif;
                                font-weight: 600;
                                font-size: 0.75rem;
                                padding: 6px 14px;
                                border: 2px solid var(--nb-border-color);
                                border-radius: 4px;
                                text-decoration: none;
                                white-space: nowrap;
                            ">🖨 Cetak Laporan</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-widgets::widget>





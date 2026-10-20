<x-filament-widgets::widget>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Neo-Brutalism Welcome Banner with Quick Shortcuts --}}
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
            gap: 20px;
        ">
            <div style="display: flex; align-items: center; gap: 18px;">
                <div style="
                    background: #FFFFFF;
                    border: 2px solid var(--nb-border-color);
                    border-radius: var(--nb-radius-sm);
                    box-shadow: 2px 2px 0px var(--nb-border-color);
                    width: 68px;
                    height: 68px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                    padding: 4px;
                    flex-shrink: 0;
                ">
                    <img src="{{ asset('images/logo-pustek.png') }}" alt="Logo SMK Pustek" width="60" height="60" loading="lazy" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <span style="
                            background: var(--nb-primary);
                            color: #FFFFFF;
                            font-family: 'Space Grotesk', sans-serif;
                            font-weight: 700;
                            font-size: 0.72rem;
                            padding: 2px 8px;
                            border-radius: 4px;
                            border: 1px solid var(--nb-border-color);
                            text-transform: uppercase;
                        ">
                            SMK PUSTEK SERPONG
                        </span>
                        <span style="font-size: 0.8rem; color: var(--nb-text); opacity: 0.7;">
                            {{ $todayDate }}
                        </span>
                    </div>
                    <h2 style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.4rem; margin: 0; color: var(--nb-text);">
                        Pusat Monitoring PKL (Administrator)
                    </h2>
                    <p style="font-family: 'DM Sans', sans-serif; font-size: 0.875rem; color: var(--nb-text); opacity: 0.8; margin: 4px 0 0 0;">
                        Kelola data siswa, pembagian guru pembimbing, verifikasi mitra, dan tinjau seluruh rekap jurnal.
                    </p>
                </div>
            </div>

            {{-- Action Shortcuts --}}
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="{{ $studentsUrl }}" class="fi-btn fi-btn-color-primary" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 8px 16px;
                    font-size: 0.8125rem;
                    text-decoration: none;
                ">
                    👨‍🎓 Data Siswa
                </a>
                <a href="{{ $teachersUrl }}" class="fi-btn" style="
                    background: var(--nb-bg-main);
                    color: var(--nb-text);
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 8px 16px;
                    font-size: 0.8125rem;
                    text-decoration: none;
                ">
                    👨‍🏫 Guru Pembimbing
                </a>
                <a href="{{ $companiesUrl }}" class="fi-btn" style="
                    background: var(--nb-bg-main);
                    color: var(--nb-text);
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 8px 16px;
                    font-size: 0.8125rem;
                    text-decoration: none;
                ">
                    🏢 Mitra DU-DI
                </a>
            </div>
        </div>

        {{-- Monitoring Alert Cards / Summary Badges --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
            
            {{-- Penempatan PKL Siswa --}}
            <div style="
                background: var(--nb-bg-card);
                border: var(--nb-border);
                border-radius: var(--nb-radius-md);
                box-shadow: var(--nb-shadow-sm);
                padding: 16px 20px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 12px;
            ">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <span style="font-size: 0.75rem; font-family: 'Space Grotesk', sans-serif; font-weight: 700; color: var(--nb-text); opacity: 0.7; text-transform: uppercase;">
                            Status Penempatan PKL
                        </span>
                        <h3 style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.5rem; margin: 4px 0 0 0; color: var(--nb-text);">
                            {{ $studentsWithPlacement }} / {{ $totalStudents }}
                        </h3>
                    </div>
                    <span style="font-size: 1.5rem;">📍</span>
                </div>
                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                    @if ($studentsWithoutPlacement > 0)
                        <span style="
                            background: rgba(255, 77, 77, 0.15);
                            color: var(--nb-red);
                            border: 1px solid var(--nb-red);
                            border-radius: 4px;
                            padding: 2px 8px;
                            font-size: 0.75rem;
                            font-weight: 700;
                        ">
                            ⚠️ {{ $studentsWithoutPlacement }} Siswa Belum Ditempatkan
                        </span>
                    @else
                        <span style="
                            background: rgba(74, 222, 128, 0.15);
                            color: var(--nb-green);
                            border: 1px solid var(--nb-green);
                            border-radius: 4px;
                            padding: 2px 8px;
                            font-size: 0.75rem;
                            font-weight: 700;
                        ">
                            ✅ Semua Siswa Terdistribusi
                        </span>
                    @endif
                </div>
            </div>

            {{-- Alokasi Guru Pembimbing --}}
            <div style="
                background: var(--nb-bg-card);
                border: var(--nb-border);
                border-radius: var(--nb-radius-md);
                box-shadow: var(--nb-shadow-sm);
                padding: 16px 20px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 12px;
            ">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <span style="font-size: 0.75rem; font-family: 'Space Grotesk', sans-serif; font-weight: 700; color: var(--nb-text); opacity: 0.7; text-transform: uppercase;">
                            Alokasi Guru Pembimbing
                        </span>
                        <h3 style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.5rem; margin: 4px 0 0 0; color: var(--nb-text);">
                            {{ $studentsWithTeacher }} / {{ $totalStudents }}
                        </h3>
                    </div>
                    <span style="font-size: 1.5rem;">🧑‍🏫</span>
                </div>
                <div>
                    @if ($studentsWithoutTeacher > 0)
                        <span style="
                            background: rgba(251, 146, 60, 0.15);
                            color: var(--nb-orange);
                            border: 1px solid var(--nb-orange);
                            border-radius: 4px;
                            padding: 2px 8px;
                            font-size: 0.75rem;
                            font-weight: 700;
                        ">
                            ⏳ {{ $studentsWithoutTeacher }} Siswa Belum Ada Pembimbing
                        </span>
                    @else
                        <span style="
                            background: rgba(74, 222, 128, 0.15);
                            color: var(--nb-green);
                            border: 1px solid var(--nb-green);
                            border-radius: 4px;
                            padding: 2px 8px;
                            font-size: 0.75rem;
                            font-weight: 700;
                        ">
                            ✅ Semua Pembimbing Terpetakan
                        </span>
                    @endif
                </div>
            </div>

            {{-- Aktivitas Jurnal Hari Ini --}}
            <div style="
                background: var(--nb-bg-card);
                border: var(--nb-border);
                border-radius: var(--nb-radius-md);
                box-shadow: var(--nb-shadow-sm);
                padding: 16px 20px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 12px;
            ">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <span style="font-size: 0.75rem; font-family: 'Space Grotesk', sans-serif; font-weight: 700; color: var(--nb-text); opacity: 0.7; text-transform: uppercase;">
                            Jurnal Masuk Hari Ini
                        </span>
                        <h3 style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.5rem; margin: 4px 0 0 0; color: var(--nb-text);">
                            {{ $journalsToday }} Jurnal
                        </h3>
                    </div>
                    <span style="font-size: 1.5rem;">📝</span>
                </div>
                <div>
                    <span style="
                        background: rgba(59, 130, 246, 0.15);
                        color: var(--nb-primary);
                        border: 1px solid var(--nb-primary);
                        border-radius: 4px;
                        padding: 2px 8px;
                        font-size: 0.75rem;
                        font-weight: 700;
                    ">
                        Total Pending: {{ $pendingJournals }}
                    </span>
                </div>
            </div>

            {{-- Total Mitra DU-DI --}}
            <div style="
                background: var(--nb-bg-card);
                border: var(--nb-border);
                border-radius: var(--nb-radius-md);
                box-shadow: var(--nb-shadow-sm);
                padding: 16px 20px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 12px;
            ">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <span style="font-size: 0.75rem; font-family: 'Space Grotesk', sans-serif; font-weight: 700; color: var(--nb-text); opacity: 0.7; text-transform: uppercase;">
                            Mitra DU-DI Terdaftar
                        </span>
                        <h3 style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.5rem; margin: 4px 0 0 0; color: var(--nb-text);">
                            {{ $totalCompanies }} Mitra
                        </h3>
                    </div>
                    <span style="font-size: 1.5rem;">🏢</span>
                </div>
                <div>
                    <span style="font-size: 0.75rem; color: var(--nb-text); opacity: 0.7;">
                        {{ $totalTeachers }} Guru Pembimbing Aktif
                    </span>
                </div>
            </div>

        </div>

        {{-- 2-Column Section: Jurnal Terbaru Menunggu Validasi & Top Perusahaan Mitra --}}
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
            
            {{-- Antrean Jurnal Masuk (Live Table) --}}
            <div style="
                background: var(--nb-bg-card);
                border: var(--nb-border);
                border-radius: var(--nb-radius-md);
                box-shadow: var(--nb-shadow-md);
                overflow: hidden;
            ">
                <div style="
                    padding: 16px 20px;
                    border-bottom: var(--nb-border);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 10px;
                ">
                    <div>
                        <h3 style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.1rem; margin: 0; color: var(--nb-text);">
                            📋 Jurnal Harian Menunggu Verifikasi
                        </h3>
                        <p style="font-size: 0.8rem; color: var(--nb-text); opacity: 0.7; margin: 2px 0 0 0;">
                            5 jurnal terbaru yang telah dikirim siswa dan sedang menunggu evaluasi guru pembimbing.
                        </p>
                    </div>
                    <a href="{{ $journalsUrl }}" style="
                        font-family: 'Space Grotesk', sans-serif;
                        font-weight: 700;
                        font-size: 0.8125rem;
                        color: var(--nb-primary);
                        text-decoration: none;
                    ">
                        Lihat Seluruh Jurnal →
                    </a>
                </div>

                @if ($recentPending->isEmpty())
                    <div style="padding: 32px 20px; text-align: center;">
                        <span style="font-size: 2rem;">🎉</span>
                        <p style="font-family: 'Space Grotesk', sans-serif; font-weight: 700; color: var(--nb-text); margin: 8px 0 2px 0;">
                            Tidak Ada Jurnal Pending
                        </p>
                        <p style="font-size: 0.825rem; color: var(--nb-text); opacity: 0.7; margin: 0;">
                            Seluruh laporan kegiatan siswa telah selesai ditinjau.
                        </p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                            <thead>
                                <tr style="background: var(--nb-border-color); color: #FFFFFF;">
                                    <th style="padding: 10px 16px; font-family: 'Space Grotesk', sans-serif; font-size: 0.75rem; text-transform: uppercase;">Tanggal</th>
                                    <th style="padding: 10px 16px; font-family: 'Space Grotesk', sans-serif; font-size: 0.75rem; text-transform: uppercase;">Siswa</th>
                                    <th style="padding: 10px 16px; font-family: 'Space Grotesk', sans-serif; font-size: 0.75rem; text-transform: uppercase;">Tempat PKL</th>
                                    <th style="padding: 10px 16px; font-family: 'Space Grotesk', sans-serif; font-size: 0.75rem; text-transform: uppercase;">Guru Pembimbing</th>
                                    <th style="padding: 10px 16px; font-family: 'Space Grotesk', sans-serif; font-size: 0.75rem; text-transform: uppercase;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentPending as $journal)
                                    <tr style="border-bottom: 1px solid rgba(75, 85, 99, 0.2);">
                                        <td style="padding: 12px 16px; font-family: 'Space Grotesk', sans-serif; font-weight: 600; color: var(--nb-text);">
                                            {{ \Carbon\Carbon::parse($journal->date)->translatedFormat('d M Y') }}
                                        </td>
                                        <td style="padding: 12px 16px; color: var(--nb-text);">
                                            <strong>{{ $journal->student->user->name ?? 'Siswa' }}</strong>
                                            <div style="font-size: 0.75rem; opacity: 0.7;">{{ $journal->student->class_name ?? '-' }}</div>
                                        </td>
                                        <td style="padding: 12px 16px; color: var(--nb-text);">
                                            {{ $journal->student->company->name ?? '-' }}
                                        </td>
                                        <td style="padding: 12px 16px; color: var(--nb-text);">
                                            {{ $journal->student->teacher->user->name ?? 'Belum ditentukan' }}
                                        </td>
                                        <td style="padding: 12px 16px;">
                                            <span style="
                                                background: rgba(251, 146, 60, 0.15);
                                                color: var(--nb-orange);
                                                border: 1px solid var(--nb-orange);
                                                border-radius: 4px;
                                                padding: 2px 8px;
                                                font-size: 0.72rem;
                                                font-weight: 700;
                                                font-family: 'Space Grotesk', sans-serif;
                                                text-transform: uppercase;
                                            ">
                                                ⏳ Menunggu Review
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-filament-widgets::widget>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Jurnal PKL - {{ $student->user->name ?? 'Siswa' }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f3f4f6;
            color: #1f2937;
            padding: 24px;
            font-size: 12px;
            line-height: 1.5;
        }

        .no-print-bar {
            background-color: #1e293b;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-secondary {
            background-color: #475569;
            color: #f8fafc;
        }
        .btn-secondary:hover {
            background-color: #334155;
        }

        .paper {
            background: #ffffff;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 36px 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-radius: 4px;
        }

        .header-kop {
            text-align: center;
            border-bottom: 3px double #1f2937;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header-kop h2 {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header-kop h3 {
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .header-kop p {
            font-size: 11px;
            color: #6b7280;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #111827;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e5e7eb;
        }

        .identity-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
            background: #f9fafb;
            padding: 12px 16px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .identity-table {
            width: 100%;
            border-collapse: collapse;
        }

        .identity-table td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 12px;
        }

        .identity-table td.label {
            width: 130px;
            color: #4b5563;
            font-weight: 500;
        }

        .identity-table td.separator {
            width: 15px;
            text-align: center;
            color: #6b7280;
        }

        .identity-table td.value {
            font-weight: 600;
            color: #111827;
        }

        .stats-row {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-box {
            flex: 1;
            padding: 8px 12px;
            border-radius: 6px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        .stat-box .count {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }

        .stat-box .desc {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
        }

        table.journal-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        table.journal-table th,
        table.journal-table td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
            font-size: 11px;
        }

        table.journal-table th {
            background-color: #f3f4f6;
            font-weight: 700;
            text-transform: uppercase;
            color: #374151;
            font-size: 10px;
            letter-spacing: 0.3px;
        }

        table.journal-table td.center {
            text-align: center;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-hadir { background: #dcfce7; color: #166534; }
        .badge-izin { background: #fef9c3; color: #854d0e; }
        .badge-sakit { background: #fee2e2; color: #991b1b; }

        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-submitted { background: #fef3c7; color: #92400e; }
        .badge-revision { background: #fee2e2; color: #991b1b; }

        .signatures {
            margin-top: 36px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            text-align: center;
            page-break-inside: avoid;
        }

        .sig-box {
            padding: 0 10px;
        }

        .sig-box p.role {
            font-weight: 600;
            margin-bottom: 60px;
            font-size: 11px;
        }

        .sig-box p.name {
            font-weight: 700;
            border-bottom: 1px solid #374151;
            display: inline-block;
            min-width: 160px;
            padding-bottom: 2px;
            font-size: 11px;
        }

        .sig-box p.id-num {
            font-size: 10px;
            color: #4b5563;
            margin-top: 2px;
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .paper {
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            @page {
                size: A4 portrait;
                margin: 15mm 12mm 15mm 12mm;
            }
            table.journal-table tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Aksi Non-Print -->
    <div class="no-print-bar no-print">
        <div>
            <h4 style="font-size: 15px; font-weight: 600;">Pratinjau Cetak Laporan Jurnal PKL</h4>
            <span style="font-size: 12px; color: #94a3b8;">Gunakan tombol cetak atau kombinasi Ctrl+P / Cmd+P untuk menyimpan sebagai PDF.</span>
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Cetak / Simpan PDF
            </button>
            <a href="javascript:window.close();" class="btn btn-secondary" onclick="if(window.history.length > 1) { history.back(); return false; }">
                ✕ Tutup / Kembali
            </a>
        </div>
    </div>

    <!-- Halaman Dokumen A4 -->
    <div class="paper">

        <!-- Kop Surat -->
        <div class="header-kop">
            <h2>Laporan Rekapitulasi Jurnal Harian</h2>
            <h3>Praktik Kerja Lapangan (PKL) Siswa SMK</h3>
            <p>Sistem Informasi Manajemen Presensi & Jurnal Digital PKL</p>
        </div>

        <!-- Identitas Siswa & DU-DI -->
        <div class="identity-grid">
            <div>
                <table class="identity-table">
                    <tr>
                        <td class="label">Nama Siswa</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $student->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">NISN / NIS</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $student->nisn ?? '-' }} / {{ $student->nis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kelas / Jurusan</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $student->class_name ?? '-' }} ({{ $student->major ?? '-' }})</td>
                    </tr>
                    <tr>
                        <td class="label">No. Telepon / WA</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $student->phone_number ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <table class="identity-table">
                    <tr>
                        <td class="label">Tempat PKL (DU-DI)</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $student->company->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Pembimbing DU-DI</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $student->company->pic_name ?? '-' }} ({{ $student->company->pic_contact ?? '-' }})</td>
                    </tr>
                    <tr>
                        <td class="label">Guru Pembimbing</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $student->teacher->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Periode PKL</td>
                        <td class="separator">:</td>
                        <td class="value">
                            {{ $student->start_date ? $student->start_date->format('d M Y') : '-' }}
                            s.d
                            {{ $student->end_date ? $student->end_date->format('d M Y') : '-' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Statistik Ringkasan -->
        <div class="stats-row">
            <div class="stat-box">
                <div class="count">{{ $stats['total'] }}</div>
                <div class="desc">Total Hari Terdata</div>
            </div>
            <div class="stat-box" style="border-left: 3px solid #16a34a;">
                <div class="count" style="color: #16a34a;">{{ $stats['hadir'] }}</div>
                <div class="desc">Hadir (Masuk)</div>
            </div>
            <div class="stat-box" style="border-left: 3px solid #ca8a04;">
                <div class="count" style="color: #ca8a04;">{{ $stats['izin'] }}</div>
                <div class="desc">Izin</div>
            </div>
            <div class="stat-box" style="border-left: 3px solid #dc2626;">
                <div class="count" style="color: #dc2626;">{{ $stats['sakit'] }}</div>
                <div class="desc">Sakit</div>
            </div>
            <div class="stat-box" style="border-left: 3px solid #2563eb;">
                <div class="count" style="color: #2563eb;">{{ $stats['approved'] }}</div>
                <div class="desc">Disetujui Guru</div>
            </div>
        </div>

        <!-- Tabel Catatan Jurnal -->
        <div class="section-title">Riwayat Kegiatan Harian</div>
        <table class="journal-table">
            <thead>
                <tr>
                    <th style="width: 25px;" class="center">No</th>
                    <th style="width: 80px;">Hari, Tanggal</th>
                    <th style="width: 55px;" class="center">Status</th>
                    <th style="width: 70px;" class="center">Jam Kerja</th>
                    <th>Uraian Kegiatan / Pekerjaan Harian</th>
                    <th style="width: 140px;">Catatan Guru Pembimbing</th>
                    <th style="width: 65px;" class="center">Validasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($journals as $index => $j)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $j->date ? $j->date->format('d/m/Y') : '-' }}</strong>
                            <br>
                            <span style="color: #6b7280; font-size: 10px;">{{ $j->date ? $j->date->locale('id')->isoFormat('dddd') : '' }}</span>
                        </td>
                        <td class="center">
                            @if ($j->attendance_status === 'hadir')
                                <span class="badge-status badge-hadir">Hadir</span>
                            @elseif ($j->attendance_status === 'izin')
                                <span class="badge-status badge-izin">Izin</span>
                            @elseif ($j->attendance_status === 'sakit')
                                <span class="badge-status badge-sakit">Sakit</span>
                            @else
                                {{ ucfirst($j->attendance_status) }}
                            @endif
                        </td>
                        <td class="center">
                            @if ($j->start_time && $j->end_time)
                                {{ substr($j->start_time, 0, 5) }} - {{ substr($j->end_time, 0, 5) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $j->work_description }}
                        </td>
                        <td>
                            {{ $j->teacher_note ?: '-' }}
                        </td>
                        <td class="center">
                            @if ($j->status === 'approved')
                                <span class="badge-status badge-approved">Disetujui</span>
                            @elseif ($j->status === 'revision')
                                <span class="badge-status badge-revision">Revisi</span>
                            @else
                                <span class="badge-status badge-submitted">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="center" style="padding: 24px; color: #9ca3af;">
                            Belum ada catatan jurnal kegiatan yang terekam.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Kolom Tanda Tangan -->
        <div class="signatures">
            <div class="sig-box">
                <p class="role">Siswa Praktikan,</p>
                <p class="name">{{ $student->user->name ?? '....................................' }}</p>
                <p class="id-num">NISN. {{ $student->nisn ?? '..................' }}</p>
            </div>
            <div class="sig-box">
                <p class="role">Pembimbing DU-DI / Industri,</p>
                <p class="name">{{ $student->company->pic_name ?? '....................................' }}</p>
                <p class="id-num">{{ $student->company->name ?? 'Mitra DU-DI' }}</p>
            </div>
            <div class="sig-box">
                <p class="role">Guru Pembimbing Sekolah,</p>
                <p class="name">{{ $student->teacher->user->name ?? '....................................' }}</p>
                <p class="id-num">NIP. {{ $student->teacher->nip ?? '..................' }}</p>
            </div>
        </div>

    </div>

</body>
</html>

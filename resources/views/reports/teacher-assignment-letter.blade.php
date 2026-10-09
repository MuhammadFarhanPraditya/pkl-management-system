<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas Pembimbing PKL - {{ $teacher->user->name ?? 'Guru Pembimbing' }}</title>
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
            padding: 40px 48px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-radius: 4px;
        }

        .header-kop {
            text-align: center;
            border-bottom: 3px double #1f2937;
            padding-bottom: 12px;
            margin-bottom: 24px;
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

        .letter-title {
            text-align: center;
            margin-bottom: 24px;
        }

        .letter-title h1 {
            font-size: 15px;
            font-weight: 800;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .letter-title .nomor {
            font-size: 12px;
            color: #4b5563;
        }

        .section-text {
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 14px;
            text-align: justify;
        }

        .teacher-info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .teacher-info-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 12px;
        }

        .teacher-info-table td.label {
            width: 180px;
            font-weight: 600;
            color: #374151;
        }

        .teacher-info-table td.colon {
            width: 20px;
            text-align: center;
        }

        .teacher-info-table td.value {
            color: #111827;
            font-weight: 600;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0 24px 0;
            font-size: 11px;
        }

        .table-data th, .table-data td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: left;
        }

        .table-data th {
            background-color: #f8fafc;
            font-weight: 700;
            color: #1f2937;
            text-align: center;
        }

        .table-data td.center {
            text-align: center;
        }

        .duties-list {
            margin: 10px 0 20px 20px;
            font-size: 11.5px;
            line-height: 1.6;
        }

        .duties-list li {
            margin-bottom: 4px;
        }

        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            margin-top: 40px;
            gap: 20px;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            font-size: 11.5px;
        }

        .signature-line {
            margin-top: 65px;
            font-weight: 700;
            text-decoration: underline;
        }

        .signature-title {
            color: #4b5563;
            margin-top: 2px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .no-print-bar {
                display: none !important;
            }
            .paper {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Aksi Kontrol (Tidak Ikut Tercetak) -->
    <div class="no-print-bar">
        <div>
            <span style="font-weight: 700; font-size: 14px;">Surat Tugas Pembimbing PKL Resmi</span>
            <span style="color: #94a3b8; font-size: 12px; margin-left: 10px;">
                Guru: {{ $teacher->user->name ?? '-' }} ({{ $students->count() }} Siswa Binaan)
            </span>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="javascript:history.back()" class="btn btn-secondary">
                &larr; Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Halaman Dokumen Kertas -->
    <div class="paper">
        <!-- Kop Surat Resmi -->
        <div class="header-kop">
            <h2>PEMERINTAH DAERAH PROVINSI</h2>
            <h3>DINAS PENDIDIKAN DAN KEBUDAYAAN</h3>
            <h2>SMK PUSAT KEUNGGULAN (PKL KELAS 11)</h2>
            <p>Jalan Pendidikan No. 45, Gedung Pokja Hubungan Industri & Masyarakat (Hubin)</p>
            <p>Telp: (021) 7890123 &bull; Email: pokja.pkl@smk.sch.id &bull; Website: www.smk-pkl.sch.id</p>
        </div>

        <!-- Judul & Nomor Surat -->
        <div class="letter-title">
            <h1>SURAT TUGAS PEMBIMBING PRAKTEK KERJA LAPANGAN</h1>
            <div class="nomor">Nomor: 421.5/088/SMK-PKL/X/{{ date('Y') }}</div>
        </div>

        <p class="section-text">
            Yang bertanda tangan di bawah ini, Kepala SMK Pusat Keunggulan, berdasarkan Program Kerja Kelompok Kerja (Pokja) Praktek Kerja Lapangan (PKL) Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}, dengan ini memberikan tugas kedinasan kepada:
        </p>

        <!-- Identitas Guru Pembimbing -->
        <table class="teacher-info-table">
            <tr>
                <td class="label">Nama Guru Pembimbing</td>
                <td class="colon">:</td>
                <td class="value">{{ $teacher->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td class="colon">:</td>
                <td class="value">{{ $teacher->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nomor WhatsApp / HP</td>
                <td class="colon">:</td>
                <td class="value">{{ $teacher->phone_number ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan Kedinasan</td>
                <td class="colon">:</td>
                <td class="value">Guru Pembimbing Internal PKL Kelas 11 SMK</td>
            </tr>
        </table>

        <p class="section-text">
            Untuk membimbing, mendampingi, dan memantau perkembangan kegiatan Praktek Kerja Lapangan (PKL) siswa kelas 11 pada Dunia Usaha / Dunia Industri (DU-DI) mitra sekolah, dengan daftar siswa sebagai berikut:
        </p>

        <!-- Tabel Daftar Siswa Bimbingan -->
        <table class="table-data">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th>Nama Siswa</th>
                    <th>NISN / NIS</th>
                    <th>Kelas & Jurusan</th>
                    <th>Perusahaan / Mitra DU-DI</th>
                    <th>Periode PKL</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $index => $student)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td style="font-weight: 600;">{{ $student->user->name ?? '-' }}</td>
                        <td class="center">{{ $student->nisn ?? '-' }}</td>
                        <td>{{ $student->class_name ?? '-' }} ({{ $student->major ?? '-' }})</td>
                        <td>
                            <strong>{{ $student->company->name ?? '-' }}</strong>
                            <div style="font-size: 10px; color: #4b5563;">PIC: {{ $student->company->pic_name ?? '-' }} ({{ $student->company->pic_contact ?? '-' }})</div>
                        </td>
                        <td class="center" style="font-size: 10.5px;">
                            {{ $student->start_date ? \Carbon\Carbon::parse($student->start_date)->format('d/m/Y') : '-' }} s.d.
                            {{ $student->end_date ? \Carbon\Carbon::parse($student->end_date)->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="center" style="color: #6b7280; padding: 20px;">
                            Belum ada siswa yang di-plotting ke guru pembimbing ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p class="section-text">
            <strong>Kewajiban dan Tanggung Jawab Guru Pembimbing:</strong>
        </p>
        <ol class="duties-list">
            <li>Menjalin koordinasi berkala dengan instruktur/pembimbing lapangan di mitra DU-DI.</li>
            <li>Memantau dan memvalidasi pengisian <strong>Jurnal Harian Digital</strong> serta kehadiran siswa secara berkala melalui sistem <em>Dashboard-PKL</em>.</li>
            <li>Memberikan bimbingan teknis dan etika kerja serta mencatat arahan revisi bila diperlukan.</li>
            <li>Melakukan supervisi/monitoring kunjungan ke lokasi industri sesuai jadwal yang ditetapkan Pokja PKL.</li>
            <li>Menyusun laporan evaluasi dan penilaian akhir PKL siswa bimbingan.</li>
        </ol>

        <p class="section-text" style="margin-top: 12px;">
            Demikian Surat Tugas ini diterbitkan agar dapat dilaksanakan dengan penuh dedikasi dan rasa tanggung jawab.
        </p>

        <!-- Tanda Tangan Pengesahan -->
        <div class="signatures">
            <div class="signature-box">
                <div>Mengetahui,</div>
                <div class="signature-title">Koordinator Pokja PKL / Hubin</div>
                <div class="signature-line">Hendra Kusuma, M.Pd.</div>
                <div class="signature-title">NIP. 19780512 200501 1 004</div>
            </div>

            <div class="signature-box">
                <div>Ditetapkan di: Jakarta</div>
                <div>Pada tanggal: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</div>
                <div class="signature-title">Kepala Sekolah,</div>
                <div class="signature-line">Drs. H. Mulyadi, M.M.</div>
                <div class="signature-title">NIP. 19690314 199403 1 002</div>
            </div>
        </div>
    </div>

</body>
</html>

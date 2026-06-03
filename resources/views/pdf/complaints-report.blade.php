<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan Layanan</title>
    <style>
        /* BASE SYSTEM RESET & FONTS */
        @page {
            margin: 1.2cm 1.4cm 1.4cm 1.4cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* KOP SURAT INSTANSI */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #0f172a;
            margin-bottom: 20px;
        }
        .kop-logo {
            width: 70px;
            padding-bottom: 12px;
            vertical-align: middle;
        }
        .kop-logo img {
            height: 60px;
            width: auto;
        }
        .kop-text {
            padding-left: 15px;
            padding-bottom: 12px;
            vertical-align: middle;
        }
        .kop-text h1 {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-text p {
            font-size: 10px;
            color: #64748b;
            margin: 3px 0 0 0;
            font-weight: 500;
        }

        /* METADATA LAPORAN */
        .report-title-container {
            margin-bottom: 25px;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
        }
        .report-period {
            font-size: 11px;
            color: #475569;
            margin: 4px 0 0 0;
        }

        /* GRID RINGKASAN DATA (Menggunakan Table Berkolom) */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-left: -8px;
            margin-right: -8px;
            margin-bottom: 25px;
        }
        .summary-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px 12px;
            text-align: left;
            border-radius: 6px;
        }
        .summary-label {
            font-size: 9px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }

        /* DUA KOLOM ANALYSIS (SLA & KATEGORI) */
        .analysis-container {
            width: 100%;
            margin-bottom: 25px;
        }
        .analysis-column {
            width: 50%;
            vertical-align: top;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 10px 0;
            border-left: 3px solid #2563eb;
            padding-left: 8px;
        }

        /* TABEL DATA DASAR */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            letter-spacing: 0.5px;
        }
        .data-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* BADGE STATUS UNTUK PDF */
        .pdf-badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-waiting { bg-color: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
        .badge-under_review { bg-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .badge-on_process { bg-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-solved { bg-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-rejected { bg-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* TEKS PEMBANTU */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: Courier, monospace; font-weight: bold; }
        .text-muted { color: #64748b; font-size: 10px; }
    </style>
</head>
<body>

    <!-- KOP SURAT INSTANSI (MENDUKUNG LOGO RS) -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                <!-- Gunakan public_path agar dompdf bisa membaca file lokal gambar tanpa masalah network -->
                <img src="{{ public_path('beraksi-logo.webp') }}" alt="Logo RS">
            </td>
            <td class="kop-text">
                <h1>Rumah Sakit Umum Daerah Beraksi</h1>
                <p>Jl. Kesehatan Raya No. 12, Kota Utama • Telp: (021) 555-1234 • Email: info@rs-beraksi.go.id</p>
            </td>
        </tr>
    </table>

    <!-- JUDUL LAPORAN & PERIODE -->
    <div class="report-title-container">
        <h2 class="report-title">Laporan Eksekutif Pengaduan Layanan</h2>
        <p class="report-period">Periode Data: <strong>{{ $startDate }}</strong> s/d <strong>{{ $endDate }}</strong></p>
    </div>

    <!-- PANEL STATISTIK RINGKASAN UTAMA -->
    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-card">
                    <div class="summary-label">Total Masuk</div>
                    <div class="summary-value">{{ $summary['total'] }}</div>
                </div>
            </td>
            <td>
                <div class="summary-card" style="border-left: 3px solid #eab308;">
                    <div class="summary-label" style="color: #a16207;">Menunggu</div>
                    <div class="summary-value">{{ $summary['waiting'] }}</div>
                </div>
            </td>
            <td>
                <div class="summary-card" style="border-left: 3px solid #3b82f6;">
                    <div class="summary-label" style="color: #1d4ed8;">Diproses</div>
                    <div class="summary-value">{{ $summary['on_process'] }}</div>
                </div>
            </td>
            <td>
                <div class="summary-card" style="border-left: 3px solid #10b981;">
                    <div class="summary-label" style="color: #047857;">Selesai</div>
                    <div class="summary-value">{{ $summary['solved'] }}</div>
                </div>
            </td>
            <td>
                <div class="summary-card">
                    <div class="summary-label">Rasio Solusi</div>
                    <div class="summary-value" style="color: #10b981;">{{ $summary['completion_rate'] }}%</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- PANEL DETIL METRIK (SLA & KATEGORI TERBANYAK) -->
    <table class="analysis-container">
        <tr>
            <!-- Kolom Kiri: Statistik SLA Pemrosesan -->
            <td class="analysis-column" style="padding-right: 15px;">
                <h3 class="section-title">Indikator Kinerja SLA (Durasi Solusi)</h3>
                <table class="data-table">
                    <tr>
                        <td style="width: 65%; color: #475569;">Rata-rata Penyelesaian</td>
                        <td class="text-right font-bold" style="font-size: 12px;">{{ $sla['avg_hours'] }}</td>
                    </tr>
                    <tr>
                        <td style="color: #475569;">Waktu Tercepat</td>
                        <td class="text-right font-bold" style="color: #166534;">{{ $sla['fastest_hours'] }}</td>
                    </tr>
                    <tr>
                        <td style="color: #475569;">Waktu Terlama</td>
                        <td class="text-right font-bold" style="color: #991b1b;">{{ $sla['slowest_hours'] }}</td>
                    </tr>
                </table>
            </td>

            <!-- Kolom Kanan: Distribusi Top Kategori -->
            <td class="analysis-column" style="padding-left: 15px;">
                <h3 class="section-title">Distribusi Klasifikasi Masalah</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="text-align: left;">Nama Kategori</th>
                            <th style="width: 30%; text-align: right;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categoryStats->take(3) as $categoryName => $count)
                            <tr>
                                <td style="font-weight: 500;">{{ $categoryName }}</td>
                                <td class="text-right font-bold">{{ $count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Belum ada klasifikasi data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- TABEL UTAMA: RAGAM BERKAS PENGADUAN -->
    <h3 class="section-title">Lampiran Rincian Berkas Pengaduan</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Kode</th>
                <th style="width: 20%;">Klasifikasi</th>
                <th style="width: 25%;">Identitas Pelapor</th>
                <th style="width: 15%; text-align: center;">Status</th>
                <th style="width: 25%;">Waktu Masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $complaint)
                <tr>
                    <td class="font-mono" style="color: #2563eb;">#{{ $complaint->tracking_code }}</td>
                    <td style="font-weight: 500;">{{ $complaint->category?->name ?? 'Umum' }}</td>
                    <td>
                        @if($complaint->is_anonymous)
                            <span style="color: #64748b; font-style: italic;">🔒 Anonim (Dirahasiakan)</span>
                        @else
                            <strong>{{ $complaint->name }}</strong>
                        @endif
                    </td>
                    <td class="text-center">
                        @php
                            $status = $complaint->status;
                            if (is_object($status)) {
                                $status = $status->value ?? $status->name ?? (string) $status;
                            }
                            $status = strtolower((string) $status);
                        @endphp
                        
                        {{-- Logika styling pengganti StatusBadge pada PDF render --}}
                        @if($status === 'waiting')
                            <span class="pdf-badge" style="background-color: #fef9c3; color: #854d0e; border: 1px solid #fef08a;">Waiting</span>
                        @elseif($status === 'under_review')
                            <span class="pdf-badge" style="background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa;">Review</span>
                        @elseif($status === 'on_process')
                            <span class="pdf-badge" style="background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe;">Process</span>
                        @elseif($status === 'solved')
                            <span class="pdf-badge" style="background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">Solved</span>
                        @else
                            <span class="pdf-badge" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">Rejected</span>
                        @endif
                    </td>
                    <td class="text-muted">
                        {{ \Carbon\Carbon::parse($complaint->submitted_at)->translatedFormat('d M Y H:i') }} WIB
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #94a3b8;">
                        Tidak ditemukan rekaman berkas laporan pada rentang periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER CETAK -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 30px;">
        <tr>
            <td class="text-muted" style="width: 50%;">
                Sistem Informasi Pengaduan Rumah Sakit (SIPERUSAK)<br>
                *Dokumen ini sah diunduh secara resmi melalui akun Penanggung Jawab Manajemen Sektor Utama.
            </td>
            <td class="text-right text-muted" style="width: 50%; vertical-align: bottom;">
                Dicetak otomatis pada: {{ now()->translatedFormat('d F Y H:i') }} WIB
            </td>
        </tr>
    </table>

</body>
</html>
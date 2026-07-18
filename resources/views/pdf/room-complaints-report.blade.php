<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan RSUD - Rekap Per Ruangan</title>

    <style>
        @page {
            margin: 2cm 1.8cm;
            size: A4;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1e2a3e;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* =========================
           KOP SURAT
        ========================== */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #1e3a5f;
            margin-bottom: 20px;
        }

        .kop-table td {
            border: none !important; /* Menghapus border bawaan tabel umum */
            padding: 0 0 12px 0 !important;
            vertical-align: middle; /* Memastikan logo dan teks sejajar secara vertikal */
        }

        .kop-logo {
            width: 70px;
            text-align: left;
        }

        .kop-logo img {
            width: 65px;
            height: auto;
            display: block;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h1 {
            margin: 0 0 6px 0;
            font-size: 16pt; /* Sedikit disesuaikan agar pas satu baris */
            font-weight: bold;
            text-transform: uppercase;
            color: #0b2b42;
            letter-spacing: 0.5px;
        }

        .kop-text p {
            margin: 0;
            font-size: 8.5pt;
            line-height: 1.5;
            color: #2c3e50;
        }

        /* Spacer di kanan untuk menyeimbangkan text-align center */
        .kop-spacer {
            width: 70px;
        }

        /* =========================
           JUDUL LAPORAN
        ========================== */
        .title-wrapper {
            text-align: center;
            margin: 15px 0 25px;
        }

        .title-report {
            display: inline-block;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e3a5f;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 6px;
            letter-spacing: 1px;
        }

        /* =========================
           HEADING
        ========================== */
        h3 {
            margin: 20px 0 12px;
            padding-bottom: 6px;
            font-size: 11pt;
            color: #1e3a5f;
            border-bottom: 2px solid #b9c7d9;
        }

        /* =========================
           TABEL UMUM
        ========================== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th {
            background: #e9edf3;
            border: 1px solid #a0b3c9;
            padding: 8px 6px;
            text-align: center;
            font-weight: bold;
            font-size: 9.5pt;
            color: #1e3a5f;
        }

        table td {
            border: 1px solid #bdc4d0;
            padding: 7px 6px;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fc;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .tracking-code {
            font-family: 'Courier New', monospace;
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* =========================
           STATUS BADGE
        ========================== */
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status-waiting {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .status-under_review {
            background: #fed7aa;
            color: #9a3412;
            border: 1px solid #fdba74;
        }

        .status-on_process {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .status-solved {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* =========================
           PAGE BREAK
        ========================== */
        .page-break {
            page-break-before: always;
            margin-top: 10px;
        }

        /* =========================
           FOOTER
        ========================== */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 8.5pt;
            color: #6c757d;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
</head>

<body>

    <!-- KOP SURAT (MENGGUNAKAN TABEL STRUKTUR AGAR PASTI SEJAJAR) -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('logo.webp') }}" alt="Logo RS">
            </td>
            <td class="kop-text">
                <h1>RSUD dr. H. Andi Abdurrahman Noor</h1>
                <p>
                    Jl. H. M. Amin KM. 10 RT. 03 Desa Sepunggur, Kecamatan Kusan Tengah, Kabupaten Tanah Bumbu<br>
                    Kalimantan Selatan 72273 | Telp. 0811 5000 266 / 0518 6070767 | Email: rsud@tanahbumbukab.go.id
                </p>
            </td>
            <td class="kop-spacer"></td>
        </tr>
    </table>

    <!-- JUDUL -->
    <div class="title-wrapper">
        <div class="title-report">
            LAPORAN PENGADUAN PER RUANGAN
        </div>
    </div>

    <!-- RINGKASAN PER RUANGAN -->
    <h3>Ringkasan Jumlah Pengaduan per Ruangan</h3>
    <table style="width: 70%; margin: 0 auto 25px auto;">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="65%">Ruangan</th>
                <th width="25%">Jumlah Pengaduan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roomSummary as $room)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-left"><strong>{{ $room->name }}</strong></td>
                <td class="text-center"><strong>{{ $room->complaints_count }}</strong> pengaduan</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- DETAIL PER RUANGAN -->
    @foreach($groupedComplaints as $roomName => $complaints)
        @if(!$loop->first)
            <div class="page-break"></div>
        @endif

        <h3>Detail Pengaduan - Ruangan: {{ $roomName }}</h3>

        <table>
            <thead>
                <tr>
                    <th width="6%">No</th>
                    <th width="27%">Kode Tracking</th>
                    <th width="15%">Tanggal Masuk</th>
                    <th width="19%">Kategori</th>
                    <th width="20%">Pelapor</th>
                    <th width="13%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $complaint)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center tracking-code">{{ $complaint->tracking_code }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($complaint->submitted_at)->translatedFormat('d F Y') }}
                    </td>
                    <td class="text-center">{{ $complaint->category?->name ?? '-' }}</td>
                    <td class="text-center">{{ $complaint->reporter_type === 'patient'? 'Pasien': 'Keluarga / Pendamping' }}</td>
                    <td class="text-center">
                        @php
                            $statusKey = strtolower(str_replace(' ', '_', $complaint->status ?? ''));
                            $statusLabel = ucfirst(str_replace('_', ' ', $complaint->status ?? 'unknown'));
                        @endphp
                        <span class="status-badge status-{{ $statusKey }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #6c757d;">
                        Tidak ada pengaduan ditemukan untuk ruangan ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach

    <!-- FOOTER SEDERHANA -->
    <div class="footer">
        Dokumen ini dicetak secara elektronik pada {{ now()->translatedFormat('d F Y H:i') }} WITA
    </div>

</body>

</html>
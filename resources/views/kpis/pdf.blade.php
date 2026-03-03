<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KPI Report - {{ $kpi->user->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #444; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 60px; height: 60px; float: left; }
        .header-text { margin-left: 70px; }
        .header-text h2 { margin: 0; font-size: 10px; color: #666; letter-spacing: 1px; }
        .header-text h1 { margin: 2px 0; font-size: 16px; color: #1e1b4b; }
        .header-text p { margin: 0; font-size: 8px; color: #888; }
        
        .title { text-align: center; margin: 20px 0; }
        .title h3 { margin: 0; font-size: 14px; text-decoration: underline; letter-spacing: 2px; }
        .title h4 { margin: 5px 0 0; font-size: 12px; }

        .info-table { width: 100%; margin-bottom: 20px; font-weight: bold; }
        .info-table td { padding: 3px 0; }

        .category-header { background-color: #4f46e5; color: white; padding: 8px 12px; font-weight: bold; text-transform: uppercase; margin-top: 15px; }
        
        table.kpi-table { width: 100%; border-collapse: collapse; margin-bottom: 5px; page-break-inside: avoid; }
        table.kpi-table th, table.kpi-table td { border: 1px solid #ddd; padding: 6px; }
        table.kpi-table th { background-color: #f8fafc; text-align: left; font-size: 8px; color: #475569; }
        
        .score-col { width: 30px; text-align: center; font-weight: bold; }
        .cat-total { text-align: right; font-weight: bold; padding: 10px; background-color: #f1f5f9; border: 1px solid #ddd; }

        .summary-section { margin-top: 30px; page-break-inside: avoid; }
        .final-box { border: 2px solid #1e1b4b; padding: 15px; background-color: #f1f5f9; }
        .final-score { font-size: 24px; font-weight: bold; color: #4f46e5; text-align: center; }
        .final-index { font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase; margin-top: 5px; }

        .notes-section { margin-top: 20px; border: 1px solid #e2e8f0; padding: 10px; border-radius: 8px; }
        .notes-title { font-weight: bold; margin-bottom: 5px; color: #64748b; text-transform: uppercase; font-size: 8px; }

        .signature-section { margin-top: 50px; width: 100%; page-break-inside: avoid; }
        .sig-box { width: 45%; float: left; text-align: center; }
        .sig-box-right { width: 45%; float: right; text-align: center; }
        .sig-line { margin-top: 10px; border-top: 1px solid #000; width: 90%; margin-left: auto; margin-right: auto; padding-top: 5px; font-weight: bold; }
        .sig-img { max-width: 120px; max-height: 60px; margin-bottom: 5px; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #aaa; }
        .clearfix::after { content: ""; clear: both; display: table; }

        /* Categor Colors */
        .color-A { color: #2563eb; }
        .color-B { color: #7c3aed; }
        .color-C { color: #db2777; }
        .color-D { color: #ea580c; }
        .color-E { color: #16a34a; }
    </style>
</head>
<body>
    <div class="header clearfix">
        <img src="{{ public_path('loog.jpeg') }}" class="logo">
        <div class="header-text">
            <h2>UNIVERSITAS MEDIA NUSANTARA CITRA</h2>
            <h1>PANITIA MNCU FUTURE LEADER SCHOLARSHIP</h1>
            <p>Jl. Panjang Blok A8, Jl. Green Garden Pintu Utara RT.1/RW.3, Kedoya Utara Email: beasiswaakbarmncuniversity@gmail.com</p>
        </div>
    </div>

    <div class="title">
        <h3>KEY PERFORMANCE INDICATOR (KPI)</h3>
        <h4>MNCU FUTURE LEADER SCHOLARSHIP</h4>
    </div>

    <table class="info-table">
        <tr>
            <td width="100">NAMA</td>
            <td width="10">:</td>
            <td style="color: #4f46e5; text-transform: uppercase;">{{ $kpi->user->name }}</td>
            <td width="100">HARI/TANGGAL</td>
            <td width="10">:</td>
            <td>{{ \Carbon\Carbon::parse($kpi->period_date)->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
            <td>DEPARTEMEN</td>
            <td>:</td>
            <td style="color: #4f46e5; text-transform: uppercase;">{{ $kpi->user->department->name ?? '-' }}</td>
            <td>PENILAI</td>
            <td>:</td>
            <td>{{ $kpi->assessor->name }}</td>
        </tr>
    </table>

    @php
        $categories = [
            'category_A' => 'A. SPECIFIC (25%)',
            'category_B' => 'B. MEASURABLE (25%)',
            'category_C' => 'C. ACHIEVABLE (20%)',
            'category_D' => 'D. RELEVANT (15%)',
            'category_E' => 'E. TIME-BOUND (15%)'
        ];
        $catData = [
            'category_A' => [
                'Pemahaman Peran Dan Tugas', 
                'Pelaksanaan Tugas Harian', 
                'Kejelasan Output Kerja'
            ],
            'category_B' => [
                'Pencapaian Target Kuantitatif', 
                'Kualitas dan Akurasi Hasil Kerja', 
                'Pelaporan dan Dokumentasi'
            ],
            'category_C' => [
                'Manajemen Waktu', 
                'Pengelolaan Beban Kerja', 
                'Pemanfaatan Dukungan Tim'
            ],
            'category_D' => [
                'Kontribusi terhadap Target Program', 
                'Keselarasan dengan Visi dan Nilai MFLS', 
                'Representasi dan Citra Program'
            ],
            'category_E' => [
                'Ketepatan Waktu Pelaksanaan', 
                'Ketepatan Waktu Pelaporan', 
                'Responsivitas Deadline'
            ]
        ];
    @endphp

    @foreach($categories as $key => $title)
    <div class="category-header">{{ $title }}</div>
    <table class="kpi-table">
        <thead>
            <tr>
                <th width="30">NO</th>
                <th>ASPEK PERILAKU</th>
                <th width="40" style="text-align: center;">NILAI ( 1 -4 )</th>
            </tr>
        </thead>
        <tbody>
            @php $qIdx = 0; @endphp
            @foreach($catData[$key] as $idx => $aspek)
                <tr>
                    <td style="text-align: center; color: #94a3b8;">{{ $idx + 1 }}</td>
                    <td>{{ $aspek }}</td>
                    <td class="score-col">
                        {{ $kpi->behavior_scores[$key][$qIdx] ?? '-' }}
                    </td>
                </tr>
                @php $qIdx++; @endphp
            @endforeach
        </tbody>
    </table>
    @endforeach

    <div class="summary-section">
        <div class="final-box">
            <div style="font-size: 8px; text-transform: uppercase; color: #64748b; margin-bottom: 5px;">Total Penilaian Akhir</div>
            <div class="final-score">{{ $kpi->total_value }}</div>
            <div class="final-index">{{ $kpi->index_score }}</div>
        </div>

        @if($kpi->pd_notes)
        <div class="notes-section">
            <div class="notes-title">Catatan / Saran dari Project Director</div>
            <div style="font-style: italic; color: #334155;">"{{ $kpi->pd_notes }}"</div>
        </div>
        @endif
    </div>

    <div class="signature-section clearfix">
        <!-- Project Director (LEFT) -->
        <div class="sig-box">
            <div style="font-size: 9px; margin-bottom: 10px;">Mengesahkan,</div>
            @if($kpi->pd_signature)
                @if($kpi->pd_signature === 'default_pd_signature')
                    <img src="{{ public_path('image.png') }}" class="sig-img">
                @else
                    <img src="{{ public_path('storage/' . $kpi->pd_signature) }}" class="sig-img">
                @endif
            @else
                <div style="height: 60px;"></div>
            @endif
            <div class="sig-line">{{ $pd->name ?? 'Project Director' }}</div>
            <div style="font-size: 8px;">Project Director</div>
        </div>

        <!-- Kepala Departemen (RIGHT) -->
        <div class="sig-box-right">
            <div style="font-size: 9px; margin-bottom: 10px;">{{ \Carbon\Carbon::parse($kpi->period_date)->translatedFormat('F Y') }}</div>
            @if($kpi->head_signature)
                <img src="{{ public_path('storage/' . $kpi->head_signature) }}" class="sig-img">
            @else
                <div style="height: 60px;"></div>
            @endif
            <div class="sig-line">{{ $kpi->assessor->name }}</div>
            <div style="font-size: 8px;">Kepala Departemen</div>
        </div>
    </div>

    <div class="footer">
        Dicetak otomatis oleh Sistem Management MNCU Future Leader Scholarship pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>


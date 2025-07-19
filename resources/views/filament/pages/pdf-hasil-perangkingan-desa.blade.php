<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Laporan Hasil Perangkingan PKH - Desa' }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3490dc;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }
        .header .info {
            margin-top: 10px;
            color: #7f8c8d;
            font-size: 12px;
        }
        .village-info {
            background-color: #3490dc;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
        }
        .village-info h2 {
            margin: 0 0 10px 0;
            font-size: 22px;
        }
        .village-info .stats {
            font-size: 14px;
            opacity: 0.9;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border-left: 4px solid #27ae60;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        th { 
            background-color: #34495e; 
            color: white; 
            padding: 15px 10px;
            font-size: 12px;
            text-align: left;
        }
        td { 
            border: 1px solid #ddd; 
            padding: 12px 10px; 
            font-size: 12px;
        }
        tr:nth-child(even) { 
            background-color: #f8f9fa; 
        }
        tr:hover {
            background-color: #e3f2fd;
        }
        .ranking-badge {
            display: inline-block;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #3490dc;
            color: white;
            text-align: center;
            line-height: 23px;
            font-weight: bold;
            font-size: 12px;
        }
        .ranking-badge.rank1 {
            background-color: #f39c12;
        }
        .ranking-badge.rank2 {
            background-color: #95a5a6;
        }
        .ranking-badge.rank3 {
            background-color: #e67e22;
        }
        .global-ranking {
            background-color: #e8f5e8;
            color: #2e7d32;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
        }
        .net-flow {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title ?? 'Laporan Hasil Perangkingan PKH' }}</h1>
        <div class="info">
            Tanggal Cetak: {{ $tanggal ?? now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="village-info">
        <h2>{{ $namaDesa ?? 'Nama Desa' }}</h2>
        <div class="stats">
            {{ $totalPenerima ?? $desaData->count() }} Penerima Bantuan PKH Terpilih
        </div>
    </div>

    <!-- <div class="summary">
        <h3>Informasi Perangkingan</h3>
        <p><strong>Metode:</strong> PROMETHEE (Preference Ranking Organization Method for Enrichment Evaluation)</p>
        <p><strong>Basis Ranking:</strong> Net Flow Value (Leaving Flow - Entering Flow)</p>
        <p><strong>Catatan:</strong> Semakin tinggi nilai Net Flow, semakin tinggi prioritas penerima bantuan</p>
    </div> -->
    
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Ranking Desa</th>
                <th style="width: 28%;">Nama Penerima</th>
                <th style="width: 22%;">NIK</th>
                <th style="width: 15%;">No HP</th>
                <th style="width: 13%;">Net Flow</th>
                <th style="width: 10%;">Ranking Global</th>
            </tr>
        </thead>
        <tbody>
            @foreach($desaData as $item)
            <tr>
                <td style="text-align: center;">
                    <span class="ranking-badge 
                        @if($item['local_ranking'] == 1) rank1
                        @elseif($item['local_ranking'] == 2) rank2
                        @elseif($item['local_ranking'] == 3) rank3
                        @endif">
                        {{ $item['local_ranking'] }}
                    </span>
                </td>
                <td><strong>{{ $item['nama'] }}</strong></td>
                <td>{{ $item['nik'] }}</td>
                <td>{{ $item['no_hp'] }}</td>
                <td style="text-align: right;">
                    <span class="net-flow">{{ number_format($item['net_flow'], 4) }}</span>
                </td>
                <td style="text-align: center;">
                    <span class="global-ranking">#{{ $item['global_ranking'] }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Sistem Informasi Pengelolaan Program Keluarga Harapan (PKH)</strong></p>
        <p>Dicetak pada {{ now()->format('d F Y, H:i:s') }} WIB</p>
    </div>
</body>
</html>
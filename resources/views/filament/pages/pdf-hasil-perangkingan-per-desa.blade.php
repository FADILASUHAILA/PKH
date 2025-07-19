<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Laporan Hasil Perangkingan PKH - Per Desa' }}</title>
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
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border-left: 4px solid #3490dc;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        .village-section { 
            margin-bottom: 35px; 
            page-break-inside: avoid;
        }
        .village-header { 
            background-color: #3490dc;
            color: white;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .village-header h2 {
            margin: 0;
            font-size: 18px;
        }
        .village-header .count {
            font-size: 12px;
            opacity: 0.9;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th { 
            background-color: #34495e; 
            color: white; 
            padding: 12px 8px;
            font-size: 11px;
            text-align: left;
        }
        td { 
            border: 1px solid #ddd; 
            padding: 10px 8px; 
            font-size: 11px;
        }
        tr:nth-child(even) { 
            background-color: #f8f9fa; 
        }
        tr:hover {
            background-color: #e3f2fd;
        }
        .ranking-badge {
            display: inline-block;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background-color: #3490dc;
            color: white;
            text-align: center;
            line-height: 20px;
            font-weight: bold;
            font-size: 10px;
        }
        .ranking-badge.top3 {
            background-color: #f39c12;
        }
        .global-ranking {
            background-color: #e8f5e8;
            color: #2e7d32;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .page-break { 
            page-break-after: always; 
        }
        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title ?? 'Laporan Hasil Perangkingan PKH - Per Desa' }}</h1>
        <div class="info">
            Tanggal Cetak: {{ $tanggal ?? now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <!-- <div class="summary">
        <h3>Ringkasan Laporan</h3>
        <p><strong>Total Desa:</strong> {{ $totalDesa ?? count($rankingPerdesa) }}</p>
        <p><strong>Total Penerima:</strong> {{ $totalPenerima ?? $rankingPerdesa->flatten()->count() }}</p>
        <p><strong>Catatan:</strong> Menampilkan maksimal 8 penerima terbaik per desa berdasarkan ranking global</p>
    </div> -->
    
    @foreach($rankingPerdesa as $desaId => $desaData)
        <div class="village-section">
            <div class="village-header">
                <h2>{{ $desaData->first()['desa'] ?? 'Desa Tidak Diketahui' }}</h2>
                <div class="count">{{ $desaData->count() }} penerima terpilih</div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">Ranking Desa</th>
                        <th style="width: 25%;">Nama Penerima</th>
                        <th style="width: 20%;">NIK</th>
                        <th style="width: 15%;">No HP</th>
                        <th style="width: 15%;">Net Flow</th>
                        <th style="width: 15%;">Ranking Global</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($desaData as $item)
                    <tr>
                        <td style="text-align: center;">
                            <span class="ranking-badge {{ $item['local_ranking'] <= 3 ? 'top3' : '' }}">
                                {{ $item['local_ranking'] }}
                            </span>
                        </td>
                        <td><strong>{{ $item['nama'] }}</strong></td>
                        <td>{{ $item['nik'] }}</td>
                        <td>{{ $item['no_hp'] }}</td>
                        <td style="text-align: right;">{{ number_format($item['net_flow'], 4) }}</td>
                        <td style="text-align: center;">
                            <span class="global-ranking">#{{ $item['global_ranking'] }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    <div class="footer">
        Sistem Informasi PKH - {{ now()->format('Y') }}
    </div>
</body>
</html>
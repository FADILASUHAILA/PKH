<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Laporan Hasil Perangkingan PKH - Semua Desa' }}</title>
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
        .village-section { 
            margin-bottom: 30px; 
            page-break-inside: avoid;
        }
        .village-header { 
            background-color: #3490dc;
            color: white;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .village-header h2 {
            margin: 0;
            font-size: 16px;
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
            padding: 10px 8px;
            font-size: 11px;
        }
        td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            font-size: 11px;
        }
        tr:nth-child(even) { 
            background-color: #f8f9fa; 
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
        <h1>{{ $title ?? 'Laporan Hasil Perangkingan PKH - Semua Desa' }}</h1>
        <div class="info">
            Tanggal Cetak: {{ $tanggal ?? now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
    
    @foreach($rankingPerdesa as $desaId => $desaData)
        <div class="village-section">
            <div class="village-header">
                <h2>{{ $desaData->first()['desa'] ?? 'Desa Tidak Diketahui' }}</h2>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 30%;">Nama Penerima</th>
                        <th style="width: 25%;">NIK</th>
                        <th style="width: 20%;">No HP</th>
                        <th style="width: 15%;">Net Flow</th>
                        <th style="width: 10%;">Ranking</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($desaData as $item)
                    <tr>
                        <td><strong>{{ $item['nama'] }}</strong></td>
                        <td>{{ $item['nik'] }}</td>
                        <td>{{ $item['no_hp'] }}</td>
                        <td style="text-align: right;">{{ number_format($item['net_flow'], 4) }}</td>
                        <td style="text-align: center;"><strong>{{ $item['local_ranking'] }}</strong></td>
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
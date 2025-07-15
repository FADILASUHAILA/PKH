<!DOCTYPE html>
<html>
<head>
    <title>Penerima Bantuan PKH</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .village-section { margin-bottom: 30px; page-break-after: avoid; }
        .village-header { 
            /* background-color: #f8f9fa;  */
            /* padding-left: 5px; */
            /* border-left: 5px solid #3490dc; */
            margin-bottom: 15px;
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #3490dc; color: white; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <h1 style="text-align: center; margin-bottom: 30px;">Laporan Hasil Perangkingan PKH</h1>
    
    @foreach($rankingPerdesa as $desaId => $desaData)
        <div class="village-section">
            <div class="village-header">
                <h2>{{ $desaData->first()['desa'] ?? 'Unknown' }}</h2>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Nama Penerima</th>
                        <th>NIK</th>
                        <th>No HP</th>
                        <th>Ranking</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($desaData as $item)
                    <tr>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ $item['nik'] }}</td>
                        <td>{{ $item['no_hp'] }}</td>
                        <td>{{ $item['local_ranking'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
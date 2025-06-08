<!DOCTYPE html>
<html>
<head>
    <title>Hasil Perangkingan</title>
    <style>
        body { font-family: 'Courier'; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Hasil Perangkingan</h1>
    <table>
        <thead>
            <tr>
                <th>Nama Penerima</th>
                <th>NIK</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Net Flow</th>
                <th>Ranking</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rankingData as $item)
            <tr>
                <td>{{ $item['nama'] }}</td>
                <td>{{ $item['nik'] }}</td>
                <td>{{ $item['alamat'] }}</td>
                <td>{{ $item['no_hp'] }}</td>
                <td>{{ number_format($item['net_flow'], 4) }}</td>
                <td>{{ $item['ranking'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
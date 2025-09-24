<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pupuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #10b981;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #10b981;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #10b981;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Pupuk</h1>
        <p>Sistem Manajemen Pupuk</p>
        <p>Dicetak pada: {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="20%">Nama Pupuk</th>
                <th width="15%">Kategori</th>
                <th width="15%">Harga Jual</th>
                <th width="25%">Kandungan Nutrisi</th>
                <th width="20%">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pupuks as $index => $pupuk)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $pupuk->nama_pupuk }}</td>
                <td>{{ $pupuk->kategori->nama_kategori ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($pupuk->harga_jual, 0, ',', '.') }}</td>
                <td>
                    @if($pupuk->nutrisi->count() > 0)
                        @foreach($pupuk->nutrisi as $nutrisi)
                            {{ $nutrisi->nama_nutrisi }} ({{ $nutrisi->pivot->kandungan_persen }}%)@if(!$loop->last), @endif
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>{{ $pupuk->deskripsi ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Sistem Manajemen Pupuk - Dicetak secara otomatis</p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pupuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 10px;
        }
        .header h2 {
            color: #22c55e;
            margin: 0;
            font-size: 16px;
        }
        .header p {
            margin: 3px 0 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #22c55e;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .nutrisi {
            font-size: 9px;
            color: #555;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Data Pupuk</h2>
        <p>Dinas Pertanian</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Pupuk</th>
                <th width="15%">Kategori</th>
                <th width="12%">Harga Jual</th>
                <th width="25%">Kandungan Nutrisi</th>
                <th width="23%">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pupuks as $index => $pupuk)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $pupuk->nama_pupuk }}</strong></td>
                <td>{{ $pupuk->kategori->nama_kategori ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($pupuk->harga_jual, 0, ',', '.') }}</td>
                <td class="nutrisi">
                    @if($pupuk->nutrisi->count() > 0)
                        @foreach($pupuk->nutrisi as $nutrisi)
                            • {{ $nutrisi->nama_nutrisi }} ({{ $nutrisi->pivot->kandungan_persen }}%)<br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>{{ $pupuk->deskripsi ?: 'Tidak ada deskripsi' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px; color: #666;">
                    Tidak ada data pupuk
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total: {{ count($pupuks) }} pupuk | Sistem Inventaris Pupuk - Dinas Pertanian</p>
    </div>
</body>
</html>
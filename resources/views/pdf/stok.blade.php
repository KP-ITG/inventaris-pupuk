<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Stok Pupuk</title>
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
        .status {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .status.aman {
            background-color: #dcfce7;
            color: #166534;
        }
        .status.hampir-habis {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status.habis {
            background-color: #fee2e2;
            color: #991b1b;
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
        <h2>Data Stok Pupuk Pusat</h2>
        <p>Dinas Pertanian</p>
        <p>Dicetak pada: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="22%">Nama Pupuk</th>
                <th width="15%">Kategori</th>
                <th width="10%">Jumlah Stok</th>
                <th width="10%">Stok Min</th>
                <th width="10%">Stok Max</th>
                <th width="18%">Lokasi Gudang</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stocks as $index => $stock)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $stock->pupuk->nama_pupuk ?? '-' }}</strong></td>
                <td>{{ $stock->pupuk->kategori->nama_kategori ?? '-' }}</td>
                <td class="text-right">{{ number_format($stock->jumlah_stok, 0, ',', '.') }} kg</td>
                <td class="text-right">{{ number_format($stock->stok_minimum, 0, ',', '.') }} kg</td>
                <td class="text-right">{{ number_format($stock->stok_maksimum, 0, ',', '.') }} kg</td>
                <td>{{ $stock->lokasi_gudang ?: '-' }}</td>
                <td class="text-center">
                    <span class="status {{ $stock->status_stok }}">
                        {{ ucfirst(str_replace('_', ' ', $stock->status_stok)) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data stok</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem</p>
    </div>
</body>
</html>

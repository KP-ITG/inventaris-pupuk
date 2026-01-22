<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Kategori Pupuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 15px;
        }
        .header h2 {
            color: #22c55e;
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #22c55e;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Data Kategori Pupuk</h2>
        <p>Dinas Pertanian</p>
        <p>Dicetak pada: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Kategori</th>
                <th width="40%">Deskripsi</th>
                <th width="15%">Jumlah Pupuk</th>
                <th width="15%">Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $item->nama_kategori }}</strong></td>
                <td>{{ $item->deskripsi ?: 'Tidak ada deskripsi' }}</td>
                <td class="text-center">{{ $item->pupuk_count }} pupuk</td>
                <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px; color: #666;">
                    Tidak ada data kategori
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total: {{ count($kategori) }} kategori | Sistem Inventaris Pupuk - Dinas Pertanian</p>
    </div>
</body>
</html>
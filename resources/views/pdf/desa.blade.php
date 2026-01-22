<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Desa</title>
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
        .status {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .status.aktif {
            background-color: #dcfce7;
            color: #166534;
        }
        .status.non-aktif {
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
        <h2>Data Desa</h2>
        <p>Dinas Pertanian</p>
        <p>Dicetak pada: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Desa</th>
                <th width="18%">Kecamatan</th>
                <th width="18%">Kabupaten</th>
                <th width="15%">Kepala Desa</th>
                <th width="12%">Distribusi</th>
                <th width="12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($desa as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $item->nama_desa }}</strong></td>
                <td>{{ $item->kecamatan }}</td>
                <td>{{ $item->kabupaten }}</td>
                <td>{{ $item->nama_kepala_desa ?: '-' }}</td>
                <td class="text-center">{{ $item->distribusi_pupuk_count }} distribusi</td>
                <td class="text-center">
                    <span class="status {{ $item->status }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; color: #666;">
                    Tidak ada data desa
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total: {{ count($desa) }} desa | Sistem Inventaris Pupuk - Dinas Pertanian</p>
    </div>
</body>
</html>
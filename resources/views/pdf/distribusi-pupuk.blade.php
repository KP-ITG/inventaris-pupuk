<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Distribusi Pupuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
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
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #22c55e;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
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
            font-size: 8px;
            font-weight: bold;
        }
        .status.selesai {
            background-color: #dcfce7;
            color: #166534;
        }
        .status.dalam-perjalanan {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status.rencana {
            background-color: #f3f4f6;
            color: #374151;
        }
        .status.batal {
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
        <h2>Data Distribusi Pupuk ke Desa</h2>
        <p>Dinas Pertanian</p>
        <p>Dicetak pada: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Nomor Distribusi</th>
                <th width="18%">Daftar Pupuk</th>
                <th width="15%">Desa Tujuan</th>
                <th width="10%">Total Jumlah</th>
                <th width="10%">Tanggal</th>
                <th width="10%">Status</th>
                <th width="21%">Penerima</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distribusi as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->nomor_distribusi }}</td>
                <td>
                    @if($item->details && $item->details->count() > 0)
                        @foreach($item->details as $detail)
                            <div style="margin-bottom: 3px;">
                                <strong>{{ $detail->pupuk->nama_pupuk ?? '-' }}</strong>
                                <span style="float: right; color: #666;">{{ number_format($detail->jumlah_distribusi, 0, ',', '.') }} kg</span>
                            </div>
                        @endforeach
                        @if($item->details->count() > 1)
                            <div style="border-top: 1px solid #ddd; margin-top: 5px; padding-top: 3px; font-weight: bold;">
                                Total: {{ number_format($item->details->sum('jumlah_distribusi'), 0, ',', '.') }} kg
                            </div>
                        @endif
                    @else
                        <span style="color: #999;">Tidak ada item</span>
                    @endif
                </td>
                <td>{{ $item->desa->nama_desa ?? '-' }}, {{ $item->desa->kecamatan ?? '' }}</td>
                <td class="text-right">
                    <strong>{{ $item->details ? number_format($item->details->sum('jumlah_distribusi'), 0, ',', '.') : 0 }} kg</strong>
                </td>
                <td class="text-center">{{ $item->tanggal_distribusi ? $item->tanggal_distribusi->format('d/m/Y') : '-' }}</td>
                <td class="text-center">
                    <span class="status {{ $item->status_distribusi }}">
                        {{ ucfirst(str_replace('_', ' ', $item->status_distribusi)) }}
                    </span>
                </td>
                <td>
                    @if($item->nama_penerima)
                        {{ $item->nama_penerima }}<br>
                        <small style="color: #666;">{{ $item->jabatan_penerima }}</small>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data distribusi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem</p>
    </div>
</body>
</html>

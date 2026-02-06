<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Semua Data Inventaris Pupuk</title>
    <style>
        @page {
            margin: 15mm 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #059669;
        }
        .header h1 {
            margin: 0;
            color: #059669;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 10px;
        }
        .section-title {
            background-color: #059669;
            color: white;
            padding: 6px 10px;
            margin: 15px 0 8px 0;
            font-size: 11px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #10B981;
            color: white;
            padding: 5px;
            text-align: left;
            font-size: 9px;
            border: 1px solid #ddd;
        }
        td {
            padding: 4px 5px;
            border: 1px solid #ddd;
            font-size: 8px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            background-color: #f0fdf4;
            padding: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #059669;
        }
        .summary-item {
            display: inline-block;
            margin-right: 15px;
            font-size: 9px;
        }
        .summary-item strong {
            color: #059669;
        }
        @media print {
            .section-title {
                page-break-after: avoid;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN SEMUA DATA INVENTARIS PUPUK</h1>
        <p>Dinas Pertanian - {{ $tanggal }}</p>
        @if(isset($periode))
        <p style="font-weight: bold; color: #059669;">Periode: {{ $periode }}</p>
        @endif
    </div>

    <div class="summary">
        <div class="summary-item"><strong>Kategori:</strong> {{ count($kategori) }}</div>
        <div class="summary-item"><strong>Nutrisi:</strong> {{ count($nutrisi) }}</div>
        <div class="summary-item"><strong>Pupuk:</strong> {{ count($pupuk) }}</div>
        <div class="summary-item"><strong>Desa:</strong> {{ count($desa) }}</div>
        <div class="summary-item"><strong>Stok:</strong> {{ count($stok) }}</div>
        <div class="summary-item"><strong>Distribusi:</strong> {{ count($distribusi) }}</div>
    </div>

    <!-- KATEGORI -->
    <div class="section-title">1. DATA KATEGORI PUPUK</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Kategori</th>
                <th width="60%">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_kategori }}</td>
                <td>{{ $item->deskripsi ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center; color: #999;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- NUTRISI -->
    <div class="section-title">2. DATA NUTRISI</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Nutrisi</th>
                <th width="10%">Simbol</th>
                <th width="55%">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nutrisi as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_nutrisi }}</td>
                <td>{{ $item->simbol_nutrisi }}</td>
                <td>{{ $item->deskripsi_nutrisi ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; color: #999;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- DESA -->
    <div class="section-title">3. DATA DESA</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Desa</th>
                <th width="25%">Kecamatan</th>
                <th width="20%">Luas (ha)</th>
                <th width="20%">Penduduk</th>
            </tr>
        </thead>
        <tbody>
            @forelse($desa as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_desa }}</td>
                <td>{{ $item->kecamatan }}</td>
                <td>{{ $item->luas_wilayah }}</td>
                <td>{{ number_format($item->jumlah_penduduk) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #999;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- PUPUK -->
    <div class="section-title">4. DATA PUPUK</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Pupuk</th>
                <th width="25%">Kategori</th>
                <th width="20%">Harga (Rp)</th>
                <th width="20%">Nutrisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pupuk as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_pupuk }}</td>
                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ number_format($item->harga_jual ?? 0, 0, ',', '.') }}</td>
                <td>{{ $item->nutrisi->pluck('nama_nutrisi')->implode(', ') ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #999;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- STOK -->
    <div class="section-title">5. DATA STOK</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Pupuk</th>
                <th width="20%">Jumlah (kg)</th>
                <th width="20%">Min/Max (kg)</th>
                <th width="25%">Lokasi Gudang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stok as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->pupuk->nama_pupuk ?? '-' }}</td>
                <td>{{ number_format($item->jumlah_stok) }}</td>
                <td>{{ $item->stok_minimum }} / {{ $item->stok_maksimum }}</td>
                <td>{{ $item->lokasi_gudang ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #999;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- DISTRIBUSI -->
    <div class="section-title">6. DATA DISTRIBUSI PUPUK</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="13%">Nomor</th>
                <th width="17%">Desa</th>
                <th width="15%">Kecamatan</th>
                <th width="25%">Daftar Pupuk</th>
                <th width="13%">Total (kg)</th>
                <th width="12%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distribusi as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nomor_distribusi }}</td>
                <td>{{ $item->desa->nama_desa ?? '-' }}</td>
                <td>{{ $item->desa->kecamatan ?? '-' }}</td>
                <td>
                    @if($item->details && $item->details->count() > 0)
                        @foreach($item->details as $detail)
                            <div style="margin-bottom: 2px; font-size: 8px;">
                                • {{ $detail->pupuk->nama_pupuk ?? '-' }}
                                <span style="float: right;">{{ number_format($detail->jumlah_distribusi) }} kg</span>
                            </div>
                        @endforeach
                    @else
                        <span style="color: #999; font-size: 8px;">Tidak ada item</span>
                    @endif
                </td>
                <td style="font-weight: bold;">{{ $item->details ? number_format($item->details->sum('jumlah_distribusi')) : 0 }}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal_distribusi)) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #999;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

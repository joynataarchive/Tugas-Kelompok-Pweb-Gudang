<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Mutasi Stok — {{ $periodLabel }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #ffffff;
        }
        .header {
            border-bottom: 3px solid #0ea5e9;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 { font-size: 18px; font-weight: 700; color: #0284c7; }
        .header p  { font-size: 11px; color: #64748b; margin-top: 2px; }
        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 11px;
            color: #475569;
        }
        .summary-grid {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }
        .summary-box {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            background: #f8fafc;
        }
        .summary-box .label { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-box .value { font-size: 20px; font-weight: 700; color: #0284c7; margin-top: 2px; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        thead tr {
            background: #0ea5e9;
            color: white;
        }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
        }
        tbody tr:nth-child(even) { background: #f1f5f9; }
        tbody tr:nth-child(odd)  { background: #ffffff; }
        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .badge-in  { background: #dcfce7; color: #15803d; border-radius: 20px; padding: 2px 8px; font-size: 10px; font-weight: 600; }
        .badge-out { background: #fee2e2; color: #b91c1c; border-radius: 20px; padding: 2px 8px; font-size: 10px; font-weight: 600; }
        .footer {
            margin-top: 20px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>GudangSaaS — Laporan Mutasi Stok</h1>
        <p>Sistem Manajemen Inventaris Gudang</p>
    </div>

    {{-- Meta info --}}
    <div class="meta">
        <span><strong>Periode:</strong> {{ $periodLabel }}</span>
        <span><strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i') }}</span>
    </div>

    {{-- Summary --}}
    <div class="summary-grid">
        <div class="summary-box">
            <div class="label">Total Masuk</div>
            <div class="value">{{ number_format($summary['total_in']) }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Total Keluar</div>
            <div class="value">{{ number_format($summary['total_out']) }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Jumlah Transaksi</div>
            <div class="value">{{ number_format($summary['total_transactions']) }}</div>
        </div>
    </div>

    {{-- Data Table --}}
    @if($mutations->isEmpty())
        <p style="text-align:center; color:#64748b; padding: 30px 0;">Tidak ada data mutasi untuk periode ini.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th>SKU</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Stok Sebelum</th>
                <th>Stok Sesudah</th>
                <th>Oleh</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mutations as $i => $mutation)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $mutation->product->name ?? '-' }}</td>
                <td style="color:#64748b;">{{ $mutation->product->sku ?? '-' }}</td>
                <td>
                    @if($mutation->type === 'in')
                        <span class="badge-in">Masuk</span>
                    @else
                        <span class="badge-out">Keluar</span>
                    @endif
                </td>
                <td style="font-weight:600;">{{ number_format($mutation->quantity) }}</td>
                <td>{{ number_format($mutation->stock_before ?? 0) }}</td>
                <td>{{ number_format($mutation->stock_after ?? 0) }}</td>
                <td>{{ $mutation->user->name ?? '-' }}</td>
                <td>{{ $mutation->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Dokumen ini dibuat otomatis oleh sistem GudangSaaS. Laporan Mutasi Stok — {{ $periodLabel }}.
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sesi #{{ $session->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 11px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-box {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .highlight {
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
        }

        .warning {
            background-color: #fff3e0;
        }

        .danger {
            background-color: #ffebee;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        .bold {
            font-weight: bold;
        }

        .green {
            color: #2e7d32;
        }

        .red {
            color: #c62828;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN SESI TOKO</h1>
        <p>Posita POS System</p>
    </div>

    <div class="info-section">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 50%;">
                    <p><span class="info-label">ID Sesi:</span> #{{ $session->id }}</p>
                    <p><span class="info-label">Karyawan:</span> {{ $session->user->name }}</p>
                    <p><span class="info-label">Status:</span> {{ ucfirst($session->status) }}</p>
                </td>
                <td style="border: none; width: 50%;">
                    <p><span class="info-label">Dibuka:</span> {{ $session->opened_at?->format('d/m/Y H:i') }}</p>
                    <p><span class="info-label">Ditutup:</span> {{ $session->closed_at?->format('d/m/Y H:i') ?? '-' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <h3 style="margin-bottom: 10px;">Daftar Barang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Penyetok</th>
                <th class="text-center">Stok Awal</th>
                <th class="text-center">Terjual</th>
                <th class="text-center">Sisa</th>
                <th class="text-right">Harga Jual</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consignments as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->partner->name ?? '-' }}</td>
                    <td class="text-center">{{ $item->qty_initial }}</td>
                    <td class="text-center">{{ $item->qty_sold }}</td>
                    <td class="text-center">{{ $item->qty_remaining }}</td>
                    <td class="text-right">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal_income, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">TOTAL</th>
                <th class="text-center">{{ $summary['total_qty_initial'] }}</th>
                <th class="text-center">{{ $summary['total_qty_sold'] }}</th>
                <th class="text-center">{{ $summary['total_qty_remaining'] }}</th>
                <th></th>
                <th class="text-right">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <h3 style="margin-bottom: 10px;">Ringkasan Kas</h3>
    <div class="summary-box">
        <div class="summary-row">
            <span>Kas Awal (Modal)</span>
            <span class="bold">Rp {{ number_format($summary['opening_cash'], 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Total Pendapatan</span>
            <span class="bold green">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Kas Akhir (Estimasi)</span>
            <span class="bold">Rp {{ number_format($summary['expected_cash'], 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Kas Akhir (Aktual)</span>
            <span class="bold">Rp {{ number_format($summary['actual_cash'] ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row {{ $summary['cash_difference'] >= 0 ? 'highlight' : 'danger' }}">
            <span class="bold">Selisih</span>
            <span class="bold {{ $summary['cash_difference'] >= 0 ? 'green' : 'red' }}">
                Rp {{ number_format(abs($summary['cash_difference']), 0, ',', '.') }}
                ({{ $summary['cash_difference'] >= 0 ? 'Lebih' : 'Kurang' }})
            </span>
        </div>
    </div>

    <h3 style="margin-bottom: 10px;">Profit</h3>
    <div class="highlight">
        <div class="summary-row">
            <span>Total Pendapatan</span>
            <span>Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Total Modal Terjual</span>
            <span>Rp {{ number_format($summary['total_base_value'], 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span class="bold">Profit Bersih</span>
            <span class="bold green">Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}</span>
        </div>
    </div>

    @if($session->notes)
        <h3 style="margin-bottom: 10px;">Catatan</h3>
        <p style="padding: 10px; background: #f5f5f5; border-radius: 5px;">{{ $session->notes }}</p>
    @endif

    <div class="footer">
        <p>Laporan dibuat pada: {{ $generated_at->format('d/m/Y H:i:s') }}</p>
        <p>Posita POS System &copy; {{ date('Y') }}</p>
    </div>
</body>

</html>
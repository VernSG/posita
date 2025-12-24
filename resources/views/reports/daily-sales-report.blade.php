<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Harian - {{ $date }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
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
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-box {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .bold {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN HARIAN</h1>
        <p>Tanggal: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
    </div>

    <div class="summary-box">
        <p><span class="bold">Total Sesi:</span> {{ $total_sessions }}</p>
        <p><span class="bold">Total Pendapatan:</span> Rp {{ number_format($total_income, 0, ',', '.') }}</p>
    </div>

    @foreach($sessions as $session)
        <h3 style="margin: 20px 0 10px 0;">Sesi #{{ $session->id }} - {{ $session->user->name }}</h3>
        <p style="color: #666; margin-bottom: 10px;">
            {{ $session->opened_at?->format('H:i') }} - {{ $session->closed_at?->format('H:i') ?? 'Masih Buka' }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Penyetok</th>
                    <th class="text-center">Terjual</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($session->consignments as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->partner->name ?? '-' }}</td>
                        <td class="text-center">{{ $item->qty_sold }}</td>
                        <td class="text-right">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal_income, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Total Sesi</th>
                    <th class="text-right">Rp
                        {{ number_format($session->consignments->sum('subtotal_income'), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    @endforeach

    <div class="footer">
        <p>Laporan dibuat pada: {{ $generated_at->format('d/m/Y H:i:s') }}</p>
        <p>Posita POS System</p>
    </div>
</body>

</html>
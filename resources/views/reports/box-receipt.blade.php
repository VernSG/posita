<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Order #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
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
            color: #666;
        }
        .info-value {
            font-weight: bold;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .items-table td.right {
            text-align: right;
        }
        .total-section {
            border-top: 2px solid #333;
            padding-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .total-row.grand {
            font-size: 16px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #FEF3C7; color: #92400E; }
        .status-paid { background: #D1FAE5; color: #065F46; }
        .status-completed { background: #DBEAFE; color: #1E40AF; }
        .status-cancelled { background: #FEE2E2; color: #991B1B; }
    </style>
</head>
<body>
    <div class="header">
        <h1>KWITANSI</h1>
        <p>Order Box #{{ $order->id }}</p>
    </div>

    <div class="info-section">
        <table width="100%">
            <tr>
                <td width="50%">
                    <p><strong>Pelanggan:</strong></p>
                    <p style="font-size: 14px; font-weight: bold;">{{ $order->customer_name }}</p>
                </td>
                <td width="50%" style="text-align: right;">
                    <p><strong>Tanggal Pengambilan:</strong></p>
                    <p style="font-size: 14px;">{{ $order->pickup_datetime->format('d/m/Y H:i') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="50%">Item</th>
                <th width="15%" style="text-align: center;">Qty</th>
                <th width="17%" style="text-align: right;">Harga</th>
                <th width="18%" style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td class="right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Tidak ada item</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-section">
        <table width="100%">
            <tr>
                <td width="70%"></td>
                <td width="30%">
                    <div class="total-row grand">
                        <span>TOTAL:</span>
                        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 20px;">
        <p><strong>Status:</strong> 
            <span class="status-badge status-{{ $order->status }}">
                @switch($order->status)
                    @case('pending') MENUNGGU @break
                    @case('paid') LUNAS @break
                    @case('completed') SELESAI @break
                    @case('cancelled') BATAL @break
                    @default {{ strtoupper($order->status) }}
                @endswitch
            </span>
        </p>
    </div>

    <div class="footer">
        <p>Dicetak: {{ $generated_at->format('d/m/Y H:i') }}</p>
        <p>Terima kasih atas pesanan Anda!</p>
    </div>
</body>
</html>

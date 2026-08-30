<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 12px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            color: #3b82f6;
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header .invoice-number {
            font-size: 13px;
            color: #666666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            vertical-align: top;
            padding-bottom: 20px;
        }
        .info-table h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #999999;
            margin: 0 0 6px 0;
        }
        .info-table p {
            margin: 0 0 3px 0;
            line-height: 1.4;
        }
        .items-table {
            margin-top: 10px;
        }
        .items-table th {
            background-color: #f3f4f6;
            text-align: left;
            padding: 8px 10px;
            font-size: 11px;
            text-transform: uppercase;
            color: #666666;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table .text-right {
            text-align: right;
        }
        .total-row td {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #3b82f6;
            border-bottom: none;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            text-transform: capitalize;
            background-color: #dbeafe;
            color: #1e40af;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #999999;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <div class="invoice-number">Invoice #{{ $order->order_number }} &nbsp;&bull;&nbsp; {{ $order->created_at->format('M d, Y') }}</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <h3>Billed To</h3>
                <p><strong>{{ $order->name }}</strong></p>
                <p>{{ $order->email }}</p>
                <p>{{ $order->phone }}</p>
            </td>
            <td width="50%">
                <h3>Shipping Address</h3>
                <p>{{ $order->address }}, {{ $order->locality }}</p>
                <p>{{ $order->landmark }}</p>
                <p>{{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <h3>Payment Method</h3>
                <p>{{ $order->payment_method === 'cod' ? 'Cash On Delivery' : $order->payment_method }}</p>
            </td>
            <td>
                <h3>Status</h3>
                <span class="status-badge">{{ $order->status }}</span>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-right">Price</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right">Total</td>
                <td class="text-right">${{ number_format($order->total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if ($order->notes)
        <table class="info-table" style="margin-top: 20px;">
            <tr>
                <td>
                    <h3>Order Notes</h3>
                    <p>{{ $order->notes }}</p>
                </td>
            </tr>
        </table>
    @endif

    <div class="footer">
        Thank you for your order!
    </div>

</body>
</html>

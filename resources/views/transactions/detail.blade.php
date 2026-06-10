<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
</head>
<body>
    <h1>Detail Transaksi</h1>
    <p>ID Transaksi: {{ $transaction->id }}</p>
    <p>Customer: {{ $transaction->customer?->name ?? 'Anonim' }}</p>
    <p>Total: Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>

    <h2>Item Belanja</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->items as $item)
                <tr>
                    <td>{{ $item->product?->name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

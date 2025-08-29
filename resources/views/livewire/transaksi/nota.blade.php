<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi #{{ $transaksi->kode }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            line-height: 1.5;
            padding: 20px;
            width: 200px;
            /* Ukuran kertas nota */
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 10px;
        }

        .details,
        .items,
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .items th,
        .items td {
            border-top: 1px dashed #ccc;
            padding: 2px 0;
            text-align: left;
        }

        .summary td {
            padding: 2px 0;
            text-align: right;
        }

        .total td {
            font-weight: bold;
        }

        .separator {
            border-bottom: 1px dashed #ccc;
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4>Nama Toko Anda</h4>
        <p>Jl. Contoh No. 123</p>
        <p>Telp: 0812-3456-7890</p>
    </div>
    <div class="separator"></div>
    <table class="details">
        <tr>
            <td>Kode Nota</td>
            <td>: {{ $transaksi->kode }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($transaksi->tanggaltransaksi)->translatedFormat('d M Y, H:i') }}</td>
        </tr>
    </table>
    <div class="separator"></div>
    <table class="items">
        <thead>
            <tr>
                <th>Item</th>
                <th style="text-align: right;">Jml</th>
                <th style="text-align: right;">Harga</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->detailTransaksis as $detail)
            <tr>
                <td>{{ $detail->produk->nama }}</td>
                <td style="text-align: right;">{{ $detail->jumlah }}</td>
                <td style="text-align: right;">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="separator"></div>
    <table class="summary">
        <tr>
            <td>Total</td>
            <td>: Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td>: Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembalian</td>
            <td>: Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>
    <div class="separator"></div>
    <div class="footer">
        <p>Terima kasih telah berbelanja!</p>
    </div>
</body>

</html>

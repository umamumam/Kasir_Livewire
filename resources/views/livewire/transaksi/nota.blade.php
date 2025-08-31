<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi #{{ $transaksi->kode }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            line-height: 1.5;
            padding: 20px;
            width: 200px;
            /* Ukuran kertas nota */
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 5px;
            line-height: 1;
        }

        .details,
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .details td,
        .summary td {
            padding: 2px 0;
        }

        .details td:last-child,
        .summary td:last-child {
            text-align: right;
        }

        .total td {
            font-weight: bold;
        }

        .separator {
            border-bottom: 1px dashed #000000;
            margin: 5px 0;
        }

        .item-list {
            margin-bottom: 5px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
        }

        .item-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-details {
            white-space: nowrap;
        }
    </style>

</head>

<body>
    <div class="header">
        <h3>Agen Sosis <br> Lancar Manunggal</h3>
        <p>Jl. Raya Tayu-Jepara Km 7 <br> depan Kantor Pos Ngablak</p>
        <p>HP: 085201454015</p>
    </div>
    <div class="separator"></div>
    <table class="details">
        <tr>
            <td>No Transaksi</td>
            <td>: {{ $transaksi->kode }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($transaksi->tanggaltransaksi)->translatedFormat('d M Y') }}</td>
        </tr>
    </table>
    <div class="separator"></div>
    <table class="details" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="text-align:left;">Produk</th>
                <th style="text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->detailTransaksis as $detail)
            <tr>
                <td>
                    {{ $detail->produk->nama }}<br>
                    {{ number_format($detail->harga, 0, ',', '.') }} x {{ $detail->jumlah }}
                </td>
                <td style="text-align:right;">
                    {{ number_format($detail->subtotal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="separator"></div>
    <table class="summary">
        <tr>
            <td>Total</td>
            <th style="text-align:right;">: Rp {{ number_format($transaksi->total, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <td>Bayar</td>
            <th style="text-align:right;">: Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <td>Kembalian</td>
            <th style="text-align:right;">: Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</th>
        </tr>
    </table>
    <div class="separator"></div>
    <div class="footer">
        <p>Terima kasih telah berbelanja! <br>
            Barang yang sudah dibeli
            tidak dapat dikembalikan.</p>
    </div>
</body>

</html>

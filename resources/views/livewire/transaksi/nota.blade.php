<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi #{{ $transaksi->kode }}</title>
    <style>
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ public_path("fonts/PlusJakartaSans-Regular.ttf") }}') format('truetype');
        }

        @page {
            margin: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', DejaVu Sans, sans-serif;
            font-size: 13px;
            line-height: 1.2;
            padding: 20px;
            width: 200px;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 5px;
        }

        .details,
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .details td,
        .details th {
            padding: 1px 0;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
            white-space: nowrap;
        }

        .separator {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }

        .summary td:last-child,
        .summary th:last-child {
            text-align: right;
            padding-right: 10px;
            white-space: nowrap;
        }

        .total-row {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3 style="margin-bottom: 5px;">Agen Sosis <br> Lancar Manunggal</h3>
        <p style="margin: 0;">Jl. Raya Tayu-Jepara Km 7 <br> depan Kantor Pos Ngablak</p>
        <p style="margin: 0;">HP: 085201454015</p>
    </div>

    <div class="separator"></div>

    <table class="details">
        <tr>
            <td class="text-left">No Transaksi</td>
            <td class="text-left">: {{ $transaksi->kode }}</td>
        </tr>
        <tr>
            <td class="text-left">Tanggal</td>
            <td class="text-left">: {{ \Carbon\Carbon::parse($transaksi->tanggaltransaksi)->translatedFormat('d M Y') }}
            </td>
        </tr>
    </table>

    <div class="separator"></div>

    <table class="details">
        <thead>
            <tr>
                <th class="text-left">Produk</th>
                <th class="text-left">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->detailTransaksis as $detail)
            <tr>
                <td class="text-left" style="vertical-align: top;">
                    {{ $detail->produk->nama }}
                </td>
                <td rowspan="2" class="text-right" style="vertical-align: bottom; padding-right: 50px;">
                    {{ number_format($detail->subtotal, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td class="text-left" style="font-size: 12px; padding-bottom: 5px;">
                    {{ number_format($detail->harga, 0, ',', '.') }} x {{ $detail->jumlah }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="separator"></div>

    <table class="summary">
        <tr>
            <td class="text-left">Total</td>
            <th class="text-right">: Rp {{ number_format($transaksi->total, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <td class="text-left">Bayar</td>
            <th class="text-right">: Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <td class="text-left">Kembalian</td>
            <th class="text-right">: Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</th>
        </tr>
    </table>

    <div class="separator"></div>

    <div class="footer">
        <p>Terima kasih telah berbelanja! <br>
            Barang yang sudah dibeli <br>
            tidak dapat dikembalikan.</p>
    </div>
</body>

</html>
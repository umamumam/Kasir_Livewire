<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaController extends Controller
{
    public function cetakNota($transaksiId)
    {
        try {
            $transaksi = Transaksi::with(['detailTransaksis.produk'])->find($transaksiId);

            if ($transaksi) {
                $safeKode = str_replace(['/', '\\'], '-', $transaksi->kode);
                $pdf = Pdf::loadView('livewire.transaksi.nota', compact('transaksi'));
                return $pdf->stream('nota-' . $safeKode . '.pdf');
            } else {
                abort(404, 'Transaksi tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return response('Gagal mencetak nota. Detail: ' . $e->getMessage(), 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // default bulan ini
        $bulan = $request->bulan ?? Carbon::now()->format('m');
        $tahun = $request->tahun ?? Carbon::now()->format('Y');

        // ambil langsung dari transaksis
        $laporan = Transaksi::whereMonth('tanggaltransaksi', $bulan)
            ->whereYear('tanggaltransaksi', $tahun)
            ->orderByDesc('tanggaltransaksi')
            ->get();

        return view('laporan.index', compact('laporan','bulan','tahun'));
    }
}

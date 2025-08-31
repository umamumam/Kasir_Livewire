<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data 7 hari terakhir
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(6);

        $dates = [];
        $totals = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dates[] = $date->format('d/m');
            $totals[] = 0;
        }

        $transactions = Transaksi::whereBetween('tanggaltransaksi', [
            $startDate->format('Y-m-d 00:00:00'),
            $endDate->format('Y-m-d 23:59:59')
        ])->get();

        $groupedData = [];
        foreach ($transactions as $transaction) {
            $date = Carbon::parse($transaction->tanggaltransaksi)->format('d/m');
            $groupedData[$date] = ($groupedData[$date] ?? 0) + $transaction->total;
        }

        foreach ($dates as $index => $date) {
            if (isset($groupedData[$date])) {
                $totals[$index] = $groupedData[$date];
            }
        }

        $hariIni = Transaksi::whereDate('tanggaltransaksi', Carbon::today())->sum('total');
        $kemarin = Transaksi::whereDate('tanggaltransaksi', Carbon::yesterday())->sum('total');

        $persentase = 0;
        if ($kemarin > 0) {
            $persentase = (($hariIni - $kemarin) / $kemarin) * 100;
        }

        $bulanIni = Transaksi::whereMonth('tanggaltransaksi', Carbon::now()->month)
            ->whereYear('tanggaltransaksi', Carbon::now()->year)
            ->sum('total');

        $bulanKemarin = Transaksi::whereMonth('tanggaltransaksi', Carbon::now()->subMonth()->month)
            ->whereYear('tanggaltransaksi', Carbon::now()->subMonth()->year)
            ->sum('total');

        $persentaseBulan = 0;
        if ($bulanKemarin > 0) {
            $persentaseBulan = (($bulanIni - $bulanKemarin) / $bulanKemarin) * 100;
        }

        $topProducts = DetailTransaksi::select(
            'produks.id',
            'produks.nama as nama_produk',
            'produks.created_at',
            DB::raw('SUM(detail_transaksis.jumlah) as total_terjual')
        )
            ->join('produks', 'detail_transaksis.produk_id', '=', 'produks.id')
            ->groupBy('produks.id', 'produks.nama', 'produks.created_at')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                // 🆕 Produk baru < 30 hari
                $item->is_new = Carbon::parse($item->created_at)->greaterThanOrEqualTo(now()->subDays(30));

                // 🔥 Cek tren minggu ini vs minggu lalu
                $salesThisWeek = DetailTransaksi::where('produk_id', $item->id)
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->sum('jumlah');

                $salesLastWeek = DetailTransaksi::where('produk_id', $item->id)
                    ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
                    ->sum('jumlah');

                $item->is_hot = $salesLastWeek > 0 && $salesThisWeek > ($salesLastWeek * 1.5);

                return $item;
            });


        return view('dashboard', [
            'chartData' => $totals,
            'categories' => $dates,
            'totalHarian' => $hariIni,
            'persentase' => round($persentase, 2),
            'totalBulanan' => $bulanIni,
            'persentaseBulanan' => round($persentaseBulan, 2),
            'topProducts' => $topProducts,
        ]);
    }
}

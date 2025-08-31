<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Carbon\Carbon;

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

        return view('dashboard', [
            'chartData' => $totals,
            'categories' => $dates,
            'totalHarian' => $hariIni,
            'persentase' => round($persentase, 2),
            'totalBulanan' => $bulanIni,
            'persentaseBulanan' => round($persentaseBulan, 2),
        ]);
    }
}

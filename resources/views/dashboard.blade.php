<x-app-layout>
    {{-- @extends('layouts.app')
    @section('content') --}}
    <div class="row">
        <div class="col-lg-8 d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">🛍️ Transaksi Penjualan</h5>
                        </div>
                        <div>
                            <select class="form-select">
                                <option value="{{ date('n') }}">
                                    {{ date('F Y') }}
                                </option>
                            </select>

                        </div>
                    </div>
                    <div id="transaksi"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card overflow-hidden">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-9 fw-semibold">💸 Pendapatan Harian</h5>
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="card-title fw-semibold mb-3">RP {{ number_format($totalHarian, 0, ',',
                                        '.') }}</h4>
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="me-1 rounded-circle
                                        {{ $persentase >= 0 ? 'bg-light-success' : 'bg-light-danger' }}
                                        round-20 d-flex align-items-center justify-content-center">

                                            <i
                                                class="ti
                                                {{ $persentase >= 0 ? 'ti-arrow-up-left text-success' : 'ti-arrow-down-right text-danger' }}">
                                            </i>
                                        </span>

                                        <p class="text-dark me-1 fs-3 mb-0">
                                            {{ $persentase >= 0 ? '+' : '' }}{{ $persentase }}%
                                        </p>
                                        <p class="fs-3 mb-0">dibanding kemarin</p>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="me-4">
                                            <span class="round-8 bg-primary rounded-circle me-2 d-inline-block"></span>
                                            <span class="fs-2">2025</span>
                                        </div>
                                        <div>
                                            <span
                                                class="round-8 bg-light-primary rounded-circle me-2 d-inline-block"></span>
                                            <span class="fs-2">2025</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-center">
                                        <div id="breakup"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <!-- Monthly Earnings -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold">Pendapatan Bulanan</h5>
                                    <h4 class="card-title fw-semibold mb-3">Rp {{ number_format($totalBulanan, 0, ',',
                                        '.') }}</h4>
                                    <div class="d-flex align-items-center pb-1">
                                        <span class="me-2 rounded-circle
                                            {{ $persentaseBulanan >= 0 ? 'bg-light-success' : 'bg-light-danger' }}
                                            round-20 d-flex align-items-center justify-content-center">
                                            <i
                                                class="ti
                                                {{ $persentaseBulanan >= 0 ? 'ti-arrow-up-left text-success' : 'ti-arrow-down-right text-danger' }}">
                                            </i>
                                        </span>
                                        <p class="text-dark me-1 fs-3 mb-0">
                                            {{ $persentaseBulanan >= 0 ? '+' : '' }}{{ $persentaseBulanan }}%
                                        </p>
                                        <p class="fs-3 mb-0">dibanding bulan lalu</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-currency-dollar fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="earning"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 class="card-title fw-semibold">🏆 Top Produk</h5>
                    </div>
                    <ul class="timeline-widget mb-0 position-relative mb-n5">
                        @foreach($topProducts as $product)
                        <li class="timeline-item d-flex position-relative overflow-hidden">
                            <div class="timeline-time text-dark flex-shrink-0 text-end">
                                {{ $product->total_terjual }}x
                            </div>
                            <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                <span class="timeline-badge border-2 border
                                @if($product->is_new) border-info
                                @elseif($product->is_hot) border-danger
                                @else border-success
                                @endif
                                flex-shrink-0 my-8">
                                </span>
                                <span class="timeline-badge-border d-block flex-shrink-0"></span>
                            </div>
                            <div class="timeline-desc fs-3 text-dark mt-n1 fw-semibold">
                                {{ $product->nama_produk }}
                                @if($product->is_new)
                                <span class="badge bg-info ms-2">🆕 New</span>
                                @endif
                                @if($product->is_hot)
                                <span class="badge bg-danger ms-2">🔥 Hot</span>
                                @endif
                                <div class="text-muted fw-normal fs-2">
                                    Terjual {{ $product->total_terjual }} unit
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">🏆️ Top Produk Terlaris 🏆️</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">
                                        <h6 class="fw-semibold mb-0">NO</h6>
                                    </th>
                                    <th>
                                        <h6 class="fw-semibold mb-0">NAMA PRODUK</h6>
                                    </th>
                                    <th class="text-center">
                                        <h6 class="fw-semibold mb-0">JUMLAH TERJUAL</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $index => $product)
                                <tr>
                                    <!-- Ranking dengan badge -->
                                    <td class="text-center">
                                        @if($index == 0)
                                        <span class="badge bg-warning text-dark fs-6">🥇 {{ $index+1 }}</span>
                                        @elseif($index == 1)
                                        <span class="badge bg-secondary fs-6">🥈 {{ $index+1 }}</span>
                                        @elseif($index == 2)
                                        <span class="badge bg-success fs-6">🥉 {{ $index+1 }}</span>
                                        @else
                                        <span class="badge bg-dark text-white">{{ $index+1 }}</span>
                                        @endif
                                    </td>

                                    <!-- Nama Produk -->
                                    <td>
                                        <h6 class="fw-semibold mb-1">{{ $product->nama_produk }}</h6>
                                    </td>

                                    <!-- Jumlah Terjual dengan badge -->
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill px-3 py-2">
                                            {{ $product->total_terjual }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Tidak ada data produk terlaris.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card overflow-hidden rounded-2">
                <div class="position-relative">
                    <a href="javascript:void(0)">
                        <img src="{{ asset('modernize/src/assets/images/products/s4.jpg') }}"
                            class="card-img-top rounded-0" alt="Product Image">
                    </a>
                    <a href="javascript:void(0)"
                        class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart"><i
                            class="ti ti-basket fs-4"></i></a>
                </div>
                <div class="card-body pt-3 p-4">
                    <h6 class="fw-semibold fs-4">Boat Headphone</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fw-semibold fs-4 mb-0">$50 <span
                                class="ms-2 fw-normal text-muted fs-3"><del>$65</del></span></h6>
                        <ul class="list-unstyled d-flex align-items-center mb-0">
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card overflow-hidden rounded-2">
                <div class="position-relative">
                    <a href="javascript:void(0)">
                        <img src="{{ asset('modernize/src/assets/images/products/s5.jpg') }}"
                            class="card-img-top rounded-0" alt="Product Image">
                    </a>
                    <a href="javascript:void(0)"
                        class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart"><i
                            class="ti ti-basket fs-4"></i></a>
                </div>
                <div class="card-body pt-3 p-4">
                    <h6 class="fw-semibold fs-4">MacBook Air Pro</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fw-semibold fs-4 mb-0">$650 <span
                                class="ms-2 fw-normal text-muted fs-3"><del>$900</del></span></h6>
                        <ul class="list-unstyled d-flex align-items-center mb-0">
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card overflow-hidden rounded-2">
                <div class="position-relative">
                    <a href="javascript:void(0)">
                        <img src="{{ asset('modernize/src/assets/images/products/s7.jpg') }}"
                            class="card-img-top rounded-0" alt="Product Image">
                    </a>
                    <a href="javascript:void(0)"
                        class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart"><i
                            class="ti ti-basket fs-4"></i></a>
                </div>
                <div class="card-body pt-3 p-4">
                    <h6 class="fw-semibold fs-4">Red Valvet Dress</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fw-semibold fs-4 mb-0">$150 <span
                                class="ms-2 fw-normal text-muted fs-3"><del>$200</del></span></h6>
                        <ul class="list-unstyled d-flex align-items-center mb-0">
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card overflow-hidden rounded-2">
                <div class="position-relative">
                    <a href="javascript:void(0)">
                        <img src="{{ asset('modernize/src/assets/images/products/s11.jpg') }}"
                            class="card-img-top rounded-0" alt="Product Image">
                    </a>
                    <a href="javascript:void(0)"
                        class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart"><i
                            class="ti ti-basket fs-4"></i></a>
                </div>
                <div class="card-body pt-3 p-4">
                    <h6 class="fw-semibold fs-4">Cute Soft Teddybear</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fw-semibold fs-4 mb-0">$285 <span
                                class="ms-2 fw-normal text-muted fs-3"><del>$345</del></span></h6>
                        <ul class="list-unstyled d-flex align-items-center mb-0">
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="me-1" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a>
                            </li>
                            <li><a class="" href="javascript:void(0)"><i class="ti ti-star text-warning"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="py-6 px-6 text-center">
        <p class="mb-0 fs-4">Design and Developed by <a href="https://github.com/umamumam" target="_blank"
                class="pe-1 text-primary text-decoration-underline">mfthlmm</a> Sistem Kasir <a
                href="https://github.com/umamumam" style="color: red">Lancar Manunggal</a></p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var chartOptions = {
                series: [{
                    name: "Total Transaksi",
                    data: @json($chartData)
                }],
                chart: {
                    type: "bar",
                    height: 345,
                    offsetX: -15,
                    toolbar: { show: true },
                    foreColor: "#adb0bb",
                    fontFamily: 'inherit',
                    sparkline: { enabled: false },
                },
                colors: ["#5D87FF"],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "35%",
                        borderRadius: [6],
                        borderRadiusApplication: 'end',
                        borderRadiusWhenStacked: 'all'
                    },
                },
                markers: { size: 0 },
                dataLabels: { enabled: false },
                legend: { show: false },
                grid: {
                    borderColor: "rgba(0,0,0,0.1)",
                    strokeDashArray: 3,
                    xaxis: { lines: { show: false } },
                },
                xaxis: {
                    type: "category",
                    categories: @json($categories),
                    labels: { style: { cssClass: "grey--text lighten-2--text fill-color" } },
                },
                yaxis: {
                    show: true,
                    min: 0,
                    tickAmount: 4,
                    labels: {
                        style: { cssClass: "grey--text lighten-2--text fill-color" },
                        formatter: function (val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    },
                },
                stroke: {
                    show: true,
                    width: 3,
                    lineCap: "butt",
                    colors: ["transparent"],
                },
                tooltip: {
                    theme: "light",
                    y: {
                        formatter: function (val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 3 }
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#transaksi"), chartOptions);
            chart.render();
        });
    </script>
    {{-- @endsection --}}
</x-app-layout>

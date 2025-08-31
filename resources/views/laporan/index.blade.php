<x-app-layout>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">📊 Laporan Transaksi</h4>

            {{-- Filter Bulan & Tahun --}}
            <form method="GET" action="{{ route('laporan.transaksi') }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select name="bulan" id="bulan" class="form-select">
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ str_pad($i,2,'0',STR_PAD_LEFT) }}"
                                {{ $bulan==str_pad($i,2,'0',STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select">
                        @for($t = now()->year; $t >= now()->year - 5; $t--)
                            <option value="{{ $t }}" {{ $tahun==$t ? 'selected' : '' }}>{{ $t }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </form>

            {{-- Tabel Data --}}
            <div class="table-responsive">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Bayar</th>
                            <th>Kembalian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporan as $row)
                        <tr>
                            <td>{{ $row->kode }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggaltransaksi)->format('d-m-Y H:i') }}</td>
                            <td>Rp {{ number_format($row->total,0,',','.') }}</td>
                            <td>Rp {{ number_format($row->bayar,0,',','.') }}</td>
                            <td>Rp {{ number_format($row->kembalian,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>

@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <h3 class="fw-bold mb-4">Laporan Transaksi & Pemesanan</h3>

        <div class="card card-round mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
                    </div>
                    <div class="col-md-4 align-self-end">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter Laporan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-round">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Hasil Ringkasan Transaksi</h5>
                <h5 class="fw-bold text-success mb-0">Total Masuk: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No Invoice</th>
                                <th>Pemesan</th>
                                <th>Tujuan Wisata</th>
                                <th>Tgl Keberangkatan</th>
                                <th>Total Sewa</th>
                                <th>DP Masuk</th>
                                <th>Metode Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporan as $row)
                                <tr>
                                    <td>{{ $row->no_invoice }}</td>
                                    <td>{{ $row->nama_pemesan }}</td>
                                    <td>{{ $row->tujuan_wisata }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->tanggal_keberangkatan)->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($row->biaya_sewa, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($row->dp_1 + $row->dp_2, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-secondary">{{ strtoupper($row->metode_pembayaran) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada transaksi ditemukan pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <h3 class="fw-bold mb-4">Daftar Permintaan Pemesanan Client</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card card-round">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>No Invoice</th>
                                <th>Pemesan</th>
                                <th>Tujuan / Paket</th>
                                <th>Tanggal Keberangkatan</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $b)
                                <tr>
                                    <td>{{ $b->no_invoice }}</td>
                                    <td>
                                        <strong>{{ $b->nama_pemesan }}</strong><br>
                                        <small>{{ $b->telepon_pemesan }}</small>
                                    </td>
                                    <td>{{ $b->tujuan_wisata }}</td>
                                    <td>{{ \Carbon\Carbon::parse($b->tanggal_keberangkatan)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ strtoupper($b->status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.booking.cetak-invoice', $b->uuid) }}" target="_blank"
                                            class="btn btn-secondary btn-sm" title="Cetak Bukti Sewa">
                                            <i class="fa fa-print"></i> Cetak Invoice
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

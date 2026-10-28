@extends('layouts.admin')

@section('content')
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->nama }}! 👋</h3>
                <h6 class="op-7 mb-0">Kelola pemesanan paket wisata dan informasi akun Anda di sini.</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('client.paket.index') }}" class="btn btn-primary btn-round">
                    <i class="fa fa-search me-1"></i> Jelajahi Paket Wisata
                </a>
            </div>
        </div>

        <!-- Info Cards / Ringkasan -->
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Pemesanan</p>
                                    <h4 class="card-title">{{ $totalPemesanan ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Pemesanan Aktif</p>
                                    <h4 class="card-title">{{ $bookingAktif ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Perjalanan Selesai</p>
                                    <h4 class="card-title">{{ $totalSelesai ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Riwayat Pemesanan Terakhir -->
            <div class="col-md-8">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Pemesanan Terakhir Saya</div>
                            <div class="card-tools">
                                <a href="{{ route('client.riwayat') }}" class="btn btn-label-info btn-round btn-sm">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No Invoice</th>
                                        <th>Paket</th>
                                        <th>Tgl Keberangkatan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentBookings ?? [] as $b)
                                        <tr>
                                            <td><span class="fw-bold text-primary">{{ $b->no_invoice }}</span></td>
                                            <td>{{ $b->tujuan_wisata }}</td>
                                            <td>{{ \Carbon\Carbon::parse($b->tanggal_keberangkatan)->format('d M Y') }}
                                            </td>
                                            <td>
                                                @if ($b->status == 'pending')
                                                    <span class="badge bg-warning text-dark">Menunggu Invoice</span>
                                                @elseif($b->status == 'sent')
                                                    <span class="badge bg-info">Invoice Terbit</span>
                                                @elseif($b->status == 'booked')
                                                    <span class="badge bg-success">Booked</span>
                                                @elseif($b->status == 'lunas')
                                                    <span class="badge bg-primary">Lunas</span>
                                                @else
                                                    <span class="badge bg-danger">Batal</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Belum ada transaksi
                                                pemesanan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rekomendasi Paket Wisata -->
            <div class="col-md-4">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">Rekomendasi Paket</div>
                    </div>
                    <div class="card-body">
                        @forelse($paketRekomendasi ?? [] as $p)
                            <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                <div class="flex-1">
                                    <h6 class="fw-bold mb-1">{{ $p->nama_sub_paket }}</h6>
                                    <small
                                        class="text-muted block d-block mb-1">{{ $p->PaketWisata->nama_paket ?? 'Paket Wisata' }}</small>
                                    <span class="text-success fw-bold">Rp
                                        {{ number_format($p->harga_akhir_per_pax, 0, ',', '.') }} /pax</span>
                                </div>
                                <a href="{{ route('client.paket.show', $p->uuid) }}"
                                    class="btn btn-sm btn-outline-primary btn-round ms-2">
                                    Detail
                                </a>
                            </div>
                        @empty
                            <p class="text-muted text-center py-2">Belum ada paket wisata.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

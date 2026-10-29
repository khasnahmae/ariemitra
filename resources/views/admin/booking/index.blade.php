@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <h3 class="fw-bold mb-4">Daftar Permintaan Pemesanan Client</h3>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card card-round">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>No Invoice</th>
                                <th>Pemesan</th>
                                <th>Tujuan / Paket</th>
                                <th>Tgl Keberangkatan</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th class="text-end" style="width: 280px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $b)
                                <tr>
                                    <td><strong class="text-primary">{{ $b->no_invoice }}</strong></td>
                                    <td>
                                        <strong>{{ $b->nama_pemesan }}</strong><br>
                                        <small class="text-muted">{{ $b->telepon_pemesan }}</small>
                                    </td>
                                    <td>{{ $b->tujuan_wisata }}</td>
                                    <td>{{ \Carbon\Carbon::parse($b->tanggal_keberangkatan)->format('d/m/Y') }}</td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($b->biaya_sewa, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($b->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($b->status == 'pending_verification')
                                            <span class="badge bg-info">Verifikasi Pembayaran</span>
                                        @elseif($b->status == 'booked')
                                            <span class="badge bg-success">Booked (DP Diterima)</span>
                                        @elseif($b->status == 'lunas')
                                            <span class="badge bg-primary">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">Batal</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">

                                            <!-- 1. Modal Verifikasi Pembayaran -->
                                            @if ($b->status == 'pending_verification')
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#modalVerifikasi{{ $b->id }}"
                                                    title="Verifikasi Pembayaran">
                                                    <i class="fa fa-check-circle me-1"></i> Verifikasi Bayar
                                                </button>

                                                <div class="modal fade" id="modalVerifikasi{{ $b->id }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form
                                                                action="{{ route('admin.booking.verifikasi-pembayaran', $b->uuid) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title fw-bold">Verifikasi Pembayaran
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    <p class="mb-1"><strong>Pemesan:</strong>
                                                                        {{ $b->nama_pemesan }}</p>
                                                                    <p class="mb-1"><strong>Metode:</strong> <span
                                                                            class="badge bg-secondary">{{ strtoupper($b->metode_pembayaran ?? 'TRANSFER') }}</span>
                                                                    </p>

                                                                    @php
                                                                        // Ambil nominal draft secara presisi dari pembayaran terbaru
                                                                        if ($b->dp_2 > 0) {
                                                                            $nominalDraft = $b->dp_2;
                                                                        } elseif ($b->dp_1 > 0) {
                                                                            $nominalDraft = $b->dp_1;
                                                                        } else {
                                                                            $nominalDraft = $b->biaya_sewa;
                                                                        }

                                                                        // Penentuan limit max input yang aman
                                                                        $maxVal = max(
                                                                            (float) $nominalDraft,
                                                                            (float) $b->biaya_sewa,
                                                                        );

                                                                        $fotoUrl =
                                                                            !empty($b->bukti_pembayaran) &&
                                                                            \Illuminate\Support\Str::startsWith(
                                                                                $b->bukti_pembayaran,
                                                                                ['http://', 'https://'],
                                                                            )
                                                                                ? $b->bukti_pembayaran
                                                                                : asset(
                                                                                    'storage/' . $b->bukti_pembayaran,
                                                                                );
                                                                    @endphp

                                                                    @if (!empty($b->bukti_pembayaran) && $b->metode_pembayaran == 'transfer')
                                                                        <div class="mb-3">
                                                                            <label class="form-label fw-bold">Bukti
                                                                                Transfer:</label><br>
                                                                            <a href="{{ $fotoUrl }}" target="_blank">
                                                                                <img src="{{ $fotoUrl }}"
                                                                                    class="img-fluid rounded border shadow-sm"
                                                                                    style="max-height: 200px;"
                                                                                    alt="Bukti Transfer">
                                                                            </a>
                                                                        </div>
                                                                    @endif

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Nominal Pembayaran
                                                                            Diterima (Rp)</label>
                                                                        <input type="number" name="jumlah_bayar"
                                                                            value="{{ round($nominalDraft) }}"
                                                                            class="form-control" min="1"
                                                                            max="{{ round($maxVal) }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-success"><i
                                                                            class="fa fa-check me-1"></i> Terima
                                                                        Pembayaran</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- 2. Cetak Invoice -->
                                            <a href="{{ route('admin.booking.cetak-invoice', $b->uuid) }}" target="_blank"
                                                class="btn btn-sm btn-outline-info" title="Cetak Perintah Bayar">
                                                <i class="fa fa-file-invoice-dollar me-1"></i> Invoice
                                            </a>

                                            <!-- 3. Terima Tunai Langsung di Kantor -->
                                            @if (in_array($b->status, ['pending', 'booked']))
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#modalTunai{{ $b->id }}"
                                                    title="Terima Tunai di Kantor">
                                                    <i class="fa fa-money-bill-wave"></i> Bayar Tunai
                                                </button>

                                                <div class="modal fade" id="modalTunai{{ $b->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <form
                                                            action="{{ route('admin.booking.verifikasi-pembayaran', $b->uuid) }}"
                                                            method="POST" class="modal-content">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title fw-bold">Terima Pembayaran Tunai
                                                                    Kantor</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body text-start">
                                                                <p class="mb-1"><strong>Pemesan:</strong>
                                                                    {{ $b->nama_pemesan }}</p>
                                                                <p class="mb-2"><strong>Sisa Tagihan:</strong> Rp
                                                                    {{ number_format($b->sisa_pembayaran, 0, ',', '.') }}
                                                                </p>
                                                                <hr>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Jumlah Pembayaran
                                                                        Tunai (Rp)</label>
                                                                    <input type="number" name="jumlah_bayar" min="100000"
                                                                        max="{{ max(100000, $b->sisa_pembayaran) }}"
                                                                        class="form-control"
                                                                        value="{{ round($b->sisa_pembayaran) }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-success"><i
                                                                        class="fa fa-check me-1"></i> Simpan
                                                                    Pembayaran</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- 4. Cetak Bukti Sewa -->
                                            @if (in_array($b->status, ['booked', 'lunas']))
                                                <a href="{{ route('admin.booking.cetak-bukti-sewa', $b->uuid) }}"
                                                    target="_blank" class="btn btn-secondary btn-sm"
                                                    title="Cetak Bukti Sewa">
                                                    <i class="fa fa-print me-1"></i> Bukti Sewa
                                                </a>
                                            @endif

                                        </div>
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

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
                                        <small class="text-muted"><i
                                                class="fa fa-phone me-1"></i>{{ $b->telepon_pemesan }}</small>
                                    </td>
                                    <td>{{ $b->tujuan_wisata }}</td>
                                    <td>{{ \Carbon\Carbon::parse($b->tanggal_keberangkatan)->format('d/m/Y') }}</td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($b->biaya_sewa, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($b->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($b->status == 'sent')
                                            <span class="badge bg-info">Tagihan Terkirim</span>
                                        @elseif($b->status == 'pending_verification')
                                            <span class="badge bg-primary">Perlu Verifikasi</span>
                                        @elseif($b->status == 'booked')
                                            <span class="badge bg-success">Booked (DP Received)</span>
                                        @elseif($b->status == 'lunas')
                                            <span class="badge bg-primary">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">Batal</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">

                                            <!-- 1. Tombol Verifikasi Pembayaran (Jika Client sudah konfirmasi bayar) -->
                                            @if ($b->status == 'pending_verification')
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#modalVerifikasi{{ $b->id }}"
                                                    title="Verifikasi Pembayaran">
                                                    <i class="fa fa-check-circle me-1"></i> Verifikasi Bayar
                                                </button>

                                                <!-- Modal Verifikasi Pembayaran -->
                                                <!-- Modal Verifikasi Pembayaran -->
                                                <div class="modal fade" id="modalVerifikasi{{ $b->id }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form
                                                                action="{{ route('admin.booking.konfirmasi-tunai', $b->uuid) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title fw-bold">Verifikasi Pembayaran
                                                                        Client</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    <p class="mb-1"><strong>Pemesan:</strong>
                                                                        {{ $b->nama_pemesan }}</p>
                                                                    <p class="mb-1">
                                                                        <strong>Metode:</strong>
                                                                        <span
                                                                            class="badge bg-secondary">{{ strtoupper($b->metode_pembayaran ?? 'TRANSFER') }}</span>
                                                                    </p>

                                                                    @php
                                                                        // Ambil nominal DP yang diunggah client atau gunakan sisa pembayaran jika DP bernilai 0
                                                                        $nominalDraft =
                                                                            $b->dp_1 > 0
                                                                                ? $b->dp_1
                                                                                : ($b->dp_2 > 0
                                                                                    ? $b->dp_2
                                                                                    : $b->biaya_sewa);

                                                                        $fotoUrl = '';
                                                                        if (!empty($b->bukti_pembayaran)) {
                                                                            $fotoUrl = \Illuminate\Support\Str::startsWith(
                                                                                $b->bukti_pembayaran,
                                                                                ['http://', 'https://'],
                                                                            )
                                                                                ? $b->bukti_pembayaran
                                                                                : asset(
                                                                                    'storage/' . $b->bukti_pembayaran,
                                                                                );
                                                                        }
                                                                    @endphp

                                                                    @if (!empty($b->bukti_pembayaran))
                                                                        <div class="mb-3">
                                                                            <label class="form-label fw-bold">Bukti Transfer
                                                                                / Bayar:</label><br>
                                                                            <a href="{{ $fotoUrl }}" target="_blank">
                                                                                <img src="{{ $fotoUrl }}"
                                                                                    class="img-fluid rounded border shadow-sm"
                                                                                    style="max-height: 220px; width: auto;"
                                                                                    alt="Bukti Transfer">
                                                                            </a>
                                                                        </div>
                                                                    @endif

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Nominal Pembayaran
                                                                            Diterima (Rp)</label>
                                                                        <input type="number" name="jumlah_bayar"
                                                                            value="{{ $nominalDraft }}"
                                                                            class="form-control" min="1"
                                                                            max="{{ $b->biaya_sewa }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-success">
                                                                        <i class="fa fa-check me-1"></i> Terima Pembayaran
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- 2. Tombol Cetak Invoice / Perintah Bayar Awal -->
                                            <a href="{{ route('admin.booking.cetak-invoice', $b->uuid) }}" target="_blank"
                                                class="btn btn-sm btn-outline-info" title="Cetak Perintah Bayar / Invoice">
                                                <i class="fa fa-file-invoice-dollar me-1"></i> Cetak Invoice
                                            </a>

                                            <!-- 3. Tombol Input Bayar Tunai Langsung di Kantor -->
                                            @if (in_array($b->status, ['pending', 'sent']))
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#modalTunai{{ $b->id }}"
                                                    title="Terima Bayar Tunai di Kantor">
                                                    <i class="fa fa-money-bill-wave"></i> Bayar Tunai
                                                </button>
                                            @endif

                                            <!-- 4. Tombol Cetak Bukti Sewa Resmi (Hanya setelah ada komitmen bayar DP/Lunas) -->
                                            @if (in_array($b->status, ['booked', 'lunas']))
                                                <a href="{{ route('admin.booking.cetak-bukti-sewa', $b->uuid) }}"
                                                    target="_blank" class="btn btn-secondary btn-sm"
                                                    title="Cetak Bukti Sewa Resmi">
                                                    <i class="fa fa-print me-1"></i> Bukti Sewa
                                                </a>
                                            @endif

                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Terima Pembayaran Tunai di Kantor -->
                                @if (in_array($b->status, ['pending', 'sent']))
                                    <div class="modal fade" id="modalTunai{{ $b->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="{{ route('admin.booking.konfirmasi-tunai', $b->uuid) }}"
                                                method="POST" class="modal-content">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Terima Pembayaran Tunai Kantor</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <p class="mb-1"><strong>Pemesan:</strong> {{ $b->nama_pemesan }}</p>
                                                    <p class="mb-2"><strong>Total Biaya:</strong> Rp
                                                        {{ number_format($b->biaya_sewa, 0, ',', '.') }}</p>
                                                    <hr>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Jumlah Pembayaran Tunai
                                                            (Rp)
                                                        </label>
                                                        <input type="number" name="jumlah_bayar" min="2000000"
                                                            max="{{ $b->biaya_sewa }}" class="form-control"
                                                            placeholder="2000000" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fa fa-check me-1"></i> Simpan Pembayaran
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

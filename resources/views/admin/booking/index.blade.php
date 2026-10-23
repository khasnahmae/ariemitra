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

                                            <!-- 1. Tombol Kirim Tagihan/Send to Client -->
                                            @if ($b->status == 'pending')
                                                <form action="{{ route('admin.booking.send-invoice', $b->uuid) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary"
                                                        title="Kirim Tagihan Perintah Bayar">
                                                        <i class="fa fa-paper-plane me-1"></i> Send to Client
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- 2. Tombol Cetak Invoice / Perintah Bayar -->
                                            <a href="{{ route('admin.booking.cetak-invoice', $b->uuid) }}" target="_blank"
                                                class="btn btn-sm btn-outline-info" title="Cetak Perintah Bayar / Invoice">
                                                <i class="fa fa-file-invoice-dollar me-1"></i> Perintah Bayar
                                            </a>

                                            <!-- 3. Tombol Input Bayar Tunai Kantor -->
                                            @if (in_array($b->status, ['pending', 'sent']))
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#modalTunai{{ $b->id }}"
                                                    title="Terima Bayar Tunai di Kantor">
                                                    <i class="fa fa-money-bill-wave"></i> Bayar
                                                </button>
                                            @endif

                                            <!-- 4. Tombol Cetak Bukti Sewa Resmi (Hanya setelah ada komitmen bayar DP/Lunas) -->
                                            @if (in_array($b->status, ['booked', 'lunas']))
                                                <a href="{{ route('admin.booking.cetak-invoice', ['uuid' => $b->uuid, 'type' => 'bukti_sewa']) }}"
                                                    target="_blank" class="btn btn-sm btn-secondary"
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
                                        <div class="modal-dialog">
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
                                                        <label class="form-label font-weight-bold">Jumlah Pembayaran Tunai
                                                            (DP Min. Rp 2.000.000)</label>
                                                        <input type="number" name="jumlah_bayar" min="2000000"
                                                            max="{{ $b->biaya_sewa }}" class="form-control"
                                                            placeholder="2000000" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success"><i
                                                            class="fa fa-check me-1"></i> Simpan & Terbitkan Bukti
                                                        Sewa</button>
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

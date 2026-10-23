@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h3 class="fw-bold mb-4">Riwayat Pemesanan Paket Wisata</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No Invoice</th>
                                <th>Paket Wisata</th>
                                <th>Tanggal Keberangkatan</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                                <tr>
                                    <td><strong>{{ $b->no_invoice }}</strong></td>
                                    <td>{{ $b->tujuan_wisata }}</td>
                                    <td>{{ \Carbon\Carbon::parse($b->tanggal_keberangkatan)->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($b->biaya_sewa, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($b->status == 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu Invoice Admin</span>
                                        @elseif($b->status == 'sent')
                                            <span class="badge bg-info">Invoice Diterbitkan</span>
                                        @elseif($b->status == 'booked')
                                            <span class="badge bg-success">Booked (DP Diterima)</span>
                                        @elseif($b->status == 'lunas')
                                            <span class="badge bg-primary">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">Batal</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($b->status == 'sent')
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                data-bs-target="#uploadModal{{ $b->id }}">
                                                Bayar / Upload DP
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal Upload Pembayaran -->
                                <div class="modal fade" id="uploadModal{{ $b->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('client.upload.pembayaran', $b->uuid) }}" method="POST"
                                            enctype="multipart/form-data" class="modal-content">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Upload DP Pembayaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Total Sewa:</strong> Rp
                                                    {{ number_format($b->biaya_sewa, 0, ',', '.') }}</p>
                                                <p class="text-danger">* Ketentuan DP Minimal Rp 2.000.000</p>
                                                <div class="mb-3">
                                                    <label class="form-label">Jumlah Pembayaran DP (Rp)</label>
                                                    <input type="number" name="jumlah_bayar" min="2000000"
                                                        max="{{ $b->biaya_sewa }}" class="form-control" required
                                                        placeholder="2000000">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Bukti Transfer</label>
                                                    <input type="file" name="bukti_pembayaran" class="form-control"
                                                        accept="image/*" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Kirim Bukti
                                                    Pembayaran</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada riwayat pemesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

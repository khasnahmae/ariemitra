@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <h3 class="fw-bold mb-4">Riwayat Pemesanan Paket Wisata</h3>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card card-round">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>NO INVOICE</th>
                                <th>PAKET WISATA</th>
                                <th>TANGGAL KEBERANGKATAN</th>
                                <th>TOTAL BIAYA</th>
                                <th>STATUS</th>
                                <th class="text-end">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                                <tr>
                                    <td><strong class="text-primary">{{ $b->no_invoice }}</strong></td>
                                    <td>{{ $b->tujuan_wisata }}</td>
                                    <td>{{ \Carbon\Carbon::parse($b->tanggal_keberangkatan)->format('d M Y') }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($b->biaya_sewa, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($b->status == 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu Invoice</span>
                                        @elseif($b->status == 'sent')
                                            <span class="badge bg-info">Invoice Diterbitkan</span>
                                        @elseif($b->status == 'pending_verification')
                                            <span class="badge bg-primary">Menunggu Verifikasi Admin</span>
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

                                            <!-- Tombol 1: Lihat Invoice / Perintah Bayar -->
                                            @if (in_array($b->status, ['pending', 'sent', 'pending_verification', 'booked', 'lunas']))
                                                <a href="{{ route('admin.booking.cetak-invoice', $b->uuid) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-info"
                                                    title="Lihat Dokumen Perintah Bayar">
                                                    <i class="fa fa-file-invoice-dollar me-1"></i> Perintah Bayar
                                                </a>
                                            @endif

                                            <!-- Tombol 2: Modal Konfirmasi Pembayaran DP / Tunai -->
                                            @if ($b->status == 'sent')
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#uploadModal{{ $b->id }}">
                                                    <i class="fa fa-money-bill-wave me-1"></i> Bayar / Upload DP
                                                </button>
                                            @endif

                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Konfirmasi Pembayaran DP / Tunai -->
                                <!-- Modal Konfirmasi Pembayaran DP / Tunai -->
                                <div class="modal fade" id="uploadModal{{ $b->id }}" tabindex="-1"
                                    aria-labelledby="uploadModalLabel{{ $b->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <!-- Tambahkan pembungkus modal-content di sini -->
                                        <div class="modal-content">
                                            <form action="{{ route('client.upload.pembayaran', $b->uuid) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold"
                                                        id="uploadModalLabel{{ $b->id }}">Konfirmasi Pembayaran DP
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <div class="alert alert-secondary py-2 mb-3">
                                                        <p class="mb-1"><strong>Total Sewa:</strong> Rp
                                                            {{ number_format($b->biaya_sewa, 0, ',', '.') }}</p>
                                                        <p class="mb-0 text-danger fw-bold">* DP Minimal: Rp 2.000.000</p>
                                                    </div>

                                                    <!-- Pilihan Metode Pembayaran -->
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Metode Pembayaran</label>
                                                        <select name="metode_pembayaran" id="metode{{ $b->id }}"
                                                            class="form-select"
                                                            onchange="toggleMetode({{ $b->id }})" required>
                                                            <option value="transfer">Transfer Bank</option>
                                                            <option value="tunai">Bayar Tunai di Kantor</option>
                                                        </select>
                                                    </div>

                                                    <!-- Informasi Rekening (Tampil Jika Transfer) -->
                                                    <div id="infoBank{{ $b->id }}"
                                                        class="p-3 bg-light rounded mb-3 border">
                                                        <h6 class="fw-bold mb-1 text-primary"><i
                                                                class="fa fa-university me-1"></i> Rekening Tujuan Transfer:
                                                        </h6>
                                                        <p class="mb-1 text-dark"><strong>Bank BCA:</strong> 1234-5678-90
                                                            (a.n. Arie Mitra Sejati)
                                                        </p>
                                                        <p class="mb-0 text-dark"><strong>Bank BRI:</strong> 0987-6543-21
                                                            (a.n. Arie Mitra Sejati)</p>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Nominal Pembayaran (Rp)</label>
                                                        <input type="number" name="jumlah_bayar" min="2000000"
                                                            max="{{ $b->biaya_sewa }}" class="form-control" required
                                                            placeholder="2000000">
                                                    </div>

                                                    <!-- Field Upload Bukti Pembayaran -->
                                                    <div class="mb-3" id="fieldBukti{{ $b->id }}">
                                                        <label class="form-label fw-bold">Unggah Bukti Transfer</label>
                                                        <input type="file" name="bukti_pembayaran"
                                                            id="inputBukti{{ $b->id }}" class="form-control"
                                                            accept="image/*" required>
                                                    </div>

                                                    <!-- Catatan Bayar di Tempat -->
                                                    <div id="infoTunai{{ $b->id }}"
                                                        class="alert alert-info py-2 d-none">
                                                        <i class="fa fa-info-circle me-1"></i> Silakan datang ke kantor kami
                                                        di <strong>Jl. Perintis Kemerdekaan No. 20 Taman Pemalang</strong>
                                                        untuk melakukan pembayaran secara tunai.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="fa fa-paper-plane me-1"></i> Kirim Konfirmasi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat pemesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleMetode(id) {
            const metode = document.getElementById('metode' + id).value;
            const infoBank = document.getElementById('infoBank' + id);
            const fieldBukti = document.getElementById('fieldBukti' + id);
            const inputBukti = document.getElementById('inputBukti' + id);
            const infoTunai = document.getElementById('infoTunai' + id);

            if (metode === 'tunai') {
                infoBank.classList.add('d-none');
                fieldBukti.classList.add('d-none');
                inputBukti.removeAttribute('required');
                infoTunai.classList.remove('d-none');
            } else {
                infoBank.classList.remove('d-none');
                fieldBukti.classList.remove('d-none');
                inputBukti.setAttribute('required', 'required');
                infoTunai.classList.add('d-none');
            }
        }
    </script>
@endsection

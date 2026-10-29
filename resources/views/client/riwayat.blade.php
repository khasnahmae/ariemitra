@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <h3 class="fw-bold mb-4">Riwayat Pemesanan Paket Wisata</h3>

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
                                <th>SISA TAGIHAN</th>
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
                                    <td class="fw-bold text-dark">Rp {{ number_format($b->biaya_sewa, 0, ',', '.') }}</td>
                                    <td class="fw-bold text-danger">Rp {{ number_format($b->sisa_pembayaran, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($b->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($b->status == 'pending_verification')
                                            <span class="badge bg-info">Menunggu Verifikasi Admin</span>
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

                                            <!-- 1. Invoice Perintah Bayar -->
                                            <a href="{{ route('admin.booking.cetak-invoice', $b->uuid) }}" target="_blank"
                                                class="btn btn-sm btn-outline-info" title="Lihat Dokumen Perintah Bayar">
                                                <i class="fa fa-file-invoice-dollar me-1"></i> Invoice
                                            </a>

                                            <!-- 2. Bukti Sewa Kuitansi (Jika Sudah DP/Lunas) -->
                                            @if (in_array($b->status, ['booked', 'lunas']))
                                                <a href="{{ route('admin.booking.cetak-bukti-sewa', $b->uuid) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-secondary"
                                                    title="Lihat Bukti Sewa Kuitansi">
                                                    <i class="fa fa-print me-1"></i> Bukti Sewa
                                                </a>
                                            @endif

                                            <!-- 3. Tombol Upload Pembayaran / Pelunasan -->
                                            @if (in_array($b->status, ['pending', 'booked']))
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#uploadModal{{ $b->id }}">
                                                    <i class="fa fa-money-bill-wave me-1"></i>
                                                    {{ $b->status == 'booked' ? 'Pelunasan / Bayar DP2' : 'Bayar / Upload DP' }}
                                                </button>
                                            @endif

                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Konfirmasi Pembayaran -->
                                <div class="modal fade" id="uploadModal{{ $b->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('client.upload.pembayaran', $b->uuid) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">
                                                        Konfirmasi Pembayaran
                                                        ({{ $b->status == 'booked' ? 'Pelunasan' : 'DP awal' }})
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <div class="alert alert-secondary py-2 mb-3">
                                                        <p class="mb-1"><strong>Total Biaya Sewa:</strong> Rp
                                                            {{ number_format($b->biaya_sewa, 0, ',', '.') }}</p>
                                                        <p class="mb-1 text-success"><strong>Sudah Dibayar:</strong> Rp
                                                            {{ number_format($b->dp_1 + $b->dp_2, 0, ',', '.') }}</p>
                                                        <p class="mb-0 text-danger fw-bold"><strong>Sisa Tagihan:</strong>
                                                            Rp {{ number_format($b->sisa_pembayaran, 0, ',', '.') }}</p>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Metode Pembayaran</label>
                                                        <select name="metode_pembayaran" id="metode{{ $b->id }}"
                                                            class="form-select"
                                                            onchange="toggleMetode({{ $b->id }})" required>
                                                            <option value="transfer">Transfer Bank</option>
                                                            <option value="tunai">Bayar Tunai di Kantor</option>
                                                        </select>
                                                    </div>

                                                    <div id="infoBank{{ $b->id }}"
                                                        class="p-3 bg-light rounded mb-3 border">
                                                        <h6 class="fw-bold mb-1 text-primary"><i
                                                                class="fa fa-university me-1"></i> Rekening Tujuan Transfer:
                                                        </h6>
                                                        <p class="mb-1 text-dark"><strong>Bank BCA:</strong> 1234-5678-90
                                                            (a.n. Arie Mitra Sejati)</p>
                                                        <p class="mb-0 text-dark"><strong>Bank BRI:</strong> 0987-6543-21
                                                            (a.n. Arie Mitra Sejati)</p>
                                                    </div>

                                                    @php
                                                        // Menghitung Sisa Tagihan yang Valid untuk Pelunasan
                                                        $sisaHarusBayar = $b->biaya_sewa - $b->dp_1;
                                                        if ($sisaHarusBayar <= 0) {
                                                            $sisaHarusBayar = $b->sisa_pembayaran;
                                                        }
                                                    @endphp

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Nominal Pembayaran (Rp)</label>
                                                        <input type="number" name="jumlah_bayar" min="100000"
                                                            max="{{ $b->sisa_pembayaran }}" class="form-control" required
                                                            value="{{ (int) ($b->status == 'booked' ? $sisaHarusBayar : $b->sisa_pembayaran) }}">
                                                    </div>

                                                    <div class="mb-3" id="fieldBukti{{ $b->id }}">
                                                        <label class="form-label fw-bold">Unggah Bukti Transfer</label>
                                                        <input type="file" name="bukti_pembayaran"
                                                            id="inputBukti{{ $b->id }}" class="form-control"
                                                            accept="image/*" required>
                                                    </div>

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
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat pemesanan.</td>
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

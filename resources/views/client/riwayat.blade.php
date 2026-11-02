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
                                            @php
                                                $tglBerangkat = \Carbon\Carbon::parse($b->tanggal_keberangkatan);
                                                $sisaHari = \Carbon\Carbon::today()->diffInDays($tglBerangkat, false);
                                            @endphp

                                            <!-- Warning Pelunasan H-7 s/d H-3 -->
                                            @if ($b->status == 'booked' && $sisaHari <= 7 && $sisaHari >= 0)
                                                <div class="alert alert-warning py-2 mb-2 small">
                                                    <i class="fa fa-exclamation-triangle me-1"></i>
                                                    <strong>Pengingat Pelunasan:</strong> Tanggal keberangkatan tinggal
                                                    {{ $sisaHari }} hari lagi. Harap lakukan pelunasan maksimal H-3.
                                                </div>
                                            @endif

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

                                            <!-- Tombol Batal (Hanya Tampil Jika Belum Ada DP Terverifikasi / Status 'pending' atau 'pending_verification') -->
                                            @if (in_array($b->status, ['pending', 'pending_verification']) && $b->dp_1 == 0)
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal" data-bs-target="#modalBatal{{ $b->id }}"
                                                    title="Batalkan Pemesanan">
                                                    <i class="fa fa-times-circle me-1"></i> Batal
                                                </button>

                                                <!-- Modal Konfirmasi Batal -->
                                                <div class="modal fade" id="modalBatal{{ $b->id }}" tabindex="-1"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form action="{{ route('client.booking.cancel', $b->uuid) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title fw-bold text-white"><i
                                                                            class="fa fa-exclamation-triangle me-2"></i>Konfirmasi
                                                                        Pembatalan</h5>
                                                                    <button type="button" class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    <p class="mb-2">Apakah Anda yakin ingin membatalkan
                                                                        pemesanan berikut?</p>
                                                                    <div class="p-3 bg-light rounded border mb-2">
                                                                        <p class="mb-1"><strong>No Invoice:</strong>
                                                                            {{ $b->no_invoice }}</p>
                                                                        <p class="mb-1"><strong>Tujuan Paket:</strong>
                                                                            {{ $b->tujuan_wisata }}</p>
                                                                        <p class="mb-0"><strong>Tgl
                                                                                Keberangkatan:</strong>
                                                                            {{ \Carbon\Carbon::parse($b->tanggal_keberangkatan)->format('d M Y') }}
                                                                        </p>
                                                                    </div>
                                                                    <small class="text-muted"><i
                                                                            class="fa fa-info-circle me-1"></i> Setelah
                                                                        dibatalkan, status invoice akan berubah menjadi
                                                                        <strong>Batal</strong>.</small>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Kembali</button>
                                                                    <button type="submit" class="btn btn-danger fw-bold"><i
                                                                            class="fa fa-check me-1"></i> Ya,
                                                                        Batalkan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Tombol Beri Ulasan / Testimoni (Hanya untuk Transaksi LUNAS) -->
                                            @if ($b->status == 'lunas')
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="modal" data-bs-target="#modalTesti{{ $b->id }}">
                                                    <i class="fa fa-star me-1"></i> Beri Testimoni
                                                </button>

                                                <!-- Modal Form Testimoni -->
                                                <div class="modal fade" id="modalTesti{{ $b->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <form action="{{ route('client.testimonial.store', $b->uuid) }}"
                                                            method="POST" class="modal-content text-start">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title fw-bold"><i
                                                                        class="fa fa-star text-warning me-2"></i>Ulasan
                                                                    Pengalaman Tour</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Beri Rating
                                                                        Penilaian</label>
                                                                    <select name="rating"
                                                                        class="form-select text-warning fw-bold" required>
                                                                        <option value="5">⭐⭐⭐⭐⭐ (5/5) Sangat Memuaskan
                                                                        </option>
                                                                        <option value="4">⭐⭐⭐⭐ (4/5) Memuaskan</option>
                                                                        <option value="3">⭐⭐⭐ (3/3) Cukup Baik</option>
                                                                        <option value="2">⭐⭐ (2/5) Kurang Memuaskan
                                                                        </option>
                                                                        <option value="1">⭐ (1/5) Buruk</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Profesi / Jabatan
                                                                        (Opsional)</label>
                                                                    <input type="text" name="jabatan_profesi"
                                                                        class="form-control"
                                                                        placeholder="Contoh: Ketua Panitia Tour / Kepala Sekolah">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Ulasan & Pesan
                                                                        Kesan</label>
                                                                    <textarea name="pesan_testimoni" class="form-control" rows="4"
                                                                        placeholder="Ceritakan pengalaman rombongan Anda selama menggunakan armada & paket wisata kami..." required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary fw-bold"><i
                                                                        class="fa fa-paper-plane me-1"></i> Kirim
                                                                    Ulasan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- 3. Tombol Upload Pembayaran / Pelunasan -->
                                            @if (in_array($b->status, ['pending', 'booked']))
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#uploadModal{{ $b->id }}">
                                                    <i class="fa fa-money-bill-wave me-1"></i>
                                                    {{ $b->status == 'booked' ? 'Pelunasan / Bayar DP2' : 'Bayar / Upload DP' }}
                                                </button>
                                            @endif
                                            <!-- Tombol Reschedule (Tampil jika transaksi sudah Booked/Lunas dan belum ada pengajuan pending) -->
                                            @if (in_array($b->status, ['booked', 'lunas']) && $b->status_reschedule != 'pending')
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalReschedule{{ $b->id }}">
                                                    <i class="fa fa-calendar-alt me-1"></i> Reschedule
                                                </button>
                                            @elseif($b->status_reschedule == 'pending')
                                                <span class="badge bg-warning text-dark"><i class="fa fa-clock me-1"></i>
                                                    Reschedule Menunggu Admin</span>
                                            @endif

                                            <!-- Modal Form Ajukan Reschedule Tanggal -->
                                            <div class="modal fade" id="modalReschedule{{ $b->id }}"
                                                tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <form action="{{ route('client.reschedule.store', $b->uuid) }}"
                                                        method="POST" class="modal-content">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold"><i
                                                                    class="fa fa-calendar-alt me-2 text-warning"></i>Ajukan
                                                                Ubah Jadwal (Reschedule)</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <div class="alert alert-info py-2 small mb-3">
                                                                <i class="fa fa-info-circle me-1"></i> Jadwal saat ini:
                                                                <strong>{{ \Carbon\Carbon::parse($b->tanggal_keberangkatan)->format('d M Y') }}</strong>.<br>
                                                                Perubahan tanggal dapat disetujui selama jadwal PO Bus pada
                                                                tanggal baru masih tersedia.
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Pilih Tanggal
                                                                    Keberangkatan Baru</label>
                                                                <input type="date" name="tanggal_reschedule"
                                                                    class="form-control"
                                                                    min="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-warning fw-bold"><i
                                                                    class="fa fa-paper-plane me-1"></i> Kirim
                                                                Pengajuan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Konfirmasi Pembayaran -->
                                <div class="modal fade" id="uploadModal{{ $b->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('client.upload.pembayaran', $b->uuid) }}"
                                                method="POST" enctype="multipart/form-data">
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
                                                                class="fa fa-university me-1"></i> Rekening Tujuan
                                                            Transfer:
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
                                                        <i class="fa fa-info-circle me-1"></i> Silakan datang ke kantor
                                                        kami
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
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat pemesanan.
                                    </td>
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

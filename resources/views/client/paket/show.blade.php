@extends('layouts.admin')

@section('content')
    <div class="page-inner">

        <div class="row">
            <!-- Informasi Paket -->
            <div class="col-md-7 mb-4">
                <div class="card card-round">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title text-white mb-0">{{ $paket->PaketWisata->nama_paket ?? 'Paket Wisata' }}
                            ({{ $paket->nama_sub_paket }})</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold text-success mb-3">Rp
                            {{ number_format($paket->harga_akhir_per_pax, 0, ',', '.') }} / Pax</h5>

                        <h6 class="fw-bold mb-2">Durasi Tour</h6>
                        <p class="text-muted">{{ $paket->jumlah_hari }} Hari @if ($paket->jumlah_malam > 0)
                                {{ $paket->jumlah_malam }} Malam
                            @endif
                        </p>

                        <h6 class="fw-bold mb-2">Minimal Peserta</h6>
                        <p class="text-muted">{{ $paket->jumlah_peserta }} Pax</p>

                        <h6 class="fw-bold mb-2">Menginap</h6>
                        <p class="text-muted">{{ ucfirst($paket->is_menginap) }}</p>

                        <hr>
                        <h6 class="fw-bold mb-2">Destinasi Wisata</h6>
                        <ul class="ps-3">
                            @foreach ($paket->proposalDestinasi as $pd)
                                <li>{{ $pd->destinasi->nama_destinasi ?? '-' }}</li>
                            @endforeach
                        </ul>

                        @if ($paket->fasilitas)
                            <hr>
                            <h6 class="fw-bold mb-2">Fasilitas</h6>
                            <p class="text-muted">{!! nl2br(e($paket->fasilitas)) !!}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Pemesanan Langsung -->
            <div class="col-md-5">
                <div class="card card-round border-primary">
                    <div class="card-header">
                        <h4 class="card-title fw-bold">Form Permintaan Booking</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('client.booking.store', $paket->uuid) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Nama Pemesan</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->nama }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Nomor WhatsApp / Telepon</label>
                                <input type="text" name="telepon_pemesan" class="form-control" required
                                    placeholder="08xxxxxxxxxx">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Keberangkatan (Min. H-7)</label>
                                <input type="date" id="tanggal_keberangkatan" name="tanggal_keberangkatan"
                                    class="form-control @error('tanggal_keberangkatan') is-invalid @enderror"
                                    min="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}" required>
                                @error('tanggal_keberangkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Alamat Penjemputan</label>
                                <textarea name="alamat_penjemputan" class="form-control" rows="3" required
                                    placeholder="Alamat lengkap lokasi penjemputan rombongan"></textarea>
                            </div>

                            <div class="alert alert-info py-2 small">
                                <i class="fa fa-info-circle me-1"></i> Setelah mengajukan booking, Admin akan memeriksa
                                jadwal dan menerbitkan invoice resmi.
                            </div>

                            <button type="submit" class="btn btn-success btn-round w-100 fw-bold mb-3">
                                <i class="fa fa-paper-plane me-1"></i> Ajukan Booking Sekarang
                            </button>
                            <a href="{{ route('client.paket.index') }}" class="btn btn-outline-secondary btn-round w-100">
                                <i class="fa fa-arrow-left me-1"></i> Kembali ke Daftar Paket
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const bookedDates = @json($bookedDates ?? []);
        const inputDate = document.getElementById('tanggal_keberangkatan');

        inputDate.addEventListener('input', function() {
            if (bookedDates.includes(this.value)) {
                alert(
                    'Tanggal ini sudah dipesan oleh rombongan lain untuk tujuan wisata ini. Silakan pilih tanggal lain.');
                this.value = '';
            }
        });
    </script>
@endsection

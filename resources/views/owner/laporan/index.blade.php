@extends('layouts.admin')

@section('content')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="page-inner py-3">
        <!-- Header Title & Filter -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
            <div>
                <h3 class="fw-bold mb-1">Laporan Kinerja Bisnis & Keuangan</h3>
                <p class="text-muted mb-0">Analisis statistik pemesanan, pendapatan real, dan outstanding piutang usaha.</p>
            </div>
            <div>
                <button onclick="window.print()" class="btn btn-outline-primary btn-round me-2">
                    <i class="fa fa-print me-1"></i> Cetak Laporan
                </button>
            </div>
        </div>

        <!-- Filter Form Bar -->
        <div class="card card-round shadow-sm border-0 mb-4">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Dari Tanggal Keberangkatan</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Filter Status Transaksi</label>
                        <select name="status" class="form-select">
                            <option value="">-- Semua Status --</option>
                            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked (DP)
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="fa fa-filter me-1"></i> Terapkan Filter
                        </button>
                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Row KPI Ringkasan Keuangan Eksekutif -->
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card card-round border-0 shadow-sm bg-primary text-white p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50 font-weight-bold">TOTAL OMSET KOTOR</small>
                            <h4 class="fw-bold mb-0 text-white mt-1">Rp {{ number_format($totalOmset, 0, ',', '.') }}</h4>
                        </div>
                        <i class="fa fa-chart-line fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card card-round border-0 shadow-sm bg-success text-white p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50 font-weight-bold">PENDAPATAN MASUK (REAL)</small>
                            <h4 class="fw-bold mb-0 text-white mt-1">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h4>
                        </div>
                        <i class="fa fa-wallet fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card card-round border-0 shadow-sm bg-warning text-dark p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-dark-50 font-weight-bold">PIUTANG (BELUM LUNAS)</small>
                            <h4 class="fw-bold mb-0 text-dark mt-1">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h4>
                        </div>
                        <i class="fa fa-hand-holding-usd fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card card-round border-0 shadow-sm bg-info text-white p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50 font-weight-bold">RASIO PELUNASAN</small>
                            <h4 class="fw-bold mb-0 text-white mt-1">{{ $rasioPelunasan }}%</h4>
                        </div>
                        <i class="fa fa-percentage fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Performa & Ringkasan Volume -->
        <div class="row my-3">
            <div class="col-md-8 mb-4">
                <div class="card card-round border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-3">
                        <h5 class="fw-bold card-title">Tren Pendapatan Bulanan ({{ date('Y') }})</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="omsetChart" style="max-height: 280px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card card-round border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-3">
                        <h5 class="fw-bold card-title">Volume & Status Pemesanan</h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted"><i class="fa fa-circle text-primary me-2"></i> Lunas</span>
                            <span class="fw-bold">{{ $totalLunas }} Transaksi</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted"><i class="fa fa-circle text-success me-2"></i> Booked (DP)</span>
                            <span class="fw-bold">{{ $totalBooked }} Transaksi</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted"><i class="fa fa-circle text-danger me-2"></i> Batal (Cancelled)</span>
                            <span class="fw-bold">{{ $totalBatal }} Transaksi</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 fw-bold fs-6">
                            <span>Total Keseluruhan</span>
                            <span class="text-primary">{{ $totalTransaksi }} Pemesanan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Widget Top Performing Tour Packages (Paket Terlaris & Omset Terbesar) -->
        <div class="card card-round border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-3 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold card-title mb-1">
                        <i class="fa fa-trophy text-warning me-2"></i>5 Paket Wisata Unggulan (Top Performing)
                    </h5>
                    <p class="text-muted small mb-0">Paket wisata dengan pemesanan terbanyak dan kontribusi omset tertinggi
                        pada periode ini.</p>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="table-light">
                                <th width="5%">PERINGKAT</th>
                                <th width="35%">NAMA PAKET WISATA</th>
                                <th width="20%">TOTAL PEMESANAN</th>
                                <th width="20%">TOTAL OMSET KOTOR</th>
                                <th width="20%">PENDAPATAN REAL MASUK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topPackages as $index => $top)
                                <tr>
                                    <td class="text-center">
                                        @if ($index == 0)
                                            <span class="badge bg-warning text-dark fs-6 rounded-circle p-2"
                                                title="Peringkat 1">🥇</span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary text-white fs-6 rounded-circle p-2"
                                                title="Peringkat 2">🥈</span>
                                        @elseif($index == 2)
                                            <span class="badge bg-danger text-white fs-6 rounded-circle p-2"
                                                title="Peringkat 3">🥉</span>
                                        @else
                                            <span class="fw-bold text-muted">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-dark fs-6">{{ $top->tujuan_wisata }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info px-3 py-2 fs-6">{{ $top->total_pemesanan }}
                                            Rombongan</span>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        Rp {{ number_format($top->total_omset, 0, ',', '.') }}
                                    </td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($top->total_pendapatan_real, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data transaksi yang
                                        terkonfirmasi pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Detail Tabel Transaksi Laporan -->
        <div class="card card-round border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="fw-bold card-title">Breakdown Rincian Pemesanan Paket</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr class="table-light">
                                <th>NO INVOICE</th>
                                <th>CLIENT / PEMESAN</th>
                                <th>TUJUAN PAKET</th>
                                <th>TGL BERANGKAT</th>
                                <th>TOTAL SEWA</th>
                                <th>TERBAYAR</th>
                                <th>SISA TAGIHAN</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                                <tr>
                                    <td><strong class="text-primary">{{ $b->no_invoice }}</strong></td>
                                    <td>
                                        <strong>{{ $b->nama_pemesan }}</strong><br>
                                        <small class="text-muted">{{ $b->telepon_pemesan }}</small>
                                    </td>
                                    <td>{{ $b->tujuan_wisata }}</td>
                                    <td>{{ \Carbon\Carbon::parse($b->tanggal_keberangkatan)->format('d/m/Y') }}</td>
                                    <td class="fw-bold">Rp {{ number_format($b->biaya_sewa, 0, ',', '.') }}</td>
                                    <td class="text-success fw-bold">Rp
                                        {{ number_format($b->dp_1 + $b->dp_2, 0, ',', '.') }}</td>
                                    <td class="text-danger fw-bold">Rp
                                        {{ number_format($b->sisa_pembayaran, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($b->status == 'lunas')
                                            <span class="badge bg-primary">Lunas</span>
                                        @elseif($b->status == 'booked')
                                            <span class="badge bg-success">Booked (DP)</span>
                                        @elseif($b->status == 'cancelled')
                                            <span class="badge bg-danger">Batal</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Tidak ada data transaksi pada
                                        periode yang dipilih.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart Tren Omset Bulanan
        const ctx = document.getElementById('omsetChart').getContext('2d');
        const omsetChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: [
                        @for ($i = 1; $i <= 12; $i++)
                            {{ $grafikOmset[$i] ?? 0 }},
                        @endfor
                    ],
                    borderColor: '#1a2035',
                    backgroundColor: 'rgba(26, 32, 53, 0.05)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection

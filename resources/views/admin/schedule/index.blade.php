@extends('layouts.admin')

@section('content')
    <!-- FullCalendar CSS & JS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <style>
        /* Custom Styling Menyerupai UI Dribbble */
        .fc {
            font-family: 'Public Sans', sans-serif;
        }

        .fc-header-toolbar {
            margin-bottom: 1.5rem !important;
        }

        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: #2a2f43;
        }

        .fc-button-primary {
            background-color: #f4f5f8 !important;
            border-color: #e4e6ef !important;
            color: #495057 !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            box-shadow: none !important;
        }

        .fc-button-active,
        .fc-button-primary:hover {
            background-color: #1a2035 !important;
            border-color: #1a2035 !important;
            color: #ffffff !important;
        }

        .fc-daygrid-day {
            background-color: #ffffff;
        }

        .fc-event {
            border-radius: 10px !important;
            padding: 5px 8px !important;
            font-size: 0.85rem !important;
            cursor: pointer;
            border: 1px solid !important;
            transition: transform 0.2s ease;
        }

        .fc-event:hover {
            transform: translateY(-2px);
        }

        .event-card-title {
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
        }

        .event-card-sub {
            font-size: 0.75rem;
            opacity: 0.85;
        }
    </style>

    <div class="page-inner py-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold mb-1">Jadwal Keberangkatan Tour</h3>
                <p class="text-muted mb-0">Menampilkan seluruh jadwal yang sudah dikonfirmasi DP atau Lunas.</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge px-3 py-2" style="background-color: #fff9e6; color: #b06000; border: 1px solid #fbbc04;">
                    <i class="fa fa-circle me-1"></i> Booked (DP Diterima)
                </span>
                <span class="badge px-3 py-2" style="background-color: #e6f4ea; color: #137333; border: 1px solid #34a853;">
                    <i class="fa fa-circle me-1"></i> Lunas
                </span>
            </div>
        </div>

        <div class="card card-round shadow-sm border-0">
            <div class="card-body p-4">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Jadwal -->
    <div class="modal fade" id="modalDetailEvent" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="detailInvoice">Detail Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="35%" class="text-muted fw-bold">Nama Pemesan</td>
                            <td width="5%">:</td>
                            <td id="detailPemesan" class="fw-bold text-dark"></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">No. Telepon / WA</td>
                            <td>:</td>
                            <td id="detailTelepon"></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Tujuan / Paket</td>
                            <td>:</td>
                            <td id="detailTujuan" class="fw-bold text-primary"></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Tanggal Keberangkatan</td>
                            <td>:</td>
                            <td id="detailTanggal" class="fw-bold"></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Total Biaya</td>
                            <td>:</td>
                            <td id="detailBiaya" class="text-success fw-bold"></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Sisa Tagihan</td>
                            <td>:</td>
                            <td id="detailSisa" class="text-danger fw-bold"></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Status Pembayaran</td>
                            <td>:</td>
                            <td id="detailStatus"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('admin.booking.index') }}" class="btn btn-primary">Buka Data Pemesanan</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'today prev,next dayGridMonth,timeGridWeek'
                },
                buttonText: {
                    today: 'Today',
                    month: 'Month',
                    week: 'Week'
                },
                events: "{{ route('admin.schedule.events') }}",
                eventContent: function(arg) {
                    let titleEl = document.createElement('div');
                    titleEl.className = 'event-card-title';
                    titleEl.innerText = arg.event.extendedProps.pemesan;

                    let subEl = document.createElement('div');
                    subEl.className = 'event-card-sub';
                    subEl.innerText = arg.event.extendedProps.tujuan;

                    let arrayOfDomNodes = [titleEl, subEl];
                    return {
                        domNodes: arrayOfDomNodes
                    };
                },
                eventClick: function(info) {
                    let props = info.event.extendedProps;

                    document.getElementById('detailInvoice').innerText = 'Jadwal - ' + props.invoice;
                    document.getElementById('detailPemesan').innerText = props.pemesan;
                    document.getElementById('detailTelepon').innerText = props.telepon;
                    document.getElementById('detailTujuan').innerText = props.tujuan;
                    document.getElementById('detailTanggal').innerText = info.event.start
                        .toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    document.getElementById('detailBiaya').innerText = props.biaya;
                    document.getElementById('detailSisa').innerText = props.sisa;

                    let statusBadge = props.status === 'LUNAS' ?
                        '<span class="badge bg-primary">LUNAS</span>' :
                        '<span class="badge bg-success">BOOKED (DP DITERIMA)</span>';
                    document.getElementById('detailStatus').innerHTML = statusBadge;

                    let modal = new bootstrap.Modal(document.getElementById('modalDetailEvent'));
                    modal.show();
                }
            });

            calendar.render();
        });
    </script>
@endsection

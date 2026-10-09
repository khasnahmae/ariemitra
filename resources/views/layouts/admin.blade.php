<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Arie Mitra Tour & Travel</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Fonts and icons -->
    <script src="{{ asset('backend/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('backend/assets/css/fonts.min.css') }}"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/kaiadmin.min.css') }}" />

</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="white">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="white">
                    <a href="#" class="logo">
                        <img src="{{ asset('images/logo.png') }}" alt="navbar brand" class="navbar-brand"
                            style="width: 72px; height: auto;" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-section">
                            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                            <h4 class="text-section">MENU UTAMA</h4>
                        </li>
                        <li class="nav-item {{ request()->is('admin/proposal*') ? 'active' : '' }}">
                            <a href="{{ route('admin.proposal.index') }}">
                                <i class="far fa-file-alt"></i>
                                <p>Proposal Tour</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ request()->is('admin/destinasi*') || request()->is('admin/armada*') || request()->is('admin/rute-tol*') ? 'active' : '' }}">
                            <a data-bs-toggle="collapse" href="#masterData">
                                <i class="fas fa-layer-group"></i>
                                <p>Data Master</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse {{ request()->is('admin/destinasi*') || request()->is('admin/armada*') || request()->is('admin/rute-tol*') ? 'show' : '' }}"
                                id="masterData">
                                <ul class="nav nav-collapse">
                                    <li class="{{ request()->is('admin/destinasi*') ? 'active' : '' }}">
                                        <a href="{{ route('admin.destinasi.index') }}"><span class="sub-item">Destinasi
                                                Wisata</span></a>
                                    </li>
                                    <li class="{{ request()->is('admin/armada*') ? 'active' : '' }}">
                                        <a href="{{ route('admin.armada.index') }}"><span class="sub-item">Armada
                                                Transportasi</span></a>
                                    </li>
                                    <li class="{{ request()->is('admin/rute-tol*') ? 'active' : '' }}">
                                        <a href="{{ route('admin.rute-tol.index') }}"><span class="sub-item">Tarif Rute
                                                Tol</span></a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item {{ request()->is('admin/paket-wisata*') ? 'active' : '' }}">
                            <a href="{{ route('admin.paket-wisata.index') }}">
                                <i class="fas fa-archive"></i>
                                <p>Paket Wisata Publik</p>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('admin/galeri*') ? 'active' : '' }}">
                            <a href="{{ route('admin.galeri.index') }}">
                                <i class="fas fa-camera"></i>
                                <p>Galeri Foto</p>
                            </a>
                        </li>

                        {{-- @if (Auth::user()->role == 'operator')
                            
                        @endif --}}
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->
        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="white">
                        <a href="#" class="logo">
                            <img src="{{ asset('images/logo.png') }}" alt="navbar brand" class="navbar-brand"
                                height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <div class="navbar-nav ms-auto me-2">
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        @if (Auth::user()->role == 'admin')
                                            <img src="{{ asset('images/profile.webp') }}" alt="Admin Avatar"
                                                class="avatar-img rounded-circle" />
                                        @else
                                            <img src="{{ asset('images/profile.webp') }}" alt="Default Avatar"
                                                class="avatar-img rounded-circle" />
                                        @endif
                                    </div>
                                    {{-- <span class="profile-username">
                                        <span class="fw-semibold" style="font-size: 18px;">
                                            {{ Auth::user()->nama }}
                                        </span>
                                    </span> --}}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="">Pengaturan</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#logoutModal">
                                    <i class="fa fa-sign-out-alt"></i> Logout
                                </button>
                            </li>
                        </div>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>
            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    {{-- <div class="card-head-row"> --}}
                                    @yield('content')
                                    {{-- </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">
                        2026, made with <i class="fa fa-heart heart text-danger"></i> by
                        <a href="#">Arie Mitra Tour & Travel</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--   Core JS Files   -->
    <script src="{{ asset('backend/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/bootstrap.min.js') }}"></script>
    <!-- jQuery Scrollbar -->
    <script src="{{ asset('backend/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <!-- Chart JS -->
    <script src="{{ asset('backend/assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <!-- jQuery Sparkline -->
    <script src="{{ asset('backend/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <!-- Chart Circle -->
    <script src="{{ asset('backend/assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <!-- Datatables -->
    <script src="{{ asset('backend/assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <!-- Bootstrap Notify -->
    <script src="{{ asset('backend/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <!-- jQuery Vector Maps -->
    <script src="{{ asset('backend/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugin/jsvectormap/world.js') }}"></script>
    <!-- Google Maps Plugin -->
    <script src="{{ asset('backend/assets/js/plugin/gmaps/gmaps.js') }}"></script>
    <!-- Sweet Alert -->
    <script src="{{ asset('backend/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <!-- Kaiadmin JS -->
    <script src="{{ asset('backend/assets/js/kaiadmin.min.js') }}"></script>
    <!-- Datatables -->
    <script src="{{ asset('backend/assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <!-- Kaiadmin JS -->
    <script src="{{ asset('backend/assets/js/kaiadmin.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#basic-datatables").DataTable({});

            $("#multi-filter-select").DataTable({
                pageLength: 5,
                initComplete: function() {
                    this.api()
                        .columns()
                        .every(function() {
                            var column = this;
                            var select = $(
                                    '<select class="form-select"><option value=""></option></select>'
                                )
                                .appendTo($(column.footer()).empty())
                                .on("change", function() {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column
                                        .search(val ? "^" + val + "$" : "", true, false)
                                        .draw();
                                });
                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function(d, j) {
                                    select.append(
                                        '<option value="' + d + '">' + d + "</option>"
                                    );
                                });
                        });
                },
            });
            // Add Row
            $("#add-row").DataTable({
                pageLength: 5,
            });
            var action =
                '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';
            $("#addRowButton").click(function() {
                $("#add-row")
                    .dataTable()
                    .fnAddData([
                        $("#addName").val(),
                        $("#addPosition").val(),
                        $("#addOffice").val(),
                        action,
                    ]);
                $("#addRowModal").modal("hide");
            });
        });
    </script>
    <script>
        const passwordInput = document.querySelector('#password');
        const togglePassword = document.querySelector('#togglePassword');
        // Fungsi untuk toggle tampilan password
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
        // Fungsi untuk menampilkan ikon saat input password diisi
        passwordInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                togglePassword.classList.remove('d-none');
            } else {
                togglePassword.classList.add('d-none');
            }
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
    <script>
        var SweetAlert2Demo = (function() {
            //== Demos
            var initDemos = function() {
                $(".delete-button").click(function(e) {
                    var form = $(this).closest("form"); // Mengambil form terdekat
                    e.preventDefault(); // Mencegah aksi form langsung dieksekusi
                    Swal.fire({
                        title: "Yakin ingin menghapus?",
                        text: "Data yang sudah terhapus tidak bisa kembali lagi!",
                        icon: "warning",
                        showCancelButton: true, // Menampilkan tombol Cancel
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Tidak, batal!',
                        buttonsStyling: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika pengguna mengklik tombol 'Yes, delete it!', submit form penghapusan
                            form.submit(); // Aksi submit akan berjalan hanya setelah konfirmasi
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            // Jika pengguna mengklik 'No, cancel!', tidak terjadi apapun
                            Swal.fire({
                                title: 'Dibatalkan',
                                text: 'Data Anda aman :)',
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                });
            };
            return {
                //== Init
                init: function() {
                    initDemos();
                },
            };
        })();
        //== Class Initialization
        jQuery(document).ready(function() {
            SweetAlert2Demo.init();
        });
    </script>
    <script>
        $(document).on('click', '.mark-as-read', function() {
            var notificationId = $(this).data('id');

            $.ajax({
                url: '/notifications/' + notificationId + '/read',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Sembunyikan notifikasi yang telah dibaca
                        location.reload(); // Bisa diganti dengan memperbarui DOM tanpa reload halaman
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>

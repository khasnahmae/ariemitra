
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('karyaModal');
    const karyaImage = document.getElementById('karyaImage');

    // Event listener untuk tombol "Lihat Karya"
    document.querySelectorAll('.lihat-karya-btn').forEach(button => {
        button.addEventListener('click', function() {
            const karyaURL = this.getAttribute('data-karya');
            karyaImage.setAttribute('src', karyaURL);
        });
    });

    // Reset gambar modal saat modal ditutup
    modal.addEventListener('hidden.bs.modal', function() {
        karyaImage.setAttribute('src', '');
    });
});

// Inisialisasi Scrollspy
document.addEventListener('DOMContentLoaded', () => {
    const scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#navbarNav',
        offset: 70, // Sesuaikan offset jika perlu
    });
});

// Change navbar color on scroll
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.remove('transparent');
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
        navbar.classList.add('transparent');
    }
});

$(document).on('click', '.pagination a', function(event) {
    event.preventDefault(); // Hindari refresh halaman
    let page = $(this).attr('href').split('page=')[1]; // Ambil nomor halaman
    fetchPage(page);
});

function fetchPage(page) {
    $.ajax({
        url: "?page=" + page,
        type: "GET",
        success: function(response) {
            $('#karyaMahasiswaTable').html($(response).find('#karyaMahasiswaTable')
                .html()); // Ganti konten tanpa reload
        }
    });
}

$(document).ready(function() {
    $('#siswaTable').DataTable({
        "paging": true,
        "searching": true,
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, ],
        "ordering": true,
        "info": true,
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data ditemukan",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "«",
                "last": "»",
                "next": "›",
                "previous": "‹"
            }
        },
        "dom": '<"top"lf>rt<"bottom"ip><"clear">', // Menata posisi elemen
    });

    // Mengganti class pagination agar lebih modern
    $('.dataTables_paginate').addClass('pagination-sm');
});



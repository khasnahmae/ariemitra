    $(document).ready(function () {
        $('#siswaTable').DataTable({
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });

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

        // Update CSS untuk memberi tahu elemen aktif
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            const navbarLinks = document.querySelectorAll('.navbar-nav .nav-link');

            // Mengecek posisi scroll dan menandai elemen aktif
            navbarLinks.forEach(link => {
                const section = document.querySelector(link.getAttribute('href'));
                const rect = section.getBoundingClientRect();

                // Jika bagian tersebut terlihat di layar, berikan kelas active
                if (rect.top <= 70 && rect.bottom >= 70) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            // Navbar berubah warna saat scroll
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
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

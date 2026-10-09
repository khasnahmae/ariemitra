<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri & Dokumentasi Perjalanan - Tour & Travel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between">

    <header class="sticky top-0 z-50 bg-slate-900/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('frontend.home') }}" class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-400 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    <i class="fa-solid fa-compass"></i>
                </div>
                <span class="font-bold text-xl text-white">Perjalanan<span class="text-blue-400">Tour</span></span>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('frontend.home') }}" class="text-slate-400 hover:text-white transition">Beranda</a>
                <a href="{{ route('frontend.profil') }}" class="text-slate-400 hover:text-white transition">Tentang
                    Kami</a>
                <a href="{{ route('frontend.paket.index') }}" class="text-slate-400 hover:text-white transition">Paket
                    Wisata</a>
                <a href="{{ route('frontend.galeri') }}"
                    class="text-blue-400 font-semibold border-b-2 border-blue-500 pb-1">Galeri</a>
                <a href="{{ route('frontend.kontak') }}" class="text-slate-400 hover:text-white transition">Kontak</a>
            </nav>

            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 text-slate-200 text-xs font-semibold border border-slate-700">
                <i class="fa-solid fa-lock text-blue-400"></i>
                <span>Portal Staf</span>
            </a>
        </div>
    </header>

    <section class="py-16 bg-slate-900/80 border-b border-slate-800 text-center">
        <div class="max-w-7xl mx-auto px-4">
            <span
                class="inline-block px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-semibold mb-3">
                Dokumentasi Kegiatan
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4">Momen Indah Perjalanan</h1>
            <p class="text-slate-400 text-sm md:text-base max-w-2xl mx-auto">
                Kumpulan potret kebahagiaan dan momen berkesan dari rombongan wisatawan yang telah mempercayakan
                perjalanannya bersama kami.
            </p>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-grow w-full">
        @if ($galeriList->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($galeriList as $foto)
                    <div
                        class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden group hover:border-slate-700 transition">
                        <div class="aspect-video bg-slate-800 overflow-hidden relative">
                            <img src="{{ asset('storage/' . $foto->file_gambar) }}" alt="{{ $foto->judul }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-bold text-white mb-1">{{ $foto->judul }}</h3>
                            @if ($foto->paketWisata)
                                <span class="text-xs text-blue-400"><i class="fa-solid fa-box-archive mr-1"></i>
                                    {{ $foto->paketWisata->nama_paket }}</span>
                            @else
                                <span class="text-xs text-slate-500"><i class="fa-regular fa-image mr-1"></i>
                                    Dokumentasi Umum</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $galeriList->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-slate-900/50 border border-slate-800 rounded-2xl">
                <i class="fa-regular fa-images text-slate-600 text-4xl mb-3"></i>
                <h3 class="text-slate-300 font-semibold">Belum Ada Foto Galeri</h3>
            </div>
        @endif
    </main>

    <footer class="bg-slate-900 border-t border-slate-800 text-slate-400 py-8 text-xs text-center">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Sistem Informasi Biro Perjalanan. Seluruh Hak Cipta Dilindungi.</p>
        </div>
    </footer>

</body>

</html>

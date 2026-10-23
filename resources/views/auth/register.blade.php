<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Client - Arie Mitra Tour & Travel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
        }

        .bg-custom-green {
            background-color: #1A2171;
        }

        .bg-custom-green:hover {
            background-color: #131850;
        }

        .bg-input-gray {
            background-color: #F3F4F6;
        }
    </style>
</head>

<body class="min-h-screen flex text-slate-800 antialiased p-3 md:p-5">

    <!-- Container Utama Split Screen -->
    <div class="w-full flex flex-col lg:flex-row rounded-[2.5rem] overflow-hidden bg-white">

        <!-- KOLOM KIRI: FORM REGISTER -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-8 sm:p-12 lg:p-16 max-w-xl mx-auto">

            <!-- Logo Header -->
            <div class="mb-6">
                <a href="{{ route('frontend.home') }}" class="inline-flex items-center gap-2.5 tracking-tight">
                    <div class="img"
                        style="background-image: url('{{ asset('frontend/images/logo.png') }}'); width: 96px; height: auto; background-size: contain; background-repeat: no-repeat;">
                    </div>
                </a>
            </div>

            <!-- Form Header Title -->
            <div class="mb-6">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-2">
                    Buat Akun Baru
                </h1>
                <p class="text-xs text-slate-400 font-medium mt-1">Daftar sekarang untuk memesan paket wisata impian
                    Anda</p>
            </div>

            <!-- Flash Alert Messages -->
            @if (session('success'))
                <div
                    class="mb-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-base shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Form Input Register -->
            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Nama Lengkap -->
                <div>
                    <div class="relative">
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                            autofocus placeholder="Nama Lengkap Anda"
                            class="w-full px-5 py-4 bg-input-gray border-none rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1A2171] transition">
                    </div>
                    @error('nama')
                        <p class="mt-1.5 ml-2 text-xs text-rose-500 font-medium flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input Email -->
                <div>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="Alamat Email"
                            class="w-full px-5 py-4 bg-input-gray border-none rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1A2171] transition">
                    </div>
                    @error('email')
                        <p class="mt-1.5 ml-2 text-xs text-rose-500 font-medium flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            placeholder="Kata Sandi (Min. 6 Karakter)"
                            class="w-full pl-5 pr-12 py-4 bg-input-gray border-none rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1A2171] transition">

                        <button type="button" onclick="togglePasswordVisibility('password', 'password-icon')"
                            class="absolute inset-y-0 right-0 flex items-center pr-5 text-slate-400 hover:text-slate-600">
                            <i id="password-icon" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 ml-2 text-xs text-rose-500 font-medium flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input Konfirmasi Password -->
                <div>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="Konfirmasi Kata Sandi"
                            class="w-full pl-5 pr-12 py-4 bg-input-gray border-none rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1A2171] transition">

                        <button type="button"
                            onclick="togglePasswordVisibility('password_confirmation', 'confirm-icon')"
                            class="absolute inset-y-0 right-0 flex items-center pr-5 text-slate-400 hover:text-slate-600">
                            <i id="confirm-icon" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-4 px-6 bg-custom-green text-white font-bold rounded-full text-sm shadow-md hover:shadow-lg transition duration-200 mt-2">
                    Daftar Sekarang
                </button>
            </form>

            <!-- Link ke Login -->
            <div class="mt-6 text-center text-xs font-semibold text-slate-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="text-[#1A2171] font-bold hover:underline ml-1">
                    Masuk di sini
                </a>
            </div>

            <!-- Footer Text -->
            <div class="mt-8 text-center text-xs font-semibold text-slate-500">
                &copy; {{ date('Y') }} Arie Mitra Tour & Travel. <a href="{{ route('frontend.home') }}"
                    class="text-slate-900 font-bold hover:underline ml-1">Kembali ke Beranda</a>
            </div>

        </div>

        <!-- KOLOM KANAN: LANSKAP FOTO & PIN LOKASI DESTINASI -->
        <div class="hidden lg:block lg:w-1/2 p-3">
            <div class="relative w-full h-full min-h-[600px] rounded-[2rem] overflow-hidden bg-cover bg-center shadow-inner"
                style="background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200&auto=format&fit=crop');">

                <!-- Dark Overlay Gradient Soft -->
                <div class="absolute inset-0 bg-black/10"></div>

                <!-- PIN 1: Top Left - Destinasi Favorit -->
                <div class="absolute top-[20%] left-[20%] flex flex-col items-center">
                    <div class="w-3.5 h-3.5 bg-white/80 rounded-full border-2 border-white/50 shadow-md mb-2"></div>
                    <div
                        class="bg-white/30 backdrop-blur-md border border-white/40 rounded-2xl p-3 px-4 shadow-lg text-white flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                            <i class="fa-solid fa-map-pin text-xs"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] text-white/80 font-medium leading-none">Kemudahan
                                Booking</span>
                            <span class="block text-xs font-bold mt-1">Pesan Paket Wisata Langsung</span>
                        </div>
                    </div>
                </div>

                <!-- PIN 2: Right - Layanan Bus Pariwisata -->
                <div class="absolute top-[35%] right-[12%] flex flex-col items-center">
                    <div
                        class="bg-white/30 backdrop-blur-md border border-white/40 rounded-2xl p-4 shadow-lg text-white max-w-[200px]">
                        <span class="block text-sm font-bold mb-1"><i class="fa-solid fa-bus mr-1.5"></i> Armada
                            Prima</span>
                        <span class="block text-[11px] text-white/80 font-normal leading-tight">Unit bus pariwisata
                            terawat & fasilitas lengkap</span>
                    </div>
                    <div class="w-3.5 h-3.5 bg-white/80 rounded-full border-2 border-white/50 shadow-md mt-6"></div>
                </div>

                <!-- PIN 3: Bottom Center - Arie Mitra Route -->
                <div class="absolute bottom-[20%] left-[55%] -translate-x-1/2 flex flex-col items-center">
                    <div
                        class="bg-white/80 backdrop-blur-md px-4 py-2 rounded-full shadow-lg text-slate-900 font-bold text-xs mb-2">
                        <i class="fa-solid fa-route text-[#1A2171] mr-1"></i> Transaksi Aman & Terpercaya
                    </div>
                    <div class="w-3.5 h-3.5 bg-white rounded-full border-2 border-white/50 shadow-md"></div>
                </div>

            </div>
        </div>

    </div>

    <!-- Script Toggle Password Visibility -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>

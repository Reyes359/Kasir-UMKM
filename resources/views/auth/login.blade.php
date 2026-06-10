<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 45%, #4c6ef5 100%);
        }
    </style>
</head>
<body class="min-h-screen text-slate-800">
    <div class="min-h-screen flex items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-5xl overflow-hidden rounded-[28px] border border-slate-200/70 bg-white shadow-2xl shadow-slate-950/20">
            <div class="grid lg:grid-cols-[1.05fr_0.95fr]">
                <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-brand-600 p-8 sm:p-10 lg:p-12 text-white">
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-sm font-medium backdrop-blur">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        Sistem Kasir Modern
                    </div>
                    <h1 class="mt-6 text-3xl font-bold leading-tight sm:text-4xl">
                        Kelola penjualan dan stok dengan lebih cepat.
                    </h1>
                    <p class="mt-4 max-w-md text-sm leading-7 text-slate-200 sm:text-base">
                        Masuk ke dashboard kasir untuk memantau produk, transaksi, dan laporan secara real-time.
                    </p>

                    <div class="mt-8 space-y-3 text-sm text-slate-100">
                        <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/10 px-4 py-3">
                            <span class="text-lg">📦</span>
                            <span>Kelola stok dan produk dengan mudah</span>
                        </div>
                        <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/10 px-4 py-3">
                            <span class="text-lg">💳</span>
                            <span>Catat transaksi cepat dan rapi</span>
                        </div>
                        <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/10 px-4 py-3">
                            <span class="text-lg">📊</span>
                            <span>Lihat laporan penjualan harian</span>
                        </div>
                    </div>
                </div>

                <div class="p-8 sm:p-10 lg:p-12">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-lg font-bold text-white shadow-lg shadow-brand-600/20">
                            K
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Masuk ke akun</h2>
                            <p class="text-sm text-slate-500">Silakan login untuk lanjut ke dashboard</p>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">
                            <p class="font-semibold">Login gagal</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100">
                        </div>
                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                            <input type="password" id="password" name="password" required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100">
                        </div>
                        <button type="submit"
                            class="flex w-full items-center justify-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">
                            Masuk ke Dashboard
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-slate-500">
                        Demo akun: <span class="font-semibold text-slate-700">admin@kasir.test</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

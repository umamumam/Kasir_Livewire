<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Kasir Professional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            /* Gambar interior toko retail/minimarket modern */
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)),
                              url('https://images.unsplash.com/photo-1534723452862-4c874018d66d?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen font-sans antialiased">

    <div class="w-full max-w-md px-4">
        <div class="glass shadow-2xl rounded-3xl px-10 pt-10 pb-10">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">KASIR KU</h1>
                <p class="text-gray-500 mt-2 font-medium">Sistem Manajemen Inventori & Penjualan</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block text-gray-800 text-sm font-bold mb-2 ml-1">Email</label>
                    <input id="email" type="email" name="email" required placeholder="admin@domain.com"
                        class="w-full px-4 py-3 rounded-2xl bg-white border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all duration-200 text-gray-700">
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-gray-800 text-sm font-bold mb-2 ml-1">Password</label>
                    <input id="password" type="password" name="password" required placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-2xl bg-white border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all duration-200 text-gray-700">
                </div>

                <div class="flex items-center justify-between mb-8 px-1">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600 font-medium">Ingat Saya</span>
                    </label>
                    <a href="#" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">Lupa Password?</a>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-200 transform transition-all active:scale-95 duration-150">
                    MASUK KE SISTEM
                </button>
            </form>

            <div class="mt-8 text-center border-t border-gray-100 pt-6">
                <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">© 2026 tokolm.my.id</p>
            </div>
        </div>
    </div>

</body>
</html>

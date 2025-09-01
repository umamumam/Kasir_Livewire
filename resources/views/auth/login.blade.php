<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('https://png.pngtree.com/background/20231030/original/pngtree-cartoon-style-3d-render-of-a-blue-store-picture-image_5781796.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen font-sans antialiased">

    <div class="w-full max-w-md">
        <div class="bg-white shadow-xl rounded-2xl px-8 pt-6 pb-8 mb-4 bg-opacity-90 backdrop-filter backdrop-blur-sm">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Welcome Back!</h1>
                <p class="text-sm text-gray-600 mt-2">Sign in to your account</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                    <input id="email" type="email" name="email" required autocomplete="username" class="shadow appearance-none border rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="shadow appearance-none border rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                </div>

                <div class="flex items-center justify-between mb-6">
                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded text-indigo-600 border-gray-300 shadow-sm focus:ring-indigo-500">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>

                    <!-- Forgot Password Link -->
                    <a href="#" class="underline text-sm text-indigo-600 hover:text-indigo-800">Forgot your password?</a>
                </div>

                <!-- Login Button -->
                <div class="flex items-center justify-center">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl focus:outline-none focus:shadow-outline transition-transform transform hover:scale-105">
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

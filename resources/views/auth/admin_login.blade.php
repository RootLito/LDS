<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login | L&D System</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 relative">
        <a href="/">
            <i class="fa-solid fa-arrow-left absolute top-8 left-8 text-red-500 z-999"></i>
        </a>
        <!-- Header -->
        <div class="text-center mb-6">
            <i class="fas fa-user-shield text-gray-800 text-6xl mb-2"></i>
            <h1 class="text-2xl font-bold text-gray-700">Admin Login</h1>
            <p class="text-gray-500 mt-1">Enter your credentials to continue</p>
        </div>

        <!-- Error Message -->
        @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:outline-none">
                @error('username')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:outline-none">
                @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-gray-800 text-white py-2 rounded-md font-semibold hover:bg-gray-600 transition">
                Login
            </button>
        </form>

        <div class="text-center mt-6 text-xs text-gray-500">
            © {{ date('Y') }} Learning & Development System
        </div>
    </div>

</body>

</html>
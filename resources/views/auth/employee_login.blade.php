<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employee Login | L&D System</title>
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
            <i class="fas fa-user-circle text-blue-800 text-6xl mb-2"></i>
            <h1 class="text-2xl font-bold text-gray-700">Employee Login</h1>
            <p class="text-gray-500 mt-1">Enter your credentials to continue</p>
        </div>

        <!-- Error Message -->
        @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('employee.login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                @error('username')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{ show: false }" class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>

                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" required
                        class="w-full px-4 py-2 pr-10 border border-gray-300  rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                        <i x-show="!show" class="fa-solid fa-eye"></i>
                        <i x-show="show" class="fa-solid fa-eye-slash"></i>
                    </button>
                </div>

                @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>


            <button type="submit"
                class="w-full bg-blue-800 text-white py-2 rounded-md font-semibold hover:bg-blue-900 transition">
                Login
            </button>
            <p class="text-sm text-center">Dont have an account? <a href="{{ route('register.form') }}"
                    class="text-indigo-600 hover:underline">Register</a></p>



        </form>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>
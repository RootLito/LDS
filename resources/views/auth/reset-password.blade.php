<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password | L&D System</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="shortcut icon" href="{{ asset('images/bfar.png') }}" type="image/x-icon">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 relative">
        {{-- <a href="{{ route('employee.login.form') }}">
            <i class="fa-solid fa-arrow-left absolute top-8 left-8 text-red-500 z-999"></i>
        </a> --}}
        <a href="javascript:history.back()">
            <i class="fa-solid fa-arrow-left absolute top-8 left-8 text-red-500 z-999"></i>
        </a>

        <div class="text-center mb-6">
            <i class="fas fa-key text-blue-800 text-6xl mb-2"></i>
            <h1 class="text-2xl font-bold text-gray-700">Reset Password</h1>
            <p class="text-gray-500 mt-1">Enter your details to update your password</p>
        </div>

        <form action="{{ route('employee.profile.password.update') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-800 focus:outline-none"
                    placeholder="Confirm your username">
                @error('username')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{ show: false }">
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" required
                        class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-800 focus:outline-none">

                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                        <i x-show="!show" class="fa-solid fa-eye text-sm"></i>
                        <i x-show="show" class="fa-solid fa-eye-slash text-sm"></i>
                    </button>
                </div>
            </div>

            <div x-data="{ show: false }">
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                        class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-800 focus:outline-none">

                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                        <i x-show="!show" class="fa-solid fa-eye text-sm"></i>
                        <i x-show="show" class="fa-solid fa-eye-slash text-sm"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-800 text-white py-2 rounded-md font-semibold hover:bg-blue-900 transition mt-6">
                Update Password
            </button>
        </form>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>

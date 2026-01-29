<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee | Learning & Development System</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


</head>

<body>
    <div class="w-full h-screen flex flex-col bg-gray-200">
        <div class="w-full bg-white border-b border-gray-200">
            <header class="flex max-w-[1100px] h-[60px] items-center justify-between mx-auto">
                <div class="flex">
                    <img src="{{ asset('images/bfar.png') }}" alt="bfar logo" width="50px" class="self-center">
                    <h2 class="font-black text-2xl text-center text-gray-600">LDS </h2>
                </div>

                <div class="flex items-center gap-1">
                    <!-- Home -->
                    <a href="{{ route('employee.dashboard') }}"
                        class="w-full text-sm font-semibold text-gray-700 flex items-center gap-4 px-6 py-2 rounded hover:bg-gray-100 transition {{ request()->routeIs('employee.dashboard') ? 'bg-gray-200' : '' }}">
                        <i class="fa-solid fa-house"></i>
                        Home
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('employee.profile') }}"
                        class="w-full text-sm font-semibold text-gray-700 flex items-center gap-4 px-6 py-2 rounded hover:bg-gray-100 transition {{ request()->routeIs('employee.profile') ? 'bg-gray-200' : '' }}">
                        <i class="fa-solid fa-user"></i>
                        Profile
                    </a>

                    <!-- Certificates -->
                    {{-- <a href="{{ route('employee.certificates') }}"
                        class="w-full text-sm font-semibold text-gray-700 flex items-center gap-4 px-6 py-2 rounded hover:bg-gray-100 transition {{ request()->routeIs('employee.certificates') ? 'bg-gray-200' : '' }}">
                        <i class="fa-solid fa-image"></i>
                        Certificates
                    </a> --}}


                    <!-- Logout -->
                    <form id="employee-logout-form" action="{{ route('employee.logout') }}" method="POST" class="flex">
                        @csrf
                        <button type="submit"
                            class="flex text-sm font-semibold items-center gap-2 px-4 py-2 rounded text-red-500 cursor-pointer hover:bg-gray-100 transition">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </button>
                    </form>
                </div>

            </header>
        </div>

        <main class="flex-1  w-[1100px] mx-auto p-6">
            @yield('content')
        </main>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
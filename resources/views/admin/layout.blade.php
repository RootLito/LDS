<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee | Learning & Development System</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    @livewireStyles

</head>

<body>
    <div class="w-full h-screen flex bg-gray-200">
        <div class="w-[260px] h-full bg-white flex flex-col border-r border-gray-200">
            <div class="flex flex-col mt-6">
                <img src="{{ asset('images/bfar.png') }}" alt="bfar logo" width="150px" class="self-center">
                <h2 class="font-black text-4xl text-center text-gray-600">L.D.S. </h2>
            </div>

            <div class="w-full px-4 mt-12 flex flex-col gap-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="w-full flex items-center gap-4 px-6 py-3 text-sm rounded hover:bg-gray-200 text-gray-600 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    <span class="font-semibold text-gray-700">Dashboard</span>
                </a>

                <a href="{{ route('admin.trainings') }}"
                    class="w-full flex items-center gap-4 px-6 py-3 text-sm rounded hover:bg-gray-200 text-gray-600 transition {{ request()->routeIs('admin.trainings*') ? 'bg-gray-200' : '' }}">
                    <i class="fa-solid fa-folder-open"></i>
                    <span class="font-semibold text-gray-700">Training</span>
                </a>

                <a href="{{ route('admin.certificates') }}"
                    class="w-full flex items-center gap-4 px-6 py-3 text-sm rounded hover:bg-gray-200 text-gray-600 transition {{ request()->routeIs('admin.certificates') ? 'bg-gray-200' : '' }}">
                    <i class="fa-solid fa-image"></i>

                    <span class="font-semibold text-gray-700">Cerificates</span>
                </a>

                <a href="{{ route('admin.employee') }}"
                    class="w-full flex items-center gap-4 px-6 py-3 text-sm rounded hover:bg-gray-200 text-gray-600 transition {{ request()->routeIs('admin.employee*') ? 'bg-gray-200' : '' }}">
                    <i class="fa-solid fa-user-group"></i>
                    <span class="font-semibold text-gray-700">Employees</span>
                </a>

            </div>

            <form id="employee-logout-form" action="{{ route('employee.logout') }}" method="POST"
                class="w-full flex p-4 mt-auto">
                @csrf
                <button type="submit"
                    class="w-full text-center px-4 py-2 text-sm font-semibold rounded bg-red-100 text-red-500 cursor-pointer hover:bg-red-200 transition">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                    Logout
                </button>
            </form>
        </div>
        <div class="flex-1">
            <div class="w-full h-[60px] bg-white flex items-center px-6 border-b border-gray-200">
                <h1 class="font-bold text-2xl text-gray-600">Learning & Development System</h1>

            </div>
            <div class="h-[calc(100vh-60px)] overflow-y-auto">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>
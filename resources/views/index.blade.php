<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Learning & Development System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="shortcut icon" href="{{ asset('images/bfar.png') }}" type="image/x-icon">
    @vite('resources/css/app.css')
</head>

<body>

    <div class="w-full h-screen flex items-center justify-center bg-gray-200">
        <div class="w-90 bg-white rounded-xl p-6 flex flex-col">
            <img src="{{ asset('images/bfar.png') }}" alt="bfar logo" width="200px" class="self-center">

            <h2 class="text-5xl font-black text-center text-slate-800">LDS</h2>
            <h2 class="font-black text-center text-gray-600 mt-2">Learning & Development System</h2>

            <div class="flex items-center my-6">
                <hr class="flex-grow border-gray-300">
                <p class="mx-4 text-gray-400 font-medium whitespace-nowrap">
                    Login as
                </p>
                <hr class="flex-grow border-gray-300">
            </div>


            <div class="w-full flex gap-2 ">
                <a href="{{ route('employee.login.form') }}"
                    class="flex-1 text-center bg-blue-800 text-white px-6 py-3 rounded-full font-semibold hover:bg-indigo-700 transition">
                    <i class="fa-solid fa-user me-1"></i> Employee
                </a>
                <a href="{{ route('admin.login.form') }}"
                    class="flex-1 text-center bg-gray-800 text-white px-6 py-3 rounded-full font-semibold hover:bg-gray-900 transition">
                    <i class="fa-solid fa-user-tie me-1"></i> Admin
                </a>
            </div>
            <div class="text-center mt-6 text-xs text-gray-500">
                © {{ date('Y') }} Learning & Development System
            </div>
        </div>
    </div>
</body>

</html>
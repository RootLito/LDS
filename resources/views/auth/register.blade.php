<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employee Registration | Learning & Development System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="w-[1000px] bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-blue-800">Employee Registration</h1>
            <p class="text-gray-500 mt-1">Create your employee account</p>
        </div>

        <form action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="fullname" value="{{ old('fullname') }}" required
                            class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
                        @error('fullname')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select name="gender"
                                class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
                                <option value="" selected disabled>Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                            <label class="block text-sm font-medium text-gray-700 mb-1">Employment Status</label>
                            <select name="status"
                                class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">

                                <option value="" disabled selected>Select Status</option>
                                <option value="permanent">Permanent</option>
                                <option value="jo">JO</option>
                                <option value="cos">COS</option>
                            </select>

                            @error('status')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                        </div>

                        <div class="space-y-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Birthdate</label>
                            <input type="date" name="birthdate" value="{{ old('birthdate') }}" required
                                class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
                            @error('birthdate')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                            <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Date</label>
                            <input type="date" name="appointment_date" value="{{ old('appointment_date') }}" required
                                class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
                            @error('appointment_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror


                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                            <input type="text" name="position" value="{{ old('position') }}" required
                                class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
                            @error('position')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Office</label>
                            <input type="text" name="office" value="{{ old('office') }}" required
                                class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
                            @error('office')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                            <input type="text" name="designation" value="{{ old('designation') }}" required
                                class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
                            @error('designation')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="mb-4" x-data="{ open: false, selectedSkills: [] }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Skills</label>

                        <div class="relative">
                            <div @click="open = !open"
                                class="w-full h-10 px-4 flex items-center border border-gray-300 rounded-md bg-white cursor-pointer focus-within:ring-2 focus-within:ring-indigo-500">
                                <span class="block truncate text-gray-700"
                                    x-text="selectedSkills.length ? selectedSkills.join(', ') : 'Select skills'">
                                </span>
                                <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <template x-for="skill in selectedSkills" :key="skill">
                                <input type="hidden" name="skills[]" :value="skill">
                            </template>

                            <div x-show="open" @click.away="open = false" x-transition x-cloak
                                class="absolute outline-none z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">

                                <div class="grid grid-cols-2 gap-2 p-4">
                                    @foreach ($skills as $skill)
                                        <label
                                            class="flex items-center space-x-2 text-sm font-medium text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                            <input type="checkbox" value="{{ $skill->name }}" x-model="selectedSkills"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span>{{ $skill->name }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="p-2 border-t border-gray-100 flex justify-end">
                                    <button @click="open = false" type="button"
                                        class="text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold p-2 px-4 rounded">
                                        Done
                                    </button>
                                </div>
                            </div>
                        </div>

                        @error('skills')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>




                    {{-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                        <input type="file" name="profile"
                            class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
                        @error('profile') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div> --}}

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                            class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
                        @error('username')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ show: false }" class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" required
                                class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                                <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ show: false }" class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                                class="w-full h-10 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                                <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <button type="submit" class="w-full bg-blue-800 text-white py-2 rounded-md hover:bg-blue-900 transition">
                Register
            </button>
        </form>



        <div class="text-center mt-6 text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('employee.login.form') }}" class="text-indigo-600 hover:underline">Login here</a>
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>

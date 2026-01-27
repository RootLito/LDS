@extends('employee.layout')

@section('content')

<div class="flex flex-col mb-6">
    <h1 class="text-2xl font-bold text-gray-600">Profile</h1>
    <p class="text-gray-500">Manage your account information.</p>
</div>

<div class="w-full bg-white rounded-lg p-8 flex gap-10">

    <div class="w-72 flex flex-col items-center">

        <div class="w-40 h-40 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
            <img src="{{ $employee->profile ? Storage::url($employee->profile) : asset('images/bfar.png') }}"
                alt="Profile Picture" class="w-full h-full object-cover" />
        </div>

        <form action="{{ route('employee.profile.update') }}" method="POST" enctype="multipart/form-data"
            class="w-full">
            @csrf

            <input type="file" name="profile"
                class="w-full h-10 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 mt-8 text-sm" />

            <button type="submit"
                class="w-full bg-blue-800 text-white h-10 rounded-md hover:bg-blue-900 transition mt-2 text-sm">
                Update Profile
            </button>
        </form>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="flex-1">
        <h2 class="text-gray-700 font-semibold mb-6 text-lg">Account Information</h2>

        <form action="{{ route('employee.profile.update') }}" method="POST" class="space-y-4 text-sm">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Full Name</label>
                <input type="text" name="fullname" value="{{ old('fullname', $employee->fullname) }}"
                    class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Gender</label>
                    <select name="gender"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="Male" @selected($employee->gender === 'Male')>Male</option>
                        <option value="Female" @selected($employee->gender === 'Female')>Female</option>
                    </select>
                </div>

                <div class="w-1/2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Birthdate</label>
                    <input type="date" name="birthdate" value="{{ old('birthdate', $employee->birthdate) }}"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Employment Status</label>
                    <select name="status"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="permanent" @selected($employee->status === 'permanent')>Permanent</option>
                        <option value="jo" @selected($employee->status === 'jo')>JO</option>
                        <option value="cos" @selected($employee->status === 'cos')>COS</option>
                    </select>
                </div>

                <div class="w-1/2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Appointment Date</label>
                    <input type="date" name="appointment_date"
                        value="{{ old('appointment_date', $employee->appointment_date) }}"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Position</label>
                    <input type="text" name="position" value="{{ old('position', $employee->position) }}"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Office</label>
                    <input type="text" name="office" value="{{ old('office', $employee->office) }}"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Designation</label>
                    <input type="text" name="designation" value="{{ old('designation', $employee->designation) }}"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Username</label>
                <input type="text" value="{{ $employee->username }}" readonly
                    class="w-full h-10 px-3 border border-gray-200 bg-gray-100 rounded cursor-not-allowed" />
            </div>

            <button type="submit"
                class="mt-4 w-full bg-blue-800 text-white h-10 rounded-md hover:bg-blue-900 transition">
                Save Changes
            </button>

            @if(session('success'))
            <div class="absolute top-0 right-0 bg-green-600 text-white px-5 py-3 rounded shadow-lg font-semibold z-50">
                {{ session('success') }}
            </div>
            @endif

        </form>
    </div>
</div>

@endsection
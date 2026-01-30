@extends('employee.layout')

@section('content')
    <div class="w-full h-full overflow-y-auto ">
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

                <form action="{{ route('employee.profile.updateProfile') }}" method="POST" enctype="multipart/form-data"
                    class="w-full">
                    @csrf

                    <input type="file" name="profile"
                        class="w-full h-10 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 mt-8 text-sm" />

                    <button type="submit"
                        class="w-full bg-blue-800 text-white h-10 rounded-md hover:bg-blue-900 transition mt-2 text-sm">
                        Update Profile
                    </button>
                </form>

                <div class="mt-6 w-full">
                    <h2 class="text-gray-700 font-semibold mb-2 text-lg">Skills</h2>
                    @if ($employee->skills && count($employee->skills) > 0)
                        <div class="flex flex-wrap  gap-2 mt-2">
                            @foreach ($employee->skills as $skill)
                                <span
                                    class="px-4 py-2 bg-gray-100 text-gray-600 text-xs font-bold  tracking-wide rounded">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic mt-2">No skills listed</p>
                    @endif
                </div>

            </div>

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
                            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Employment
                                Status</label>
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

                    <div class="mb-4" x-data="{
                        open: false,
                        selectedSkills: {{ json_encode($employee->skills ?? []) }}
                    }">
                        <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Skills</label>

                        <div class="relative">
                            <div @click="open = !open"
                                class="w-full h-10 px-3 flex items-center border border-gray-300 rounded bg-white cursor-pointer focus-within:ring-2 focus-within:ring-indigo-500">
                                <span class="block truncate text-gray-700 text-sm"
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
                                class="absolute outline-none z-50 mt-1 w-full bg-white border border-gray-300 rounded shadow-xl max-h-60 overflow-y-auto">

                                <div class="grid grid-cols-2 gap-2 p-4">
                                    @foreach ($skills as $skill)
                                        <label
                                            class="flex items-center justify-start space-x-2 text-sm font-medium text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                            <input type="checkbox" value="{{ $skill->name }}" x-model="selectedSkills"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span>{{ $skill->name }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="p-2 border-t border-gray-100 flex justify-between items-center bg-gray-50">
                                    <span class="text-xs text-gray-500 px-2"
                                        x-text="selectedSkills.length + ' selected'"></span>
                                    <button @click="open = false" type="button"
                                        class="text-xs bg-blue-800 hover:bg-blue-900 text-white font-bold py-1.5 px-4 rounded transition">
                                        Done
                                    </button>
                                </div>
                            </div>
                        </div>

                        @error('skills')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
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
                            <input type="text" name="designation"
                                value="{{ old('designation', $employee->designation) }}"
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

                </form>
            </div>
        </div>
    </div>
@endsection

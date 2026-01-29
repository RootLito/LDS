@extends('admin.layout')

@section('content')
    <div class="w-full h-full overflow-y-auto p-10">
        <h1 class="text-2xl font-bold text-gray-700">Employee Management</h1>
        <p class="text-gray-500 mb-10">Employee profiles and trainings</p>

        <form method="GET" class="bg-white p-4 py-6 rounded mb-6 flex flex-wrap gap-4">
            <input type="text" name="name" value="{{ request('name') }}" placeholder="Search name" class="border border-gray-300 px-3 py-2 rounded w-100">

            <select name="status" class="border border-gray-300 px-3 py-2 rounded w-40">
                <option value="">All Status</option>
                <option value="permanent" @selected(request('status') == 'permanent')>Permanent</option>
                <option value="jo" @selected(request('status') == 'jo')>JO</option>
                <option value="cos" @selected(request('status') == 'cos')>COS</option>
            </select>

            <input type="text" name="office" value="{{ request('office') }}" placeholder="Office" class="border border-gray-300 px-3 py-2 rounded w-48">
            <input type="text" name="position" value="{{ request('position') }}" placeholder="Position" class="border border-gray-300 px-3 py-2 rounded w-48">
            <input type="number" name="hours" value="{{ request('hours') }}" placeholder="Min Hours" class="border border-gray-300 px-3 py-2 rounded w-40">

            <button class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded cursor-pointer">
                <i class="fa-solid fa-sliders me-2"></i> Apply Filters
            </button>
        </form>

        <div x-data="{ openDetails: false, employee: null }" class="bg-white rounded overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-600">
                    <tr class="border-b border-gray-200">
                        <th class="p-4">Employee</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Office</th>
                        <th class="p-4">Position</th>
                        <th class="p-4">Trainings</th>
                        <th class="p-4" width="150">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($employees as $employee)
                        <tr class="{{ $loop->last ? '' : 'border-b border-gray-200' }} hover:bg-gray-50">
                            <td class="px-4 py-3 flex items-center gap-3">
                                <img src="{{ $employee->profile ? Storage::url($employee->profile) : asset('images/bfar.png') }}" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <p class="font-semibold text-gray-700">{{ $employee->fullname }}</p>
                                    <p class="text-xs text-gray-500">{{ $employee->designation }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $colors = [
                                        'permanent' => 'bg-green-100 text-green-600',
                                        'jo' => 'bg-blue-100 text-blue-600',
                                        'cos' => 'bg-yellow-100 text-yellow-600',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-bold rounded-full {{ $colors[$employee->status] ?? 'bg-gray-100' }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $employee->office }}</td>
                            <td class="px-4 py-3">{{ $employee->position }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $employee->trainingsAttended->count() ?: 'None' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <button @click="openDetails = true; employee = {{ $employee->load('trainingsAttended')->toJson() }}"
                                    class="h-8 px-2 bg-green-500 text-white rounded hover:bg-green-600 flex items-center justify-center">
                                    <i class="fa-solid fa-eye me-2"></i> View Profile
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div x-show="openDetails" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 overflow-auto px-4">
                <div @click.away="openDetails = false" class="bg-white rounded shadow-2xl w-full max-w-5xl p-8 relative">
                    <div class="text-center pb-4 mb-6">
                        <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                            <span x-text="employee?.fullname"></span>
                        </h2>
                        <p class="text-xl font-semibold text-gray-600 mt-2">
                            <span class="inline-block px-4 py-2 font-bold text-gray-600 bg-gray-200 rounded-full" x-text="employee?.position"></span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-sm">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 space-y-3">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600"><i class="fa-solid fa-user mr-2"></i> Gender</span>
                                <span x-text="employee?.gender"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600"><i class="fa-solid fa-birthday-cake mr-2"></i> Birthdate</span>
                                <span x-text="employee?.birthdate"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600"><i class="fa-solid fa-user-tag mr-2"></i> Status</span>
                                <span x-text="employee?.status"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600"><i class="fa-solid fa-building mr-2"></i> Office</span>
                                <span x-text="employee?.office"></span>
                            </div>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 space-y-3">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600"><i class="fa-solid fa-briefcase mr-2"></i> Designation</span>
                                <span x-text="employee?.designation"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600"><i class="fa-solid fa-calendar-day mr-2"></i> Appointment Date</span>
                                <span x-text="employee?.appointment_date"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600"><i class="fa-solid fa-certificate mr-2"></i> Trainings Attended</span>
                                <span x-text="employee?.trainings_attended?.length || 0"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-xl font-semibold text-gray-600 mb-3">Trainings Attended</h3>
                        <div x-show="!employee?.trainings_attended?.length" class="text-gray-500 py-6 text-center">No training attended yet.</div>
                        <div x-show="employee?.trainings_attended?.length" class="overflow-y-auto" style="max-height: 300px;">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr class="text-gray-600 text-left">
                                        <th class="px-4 py-2">Title</th>
                                        <th class="px-4 py-2">Date</th>
                                        <th class="px-4 py-2">Duration</th>
                                        <th class="px-4 py-2">Type</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <template x-for="training in employee?.trainings_attended" :key="training.id">
                                        <tr>
                                            <td class="px-4 py-2" x-text="training.title"></td>
                                            <td class="px-4 py-2" x-text="training.date"></td>
                                            <td class="px-4 py-2" x-text="training.duration"></td>
                                            <td class="px-4 py-2" x-text="training.type"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button @click="openDetails = false" class="absolute top-4 right-4 rounded-full p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $employees->withQueryString()->links() }}
        </div>
    </div>
@endsection
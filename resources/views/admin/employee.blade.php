@extends('admin.layout')

@section('content')
<div class="p-10">
    <h1 class="text-2xl font-bold text-gray-700">Employee Management</h1>
    <p class="text-gray-500 mb-10">System Summary</p>

    <form method="GET" class="bg-white p-4 py-6 rounded mb-6 flex flex-wrap gap-4">
        <input type="text" name="name" value="{{ request('name') }}" placeholder="Search name"
            class="border border-gray-300 px-3 py-2 rounded w-100">

        <select name="status" class="border border-gray-300 px-3 py-2 rounded w-40">
            <option value="">All Status</option>
            <option value="permanent" @selected(request('status')=='permanent' )>Permanent</option>
            <option value="jo" @selected(request('status')=='jo' )>JO</option>
            <option value="cos" @selected(request('status')=='cos' )>COS</option>
        </select>

        <input type="text" name="office" value="{{ request('office') }}" placeholder="Office"
            class="border border-gray-300 px-3 py-2 rounded w-48">

        <input type="text" name="position" value="{{ request('position') }}" placeholder="Position"
            class="border border-gray-300 px-3 py-2 rounded w-48">

        <input type="number" name="hours" value="{{ request('hours') }}" placeholder="Min Hours"
            class="border border-gray-300 px-3 py-2 rounded w-40">

        <button class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded cursor-pointer">
            <i class="fa-solid fa-sliders me-2"></i> Apply Filters
        </button>
    </form>

    <div class="bg-white rounded overflow-hidden">
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
                        <img src="{{ $employee->profile ? Storage::url($employee->profile) : asset('images/bfar.png') }}"
                            class="w-10 h-10 rounded-full object-cover">

                        <div>
                            <p class="font-semibold text-gray-700">
                                {{ $employee->fullname }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $employee->designation }}
                            </p>
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

                    <td class="px-4 py-3">
                        {{ $employee->office }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $employee->position }}
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ $employee->trainingsAttended->count() == 0 ? 'None' : $employee->trainingsAttended->count()
                        }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a href="{{ route('admin.employee.profile', $employee->id) }}"
                            class="h-8 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center justify-center">
                                       <i class="fa-solid fa-eye me-2"></i> View Profile
                        </a>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">
                        No employees found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $employees->withQueryString()->links() }}
    </div>
</div>
@endsection
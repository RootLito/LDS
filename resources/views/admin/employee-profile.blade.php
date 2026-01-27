@extends('admin.layout')

@section('content')

<div class="w-full h-full flex flex-col">
    <div class="px-10 mt-10">
        <h1 class="text-2xl font-bold text-gray-700">Employee Profile</h1>
        <p class="text-gray-500">View employee details and training attended</p>
    </div>

    <div class="flex-1 flex p-10 gap-10">
        <div class="w-100 bg-white rounded-lg p-6 flex flex-col items-center gap-4 justify-self-start">
            <img src="{{ $employee->profile ? Storage::url($employee->profile) : asset('images/bfar.png') }}"
                class="w-48 h-48 rounded-full object-cover border border-gray-300 mt-6">
            <h2 class="text-2xl font-bold text-gray-700 mt-6">{{ $employee->fullname }}</h2>
            <p class="font-semibold italic text-gray-500">{{ $employee->position }}</p>


            <a href="{{ route('admin.employee') }}" class="w-full py-2 rounded bg-red-100 hover:bg-red-200 text-center text-sm mt-auto text-red-500"><i class="fa-solid fa-arrow-left me-2"></i> Back</a>
        </div>
        <div class="flex-1 flex flex-col gap-10">
            <div class="bg-white rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-600 mb-6">Employee Details</h2>
                <div class="grid grid-cols-2 gap-2 text-gray-600 text-sm">
                    <p><span class="font-semibold inline-block w-48">Gender:</span> {{ ucfirst($employee->gender) }}</p>
                    <p><span class="font-semibold inline-block w-48">Birthdate:</span> {{ $employee->birthdate }}</p>
                    <p><span class="font-semibold inline-block w-48">Appointment Date:</span> {{
                        $employee->appointment_date }}</p>

                    @php
                    $employmentStatusLabels = [
                    'permanent' => 'Permanent',
                    'jo' => 'Job Order',
                    'cos' => 'Contract of Service',
                    ];
                    $statusLabel = $employmentStatusLabels[$employee->status] ?? ucfirst($employee->status);
                    @endphp
                    <p><span class="font-semibold inline-block w-48">Employment Status:</span> {{ $statusLabel }}</p>

                    <p><span class="font-semibold inline-block w-48">Office:</span> {{ $employee->office }}</p>
                    <p><span class="font-semibold inline-block w-48">Designation:</span> {{ $employee->designation }}
                    </p>
                </div>

            </div>
            <div class="flex-1 bg-white rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-600 mb-6">Trainings Attended</h2>

                @if($employee->trainingsAttended->isEmpty())
                <p class="text-gray-500 text-center py-6">No training attended yet.</p>
                @else
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 border rounded border-gray-200">
                        <tr class="text-gray-600 text-left">
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Inclusive Date of Attendance</th>
                            <th class="px-4 py-2">Number of Hours</th>
                            <th class="px-4 py-2">Type of L&D</th>
                            <th class="px-4 py-2">Conducted/Sponsored By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($employee->trainingsAttended as $training)
                        <tr>
                            <td class="px-4 py-2">{{ $training->title }}</td>
                            <td class="px-4 py-2">{{ $training->date }}</td>
                            <td class="px-4 py-2">{{ $training->duration }}</td>
                            <td class="px-4 py-2">{{ ucfirst($training->type) }}</td>
                            <td class="px-4 py-2">{{ $training->sponsored }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
@extends('admin.layout')

@section('content')
<div class="w-full h-full flex flex-col p-10">
    <div class="w-full flex justify-between items-center mb-10">
        <div class="flex flex-col">
            <h1 class="text-2xl font-bold text-gray-700">Training Details</h1>
            <p class="text-gray-500">Training summary and nominee details</p>
        </div>
    </div>

    <div class="w-[720px] flex flex-col bg-white mx-auto rounded p-10">
        <h2 class="text-center text-2xl font-semibold text-gray-600">{{ $training->title }}</h2>
        @php
        $badgeLabels = [
        'permanent' => 'Permanent',
        'jocos' => 'Jocos',
        'permanent_and_jocos' => 'Permanent and Jocos',
        ];

        switch($training->applicable_for) {
        case 'permanent':
        $badgeColor = 'bg-green-100 text-green-600';
        break;
        case 'jocos':
        $badgeColor = 'bg-blue-100 text-blue-600';
        break;
        case 'permanent_and_jocos':
        $badgeColor = 'bg-yellow-100 text-yellow-600';
        break;
        default:
        $badgeColor = 'bg-gray-100 text-gray-800';
        }

        $badgeLabel = $badgeLabels[$training->applicable_for] ?? ucfirst($training->applicable_for);
        @endphp
        <p class="text-center mt-2 mb-6">
            <span class="self-center mt-2 px-6 py-1 text-sm italic font-semibold rounded-full mb-2 {{ $badgeColor }}">
                {{ $badgeLabel }}
            </span>
        </p>

        <div class="p-6 space-y-2 bg-gray-50 rounded mt-6 text-sm">
            @php
            $labelWidth = 'w-60';
            @endphp

            <div class="flex justify-between">
                <span class="font-semibold text-gray-600 {{ $labelWidth }}">Status:</span>
                <span>{{ ucfirst($training->status) }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-semibold text-gray-600 {{ $labelWidth }}">Duration:</span>
                <span>{{ $training->duration }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-semibold text-gray-600 {{ $labelWidth }}">Conducted By:</span>
                <span>{{ $training->conducted_by }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-semibold text-gray-600 {{ $labelWidth }}">Charging of Funds:</span>
                <span>{{ $training->charging_of_funds }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-semibold text-gray-600 {{ $labelWidth }}">Endorsed By:</span>
                <span>{{ $training->endorsed_by }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-semibold text-gray-600 {{ $labelWidth }}">HRDC Resolution No.:</span>
                <span>{{ $training->hrdc_resolution_no }}</span>
            </div>
        </div>


        <h2 class="text-center text-2xl font-semibold text-gray-600 mt-10">
            Nominees ({{ $training->number_of_nominees }})
        </h2>

        <div class="flex-1 bg-white rounded-lg p-6 mt-4">
            @if($training->nominees->isEmpty())
            <p class="text-gray-500 text-center py-6">No nominees yet.</p>
            @else
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 border rounded border-gray-200">
                    <tr class="text-gray-600 text-left">
                        <th class="p-4">Name</th>
                        <th class="p-4">Position</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($training->nominees as $nominee)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-semibold text-gray-600">{{ $nominee->fullname }}</td>
                        <td class="px-4 py-2">{{ $nominee->position }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>





        <a href="{{ route('admin.trainings') }}"
            class="w-full py-2 rounded bg-red-100 hover:bg-red-300 text-center text-sm mt-20 text-red-500"><i
                class="fa-solid fa-arrow-left me-2"></i> Back</a>

    </div>
</div>
@endsection
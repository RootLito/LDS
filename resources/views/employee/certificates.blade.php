@extends('employee.layout')

@section('content')
<div class="w-[1100px] mx-auto p-6 h-full overflow-y-auto" x-data="{ isOpen: false, modalImage: '' }">
    <div class="flex flex-col mb-6">
        <h1 class="text-2xl font-bold text-gray-600">Certificates</h1>
        <p class="text-gray-500">Certificate gallery</p>
    </div>

    <div class="grid grid-cols-4 gap-4">
        @foreach ($trainings as $training)
        @php
        $imageUrl = $training->certificate_path
        ? asset('storage/' . $training->certificate_path)
        : 'https://via.placeholder.com/300x450?text=No+Image';

        $startDate = explode(' - ', $training->date)[0] ?? '';
        $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $startDate)->format('d M Y');
        @endphp

        <div class="relative group h-[380px] overflow-hidden rounded-2xl shadow-lg bg-gray-900">

            <img src="{{ $imageUrl }}" alt="{{ $training->title }}"
                class="w-full h-full object-cover opacity-80 transition duration-500 group-hover:scale-110 group-hover:opacity-100">

            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>

            <div class="absolute inset-0 p-4 flex flex-col justify-end text-white">
                <h3 class="text-sm font-bold mb-1 leading-tight">{{ $training->title }}</h3>

                <p class="text-[10px] text-gray-300 mb-3 line-clamp-2">
                    Certificate awarded for completing {{ $training->title }} training.
                </p>

                <div class="flex items-center gap-1 mb-4">
                    <span
                        class="px-2 py-0.5 bg-white/20 backdrop-blur-md text-[9px] font-semibold uppercase rounded-full border border-white/10">
                        {{ $formattedDate }}
                    </span>
                </div>

                <button @click="isOpen = true; modalImage = '{{ $imageUrl }}'"
                    class="w-full py-2.5 bg-white text-gray-900 font-bold rounded-xl text-xs hover:bg-gray-200 transition shadow-lg">
                    View Certificate
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <div x-show="isOpen" x-transition.opacity
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4"
        @keydown.escape.window="isOpen = false" x-cloak style="display: none;">

        <button @click="isOpen = false" class="absolute top-6 right-6 text-white hover:text-gray-300 transition">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="max-w-5xl w-full h-full flex items-center justify-center" @click.away="isOpen = false">
            <img :src="modalImage" class="max-w-full max-h-full object-contain rounded shadow-2xl">
        </div>
    </div>
</div>
@endsection
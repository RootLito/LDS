@extends('employee.layout')

@section('content')
<div class="w-full h-full flex flex-col" x-data="{ isOpen: false, modalImage: '', modalTitle: '', modalDate: '' }">
    <div class="flex flex-col mb-6">
        <h1 class="text-2xl font-bold text-gray-600">Certificates</h1>
        <p class="text-gray-500">Certificate gallery</p>
    </div>

    <div class="flex-1 bg-white rounded-lg p-6">
        <div class="min-h-full grid grid-cols-4 gap-6 ">
            @foreach($trainings as $training)
            @php
            $imageUrl = $training->certificate_path ? asset('storage/' . $training->certificate_path) :
            'https://via.placeholder.com/300x200?text=No+Image';
            $startDate = explode(' - ', $training->date)[0] ?? '';
            $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $startDate)->format('d M Y');
            @endphp

            <div class="cursor-pointer group" @click="
        isOpen = true; 
        modalImage = {{ json_encode($imageUrl) }}; 
        modalTitle = {{ json_encode($training->title) }}; 
        modalDate = {{ json_encode($formattedDate) }}
    ">
                <img src="{{ $imageUrl }}" alt="{{ $training->title }}"
                    class="w-full h-48 object-cover rounded-md border border-gray-200 group-hover:opacity-80 transition duration-300">
                <h3 class="mt-2 font-semibold text-gray-800 text-center">{{ $training->title }}</h3>
                <p class="text-sm text-gray-500 text-center">{{ $formattedDate }}</p>
            </div>

            @endforeach
        </div>
        <!-- Modal -->
        <div x-show="isOpen" x-transition.opacity
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
            @keydown.escape.window="isOpen = false" style="display: none;">
            <div class="bg-white rounded-lg overflow-hidden max-w-3xl max-h-full mx-4 relative"
                @click.away="isOpen = false">
                <button @click="isOpen = false"
                    class="absolute top-2 right-2 text-red-600 hover:text-gray-900 text-2xl font-bold bg-white w-8 h-8 rounded"
                    aria-label="Close modal">&times;</button>
                <img :src="modalImage" alt="" class="w-full max-h-[80vh] object-contain">
                <div class="p-4 text-center">
                    <h2 class="text-xl font-bold text-gray-600" x-text="modalTitle"></h2>
                    <p class="text-gray-500 mt-1" x-text="modalDate"></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
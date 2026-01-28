@extends('employee.layout')

@section('content')
<div class="flex flex-col gap-6">

    <div x-data="{ open: false }" class="flex flex-col gap-6">
        <div class="w-full flex justify-between items-center">
            <div class="flex flex-col">
                <h1 class="text-2xl font-bold text-gray-600">Home</h1>
                <p class="text-gray-500">Manage your personal training and development records.</p>
            </div>

            <button @click="open = true"
                class="bg-green-600 p-3 rounded text-sm text-white cursor-pointer hover:bg-green-700 transition">
                <i class="fa-solid fa-plus"></i>
                Add New Record
            </button>
        </div>



        <div x-show="open" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="open = false" class="bg-white rounded shadow-lg w-full max-w-md p-6 relative">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Add New Training</h2>

                <form action="{{ route('training.attended.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <textarea name="title" required
                            class="w-full h-[100px] resize-none px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none"></textarea>

                    </div>

                    <div>
                        <p>Inclusive dates of attendance</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">From</label>
                            <input type="date" name="start_date" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">To</label>
                            <input type="date" name="end_date" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>
                    </div>

                    {{-- <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Number of Hours</label>
                        <input type="text" name="duration" required
                            class="w-full px-4 py-2 border-gray-300 border border-gray-300-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
                    </div> --}}

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Type of L&D</label>
                        <input type="text" name="type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Conducted/Sponsored by(optional)</label>
                        <input type="text" name="sponsored"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Certificate
                        </label>
                        <input type="file" name="certificate_path" accept="image/jpeg,image/png,image/jpg"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <p class="text-gray-500 text-sm mt-1">Upload one image (JPG or PNG, max 2MB).</p>
                    </div>



                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" @click="open = false"
                            class="px-4 py-2 rounded border border-gray-300 bg-gray-100 hover:bg-gray-300 transition cursor-pointer text-sm">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 transition  text-sm">Save</button>
                    </div>
                </form>
                <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
        <div class="bg-white rounded p-4 py-6">
            <form method="GET" class="flex gap-4 items-center">

                <input type="text" name="title" value="{{ request('title') }}" placeholder="Filter by title"
                    class="border border-gray-300 px-3 py-2 rounded w-150">

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded text-sm flex items-center gap-2">
                    <i class="fa-solid fa-sliders"></i>
                    Apply Filters
                </button>

                {{-- @if(request()->hasAny(['title', 'date']))
                <a href="{{ route('employee.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">
                    Clear
                </a>
                @endif --}}

            </form>
        </div>

        <div class="bg-white rounded overflow-hidden">

            @if($trainings->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr class="border-b border-gray-200">
                            <th class="px-6 py-4">TITLE OF LEARNING AND DEVELOPMENT INTERVENTIONS/TRAINING PROGRAMS</th>
                            <th class="px-6 py-3">Inclusive Dates of Attendance</th>
                            <th class="px-6 py-3">Number of Hours</th>
                            <th class="px-6 py-3">Type of L&D</th>
                            <th class="px-6 py-3">Conducted/Sponsored By</th>
                            <th class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">

                        @foreach($trainings as $training)
                        <tr class="{{ $loop->last ? '' : 'border-b border-gray-200' }} hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-700">
                                {{ $training->title }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $training->date }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $training->duration }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $training->type }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $training->sponsored ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <div class="flex flex-col gap-2" x-data="{ openEdit: false, openDelete: false }">

                                    <!-- Edit / View Details Button -->
                                    <button @click="openEdit = true"
                                        class="h-8 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center justify-center gap-2 px-2">
                                        <i class="fa-solid fa-file-pen"></i> Update
                                    </button>

                                    <!-- Edit Modal -->
                                    <div x-show="openEdit" x-cloak
                                        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                        <div @click.away="openEdit = false"
                                            class="bg-white rounded shadow-lg w-full max-w-md p-6 relative">
                                            <h2 class="text-xl font-bold text-gray-700 mb-4">Edit Training</h2>

                                            <form action="{{ route('training.attended.update', $training->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                                    <textarea name="title" required
                                                        class="w-full h-[100px] resize-none px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">{{ $training->title }}</textarea>
                                                </div>

                                                @php
                                                $dates = explode(' - ', $training->date ?? '');
                                                $start_date = $dates[0] ?? '';
                                                $end_date = $dates[1] ?? '';

                                                $start_date_input = '';
                                                $end_date_input = '';

                                                if ($start_date) {
                                                $start_date_input = \Carbon\Carbon::createFromFormat('d/m/Y',
                                                $start_date)->format('Y-m-d');
                                                }

                                                if ($end_date) {
                                                $end_date_input = \Carbon\Carbon::createFromFormat('d/m/Y',
                                                $end_date)->format('Y-m-d');
                                                }
                                                @endphp

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700">From</label>
                                                        <input type="date" name="start_date" required
                                                            value="{{ $start_date_input }}"
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700">To</label>
                                                        <input type="date" name="end_date" required
                                                            value="{{ $end_date_input }}"
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
                                                    </div>
                                                </div>



                                                {{-- <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700">Number of
                                                        Hours</label>
                                                    <input type="text" name="duration" value="{{ $training->duration }}"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
                                                </div> --}}

                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700">Type of
                                                        L&D</label>
                                                    <input type="text" name="type" required
                                                        value="{{ $training->type }}"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
                                                </div>

                                                <div class="mb-4">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700">Conducted/Sponsored
                                                        By(optional)</label>
                                                    <input type="text" name="sponsored"
                                                        value="{{ $training->sponsored }}"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
                                                </div>
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700">
                                                        Certificate
                                                    </label>
                                                    <input type="file" name="certificate_path"
                                                        accept="image/jpeg,image/png,image/jpg"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                                    <p class="text-gray-500 text-sm mt-1">Upload one image (JPG or PNG,
                                                        max 2MB).</p>
                                                </div>


                                                <div class="flex justify-end gap-2 mt-4">
                                                    <button type="button" @click="openEdit = false"
                                                        class="px-4 py-2 rounded border border-gray-300 bg-gray-100 hover:bg-gray-300 transition cursor-pointer">Cancel</button>
                                                    <button type="submit"
                                                        class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 transition">Save</button>
                                                </div>
                                            </form>

                                            <!-- Close Icon -->
                                            <button @click="openEdit = false"
                                                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                                                <i class="fa-solid fa-xmark text-lg"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <button @click="openDelete = true"
                                        class="h-8 bg-red-400 text-white rounded hover:bg-red-500 flex items-center justify-center gap-2 px-2">
                                        <i class="fa-solid fa-trash-can"></i> Delete
                                    </button>

                                    <!-- Delete Confirmation Modal -->
                                    <div x-show="openDelete" x-cloak
                                        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                        <div @click.away="openDelete = false"
                                            class="bg-white rounded shadow-lg w-96 p-6 relative">
                                            <h2 class="text-lg font-bold text-gray-700 mb-4">Confirm Delete</h2>
                                            <p class="text-gray-500 mb-6">
                                                Are you sure you want to delete <strong>{{ $training->title }}</strong>?
                                                This action cannot be undone.
                                            </p>

                                            <div class="flex justify-end gap-3">
                                                <button @click="openDelete = false"
                                                    class="px-4 py-2 rounded border border-gray-300 bg-gray-100 hover:bg-gray-300 transition cursor-pointer">
                                                    Cancel
                                                </button>

                                                <form method="POST"
                                                    action="{{ route('training.attended.destroy', $training->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 transition cursor-pointer">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Close Button -->
                                            <button @click="openDelete = false"
                                                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </td>


                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            @else
            <div class="p-6 text-center text-gray-500">
                No training records found.
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
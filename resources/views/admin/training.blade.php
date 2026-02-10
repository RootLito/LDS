@extends('admin.layout')

@section('content')
    <div class="w-full h-full flex flex-col gap-6 overflow-y-auto">

        <div x-data="{ open: false }" class="flex flex-col">
            <div class="w-full flex justify-between items-center p-10">
                <div class="flex flex-col">
                    <h1 class="text-2xl font-bold text-gray-700">Training and Development</h1>
                    <p class="text-gray-500">System Summary</p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('export.trainings') }}"
                        class="bg-blue-500 p-3 rounded text-sm text-white cursor-pointer hover:bg-blue-600 transition inline-flex items-center">
                        <i class="fa-solid fa-download mr-2"></i> Export
                    </a>

                    <button @click="open = true"
                        class="bg-green-500 p-3 rounded text-sm text-white cursor-pointer hover:bg-green-600 transition">
                        <i class="fa-solid fa-plus"></i>
                        New Training
                    </button>
                </div>
            </div>




            <div x-show="open" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div @click.away="open = false" class="bg-white rounded shadow-lg w-[700px] p-6 relative">
                    <h2 class="text-xl font-bold text-gray-700 mb-4">Add New Training</h2>


                    <form action="{{ route('trainings.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <textarea name="title" required
                                class="w-full h-[100px] resize-none px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:outline-none"></textarea>

                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Applicable for</label>
                            <select name="applicable_for"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:outline-none">
                                <option value="">Select</option>
                                <option value="permanent">Permanent</option>
                                <option value="jocos">Jocos</option>
                                <option value="permanent_and_jocos">Permanent and Jocos</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Applicable Skill(s)</label>
                            <div x-data="{ open: false, selected: [] }" class="relative">

                                <div @click="open = !open"
                                    class="inline-flex items-center justify-between w-full px-4 p-2 bg-white text-gray-900 border border-gray-300 rounded cursor-pointer">
                                    <span class="block truncate"
                                        x-text="selected.length ? selected.join(', ') : 'Select Skills'"></span>
                                    <svg class="w-4 h-4 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <div x-show="open" @click.away="open = false" x-transition x-cloak
                                    class="absolute z-10 w-full bg-white border border-gray-300 shadow rounded mt-1 max-h-60 overflow-y-auto">
                                    <ul class="p-2 space-y-2">
                                        @foreach ($skills as $skill)
                                            <li>
                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                    <input type="checkbox" value="{{ $skill->name }}" x-model="selected"
                                                        class="w-4 h-4 text-blue-600 rounded border border-gray-300">
                                                    <span class="text-sm text-gray-900">{{ $skill->name }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <template x-for="skill in selected" :key="skill">
                                    <input type="hidden" name="applicable_skills[]" :value="skill">
                                </template>
                            </div>
                        </div>



                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:outline-none">
                                <option value="">Select status</option>
                                <option value="pending">Pending</option>
                                <option value="attended">Attended</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Duration</label>
                            <input type="text" name="duration" placeholder="e.g. 16 hours / 3 days" required
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Conducted by / Facilitator</label>
                            <input type="text" name="conducted_by" required
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                Charging of Funds
                            </label>
                            <input type="text" name="charging_of_funds"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                Endorse / Recommended by
                            </label>
                            <input type="text" name="endorsed_by"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                HRDC Resolution No.
                            </label>
                            <input type="text" name="hrdc_resolution_no"
                                class="w-full px-4 py-2 border  border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" @click="open = false"
                                class="px-4 text-sm py-2 rounded border border-gray-200 bg-gray-100 hover:bg-gray-200 transition">
                                Cancel
                            </button>

                            <button type="submit"
                                class="text-sm px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 transition">
                                Save Training
                            </button>
                        </div>
                    </form>


                    <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
            </div>

            <div class="px-10">
                <form method="GET" action="{{ route('admin.trainings') }}"
                    class="bg-white rounded px-4 py-6 mb-6 flex flex-wrap gap-4">

                    <input type="text" name="title" value="{{ request('title') }}" placeholder="Filter by title"
                        class="border border-gray-300 px-3 py-2 rounded w-100">

                    <select name="applicable_for" class="border border-gray-300 px-3 py-2 rounded w-48">
                        <option value="">Applicable for All</option>
                        <option value="permanent" @selected(request('applicable_for') == 'permanent')>Permanent</option>
                        <option value="jocos" @selected(request('applicable_for') == 'jocos')>Jocos</option>
                        <option value="permanent_and_jocos" @selected(request('applicable_for') == 'permanent_and_jocos')>
                            Permanent and Jocos
                        </option>
                    </select>

                    <select name="status" class="border border-gray-300 px-3 py-2 rounded w-48">
                        <option value="">All Status</option>
                        <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                        <option value="attended" @selected(request('status') == 'attended')>Attended</option>
                        <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
                    </select>

                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded cursor-pointer text-sm">
                        <i class="fa-solid fa-sliders me-2"></i> Apply Filters
                    </button>
                    @if (request()->anyFilled(['title', 'applicable_for', 'status']))
                        <a href="{{ route('admin.trainings') }}"
                            class="bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded text-sm flex items-center transition">
                            <i class="fa-solid fa-xmark me-2"></i> Clear
                        </a>
                    @endif


                </form>

                <div class="bg-white rounded overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-600">
                            <tr class="text-gray-600 text-left border-b border-gray-200">
                                <th class="p-4" width="30%">Title</th>
                                <th class="p-4">Applicable For</th>
                                <th class="p-4">Applicable Skill</th>
                                <th class="p-4">Duration</th>
                                <th class="p-4">Number of Nominees</th>
                                <th class="p-4">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse($trainings as $training)
                                <tr class="{{ $loop->last ? '' : 'border-b border-gray-200' }} hover:bg-gray-50">
                                    @php
                                        $badgeLabels = [
                                            'permanent' => 'Permanent',
                                            'jocos' => 'Jocos',
                                            'permanent_and_jocos' => 'Permanent and Jocos',
                                        ];

                                        switch ($training->applicable_for) {
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

                                        $badgeLabel =
                                            $badgeLabels[$training->applicable_for] ??
                                            ucfirst($training->applicable_for);
                                    @endphp

                                    <td class="p-4">
                                        <span class="font-bold text-gray-600">
                                            {{ $training->title }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <span
                                            class="inline-block px-2 py-1 text-xs font-semibold rounded-full mb-2 {{ $badgeColor }}">
                                            {{ $badgeLabel }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        @if ($training->applicable_skills && count($training->applicable_skills) > 0)
                                            @foreach ($training->applicable_skills as $skill)
                                                <span
                                                    class="inline-block px-2 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-700  mr-2 mb-2">
                                                    {{ $skill }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-gray-400 italic text-xs">Any Skill</span>
                                        @endif
                                    </td>

                                    <td class="p-4 font-semibold">{{ $training->duration }}</td>
                                    <td class="p-4">
                                        {!! $training->number_of_nominees > 0
                                            ? $training->number_of_nominees
                                            : '<span class="text-gray-400 text-sm">None</span>' !!}
                                    </td>
                                    <td class="p-4">
                                        <div x-data="{ openDetails: false, openDelete: false, openEdit: false }" class="flex flex-col gap-1">

                                            <!-- Details Button -->
                                            <button @click="openDetails = true"
                                                class="h-8 bg-green-500 text-white rounded hover:bg-green-600 flex items-center justify-center">
                                                <i class="fa-solid fa-file-lines me-2"></i> Details
                                            </button>

                                            <!-- Edit -->
                                            <button @click="openEdit = true"
                                                class="h-8 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center justify-center">
                                                <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                                            </button>

                                            <!-- Delete Button -->
                                            <button @click="openDelete = true"
                                                class="h-8 bg-red-400 text-white rounded hover:bg-red-500 flex items-center justify-center">
                                                <i class="fa-solid fa-trash-can me-2"></i> Delete
                                            </button>

                                            <!-- Delete Confirmation Modal -->
                                            <div x-show="openDelete" x-cloak
                                                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                                <div @click.away="openDelete = false"
                                                    class="bg-white rounded shadow-lg w-96 p-6 relative">
                                                    <h2 class="text-lg font-bold text-gray-700 mb-4">Confirm Delete</h2>
                                                    <p class="text-gray-500 mb-6">
                                                        Are you sure you want to delete
                                                        <strong>{{ $training->title }}</strong>?
                                                        This action cannot be undone.
                                                    </p>
                                                    <div class="flex justify-end gap-3">
                                                        <button @click="openDelete = false"
                                                            class="px-4 py-2 rounded border border-gray-300 bg-gray-100 hover:bg-gray-300 transition cursor-pointer">
                                                            Cancel
                                                        </button>
                                                        <form method="POST"
                                                            action="{{ route('admin.trainings.destroy', $training->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 transition cursor-pointer">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <button @click="openDelete = false"
                                                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Training Details Modal -->
                                            <div x-show="openDetails" x-cloak
                                                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 overflow-auto px-4">

                                                <div @click.away="openDetails = false"
                                                    class="bg-white rounded shadow-2xl w-full max-w-5xl p-8 relative">

                                                    <!-- Header -->
                                                    <div class="text-center pb-4 mb-6">
                                                        <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                                                            {{ $training->title }}
                                                        </h2>

                                                        <p class="mt-3">
                                                            <span
                                                                class="inline-flex items-center gap-2 px-5 py-1.5 text-xs font-semibold rounded-full {{ $badgeColor }}">
                                                                <i class="fa-solid fa-users"></i>
                                                                {{ $badgeLabel }}
                                                            </span>
                                                        </p>
                                                    </div>

                                                    <!-- Info Cards -->
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-sm">

                                                        <div
                                                            class="bg-gray-50 border border-gray-200 rounded-xl p-5 space-y-3">
                                                            <div class="flex justify-between">
                                                                <span
                                                                    class="font-semibold text-gray-600 flex items-center gap-2">
                                                                    <i class="fa-solid fa-circle-info"></i>Status
                                                                </span>
                                                                <span
                                                                    class="font-medium ">{{ ucfirst($training->status) }}</span>
                                                            </div>

                                                            <div class="flex justify-between">
                                                                <span
                                                                    class="font-semibold text-gray-600 flex items-center gap-2">
                                                                    <i class="fa-solid fa-clock"></i>Duration
                                                                </span>
                                                                <span>{{ $training->duration }}</span>
                                                            </div>

                                                            <div class="flex justify-between">
                                                                <span
                                                                    class="font-semibold text-gray-600 flex items-center gap-2">
                                                                    <i class="fa-solid fa-chalkboard-user"></i>Conducted By
                                                                </span>
                                                                <span>{{ $training->conducted_by }}</span>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="bg-gray-50 border border-gray-200 rounded-xl p-5 space-y-3">
                                                            <div class="flex justify-between">
                                                                <span
                                                                    class="font-semibold text-gray-600 flex items-center gap-2">
                                                                    <i class="fa-solid fa-wallet"></i>Charging of Funds
                                                                </span>
                                                                <span>{{ $training->charging_of_funds ?? '—' }}</span>
                                                            </div>

                                                            <div class="flex justify-between">
                                                                <span
                                                                    class="font-semibold text-gray-600 flex items-center gap-2">
                                                                    <i class="fa-solid fa-file-signature"></i>Endorsed By
                                                                </span>
                                                                <span>{{ $training->endorsed_by ?? '—' }}</span>
                                                            </div>

                                                            <div class="flex justify-between">
                                                                <span
                                                                    class="font-semibold text-gray-600 flex items-center gap-2">
                                                                    <i class="fa-solid fa-file-lines"></i>HRDC Resolution
                                                                    No.
                                                                </span>
                                                                <span>{{ $training->hrdc_resolution_no ?? '—' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Nominees -->
                                                    <div>
                                                        <h3
                                                            class="text-xl font-semibold text-gray-600 mb-3 flex items-center gap-2">
                                                            <i class="fa-solid fa-user-check text-gray-600"></i>
                                                            Nominees
                                                            <span
                                                                class="text-xl text-gray-500">({{ $training->number_of_nominees }})</span>
                                                        </h3>

                                                        <div
                                                            class="overflow-auto max-h-72 border border-gray-200 rounded-xl">
                                                            @if ($training->nominees->isEmpty())
                                                                <div class="text-gray-500 text-center py-10">
                                                                    <i class="fa-solid fa-user-slash text-3xl mb-2"></i>
                                                                    <p>No nominees have been assigned yet.</p>
                                                                </div>
                                                            @else
                                                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                                                    <thead class="bg-gray-100 sticky top-0">
                                                                        <tr
                                                                            class="text-gray-600 text-left border-b border-gray-200">
                                                                            <th class="px-4 py-2">Name</th>
                                                                            <th class="px-4 py-2">Position</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="divide-y divide-gray-200">
                                                                        @foreach ($training->nominees as $nominee)
                                                                            <tr class="hover:bg-gray-50 transition">
                                                                                <td
                                                                                    class="px-4 py-2 font-semibold text-gray-700">
                                                                                    {{ $nominee->fullname }}
                                                                                </td>
                                                                                <td class="px-4 py-2 text-gray-600">
                                                                                    {{ $nominee->position }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Close Button -->
                                                    <button @click="openDetails = false"
                                                        class="absolute top-4 right-4 rounded-full p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition">
                                                        <i class="fa-solid fa-xmark text-lg"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Edit Training Modal -->
                                            <div x-show="openEdit" x-cloak
                                                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 overflow-auto px-4">

                                                <div @click.away="openEdit = false"
                                                    class="bg-white rounded shadow-2xl w-full max-w-3xl p-6 relative">

                                                    <h2 class="text-xl font-bold text-gray-700 mb-4">
                                                        Update Training
                                                    </h2>

                                                    <form action="{{ route('admin.trainings.update', $training->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- Title -->
                                                        <div class="mb-4">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Title</label>
                                                            <textarea name="title" required
                                                                class="w-full h-[100px] resize-none px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ $training->title }}</textarea>
                                                        </div>

                                                        <!-- Applicable For -->
                                                        <div class="mb-4">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Applicable
                                                                for</label>
                                                            <select name="applicable_for"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                                <option value="">Select</option>
                                                                <option value="permanent" @selected($training->applicable_for == 'permanent')>
                                                                    Permanent</option>
                                                                <option value="jocos" @selected($training->applicable_for == 'jocos')>Jocos
                                                                </option>
                                                                <option value="permanent_and_jocos"
                                                                    @selected($training->applicable_for == 'permanent_and_jocos')>
                                                                    Permanent and Jocos
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-4">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Applicable
                                                                Skill(s)</label>

                                                            <div x-data="{
                                                                open: false,
                                                                selected: {{ json_encode($training->applicable_skill ?? []) }}
                                                            }" class="relative">

                                                                <div @click="open = !open"
                                                                    class="inline-flex items-center justify-between w-full px-4 p-2 bg-white text-gray-900 border border-gray-300 rounded cursor-pointer min-h-[42px]">

                                                                    <div class="flex flex-wrap gap-1">
                                                                        <template x-if="selected.length > 0">
                                                                            <template x-for="skill in selected"
                                                                                :key="skill">
                                                                                <span
                                                                                    class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded flex items-center">
                                                                                    <span x-text="skill"></span>
                                                                                </span>
                                                                            </template>
                                                                        </template>
                                                                        <template x-if="selected.length === 0">
                                                                            <span class="text-gray-500">Select
                                                                                Skills</span>
                                                                        </template>
                                                                    </div>

                                                                    <svg class="w-4 h-4 ml-2 transition-transform"
                                                                        :class="open ? 'rotate-180' : ''" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 9l-7 7-7-7" />
                                                                    </svg>
                                                                </div>

                                                                <div x-show="open" @click.away="open = false"
                                                                    x-transition x-cloak
                                                                    class="absolute z-10 w-full bg-white border border-gray-300 shadow rounded mt-1 max-h-60 overflow-y-auto">
                                                                    <ul class="p-2 space-y-1">
                                                                        @foreach ($skills as $skill)
                                                                            <li>
                                                                                <label
                                                                                    class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded cursor-pointer">
                                                                                    <input type="checkbox"
                                                                                        value="{{ $skill->name }}"
                                                                                        x-model="selected"
                                                                                        class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                                                                    <span
                                                                                        class="text-sm text-gray-900">{{ $skill->name }}</span>
                                                                                </label>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>

                                                                <template x-for="skill in selected" :key="skill">
                                                                    <input type="hidden" name="applicable_skill[]"
                                                                        :value="skill">
                                                                </template>

                                                                <template x-if="selected.length === 0">
                                                                    <input type="hidden" name="applicable_skill[]"
                                                                        value="">
                                                                </template>
                                                            </div>
                                                        </div>



                                                        <!-- Status -->
                                                        <div class="mb-4">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Status</label>
                                                            <select name="status" required
                                                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                                <option value="pending" @selected($training->status == 'pending')>
                                                                    Pending</option>
                                                                <option value="attended" @selected($training->status == 'attended')>
                                                                    Attended</option>
                                                                <option value="cancelled" @selected($training->status == 'cancelled')>
                                                                    Cancelled</option>
                                                            </select>
                                                        </div>

                                                        <!-- Duration -->
                                                        <div class="mb-4">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Duration</label>
                                                            <input type="text" name="duration"
                                                                value="{{ $training->duration }}" required
                                                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                        </div>

                                                        <!-- Conducted By -->
                                                        <div class="mb-4">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Conducted
                                                                by</label>
                                                            <input type="text" name="conducted_by"
                                                                value="{{ $training->conducted_by }}" required
                                                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                        </div>

                                                        <!-- Charging of Funds -->
                                                        <div class="mb-4">
                                                            <label class="block text-sm font-medium text-gray-700">Charging
                                                                of
                                                                Funds</label>
                                                            <input type="text" name="charging_of_funds"
                                                                value="{{ $training->charging_of_funds }}"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                        </div>

                                                        <!-- Endorsed By -->
                                                        <div class="mb-4">
                                                            <label class="block text-sm font-medium text-gray-700">Endorsed
                                                                By</label>
                                                            <input type="text" name="endorsed_by"
                                                                value="{{ $training->endorsed_by }}"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                        </div>

                                                        <!-- HRDC -->
                                                        <div class="mb-6">
                                                            <label class="block text-sm font-medium text-gray-700">HRDC
                                                                Resolution No.</label>
                                                            <input type="text" name="hrdc_resolution_no"
                                                                value="{{ $training->hrdc_resolution_no }}"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                        </div>

                                                        <!-- Actions -->
                                                        <div class="flex justify-end gap-3">
                                                            <button type="button" @click="openEdit = false"
                                                                class="px-4 py-2 rounded border border-gray-200 bg-gray-100 hover:bg-gray-200 transition">
                                                                Cancel
                                                            </button>

                                                            <button type="submit"
                                                                class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition">
                                                                Update Training
                                                            </button>
                                                        </div>
                                                    </form>

                                                    <!-- Close -->
                                                    <button @click="openEdit = false"
                                                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                                                        <i class="fa-solid fa-xmark text-lg"></i>
                                                    </button>
                                                </div>
                                            </div>


                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-3 py-6 text-center text-gray-500">
                                        <i class="fa-solid fa-folder-open"></i>
                                        No training records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            <div class="px-10 mb-10 mt-6">
                {{ $trainings->withQueryString()->links() }}
            </div>

        </div>
    </div>
@endsection

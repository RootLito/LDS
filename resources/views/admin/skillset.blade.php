@extends('admin.layout')

@section('content')
    <div class="w-full h-full flex flex-col p-10 overflow-y-auto">
        <h1 class="text-2xl font-bold text-gray-700">Skill</h1>
        <p class="text-gray-500 mb-10">Manage employee skills</p>

        <!-- Add Skill Section -->
        <div class="px-4 py-6 bg-white rounded">
            <form action="{{ route('skills.store') }}" method="POST">
                @csrf
                <div class="w-full flex gap-4">
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-100 px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:outline-none"
                        placeholder="Enter skill name">

                    <button type="submit" class="text-sm px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        <i class="fa-solid fa-plus"></i> Add Skill
                    </button>
                </div>

                @error('name')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </form>

        </div>

        <!-- Skill Table Section -->
        <div class="bg-white rounded mt-6">
            <table class="w-full text-sm text-left rounded overflow-hidden">
                <thead class="bg-gray-100 text-gray-600">
                    <tr class="border-b border-gray-200">
                        <th class="p-4" width="50%">Skill</th>
                        <th class="p-4" width="35%">Date Added</th>
                        <th class="p-4" width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($skills as $skill)
                        <tr class="{{ $loop->last ? '' : 'border-b border-gray-200' }} hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $skill->name }}</td>
                            <td class="px-4 py-3">{{ $skill->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <div x-data="{ openEdit: false, openDelete: false }" class="flex gap-2">
                                    <!-- Edit Button -->
                                    <button @click="openEdit = true"
                                        class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                                        <i class="fa-solid fa-file-pen"></i> Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <button @click="openDelete = true"
                                        class="bg-red-500 text-white p-2 rounded hover:bg-red-600">
                                        <i class="fa-solid fa-trash-can"></i> Delete
                                    </button>

                                    <!-- Edit Skill Modal -->
                                    <div x-show="openEdit" x-cloak @click.away="openEdit = false"
                                        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                        <div @click.away="openEdit = false" class="bg-white rounded shadow-lg p-6 w-96">
                                            <h2 class="text-xl font-bold text-gray-700 mb-6">Edit Skill</h2>
                                            <form action="{{ route('skills.update', $skill->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-4">

                                                    <input type="text" name="name" value="{{ $skill->name }}"
                                                        required class="w-full px-4 py-2 border border-gray-200 rounded  ">
                                                </div>

                                                <div class="flex justify-end gap-3">
                                                    <button type="button" @click="openEdit = false"
                                                        class="px-4 py-2 bg-gray-100 border border-gray-200 text-gray-600 rounded hover:bg-gray-200">
                                                        Cancel
                                                    </button>
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                        Update Skill
                                                    </button>
                                                </div>
                                            </form>
                                            <button @click="openEdit = false"
                                                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Delete Confirmation Modal -->
                                    <div x-show="openDelete" x-cloak @click.away="openDelete = false"
                                        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                        <div @click.away="openDelete = false" class="bg-white rounded shadow-lg p-6 w-96">
                                            <h2 class="text-lg font-bold text-gray-700 mb-4">Confirm Delete</h2>
                                            <p class="text-gray-500 mb-6">
                                                Are you sure you want to delete <strong>{{ $skill->name }}</strong>? This
                                                action cannot be undone.
                                            </p>
                                            <div class="flex justify-end gap-3">
                                                <button @click="openDelete = false"
                                                    class="px-4 py-2 bg-gray-100 text-gray-600 rounded hover:bg-gray-200">
                                                    Cancel
                                                </button>
                                                <form method="POST" action="{{ route('skills.destroy', $skill->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
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
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="mt-4">
            {{ $skills->links() }}
        </div>
    </div>
@endsection

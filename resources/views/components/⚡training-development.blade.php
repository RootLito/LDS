<?php

use App\Models\TrainingAttended;
use App\Models\Training;
use App\Models\Employee;
use Livewire\Component;

new class extends Component {
    public $trainings = [];
    public $title = '';
    public $applicable_for = '';
    public $status = '';
      public $confirmingDeleteId = null; 


    public function mount()
    {
        $this->loadTrainings();
    }

    public function updated($property)
    {
        $this->loadTrainings();
    }

    public function loadTrainings()
    {
        $this->trainings = Training::query()
            ->when($this->title, function ($query) {
                $query->where('title', 'like', '%' . $this->title . '%');
            })
            ->when($this->applicable_for, function ($query) {
                $query->where('applicable_for', $this->applicable_for);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->get();

        foreach ($this->trainings as $training) {

            $nominees = Employee::where(function ($query) use ($training) {

                $query->whereHas('trainingsAttended', function ($q) use ($training) {
                    $q->where('title', '!=', $training->title);
                })
                ->orWhereDoesntHave('trainingsAttended');

            })
            ->whereDoesntHave('trainingsAttended', function ($query) use ($training) {
                $query->where('title', $training->title);
            })
            ->get();

            $training->name_of_nominees = $nominees->pluck('fullname')->toArray();
            $training->number_of_nominees = $training->name_of_nominees ? count($training->name_of_nominees) : 0;
        }
    }


     public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id; // open modal
    }
    public function delete()
    {
        $training = Training::find($this->confirmingDeleteId);
        if ($training) {
            $training->delete();
            session()->flash('success', 'Training deleted successfully!');
        }
        $this->confirmingDeleteId = null; // close modal
        $this->trainings = Training::all(); // refresh table
    }

};
?>


<div class="overflow-x-auto px-10 flex-1 flex flex-col mb-10">
    @if(session()->has('success'))
    <div class="mb-4 p-2 bg-green-500 text-white rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg px-4 py-6 mb-10">
        <div class="flex flex-wrap gap-4">
            <input type="text" wire:model.live.debounce.300ms="title" placeholder="Filter by title"
                class="border border-gray-300 px-3 py-2 rounded w-100 focus:outline-none focus:ring focus:ring-blue-200" />
            <select wire:model.live.debounce.300ms="applicable_for"
                class="border border-gray-300 px-3 py-2 rounded w-50 focus:outline-none focus:ring focus:ring-blue-200">
                <option value="">Applicable for All</option>
                <option value="regular">Regular</option>
                <option value="jo">JO</option>
                <option value="cos">COS</option>
            </select>
            <select wire:model.live.debounce.300ms="status"
                class="border border-gray-300 px-3 py-2 rounded w-50 focus:outline-none focus:ring focus:ring-blue-200">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            {{ $title }}
            {{ $applicable_for }}
            {{ $status }}
            {{ $title }}
        </div>
    </div>


    <div class="bg-white rounded-lg shadow flex-1">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="text-left">
                <tr class="text-gray-600">
                    <th class="px-3 py-4" width="20%">Title</th>
                    <th class="px-3 py-4">Status</th>
                    <th class="px-3 py-4">Duration</th>
                    <th class="px-3 py-4">Conducted by/ Facilitator</th>
                    <th class="px-3 py-4">Charging of Funds</th>
                    <th class="px-3 py-4">Name of Nominees / Participants</th>
                    <th class="px-3 py-4">Number of Nominees / Participants</th>
                    <th class="px-3 py-4">Endorse / Recomended by</th>
                    <th class="px-3 py-4">HRDC Resolution No.</th>
                    <th class="px-3 py-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($trainings as $training)
                <tr class="">
                    @php
                    $badgeLabels = [
                    'regular' => 'Regular',
                    'jocos' => 'Jocos',
                    'regular_and_jocos' => 'Regular and Jocos',
                    ];

                    switch($training->applicable_for) {
                    case 'regular':
                    $badgeColor = 'bg-green-100 text-green-600';
                    break;
                    case 'jocos':
                    $badgeColor = 'bg-blue-100 text-blue-600';
                    break;
                    case 'regular_and_jocos':
                    $badgeColor = 'bg-yellow-100 text-yellow-600';
                    break;
                    default:
                    $badgeColor = 'bg-gray-100 text-gray-800';
                    }

                    $badgeLabel = $badgeLabels[$training->applicable_for] ?? ucfirst($training->applicable_for);
                    @endphp

                    <td class="px-3 py-2">
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full mb-2 {{ $badgeColor }}">
                            {{ $badgeLabel }}
                        </span>
                        <br>
                        <span class="font-bold text-gray-600">
                            {{ $training->title }}
                        </span>
                    </td>
                    <td class="px-3 py-2">{{ ucfirst($training->status) }}</td>
                    <td class="px-3 py-2">{{ $training->duration }}</td>
                    <td class="px-3 py-2">{{ $training->conducted_by }}</td>
                    <td class="px-3 py-2">{{ $training->charging_of_funds }}</td>
                    <td class="px-3 py-2">
                        @foreach($training->name_of_nominees as $name)
                        <span class="block">{{ $name }}</span>
                        @endforeach
                    </td>
                    <td class="px-3 py-2">
                        {{ $training->number_of_nominees > 0 ? $training->number_of_nominees : '' }}
                    </td>
                    <td class="px-3 py-2">{{ $training->endorsed_by }}</td>
                    <td class="px-3 py-2">{{ $training->hrdc_resolution_no }}</td>
                    <td class="px-3 py-2">
                        <div class="flex flex-col gap-1">
                            {{-- <button
                                class="w-8 h-8 bg-green-400 text-white rounded-lg cursor-pointer hover:bg-green-500"><i
                                    class="fa-solid fa-file-lines"></i></button> --}}
                            <a href="{{ route('admin.trainings.edit', $training->id) }}"
                                class="w-8 h-8 bg-blue-400 text-white rounded-lg cursor-pointer hover:bg-blue-500 flex items-center justify-center">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <button wire:click="confirmDelete({{ $training->id }})"
                                class="w-8 h-8 bg-red-400 text-white rounded hover:bg-red-500 flex items-center justify-center">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="10" class="px-3 py-6 text-center text-gray-500">
                        <i class="fa-solid fa-folder-open"></i> No training records found.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
    <div x-data="{ open: @entangle('confirmingDeleteId') }" 
     x-show="open" 
     x-cloak
     style="display: none;"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

    <div class="bg-white rounded-lg p-6 w-96" @click.away="open = null">
        <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
        <p class="mb-4">Are you sure you want to delete this training?</p>

        <div class="flex justify-end gap-3">
            <button @click="open = null" class="px-4 py-2 rounded bg-gray-200">Cancel</button>

            <button wire:click="delete" class="px-4 py-2 rounded bg-red-500 text-white">
                Delete
            </button>
        </div>
    </div>
</div>
</div>
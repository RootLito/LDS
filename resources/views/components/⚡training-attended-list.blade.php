<?php

use App\Models\TrainingAttended;
use Livewire\Component;

new class extends Component
{
    public $trainings = [];
    public $titleFilter = '';
    public $dateFilter = '';

    public $test = '';

    public function mount()
    {
        $this->loadTrainings();
    }

    public function updated()
    {
        $this->loadTrainings();
    }


    protected function loadTrainings()
    {
        $this->trainings = TrainingAttended::where('emp_id', auth()->id())
            ->when($this->titleFilter, fn ($q) =>
                $q->where('title', 'like', "%{$this->titleFilter}%")
            )
            ->when($this->dateFilter, fn ($q) =>
                $q->whereDate('date', $this->dateFilter)
            )
            ->latest()
            ->get();
    }
};
?>

<div class="overflow-x-auto">

    <!-- Filters -->
    <div class="bg-white rounded-lg mb-6 p-4 flex gap-4">
        <input type="text" wire:model.live.debounce.300ms="test" placeholder="Filter by title"
               class="border border-gray-300 px-3 py-2 rounded w-1/3">
               {{ $this->titleFilter }}

        <input type="date" wire:model.live.debounce.300ms="dateFilter"
               class="border border-gray-300 px-3 py-2 rounded">
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-left">
                <tr class="text-gray-600">
                    <th class="px-3 py-2">Title</th>
                    <th class="px-3 py-2">Date</th>
                    <th class="px-3 py-2">Duration</th>
                    <th class="px-3 py-2">Type</th>
                    <th class="px-3 py-2">Sponsored</th>
                    <th class="px-3 py-2">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($trainings as $training)
                    <tr>
                        <td class="px-3 py-2">{{ $training->title }}</td>
                        <td class="px-3 py-2">{{ $training->date }}</td>
                        <td class="px-3 py-2">{{ $training->duration }}</td>
                        <td class="px-3 py-2">{{ $training->type }}</td>
                        <td class="px-3 py-2">{{ $training->sponsored ?? '—' }}</td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-6 text-center text-gray-500">
                            No training records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

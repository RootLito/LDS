<?php

use App\Models\Employee;
use Livewire\Component;

new class extends Component
{
    public $employees = [];
    public $name = '';
    public $status = '';
    public $office = '';
    public $position = '';
    public $hours = '';

    public function mount()
    {
        $this->loadEmployees();
    }

    public function updated()
    {
        $this->loadEmployees();
    }

    protected function loadEmployees()
    {
        $this->employees = Employee::with('trainingsAttended')
            ->when($this->name, fn ($q) =>
                $q->where('fullname', 'like', "%{$this->name}%")
            )
            ->when($this->status, fn ($q) =>
                $q->where('status', $this->status)
            )
            ->when($this->office, fn ($q) =>
                $q->where('office', 'like', "%{$this->office}%")
            )
            ->when($this->position, fn ($q) =>
                $q->where('position', 'like', "%{$this->position}%")
            )
            ->when($this->hours, fn ($q) =>
                $q->whereHas('trainingsAttended', fn ($t) =>
                    $t->selectRaw('employee_id, SUM(duration) as total_hours')
                      ->groupBy('employee_id')
                      ->having('total_hours', '>=', $this->hours)
                )
            )
            ->get();
    }
};
?>

<div class="flex-1 mt-10">
    <div class="bg-white rounded-lg px-4 py-6 mb-10">
        <div class="flex flex-wrap gap-4">
            <input type="text" wire:model.live.debounce.300ms="name" placeholder="Filter by name"
                class="border border-gray-300 px-3 py-2 rounded w-100 focus:outline-none focus:ring focus:ring-blue-200" />
                

            <select wire:model.live.debounce.300ms="status"
                class="border border-gray-300 px-3 py-2 rounded w-40 focus:outline-none focus:ring focus:ring-blue-200">
                <option value="">All Status</option>
                <option value="regular">Regular</option>
                <option value="jo">JO</option>
                <option value="cos">COS</option>
            </select>

            <input type="text" wire:model.live.debounce.300ms="office" placeholder="Filter by office"
                class="border border-gray-300 px-3 py-2 rounded flex-1 focus:outline-none focus:ring focus:ring-blue-200" />

            <input type="text" wire:model.live.debounce.300ms="position" placeholder="Filter by position"
                class="border border-gray-300 px-3 py-2 rounded flex-1 focus:outline-none focus:ring focus:ring-blue-200" />

            <input type="number" wire:model.live.debounce.300ms="hours" placeholder="No. of hours attended"
                class="border border-gray-300 px-3 py-2 rounded w-full md:w-1/6 focus:outline-none focus:ring focus:ring-blue-200" />

        </div>

    </div>



    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($employees as $employee)
        <div class="bg-white rounded-lg p-4 flex flex-col">
            <div class="w-full flex gap-10">
                <div class="w-20 h-20 rounded-full overflow-hidden">
                    <img src="{{ $employee->profile ? Storage::url($employee->profile) : asset('images/bfar.png') }}"
                        alt="Profile Picture" class="w-full h-full object-cover" />
                </div>

                <div class="flex-1">
                    <h2 class="text-lg font-semibold mb-2 text-gray-600">{{ $employee->fullname }}</h2>
                    <div class="flex gap-2">
                        @php
                        switch($employee->status) {
                        case 'regular':
                        $badgeColor = 'bg-green-100 text-green-600';
                        $badgeLabel = 'Regular';
                        break;
                        case 'jo':
                        $badgeColor = 'bg-blue-100 text-blue-600';
                        $badgeLabel = 'JO';
                        break;
                        case 'cos':
                        $badgeColor = 'bg-yellow-100 text-yellow-600';
                        $badgeLabel = 'COS';
                        break;
                        default:
                        $badgeColor = 'bg-gray-100 text-gray-800';
                        $badgeLabel = ucfirst($employee->status);
                        break;
                        }
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeColor }}">{{ $badgeLabel
                            }}</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">{{
                            $employee->appointment_date }}</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-10 ms-2 text-sm">

                <!-- Labels -->
                <div class="w-20 space-y-2 font-medium">
                    <p>Gender:</p>
                    <p>Birthdate:</p>
                    <p>Username:</p>
                    <p>Position:</p>
                    <p>Office:</p>
                    <p>Designation:</p>
                </div>

                <!-- Values -->
                <div class="flex-1 space-y-2">
                    <p>{{ $employee->gender }}</p>
                    <p>{{ $employee->birthdate }}</p>
                    <p>{{ $employee->username }}</p>
                    <p>{{ $employee->position }}</p>
                    <p>{{ $employee->office }}</p>
                    <p>{{ $employee->designation }}</p>
                </div>

            </div>






            @if($employee->trainingsAttended->isNotEmpty())
            <div class="mt-4 ms-2">
                <h3 class="font-semibold mb-1 text-gray-600">Trainings Attended:</h3>
                @foreach($employee->trainingsAttended as $training)
                <span class="block text-sm text-gray-700">
                    {{ $training->title }} ({{ $training->duration }})
                </span>
                @endforeach
            </div>
            @else
            <p class="mt-4 text-gray-500 text-sm">No trainings attended.</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
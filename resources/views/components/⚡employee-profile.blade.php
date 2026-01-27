<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithFileUploads;

    public $employee;
    public $fullname;
    public $gender;
    public $birthdate;
    public $appointment_date;
    public $username;  
    public $status;
    public $profile;
    public $position;
    public $office;
    public $designation;

    public function mount()
    {
        $this->employee = Auth::guard('employee')->user();

        $this->fullname = $this->employee->fullname;
        $this->gender = $this->employee->gender;
        $this->birthdate = $this->employee->birthdate;
        $this->appointment_date = $this->employee->appointment_date;
        $this->username = $this->employee->username;
        $this->status = $this->employee->status;
        $this->position = $this->employee->position;
        $this->office = $this->employee->office;
        $this->designation = $this->employee->designation;
    }

    public function updateProfile()
    {
        $this->validate([
            'fullname' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'birthdate' => 'required|date',
            'appointment_date' => 'required|date',
            'status' => 'required|in:JO,COS',
            'profile' => 'nullable|image|max:2048',
            'position' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
        ]);

        if ($this->profile) {
            $path = $this->profile->store('profiles', 'public');
            $this->employee->profile = $path;
        }

        $this->employee->fullname = $this->fullname;
        $this->employee->gender = $this->gender;
        $this->employee->birthdate = $this->birthdate;
        $this->employee->appointment_date = $this->appointment_date;
        $this->employee->status = $this->status;

        $this->employee->position = $this->position;
        $this->employee->office = $this->office;
        $this->employee->designation = $this->designation;

        $this->employee->save();

        session()->flash('success', 'Profile updated successfully!');
    }
};
?>

<div class="w-full bg-white rounded-lg p-8 flex gap-10">

    <div class="w-72 flex flex-col items-center">

        <div class="w-40 h-40 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
            <img src="{{ $employee->profile ? Storage::url($employee->profile) : asset('images/bfar.png') }}"
                alt="Profile Picture" class="w-full h-full object-cover" />
        </div>

        <input type="file" wire:model="profile"
            class="w-full h-10 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 mt-8 text-sm" />

        <button wire:click="updateProfile"
            class="w-full bg-blue-800 text-white h-10 rounded-md hover:bg-blue-900 transition mt-2 text-sm">
            Update Profile
        </button>
    </div>

    <div class="flex-1">
        <h2 class="text-gray-700 font-semibold mb-6 text-lg">Account Information</h2>
        <form wire:submit.prevent="updateProfile" class="space-y-4 text-sm">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Full Name</label>
                <input type="text" wire:model.defer="fullname"
                    class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Gender</label>
                    <select wire:model.defer="gender"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Birthdate</label>
                    <input type="date" wire:model.defer="birthdate"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Employment Status</label>
                    <select wire:model.defer="status"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="regular">Regular</option>
                        <option value="jo">JO</option>
                        <option value="cos">COS</option>
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Appointment Date</label>
                    <input type="date" wire:model.defer="appointment_date"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Position</label>
                    <input type="text" wire:model.defer="position"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Office</label>
                    <input type="text" wire:model.defer="office"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Designation</label>
                    <input type="text" wire:model.defer="designation"
                        class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Username</label>
                <input type="text" value="{{ $username }}" readonly
                    class="w-full h-10 px-3 border border-gray-200 bg-gray-100 rounded cursor-not-allowed" />
            </div>

            <button type="submit"
                class="mt-4 w-full bg-blue-800 text-white h-10 rounded-md hover:bg-blue-900 transition">
                Save Changes
            </button>

            @if(session()->has('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                style="display: none"
                class="absolute top-0 right-0 bg-green-600 text-white px-5 py-3 rounded shadow-lg font-semibold z-50">
                {{ session('success') }}
            </div>
            @endif

        </form>
    </div>
</div>
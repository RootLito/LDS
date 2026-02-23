@extends('employee.layout')

@section('content')
<div class="w-[1100px] mx-auto p-6 h-full overflow-y-auto">
    <div class="flex flex-col mb-6">
        <h1 class="text-2xl font-bold text-gray-600">Reset Password</h1>
        <p class="text-gray-500">Enter your new password below.</p>
    </div>

    <div class="max-w-md bg-white rounded-lg p-8 shadow-sm mx-auto">
        <form action="{{ route('employee.profile.password.update') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">New Password</label>
                <input type="password" name="password" required
                    class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Confirm New Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full h-10 px-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-blue-800 text-white h-10 rounded-md hover:bg-blue-900 transition text-sm font-semibold">
                    Update Password
                </button>
                <a href="{{ route('employee.profile') }}" 
                    class="flex-1 border border-gray-300 text-gray-600 h-10 rounded-md hover:bg-gray-50 transition flex items-center justify-center text-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
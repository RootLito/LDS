<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Employee;

class EmployeeController extends Controller
{
    // Show Employee Profile Page
    public function profile()
    {
        $employee = Auth::guard('employee')->user();
        return view('employee.profile', compact('employee'));
    }

    // Update only profile picture
    public function updateProfilePicture(Request $request)
    {
        $employee = Auth::guard('employee')->user();

        $validated = $request->validate([
            'profile' => 'required|image|max:2048',
        ]);

        if ($employee->profile) {
            Storage::disk('public')->delete($employee->profile);
        }

        $employee->profile = $request->file('profile')->store('profiles', 'public');
        $employee->save();

        return back()->with('success', 'Profile picture updated successfully!');
    }

    // Update account info (excluding profile picture)
    public function updateAccount(Request $request)
    {
        $employee = Auth::guard('employee')->user();

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'birthdate' => 'required|date',
            'appointment_date' => 'required|date',
            'status' => 'required|in:permanent,jo,cos',
            'position' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
        ]);

        $employee->update($validated);

        return back()->with('success', 'Account information updated successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Employee;

class EmployeeController extends Controller
{
    // ===============================
    // Show Employee Profile Page
    // ===============================
    public function profile()
    {
        if (!Auth::guard('employee')->check()) {
            return redirect()->route('employee.login.form')
                ->with('error', 'Please login first.');
        }
        $employee = Auth::guard('employee')->user();

        return view('employee.profile', compact('employee'));
    }

    // ===============================
    // Update Employee Profile
    // ===============================
    public function updateProfile(Request $request)
    {
        $employee = Auth::guard('employee')->user();

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'birthdate' => 'required|date',
            'appointment_date' => 'required|date',
            'status' => 'required|in:permanent,jo,cos',
            'profile' => 'nullable|image|max:2048',
            'position' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('profile')) {
            if ($employee->profile) {
                Storage::disk('public')->delete($employee->profile);
            }
            $validated['profile'] = $request->file('profile')
                ->store('profiles', 'public');
        }
        $employee->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}

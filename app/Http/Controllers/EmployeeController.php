<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Employee;
use App\Models\Skill;

class EmployeeController extends Controller
{
    // Show Employee Profile Page
    public function profile()
    {
        $employee = Auth::guard('employee')->user();
        $skills = Skill::all();  // Fetch all available skills
        $employeeSkills = $employee->skills; // Get the employee's skills via the relationship
        return view('employee.profile', compact('employee', 'skills', 'employeeSkills'));
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
            'skills' => 'nullable|array',
        ]);

        $employee->update($validated);

        return back()->with('success', 'Account information updated successfully!');
    }

}

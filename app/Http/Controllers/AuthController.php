<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

use App\Models\Admin;

class AuthController extends Controller
{
    // Show Employee Registration Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }


    public function registerEmployee(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'birthdate' => 'required|date',
            'appointment_date' => 'required|date',
            'status' => 'required|in:permanent,jo,cos',
            'position' => 'required|string|max:255',
            'office' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'username' => 'required|string|unique:employees,username',
            'password' => 'required|string|min:6|confirmed',
            'profile' => 'nullable|image|max:2048',
        ]);

        $profilePath = null;
        if ($request->hasFile('profile')) {
            $profilePath = $request->file('profile')->store('profiles', 'public');
        }

        Employee::create([
            'fullname' => $request->fullname,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'appointment_date' => $request->appointment_date,
            'status' => $request->status,
            'position' => $request->position,
            'office' => $request->office,
            'designation' => $request->designation,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'profile' => $profilePath,
        ]);

        return redirect()->route('employee.login.form')
            ->with('success', 'Registration successful! Please login.');
    }




    // Show Employee Login Form
    public function showEmployeeLoginForm()
    {
        return view('auth.employee_login');
    }

    // Show Admin Login Form
    public function showAdminLoginForm()
    {
        return view('auth.admin_login');
    }

    // Handle Employee Login
    public function employeeLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Use the employee guard
        if (Auth::guard('employee')->attempt($credentials)) {
            // Auth::guard('employee')->login(Auth::guard('employee')->user());
            $request->session()->regenerate();
            return redirect()->route('employee.dashboard')
                ->with('success', 'Logged in successfully as Employee.');
        }

        return back()->withErrors(['username' => 'Invalid employee credentials'])->withInput();
    }

    // Employee Logout
    public function employeeLogout(Request $request)
    {
        Auth::guard('employee')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('employee.login.form')
            ->with('success', 'Logged out successfully.');
    }

    // Handle Admin Login
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate(); // important
            return redirect()->route('admin.dashboard')
                ->with('success', 'Logged in successfully as Admin.');
        }

        return back()->withErrors(['username' => 'Invalid admin credentials'])->withInput();
    }


    // Admin Logout
    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.form')
            ->with('success', 'Logged out successfully.');
    }
}

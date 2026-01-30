<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Employee;
use App\Models\TrainingAttended;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportData;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class TrainingController extends Controller
{
    // ===============================
    // Employee Dashboard
    // ===============================
    public function dashboard(Request $request)
    {
        if (!Auth::guard('employee')->check()) {
            return redirect()
                ->route('employee.login.form')
                ->with('error', 'Please login first.');
        }

        $employee = Auth::guard('employee')->user();

        $trainings = TrainingAttended::where('emp_id', $employee->id)
            ->when($request->title, function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->title . '%');
            })
            ->when($request->date, function ($q) use ($request) {
                $q->where('date', 'like', '%' . $request->date . '%');
            })
            ->latest()
            ->get();

        return view('employee.dashboard', compact('trainings'));
    }

    // ===============================
    // Admin Dashboards
    // ===============================
    public function adminDashboard()
    {
        $totalTrainings = Training::count();
        $totalEmployees = Employee::count();
        $employeesWithTraining = Employee::has('trainingsAttended')->count();
        $employeesWithoutTraining = Employee::doesntHave('trainingsAttended')->count();
        $monthlyTrainings = TrainingAttended::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
        $trainingsByMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $trainingsByMonth[$i] = $monthlyTrainings[$i] ?? 0;
        }
        $employeeRanking = Employee::withCount('trainingsAttended')
            ->orderByDesc('trainings_attended_count')
            ->get();

        return view('admin.dashboard', compact(
            'totalTrainings',
            'totalEmployees',
            'employeesWithTraining',
            'employeesWithoutTraining',
            'trainingsByMonth',
            'employeeRanking'
        ));
    }

    public function trainings(Request $request)
    {
        $skills = Skill::all();
        $trainings = Training::query()
            ->when($request->title, fn($q) => $q->where('title', 'like', '%' . $request->title . '%'))
            ->when($request->applicable_for, fn($q) => $q->where('applicable_for', $request->applicable_for))
            ->latest()
            ->paginate(4);

        foreach ($trainings as $training) {
            $nominees = Employee::query()
                ->whereDoesntHave('trainingsAttended', function ($q) use ($training) {
                    $q->where('title', $training->title);
                })
                ->when($training->applicable_for, function ($q) use ($training) {
                    if ($training->applicable_for === 'permanent') {
                        $q->where('status', 'permanent');
                    } elseif ($training->applicable_for === 'jocos') {
                        $q->whereIn('status', ['jo', 'cos']);
                    } elseif ($training->applicable_for === 'permanent_and_jocos') {
                        $q->whereIn('status', ['permanent', 'jo', 'cos']);
                    }
                })
                ->when($training->applicable_skills, function ($q) use ($training) {
                    $q->whereJsonContains('skills', $training->applicable_skills);
                })
                ->get();

            $training->nominees = $nominees;
            $training->number_of_nominees = $nominees->count();
        }

        return view('admin.training', compact('trainings', 'skills'));
    }



    public function employees(Request $request)
    {
        $employees = Employee::with('trainingsAttended')  // Ensure training data is eagerly loaded
            ->when(
                $request->name,
                fn($q) =>
                $q->where('fullname', 'like', '%' . $request->name . '%')
            )
            ->when(
                $request->status,
                fn($q) =>
                $q->where('status', $request->status)
            )
            ->when(
                $request->office,
                fn($q) =>
                $q->where('office', 'like', '%' . $request->office . '%')
            )
            ->when(
                $request->position,
                fn($q) =>
                $q->where('position', 'like', '%' . $request->position . '%')
            )
            ->when($request->hours, function ($q) use ($request) {
                $q->whereHas('trainingsAttended', function ($t) use ($request) {
                    $t->selectRaw('emp_id, SUM(duration) as total_hours')
                        ->groupBy('emp_id')
                        ->having('total_hours', '>=', $request->hours);
                });
            })
            ->paginate(8);

        return view('admin.employee', compact('employees'));
    }


    public function show($id)
    {
        $employee = Employee::with('trainingsAttended')->findOrFail($id);
        return view('admin.employee-profile', compact('employee'));
    }





    // ===============================
    // Employee: Store Attended Training
    // ===============================
    public function storeAttendedTraining(Request $request)
    {
        if (!Auth::guard('employee')->check()) {
            return redirect()->route('employee.login.form')->with('error', 'Access denied.');
        }

        $employee = Auth::guard('employee')->user();

        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|max:100',
            'sponsored' => 'nullable|string|max:255',
            'certificate_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Calculate duration
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $hours = ($start->diffInDays($end) + 1) * 8;
        $formattedDate = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');

        $certificatePath = null;

        if ($request->hasFile('certificate_path')) {
            $file = $request->file('certificate_path');

            // VITAL: Check isValid() to catch IIS temp permission issues
            if ($file->isValid()) {
                $certificatePath = $file->store('certificates', 'public');
            } else {
                // If IIS blocks the file, we return an error instead of crashing
                return back()->withErrors(['certificate_path' => 'Server Error: Unable to read temporary file. Check IIS Temp permissions.']);
            }
        }

        TrainingAttended::create([
            'emp_id' => $employee->id,
            'title' => strtoupper($request->title),
            'date' => $formattedDate,
            'duration' => $hours,
            'type' => strtoupper($request->type),
            'sponsored' => $request->sponsored ? strtoupper($request->sponsored) : null,
            'certificate_path' => $certificatePath, // Guaranteed to be path or null
        ]);

        return back()->with('success', 'Training record added successfully!');
    }

    // ===============================
// Employee: Update Attended Training
// ===============================
    public function updateAttendedTraining(Request $request, $id)
    {
        $employee = Auth::guard('employee')->user();
        $training = TrainingAttended::where('emp_id', $employee->id)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|max:100',
            'sponsored' => 'nullable|string|max:255',
            'certificate_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $hours = ($start->diffInDays($end) + 1) * 8;
        $formattedDate = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');

        $certificatePath = $training->certificate_path;

        if ($request->hasFile('certificate_path')) {
            $file = $request->file('certificate_path');

            if ($file->isValid()) {
                // FIX: Using !empty() prevents the "Path must not be empty" error if old path was null
                if (!empty($certificatePath) && Storage::disk('public')->exists($certificatePath)) {
                    Storage::disk('public')->delete($certificatePath);
                }
                $certificatePath = $file->store('certificates', 'public');
            } else {
                return back()->withErrors(['certificate_path' => 'Server Error: Unable to read temporary file.']);
            }
        }

        $training->update([
            'title' => strtoupper($request->title),
            'date' => $formattedDate,
            'duration' => $hours,
            'type' => strtoupper($request->type),
            'sponsored' => $request->sponsored ? strtoupper($request->sponsored) : null,
            'certificate_path' => $certificatePath,
        ]);

        return back()->with('success', 'Training record updated successfully!');
    }
    // ===============================
    // Employee: Delete Attended Training
    // ===============================
    public function destroyAttendedTraining($id)
    {
        $employee = Auth::guard('employee')->user();
        $training = TrainingAttended::where('emp_id', $employee->id)->findOrFail($id);
        $training->delete();

        return back()->with('success', 'Training record deleted successfully!');
    }
    // ===============================
    // Employee: View Certificates
    // ===============================
    public function certificates()
    {
        $employee = Auth::guard('employee')->user();

        $trainings = TrainingAttended::where('emp_id', $employee->id)
            ->whereNotNull('certificate_path')
            ->get();

        return view('employee.certificates', compact('trainings'));
    }

    public function allCertificates()
    {
        $name = request('name');
        $title = request('title');

        $employees = Employee::query()
            ->when($title, function ($query) use ($title) {
                $query->whereHas('trainingsAttended', function ($q) use ($title) {
                    $q->whereNotNull('certificate_path')
                        ->where('title', 'like', "%$title%");
                });
            })
            ->when($name, function ($query) use ($name) {
                $query->where('fullname', 'like', "%$name%");
            })
            ->with([
                'trainingsAttended' => function ($q) use ($title) {
                    $q->whereNotNull('certificate_path');
                    if ($title) {
                        $q->where('title', 'like', "%$title%");
                    }
                }
            ])
            ->paginate(10)
            ->withQueryString();

        return view('admin.certificates', compact('employees'));
    }


    // ===============================
    // Admin: Training CRUD
    // ===============================
    public function index()
    {
        $trainings = Training::all()->pagination(8);
        return view('admin.trainings.index', compact('trainings'));
    }

    public function create()
    {
        return view('admin.trainings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|string|max:50',
            'duration' => 'required|string|max:50',
            'conducted_by' => 'required|string|max:255',
            'charging_of_funds' => 'nullable|string|max:255',
            'endorsed_by' => 'nullable|string|max:255',
            'hrdc_resolution_no' => 'nullable|string|max:255',
            'applicable_for' => 'nullable|string|max:255',
            'applicable_skills' => 'nullable|array',
        ]);

        // dd($request->all());

        Training::create([
            'title' => $request->title,
            'status' => $request->status,
            'duration' => $request->duration,
            'conducted_by' => $request->conducted_by,
            'charging_of_funds' => $request->charging_of_funds,
            'endorsed_by' => $request->endorsed_by,
            'hrdc_resolution_no' => $request->hrdc_resolution_no,
            'applicable_for' => $request->applicable_for,
            'applicable_skills' => $request->applicable_skills,
        ]);

        return redirect()
            ->route('admin.trainings')
            ->with('success', 'Training created successfully.');
    }

    public function edit($id)
    {
        $training = Training::findOrFail($id);
        $nominees = Employee::whereDoesntHave('trainingsAttended', function ($q) use ($training) {
            $q->where('title', $training->title);
        })->get();
        $training->nominees = $nominees;
        $training->number_of_nominees = $nominees->count();

        return view('admin.update-training', compact('training'));
    }



    public function update(Request $request, $id)
    {
        $training = Training::findOrFail($id);

        // Validate the input
        $validated = $request->validate([
            'title' => 'required|string',
            'applicable_for' => 'nullable|string',
            'status' => 'required|string',
            'duration' => 'required|string',
            'conducted_by' => 'required|string',
            'charging_of_funds' => 'nullable|string',
            'endorsed_by' => 'nullable|string',
            'hrdc_resolution_no' => 'nullable|string',
            'applicable_skill' => 'nullable|array',
        ]);

        $training->update($validated);

        return redirect()->route('admin.trainings')
            ->with('success', 'Training and nominee list updated successfully.');
    }

    public function destroy($id)
    {
        Training::findOrFail($id)->delete();

        return redirect()
            ->route('admin.trainings')
            ->with('success', 'Training deleted successfully.');
    }



    // export  
    public function exportTrainings()
    {
        return Excel::download(new ExportData, 'Training_and_Developement.xlsx');
    }
}

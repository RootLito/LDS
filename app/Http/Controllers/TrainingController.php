<?php

namespace App\Http\Controllers;

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
        $trainings = Training::query()
            ->when(
                $request->title,
                fn($q) =>
                $q->where('title', 'like', '%' . $request->title . '%')
            )
            ->when(
                $request->applicable_for,
                fn($q) =>
                $q->where('applicable_for', $request->applicable_for)
            )
            ->when(
                $request->status,
                fn($q) =>
                $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(10);

        foreach ($trainings as $training) {
            $nominees = Employee::whereDoesntHave('trainingsAttended', function ($q) use ($training) {
                $q->where('title', $training->title);
            })->get();

            $training->nominees = $nominees;
            $training->number_of_nominees = $nominees->count();
        }


        return view('admin.training', compact('trainings'));
    }


    public function employees(Request $request)
    {
        $employees = Employee::with('trainingsAttended')
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
            ->paginate(10);

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
            return redirect()
                ->route('employee.login.form')
                ->with('error', 'Access denied.');
        }

        $request->validate([
            'title'           => 'required|string|max:255',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'type'            => 'required|string|max:100',
            'sponsored'       => 'nullable|string|max:255',
            'certificate_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $employee = Auth::guard('employee')->user();

        // Calculate duration in hours: 1 day = 8 hours
        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date);
        $days  = $start->diffInDays($end) + 1; // +1 to include the start day
        $hours = $days * 8;

        // Format dates as dd/mm/yyyy
        $formattedDate = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');

        $certificatePath = null;
        if ($request->hasFile('certificate_path')) {
            $certificatePath = $request->file('certificate_path')->store('certificates', 'public');
        }

        TrainingAttended::create([
            'emp_id'          => $employee->id,
            'title'           => strtoupper($request->title),
            'date'            => $formattedDate,
            'duration'        => $hours,
            'type'            => strtoupper($request->type),
            'sponsored'       => strtoupper($request->sponsored),
            'certificate_path' => $certificatePath,
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
            'title'           => 'required|string|max:255',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'type'            => 'required|string|max:100',
            'sponsored'       => 'nullable|string|max:255',
            'certificate_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Calculate duration in hours: 1 day = 8 hours
        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date);
        $days  = $start->diffInDays($end) + 1;
        $hours = $days * 8;

        // Format dates as dd/mm/yyyy
        $formattedDate = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');

        $certificatePath = $training->certificate_path;

        if ($request->hasFile('certificate_path')) {
            // Delete old certificate if it exists
            if ($certificatePath) {
                Storage::disk('public')->delete($certificatePath);
            }
            $certificatePath = $request->file('certificate_path')->store('certificates', 'public');
        }

        $training->update([
            'title'           => strtoupper($request->title),
            'date'            => $formattedDate,
            'duration'        => $hours,
            'type'            => strtoupper($request->type),
            'sponsored'       => strtoupper($request->sponsored),
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
        $employees = Employee::with(['trainingsAttended' => function ($q) {
            $q->whereNotNull('certificate_path');
        }])
        ->paginate(10);

        return view('admin.certificates', compact('employees'));
    }



    // ===============================
    // Admin: Training CRUD
    // ===============================
    public function index()
    {
        $trainings = Training::all();
        return view('admin.trainings.index', compact('trainings'));
    }

    public function create()
    {
        return view('admin.trainings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'status'              => 'required|string|max:50',
            'duration'            => 'required|string|max:50',
            'conducted_by'        => 'required|string|max:255',
            'charging_of_funds'   => 'nullable|string|max:255',
            'endorsed_by'         => 'nullable|string|max:255',
            'hrdc_resolution_no'  => 'nullable|string|max:255',
            'applicable_for'      => 'nullable|string|max:255',
        ]);

        Training::create([
            'title'              => $request->title,
            'status'             => $request->status,
            'duration'           => $request->duration,
            'conducted_by'       => $request->conducted_by,
            'charging_of_funds'  => $request->charging_of_funds,
            'endorsed_by'        => $request->endorsed_by,
            'hrdc_resolution_no' => $request->hrdc_resolution_no,
            'applicable_for'     => $request->applicable_for,
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
        $training->update($request->all());
        return redirect()->route('admin.trainings')
            ->with('success', 'Training updated successfully.');
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

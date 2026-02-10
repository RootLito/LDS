@extends('admin.layout')

@section('content')
    <div class="w-full h-full flex flex-col p-10 overflow-y-auto">
        <h1 class="text-2xl font-bold text-gray-700">Dashboard</h1>
        <p class="text-gray-500">Overview of training and employee statistics</p>

        <div class="flex-1 grid gap-6 mt-10
            grid-cols-1
            md:grid-cols-4">
            <div class="bg-white rounded p-6 flex flex-col items-center">
                <div class="bg-blue-100 text-blue-600 rounded-full p-3 mb-4 w-12 h-12 flex items-center justify-center">
                    <i class="fa-solid fa-chalkboard-user fa-lg"></i>
                </div>
                <p class="text-gray-500 mt-2">Total Trainings</p>
                <p class="text-xl font-bold mt-1">{{ $totalTrainings }}</p>
            </div>
            <div class="bg-white rounded p-6 flex flex-col items-center">
                <div class="bg-green-100 text-green-600 rounded-full p-3 mb-4 w-12 h-12 flex items-center justify-center">
                    <i class="fa-solid fa-users fa-lg"></i>
                </div>
                <p class="text-gray-500 mt-2">Total Employees</p>
                <p class="text-xl font-bold mt-1">{{ $totalEmployees }}</p>
            </div>
            <div class="bg-white rounded p-6 flex flex-col items-center">
                <div class="bg-yellow-100 text-yellow-600 rounded-full p-3 mb-4 w-12 h-12 flex items-center justify-center">
                    <i class="fa-solid fa-user-check fa-lg"></i>
                </div>
                <p class="text-gray-500 mt-2">Employee(s) With Training</p>
                <p class="text-xl font-bold mt-1">{{ $employeesWithTraining }}</p>
            </div>
            <div class="bg-white rounded p-6 flex flex-col items-center">
                <div class="bg-red-100 text-red-600 rounded-full p-3 mb-4 w-12 h-12 flex items-center justify-center">
                    <i class="fa-solid fa-user-xmark fa-lg"></i>
                </div>
                <p class="text-gray-500 mt-2">Employee(s) Without Training</p>
                <p class="text-xl font-bold mt-1">{{ $employeesWithoutTraining }}</p>
            </div>

            <div class="flex flex-col bg-white rounded p-6 md:col-span-3 h-[520px]">
                <p class="text-gray-600 text-lg mb-6 font-bold">Monthly Training Distribution</p>
                <div class="relative flex-1 h-full w-full">
                    <canvas id="monthlyTrainingChart"></canvas>
                </div>
            </div>


            <div class="bg-white rounded p-6 md:col-span-1 h-[520px] overflow-y-auto">
                <p class="text-gray-600 text-lg mb-6 font-bold">Training Engagement</p>


                <div class="w-full bg-white rounded space-y-2">
                    @foreach ($employeeRanking as $employee)
                        <div class="w-full flex items-center justify-between p-4 bg-gray-50 rounded">
                            <div class="flex-shrink-0">
                                <img src="{{ $employee->profile ? Storage::url($employee->profile) : asset('images/bfar.png') }}"
                                    alt="{{ $employee->fullname }}"
                                    class="w-16 h-16 rounded-full object-cover border border-gray-300">
                            </div>
                            <div class="flex-1 flex flex-col ml-4">
                                <h3 class="text-lg font-bold text-gray-700">{{ $employee->fullname }}</h3>
                                <div class="flex gap-2 mt-1">

                                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-500 italic">
                                        {{ $employee->position }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-center justify-center ml-4 ">
                                <i class="fa-solid fa-clipboard-check text-green-500 text-xl"></i>
                                <span class="mt-1 text-gray-700 font-semibold text-sm">
                                    {{ $employee->trainings_attended_count }}
                                </span>
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const ctx = document.getElementById('monthlyTrainingChart').getContext('2d');
            const monthlyTrainingChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        data: {!! json_encode(array_values($trainingsByMonth)) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.8)', // Increased opacity for better look
                        borderWidth: 0, // Remove border width
                        borderRadius: 6, // Makes bars rounded (Tailwind-like)
                        borderSkipped: false, // Ensures all corners can be rounded
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Removes the "Number of Trainings" title/legend
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false 
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: '#f3f4f6'
                            }
                        }
                    }
                }
            });
        </script>
    </div>
@endsection

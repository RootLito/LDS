@extends('admin.layout')

@section('content')
<div class="p-10" x-data="certificateModal()" @keydown.escape.window="isOpen = false">

    <h1 class="text-2xl font-bold text-gray-700">Employee Certificates</h1>
    <p class="text-gray-500 mb-10">View all employee trainings and certificates</p>

    <div class="bg-white rounded overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600">
                <tr class="border-b border-gray-200">
                    <th class="p-4">Employee</th>
                    <th class="p-4">Total Trainings</th>
                    <th class="p-4">Total Certificates</th>
                    <th class="p-4" width="15%">Certificates</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($employees as $employee)
                @php
                    $certificates = $employee->trainingsAttended->filter(fn($t) => $t->certificate_path)->values();
                @endphp
                <tr class="{{ $loop->last ? '' : 'border-b border-gray-200' }} hover:bg-gray-50">
                    <td class="px-4 py-3 flex items-center gap-3">
                        <img src="{{ $employee->profile ? Storage::url($employee->profile) : asset('images/bfar.png') }}"
                            class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <p class="font-semibold text-gray-700">{{ $employee->fullname }}</p>
                            <p class="text-xs text-gray-500">{{ $employee->designation }}</p>
                        </div>
                    </td>

                    <td class="px-4 py-3">{{ $employee->trainingsAttended->count() }}</td>

                    <td class="px-4 py-3">{{ $certificates->count() }}</td>

                    <td class="px-4 py-3">
                        @if($certificates->isEmpty())
                            <span class="text-gray-400 text-sm">No Certificates</span>
                        @else
                            <button 
                                @click="openModal({{ $certificates->map(fn($c)=>[
                                    'certificate_path'=>Storage::url($c->certificate_path),
                                    'title'=>$c->title
                                ])->toJson() }})"
                                class="h-8 px-2 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center justify-center">
                                <i class="fa-solid fa-eye me-2"></i> View Certificates
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-500">
                        No employees found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $employees->withQueryString()->links() }}
    </div>

    <!-- Modal -->
    <div
        x-show="isOpen"
        x-transition.opacity
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-10"
        style="display: none;"
        @click.away="isOpen = false"
    >
        <div class="bg-white rounded-lg overflow-hidden w-full max-w-4xl relative flex flex-col" style="max-height: calc(100vh - 4rem);">

            <!-- Close Button -->
            <button
                @click="isOpen = false"
                class="absolute top-2 right-2 text-red-600 hover:text-gray-900 text-2xl font-bold bg-white w-8 h-8 rounded flex items-center justify-center z-10"
                aria-label="Close modal"
            >
                &times;
            </button>

            <!-- Large Image -->
            <div class="flex-1 flex items-center justify-center bg-gray-100 p-4 max-h-[70vh]">
                <img
                    :src="modalImage"
                    alt="Certificate"
                    class="max-h-full max-w-full object-contain"
                />
            </div>

            <!-- Thumbnails Carousel -->
            <div class="border-t border-gray-200 bg-white p-4 flex items-center justify-center space-x-2 overflow-x-auto scrollbar-hide no-scrollbar">

                <template x-for="cert in employeeCertificates" :key="cert.certificate_path">
                    <img
                        :src="cert.certificate_path"
                        @click="selectCertificate(cert)"
                        class="cursor-pointer border border-gray-300 rounded transition w-24 h-16 object-cover flex-shrink-0"
                        :class="cert.certificate_path === modalImage ? 'opacity-100 ring-2 ring-indigo-600' : 'opacity-50 hover:opacity-80'"
                        alt="Certificate thumbnail"
                    />
                </template>

            </div>
        </div>
    </div>
</div>

<script>
function certificateModal() {
    return {
        isOpen: false,
        modalImage: '',
        modalTitle: '',
        employeeCertificates: [],

        openModal(certificates) {
            this.employeeCertificates = certificates;
            if (certificates.length) {
                this.selectCertificate(certificates[0]);
            }
            this.isOpen = true;
        },

        selectCertificate(cert) {
            this.modalImage = cert.certificate_path;
            this.modalTitle = cert.title || '';
        },
    }
}
</script>
@endsection

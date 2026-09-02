<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Ringkasan: satu card, dibagi per kolom biar padat --}}
            <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-100 sm:divide-y-0 sm:divide-x sm:grid sm:grid-cols-3">

                <div class="flex items-center gap-3 p-4">
                    <span class="shrink-0 w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-3a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs text-gray-500">Total Karyawan Aktif</p>
                        <p class="text-xl font-bold text-gray-800 leading-tight">{{ $totalEmployees }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-4">
                    <span class="shrink-0 w-9 h-9 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-500">Cuti Hari Ini</p>
                        <div class="flex items-baseline gap-2">
                            <p class="text-xl font-bold text-gray-800 leading-tight">{{ $onLeaveToday }}</p>
                            <a href="{{ route('leave.approvals') }}" class="text-xs text-indigo-600 hover:underline">Lihat &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-4">
                    <span class="shrink-0 w-9 h-9 rounded-md bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs text-gray-500">Telat Bulan Ini</p>
                        <p class="text-xl font-bold text-gray-800 leading-tight">{{ $lateThisMonth }}</p>
                    </div>
                </div>
            </div>

            {{-- Aksi cepat: baris tombol ringkas, tidak makan tempat --}}
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wide mr-1">Aksi Cepat</span>

                <a href="{{ route('salary.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .672-3 1.5S10.343 11 12 11s3 .672 3 1.5-1.343 1.5-3 1.5m0-6V6m0 8v1m0-9a9 9 0 100 18 9 9 0 000-18z" />
                    </svg>
                    Kelola Gaji
                </a>

                <a href="{{ route('payroll.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                    </svg>
                    Generate Payroll
                </a>

                <a href="{{ route('leave.approvals') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Approval Cuti
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

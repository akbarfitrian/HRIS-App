<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Karyawan Aktif</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalEmployees }}</p>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500">Cuti Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $onLeaveToday }}</p>
                    <a href="{{ route('leave.approvals') }}" class="text-xs text-indigo-600 hover:underline">Lihat approval &rarr;</a>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500">Telat Bulan Ini</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $lateThisMonth }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Aksi Cepat</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('salary.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">
                        Kelola Gaji
                    </a>
                    <a href="{{ route('payroll.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">
                        Generate Payroll
                    </a>
                    <a href="{{ route('leave.approvals') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">
                        Approval Cuti
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

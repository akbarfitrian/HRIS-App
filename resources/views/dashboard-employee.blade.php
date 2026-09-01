<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Saya
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (!$employee)
                <div class="bg-white shadow-sm rounded-lg p-6 text-gray-500">
                    Akun ini belum terhubung ke data karyawan. Hubungi Admin/HR untuk menghubungkan akunmu.
                </div>
            @else
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Data Diri</h3>
                    <dl class="grid grid-cols-2 gap-y-2 text-sm">
                        <dt class="text-gray-500">Nama</dt>
                        <dd>{{ $employee->user->name ?? '-' }}</dd>

                        <dt class="text-gray-500">Kode Karyawan</dt>
                        <dd>{{ $employee->employee_code }}</dd>

                        <dt class="text-gray-500">Departemen</dt>
                        <dd>{{ $employee->department->name ?? '-' }}</dd>

                        <dt class="text-gray-500">Posisi</dt>
                        <dd>{{ $employee->position->name ?? '-' }}</dd>

                        <dt class="text-gray-500">Tanggal Bergabung</dt>
                        <dd>{{ optional($employee->hire_date)->format('d M Y') ?? '-' }}</dd>
                    </dl>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Sisa Kuota Cuti ({{ now()->year }})</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @forelse ($leaveTypes as $type)
                            <div class="border rounded-md p-3">
                                <p class="text-sm text-gray-500">{{ $type->name }}</p>
                                <p class="text-lg font-semibold">
                                    {{ $type->remaining_quota }}
                                    <span class="text-sm font-normal text-gray-400">/ {{ $type->default_quota }} hari</span>
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 col-span-3">Belum ada jenis cuti terdaftar.</p>
                        @endforelse
                    </div>
                    <a href="{{ route('leave.index') }}" class="inline-block mt-4 text-sm text-indigo-600 hover:underline">
                        Ajukan cuti &rarr;
                    </a>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Slip Gaji Terakhir</h3>
                    @if ($latestPayroll)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Periode {{ $latestPayroll->period }}</p>
                                <p class="text-xl font-bold text-gray-800">
                                    Rp {{ number_format($latestPayroll->net_salary, 0, ',', '.') }}
                                </p>
                            </div>
                            <a href="{{ route('payroll.download', $latestPayroll) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                                Unduh PDF
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-400">Belum ada slip gaji.</p>
                    @endif
                    <a href="{{ route('payroll.history') }}" class="inline-block mt-4 text-sm text-indigo-600 hover:underline">
                        Lihat semua riwayat &rarr;
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Komponen Gaji Karyawan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-4 py-2">Kode</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Gaji Pokok</th>
                            <th class="px-4 py-2">Tunjangan</th>
                            <th class="px-4 py-2">Potongan</th>
                            <th class="px-4 py-2">Berlaku Sejak</th>
                            <th class="px-4 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($employees as $employee)
                            <tr>
                                <td class="px-4 py-2">{{ $employee->employee_code }}</td>
                                <td class="px-4 py-2">{{ $employee->user->name ?? '-' }}</td>
                                @if ($employee->latestSalaryComponent)
                                    <td class="px-4 py-2">Rp {{ number_format($employee->latestSalaryComponent->basic_salary, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($employee->latestSalaryComponent->allowance, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($employee->latestSalaryComponent->deduction, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ $employee->latestSalaryComponent->effective_date->format('d M Y') }}</td>
                                @else
                                    <td class="px-4 py-2 text-gray-400" colspan="4">Belum diset</td>
                                @endif
                                <td class="px-4 py-2 text-right">
                                    <a href="{{ route('salary.edit', $employee) }}" class="text-indigo-600 hover:underline">
                                        {{ $employee->latestSalaryComponent ? 'Update' : 'Set Gaji' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-400">Belum ada data employee.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

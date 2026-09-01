<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Slip Gaji
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (!$employee)
                <div class="bg-white shadow-sm rounded-lg p-6 text-gray-500">
                    Akun ini belum terhubung ke data karyawan, jadi belum ada slip gaji yang bisa ditampilkan.
                </div>
            @else
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="px-4 py-2">Periode</th>
                                <th class="px-4 py-2">Gaji Pokok</th>
                                <th class="px-4 py-2">Potongan Telat</th>
                                <th class="px-4 py-2">Gaji Bersih</th>
                                <th class="px-4 py-2 text-right">Slip</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($payrolls as $payroll)
                                <tr>
                                    <td class="px-4 py-2">{{ $payroll->period }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($payroll->late_deduction, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 font-semibold">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <a href="{{ route('payroll.download', $payroll) }}" class="text-indigo-600 hover:underline">Unduh PDF</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada slip gaji.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $payrolls->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

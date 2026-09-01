<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Payroll — Periode {{ $period }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded-md text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6 flex flex-wrap gap-6 items-end">
                <form method="GET" class="flex items-end gap-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Lihat Periode</label>
                        <input type="month" name="period" value="{{ $period }}" class="border-gray-300 rounded-md text-sm">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">
                        Tampilkan
                    </button>
                </form>

                <form method="POST" action="{{ route('payroll.generate') }}" class="flex items-end gap-3"
                      onsubmit="return confirm('Generate payroll untuk periode {{ $period }}? Data yang sudah ada untuk periode ini akan di-update ulang.')">
                    @csrf
                    <input type="hidden" name="period" value="{{ $period }}">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                        Generate Payroll Periode Ini
                    </button>
                </form>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-4 py-2">Kode</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Gaji Pokok</th>
                            <th class="px-4 py-2">Potongan Telat</th>
                            <th class="px-4 py-2">Gaji Bersih</th>
                            <th class="px-4 py-2 text-right">Slip</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($payrolls as $payroll)
                            <tr>
                                <td class="px-4 py-2">{{ $payroll->employee->employee_code }}</td>
                                <td class="px-4 py-2">{{ $payroll->employee->user->name ?? '-' }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($payroll->late_deduction, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 font-semibold">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">
                                    <a href="{{ route('payroll.download', $payroll) }}" class="text-indigo-600 hover:underline">Unduh PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                                    Belum ada payroll untuk periode ini. Klik "Generate Payroll Periode Ini" di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $payrolls->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

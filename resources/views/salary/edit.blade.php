<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Set Komponen Gaji — {{ $employee->user->name ?? $employee->employee_code }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                @if ($current)
                    <div class="mb-4 p-3 bg-gray-50 rounded-md text-sm text-gray-600">
                        Komponen saat ini berlaku sejak {{ $current->effective_date->format('d M Y') }}:
                        Rp {{ number_format($current->basic_salary, 0, ',', '.') }} pokok,
                        Rp {{ number_format($current->allowance, 0, ',', '.') }} tunjangan,
                        Rp {{ number_format($current->deduction, 0, ',', '.') }} potongan.
                        <br>
                        Menyimpan form di bawah akan membuat entri baru (histori lama tetap tersimpan).
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('salary.update', $employee) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Gaji Pokok</label>
                        <input type="number" step="0.01" name="basic_salary"
                               value="{{ old('basic_salary', $current->basic_salary ?? '') }}"
                               class="w-full border-gray-300 rounded-md text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tunjangan</label>
                            <input type="number" step="0.01" name="allowance"
                                   value="{{ old('allowance', $current->allowance ?? 0) }}"
                                   class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Potongan Tetap</label>
                            <input type="number" step="0.01" name="deduction"
                                   value="{{ old('deduction', $current->deduction ?? 0) }}"
                                   class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Berlaku Sejak</label>
                        <input type="date" name="effective_date"
                               value="{{ old('effective_date', now()->toDateString()) }}"
                               class="w-full border-gray-300 rounded-md text-sm">
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                            Simpan
                        </button>
                        <a href="{{ route('salary.index') }}" class="px-4 py-2 text-gray-600 text-sm hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Karyawan</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-3">
                @if ($employee->photo)
                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="Foto" class="w-24 h-24 rounded-full object-cover mb-4">
                @endif

                <p><span class="font-medium">Kode:</span> {{ $employee->employee_code }}</p>
                <p><span class="font-medium">Nama:</span> {{ $employee->user->name }}</p>
                <p><span class="font-medium">Email:</span> {{ $employee->user->email }}</p>
                <p><span class="font-medium">Departemen:</span> {{ $employee->department->name }}</p>
                <p><span class="font-medium">Posisi:</span> {{ $employee->position->name }}</p>
                <p><span class="font-medium">No. Telepon:</span> {{ $employee->phone ?? '-' }}</p>
                <p><span class="font-medium">Alamat:</span> {{ $employee->address ?? '-' }}</p>
                <p><span class="font-medium">Tanggal Bergabung:</span> {{ $employee->hire_date->format('d M Y') }}</p>
                <p><span class="font-medium">Status:</span> {{ ucfirst($employee->status) }}</p>

                <div class="pt-4">
                    <a href="{{ route('employees.index') }}" class="text-indigo-600 hover:underline">&larr; Kembali ke daftar</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

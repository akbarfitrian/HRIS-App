<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rekap Kehadiran
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="GET" class="mb-4 flex items-center gap-2">
                <label for="month" class="text-sm text-gray-600">Bulan</label>
                <input type="month" id="month" name="month" value="{{ $month }}"
                    class="border-gray-300 rounded-md text-sm" onchange="this.form.submit()">
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Karyawan</th>
                            <th class="px-4 py-2">Check-in</th>
                            <th class="px-4 py-2">Check-out</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($attendances as $record)
                            <tr>
                                <td class="px-4 py-2">{{ $record->date->format('d M Y') }}</td>
                                <td class="px-4 py-2">{{ $record->employee->user->name }}</td>
                                <td class="px-4 py-2">{{ $record->check_in ? \Carbon\Carbon::parse($record->check_in)->format('H:i') : '-' }}</td>
                                <td class="px-4 py-2">{{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('H:i') : '-' }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $record->status === 'late' ? 'bg-yellow-100 text-yellow-700' : ($record->status === 'absent' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada data presensi bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

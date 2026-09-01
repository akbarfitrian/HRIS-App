<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Presensi Saya
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>
            @endif

            @if (!$employee)
                <div class="bg-white shadow-sm rounded-lg p-6 text-gray-500">
                    Akun ini belum terhubung ke data karyawan, jadi belum bisa presensi.
                </div>
            @else
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500 mb-3">{{ now()->translatedFormat('l, d F Y') }}</p>

                    <div class="flex items-center gap-6 mb-4">
                        <div>
                            <p class="text-xs text-gray-400">Check-in</p>
                            <p class="text-lg font-semibold">{{ $today?->check_in ? \Carbon\Carbon::parse($today->check_in)->format('H:i') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Check-out</p>
                            <p class="text-lg font-semibold">{{ $today?->check_out ? \Carbon\Carbon::parse($today->check_out)->format('H:i') : '-' }}</p>
                        </div>
                        @if ($today)
                            <span class="px-2 py-1 rounded-full text-xs {{ $today->status === 'late' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                {{ ucfirst($today->status) }}
                            </span>
                        @endif
                    </div>

                    <div class="flex gap-3">
                        <form action="{{ route('attendance.check-in') }}" method="POST">
                            @csrf
                            <button type="submit" {{ $today?->check_in ? 'disabled' : '' }}
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed">
                                Check-in
                            </button>
                        </form>
                        <form action="{{ route('attendance.check-out') }}" method="POST">
                            @csrf
                            <button type="submit" {{ (!$today?->check_in || $today?->check_out) ? 'disabled' : '' }}
                                class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-900 disabled:opacity-40 disabled:cursor-not-allowed">
                                Check-out
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Riwayat Presensi</h3>
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2">Check-in</th>
                                <th class="px-4 py-2">Check-out</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($history as $record)
                                <tr>
                                    <td class="px-4 py-2">{{ $record->date->format('d M Y') }}</td>
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
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada riwayat presensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $history->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pengajuan Cuti Saya
            </h2>
            <a href="{{ route('leave.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                Ajukan Cuti
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            @if (!$employee)
                <div class="bg-white shadow-sm rounded-lg p-6 text-gray-500">
                    Akun ini belum terhubung ke data karyawan, jadi belum bisa mengajukan cuti.
                </div>
            @else
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Sisa Kuota Cuti ({{ now()->year }})</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach ($leaveTypes as $type)
                            <div class="border rounded-md p-3">
                                <p class="text-sm text-gray-500">{{ $type->name }}</p>
                                <p class="text-lg font-semibold">
                                    {{ $type->remaining_quota }}
                                    <span class="text-sm font-normal text-gray-400">/ {{ $type->default_quota }} hari</span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Riwayat Pengajuan</h3>
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="px-4 py-2">Jenis Cuti</th>
                                <th class="px-4 py-2">Periode</th>
                                <th class="px-4 py-2">Alasan</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($requests as $leave)
                                <tr>
                                    <td class="px-4 py-2">{{ $leave->leaveType->name }}</td>
                                    <td class="px-4 py-2">{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</td>
                                    <td class="px-4 py-2">{{ $leave->reason }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 rounded-full text-xs
                                            {{ $leave->status === 'approved' ? 'bg-green-100 text-green-700' : ($leave->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        @if ($leave->status === 'pending')
                                            <form action="{{ route('leave.cancel', $leave) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan pengajuan cuti ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Batalkan</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada pengajuan cuti.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

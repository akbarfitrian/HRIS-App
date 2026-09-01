<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Approval Cuti
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-4 py-2">Karyawan</th>
                            <th class="px-4 py-2">Jenis Cuti</th>
                            <th class="px-4 py-2">Periode</th>
                            <th class="px-4 py-2">Alasan</th>
                            <th class="px-4 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($requests as $leave)
                            <tr>
                                <td class="px-4 py-2">{{ $leave->employee->user->name }}</td>
                                <td class="px-4 py-2">{{ $leave->leaveType->name }}</td>
                                <td class="px-4 py-2">
                                    {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                                    <span class="text-gray-400">({{ $leave->duration }} hari)</span>
                                </td>
                                <td class="px-4 py-2">{{ $leave->reason }}</td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <form action="{{ route('leave.approve', $leave) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:underline">Approve</button>
                                    </form>
                                    <form action="{{ route('leave.reject', $leave) }}" method="POST" class="inline" onsubmit="return confirm('Tolak pengajuan cuti ini?')">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:underline">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400">Gak ada pengajuan cuti yang nunggu approval.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

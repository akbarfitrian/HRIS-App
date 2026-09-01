<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajukan Cuti
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('leave.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Jenis Cuti</label>
                        <select name="leave_type_id" class="w-full border-gray-300 rounded-md text-sm">
                            @foreach ($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} (kuota {{ $type->default_quota }} hari/tahun)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Mulai</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Selesai</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Alasan</label>
                        <textarea name="reason" rows="3" class="w-full border-gray-300 rounded-md text-sm">{{ old('reason') }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                            Kirim Pengajuan
                        </button>
                        <a href="{{ route('leave.index') }}" class="px-4 py-2 text-gray-600 text-sm hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

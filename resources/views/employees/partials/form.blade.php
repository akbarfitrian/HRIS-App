@php
    $user = $employee?->user;
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Nama</label>
    <input type="text" name="name" value="{{ old('name', $user?->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Email</label>
    <input type="email" name="email" value="{{ old('email', $user?->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">
        Password {{ $employee ? '(kosongkan jika tidak diubah)' : '' }}
    </label>
    <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Kode Karyawan</label>
    <input type="text" name="employee_code" value="{{ old('employee_code', $employee?->employee_code) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    @error('employee_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Departemen</label>
        <select name="department_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">-- Pilih Departemen --</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected(old('department_id', $employee?->department_id) == $department->id)>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
        @error('department_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Posisi</label>
        <select name="position_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">-- Pilih Posisi --</option>
            @foreach ($positions as $position)
                <option value="{{ $position->id }}" @selected(old('position_id', $employee?->position_id) == $position->id)>
                    {{ $position->name }}
                </option>
            @endforeach
        </select>
        @error('position_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $employee?->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Bergabung</label>
        <input type="date" name="hire_date" value="{{ old('hire_date', $employee?->hire_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @error('hire_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Alamat</label>
    <textarea name="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('address', $employee?->address) }}</textarea>
    @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Foto</label>
    <input type="file" name="photo" class="mt-1 block w-full">
    @error('photo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Status</label>
    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @foreach (['active' => 'Aktif', 'inactive' => 'Nonaktif'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $employee?->status) == $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

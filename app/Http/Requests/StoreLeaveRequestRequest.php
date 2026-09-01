<?php

namespace App\Http\Requests;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:500'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $employee = $this->user()->employee;

            if (!$employee) {
                $validator->errors()->add('employee', 'Akun ini belum terhubung ke data karyawan.');
                return;
            }

            // Kalau rule dasar di atas udah gagal (tanggal/jenis cuti kosong/invalid),
            // gak perlu lanjut cek kuota & bentrok — biar pesan errornya gak numpuk.
            if (!$this->start_date || !$this->end_date || !$this->leave_type_id) {
                return;
            }

            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);
            $requestedDays = $start->diffInDays($end) + 1;

            $leaveType = LeaveType::find($this->leave_type_id);

            if ($leaveType) {
                $usedDays = LeaveRequest::where('employee_id', $employee->id)
                    ->where('leave_type_id', $leaveType->id)
                    ->whereIn('status', ['pending', 'approved'])
                    ->whereYear('start_date', $start->year)
                    ->get()
                    ->sum(fn ($lr) => $lr->start_date->diffInDays($lr->end_date) + 1);

                if (($usedDays + $requestedDays) > $leaveType->default_quota) {
                    $sisaKuota = max($leaveType->default_quota - $usedDays, 0);
                    $validator->errors()->add(
                        'start_date',
                        "Sisa kuota {$leaveType->name} kamu cuma {$sisaKuota} hari, gak cukup buat {$requestedDays} hari yang diajukan."
                    );
                }
            }

            // Cek bentrok sama pengajuan lain yang masih pending/approved
            $overlap = LeaveRequest::where('employee_id', $employee->id)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_date', [$start, $end])
                        ->orWhereBetween('end_date', [$start, $end])
                        ->orWhere(function ($q2) use ($start, $end) {
                            $q2->where('start_date', '<=', $start)->where('end_date', '>=', $end);
                        });
                })
                ->exists();

            if ($overlap) {
                $validator->errors()->add('start_date', 'Tanggal ini bentrok dengan pengajuan cuti lain yang masih pending/approved.');
            }
        });
    }
}

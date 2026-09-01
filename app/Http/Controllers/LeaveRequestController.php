<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequestRequest;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Notifications\LeaveStatusUpdated;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return view('leave.index', [
                'employee' => null,
                'leaveTypes' => collect(),
                'requests' => null,
            ]);
        }

        $leaveTypes = LeaveType::orderBy('name')->get()->map(function ($type) use ($employee) {
            $used = LeaveRequest::where('employee_id', $employee->id)
                ->where('leave_type_id', $type->id)
                ->whereIn('status', ['pending', 'approved'])
                ->whereYear('start_date', now()->year)
                ->get()
                ->sum(fn ($lr) => $lr->start_date->diffInDays($lr->end_date) + 1);

            $type->used_quota = $used;
            $type->remaining_quota = max($type->default_quota - $used, 0);

            return $type;
        });

        $requests = LeaveRequest::with('leaveType')
            ->where('employee_id', $employee->id)
            ->orderByDesc('start_date')
            ->paginate(10);

        return view('leave.index', compact('employee', 'leaveTypes', 'requests'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::orderBy('name')->get();

        return view('leave.create', compact('leaveTypes'));
    }

    public function store(StoreLeaveRequestRequest $request)
    {
        $employee = Auth::user()->employee;

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('leave.index')->with('success', 'Pengajuan cuti berhasil dikirim, menunggu approval.');
    }

    public function cancel(LeaveRequest $leaveRequest)
    {
        $employee = Auth::user()->employee;

        abort_unless($employee && $leaveRequest->employee_id === $employee->id, 403);
        abort_unless($leaveRequest->status === 'pending', 422, 'Cuma pengajuan yang masih pending yang bisa dibatalkan.');

        $leaveRequest->delete();

        return back()->with('success', 'Pengajuan cuti dibatalkan.');
    }

    public function approvals()
    {
        $requests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->where('status', 'pending')
            ->orderBy('start_date')
            ->paginate(15);

        return view('leave.approvals', compact('requests'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        $leaveRequest->employee->user->notify(new LeaveStatusUpdated($leaveRequest));

        return back()->with('success', 'Pengajuan cuti disetujui.');
    }

    public function reject(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        $leaveRequest->employee->user->notify(new LeaveStatusUpdated($leaveRequest));

        return back()->with('success', 'Pengajuan cuti ditolak.');
    }
}

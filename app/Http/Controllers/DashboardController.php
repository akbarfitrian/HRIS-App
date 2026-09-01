<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            return $this->adminDashboard();
        }

        return $this->employeeDashboard($user);
    }

    private function adminDashboard()
    {
        $totalEmployees = Employee::where('status', 'active')->count();

        $today = now()->toDateString();
        $onLeaveToday = LeaveRequest::where('status', 'approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->count();

        $lateThisMonth = Attendance::where('status', 'late')
            ->whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->count();

        return view('dashboard-admin', compact('totalEmployees', 'onLeaveToday', 'lateThisMonth'));
    }

    private function employeeDashboard($user)
    {
        $employee = $user->employee;

        if (! $employee) {
            return view('dashboard-employee', [
                'employee' => null,
                'leaveTypes' => collect(),
                'latestPayroll' => null,
            ]);
        }

        $year = now()->year;

        $leaveTypes = LeaveType::all()->map(function ($type) use ($employee, $year) {
            $usedDays = LeaveRequest::where('employee_id', $employee->id)
                ->where('leave_type_id', $type->id)
                ->whereIn('status', ['pending', 'approved'])
                ->whereYear('start_date', $year)
                ->get()
                ->sum(fn ($leave) => $leave->start_date->diffInDays($leave->end_date) + 1);

            $type->remaining_quota = max($type->default_quota - $usedDays, 0);

            return $type;
        });

        $latestPayroll = $employee->payrolls()->orderByDesc('period')->first();

        return view('dashboard-employee', compact('employee', 'leaveTypes', 'latestPayroll'));
    }
}

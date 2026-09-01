<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Jam masuk standar — check-in lewat dari jam ini otomatis kehitung telat
    private const WORK_START_TIME = '08:00:00';

    public function index()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return view('attendance.index', [
                'employee' => null,
                'today' => null,
                'history' => null,
            ]);
        }

        $today = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        $history = Attendance::where('employee_id', $employee->id)
            ->orderByDesc('date')
            ->paginate(15);

        return view('attendance.index', compact('employee', 'today', 'history'));
    }

    public function checkIn()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return back()->with('error', 'Akun ini belum terhubung ke data karyawan.');
        }

        $today = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if ($today && $today->check_in) {
            return back()->with('error', 'Kamu sudah check-in hari ini.');
        }

        $now = Carbon::now();
        $status = $now->format('H:i:s') > self::WORK_START_TIME ? 'late' : 'present';

        Attendance::updateOrCreate(
            ['employee_id' => $employee->id, 'date' => Carbon::today()->toDateString()],
            ['check_in' => $now->format('H:i:s'), 'status' => $status]
        );

        return back()->with('success', 'Check-in berhasil dicatat jam ' . $now->format('H:i') . '.');
    }

    public function checkOut()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return back()->with('error', 'Akun ini belum terhubung ke data karyawan.');
        }

        $today = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if (!$today || !$today->check_in) {
            return back()->with('error', 'Kamu belum check-in hari ini.');
        }

        if ($today->check_out) {
            return back()->with('error', 'Kamu sudah check-out hari ini.');
        }

        $today->update(['check_out' => Carbon::now()->format('H:i:s')]);

        return back()->with('success', 'Check-out berhasil dicatat.');
    }

    public function recap(Request $request)
    {
        $validated = $request->validate([
            'month' => 'nullable|date_format:Y-m',
        ]);

        $month = $validated['month'] ?? Carbon::now()->format('Y-m');
        $period = Carbon::createFromFormat('Y-m', $month);

        $attendances = Attendance::with(['employee.user'])
            ->whereYear('date', $period->year)
            ->whereMonth('date', $period->month)
            ->orderByDesc('date')
            ->paginate(20)
            ->withQueryString();

        return view('attendance.recap', compact('attendances', 'month'));
    }
}

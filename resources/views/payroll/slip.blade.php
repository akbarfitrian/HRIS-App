<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Slip Gaji {{ $payroll->employee->employee_code }} - {{ $payroll->period }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 13px; color: #222; margin: 30px; }
        h1 { font-size: 18px; margin-bottom: 0; }
        .subtitle { color: #666; margin-top: 4px; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        td { padding: 6px 4px; }
        .label { color: #666; width: 55%; }
        .value { text-align: right; font-weight: bold; }
        .divider { border-top: 1px solid #ddd; margin: 16px 0; }
        .total-row td { font-size: 15px; padding-top: 10px; border-top: 2px solid #333; }
        .header-box { margin-bottom: 20px; }
        .header-box td { padding: 2px 4px; }
    </style>
</head>
<body>
    <h1>Slip Gaji</h1>
    <p class="subtitle">Periode {{ $payroll->period }}</p>

    <table class="header-box">
        <tr>
            <td class="label">Nama Karyawan</td>
            <td>{{ $payroll->employee->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kode Karyawan</td>
            <td>{{ $payroll->employee->employee_code }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Digenerate</td>
            <td>{{ optional($payroll->generated_at)->format('d M Y H:i') ?? '-' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td class="label">Gaji Pokok</td>
            <td class="value">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Tunjangan</td>
            <td class="value">+ Rp {{ number_format($payroll->total_allowance, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Potongan Tetap</td>
            <td class="value">- Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Potongan Keterlambatan</td>
            <td class="value">- Rp {{ number_format($payroll->late_deduction, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td class="label">Gaji Bersih (Take Home Pay)</td>
            <td class="value">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
        </tr>
    </table>

    <p style="margin-top: 32px; color: #999; font-size: 11px;">
        Dokumen ini dibuat otomatis oleh sistem HRIS dan sah tanpa tanda tangan basah.
    </p>
</body>
</html>

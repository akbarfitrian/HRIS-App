<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'employee_code',
        'department_id',
        'position_id',
        'phone',
        'address',
        'hire_date',
        'photo',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function salaryComponents()
    {
        return $this->hasMany(SalaryComponent::class);
    }

    public function latestSalaryComponent()
    {
        return $this->hasOne(SalaryComponent::class)->latestOfMany('effective_date');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances()
{
    return $this->hasMany(Attendance::class);
}

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}

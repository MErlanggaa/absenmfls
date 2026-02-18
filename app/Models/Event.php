<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'event_date',
        'end_date',
        'department_id',
        'created_by',
        'reminder_enabled',
        'is_active',
        'target_departments',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'end_date' => 'datetime',
        'reminder_enabled' => 'boolean',
        'is_active' => 'boolean',
        'target_departments' => 'array',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function location()
    {
        return $this->hasOne(EventLocation::class);
    }

    public function qrcodes()
    {
        return $this->hasMany(EventQrcode::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'qr_id',
        'check_in',
        'check_out',
        'status',
        'user_latitude',
        'user_longitude',
        'distance_meter',
        'device_info',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qrcode()
    {
        return $this->belongsTo(EventQrcode::class, 'qr_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventQrcode extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'code',
        'expired_at',
        'is_active',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function attendances() 
    {
        return $this->hasMany(Attendance::class, 'qr_id');
    }
}

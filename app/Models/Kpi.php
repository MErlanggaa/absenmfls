<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $fillable = [
        'user_id',
        'assessor_id',
        'period_date',
        'behavior_scores',
        'total_value',
        'index_score',
    ];

    protected $casts = [
        'behavior_scores' => 'array',
        'period_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class , 'assessor_id');
    }
}

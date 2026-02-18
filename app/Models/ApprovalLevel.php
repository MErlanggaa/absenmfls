<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLevel extends Model
{
    use HasFactory;

    protected $fillable = ['level_order', 'role_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

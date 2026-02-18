<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'department_id',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function approvalRequests()
    {
        return $this->hasMany(ApprovalRequest::class, 'created_by');
    }

    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLog::class, 'approved_by');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function isAdminIT(): bool
    {
        return $this->role->name === 'admin' && $this->department?->name === 'IT';
    }

    public function isAdministrasi(): bool
    {
        return $this->department?->name === 'Administrasi';
    }

    public function isKepalaDivisi(): bool
    {
        return $this->role->name === 'kepala_divisi';
    }

    public function isAnggota(): bool
    {
        return $this->role->name === 'anggota';
    }

    public function isSuperAdmin(): bool
    {
        return $this->email === 'admin@mfls.com';
    }

    /**
     * Permission Checks
     */
    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin() || $this->isAdminIT();
    }

    public function canManageEvents(): bool
    {
        return $this->isSuperAdmin() || $this->isAdminIT() || $this->isAdministrasi() || $this->role?->name === 'project_director';
    }

    public function canCreateApproval(): bool
    {
        return $this->isSuperAdmin() || 
               $this->isAdminIT() || 
               $this->isAdministrasi() || 
               $this->isKepalaDivisi() ||
               $this->role->name === 'vice_project_director' || 
               $this->role->name === 'project_director';
    }

    public function canViewAllAttendance(): bool
    {
        return $this->isSuperAdmin() || 
               $this->isAdminIT() || 
               $this->isAdministrasi() || 
               $this->role->name === 'vice_project_director' || 
               $this->role->name === 'project_director';
    }
}

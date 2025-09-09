<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;   // <- penting

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
        'department_id',
        'position_id',
        'work_at',
        'can_checklist',
        'admin',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'admin' => 'boolean',
        'can_checklist' => 'boolean',
        'email_verified_at' => 'datetime',
        'work_at' => 'integer',
        'department_id' => 'integer',
        'position_id' => 'integer',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function features(): array
    {
        if ($this->admin) {
            return ['hotels', 'restaurants', 'settings'];
        }

        $depName = strtolower($this->department->name ?? '');
        $depCode = strtoupper($this->department->code ?? '');
        $isDeveloper = ($depName === 'developer') || ($depCode === 'DEV');

        if ($isDeveloper) {
            return ['hotels', 'restaurants', 'settings'];
        }
        if ($depName === 'hotel') {
            return ['hotels'];
        }
        if ($depName === 'restaurant' || $depName === 'restoran') {
            return ['restaurants'];
        }
        return [];
    }
}

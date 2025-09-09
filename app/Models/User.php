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
        $depName = strtolower($this->department->name ?? '');
        $depCode = strtoupper($this->department->code ?? '');
        $isDeveloper = ($depName === 'developer') || ($depCode === 'DEV');

        // base features
        $features = [];
        if ($isDeveloper) {
            $features = ['hotels', 'restaurants', 'settings', 'approval']; // dev dapat approval
        } else {
            if ($depName === 'hotel') {
                $features[] = 'hotels';
            } elseif (in_array($depName, ['restaurant', 'restoran'])) {
                $features[] = 'restaurants';
            }

            // posisi: Manager/Supervisor -> approval
            $posName = strtolower($this->position->name ?? '');
            $posCode = strtoupper($this->position->code ?? '');
            $isMgrSpv = in_array($posName, ['manager', 'supervisor']) || in_array($posCode, ['MGR', 'SPV']);

            if ($isMgrSpv) {
                $features[] = 'approval';
            }
        }

        // pastikan unik & rapi
        return array_values(array_unique($features));
    }
}

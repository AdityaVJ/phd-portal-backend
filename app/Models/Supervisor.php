<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Supervisor extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'type',
        'phone',
        'is_active',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scholarSupervisors()
    {
        return $this->hasMany(ScholarSupervisor::class);
    }

    public function scholars()
    {
        return $this->belongsToMany(Scholar::class, 'scholars_supervisors')
            ->withPivot(['is_active', 'assigned_date', 'removal_date', 'assigned_by_admin_id'])
            ->withTimestamps();
    }

    /**
     * Scope a query to filter by Supervisor ID.
     */
    #[Scope]
    protected function type(Builder $query, ?string $typeName)
    {
        if (!is_null($typeName)) {
            $query->where('type', $typeName);
        }
    }

    #[Scope]
    protected function nameLike(Builder $query, ?string $name)
    {
        if ($name) {
            $query->where('name', 'like', "%$name%");
        }
    }

    #[Scope]
    protected function isActive(Builder $query, ?bool $active)
    {
        if (!is_null($active)) {
            $query->where('is_active', $active);
        }
    }
}

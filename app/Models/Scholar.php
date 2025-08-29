<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class Scholar extends Authenticatable
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
        'registration_number',
        'registration_date',
        'is_active',
        'is_scholarship_complete',
        'phone',
        'password',
        'gender',
        'date_of_birth',
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

    public function scholarFlags()
    {
        return $this->hasOne(ScholarFlags::class);
    }

    public function communicationRecords()
    {
        return $this->hasMany(CommunicationRecord::class);
    }

    public function scholarSupervisors()
    {
        return $this->hasMany(ScholarSupervisor::class);
    }

    // Current active supervisor
    public function activeSupervisor()
    {
        return $this->hasOne(ScholarSupervisor::class)
            ->where('is_active', true)
            ->with('supervisor');
    }

    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'scholars_supervisors')
            ->withPivot(['is_active', 'assigned_date', 'removal_date', 'assigned_by_admin_id'])
            ->withTimestamps();
    }

    public function details()
    {
        return DB::table('scholar_details')->where('scholar_id', $this->id)->first();
    }

    public function saveDetails(array $data)
    {
        // check if exists
        $exists = DB::table('scholar_details')->where('scholar_id', $this->id)->exists();

        if ($exists) {
            DB::table('scholar_details')
                ->where('scholar_id', $this->id)
                ->update(array_merge($data, [
                    'updated_at' => now(),
                ]));
        } else {
            DB::table('scholar_details')->insert(array_merge($data, [
                'scholar_id' => $this->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Scope a query to filter by Supervisor ID.
     */
    #[Scope]
    protected function fromSupervisor(Builder $query, string $supervisorID)
    {
        $query->whereHas('activeSupervisor', function ($q) use ($supervisorID) {
            $q->where('supervisor_id', $supervisorID)
                ->where('is_active', true);
        });
    }

    #[Scope]
    protected function registeredBetween(Builder $query, ?string $startDate, ?string $endDate): void
    {
        if ($startDate && $endDate) {
            $query->whereBetween('registration_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('registration_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('registration_date', '<=', $endDate);
        }
    }

}

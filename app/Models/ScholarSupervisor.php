<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScholarSupervisor extends Model
{
    use SoftDeletes;

    protected $table = 'scholars_supervisors';

    protected $fillable = [
        'scholar_id',
        'supervisor_id',
        'is_active',
        'assigned_date',
        'removal_date',
        'assigned_by_admin_id',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(Admin::class, 'assigned_by_admin_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunicationTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'message', 'partner_template_id', 'type'
    ];

    public function communicationRecords()
    {
        return $this->hasMany(CommunicationRecord::class);
    }

}

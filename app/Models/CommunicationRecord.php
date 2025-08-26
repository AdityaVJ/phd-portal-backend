<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunicationRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['scholar_id', 'communication_template_id'];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function communicationTemplate()
    {
        return $this->belongsTo(CommunicationTemplate::class);
    }
}

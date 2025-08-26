<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScholarFlags extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'scholar_id',
        'step_1_complete',
        'step_2_complete',
        'step_3_complete',
        'step_4_complete',
        'step_5_complete',
        'rac_6_approval',
        'drc_6_approval',
        'step_6_complete',
        'rac_7_approval',
        'drc_7_approval',
        'step_7_complete',
        'step_8a_complete',
        'step_8b_complete',
        'step_9_complete',
        'step_10_complete',
        'step_11_complete',
        'step_12_complete',
    ];

    function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}

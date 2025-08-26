<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScholarFlags extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'scholar_id',
        'secondary_school_name',
        'secondary_school_year',
        'secondary_school_subjects',
        'secondary_school_aggregate',
        'secondary_school_grade',
        'secondary_school_board',
        'hs_school_name',
        'hs_school_year',
        'hs_school_board',
        'hs_school_subjects',
        'hs_school_aggregate',
        'hs_school_grade',
        'grad_course',
        'grad_pass_year',
        'grad_university',
        'grad_aggregate',
        'grad_subject',
        'grad_grade',
        'post_grad_course',
        'post_grad_pass_year',
        'post_grad_university',
        'post_grad_aggregrate',
        'post_grad_subject',
        'post_grad_grade',
        'address',
        'city',
        'state',
        'pincode'
    ];
}

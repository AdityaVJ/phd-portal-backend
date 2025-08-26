<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scholar_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained('scholars');
            $table->string('secondary_school_name')->nullable();
            $table->string('secondary_school_year')->nullable();
            $table->string('secondary_school_subjects')->nullable();
            $table->string('secondary_school_aggregate')->nullable();
            $table->string('secondary_school_grade')->nullable();
            $table->string('secondary_school_board')->nullable();
            $table->string('hs_school_name')->nullable();
            $table->string('hs_school_year')->nullable();
            $table->string('hs_school_board')->nullable();
            $table->string('hs_school_subjects')->nullable();
            $table->string('hs_school_aggregate')->nullable();
            $table->string('hs_school_grade')->nullable();
            $table->string('grad_course')->nullable();
            $table->string('grad_pass_year')->nullable();
            $table->string('grad_university')->nullable();
            $table->string('grad_aggregate')->nullable();
            $table->string('grad_subject')->nullable();
            $table->string('grad_grade')->nullable();
            $table->string('post_grad_course')->nullable();
            $table->string('post_grad_pass_year')->nullable();
            $table->string('post_grad_university')->nullable();
            $table->string('post_grad_aggregrate')->nullable();
            $table->string('post_grad_subject')->nullable();
            $table->string('post_grad_grade')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholar_details');
    }
};

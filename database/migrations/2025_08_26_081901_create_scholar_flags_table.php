<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scholar_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained('scholars');
            $table->boolean('step_1_complete')->default(false);
            $table->boolean('step_2_complete')->default(false);
            $table->boolean('step_3_complete')->default(false);
            $table->boolean('step_4_complete')->default(false);
            $table->boolean('step_5_complete')->default(false);
            $table->boolean('rac_6_approval')->default(false);
            $table->boolean('drc_6_approval')->default(false);
            $table->boolean('step_6_complete')->default(false);
            $table->boolean('rac_7_approval')->default(false);
            $table->boolean('drc_7_approval')->default(false);
            $table->boolean('step_7_complete')->default(false);
            $table->boolean('step_8a_complete')->default(false);
            $table->boolean('step_8b_complete')->default(false);
            $table->boolean('step_9_complete')->default(false);
            $table->boolean('step_10_complete')->default(false);
            $table->boolean('step_11_complete')->default(false);
            $table->boolean('step_12_complete')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholar_flags');
    }
};

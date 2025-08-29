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
        Schema::create('scholars_supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained('scholars');
            $table->foreignId('supervisor_id')->constrained('supervisors');
            $table->boolean('is_active')->default(true);
            $table->date('assigned_date');
            $table->date('removal_date')->nullable();
            $table->foreignId('assigned_by_admin_id')->constrained('admins');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholars_supervisors');
    }
};

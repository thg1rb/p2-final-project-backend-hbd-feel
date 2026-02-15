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
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('student_id');
            $table->uuid('event_id');
            $table->string('path');
            $table->uuid('award_id');
            $table->json('documents');
            $table->integer('year');
            $table->double('grade');

            $table->enum('status', ['SUBMITTED'])->default('SUBMITTED');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};

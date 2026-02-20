<?php

use App\Enums\ApplicationStatus;
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
            $table->foreign('student_id')
                ->references('student_id')
                ->on('users')
                ->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->foreignId('award_id')->constrained()->cascadeOnDelete();
            $table->json('documents');
            $table->integer('year');
            $table->double('grade');

            $table->enum('status', ApplicationStatus::cases())->default('SUBMITTED');

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

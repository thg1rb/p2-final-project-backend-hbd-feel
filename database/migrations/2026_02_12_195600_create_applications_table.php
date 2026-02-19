<?php

use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
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

            $table->tinyInteger('level')->default(RoleLevel::NISIT->value);;
            $table->enum('status', array_map(fn ($case) => $case->value, ApprovalStatus::cases()))->default('APPROVED');

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

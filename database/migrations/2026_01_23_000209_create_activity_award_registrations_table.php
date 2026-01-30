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
        Schema::create('activity_award_registrations', function (Blueprint $table) {
            $table->id();

            $table->json('activity_types');

            $table->date('award_date');
            $table->string('project_name');
            $table->string('team_name');
            $table->string('work_name');
            $table->string('award_name');
            $table->string('organizer');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_award_registrations');
    }
};

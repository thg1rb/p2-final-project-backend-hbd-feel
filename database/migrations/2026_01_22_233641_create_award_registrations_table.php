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
        Schema::create('award_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->foreignIdFor(\App\Models\Award::class)->constrained();
            $table->foreignIdFor(\App\Models\Event::class)->constrained();
//            $table->string('type'); // เก็บชื่อ Class เช่น App\Models\ActivityAward
//            $table->json('additional_info')->nullable(); // ข้อมูลเฉพาะด้าน
            $table->string('status')->default('waiting_for_documents');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('academic_year');
            $table->json('documents');
//            $table->string('award_type');
            $table->morphs('awardable');
            $table->timestamps();
            $table->softDeletes();

            // กฎ: 1 คน สมัครได้เพียง 1 ประเภทรางวัล ต่อ 1 รอบ (Event)
//            $table->unique(['user_id', 'event_id'], 'user_event_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('award_registrations');
    }
};

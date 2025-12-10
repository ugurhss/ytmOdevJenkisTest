<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Olay tipi (örnek: group_created, student_added_to_group, announcement_created)
            $table->string('event')->index();

            // İnsan tarafından okunabilir açıklama
            $table->text('description');

            // Olayı tetikleyen kullanıcı (opsiyonel)
            $table->foreignId('actor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('actor_name')->nullable();

            // İlgili kayıt (polymorphic subject)
            $table->string('subject_type')->nullable(); // App\Models\Group, App\Models\User vb.
            $table->unsignedBigInteger('subject_id')->nullable();

            // Ek bilgiler için JSON
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

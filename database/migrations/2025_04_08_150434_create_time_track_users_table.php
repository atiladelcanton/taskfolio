<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_track_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->decimal('total_hours', 5, 2);
            $table->timestamps();

            $table->unique(['task_id', 'user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_track_users');
    }
};

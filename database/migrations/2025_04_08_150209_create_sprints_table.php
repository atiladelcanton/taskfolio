<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sprints', function (Blueprint $table) {
            $table->id();
            $table->string('sprint_code')->unique();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->enum('status', ['ongoing', 'completed', 'running', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sprints');
    }
};

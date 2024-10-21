<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('sprint_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('collaborator_id')->constrained();
            $table->string('task_code');
            $table->integer('priority')->comment('1 - Low, 2 - Medium, 3 - High');
            $table->integer('type')->comment('1 - Bug, 2 - Feature');
            $table->integer('order')->default(1)->nullable();
            $table->string('name');
            $table->longText('description');
            $table->integer('status')->default(1)->comment('1 - BackLog, 2 - In Progress, 3 - Validation, 4 - Correction,4 - Completed');
            $table->decimal('total_hours');
            $table->string('evidences')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->integer('priority')->comment('1 - Low, 2 - Medium, 3 - High');
            $table->integer('type')->comment('1 - Bug, 2 - Feature');
            $table->string('name');
            $table->longText('description');
            $table->integer('status')->default(1)->comment('1 - BackLog, 2 - In Progress, 3 - Validation, 4 - Correction,4 - Completed');
            $table->decimal('total_hours');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

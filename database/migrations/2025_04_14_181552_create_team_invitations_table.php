<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignId('team_id')->constrained('teams');
            $table->string('billing_type');
            $table->integer('billing_rate');
            $table->integer('status');
            $table->string('invitation_code');
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_invitations');
    }
};

<?php

declare(strict_types=1);

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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('type_billing')->default(0)->comment('0 - free, 1 - monthly, 2 - hour');
            $table->float('price')->default(0)->comment('price for monthly or hour');
            $table->string('avatar')->nullable()->comment('avatar for user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type_billing');
            $table->dropColumn('price');
            $table->dropColumn('avatar');
        });
    }
};

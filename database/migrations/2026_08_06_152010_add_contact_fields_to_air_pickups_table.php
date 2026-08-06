<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_pickups', function (Blueprint $table) {
            $table->string('fullName')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('concern')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('air_pickups', function (Blueprint $table) {
            $table->dropColumn(['fullName', 'whatsapp', 'concern']);
        });
    }
};

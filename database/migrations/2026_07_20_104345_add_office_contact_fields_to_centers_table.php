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
        Schema::table('centers', function (Blueprint $table) {
            $table->text('floor_map')->nullable()->after('treatments');
            $table->text('operational_hours')->nullable()->after('floor_map');
            $table->string('whatsapp_hotline')->nullable()->after('operational_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('centers', function (Blueprint $table) {
            $table->dropColumn(['floor_map', 'operational_hours', 'whatsapp_hotline']);
        });
    }
};

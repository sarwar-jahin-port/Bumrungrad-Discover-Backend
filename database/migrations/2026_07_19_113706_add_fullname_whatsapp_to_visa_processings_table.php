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
        Schema::table('visa_processings', function (Blueprint $table) {
            $table->string('fullName')->nullable()->after('id');
            $table->string('whatsapp')->nullable()->after('fullName');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visa_processings', function (Blueprint $table) {
            $table->dropColumn(['fullName', 'whatsapp']);
        });
    }
};

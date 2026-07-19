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
        Schema::table('tele_medicines', function (Blueprint $table) {
            $table->string('patientType')->nullable()->after('fullName');
            $table->string('timeSlot')->nullable()->after('preferredDoctor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tele_medicines', function (Blueprint $table) {
            $table->dropColumn(['patientType', 'timeSlot']);
        });
    }
};

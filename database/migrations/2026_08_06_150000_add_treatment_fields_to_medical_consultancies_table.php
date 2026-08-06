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
        Schema::table('medical_consultancies', function (Blueprint $table) {
            $table->string('birthDate')->nullable();
            $table->string('patientType')->nullable();
            $table->text('specificConcern')->nullable();
            $table->string('passport')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_consultancies', function (Blueprint $table) {
            $table->dropColumn(['birthDate', 'patientType', 'specificConcern', 'passport']);
        });
    }
};

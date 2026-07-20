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
        Schema::create('air_ambulance_hubs', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('office_name')->nullable();
            $table->string('building')->nullable();
            $table->text('floor_map')->nullable();
            $table->text('address')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('whatsapp_hotline')->nullable();
            $table->text('operational_hours')->nullable();
            $table->text('map_embed_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('air_ambulance_hubs');
    }
};

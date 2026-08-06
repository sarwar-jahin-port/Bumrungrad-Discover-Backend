<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_interpreters', function (Blueprint $table) {
            $table->id();
            $table->string('fullName')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('concern')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_interpreters');
    }
};

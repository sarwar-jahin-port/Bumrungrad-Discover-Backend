<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The RegisterController stores firstName/lastName/phone/etc. and never
     * sets the default "name" column. Production's `users` table doesn't have
     * a `name` column at all, so it's dropped rather than kept nullable.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstName')->nullable()->after('id');
            $table->string('lastName')->nullable()->after('firstName');
            $table->string('phone')->nullable()->after('email');
            $table->string('gender')->nullable()->after('phone');
            $table->string('dob')->nullable()->after('gender');
            $table->string('country')->nullable()->after('dob');
            $table->string('citizenship')->nullable()->after('country');
            $table->string('passport')->nullable()->after('citizenship');
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'firstName', 'lastName', 'phone', 'gender', 'dob', 'country', 'citizenship', 'passport',
            ]);
            $table->string('name')->nullable();
        });
    }
};

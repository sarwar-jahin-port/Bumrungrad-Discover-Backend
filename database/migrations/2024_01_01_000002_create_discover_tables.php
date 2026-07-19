<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Schema synced against a production dump of `discoverinternat_medical_service`
 * (phpMyAdmin export, MariaDB 11.4.12, 2026-07-18) to replace the earlier
 * reverse-engineered version. Column presence/naming matches production;
 * primary keys use Laravel's bigint `id()` rather than production's `int`
 * for consistency with the rest of the app's conventions.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->timestamps();
        });

        Schema::create('sub_specialties', function (Blueprint $table) {
            $table->id();
            $table->string('specialty')->nullable();
            $table->string('sub_specialty')->nullable();
            $table->timestamps();
        });

        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('cover_photo')->nullable();
            $table->mediumText('specialty')->nullable();
            $table->mediumText('sub_specialty')->nullable();
            $table->mediumText('lang')->nullable();
            $table->mediumText('gender')->nullable();
            $table->longText('school')->nullable();
            $table->longText('schools')->nullable();
            $table->longText('certificates')->nullable();
            $table->longText('fellowships')->nullable();
            $table->longText('interests')->nullable();
            $table->longText('experiences')->nullable();
            $table->longText('researches')->nullable();
            $table->longText('article')->nullable();
            $table->longText('trainings')->nullable();
            $table->longText('schedule')->nullable();
            $table->mediumText('day')->nullable();
            $table->mediumText('arrival')->nullable();
            $table->mediumText('leave')->nullable();
            $table->mediumText('location')->nullable();
            $table->longText('shift')->nullable();
            $table->timestamps();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('cover_photo')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        Schema::create('sub_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->integer('price')->nullable();
            $table->string('location')->nullable();
            $table->longText('content')->nullable();
            $table->string('shift1')->nullable();
            $table->string('shift2')->nullable();
            $table->longText('conditions')->nullable();
            $table->longText('inclusions')->nullable();
            $table->longText('exclusions')->nullable();
            $table->text('cover_photo')->nullable();
            $table->timestamps();
        });

        Schema::create('centers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('cover_photo')->nullable();
            $table->text('location')->nullable();
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->longText('informations')->nullable();
            $table->longText('conditions')->nullable();
            $table->longText('treatments')->nullable();
            $table->timestamps();
        });

        Schema::create('air_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('booking_date')->nullable();
            $table->string('country')->nullable();
            $table->text('doc')->nullable();
            $table->string('destination')->nullable();
            $table->string('return_date')->nullable();
            $table->timestamps();
        });

        Schema::create('air_pickups', function (Blueprint $table) {
            $table->id();
            $table->text('appointment')->nullable();
            $table->text('air_ticket')->nullable();
            $table->integer('passenger')->nullable();
            $table->timestamps();
        });

        Schema::create('air_ambulances', function (Blueprint $table) {
            $table->id();
            $table->string('entry_date')->nullable();
            $table->text('passport_copy')->nullable();
            $table->mediumText('summary')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        Schema::create('order_medicines', function (Blueprint $table) {
            $table->id();
            $table->string('phoneNumber')->nullable();
            $table->string('address')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('prescription')->nullable();
            $table->longText('medicines')->nullable();
            $table->longText('quantity')->nullable();
            $table->timestamps();
        });

        Schema::create('tele_medicines', function (Blueprint $table) {
            $table->id();
            $table->string('fullName')->nullable();
            $table->string('hnNum')->nullable();
            $table->string('birthDate')->nullable();
            $table->string('passportId')->nullable();
            $table->string('nationality')->nullable();
            $table->string('residence')->nullable();
            $table->string('preferredDate')->nullable();
            $table->string('preferredDoctor')->nullable();
            $table->string('purposeAppointment')->nullable();
            $table->text('investigationDocument')->nullable();
            $table->string('contactDetails')->nullable();
            $table->string('paymentType')->nullable();
            $table->text('epaymentlink')->nullable();
            $table->string('interpreter')->nullable();
            $table->string('specificConcern')->nullable();
            $table->timestamps();
        });

        // NOTE: production's `updated_at` column is actually named `update_at`
        // (typo baked into the live schema) — kept as-is so the model/DB agree.
        Schema::create('medicalreports', function (Blueprint $table) {
            $table->id();
            $table->text('passport')->nullable();
            $table->string('hnNum')->nullable();
            $table->longText('caseSummary')->nullable();
            $table->string('name')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('update_at')->nullable()->useCurrent();
        });

        Schema::create('doctorappoinments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('specialty')->nullable();
            $table->string('subSpecialty')->nullable();
            $table->string('doctor')->nullable();
            $table->string('medicalDesc')->nullable();
            $table->string('selectedDate')->nullable();
            $table->string('selectedDate2')->nullable();
            $table->string('shift')->nullable();
            $table->string('shift2')->nullable();
            $table->string('HnNumber')->nullable();
            $table->string('PataientFirstName')->nullable();
            $table->string('PataientLastName')->nullable();
            $table->string('PataientCitizenship')->nullable();
            $table->string('PataientGender')->nullable();
            $table->string('PataientEmail')->nullable();
            $table->string('PataientPhone')->nullable();
            $table->string('PataientDob')->nullable();
            $table->string('RequestorFirstname')->nullable();
            $table->string('RequestorLastName')->nullable();
            $table->string('RequestorEmail')->nullable();
            $table->string('RequestorPhone')->nullable();
            $table->string('RequestoerRelation')->nullable();
            $table->string('mediicalCorncern')->nullable();
            $table->string('country')->nullable();
            $table->string('passport')->nullable();
            $table->string('medicalReport1')->nullable();
            $table->string('medicalReport2')->nullable();
            $table->string('medicalReport3')->nullable();
            $table->string('oldPataint')->nullable();
            $table->string('firstSiftTime')->nullable();
            $table->string('SecondSiftTime')->nullable();
            $table->string('driveLink1')->nullable();
            $table->string('driveLink2')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('inquery')->nullable();
            $table->text('doctorName')->nullable();
            $table->string('treatmentInterest')->nullable();
            $table->string('bumRungradOffice')->nullable();
            $table->text('question')->nullable();
            $table->string('hospitalNumber')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('email')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('birtDate')->nullable();
            $table->string('gender')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });

        Schema::create('health_check_ups', function (Blueprint $table) {
            $table->id();
            $table->string('healtePackage')->nullable();
            $table->string('prefferdDoctor')->nullable();
            $table->string('specialty')->nullable();
            $table->string('appoinMentDate')->nullable();
            $table->string('appoinMentTime')->nullable();
            $table->string('medicalConcern')->nullable();
            $table->string('HnNumber')->nullable();
            $table->string('patientName')->nullable();
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport')->nullable();
            $table->string('other_doc')->nullable();
            $table->timestamps();
        });

        Schema::create('package_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('packageName')->nullable();
            $table->integer('packagePrice')->nullable();
            $table->string('patientName')->nullable();
            $table->string('hnNumber')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('visa_processings', function (Blueprint $table) {
            $table->id();
            $table->string('oldPataint')->nullable();
            $table->string('HnNumber')->nullable();
            $table->string('PataientFirstName')->nullable();
            $table->string('PataientLastName')->nullable();
            $table->string('PataientCitizenship')->nullable();
            $table->string('PataientGender')->nullable();
            $table->string('PataientEmail')->nullable();
            $table->string('PataientPhone')->nullable();
            $table->string('PataientDob')->nullable();
            $table->string('country')->nullable();
            $table->string('mediicalCorncern')->nullable();
            $table->string('passport')->nullable();
            $table->string('medicalReport1')->nullable();
            $table->string('medicalReport2')->nullable();
            $table->string('invitationLetter')->nullable();
            $table->string('driveLink1')->nullable();
            $table->string('driveLink2')->nullable();
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('newsTitle')->nullable();
            $table->string('newsImage')->nullable();
            $table->longText('newsDescription')->nullable();
            $table->mediumText('newsSlogan')->nullable();
            $table->timestamps();
        });

        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('region')->nullable();
            $table->string('blogTitle')->nullable();
            $table->string('slug')->nullable();
            $table->string('blogImage')->nullable();
            $table->longText('blogDescription')->nullable();
            $table->timestamps();
        });

        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('case_summary')->nullable();
            $table->string('date')->nullable();
            $table->string('passport')->nullable();
            $table->longText('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('news');
        Schema::dropIfExists('visa_processings');
        Schema::dropIfExists('package_bookings');
        Schema::dropIfExists('health_check_ups');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('doctorappoinments');
        Schema::dropIfExists('medicalreports');
        Schema::dropIfExists('tele_medicines');
        Schema::dropIfExists('order_medicines');
        Schema::dropIfExists('air_ambulances');
        Schema::dropIfExists('air_pickups');
        Schema::dropIfExists('air_tickets');
        Schema::dropIfExists('centers');
        Schema::dropIfExists('sub_packages');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('sub_specialties');
        Schema::dropIfExists('specialties');
    }
};

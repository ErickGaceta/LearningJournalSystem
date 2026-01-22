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
        // CREATE POSTIONS TABLE
        Schema::create('position', function (Blueprint $table) {
            $table->id();
            $table->string('position', 255);
            $table->timestamps();
        });

        // CREATE DIVISION TABLE
        Schema::create('division_unit', function (Blueprint $table) {
            $table->id();
            $table->string('division_unit', 255);
            $table->timestamps();
        });

        // CREATE USERS TABLE
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 191)->unique()->default('');
            $table->string('first_name', 191)->default('');
            $table->string('middle_name', 191)->nullable()->default(null);
            $table->string('last_name', 191)->default('');
            $table->string('gender', 191)->default('Not specified');
            $table->foreignId('id_position')->nullable()->constrained('position')->onDelete('cascade')->default(1);
            $table->foreignId('id_division_unit')->nullable()->constrained('division_unit')->onDelete('cascade')->default(1);
            $table->string('employee_type', 191)->default('Regular');
            $table->string('roles', 191)->default('user');
            $table->string('username', 191)->unique()->default('');
            $table->string('email', 191)->unique()->default('');
            $table->timestamp('email_verified_at')->nullable()->default(null);
            $table->string('password', 191)->default('');
            $table->timestamp('last_login')->nullable()->default(null);
            $table->tinyInteger('is_active')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // CREATE DOCUMENT TABLE
        Schema::create('document', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('fullname');
            $table->text('title');
            $table->integer('hours');
            $table->date('date');
            $table->string('venue');
            $table->string('registration_fee', 100);
            $table->string('travel_expenses', 100);
            $table->text('topics');
            $table->text('insights');
            $table->text('application');
            $table->text('challenges');
            $table->text('appreciation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

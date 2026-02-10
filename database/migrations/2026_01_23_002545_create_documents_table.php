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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('fullname');
            $table->string('travel_expenses', 100)->default('N/A')->nullable();
            $table->text('topics');
            $table->text('insights');
            $table->text('application');
            $table->text('challenges');
            $table->text('appreciation');
            $table->integer('isPrinted');
            $table->foreignId('module_id')->constrained('training_modules')->onDelete('cascade');
            $table->date('printedAt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

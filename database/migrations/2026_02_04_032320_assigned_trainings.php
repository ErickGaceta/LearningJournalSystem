<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->text('employee_name');
            $table->foreignId('id')->nullable()->constrained('users')->onDelete('cascade')->default(1);
            $table->string('training_module');
            $table->foreignId('id', 100)->nullable()->constrained('training_module')->onDelete('cascade')->default(1);
            $table->timestamps();
        });
    }
};

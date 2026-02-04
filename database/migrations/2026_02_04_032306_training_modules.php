<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_module', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('hours');
            $table->date('datestart');
            $table->date('dateend');
            $table->string('venue');
            $table->string('conductedby', 100);
            $table->string('registration_fee', 100);
            $table->string('travel_expenses', 100);
            $table->timestamps();
        });
    }

};

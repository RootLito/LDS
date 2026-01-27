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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('status');
            $table->string('duration'); 
            $table->string('conducted_by');
            $table->string('charging_of_funds')->nullable();
            $table->text('name_of_nominees')->nullable();
            $table->integer('number_of_nominees')->nullable();
            $table->string('endorsed_by')->nullable();
            $table->string('hrdc_resolution_no')->nullable();
            $table->string('applicable_for')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};

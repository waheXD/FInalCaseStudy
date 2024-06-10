<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->integer('weight');
            $table->integer('height');
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnUpdate();
            $table->foreignId('doctor_id')->references('id')->on('users')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
};

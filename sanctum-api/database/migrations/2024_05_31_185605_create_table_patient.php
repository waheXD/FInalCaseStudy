<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patient', function (Blueprint $table) {
            $table->id();
            $table->date('birthday');
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('weight');
            $table->string('height');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient');
    }
};

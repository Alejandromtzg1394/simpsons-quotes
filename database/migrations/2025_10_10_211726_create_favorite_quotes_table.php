<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('favorite_quotes', function (Blueprint $table) {
            $table->id();
            $table->text('quote');
            $table->string('character');
            $table->string('image')->nullable();
            $table->string('character_direction')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorite_quotes');
    }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Settings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('website');
            $table->string('twitter');
            $table->string('instgram');
            $table->string('snapchat');
            $table->string('mobile');
            $table->string('phone');
            $table->string('logo');
            $table->longText('arconditions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

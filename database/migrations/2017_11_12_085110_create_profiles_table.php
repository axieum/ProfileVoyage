<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('link', 32)->unique();
            $table->string('name', 16);

            $table->string('display_name');
            $table->string('motto', 255)->default(null)->nullable();
            $table->string('avatar')->default(null)->nullable();
            $table->date('date_of_birth')->default(null)->nullable();
            $table->string('location')->default(null)->nullable();
            $table->string('country')->default(null)->nullable();

            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
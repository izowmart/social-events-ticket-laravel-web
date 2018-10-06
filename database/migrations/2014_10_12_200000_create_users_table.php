<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable()->unique();
            $table->string('year_of_birth')->nullable();
            $table->integer('gender')->comment("1: Male, 2: Female")->nullable();
            $table->string('profile_url')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('fcm_token')->default("0");
            $table->boolean('auto_follow_status')->default(true)->comment('true: follow is automatic; false: send me a follow request');
            $table->string('app_version_code')->default("1.0.0");
            $table->string('password');
            $table->integer('status')->default(1)->comment('0- inactive, 1 - active, 2 - deactivated');
            $table->boolean('first_time_login')->default(true);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned();
            $table->foreign('admin_id')->references('id')->on('admins');
            $table->string('title');
            $table->text('description');
            $table->string('image_url');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('status')->default(1)->comment('0- inactive, 1 - active, 2 - deactivated');
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
        Schema::dropIfExists('adverts');
    }
}

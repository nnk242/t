<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fb_page_id');
            $table->string('user_id_fb_page_id')->unique();
            $table->string('name');
            $table->text('picture')->nullable();
            $table->string('category')->nullable();
            $table->string('access_token', 255);
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('pages');
    }
}

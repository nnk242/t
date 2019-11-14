<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFbPagesTable extends Migration
{
    public function up()
    {
        #2016433678466136?fields=gender,first_name,last_name,name,id,locale,timezone
        Schema::connection('mongodb')->create('user_fb_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('page_id');
            $table->foreign('page_id')->references('_id')->on('pages')->onDelete('cascade');
            $table->string('user_fb_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('profile_pic')->nullable();
            $table->string('gender')->nullable();
            $table->string('locale')->nullable();
            $table->float('timezone')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('user_messages');
    }
}

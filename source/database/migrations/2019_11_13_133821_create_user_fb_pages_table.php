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
            $table->index('id');
            $table->index('m_user_fb_id');
            $table->string('page_id');
            $table->foreign('page_id')->references('_id')->on('pages')->onDelete('cascade');
            $table->string('user_fb_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('name');
            $table->string('profile_pic')->nullable();
            $table->string('gender')->nullable();
            $table->string('locale')->nullable();
            $table->integer('timezone')->nullable();
            $table->timestamps();

//            $table->index('page_id');
//            $table->index('user_fb_id');
//            $table->index('first_name');
//            $table->index('name');
//            $table->index('profile_pic');
//            $table->index('gender');
//            $table->index('locale');
//            $table->index('timezone');
//
//            $table->index(['page_id', 'user_fb_id', 'first_name', 'last_name', 'name', 'profile_pic', 'gender', 'locale', 'timezone']);
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('user_fb_pages');
    }
}

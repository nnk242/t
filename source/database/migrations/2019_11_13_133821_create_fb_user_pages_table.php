<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbUserPagesTable extends Migration
{
    public function up()
    {
        #2016433678466136?fields=gender,first_name,last_name,name,id,locale,timezone
        Schema::connection('mongodb')->create('fb_user_pages', function (Blueprint $table) {
            $table->index('id');
            ##$fb_page_id + '_' + $page_user_id
            $table->index('m_page_user_id');
            $table->string('fb_page_id');
            $table->foreign('fb_page_id')->references('fb_page_id')->on('pages')->onDelete('cascade');
            $table->string('user_fb_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('name');
            $table->string('profile_pic')->nullable();
            $table->string('gender')->nullable();
            $table->string('locale')->nullable();
            $table->integer('timezone')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('fb_user_pages');
    }
}

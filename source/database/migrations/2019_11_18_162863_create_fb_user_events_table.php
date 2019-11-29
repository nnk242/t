<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbUserEventsTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('fb_user_events', function (Blueprint $table) {
            $table->index('id');
            $table->string('bot_message_head_id');
            $table->foreign('bot_message_head_id')->references('_id')->on('bot_message_heads')->onDelete('cascade');
            $table->string('fb_page_id');
            $table->foreign('fb_page_id')->references('fb_page_id')->on('pages')->onDelete('cascade');
            $table->string('user_fb_id');
            $table->foreign('user_fb_id')->references('user_fb_id')->on('fb_user_pages')->onDelete('cascade');
            $table->string('gift');
            $table->string('message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('fb_user_events');
    }
}

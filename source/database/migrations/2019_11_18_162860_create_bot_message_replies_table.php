<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotMessageRepliesTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('bot_message_replies', function (Blueprint $table) {
            $table->index('id');
            $table->string('type_message');
            $table->string('type_notify');
            $table->string('bot_message_head_id')->nullable();
            $table->foreign('bot_message_head_id')->references('_id')->on('bot_message_heads')->onDelete('cascade');
            $table->string('fb_page_id');
            $table->foreign('fb_page_id')->references('fb_page_id')->on('pages')->onDelete('cascade');
            ##normal
            $table->text('text')->nullable();

            ##Timer
            $table->string('begin_time_open')->nullable();
            $table->string('end_time_open')->nullable();
            $table->string('begin_time_active')->nullable();
            $table->string('end_time_active')->nullable();
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('bot_message_replies');
    }
}

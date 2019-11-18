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
            $table->enum('type', ['normal', 'quick_replies']);
            $table->string('message_template_text_id')->nullable();
            $table->foreign('message_template_text_id')->references('_id')->on('message_template_texts')->onDelete('cascade');
            $table->string('fb_page_id');
            $table->foreign('fb_page_id')->references('fb_page_id')->on('pages')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('bot_message_replies');
    }
}

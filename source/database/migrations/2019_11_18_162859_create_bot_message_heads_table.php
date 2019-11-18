<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotMessageHeadsTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('bot_message_heads', function (Blueprint $table) {
            $table->index('id');
            $table->text('text');
            $table->string('fb_page_id');
            $table->foreign('fb_page_id')->references('fb_page_id')->on('pages')->onDelete('cascade');
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('bot_message_heads');
    }
}

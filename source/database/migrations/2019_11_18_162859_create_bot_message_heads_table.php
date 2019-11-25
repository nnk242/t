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
            $table->string('text');
            $table->string('fb_page_id');
            $table->foreign('fb_page_id')->references('fb_page_id')->on('pages')->onDelete('cascade');
            #message
            $table->string('type_event')->nullable();
            $table->string('text_success_id')->nullable();
            $table->string('text_error_begin_time_active_id')->nullable();
            $table->string('text_error_end_time_active_id')->nullable();
            $table->string('text_error_time_open_id')->nullable();
            $table->string('text_error_gift_id')->nullable();

            ##Timer
            $table->integer('begin_time_open')->nullable();
            $table->integer('end_time_open')->nullable();
            $table->integer('begin_time_active')->nullable();
            $table->integer('end_time_active')->nullable();
            $table->string('type')->default('normal');
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('bot_message_heads');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBroadcastMessengersTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('broadcast_messengers', function (Blueprint $table) {
            $table->index('id');
            $table->string('bot_message_reply_id');
            $table->foreign('bot_message_reply_id')->references('_id')->on('bot_message_replies')->onDelete('cascade');
            $table->integer('time_interactive')->nullable();
            $table->integer('begin_time_active')->nullable();
            $table->integer('end_time_active')->nullable();
            $table->smallInteger('status')->default(1);
            $table->string('user_id');
            $table->foreign('user_id')->references('_id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('broadcast_messengers');
    }
}

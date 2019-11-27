<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBroadcastPagesTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('broadcast_pages', function (Blueprint $table) {
            $table->index('id');
            $table->string('broadcast_messenger_id');
            $table->foreign('broadcast_messenger_id')->references('_id')->on('broadcast_messengers')->onDelete('cascade');
            $table->string('fb_page_id');
            $table->foreign('fb_page_id')->references('fb_page_id')->on('pages')->onDelete('cascade');
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('broadcast_pages');
    }
}

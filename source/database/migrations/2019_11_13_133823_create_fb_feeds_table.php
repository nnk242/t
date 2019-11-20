<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbFeedsTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('fb_feeds', function (Blueprint $table) {
            $table->index('id');
            $table->string('fb_page_id');
            $table->foreign('fb_page_id')->references('fb_page_id')->on('pages')->onDelete('cascade');
            $table->index('post_id');
            $table->text('message')->nullable();

            $table->string('from_id');
            $table->string('link')->nullable();

            $table->string('item');

            $table->integer('created_time');
            $table->string('verb')->default('add');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('fb_feeds');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbPostActionsTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('fb_post_actions', function (Blueprint $table) {
            $table->index('id');
            $table->string('fb_page_id');
            $table->foreign('fb_page_id')->references('fb_page_id')->on('pages')->onDelete('cascade');
            $table->string('post_id');
            $table->foreign('post_id')->references('post_id')->on('fb_feeds')->onDelete('cascade');
            $table->string('comment_id')->nullable();
            $table->string('from_id');
            $table->string('item');
            $table->text('message')->nullable();
            $table->string('parent_id')->nullable();
            $table->string('reaction_type')->nullable();
            $table->text('post')->nullable();
            $table->text('photo')->nullable();

            $table->integer('created_time');
            $table->string('verb')->default('add');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('fb_post_actions');
    }
}

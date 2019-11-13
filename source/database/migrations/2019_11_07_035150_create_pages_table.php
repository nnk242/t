<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('pages', function (Blueprint $table) {
            $table->index('id');
            $table->index('fb_page_id');
            $table->string('name');
            $table->text('picture')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('pages');
    }
}

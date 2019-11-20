<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('users', function (Blueprint $table) {
            $table->index('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('social_id')->nullable();
            $table->string('access_token')->nullable();
            $table->text('page_selected')->nullable();
            $table->text('page_use')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        \App\Model\User::updateorcreate(['email' => 'admin@admin.com'], [
            'name' => 'Khang',
            'email' => 'khangnn@appota.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);
        \App\Model\User::updateorcreate(['email' => 'test@test.com',], [
            'name' => 'Test',
            'email' => 'nnk2402@gmail.com',
            'password' => Hash::make('test123'),
            'role' => 'normal'
        ]);
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('users');
    }
}

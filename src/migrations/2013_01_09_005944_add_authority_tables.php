<?php

use Illuminate\Database\Migrations\Migration;

class AddAuthorityTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function($table)
		{
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
		});

        Schema::create('role_user', function($table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('role_id');
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('roles');
        Schema::drop('role_user');
	}

}
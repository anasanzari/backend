<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManagersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('managers', function($table)
		{	
		    $table->increments('id');
		    $table->string('email', 50)->unique();
		    $table->string('name', 100);
		    $table->string('password');

		    $table->string('signup_data', 100);
		    
		    $table->string('roll_no', 20);
		    $table->integer('role')->default(0);
		    $table->string('event_code', 10)->nullable();
		    $table->boolean('validated')->default(false);

		    $table->rememberToken();
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
		Schema::drop('managers');
	}

}

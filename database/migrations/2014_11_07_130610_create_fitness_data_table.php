<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFitnessDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fitness_data', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('participant_id')->unsigned();
            $table->enum('type', array('steps', 'time'));
            $table->date('date');
            $table->integer('amount');
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
		Schema::drop('fitness_data');
	}

}

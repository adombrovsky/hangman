<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLettersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('letters', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('letter');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('game_id',false, true);
            $table->index('game_id');
            $table->foreign('game_id')
                  ->references('id')->on('games')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('letters');
	}

}

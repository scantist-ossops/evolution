<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventLogTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
    {
		Schema::create('event_log', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('eventid')->nullable()->default(0);
			$table->integer('createdon')->default(0);
			$table->integer('type')->default(1)->comment('1- information, 2 - warning, 3- error');
			$table->integer('user')->default(0)->index()->comment('link to user table');
			$table->integer('usertype')->default(0)->comment('0 - manager, 1 - web');
			$table->string('source', 128)->default('');
			$table->longText('description')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
    {
		Schema::drop('event_log');
	}
}

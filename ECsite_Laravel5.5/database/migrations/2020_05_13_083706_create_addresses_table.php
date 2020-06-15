<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('addresses', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('name');
			$table->string('zip', 7);
			$table->string('Prefecture', 4);
			$table->string('city', 50);
			$table->string('street', 50);
			$table->string('phone_number', 11);
			$table->string('sum_md5', 35);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('addresses');
	}
}

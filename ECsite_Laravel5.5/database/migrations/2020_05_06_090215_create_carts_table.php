<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('carts', function (Blueprint $table) {
			$table->increments('id'); //カートの商品ID
			$table->integer('user_id'); //関連するusersテーブルのレコードID
			$table->string('name'); //商品名
			$table->unsignedInteger('number'); //購入数
			$table->softDeletes();
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
        Schema::dropIfExists('carts');
    }
}

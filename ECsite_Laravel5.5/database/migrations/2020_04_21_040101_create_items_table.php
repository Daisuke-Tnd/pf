<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration {
	public function up() {
		Schema::create('items', function (Blueprint $table) {
			$table->increments('id'); // AI, PK
			$table->string('name'); // 商品名
			$table->string('body'); // 商品説明
			$table->unsignedInteger('price'); // 値段(MEDIUMINTだと16777215までなので、最高価格が超える可能性を考慮してINTを採用)
			$table->unsignedMediumInteger('stock'); // 在庫数(ものによっては65535を超える可能性があるので、MEDIUMINTを採用）
			$table->timestamps(); // レコード作成・更新日
			$table->softDeletes(); // 論理削除
		});
	}

	public function down() {
		Schema::dropIfExists('items');
	}
}

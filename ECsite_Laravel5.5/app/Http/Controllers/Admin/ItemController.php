<?php

namespace App\Http\Controllers\Admin;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;

class ItemController extends Controller {
	public function index() {
		$items = Item::all();
		return view('admin.item.index', ['items' => $items]);
	}

	public function detail(Request $request) {
		$item = Item::where('id', $request->id)->first();
		return view('admin.item.detail', ['item' => $item]);
	}

	public function add(Request $request) {
		return view('admin.item.add');
	}

	public function create(CreateItemRequest $request) {
		$item = new Item;
		$form = $request->all();
		unset($form['_token']);
		$item->fill($form)->save();
		return redirect('admin/item');
	}

	public function edit(Request $request) {
		$item = Item::find($request->id);
		return view('admin.item.edit', ['form' => $item]);
	}

	public function update(UpdateItemRequest $request) {
		$item = Item::find($request->id);
		$form = $request->all();
		unset($form['_token']);
		$item->fill($form)->save();
		return redirect('admin/item');
	}

}

<?php

namespace App\Http\Controllers;

use Auth;
use App\Cart;
use App\Item;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;


class CartController extends Controller {
	public function index(Request $request)
	{
		$items = Cart::where('user_id', Auth::id())->get();
		$total = Cart::where('user_id', Auth::id())->sum('total');
		return view('cart.index', ['items' => $items, 'total' => $total]);
	}

	public function add(Request $request)
	{
		$id = $request->id;
		$number = $request->number;
		$item = Item::select('name', 'price', 'stock')->where('id', $id)->first();
		$data['number'] = "$number";
		$rules = ['number' => "numeric|min:0|max:{$item->stock}"];
		$messages = [
			'number.min' => '注文数は1以上になるようにしてください。',
			'number.max' => '在庫数を超えています。'
		];
		$validator = Validator::make($data, $rules, $messages);
		if ($validator->fails()) {
			return redirect()
				->to(url()->previous())
				->withErrors($validator)
				->withInput();
		}
		$cart = new Cart;
		$form = [
			'user_id' => Auth::id(),
			'name' => $item->name,
			'number' => $number,
			'price' => $item->price,
			'total' => $item->price * $number
		];
		$cart->fill($form)->save();
		$request->session()->regenerateToken();
		return redirect('cart');
	}

	public function edit(Request $request)
	{
		$id = $request->id;
		$item = Item::select('name', 'price', 'stock')->where('id', $id)->first();
		$old_cart = Cart::where('user_id', Auth::id())
			->where('name', $item->name)
			->where('deleted_at', NULL)
			->first();
		$number = $request->number + $old_cart['number'];
		$data['number'] = "$number";
		$rules = ['number' => "numeric|min:0|max:{$item->stock}"];
		$messages = [
			'number.min' => '注文数は1以上になるようにしてください。',
			'number.max' => '在庫数を超えています。'
		];
		$validator = Validator::make($data, $rules, $messages);
		if ($validator->fails()) {
			return redirect()
				->to(url()->previous())
				->withErrors($validator)
				->withInput();
		}
		$number_add = $request->number + $old_cart['number'];
		$cart = Cart::find($old_cart['id']);
		$form = [
			'user_id' => Auth::id(),
			'name' => $item->name,
			'number' => $number_add,
			'price' => $item->price,
			'total' => $item->price * $number_add
		];
		$cart->fill($form)->save();
		$request->session()->regenerateToken();
		return redirect('cart');
	}

	public function delete(Request $request)
	{
		$id = $request->id;
		$cart = Cart::where('user_id', Auth::id())->where('id', $id)->first()->delete();
		return redirect('cart');
	}

}

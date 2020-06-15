<?php

namespace App\Http\Controllers;

use Auth;
use App\Item;
use App\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller {
	public function index(Request $request) {
		$items = Item::all();
		return view('item.index', ['items' => $items]);
	}

	public function detail(Request $request) {
		$session = $request->session()->get('session');
		$item = Item::where('id', $request->id)->first();
		$carts = Cart::where('user_id', Auth::id())->get()->pluck('name')->toArray();
		$cart = ['name' => $carts];
		return view('item.detail', ['item' => $item, 'cart' => $cart, 'session' => $session]);
	}

}

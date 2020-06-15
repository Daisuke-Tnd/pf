@extends('layouts.app')
@section('title', '商品詳細')
@section('content')

<table>
<tr>
<th>商品名</th><th>商品説明</th><th>値段</th><th>在庫</th>
</tr>
<tr>
<td>{{$item->name}}</td>
<td>{{$item->body}}</td>
<td>{{$item->price}}</td>
@if ($item->stock <= 0)
<td>×</td>
@elseif ($item->stock > 0)
<td>{{$item->stock}}</td>
@endif
</tr>
</table>
@guest
<button type="button" onclick="location.href='../login'">ログインしてください</button>
@else
@if ($errors->has('number'))
<tr><th>ERROR: </th><td>{{$errors->first('number')}}</td></tr>
@endif
@if ($item->stock <= 0)
<button type="button">在庫なし</button>
@elseif (in_array($item->name, $cart['name'], true))
<form action="{{route('cart.edit', ['id' => $item->id])}}" method="post">
数量<input type="text" name="number" value="1" size="5">
<input type="hidden" name="id" value="{{$item->id}}">
<input type="hidden" name="_token" value="{{csrf_token()}}">
<input type="submit" value="カートへ">
</form>
@else
<form action="{{route('cart.add', ['id' => $item->id])}}" method="post">
数量<input type="text" name="number" value="1" size="5">
<input type="hidden" name="id" value="{{$item->id}}">
<input type="hidden" name="_token" value="{{csrf_token()}}">
<input type="submit" value="カートへ">
</form>
@endif
@endguest
@endsection

@extends('layouts.app')
@section('title', '商品一覧')
@section('content')
<table>
<tr>
<th>商品名</th><th>値段</th><th>在庫</th>
</tr>
@foreach ($items as $item)
<tr>
<td><a href="{{route('detail', ['id' => $item->id])}}">{{$item->name}}</a></td>
<td>{{$item->price}}</td>
@if ($item->stock <= 0)
<td>×</td>
@elseif ($item->stock > 0)
<td>○</td>
@endif
</tr>
@endforeach
</table>
@auth
<p>&#x1f6d2;<a href="cart">カートをみる</a></p>
@endauth
@endsection

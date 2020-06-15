@extends('layouts.app')
@section('title', '&#x1f6d2; カート')
@section('content')

@if ($total == 0)
<p>カートが空です</p>
@else
<table>
<tr>
<th>商品名</th><th>購入数</th><th>値段</th><th>小計</th><th></th>
</tr>
@foreach ($items as $item)
@if ($item->number > 0)
<tr>
<td align="left">{{$item->name}}</a></td>
<td align="center">{{$item->number}}</td>
<td align="center">{{$item->price}}</td>
<td align="center">{{$item->total}}</td>
<td align="center"><input type="button" onClick="location.href='{{route('cart.delete', ['id' => $item->id])}}'" value="削除"></td>
</tr>
@endif
@endforeach
</table>
<span>合計金額</span>
@php
echo $total;
@endphp
<span>円</span>
@endif
@endsection

@extends('layouts.app_admin')
@section('title', 'Admin.Item.index')
@section('content')
<p>商品の新規追加はこちらへ</p>
<p>➡︎<a href="{{route('admin.add')}}">新規追加する</a></p>
<table>
<tr>
<th>商品名</th><th>値段</th><th>在庫</th>
</tr>
@foreach ($items as $item)
<tr>
<td><a href="{{route('admin.detail', ['id' => $item->id])}}">{{$item->name}}</a></td>
<td>{{$item->price}}</td>
@if ($item->stock <= 0)
<td>なし</td>
@elseif ($item->stock > 0)
<td>あり</td>
@endif
</tr>
@endforeach
</table>
@endsection

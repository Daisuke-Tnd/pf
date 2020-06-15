@extends('layouts.app_admin')
@section('title', 'Admin.Item.detail')
@section('content')
<p><a href="{{route('admin.edit', ['id' => $item->id])}}">以下の内容を編集する</a></p>
<table>
<tr>
<th>商品名</th><th>商品説明</th><th>値段</th><th>在庫</th>
</tr>
<tr>
<td>{{$item->name}}</td>
<td>{{$item->body}}</td>
<td>{{$item->price}}</td>
<td>{{$item->stock}}</td>
</tr>
</table>
@endsection

@extends('layouts.app_admin')
@section('title', 'Admin.Item.edit')
@section('content')

@if (count($errors) > 0)
<p>入力に問題があります。再入力して下さい。</p>
@endif
<form method="POST" action="https://procir-study.site/tsunoda277/LaravelMaster/public/admin/item/edit">
<table>
{{csrf_field()}}
<input type="hidden" name="id" value="{{$form->id}}">
@if ($errors->has('name'))
<tr><th>ERROR</th><td>{{$errors->first('name')}}</td></tr>
@endif
<tr><th>商品名</th><td><input type="text" name="name" value="{{$form->name}}"></td></tr>
@if ($errors->has('body'))
<tr><th>ERROR</th><td>{{$errors->first('body')}}</td></tr>
@endif
<tr><th>商品説明</th><td><input type="text" name="body" value="{{$form->body}}"></td></tr>
@if ($errors->has('stock'))
<tr><th>ERROR</th><td>{{$errors->first('stock')}}</td></tr>
@endif
<tr><th>在庫</th><td><input type="number" name="stock" value="{{$form->stock}}"></td></tr>
<tr><th></th><td><input type="submit" value="編集する"></td></tr>
</table>
</form>
@endsection

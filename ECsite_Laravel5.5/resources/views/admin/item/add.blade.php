@extends('layouts.app_admin')
@section('title', 'Admin.Item.add')
@section('content')

@if (count($errors) > 0)
<p>入力に問題があります。再入力して下さい。</p>
@endif
<form method="POST" action="https://procir-study.site/tsunoda277/LaravelMaster/public/admin/item/add">
<table>
{{csrf_field()}}
@if ($errors->has('name'))
<tr><th>ERROR</th><td>{{$errors->first('name')}}</td></tr>
@endif
<tr><th>商品名</th><td><input type="text" name="name" value="{{old('name')}}"></td></tr>
@if ($errors->has('body'))
<tr><th>ERROR</th><td>{{$errors->first('body')}}</td></tr>
@endif
<tr><th>商品説明</th><td><input type="text" name="body" value="{{old('body')}}"></td></tr>
@if ($errors->has('price'))
<tr><th>ERROR</th><td>{{$errors->first('price')}}</td></tr>
@endif
<tr><th>値段</th><td><input type="number" name="price" value="{{old('price')}}"></td></tr>
@if ($errors->has('stock'))
<tr><th>ERROR</th><td>{{$errors->first('stock')}}</td></tr>
@endif
<tr><th>在庫</th><td><input type="number" name="stock" value="{{old('stock')}}"></td></tr>
<tr><th></th><td><input type="submit" value="新規追加"></td></tr>
</table>
</form>
@endsection

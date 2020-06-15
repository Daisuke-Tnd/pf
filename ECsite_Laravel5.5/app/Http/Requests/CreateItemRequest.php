<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
	{
		return [
			'name' => 'required | unique:items,name',
			'body' => 'required | between:2,100',
			'price' => 'required | numeric | min:0',
			'stock' => 'required | numeric'
        ];
	}

	public function messages()
	{
		return [
			'required' => '入力してください。',
			'name.unique' => '既に使われている商品名です。',
			'body.between' => '2~100文字の間で入力してください。',
			'price.min' => 'マイナスは入力できません。'
		];
	}
}

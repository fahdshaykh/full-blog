<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        //dimensions:width=128,height=128
        return [
            'avatar' => 'image|mimes:jpg,jpeg,png|max:1024',
            'locale' => [
                'required',
                Rule::in(array_keys(User::LOCALES))
            ]
        ];
    }
}

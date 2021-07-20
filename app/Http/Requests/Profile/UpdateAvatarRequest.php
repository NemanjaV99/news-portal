<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAvatarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'avatar' => ['required', 'image', 'max:2048', 'mimes:jpg, jpeg, png', 'dimensions:ratio=1/1']
        ];
    }

    public function messages()
    {
        return [
            'avatar.max' => 'The image size exceeds the maximum limit ( 2MB )',
            'avatar.dimensions' => 'The avatar should have a ratio of 1/1'
        ];
    }
}

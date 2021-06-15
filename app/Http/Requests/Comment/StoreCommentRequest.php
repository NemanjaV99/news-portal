<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCommentRequest extends FormRequest
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
            'comment' => ['required', 'string'],
            'article' => ['required', 'exists:articles,hash_id']
        ];
    }

    public function messages()
    {
        return [
            'article.required' => 'Something went wrong.',
            'aricle.exists' => 'Something went wrong.',
        ];
    }
}

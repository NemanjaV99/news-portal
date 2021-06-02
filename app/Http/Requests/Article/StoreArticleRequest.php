<?php

namespace App\Http\Requests\Article;

use App\Models\ArticleCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isEditor();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(ArticleCategory $category)
    {
        // Get all the available/allowed categories to validate the category field 
        // The passed id in request needs to be inside this array
        $allowedCategories = $category->getAll()->pluck('id')->toArray();

        return [
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
            'category' => ['required', 'in:' . implode(',', $allowedCategories)],
        ];
    }
}

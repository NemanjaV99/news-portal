<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArticleCategory extends Model
{
    use HasFactory;

    protected $table = 'article_categories';
    
    public function getAll()
    {
        $categories = DB::table($this->table)->get(['id', 'name']);

        return $categories;
    }
}

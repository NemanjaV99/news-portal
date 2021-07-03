<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Editor extends Model
{
    use HasFactory;

    protected $table = 'editors';

    public function getByHashId($uuid)
    {
        $editor = DB::table($this->table)
            ->join('users', $this->table . '.user_id', '=', 'users.id')
            ->where($this->table . '.uuid', $uuid)
            ->select($this->table . '.*', 'users.*')
            ->get();
        
        return $editor;
    }
}

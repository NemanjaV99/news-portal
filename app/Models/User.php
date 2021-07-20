<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'users';

    private $roles = [
        'USER' => 1,
        'EDITOR' => 2,
        'ADMIN' => 3,
    ];

    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role');
    }
    
    /**
     * Create a full user name
     * 
     * @return string
     */
    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Determine if a user is an editor / has the editor role (2)
     * 
     */
    public function isEditor()
    {
        return $this->role()->where('id', $this->roles['EDITOR'])->exists();
    }

    public function updateMainInfo($data)
    {
        return DB::table($this->table)
            ->where(['id' => $data['id']])
            ->update(['first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'updated_at' => Carbon::now()]);
    }

    public function updateAvatar($data)
    {
        return DB::table($this->table)
            ->where(['id' => $data['id']])
            ->update(['avatar' => $data['avatar'], 'updated_at' => Carbon::now()]);
    }
}

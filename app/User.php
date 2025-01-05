<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Helpers\UuidHelper;

class User extends Authenticatable {

    use Notifiable;
    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'users';
    protected $uuidFieldName = 'user_uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_uuid', 'uuid', 'name', 'role', 'email', 'password', 'user_token', 'is_archive', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function saveUser(){

        $user = User::create(['uuid'=>UuidHelper::generateUniqueUUID()]);

        return !empty($user) ? $user->toArray() : [];
    }
}

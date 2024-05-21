<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Contracts\Role;


class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    // use Role;
    public $successStatus = 200;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname', 'email', 'password','phone','token','remember_token','score','api_token','role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function generateToken() {
        $this->api_token = md5(uniqid(''));
        $this->save();
        return $this->api_token;
    }

}

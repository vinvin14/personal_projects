<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'username',
        'password',
        'responsible_person',
        'role',
        'logAttemptsMax',
        'logCurAttempt',
        'lastLog',
        'token',
        'account_permission',
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
}

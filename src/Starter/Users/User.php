<?php
namespace Starter\Users;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'number',
        'api_key',
        'status',
    ];

    public static $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'number' => 'required',
        'status' => 'required',
    ];

    public $readable = 'User';
}

<?php

require_once(__DIR__ . '/Model.class.php');

class User extends Model 
{
    protected static $table = 'users';
    protected static $fields = ['id', 'email', 'password'];

    public function links()
    {
        return $this->hasMany('UserLink', 'id', 'user_id');
    }

    public function news()
    {
        return $this->hasMany('UserNews', 'id', 'user_id');
    }

    public function friends() 
    {
        return $this->hasMany('Friends', 'id', 'user_id');
    }
}
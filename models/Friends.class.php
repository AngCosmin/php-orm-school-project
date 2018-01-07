<?php

require_once(__DIR__ . '/Model.class.php');

class Friends extends Model 
{
    protected static $table = 'friends';
    protected static $fields = ['id', 'user_id', 'friend_id'];

    public function friend()
    {
        return $this->hasOne('User', 'friend_id', 'id');
    }
}
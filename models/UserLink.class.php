<?php

require_once(__DIR__ . '/Model.class.php');

class UserLink extends Model 
{
    protected static $table = 'user_links';
    protected static $fields = ['id', 'user_id', 'link_id'];

    public function user()
    {
        return $this->hasOne('User', 'user_id', 'id');
    }

    public function link()
    {
        return $this->hasOne('Link', 'link_id', 'id');
    }
}
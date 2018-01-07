<?php

require_once(__DIR__ . '/Model.class.php');
require_once(__DIR__ . '/User.class.php');
require_once(__DIR__ . '/Link.class.php');

class UserNews extends Model 
{
    public static $table = 'user_news';
    protected static $fields = ['id', 'user_id', 'news_id', 'shared'];

    public function user()
    {
        return $this->hasOne('User', 'user_id', 'id');
    }

    public function news()
    {
        return $this->hasOne('News', 'news_id', 'id');
    }
}
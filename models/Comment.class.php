<?php

require_once(__DIR__ . '/Model.class.php');

class Comment extends Model 
{
    protected static $table = 'comments';
    protected static $fields = ['id', 'user_news_id', 'user_id', 'text', 'created_at'];

    public function creator()
    {
        return $this->hasOne('User', 'user_id', 'id');
    }
}
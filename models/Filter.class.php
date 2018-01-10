<?php

require_once(__DIR__ . '/Model.class.php');

class Filter extends Model 
{
    protected static $table = 'filters';
    protected static $fields = ['id', 'user_id', 'link_id', 'filter'];

    public function creator()
    {
        return $this->hasOne('User', 'user_id', 'id');
    }

    public function link()
    {
        return $this->hasOne('Link', 'link_id', 'id');
    }
}
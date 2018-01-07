<?php

require_once(__DIR__ . '/Model.class.php');

class News extends Model 
{
    protected static $table = 'news';
    protected static $fields = ['id', 'link_id', 'title', 'origin_url', 'description', 'keywords', 'datetime'];
}
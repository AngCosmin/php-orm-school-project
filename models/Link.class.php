<?php

require_once(__DIR__ . '/Model.class.php');

class Link extends Model 
{
    protected static $table = 'links';
    protected static $fields = ['id', 'url'];
}
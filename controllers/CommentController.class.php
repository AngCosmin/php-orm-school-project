<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/Comment.class.php');  

session_start();

class CommentController 
{
    public static function add($request) 
    {
        // Get data from request
        $user_news_id = $request['user_news_id'];
        $text = $request['comment'];
        $user_id = $_SESSION['user']->id;
        $created_at = date('Y-m-d H:i:s');
        $location = $request['location'];

        // Add comment in database
        $item = [
            'user_news_id' => $user_news_id,
            'user_id'      => $user_id,
            'text'         => $text,
            'created_at'   => $created_at,
        ];

        Comment::create($item);

        header('Location: /stiri/views/' . $location);
    }
}
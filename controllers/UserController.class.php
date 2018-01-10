<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/News.class.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/UserDAO.class.php'); 

session_start();

class UserController 
{
    public static function addFriend($request)
    {
        $friend_id = $request['user_id'];

        $user_actions = new UserDAO($_SESSION['user']);

        $relation = $user_actions->addFriend($friend_id);

        if ($relation) {
            $_SESSION['message'] = 'You added a new friend!';                    
        }
        else {
            $_SESSION['message'] = 'There was an error!';                    
        }

        header('Location: /stiri/views/users');        
    }

    public static function unfriend($request)
    {
        $friend_id = $request['user_id'];

        $user_actions = new UserDAO($_SESSION['user']);

        $relation = Friends::whereFirst([['user_id', $user_actions->user->id], ['friend_id', $friend_id]]);

        if ($relation) {
            $relation->delete();

            $_SESSION['message'] = 'You lost one friend :( !';                    
        }
        else {
            $_SESSION['message'] = 'There was an error!';                    
        }

        header('Location: /stiri/views/users');  
    }

    public static function share($request)
    {
        $news_id = $request['news_id'];
        $user_id = $_SESSION['user']->id;

        $news = UserNews::whereFirst([['user_id', $user_id], ['news_id', $news_id]]);
        $news->shared = 1;
        $news->save();

        header('Location: /stiri/views/my-news');  
    }
}
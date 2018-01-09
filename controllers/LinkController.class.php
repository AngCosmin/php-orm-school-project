<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/Link.class.php');  
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/User.class.php');  
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/UserLink.class.php');  

session_start();

class LinkController 
{
    public static function addLink($request) 
    {
        // Get data from request
        $url = $request['link'];

        // Check if url contains http
        if (strpos($url, 'http://') === false) {
            $_SESSION['message'] = 'Please, start the link with http://';
            header('Location: /stiri/views/add-link');
            return;
        }
        
        // Get logged in user
        $user_actions = new UserDAO($_SESSION['user']);

        // Check if link is already in database
        $link = Link::whereFirst(['url', $url]);

        if (!$link) {
            $link = Link::create(['url' => $url]);
        }

        // Check if link is already associated with logged user
        $has_link = UserLink::whereFirst([['user_id', $user_actions->user->id], ['link_id', $link->id]]);

        if (!$has_link) {
            $user_actions->addLink($link);
            $_SESSION['message'] = 'Link added!';
        }
        else {
            $_SESSION['message'] = 'Link already added!';            
        }

        header('Location: /stiri/views/add-link');
    }
}
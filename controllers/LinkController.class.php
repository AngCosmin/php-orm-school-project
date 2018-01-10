<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/Link.class.php');  
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/Filter.class.php');  
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/UserDAO.class.php');  
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/UserLink.class.php');  

session_start();

class LinkController 
{
    public static function add($request) 
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

    public static function filter($request)
    {
        // Get data from request
        $link_id = $request['link_id'];
        $filter  = $request['filter'];

        // Get user id
        $user_id = $_SESSION['user']->id;

        // Check if there is a filter set for logged user and link
        $filter_db = Filter::whereFirst([['user_id', $user_id], ['link_id' => $link_id]]);

        if ($filter_db) {
            // Update the current filter 

            $filter_db->filter = $filter;
            $filter_db->save();

            $_SESSION['message'] = 'Filter updated!';            
        }
        else {
            // Create the filter

            Filter::create(['user_id' => $user_id, 'link_id' => $link_id, 'filter' => $filter]);
            $_SESSION['message'] = 'Filter created!';
        }

        header('Location: /stiri/views/filter?link_id=' . $link_id);
    }
}
<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/News.class.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/User.class.php'); 

session_start();

class UserController 
{
    public static function updateNews($request) 
    {
        $link_id = $request['link_id'];

        $link = Link::find($link_id);

        $data = file_get_contents($link->url);
        $data = simplexml_load_string($data);

        $logged_user = $_SESSION['user'];

        foreach ($data->channel->item as $item) {
            $title = (string) $item->title;
            $link  = (string) $item->link;
            $description = (string) $item->description;
            $keywords = str_replace(' ', ',', $title);
            
            $date = (string) $item->pubDate;
            $date = strptime($date,'%a, %d %b %Y %H:%M:%S %Z');
            $date = date("Y-m-d H:i:s", mktime($date['tm_hour'], $date['tm_min'], $date['tm_sec'], $date['tm_mon']+1, $date['tm_mday'], 1900 + $date['tm_year']));
            
            $news_in_db = News::whereFirst(['origin_url', $link]);

            if ($news_in_db) {
                // Article already in database

                $logged_user->addNews($news_in_db->id); // Asign it to user
            }
            else {
                // Article is not in database
                $item = [
                    'link_id'     => $link_id,
                    'title'       => $title,
                    'origin_url'  => $link,
                    'description' => $description,
                    'keywords'    => $keywords,
                    'datetime'    => $date,
                ];

                $news = News::create($item); // Save article in database

                if ($news) {
                    // If article was created

                    $logged_user->addNews($news->id); // Asign it to user                
                }
            }
        }

        if ($data == null) {
            $_SESSION['message'] = 'Could not get data from RSS!';        
        }
        else {
            $_SESSION['message'] = 'Update success!';
        }

        header('Location: /stiri/views/home');
    }

    public static function addFriend($request)
    {
        $friend_id = $request['user_id'];

        $logged_user = $_SESSION['user'];

        $relation = $logged_user->addFriend($friend_id);

        if ($relation) {
            $_SESSION['message'] = 'You added a new friend!';                    
        }
        else {
            $_SESSION['message'] = 'There was an error!';                    
        }

        header('Location: /stiri/views/all-users');        
    }

    public static function unfriend($request)
    {
        $friend_id = $request['user_id'];

        $logged_user = $_SESSION['user'];

        $relation = Friends::whereFirst([['user_id', $logged_user->id], ['friend_id', $friend_id]]);

        if ($relation) {
            $relation->delete();

            $_SESSION['message'] = 'You lost one friend :( !';                    
        }
        else {
            $_SESSION['message'] = 'There was an error!';                    
        }

        header('Location: /stiri/views/my-friends');  
    }
}
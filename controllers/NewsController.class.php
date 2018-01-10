<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/News.class.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/Filter.class.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/UserDAO.class.php'); 

session_start();

class NewsController 
{
    public static function update($request) 
    {
        // Get data from request
        $link_id = $request['link_id'];

        // Get link from database
        $link = Link::find($link_id);

        // Get user id 
        $user_id = $_SESSION['user']->id;                

        $data = file_get_contents($link->url);
        $data = simplexml_load_string($data);

        if ($data == null) {
            $_SESSION['message'] = 'Could not get data from RSS!';        
            header('Location: /stiri/views/home');
            return;
        }

        $link_filters = Filter::whereFirst([['user_id', $user_id], ['link_id', $link_id]]);

        if ($link_filters && $link_filters->filter != '') {
            // If there are filters

            NewsController::applyFilter($data, $link_id);            
        }
        else {
            // If there are no filters

            NewsController::doNotApplyFilter($data, $link_id);                        
        }

        $_SESSION['message'] = 'Update success!';
        header('Location: /stiri/views/home');        
    }

    private static function applyFilter($data, $link_id)
    {
        $user_id = $_SESSION['user']->id;        
        $user_actions = new UserDAO($_SESSION['user']);  
        $link_filters = Filter::whereFirst([['user_id', $user_id], ['link_id', $link_id]]);
        
        foreach ($data->channel->item as $item) {
            $title = (string) $item->title;
            $link  = (string) $item->link;
            $description = (string) $item->description;
            $keywords = str_replace(' ', ',', $title);
            
            $date = (string) $item->pubDate;
            $date = strptime($date, '%a, %d %b %Y %H:%M:%S %Z');
            $date = date("Y-m-d H:i:s", mktime($date['tm_hour'], $date['tm_min'], $date['tm_sec'], $date['tm_mon'] + 1, $date['tm_mday'], 1900 + $date['tm_year']));
            
            // Serach for the news in database
            $news_in_db = News::whereFirst(['origin_url', $link]);
            
            $filters = explode(',', $link_filters->filter);
            
            foreach ($filters as $filter) {
                // Check if title or description contains filter
                
                if (strpos(strtolower($title), strtolower($filter)) !== false || strpos(strtolower($description), strtolower($filter)) !== false) {
                    if ($news_in_db) {
                        // Article already in database
                        
                        $user_news = UserNews::whereFirst([['user_id', $user_id], ['news_id', $news_in_db->id]]);

                        if (!$user_news) {
                            // If user don't have this article assigned 

                            $user_actions->addNews($news_in_db->id); // Asign it to user
                        }
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

                            $user_actions->addNews($news->id); // Asign it to user
                        }
                    }
                }
            }
        }
    }

    private static function doNotApplyFilter($data, $link_id)
    {
        $user_id = $_SESSION['user']->id;        
        $user_actions = new UserDAO($_SESSION['user']);        

        foreach ($data->channel->item as $item) {
            $title = (string) $item->title;
            $link  = (string) $item->link;
            $description = (string) $item->description;
            $keywords = str_replace(' ', ',', $title);
            
            $date = (string) $item->pubDate;
            $date = strptime($date, '%a, %d %b %Y %H:%M:%S %Z');
            $date = date("Y-m-d H:i:s", mktime($date['tm_hour'], $date['tm_min'], $date['tm_sec'], $date['tm_mon'] + 1, $date['tm_mday'], 1900 + $date['tm_year']));
            
            // Serach for the news in database
            $news_in_db = News::whereFirst(['origin_url', $link]);
            
            if ($news_in_db) {
                // Article already in database

                $user_news = UserNews::whereFirst([['user_id', $user_id], ['news_id', $news_in_db->id]]);

                if (!$user_news) {
                    // If user don't have this article assigned 

                    $user_actions->addNews($news_in_db->id); // Asign it to user
                }
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

                    $user_actions->addNews($news->id); // Asign it to user
                }
            }
        }
    }
}
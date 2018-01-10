<?php

require_once(__DIR__ . '/Model.class.php');
require_once(__DIR__ . '/User.class.php');
require_once(__DIR__ . '/Link.class.php');
require_once(__DIR__ . '/News.class.php');
require_once(__DIR__ . '/Friends.class.php');
require_once(__DIR__ . '/UserLink.class.php');
require_once(__DIR__ . '/UserNews.class.php');

class UserDAO
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function addLink($link) 
    {
        $data = [
            'user_id' => $this->user->id,
            'link_id' => $link->id,
        ];

        return UserLink::create($data);
    }

    public function addNews($news_id, $shared = 0) 
    {
        $data = [
            'user_id' => $this->user->id,
            'news_id' => $news_id,
            'shared'  => $shared,
        ];

        return UserNews::create($data);
    }

    public function addFriend($friend_id) 
    {
        $data = [
            'user_id'   => $this->user->id,
            'friend_id' => $friend_id, 
        ];

        return Friends::create($data);
    }

    public function links()
    {
        return $this->user->links();
    }

    public function news()
    {
        $user_news = $this->user->news();

        $news = [];

        foreach ($user_news as $value) {
            $news[] = $value->news();
        }

        return $news;
    }

    public function friends() 
    {
        $relations = $this->user->friends();

        $friends = [];

        foreach ($relations as $relation) {
            $friends[] = $relation->friend();
        }

        return $friends;
    }

    public function filterActive($link_id)
    {
        $filter = Filter::whereFirst([['user_id', $this->user->id], ['link_id', $link_id]]);

        if ($filter && $filter->filter != '') {
            return true;
        }

        return false;
    }
}
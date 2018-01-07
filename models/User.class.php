<?php

require_once(__DIR__ . '/Model.class.php');
require_once(__DIR__ . '/Link.class.php');
require_once(__DIR__ . '/News.class.php');
require_once(__DIR__ . '/Friends.class.php');
require_once(__DIR__ . '/UserLink.class.php');
require_once(__DIR__ . '/UserNews.class.php');

class User extends Model 
{
    protected static $table = 'users';
    protected static $fields = ['id', 'email', 'password'];

    public function addLink($link) {
        $data = [
            'user_id' => $this->id,
            'link_id' => $link->id,
        ];

        return UserLink::create($data);
    }

    public function addNews($news_id, $shared = 0) {
        $data = [
            'user_id' => $this->id,
            'news_id' => $news_id,
            'shared'  => $shared,
        ];

        return UserNews::create($data);
    }

    public function addFriend($friend_id) {
        $data = [
            'user_id'   => $this->id,
            'friend_id' => $friend_id, 
        ];

        return Friends::create($data);
    }

    public function getFriends() {
        parent::getDatabase();
        
        $user_news_table = UserNews::$table;
        $news_table      = News::$table;

        $sql = "SELECT news_id as id, link_id, title, origin_url, description, keywords, date FROM $user_news_table ";
        $sql .= "INNER JOIN $news_table ON $user_news_table.news_id = $news_table.id WHERE user_id = ? ORDER BY date DESC";

        if ($stmt = self::$database->prepare($sql)) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $link_id, $title, $origin_url, $description, $keywords, $date);
                
                $news = [];

                while ($stmt->fetch()) {
                    $item   = new News($id, $link_id, $title, $origin_url, $description, $keywords, $date);
                    $news[] = $item;
                }

                $stmt->close();
                return $news;
            }

            $stmt->close();
        }

        return null;
    }

    public function links()
    {
        return $this->hasMany('UserLink', 'id', 'user_id');
    }

    public function news()
    {
        $user_news = $this->hasMany('UserNews', 'id', 'user_id');

        $news = [];

        foreach ($user_news as $value) {
            $news[] = $value->news();
        }

        return $news;
    }

    public function friends() 
    {
        $relations = $this->hasMany('Friends', 'id', 'user_id');

        $friends = [];

        foreach ($relations as $relation) {
            $friends[] = $relation->friend();
        }

        return $friends;
    }
}
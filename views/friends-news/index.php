<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2 class="text-center m-b-30">FRIENDS NEWS</h2>
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/partials/header.php'); ?>

        <h4 class="text-center text-info">
            <?php 
                if (isset($_SESSION['message'])) {
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']); 
                }
            ?>
        </h4>

        <?php 
            $user_actions = new UserDAO($_SESSION['user']);
            $friends = $user_actions->friends();
        ?>

        <?php 
            $number_of_news = 0;
            foreach ($friends as $friend) {
                $friend_shared_news = UserNews::where([['user_id', $friend->id], ['shared', 1]]);
                foreach ($friend_shared_news as $news) {
                    $news_detailed = $news->news();
                    $number_of_news++;
        ?>

        <div class="card text-center">
            <div class="card-block">
                <h5 class="mt-2"><?php echo $news_detailed->title ?></h5>
                
                <p class="card-text mt-3 pl-4 pr-4">
                    <?php
                        if ($news_detailed->description == '') {
                            echo 'This article has no description...';                                
                        } 
                        else {
                            echo $news_detailed->description;
                        }
                    ?>
                </p>
                
                <a href="<?php echo $news_detailed->origin_url ?>" class="btn btn-link" target="_blank">
                    See full article
                </a>

                <div class="mb-2">
                    <small class="text-muted"><?php echo $news_detailed->datetime ?></small>
                </div>
            </div>
        </div>
        <br>
                    
        <?php 
            } 
        }
        ?>

        <?php if (!$friends) { ?>
            <h3 class="text-center">You have no friends...</h3>
        <?php } else if ($number_of_news == 0) { ?>
            <h3 class="text-center">Your friends didn't shared any news...</h3>
        <?php } ?>
    </div>
</body>
</html>
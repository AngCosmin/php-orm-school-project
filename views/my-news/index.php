<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My news</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2 class="text-center m-b-30">MY NEWS</h2>
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
            $news = $user_actions->news();
        ?>

        <?php foreach ($news as $item) { ?>
        
            <div class="card">
                <div class="card-block">
                    <h5 class="mt-2 text-center"><?php echo $item->title ?></h5>
                    <div class="text-center mb-2">
                        <small class="text-muted"><?php echo $item->datetime ?></small>
                    </div>
                    
                    <p class="card-text mt-3 pl-4 pr-4">
                        <?php
                            if ($item->description == '') {
                                echo 'This article has no description...';                                
                            } 
                            else {
                                echo $item->description;
                            }
                        ?>
                    </p>
                    
                    <div class="text-center">
                        <a href="<?php echo $item->origin_url ?>" class="btn btn-link" target="_blank">
                            See full article
                        </a>
                    </div>

                    <?php
                        $is_shared = UserNews::whereFirst([['user_id', $_SESSION['user']->id], ['news_id', $item->id], ['shared', 1]])
                    ?>

                    <?php if (!$is_shared) { ?>
                        <form action="/stiri/routes/web.php" method="POST" class="text-center mb-3">
                            <input type="hidden" name="action" value="share">
                            <input type="hidden" name="news_id" value="<?php echo $item->id ?>">
                            <button type="submit" class="btn btn-sm btn-primary">Share</button>
                        </form>
                    <?php } else { ?>
                        <hr>
                        <div class="text-center text-muted">Comments</div>

                        <?php $comments = Comment::where(['user_news_id', $is_shared->id]); ?>
                        <?php if (count($comments) == 0) { ?>
                            <h6 class="text-center text-muted">No comments</h6>
                        <?php } ?>
                        
                        <?php foreach ($comments as $comment) { ?>
                            <div class="media mb-2">
                                <img class="align-self-start mr-3 ml-3" src="https://www.timeshighereducation.com/sites/default/files/byline_photos/default-avatar.png" width="64px" height="64px">
                                <div class="media-body">
                                    <h6 class="mt-0">
                                        <?php echo $comment->creator()->username; ?>
                                        <small class="ml-1"><?php echo $comment->created_at; ?></small>
                                    </h6>
                                    <p style="font-size: 0.9em"><?php echo $comment->text; ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <form action="/stiri/routes/web.php" method="POST" class="text-center mb-3 mt-3">
                            <input type="hidden" name="action" value="add-comment">
                            <input type="hidden" name="user_news_id" value="<?php echo $is_shared->id ?>">
                            <input type="hidden" name="location" value="my-news">

                            <div class="input-group ml-3 mr-3">
                                <input type="text" name="comment" class="form-control" placeholder="Write something...">
                                <span class="input-group-btn mr-5">
                                    <button type="submit" class="btn btn-sm btn-primary">Comment</button>
                                </span>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
            <br>

        <?php } ?>

        <?php if (count($news) == 0) { ?>

            <h4 class="text-center">You have no news...</h4>

        <?php } ?>

    </div>
</body>
</html>
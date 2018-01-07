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

        <?php foreach ($_SESSION['user']->news() as $item) { ?>
        
            <div class="card text-center">
                <div class="card-block">
                    <h5 class="mt-2"><?php echo $item->title ?></h5>
                    
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
                    
                    <a href="<?php echo $item->origin_url ?>" class="btn btn-link mb-2" target="_blank">
                        See full article
                    </a>

                    <div class="mb-2">
                        <small class="text-muted"><?php echo $item->datetime ?></small>
                    </div>
                </div>
            </div>
            <br>

        <?php } ?>

    </div>
</body>
</html>
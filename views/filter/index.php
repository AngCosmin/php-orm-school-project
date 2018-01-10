<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Filter</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
    <?php
        if (!isset($_GET['link_id'])) {
            header('Location: /stiri/index.php');
        }
    ?>

    <div class="container">
        <h2 class="text-center m-b-30">Filter</h2>
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/partials/header.php'); ?>

        <h6 class="text-center m-b-30">                
            <?php 
                if (isset($_SESSION['message'])) {
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                }
            ?>
        </h6>

        <?php 
            $user_id = $_SESSION['user']->id;
            $filter_db = Filter::whereFirst([['user_id', $user_id], ['link_id' => $link_id]]);
        ?>

        <form action="/stiri/routes/web.php" method="POST" class="text-center mb-3">
            <input type="hidden" name="action" value="filter">
            <input type="hidden" name="link_id" value="<?php echo $_GET['link_id']; ?>">

            <div class="form-group">
                <label>Filter <small>(add comma separated words)</small></label>
                <input type="text" name="filter" class="form-control" autocomplete="off" value="<?php echo isset($filter_db) ? $filter_db->filter : null; ?>">
            </div>

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>
</body>
</html>
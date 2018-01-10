<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Users</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2 class="text-center m-b-30">MY FRIENDS</h2>
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

        <div class="row">
            <div class="col-md-6">
                <h3 class="text-center">All users</h3>
                <div class="table-responsive">
                    <table class="table table-bordered w-100">
                        <tr>
                            <th>username</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach (User::all() as $user) { ?>
                            <?php if ($user->username != $_SESSION['user']->username) { ?>

                                <tr>
                                    <td><?php echo $user->username ?></td>
                                    <td>
                                        <?php if (Friends::where([['user_id', $_SESSION['user']->id], ['friend_id', $user->id]])) { ?>
                                            <span class="text-muted">Already your friend</span>
                                        <?php } else { ?>
                                        
                                            <form action="/stiri/routes/web.php" method="POST" class="d-inline-block">
                                                <input type="hidden" name="action" value="add-friend">
                                                <input type="hidden" name="user_id" value="<?php echo $user->id ?>">

                                                <button type="submit" class="btn btn-sm btn-secondary">Add friend</button>
                                            </form>

                                        <?php } ?>
                                    </td>
                                </tr>
                                
                            <?php } ?>
                        <?php } ?>
                    </table>
                </div>
            </div>
            
            <div class="col-md-6">
                <h3 class="text-center">Friends</h3>
                <div class="table-responsive">
                    <table class="table table-bordered w-100">
                        <tr>
                            <th>username</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach ($friends as $friend) { ?>

                            <tr>
                                <td><?php echo $friend->username ?></td>
                                <td>
                                    <form action="/stiri/routes/web.php" method="POST" class="d-inline-block">
                                        <input type="hidden" name="action" value="unfriend">
                                        <input type="hidden" name="user_id" value="<?php echo $friend->id ?>">

                                        <button type="submit" class="btn btn-sm btn-secondary">Unfriend</button>
                                    </form>
                                </td>
                            </tr>

                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
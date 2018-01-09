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
        <h2 class="text-center m-b-30">HOME</h2>
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/partials/header.php'); ?> 

        <h4 class="text-center text-info">
            <?php 
                if (isset($_SESSION['message'])) {
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']); 
                }
            ?>
        </h4>

        <a href="/stiri/views/add-link"><button class="btn btn-secondary mb-3">Add new link</button></a>

        <?php 
            $user_actions = new UserDAO($_SESSION['user']);
            $links = $user_actions->links();
        ?>

        <div class="table-responsive">
            <table class="table table-bordered w-100">
                <tr>
                    <th>Link</th>
                    <th>Action</th>
                </tr>
                
                <?php foreach ($links as $item) { ?>
                    <tr>
                        <td><?php echo $item->link()->url ?></td>
                        <td>
                            <form action="/stiri/routes/web.php" method="POST" class="d-inline-block">
                                <input type="hidden" name="action" value="update-link">
                                <input type="hidden" name="link_id" value="<?php echo $item->link()->id ?>">

                                <button type="submit" class="btn btn-sm btn-secondary">Update</button>
                            </form>

                            <form action="/stiri/routes/web.php" method="POST" class="d-inline-block">
                                <input type="hidden" name="action" value="filter-link">
                                <input type="hidden" name="link_id" value="<?php echo $item->link()->id ?>">

                                <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
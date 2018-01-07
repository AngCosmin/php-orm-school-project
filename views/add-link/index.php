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
        <h2 class="text-center m-b-30">Add new link</h2>
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/partials/header.php'); ?>

        <form action="/stiri/routes/web.php" method="POST" class="text-center mb-3">
            <input type="hidden" name="action" value="add-link">

            <div class="form-group">
                <label>Link</label>
                <input type="url" name="link" class="form-control" required autocomplete="off">
            </div>

            <h6 class="text-center m-b-30">                
                <?php 
                    if (isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                ?>
            </h6>

            <button type="submit" class="btn btn-primary">Add</button>
        </form>

        <div class="text-center">
            <a href="/stiri/views/home"><button class="btn btn-secondary">Cancel</button></a>
        </div>
    </div>
</body>
</html>
<?php
    session_start();    

    spl_autoload_register(function ($class_name) {
        if (strpos($class_name, 'Controller') !== false) {
            require_once($_SERVER['DOCUMENT_ROOT'] . "/stiri/controllers/$class_name.class.php");  
        }
        else {
            require_once($_SERVER['DOCUMENT_ROOT'] . "/stiri/models/$class_name.class.php");  
        }
    });

    // If user is logged in
    if (isset($_SESSION['user'])) {
        header('Location: /stiri/views/home');        
    }
?>

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
        <div class="row justify-content-md-center">
            <img class="mt-5" src="http://web.emerson.edu/emersonchannel/wp-content/uploads/sites/46/2016/11/Breaking-News-Logo.png">

            <div class="col-md-6 mt-5">
                <form action="./routes/web.php" method="POST">       
                    <input type="hidden" name="action" value="login" />

                    <div class="form-group">
                        <input type="text" class="form-control" name="email" placeholder="Email" required="" autofocus="" autocomplete="off">
                    </div>
                    <div class="form-group">                    
                        <input type="password" class="form-control" name="password" placeholder="Password" required="" autocomplete="off">      
                    </div>

                    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>   
                </form>

                <div class="text-center mt-1">
                    <?php 
                        if (isset($_SESSION['error'])) {
                            echo $_SESSION['error']; 
                            unset($_SESSION['error']); 
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
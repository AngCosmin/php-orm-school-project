<?php
    spl_autoload_register(function ($class_name) {
        if (strpos($class_name, 'Controller') !== false) {
            require_once($_SERVER['DOCUMENT_ROOT'] . "/stiri/controllers/$class_name.class.php");  
        }
        else {
            require_once($_SERVER['DOCUMENT_ROOT'] . "/stiri/models/$class_name.class.php");  
        }
    });

    session_start();

    // If user is not logged in
    if (!isset($_SESSION['user'])) {
        header('Location: /stiri/index.php');        
    }
?>

<div class="row justify-content-md-center">
    <div class="col-md-12">
        <h2 class="text-center m-b-30"></h2>
        <div class="text-center text-muted">
            Welcome 
            <?php 
                echo $_SESSION['user']->email;
            ?>
        </div>
        
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/partials/navbar.php'); ?>
    </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/controllers/UserController.class.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/controllers/LinkController.class.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/controllers/AuthController.class.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/controllers/CommentController.class.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/controllers/NewsController.class.php'); 

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);

    switch ($action) {
        case 'login':
            AuthController::login($_POST);
            break;
        case 'logout':
            AuthController::logout();
            break;
        case 'add-link':
            LinkController::add($_POST);
            break;
        case 'update-link':
            NewsController::update($_POST);
            break;
        case 'add-friend':
            UserController::addFriend($_POST);
            break;
        case 'unfriend':
            UserController::unfriend($_POST);
            break;
        case 'filter':
            LinkController::filter($_POST);
            break;
        case 'share':
            UserController::share($_POST);
            break;
        case 'add-comment':
            CommentController::add($_POST);
            break;
    }
}
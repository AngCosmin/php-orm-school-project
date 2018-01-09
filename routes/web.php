<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/controllers/UserController.class.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/controllers/LinkController.class.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/controllers/AuthController.class.php'); 

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
            LinkController::addLink($_POST);
            break;
        case 'update-link':
            UserController::updateNews($_POST);
            break;
        case 'add-friend':
            UserController::addFriend($_POST);
            break;
        case 'unfriend':
            UserController::unfriend($_POST);
            break;
        case 'filter-link':

            break;
        case 'share':
            UserController::share($_POST);
            break;
    }
}
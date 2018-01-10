<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/stiri/models/User.class.php');  

session_start();

class AuthController {
    public static function login($request) {
        // Reset session
        session_reset();
        
        // Get data from request
        $username = $request['username'];
        $passsword = $request['password'];

        // Find user where data
        $user = User::whereFirst([['username', $username], ['password', $passsword]]);

        if ($user) {
            // If user was found
            $_SESSION['user'] = $user;      
            
            // Redirect to home
            header('Location: /stiri/views/home');            
        }
        else {
            // If user was not found
            $_SESSION['error'] = 'Credentials not found!';

            // Redirect to login
            header('Location: /stiri/index.php');
        }
    }

    public static function logout() {
        // Destroy session
        session_destroy();

        // Redirect to login
        header('Location: /stiri/index.php');        
    }
}
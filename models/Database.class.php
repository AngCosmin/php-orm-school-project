<?php

class Database 
{
    private static $connection = null;

    private function __construct() {

    }

    public static function instance() {
        if (!isset(self::$connection)) {
            $config           = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/stiri/config/app.ini', true);
            $database_details = $config['database'];

            $hostname = $database_details['hostname'];
            $database = $database_details['database'];
            $username = $database_details['username'];
            $password = $database_details['password'];

            try {
                self::$connection = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $e) {
                echo 'Exception! ' . $e->getMessage();
            }
        }

        return self::$connection;
    }
}
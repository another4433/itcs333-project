<?php
        $db_host = 'localhost';
        $db_user = 'root';
        $db_password = '';
        $db_name = 'bookingdb';
        
        //set DSN
        $dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name;
        
        $PDOoptions=[
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => FALSE
        ];
?>
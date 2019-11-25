<?php
    $DB_HOST = '192.168.99.100:3306';
	$DB_USER = 'root';
	$DB_PASSWORD = 'password';
	$DB_NAME = 'camagru';

    function connectDB($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME){

		$pdo = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD);
		return $pdo;
    }
    

    function connect_mysql()
    {
        $DB_HOST = '192.168.99.100:3306';
        $DB_USER = 'root';
        $DB_PASSWORD = 'password';
        $DB_NAME = 'camagru';

        $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
        if (!$conn)
        {
            die("NOPE : ".mysqli_connect_error());
        }
        return $conn;
    }

?>
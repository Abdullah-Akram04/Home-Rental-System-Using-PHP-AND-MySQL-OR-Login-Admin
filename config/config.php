<?php
	session_start();

	require_once 'auth/secure.php'; // ✅ Include the key securely from another file

	// Define database
	define('dbhost', 'localhost');
	define('dbuser', 'root');
	define('dbpass', '');
	define('dbname', 'newrent');

	try {
		$connect = new PDO("mysql:host=".dbhost."; dbname=".dbname, dbuser, dbpass);
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}
?>

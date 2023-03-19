<?php

    try {
		$mysqlConnection = new PDO('mysql:host=localhost;dbname=sauceblog;charset=utf8','root','root');
		//echo "Connection established";
	}
	catch (Exception $e){
		die('Erreur : ' . $e->getMessage());
	}

?>
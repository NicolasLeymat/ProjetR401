<?php
	$host = "localhost";
	$user = "root";
	$mdp= "root";
    try {
		$mysqlConnection = new PDO('mysql:host='.$host.';dbname=sauceblog;charset=utf8',$user, $mdp);
		//echo "Connection established";
	}
	catch (Exception $e){
		die('Erreur : ' . $e->getMessage());
	}

?>
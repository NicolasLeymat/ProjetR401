<?php

    try {
		$mysqlConnection = new PDO('mysql:host=mysql-sauceblog.alwaysdata.net;dbname=sauceblog_blog;charset=utf8','sauceblog_root','SauceBlogRoot');
	}
	catch (Exception $e){
		die('Erreur : ' . $e->getMessage());
	}

?>
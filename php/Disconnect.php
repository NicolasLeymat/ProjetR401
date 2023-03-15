<?php

	include('./bdConnect.php');

	include('./utils.php');

	initialiserSessionInit();

	session_unset();
	session_destroy();	

	header ('Location: ../Index.php');

?>
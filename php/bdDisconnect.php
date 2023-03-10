<?php

	include "bdConnect.php";

	require "utils.php";

	initialiserSession();

	session_unset();
	session_destroy();	

	header ('location: ../pages/index.html');

?>
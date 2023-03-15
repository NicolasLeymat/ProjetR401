<?php

	function initialiserSessionInit() : bool {
		if(!session_id()){
			session_start();
			session_regenerate_id();
			return true;	
		}
		return false;
	}

	function initialiserSession($jwt) : bool {
		if(session_id()){
			session_unset();
			session_destroy();
		}
		if(!session_id()){
			session_start();
			session_regenerate_id();
			$_SESSION['jwt'] = $jwt;
			return true;	
		}
		return false;
	}

	function estConnecte() : bool {
		if ( isset($_SESSION['id']) ) {
			return true;
		} else {
			return false;
		}
	}

?>
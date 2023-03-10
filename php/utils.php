<?php

	function initialiserSession() : bool {
		if(!session_id()){
			session_start();
			session_regenerate_id()	;
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
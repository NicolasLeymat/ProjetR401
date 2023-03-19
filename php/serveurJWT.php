<?php
    /// Librairies éventuelles (pour la connexion à la BDD, etc.)
    include('jwt_utils.php');
    require('bdConnect.php');
    
    /// Paramétrage de l'entête HTTP (pour la réponse au Client)
    header("Content-Type:application/json");

    /// Identification du type de méthode HTTP envoyée par le client
    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method){
        
        case "POST" :
            
            //Execute la requete
            $req = $mysqlConnection->prepare('select id_utilisateur, identifiant, password, id_role from utilisateur where identifiant = ?');
            $req->execute(array($_POST['identifiant']));
            $matchingData = $req->fetch();
            //echo password_hash($_POST['pwd'], PASSWORD_DEFAULT, ["cost" => 12]);
            if ($_POST['identifiant'] == $matchingData['identifiant'] && password_verify($_POST['pwd'], $matchingData['password'])){
                $reqrole = $mysqlConnection->prepare('select id_role, denomination from role where id_role = ?');
                $reqrole->execute(array($matchingData['id_role']));
                
                $roleData = $reqrole->fetch();
                $username = $_POST['identifiant'];

                $headers = array('alg'=>'HS256', 'typ'=>'JWT');
                $payload = array('id_utilisateur'=>$matchingData['id_utilisateur'], 'username'=>$username, 'role'=>$roleData['denomination'], 'exp'=>(time()+3600));

                $jwt = generate_jwt($headers,$payload);
                reinitialiserSession($jwt);
                //echo $_SESSION['jwt'];
                $requestHeader = 'Location:../Index.php';
                header($requestHeader);
            }
            /// Traitement
            /// Envoi de la réponse au Client
            //deliver_response(201, "Votre message", $jwt);
            break;
        
        }
    
        /// Envoi de la réponse au Client
    function deliver_response($status, $status_message, $data){
        /// Paramétrage de l'entête HTTP, suite
        header("HTTP/1.1 $status $status_message");
        /// Paramétrage de la réponse retournée
        $response['status'] = $status;
        $response['status_message'] = $status_message;
        $response['data'] = $data;
        /// Mapping de la réponse au format JSON
        $json_response = json_encode($response);
        echo $json_response;
    }

    function reinitialiserSession($jwt) : bool {
        
        if($_SESSION['jwt'] != null){
            unset($_SESSION['jwt']);
        }
        if(!session_id()){
            session_start();
			session_regenerate_id()	;
            $_SESSION['jwt'] = $jwt;
			return true;	
		}
		return false;
	}
?>
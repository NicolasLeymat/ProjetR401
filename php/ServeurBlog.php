<?php
    /// Librairies éventuelles (pour la connexion à la BDD, etc.)
    include('./jwt_utils.php');
    require('./bdConnect.php');
    include('./utils.php');
    
    /// Paramétrage de l'entête HTTP (pour la réponse au Client)
    header("Content-Type:application/json");

    $headers = getallheaders();
    $http_method = $_SERVER['REQUEST_METHOD'];
    $http_type = $headers['REQUEST_TYPE'];
    if(!isset($_SESSION['id'])){
        session_start();
        session_regenerate_id()	;
    }
    $token = '';
    //var_dump(getallheaders());
    $token = get_bearer_token();
    //echo 'Chibre'.$token;

    if (!is_jwt_valid($token)){
        echo 'Non Sauce Chibre';
    }
    else {
        $tokenData = get_payload($token);
        /// Identification du type de méthode HTTP envoyée par le client
        switch ($http_method){
            /// Cas de la méthode GET
            case "GET" :
                /// Récupération des critères de recherche envoyés par le Client
                //echo($_SERVER['REQUEST_TYPE']);
                //Recuperation de tout les articles
                if($http_type === "Tab"){
                    $res = get_articles();
                    $matchingData = array();
                    foreach($res as $articles){

                        $utilisateur = get_author($articles['id_utilisateur']);
                        $nbDislike = count_dislike($articles['id_article']);
                        $nbLike = count_like($articles['id_article']);

                        if($tokenData['role'] == 'Anonymous'){
                            $a = array( "id_article" => $articles['id_article'],
                                        "Titre" => "".$articles['titre'],
                                        "NomAut" => "".$utilisateur['identifiant'],
                                        "Publi" => "".$articles['date_publication'],
                                        "NbLike" => "#", 
                                        "NbDislike" => "#");
                        }else{
                            $a = array( "id_article" => $articles['id_article'],
                            "Titre" => "".$articles['titre'],
                            "NomAut" => "".$utilisateur['identifiant'],
                            "Publi" => "".$articles['date_publication'], 
                            "NbLike" => "".$nbLike['nbLike'], 
                            "NbDislike" => "".$nbDislike['nbDislike']);
                        }
                        
                        array_push($matchingData,$a); 
                    }
                //Recuperation d'un article precis
                }else if($http_type === "Art"){
                    $matchingData = get_content($headers['ID']);
                    $nbDislike = count_dislike($headers['ID']);
                    $nbLike = count_like($headers['ID']);
                    $author = get_author($matchingData['id_utilisateur'])["identifiant"];
                    $matchingData["author"] = $author;
                    $matchingData["nb_dislike"] = $nbDislike;
                    $matchingData["nb_like"] = $nbLike;
                }else{
                    deliver_response(404, "demande inconnu", null);
                }

                /// Envoi de la réponse au Client
                deliver_response(200, "Votre message", $matchingData);
                break;
            /// Cas de la méthode d'ajout d'un article
            case "POST" :
                $postedData = file_get_contents('php://input');
                $res = json_decode($postedData, true);
                $commande = insert_article($res['titre'], $res['contenu'], $res['id_user']);
                switch($commande){
                    case 1:
                        deliver_response(201, "Creation de l'article", NULL);
                        break;
                    case -1:
                        deliver_response(422, "une erreur est presente dans les données passé", NULL);
                        break;
                }
                break;
                /// Cas de la méthode PUT
            case "PUT" :
                /// Récupération des données envoyées par le Client
                $postedData = file_get_contents('php://input');
                $res = json_decode($postedData, true);
                echo $res['type'];
                switch($res['type']){
                    case 0:
                        $commande =  update_article($res['id_article'], $res['contenu']);
                        switch($commande){
                            case 1:
                                deliver_response(201, "Modification de l'article", NULL);
                                break;
                            case -1:
                                deliver_response(422, "une erreur est presente dans les données passé", NULL);
                                break;
                        }
                        break;
                    case 1 : 
                        $commande = like($res['id_utilisateur'], $res['id_article']);
                        switch($commande){
                            case 1:
                                deliver_response(201, "Like effectué", NULL);
                                break;
                            case -1:
                                deliver_response(422, "une erreur est presente dans les données passé", NULL);
                                break;
                        }
                        break;
                    case 2 : 
                            $commande = dislike($res['id_utilisateur'], $res['id_article']);
                            switch($commande){
                                case 1:
                                    deliver_response(201, "dislike effectué", NULL);
                                    break;
                                case -1:
                                    deliver_response(422, "une erreur est presente dans les données passé", NULL);
                                    break;
                            }
                            break;
                }
                break;
            /// Cas de la méthode DELETE
            case "DELETE" : 
                $postedData = file_get_contents('php://input');
                $res = json_decode($postedData, true);
                switch($res['type']){
                    case 0:
                        $commande =  delete_article($res['id_article']);
                        switch($commande){
                            case 1:
                                deliver_response(201, "Suppression de l'article reussi", NULL);
                                break;
                            case -1:
                                deliver_response(422, "une erreur est presente dans les données passé", NULL);
                                break;
                        }
                        break;
                }
                break;
            default :
                /// Récupération de l'identifiant de la ressource envoyé par le Client
                // if (!empty($_GET['id'])){
                //     $req = $mysqlConnection->prepare('DELETE FROM chuckn_facts WHERE id = :val');
                //     $req->bindParam(':val',$_GET['id'],PDO::PARAM_STR);
                //     $res = $req->execute();
                // }
                // /// Envoi de la réponse au Client
                // deliver_response(200, "Votre message", NULL);
                break;
            }
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
?>
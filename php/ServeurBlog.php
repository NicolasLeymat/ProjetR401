<?php
    /// Librairies éventuelles (pour la connexion à la BDD, etc.)
    include('./jwt_utils.php');
    require('./bdConnect.php');
    include('./utils.php');
    
    /// Paramétrage de l'entête HTTP (pour la réponse au Client)
    header("Content-Type:application/json");

    $headers = getallheaders();
    $http_method = $_SERVER['REQUEST_METHOD'];
    if(!isset($_SESSION['id'])){
        session_start();
        session_regenerate_id()	;
    }
    $token = '';
    $token = get_bearer_token();
    if (!is_jwt_valid($token)){
        echo "La sauce n'est pas bonne : Token invalide";
    }
    else {
        $tokenData = get_payload($token);
        /// Identification du type de méthode HTTP envoyée par le client
        switch ($http_method){
            /// Cas de la méthode GET
            case "GET" :
                $http_type = $headers['REQUEST_TYPE'];
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
                    $matchingData["nb_dislike"] = $nbDislike['nbDislike'];
                    $matchingData["nb_like"] = $nbLike['nbLike'];
                }else{
                    deliver_response(404, "Demande inconnue", null);
                }

                /// Envoi de la réponse au Client
                deliver_response(200, "Recuperation des données voulu effectuer", $matchingData);
                break;
            /// Cas de la méthode d'ajout d'un article
            case "POST" :
                $postedData = file_get_contents('php://input');
                $res = json_decode($postedData, true);
                $commande = insert_article($res['titre'], $res['contenu'], $res['id_author']);
                switch($commande){
                    case 1:
                        deliver_response(200, "Creation de l'article", NULL);
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
                //echo $res['type'];
                if($tokenData['role'] == 'Anonymous'){
                    deliver_response(405, "Forbidden vous n'avez pas les droits pour effectuer l'action suivante", null);
                }
                switch($res['type']){
//==================Mis à jour d'un artcile==================//
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
//==================Like==================//
                    case 1 : 
                        $commande = like($res['id_utilisateur'], $res['id_article']);
                        echo $commande;
                        switch($commande){
                            case 0:
                                deliver_response(400, "like déjà effectué", null);
                                break;
                            case 1:
                                deliver_response(201, "Like effectué", NULL);
                                break;
                            case -1:
                                deliver_response(422, "une erreur est presente dans les données passé", NULL);
                                break;
                        }
                        break;
//==================Dislike==================//
                    case 2 : 
                            $commande = dislike($res['id_utilisateur'], $res['id_article']);
                            switch($commande){
                                case 0:
                                    deliver_response(400, "dislike déjà effectué", null);
                                    break;
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
                        if($tokenData['role'] == 'Anonymous'){
                            deliver_response(405, "Forbidden vous n'avez pas les droits pour effectuer l'action suivante", null);
                        }
                            $commande =  delete_article($res['id_article']);
                            switch($commande){
                                case 1:
                                    deliver_response(200, "Suppression de l'article reussi", NULL);
                                    break;
                                case -1:
                                    deliver_response(422, "une erreur est presente dans les données passé", NULL);
                                    break;
                            }
                        break;
                }
                break;
            default :
                deliver_response(404, "Commande non reconnue", NULL);
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
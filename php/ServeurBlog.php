<?php
    /// Librairies éventuelles (pour la connexion à la BDD, etc.)
    include('./jwt_utils.php');
    require('./bdConnect.php');
    
    /// Paramétrage de l'entête HTTP (pour la réponse au Client)
    header("Content-Type:application/json");


    $http_method = $_SERVER['REQUEST_METHOD'];
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

        /// Identification du type de méthode HTTP envoyée par le client
        switch ($http_method){
            /// Cas de la méthode GET
            case "GET" :
                /// Récupération des critères de recherche envoyés par le Client
                    $req = $mysqlConnection->prepare("SELECT * FROM articles");
                    $req->execute();
                    $matchingData = array();
                    while($articles = $req->fetch()){
                        $reqUtilisateur = $mysqlConnection->prepare('SELECT * FROM utilisateur WHERE id_utilisateur = ' . $articles['id_utilisateur']);
                        $reqUtilisateur->execute();
                        $utilisateur = $reqUtilisateur->fetch();
                        
                        $reqDislike = $mysqlConnection->prepare('SELECT count(*) as nbDislike FROM disliker WHERE id_article = ?');
                        $reqDislike->execute(array($articles['id_article']));
                        $nbDislike = $reqDislike->fetch();
                        
                        $reqLike = $mysqlConnection->prepare('SELECT count(*) as nbLike FROM liker WHERE id_article = ' . $articles['id_article']);
                        $reqLike->execute();
                        $nbLike = $reqLike->fetch();
                        
                        //Titre, NomAut, Publi, NbLike, NbDislike
                        $a = array( "id_article" => $articles['id_article'],
                                    "Titre" => "".$articles['titre'],
                                    "NomAut" => "".$utilisateur['identifiant'],
                                    "Publi" => "".$articles['date_publication'], 
                                    "NbLike" => "".$nbLike['nbLike'], 
                                    "NbDislike" => "".$nbDislike['nbDislike']);
                        
                        array_push($matchingData,$a); 
                    }


                /// Envoi de la réponse au Client
                deliver_response(200, "Votre message", $matchingData);
                break;
            /// Cas de la méthode POST
            case "POST" :
                /// Récupération des données envoyées par le Client

                // $stmt = $mysqlConnection->prepare("SELECT MAX(Id) AS max_id FROM chuckn_facts");
                // $stmt -> execute();
                // $invNum = $stmt -> fetch(PDO::FETCH_ASSOC);
                // $max_id = $invNum['max_id']+1;

                // $postedData = file_get_contents('php://input');
                // $res = json_decode($postedData, true);
                // $req = $mysqlConnection->prepare('INSERT INTO chuckn_facts values
                // (:id,:val, 0, "2023-02-01 08:00:00", NULL, 0, 0)');
                // $req->bindParam(':id',$max_id,PDO::PARAM_INT);
                // $req->bindParam(':val',$res['phrase'],PDO::PARAM_STR);
                // $res = $req->execute();
                // echo $res;
                // if (!$res){
                //     die('Aled');
                // }
                // /// Traitement
                // /// Envoi de la réponse au Client
                // deliver_response(201, "Votre message", NULL);
                break;
                /// Cas de la méthode PUT
            case "PUT" :
                /// Récupération des données envoyées par le Client
                // $postedData = file_get_contents('php://input');
                // $res = json_decode($postedData, true);
                // echo $res['phrase'];
                // echo $_GET['id'];
                // $req = $mysqlConnection->prepare('UPDATE chuckn_facts SET
                // phrase = :phrase WHERE id = :id');
                // $req->bindParam(':phrase', $res['phrase'],PDO::PARAM_STR);

                // $req->bindParam(':id',$_GET['id'],PDO::PARAM_INT);
                // $res = $req->execute();
                // /// Traitement
                // /// Envoi de la réponse au Client
                // deliver_response(200, "Votre message", NULL);
                break;
            /// Cas de la méthode DELETE
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
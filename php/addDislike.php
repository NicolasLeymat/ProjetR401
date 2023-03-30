<?php
include('jwt_utils.php');
include('utils.php');
    initialiserSessionInit();
    $token = $_SESSION['jwt'];
    $utilisateur = get_payload($token);

    $data = array("id_utilisateur" => $utilisateur['id_utilisateur'], "id_article" => $_GET["id"], "type" => 2);
    $data_string = json_encode($data);
    /// Envoi de la requête
    $result = file_get_contents(
    'http://localhost/ProjetR401/php/ServeurBlog.php',
    null,
    stream_context_create(array(
    'http' => array('method' => 'PUT', // ou PUT
    'content' => $data_string,
    'header' => array('Content-Type: application/json'."\r\n"
    .'Content-Length: '.strlen($data_string)."\r\n"."Authorization: Bearer $token\r\n"))))
    );
    $data= json_decode($result);
    var_dump($data);
header('Location: ../Article.php?id='.$_GET["id"]);
?>


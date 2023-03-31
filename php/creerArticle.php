<?php
include('./utils.php');
initialiserSessionInit();
if (isset($_POST["Titre"]) && $_POST['Contenu']){
    $token = $_SESSION['jwt'];
    $data = array("titre" => $_POST["Titre"], "contenu" => $_POST["Contenu"], "id_author" => $_POST["id_author"]);
    $data_string = json_encode($data);
    /// Envoi de la requête
    $result = file_get_contents(
    'http://localhost/ProjetR401/php/ServeurBlog.php',
    null,
    stream_context_create(array(
    'http' => array('method' => 'POST', // ou PUT
    'content' => $data_string,
    'header' => array('Content-Type: application/json'."\r\n"
    .'Content-Length: '.strlen($data_string)."\r\n"."Authorization: Bearer $token\r\n"))))
    ); 
}else{
    echo 'Des données ne sont pas présente';
}
header('Location: ../Index.php');
?>
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

    //Retourne un article a partir de son id
	function get_content($id){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("SELECT titre, date_publication, contenu, id_utilisateur FROM articles WHERE id_article = :id");
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $res=$req->execute();
        $data = $req->fetch();
        return $data;
    }
    //Retourne un auteur a partir de son id
    function get_author($id){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("SELECT identifiant FROM utilisateur WHERE id_utilisateur = :id");
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $res=$req->execute();
        $data = $req->fetch();
        return $data;
    }
    //Supprime un article a partir de son id
    function delete_article($id){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("DELETE FROM articles WHERE id_article = :id");
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $res=$req->execute();
        if ($res == 1){
            return 1;
        }
        else {
            return -1;
        }
    }
    //Supprime un like d'un utilisateur sur un article
    function delete_like($id_article, $id_utilisateur){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("DELETE FROM liker WHERE id_article = :id_article and id_utilisateur = :id_utilisateur");
        $req->bindParam(':id_article', $id_article, PDO::PARAM_INT);
        $req->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $res=$req->execute();
    }
    //Supprime un dislike d'un utilisateur sur un article
    function delete_dislike($id_article, $id_utilisateur){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("DELETE FROM disliker WHERE id_article = :id_article and id_utilisateur = :id_utilisateur");
        $req->bindParam(':id_article', $id_article, PDO::PARAM_INT);
        $req->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
		var_dump($req);
        $res=$req->execute();
    }
    //
    function get_users_like_article($id_article){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("SELECT u.id_utilisateur, u.identifiant FROM utilisateurs u, liker l, articles a WHERE
        a.id_article = l.id_article and l.id_utilisateur = u.id_utilisateur and a.id_article = :id");
        $req->bindParam(':id', $id_article, PDO::PARAM_INT);
        $res=$req->execute();
        $data = $req->fetchAll();
        return $data;
    }

    function get_users_dislike_article($id_article){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("SELECT u.id_utilisateur, u.identifiant FROM utilisateurs u, disliker d, articles a WHERE
        a.id_article = d.id_article and d.id_utilisateur = u.id_utilisateur and a.id_article = :id");
        $req->bindParam(':id', $id_article, PDO::PARAM_INT);
        $res=$req->execute();
        $data = $req->fetchAll();
        return $data;
    }

	function check_user_like_article($id_user, $id_article){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("SELECT count(1) as verif FROM utilisateurs u, liker l, articles a WHERE
        a.id_article = l.id_article and l.id_utilisateur = u.id_utilisateur and a.id_article = :id_article and u.id_utilisateur = :id_user");
        $req->bindParam(':id_article', $id_article, PDO::PARAM_INT);
		$req->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $res=$req->execute();
        $data = $req->fetch();
		if ($data['verif'] != 0){
			return 1;
		}
		else {
			return 0;
		}
    }

	function check_user_dislike_article($id_user, $id_article){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("SELECT count(1) as verif FROM utilisateurs u, disliker l, articles a WHERE
        a.id_article = l.id_article and l.id_utilisateur = u.id_utilisateur and a.id_article = :id_article and u.id_utilisateur = :id_user");
        $req->bindParam(':id_article', $id_article, PDO::PARAM_INT);
		$req->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $res=$req->execute();
        $data = $req->fetch();
		if ($data['verif'] != 0){
			return 1;
		}
		else {
			return 0;
		}
    }

    //count($data) pour compter le nombre de like et le nombre de dislike

    function insert_article($titre, $contenu, $id_user){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("INSERT INTO articles(titre, contenu, id_utilisateur) values (:titre, :contenu, :id_user)");
        $req->bindParam(':titre', $titre, PDO::PARAM_STR);
        $req->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $req->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $res=$req->execute();
        if ($res == 1){
            return 1;
        }
        else {
            return -1;
        }
    }

    function get_user_articles($id_user){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("SELECT a.id_user FROM articles a, utilisateur u WHERE
        a.id_utilisateur = u.id_utilisateur and a.id_utilisateur = :id");
        $req->bindParam(':id', $id_user, PDO::PARAM_INT);
        $res=$req->execute();
        $data = $req->fetchAll();
        return $data;
    }
    //Modifie le contenu d'un article a partir de son id
    function update_article($id_article, $contenu){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("UPDATE articles set contenu = :contenu WHERE id_article = :id");
        $req->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $req->bindParam(':id', $id_article, PDO::PARAM_INT);
        $res=$req->execute();
        if ($res == 1){
            return 1;
        }
        else {
            return -1;
        }
    }

    function add_like($id_user, $id_article){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("INSERT INTO liker values (:id_article, :id_user)");
        $req->bindParam(':id_article', $id_article, PDO::PARAM_INT);
        $req->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $res=$req->execute();
    }

    function add_dislike($id_user, $id_article){
        include("bdConnect.php");
        $req = $mysqlConnection->prepare("INSERT INTO disliker values (:id_article, :id_user)");
        $req->bindParam(':id_article', $id_article, PDO::PARAM_INT);
        $req->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $res=$req->execute();
    }

	function like($id_user, $id_article){
		delete_dislike($id_user, $id_article);
		add_like($id_user, $id_article);
	}

	function dislike($id_user, $id_article){
		delete_like($id_user, $id_article);
		add_dislike($id_user, $id_article);
	}

?>
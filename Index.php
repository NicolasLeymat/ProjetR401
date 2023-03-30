    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    
    <!--  CSS  -->
    <link rel="stylesheet" href="assets/css/styles.css" />

    <!--  UNICONS  -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    
    <link rel="shortcut icon" href="#">
    <title>Sauce Blog</title>
    </head>

    <style>
        tr[data-href]{
            cursor:pointer;
        }
    </style>

    <body>
    <?php
        require('./php/bdConnect.php');
        include('./php/jwt_utils.php');
        include('./php/utils.php');
        initialiserSessionInit();
        if($_SESSION['jwt'] == null || !is_jwt_valid($_SESSION['jwt'])){
            $headers = array('alg'=>'HS256', 'typ'=>'JWT');
            $payload = array('id_utilisateur'=>-1, 'username'=>'Anonymous', 'role'=>'Anonymous', 'exp'=>(time()+7200));
            $jwt = generate_jwt($headers,$payload);
            if($_SESSION['jwt'] == null){
                initialiserSession($jwt);
                //echo $_SESSION['jwt'];
            }else{
                $_SESSION['jwt'] = $jwt;
            }
            //echo $_SESSION['jwt'];
        }
        $token = $_SESSION['jwt'];
        $payloadSessionToken = get_payload($token);
        //var_dump($payloadSessionToken);

    ?>
    <!--  HEADER  -->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="#" class="nav_logo"
            >Sauce blog</a
            >
            <div class="nav_menu" id="nav-menu">
            <ul class="nav_list grid">
                <li class="nav_item">
                <a href="./Index.php" class="nav_link active-link">
                    <i class="uil uil-estate nav_icon"></i> Home
                </a>
                </li>
            </ul>
            <i class="uil uil-times nav_close" id="nav-close"></i>
            </div>
            <div class="nav_btns">
                <?php
                    //echo $payloadSessionToken;
                    $role = $payloadSessionToken['role'];
                    if($role == 'Anonymous'){

                ?>
                    <a href="./Connexion.php" class="nav_link">
                        Sign in
                    </a>
                <?php
                    }else{
                ?>
                    <a href="./php/Disconnect.php"><i class="uil uil-signout"></i></a>
                <?php
                    }
                ?>
                <i class="uil uil-moon change-theme" id="theme-button"></i>
                <div class="nav_toggle" id="nav-toggle">
                    <i class="uil uil-apps"></i>
                </div>
            </div>
        </nav>
        </header>
    <!--  HEADER FIN  -->
    <!--  MAIN   -->
    <main class="main">
        <!-- HOME -->
        <section class="home section" id="home">
            
            <?php 
                if($payloadSessionToken['role'] == 'Anonymous'){
            ?>
                <div class="flexConnxInfo">
                    <h3 class="titreConnx"> Bonjour vous etes connecter en tant que : <?php echo $payloadSessionToken["username"];?></h3>
                </div>
            <?php
                }
            ?>

            <?php 
                if($payloadSessionToken['role'] == 'Moderateur'){
            ?>
                <div class="flexConnxInfo">
                    <h3 class="titreConnx"> Bonjour vous etes connecter en tant que : <?php echo $payloadSessionToken["username"];?></h3>
                </div>
            <?php
                }
            ?>

            <?php 
                if($payloadSessionToken['role'] == 'Publisher'){
            ?>
                <div class="flexConnxInfo">
                    <h3 class="titreConnx"> Bonjour vous etes connecter en tant que : <?php echo $payloadSessionToken["username"];?></h3>
                    <button class="buttonAdd" id="addButton" data-href=<?php echo "./newArticle.php?id=".$payloadSessionToken["id_utilisateur"];?>> Créer un post</button>
                </div>
            <?php
                }
            ?>

            <table class="table">
                <thead class="thead">
                    <th scope="col" class="th_30">Titre de l'article</th>
                    <th scope="col" class="th_30">Createur</th>
                    <th scope="col" class="th_30">Date de publication</th>
                    <th scope="col" class="th_5">Like</th>
                    <th scope="col" class="th_5">Dislike</th>
                </thead>
                <tbody class="tbody">
                    <?php
                        //echo $token;
                        //var_dump($payloadSessionToken);
                        //var_dump($_SESSION['jwt']);
                        //var_dump(is_jwt_valid($_SESSION['jwt']));
                        $result = file_get_contents('http://localhost/ProjetR401/php/ServeurBlog.php',
                        true,
                        stream_context_create(array('http' => array('method' => 'GET', 'header' => "Authorization: Bearer $token\r\n"."Content-Type: application/json\r\n"."REQUEST_TYPE: Tab\r\n"))) // ou DELETE
                        );
                        $data = json_decode($result, true);
                        //var_dump($data);
                        foreach($data['data'] as $articles){
                            $date = date_format(date_create($articles['Publi']),"Y/m/d H:i:s");
                    ?>
                        <tr class="tr" data-href=<?php echo "./Article.php?id=".$articles['id_article']?>>
                                <td class="td_30"><?php echo $articles['Titre']; ?></td>
                                <td class="td_30"><?php echo $articles['NomAut']; ?></td>
                                <td class="td_30"><?php echo $date;?></td>
                                <td class="td_5"> <i class="uil uil-thumbs-up"></i> <?php echo $articles['NbLike']; ?></td>
                                <td class="td_5"> <i class="uil uil-thumbs-down"></i> <?php echo $articles['NbDislike']; ?></td>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </section>
        <!-- HOME FIN -->
    </main>
    <!-- MAIN FIN -->

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer_bg">
        <div class="footer_container container grid">
            <div>
            <h1 class="footer_title">Sauce Blog</h1>
            </div>
        </div>
        <p class="footer_copy">&#169; Leymat & Veslin. All rights reserved.</p>
        </div>
    </footer>
    <!-- FOOTER FIN -->

    <!-- SCROLL TOP  -->
    <a href="#" class="scrollup" id="scroll-up">
        <i class="uil uil-arrow-up scrollup_icon"></i>
    </a>
    <!-- SCROLL TOP FIN -->

    <!--  MAIN JS  -->
    <script src="assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelectorAll("tr[data-href]");

            let addBtn = document.getElementById("addButton");

            if(addBtn != null){
                addBtn.addEventListener("click", () => {window.location.href = addBtn.dataset.href;});
            }

            rows.forEach(row => {
                row.addEventListener("click", () => {window.location.href = row.dataset.href;});
            });
        });

    </script>
</body>
</html>

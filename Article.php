    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!--  UNICONS  -->
    <link
        rel="stylesheet"
        href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"
    />

    <!--  SWIPER CSS  -->
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css" />
    <!--  CSS  -->
    <link rel="stylesheet" href="assets/css/styles.css" />

    <title>Sauce Blog</title>
    </head>
    <body>
    <?php
        require('./php/bdConnect.php');
        include('./php/jwt_utils.php');
        include('./php/utils.php');
        initialiserSessionInit();
        if($_SESSION['jwt'] == null || !is_jwt_valid($_SESSION['jwt'])){
            $headers = array('alg'=>'HS256', 'typ'=>'JWT');
            $payload = array('id_utilisateur'=>-1, 'username'=>'Anonymous', 'role'=>'Anonymous', 'exp'=>(time()+3600));
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
            >Leymat <br />
            Nicolas</a
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
                    <a href="./Connexion.html" class="nav_link">
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
        <div class="article">
            <!--Auteur, Date de publication, Titre, Contenu, Likes, Dislikes-->
            <?php
            $result = file_get_contents('http://localhost/ProjetR401/php/ServeurBlog.php',
            true,
            stream_context_create(array('http' => array('method' => 'GET', 'header' => "Authorization: Bearer $token\r\n" . 
            "Content-Type: application/json\r\n". "REQUEST_TYPE: Art\r\n"."ID: ".$_GET['id']."\r\n"))) // ou DELETE
            );
            $data = json_decode($result, true); 
            $resultat = $data['data'];
            ?>
            
            <h1 class="titre"><?php echo ($resultat['titre']); ?></h1>
            <p><?php echo ($resultat['contenu']); ?></p>
            <br />
            <div class="left">
                <p><?php echo ($resultat['author']); ?></p>
            </div>
            <div class="right">
                <p><?php echo ($resultat['date_publication']); ?></p>
            </div>
            <div style="clear: both;"></div>
            <br />
            
            <a href=<?php echo "php/addLike.php?id=".$_GET['id']?>>
                <div class="left">
                    <p><i class="uil uil-thumbs-up"></i><?php echo ($resultat['nb_like']); ?></p>
                </div>
            </a>
            <a href=<?php echo "php/addDislike.php?id=".$_GET['id']?>>
                <div class="right">
                    <p><i class="uil uil-thumbs-down"></i><?php echo ($resultat['nb_dislike']); ?></p>
                </div>
            </a>



        </div>
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

    <!--  SWIPER JS  -->
    <script src="assets/js/swiper-bundle.min.js"></script>
    <!--  MAIN JS  -->
    <script src="assets/js/main.js"></script>
    </body>
    </html>

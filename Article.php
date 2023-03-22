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
        <div class="home_container container flex vertical">
            <!--Auteur, Date de publication, Titre, Contenu, Likes, Dislikes-->


            <?php
                require("php/utils.php");

                //$date = date('Y-m-d H:i:s');


                //$res = insert_article(0,"ahahha","SDSDSD");
                //echo $res;






            ?>







































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
            <h1 class="footer_title">Nicolas</h1>
            <span class="footer_subtitle">Développeur</span>
            </div>

            <ul class="footer_links">
            <li>
                <a href="#services" class="footer_link">Services</a>
            </li>

            <li>
                <a href="#portfolio" class="footer_link">Portfolio</a>
            </li>

            <li>
                <a href="#contact" class="footer_link">Contact Me</a>
            </li>
            </ul>

            <div class="footer_socials">
            <a href="" target="_blank" class="footer_social">
                <i class="uil uil-facebook-f"></i>
            </a>
            <a href="" target="_blank" class="footer_social">
                <i class="uil uil-instagram"></i>
            </a>
            </div>
        </div>
        <p class="footer_copy">&#169; Leymat. All rights reserved.</p>
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

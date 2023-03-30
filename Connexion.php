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

    <title>Dark Chat</title>
  </head>
  <body>
    <!--  HEADER -->
    <header class="header" id="header">
      <nav class="nav container">
          <div class="nav_btns">
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
          <div class="home_content title flex">
            <a href="./Connexion.html.php" class="nav_logo title">Dark Chat</a>
          </div>
          <div class="home_content_form flex">
            <form action="./php/serveurJWT.php" method="POST" class="form">
              <label for="identifiant" class="label"> Identifiant : <br><input type="text" name="identifiant" id="id" class="form_input" placeholder="Identifiant"></label><br>
              <label for="pwd" class="label"> Mot de passe : <br><input type="text" name="pwd" id="pwd" class="form_input" placeholder="password"></label><br>
              <?php
                if(isset($_GET["error"])) {
              ?>
                <span class="error error--message">  Mot de passe ou identifiant incorrect  </span> </br>
              <?php
                }
              ?>
              <input type="submit" value="Connexion" class="button button--flex log_btn">
            </form>
          </div>
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

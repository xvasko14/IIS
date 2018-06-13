<html lang="en" class="gr__getbootstrap_com">
<head>
  <?php include 'head.php';?>
  <title>IIS - Prehľad predmetov</title>
</head>
<?php
  session_save_path("./tmp");
  session_start();
  require_once('./html/php/class.php');
  // if
  $login_class = new Login($_SESSION['login']);
  $login_class->init_session();
  if ($login_class->get_user() == "student") {
    $student = new Student($_SESSION['login']);
?>


<body data-gr-c-s-loaded="true" style="">
    <div class="container-fluid" style="">
      <div class="row">
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-slack sidebar" style="">
            <div style="">
                <div style="">
                  <img src="./img/logo_sh.png" style="width:100%;">
                </div>
            </div>
          <ul class="nav nav-pills  flex-column">
            <li class="nav-item ">
              <a class="nav-link" href="reg-subj.php">Registrácia pedmetov<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="#">Zapísané predmety</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list-study.php">Prehľad študia</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Profil</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="./html/php/logout.php">Odhlásiť</a>
            </li>
          </ul>
        </nav>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Zapísané predmety</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Na tejto stránke môžete videť všetky aktuálne zapísané predmety v ak. roku 2017/2018.
          </span>
          <h2>Prehľad zapísaných predmetov</h2>
          <div class="table-responsive">
            <h3><?php $student->get_year(true); ?></h3>
            <div class="form-group float-lg-right col-md-3" style="margin-top:5px;">
              <input type="text" class="search_winter form-control" onkeyup="myFunction('winter', 0, 3)" placeholder="What you looking for?">
            </div>
            <table class="table table-striped results_winter table-hover">
              <thead>
                <tr>
                  <th colspan="7">Zimný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Body</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  // Winter term subjects if exists within this year
                  $student->get_subject("Zimny", true);
                ?>
              </tbody>
            </table>
            <br>
            <div class="form-group float-lg-right col-md-3" style="margin-top:5px;">
              <input type="text" class="search_summer form-control" onkeyup="myFunction('summer', 0, 3)" placeholder="What you looking for?">
            </div>
            <table class="table table-striped results_summer table-hover">
              <thead>
                <tr>
                  <th colspan="7">Letný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Body</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $student->get_subject("Letny", true);
                ?>
              </tbody>
            </table>
            <br>
            <table class="table table-responsive">
              <thead>
                <tr>
                  <th>Povinné</th>
                  <th>Povinno-voliteľné</th>
                  <th>Voliteľne</th>
                  <th>Celkovo</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $student->get_count();
                ?>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


</body>

<?php
  } else if ($login_class->get_user() == "garant" || $login_class->get_user() == "administrator") {
    require_once('./html/php/garant.php');
    $garant = new Garant($_SESSION["login"]);
    if (isset($_GET["subj"]) && isset($_GET["rok"])) {
      $garant->delete_subject_prihlasuje();
    }
?>
<body data-gr-c-s-loaded="true" style="">
    <div class="container-fluid" style="">
      <div class="row">
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-slack sidebar" style="">
            <div style="">
                <div style="">
                  <img src="./img/logo_sh.png" style="width:100%;">
                </div>
            </div>
          <ul class="nav nav-pills  flex-column">
            <li class="nav-item ">
              <a class="nav-link active" href="#">Zoznam predmetov<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list-field.php">Študíjne obory</a>
            </li>

          <?php
            if($login_class->get_user() == "administrator") {
            ?>
            <li class="nav-item">
              <a class="nav-link" href="list-rules.php">Pravidlá registrácií</a>
            </li>
          </ul>
            <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="user-mntc.php">Správa účtov</a>
            </li>
          </ul>
          <?php
          } else {
            echo "</ul>";
          }
          ?>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Profil</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="./html/php/logout.php">Odhlásiť</a>
            </li>
          </ul>
        </nav>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Zoznam všetkých predmetov</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Na tejto stránke môžete videť všetky predmety.
          </span>
          <h2>Vytvoriť predmet</h2>
            <button type="button" onclick="window.location.href='create-subj.php'" class="btn btn-success" >Vytvor predmet</button>
          <br>
          <br>

          <h2>Prehľad predmetov</h2>
          <div class="table-responsive">
            <div class="form-group float-lg-right col-md-3" style="margin-top:5px;">
              <input type="text" class="search_winter form-control" onkeyup="myFunction('winter', 0, 3)" placeholder="What you looking for?">
            </div>
            <table class="table table-striped results_winter table-hover">
              <thead>
                <tr>
                  <th colspan="9">Zimný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Obor</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Zapísaných</th>
                  <th>Ročník</th>
                  <th>Akcie</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $garant->show_subjects();
                ?>
              </tbody>
            </table>
            <br>
            <div class="form-group float-lg-right col-md-3" style="margin-top:5px;">
              <input type="text" class="search_summer form-control" onkeyup="myFunction('summer', 0, 3)" placeholder="What you looking for?">
            </div>
            <table class="table table-striped results_summer table-hover">
              <thead>
                <tr>
                  <th colspan="9">Letný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Obor</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Zapísaných</th>
                  <th>Ročník</th>
                  <th>Akcie</th>
                </tr>
              </thead>
              <tbody>
               <?php
                  $garant->show_subjects("Letny");
                ?>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


</body>
</html>

<?php
  } else {
    header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/");
  }
?>
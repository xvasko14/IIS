<?php
  session_save_path("./tmp");
  session_start();
  require_once('./html/php/class.php');
  // if
  $login_class = new Login($_SESSION['login']);
  $login_class->init_session();


  if ($login_class->get_user() == "student") {
    $student = new Student($_SESSION['login']);
    if(isset($_POST["Submit"])) {
      $student->change_register_subject();
      // header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/reg-subj.php");
    }
?>
<html lang="en" class="gr__getbootstrap_com">
<head>
  <?php include 'head.php';?>
  <title>IIS - registrácia predmetov</title>
</head>

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
              <a class="nav-link active" href="#">Registrácia pedmetov<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list-subj.php">Zapísané predmety</a>
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
          <h1>Registrácia predmetov</h1>
          <span class=".text-left" style="width: 45%; display:block; margin-bottom: 15px;">
            V nasledujúcej ponuke si možete zaregistrovať či poprípade odregistrovať predmety. Pre úspešné zvládnutie semestru je potrebné získať aspoň 15 kreditov. Pre postup do ďalšieho ročníka potrebujete za tento akademický rok získať aspoň 30 kreditov.
          </span>
          <h2>Prehľad všetkých predmetov</h2>
          <form method="POST">
          <div class="table-responsive">
            <div class="form-group float-lg-right col-md-3" style="margin-top:5px;">
              <input type="text" class="search_winter form-control" onkeyup="myFunction('winter', 0, 3)" placeholder="What you looking for?">
            </div>
            <table class="table table-striped results_winter table-hover">
              <thead>
                <tr>
                  <th colspan="8">Zimný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Voľných</th>
                  <th>Registrovaný</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  // Show winter subjects register for current Academic year
                  $student->show_subj_register();
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
                  <th colspan="8">Letný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Voľných</th>
                  <th>Registrovaný</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  // Summer subject registration list
                  $student->show_subj_register("Letny");
                ?>
              </tbody>
            </table>
            <br>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Povinné</th>
                  <th>Povinno-voliteľné</th>
                  <th>Voliteľne</th>
                  <th>Celkovo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php
                    //TODO
                    $student->show_subj_count();
                  ?>
                </tr>
              </tbody>
            </table>
            <button type="submit" name="Submit" class="btn btn-primary" style="margin: 0 5px 15px 0; float:right;">Potvrď</button>
          </div>
        </form>
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
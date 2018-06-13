<?php
  session_save_path("./tmp");
  session_start();
  require_once('./html/php/class.php');
  // if
  $login_class = new Login($_SESSION['login']);
  $login_class->init_session();
  if ($login_class->get_user() == "administrator") {
    require_once("./html/php/admin.php");
    $admin = new Admin($_SESSION['login']);
    if (isset($_GET["id"])) {
      $admin->delete_rule();
    }
?>

<html lang="en" class="gr__getbootstrap_com">
<head>
  <?php include 'head.php';?>
  <title>IIS - Prehľad pravidiel</title>
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
              <a class="nav-link" href="list-subj.php">Zoznam predmetov<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list-field.php">Študíjne obory</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="list-rules.php">Pravidlá registrácií</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="user-mntc.php">Správa účtov</a>
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
          <h1>Zoznam všetkých pravidiel</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Na tejto stránke môžete videť všetky pravidlá pre registráciu.
          </span>
          <h2>Vytvoriť pravidlo</h2>
            <button type="button" onclick="window.location.href='create-rule.php'" class="btn btn-success" >Vytvor pravidlo</button>
          <br>
          <br>

          <h2>Prehľad pravidiel</h2>
          <div class="table-responsive">
            <div class="form-group float-lg-right col-md-3" style="margin-top:5px;">
              <input type="text" class="search_rules form-control" onkeyup="myFunction('rules', 0, -1)" placeholder="What you looking for?">
            </div>
            <table class="table table-striped results_rules table-hover">
              <thead>
                <tr>
                  <th>ID pravidla</th>
                  <th>Počet kreditov</th>
                  <th>Maximálny počet registrácií</th>
                  <th>Ročný kreditový strop</th>
                  <th>Operácie</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $admin->list_rules();
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
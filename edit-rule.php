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
    if (isset($_POST["Submit"])) {
      $admin->update_rule();
    }
    if (isset($_GET["id"])) {
      $options = $admin->edit_rule();
    }

?>


<html lang="en" class="gr__getbootstrap_com">
<head>
    <?php include 'head.php';?>

    <title>IIS - Zmeň pravidlo</title>

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
              <a class="nav-link" href="list-rules.php">Pravidlá registrácií</a>
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
          <h1>Vytvorenie pravidla</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Vyplnťe informácie o pravidle
          </span>
          <h2>Zmeň pravidlo</h2>
          <form method="POST">
            <div class="form-row">
              <div class="form-group col-md-3 required">
                <label class="control-label" for="inputEmail4">Počet kreditov</label>
                <input type="number" class="form-control" id="inputEmail4" placeholder="60" name="kredity" value="<?php echo $options["Pocet_kreditov"]; ?>" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3 required">
                <label class="control-label" for="inputPassword4">Maximálny počet registrácií</label>
                <input type="number" class="form-control" id="inputPassword4" placeholder="12" name="pocet" value="<?php echo $options["Max_pocet_registracii"]; ?>" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3 required">
                <label class="control-label" for="inputAddress">ročný kreditový strop</label>
                <input type="number" class="form-control" id="inputAddress" placeholder="75" name="strop" value="<?php echo $options["Rocny_kreditovy_strop"]; ?>" required>
              </div>
            </div>
            <button type="submit" name="Submit" class="btn btn-primary">Uložiť</button>
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
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
      $admin->create_division();
    }
    $options = $admin->get_options();
?>

<html lang="en" class="gr__getbootstrap_com">
<head>
  <?php include 'head.php';?>
  <title>IIS - zmena programu</title>
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
          <h1>Študijný obor</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Na tejto stránke je možné vytvoriť študijný obor.
          </span>
          <h2>Vytvoriť študijný obor</h2>
          <form method="POST">
            <div class="form-row">
              <div class="form-group col-md-4 required">
                <label class="control-label" for="inputEmail4">Skratka</label>
                <input type="text" class="form-control" id="Name" placeholder="Názov" name="skratka" value="<?php echo $options["Skratka_programu"]; if (isset($_POST["skratka"])) echo $_POST["skratka"];?>"  required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-2 required">
                <label class="control-label" for="inputCity">Dátum ukončenia akreditácie</label>
                <input type="text" class="form-control" id="inputCity" placeholder="RRRR"  name="akredit" value="<?php echo $options["Akreditacia"]; if (isset($_POST["akredit"])) echo $_POST["akredit"];?>" required="required">
              </div>
              <div class="form-group col-md-2 required">
                <label for="inputState">Typ pravidla</label>
                <select id="inputState" class="form-control" name="pravidlo" required>
                <?php
                  $admin->show_rules();
                ?>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-2 required">
                <label class="control-label" for="inputAddress">Garant</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="Meno" name="garant" value="<?php if (isset($_POST["garant"])) echo $_POST["garant"];?>" required>
              </div>
              <div class="form-group required col-md-2">
                <label class="control-label" for="inputZip">Odbor</label>
                <input type="text" class="form-control" id="inputZip" placeholder="Doplnte sem názov odboru" name="odbor" value="<?php echo $options["Odbor"]; if (isset($_POST["odbor"])) echo $_POST["odbor"];?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="inputState">Forma štúdia</label>
                <select id="inputState" class="form-control" name="forma">
                  <option value="prezenčná" selected>Prezenčná</option>
                  <option value="externá">Externá</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="inputState">Doba štúdia</label>
                <select id="inputState" class="form-control" name="doba">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3" selected>3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>
            </div>
            Prvky označené <span style="color: #d00;position: relative; margin-left: 4px; top: -6px;">*</span> sú povinné.
            <br>
            <br>
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
}else {
  header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/");
}
?>
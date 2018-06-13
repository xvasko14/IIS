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
      $admin->create_user();
    }
?>

<html lang="en" class="gr__getbootstrap_com">
<head>
    <?php include 'head.php';?>
    <title>IIS - Vytvorenie užívateľa</title>

    <script>
      function typeVisibility() {
          var id = document.getElementById("typeSelector").selectedIndex;
          // alert(id);
          var item;
          if(id == 0){
            item = "student";
          }
          else if(id == 1){
            item = "zamestnanec";
          }
          else{
            item = "zamestnanec";
          }
          var a = document.getElementsByClassName("userType");
          a.student.style.display = "none";
          a.zamestnanec.style.display = "none";

          document.getElementById(item).style.display = "block";
      };
    </script>
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
          <h1>Vytvorenie užívateľa</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Na tejto stránke môžete pridať nového užívateľa.
          </span>
          <h2>Informácie o užívateľovi</h2>
          <form method="POST">
            <div class="form-row">
              <div class="form-group col-md-2 required">
                <label class="control-label" for="inputAddress">Meno</label>
                <input type="text" class="form-control" id="Name" placeholder="Meno a Priezvisko" name="meno" value="<?php if (isset($_POST["meno"])) echo $_POST["meno"];?>" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-2 required">
                <label class="control-label" for="inputAddress">Login</label>
                <input type="text" class="form-control" id="Name" placeholder="XNAMEYY" name="login" value="<?php if (isset($_POST["login"])) echo $_POST["login"];?>" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4 required">
                <label class="control-label" for="inputEmail4">Email</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="Email" name="email" value="<?php if (isset($_POST["email"])) echo $_POST["email"];?>" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-2 required">
                <label for="inputPassword4">Heslo</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Heslo" name="heslo">
              </div>
              <div class="form-group col-md-2 required">
                <label for="inputPassword4">Heslo znova</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Heslo" name="heslo_potvrd">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label class="control-label" for="inputAddress">Adresa</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="Ulica a popisné číslo" input="adresa" value="<?php if (isset($_POST["adresa"])) echo $_POST["adresa"];?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-2 required">
                <label class="control-label" for="inputCity">Mesto</label>
                <input type="text" class="form-control" id="inputCity" placeholder="Mesto" required="required" name="mesto" value="<?php if (isset($_POST["mesto"])) echo $_POST["mesto"];?>">
              </div>
              <div class="form-group required col-md-2">
                <label class="control-label" for="inputZip">PSČ</label>
                <input type="text" class="form-control" id="inputZip" placeholder="000 00" required="required" name="psc" value="<?php if (isset($_POST["psc"])) echo $_POST["psc"];?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="typeSelector">Typ účtu</label>
                <select id="typeSelector" class="form-control" onchange="typeVisibility()" name="typ">
                  <option value="student">Študent</option>
                  <option value="zamestnanec">Zamestnanec</option>
                  <option value="admin">Administrátor</option>
                </select>
              </div>
            </div>
            <div class="userType" id="student">
              <h4>Atribúty pre typ študent</h4>
              <div class="form-row">
                <div class="form-group col-md-2">
                  <label for="inputState">Obor</label>
                 <select id="inputState" class="form-control" name="odbor">

                  <?php
                    $admin->show_odbor();
                  ?>
                  </select>
                </div>
                <div class="form-group col-md-2">
                  <label for="inputState">Rocnik</label>
                  <select id="inputState" class="form-control" name="rocnik">
                    <option value="1" selected>1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="userType" id="zamestnanec">
              <h4>Atribúty pre typ zamestnanec</h4>
              <div class="form-row">
                <div class="form-group col-md-2">
                  <label for="inputState">Ústav</label>
                  <select id="inputState" class="form-control" name="ustav">
                    <!-- TODO -->
                    <option value="UIFS" selected>UIFS</option>
                    <option value="UPGM">UPGM</option>
                    <option value="UVST">UVST</option>
                  </select>
                </div>
                <div class="form-group col-md-2">
                  <label for="inputState">Vyučuje</label>
                  <select id="inputState" class="form-control" name="vyucuje">
                    <option value="1">Áno</option>
                    <option value="0" selected>Nie</option>
                  </select>
                </div>
              </div>
            </div>
            Prvky označené <span style="color: #d00;position: relative; margin-left: 4px; top: -6px;">*</span> sú povinné.
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
} else {
  header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/index.php");
}
?>
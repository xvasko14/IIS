<!DOCTYPE html>
<html lang="en" class="gr__getbootstrap_com">
<head>
  <?php include 'head.php';?>

  <title>IIS-prihlasovanie do predmetov</title>
</head>

<?php
session_save_path("./tmp");
session_start();
ini_set("default_charset", "utf-8");
require_once('./html/php/class.php');
require_once("./html/php/session.php"); // creates $login_class class
if (!isset($_SESSION['login']) && !isset($_SESSION['logged_in'])) {
?>
<body>
	<div style="
	    background-image: url(./img/bg.png);
	    display: block;
	    width: 100%;
	    height: 100%;
	    position: absolute;
	    z-index: -1;
	    top: 0;
	    background-repeat: no-repeat;
	    background-size: 100%;
	    filter: blur(10pt);
	    -webkit-filter: blur(10pt);
	"></div>
	<div class="container" style="max-width: 300px; margin-top: 10%; margin-bottom:10%;">
		<form method="POST">
			<div class="form-group">
				<label for="exampleInputLogin">Login</label>
				<input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter login" name="input_login" value="<?php if($save === 1) echo $_POST["input_login"];?>">
				<small id="emailHelp" class="form-text text-muted">We'll never share your login with anyone else.</small>
			</div>

			<div class="form-group">
				<label for="exampleInputPassword1">Password</label>
				<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="input_password">
			</div>
			<button type="submit" name="Submit2" class="btn btn-primary">Login</button>
		</form>
	</div>
</body>
<?php
} else {
	require_once('./html/php/class.php');
	$login_class = new Login($_SESSION['login']);
	$login_class->check_session();
	if ($login_class->get_user() == "student") {
		$student = new Student($_SESSION['login']);
		if (isset($_POST["Submit"])) {
			$student->change_information();
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
              <a class="nav-link" href="reg-subj.php">Registrácia pedmetov<span class="sr-only">(current)</span></a>
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
              <a class="nav-link active" href="#">Profil</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="./html/php/logout.php">Odhlásiť</a>
            </li>
          </ul>
        </nav>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Profil</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Na tejto stránke môžete upraviť svoje osobné informácie.
          </span>
          <h2>Osobné informácie</h2>
          <form method="POST">
            <div class="form-row">
              <div class="form-group col-md-3 required">
                <label class="control-label" for="inputEmail4">Email</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="Email" name="email" value="<?php if (isset($_POST["email"])) echo $_POST["email"];?>" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="inputPassword4">Heslo</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Heslo" name="heslo">
              </div>
              <div class="form-group col-md-3">
                <label for="inputPassword4">Heslo znova</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Heslo" name="heslo_potvrd">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3 ">
                <label class="control-label" for="inputAddress">Adresa</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="Ulica a popisné číslo" name="adresa" value="<?php if (isset($_POST["adresa"])) echo $_POST["adresa"];?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label class="control-label" for="inputCity">Mesto</label>
                <input type="text" class="form-control" id="inputCity" placeholder="Mesto"  name="mesto" value="<?php if (isset($_POST["mesto"])) echo $_POST["mesto"];?>">
              </div>
              <div class="form-group  col-md-3">
                <label class="control-label" for="inputZip">PSČ</label>
                <input type="text" class="form-control" id="inputZip" placeholder="00000"  name="psc" value="<?php if (isset($_POST["psc"])) echo $_POST["psc"];?>">
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
<?php
	} else if ($login_class->get_user() == "garant") {
		require_once('./html/php/garant.php');
		$garant = new Garant($_SESSION['login']);
		if (isset($_POST["Submit"])) {
			$garant->change_information();
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
              <a class="nav-link" href="list-subj.php">Zoznam predmetov<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list-field.php">Študíjne obory</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="index.php">Profil</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="./html/php/logout.php">Odhlásiť</a>
            </li>
          </ul>
        </nav>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Profil</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Na tejto stránke môžete upraviť svoje osobné informácie.
          </span>
          <h2>Osobné informácie</h2>
          <form method="POST">
            <div class="form-row">
              <div class="form-group col-md-3 required">
                <label class="control-label" for="inputEmail4">Email</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="Email" name="email" value="<?php if (isset($_POST["email"])) echo $_POST["email"];?>" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="inputPassword4">Heslo</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Heslo" name="heslo" >
              </div>
              <div class="form-group col-md-3">
                <label for="inputPassword4">Heslo znova</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Heslo" name="heslo_potvrd">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label class="control-label" for="inputAddress">Adresa</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="Ulica a popisné číslo" name="adresa" value="<?php if (isset($_POST["adresa"])) echo $_POST["adresa"];?>" >
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label class="control-label" for="inputCity">Mesto</label>
                <input type="text" class="form-control" id="inputCity" placeholder="Mesto"  name="mesto" value="<?php if (isset($_POST["mesto"])) echo $_POST["mesto"];?>">
              </div>
              <div class="form-group col-md-3">
                <label class="control-label" for="inputZip">PSČ</label>
                <input type="text" class="form-control" id="inputZip" placeholder="00000"  name="psc" value="<?php if (isset($_POST["psc"])) echo $_POST["psc"];?>">
              </div>
            </div>
            Prvky označené <span style="color: #d00;position: relative; margin-left: 4px; top: -6px;">*</span> sú povinné.
            <br>
            <br>
            <button type="submit" class="btn btn-primary" name="Submit">Uložiť</button>
          </form>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


</body>


<?php
	} else {
		require_once('./html/php/garant.php');
		$garant = new Garant($_SESSION['login']);
		if (isset($_POST["Submit"])) {
			$garant->change_information();
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
              <a class="nav-link active" href="index.php">Profil</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="./html/php/logout.php">Odhlásiť</a>
            </li>
          </ul>
        </nav>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Profil</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Na tejto stránke môžete upraviť svoje osobné informácie.
          </span>
          <h2>Osobné informácie</h2>
          <form>
            <div class="form-row">
              <div class="form-group col-md-3 required">
                <label class="control-label" for="inputEmail4">Email</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="Email" required name="email" value="<?php if (isset($_POST["email"])) echo $_POST["email"];?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="inputPassword4">Heslo</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Heslo" name="heslo">
              </div>
              <div class="form-group col-md-3">
                <label for="inputPassword4">Heslo znova</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Heslo" name="heslo_potvrd">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label class="control-label" for="inputAddress">Adresa</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="Ulica a popisné číslo" name="adresa" value="<?php if (isset($_POST["adresa"])) echo $_POST["adresa"];?>" >
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label class="control-label" for="inputCity">Mesto</label>
                <input type="text" class="form-control" id="inputCity" placeholder="Mesto" name="mesto" value="<?php if (isset($_POST["mesto"])) echo $_POST["mesto"];?>">
              </div>
              <div class="form-group col-md-3">
                <label class="control-label" for="inputZip">PSČ</label>
                <input type="text" class="form-control" id="inputZip" placeholder="00000" name="psc" value="<?php if (isset($_POST["psc"])) echo $_POST["psc"];?>">
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

<?php
	}

}
?>
</html>
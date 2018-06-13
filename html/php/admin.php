<?php
require_once('./html/php/garant.php');

class Admin extends Garant{
	private $mysql;
	private $login;

	public function __construct($login)
	{
		require_once("./database/mysql_init.php");
		$this->login = $login;
		$this->mysql = new mysql_class();
		$this->mysql = $this->mysql->get_status();
	}

	public function generate_division() {
		$query = "SELECT * FROM Studijny_program";
		$result = mysqli_query($this->mysql, $query);
		$curr = 0;
		if ($result->num_rows > 0) {
			while( $row = $result->fetch_assoc()) {
				if (($curr % 3) === 0) {
					echo <<<EOL
					<div class="row">
						<div class="col">
						<div class="card">
							<h4 class="card-header">{$row["Odbor"]}</h4>
							<div class="card-body">
								<h4 class="card-title">Detaily</h4>
								<h6 class="card-subtitle mb-2 text-muted"><b>Typ:</b> {$row["Typ_studia"]}</h6>
								<h6 class="card-subtitle mb-2 text-muted"><b>Skratka programu:</b> {$row["Skratka_programu"]}</h6>
								<h6 class="card-subtitle mb-2 text-muted"><b>Akreditácia do:</b> {$row["Akreditacia"]}</h6>
								<p class="card-text">{$row["Popis"]}.</p>
								<a href="edit-field.php?skr={$row["Skratka_programu"]}&rok={$row["Ak_rok"]}" class="btn btn-secondary">Upraviť</a>
								<button type="button" onclick="{$row['Skratka_programu']}_{$row["Ak_rok"]}()" class="btn btn-danger" >Vymaž</button>
							</div>
						 </div>
					</div>
					<script>
        function {$row['Skratka_programu']}_{$row["Ak_rok"]}() {
        	var redirect = "list-field.php?prog={$row['Skratka_programu']}&rok={$row["Ak_rok"]}";
        	var result = confirm("Want to delete?");
        	if (result) {
        		window.location.href=redirect;
        	}
        }
        </script>
EOL;
				} else {
					echo <<<EOL
						<div class="col">
						<div class="card">
							<h4 class="card-header">{$row["Odbor"]}</h4>
							<div class="card-body">
								<h4 class="card-title">Detaily</h4>
								<h6 class="card-subtitle mb-2 text-muted"><b>Typ:</b> {$row["Typ_studia"]}</h6>
								<h6 class="card-subtitle mb-2 text-muted"><b>Skratka programu:</b> {$row["Skratka_programu"]}</h6>
								<h6 class="card-subtitle mb-2 text-muted"><b>Akreditácia do:</b> {$row["Akreditacia"]}</h6>
								<p class="card-text">{$row["Popis"]}.</p>
								<a href="edit-field.php?skr={$row["Skratka_programu"]}&rok={$row["Ak_rok"]}" class="btn btn-secondary">Upraviť</a>
								<button type="button" onclick="{$row['Skratka_programu']}_{$row["Ak_rok"]}()" class="btn btn-danger" >Vymaž</button>
							</div>
						 </div>
						 </div>
						 <script>
        function {$row['Skratka_programu']}_{$row["Ak_rok"]}() {
        	var redirect = "list-field.php?prog={$row['Skratka_programu']}&rok={$row["Ak_rok"]}";
        	var result = confirm("Want to delete?");
        	if (result) {
        		window.location.href=redirect;
        	}
        }
        </script>
EOL;
				}
				if (($curr % 3) === 2) {
					echo "</div><br>";
				}
				$curr++;
			}
		}
	}
	/* vymaze program z tabulky + rekurzivne studentov a prihlasovane predmety */
	public function delete_program($prog, $rok)
	{

		$program = mysqli_real_escape_string($this->mysql, $prog);
		$rok = mysqli_real_escape_string($this->mysql, $rok);

		// DELETE students, osoba, and predmet
		$query = "SELECT Login FROM Student NATURAL JOIN Osoba WHERE Student.Skratka_programu='$program' AND Student.Ak_rok=$rok ";
		$result = mysqli_query($this->mysql, $query);

		while($row = $result->fetch_assoc()) {
			$query = "DELETE FROM Zamestnanec WHERE Login='" . $row["Login"] . "'";
			$qry = mysqli_query($this->mysql, $query);

			$query = "DELETE FROM Prihlasuje WHERE Login='" . $row["Login"] . "'";
			$qry = mysqli_query($this->mysql, $query);


			$query = "DELETE FROM Student WHERE Login='" . $row["Login"] . "'";
			$qry = mysqli_query($this->mysql, $query);
			if ($qry) {
				// echo "Success";
			} else {
				// echo "Failure";
			}

			$query = "DELETE FROM Osoba WHERE Login='" . $row["Login"] . "'";
			$qry = mysqli_query($this->mysql, $query);
			if ($qry) {
				// echo "Success";
			} else {
				// echo "Failure";
			}
		}
		// Odstran vsetkych co su mimo oborovy
		$query = "SELECT Skratka_predmetu FROM Predmet WHERE Skratka_programu='$program' AND Ak_rok=$rok";
		$vysl = mysqli_query($this->mysql, $query);
		while($row= $vysl->fetch_assoc()) {
			$query = "DELETE FROM Prihlasuje WHERE Skratka_predmetu='" . $row["Skratka_predmetu"] ."'";
			$result = mysqli_query($this->mysql, $query);
		}

		$query = "DELETE FROM Predmet WHERE Skratka_programu='$program' AND Ak_rok=$rok";
		mysqli_query($this->mysql, $query);

		$query = "DELETE FROM Studijny_program WHERE Skratka_programu='$program' AND Ak_rok=$rok";
		$result = mysqli_query($this->mysql, $query);
		if ($result) {
		} else {
		}
	}

	/* zobraz uzivatelov*/
	public function show_users()
	{
		$query = "SELECT * FROM Osoba NATURAL JOIN Spravca";
		$this->user_query($query, "Spravca");
		$query = "SELECT * FROM Osoba NATURAL JOIN Zamestnanec WHERE Login NOT IN (SELECT Login FROM Osoba NATURAL JOIN Spravca)";
		$this->user_query($query);
		$query = "SELECT * FROM Osoba NATURAL JOIN Student";
		$this->user_query($query, "Student");

	}

	/* formatovanie uzivatelov do bootstrapovej sablony */
	private function user_query($query, $type="Zamestnanec") {
		$result = mysqli_query($this->mysql, $query);
		while($row = $result->fetch_assoc()) {
			if ($type == "Student") {
				echo <<<EOL
					<tr>
					<td>{$row["Login"]}</td>
					<td>{$row["Meno"]}</td>
					<td>{$row["Email"]}</td>
					<td>{$row["Mesto"]}</td>
					<td>
						<select id="inputState" class="form-control" name="{$row["Login"]}">
							<option value="Student" selected>Študent</option>
							<option value="Garant" >Garant</option>
							<option value="Admin">Admin</option>
						</select>
					</td>
					<td>26. 11. 2017 19:27:11</td>
	        <td>26. 11. 2017 19:27:11</td>
	        <td>
	          <button type="button" onclick="func{$row['Login']}()" class="btn btn-danger" >Vymaž</button>
	        </td>
					</tr>
				<script>
        function func{$row['Login']}() {
        	var redirect = "user-mntc.php?login={$row['Login']}";
        	var result = confirm("Want to delete?");
        	if (result) {
        		window.location.href=redirect;
        	}
        }
        </script>
EOL;
			} else if ($type == "Spravca") {
				echo <<<EOL
					<tr>
					<td>{$row["Login"]}</td>
					<td>{$row["Meno"]}</td>
					<td>{$row["Email"]}</td>
					<td>{$row["Mesto"]}</td>
					<td>
						<select id="inputState" class="form-control" name="{$row["Login"]}">
							<option value="Student" >Študent</option>
							<option value="Garant" >Garant</option>
							<option value="Admin" selected>Admin</option>
						</select>
					</td>
					<td>26. 11. 2017 19:27:11</td>
	        <td>26. 11. 2017 19:27:11</td>
	        <td>
	          <button type="button" onclick="func{$row['Login']}()" class="btn btn-danger" >Vymaž</button>
	        </td>
					</tr>
					<script>
        function func{$row['Login']}() {
        	var redirect = "user-mntc.php?login={$row['Login']}";
        	var result = confirm("Want to delete?");
        	if (result) {
        		window.location.href=redirect;
        	}
        }
        </script>
EOL;

			} else {
			echo <<<EOL
					<tr>
					<td>{$row["Login"]}</td>
					<td>{$row["Meno"]}</td>
					<td>{$row["Email"]}</td>
					<td>{$row["Mesto"]}</td>
					<td>
						<select id="inputState" class="form-control" name="{$row["Login"]}">
							<option value="Student">Študent</option>
							<option value="Garant" selected>Garant</option>
							<option value="Admin">Admin</option>
						</select>
					</td>
					<td>26. 11. 2017 19:27:11</td>
	        <td>26. 11. 2017 19:27:11</td>
	        <td>
	          <button type="button" onclick="func{$row['Login']}()" class="btn btn-danger" >Vymaž</button>
	        </td>
					</tr>
					<script>
        function func{$row['Login']}() {
        	var redirect = "user-mntc.php?login={$row['Login']}";
        	var result = confirm("Want to delete?");
        	if (result) {
        		window.location.href=redirect;
        	}
        }
        </script>
EOL;
			}
		}
	}

	/* vymaz uzivatela podla typu ci je spravca / garant alebo student*/
	public function delete_user() {
		$login = mysqli_real_escape_string($this->mysql, $_GET["login"]);
		$query = "DELETE FROM Prihlasuje WHERE Login='$login'";
		mysqli_query($this->mysql, $query);

		$query = "DELETE FROM Student WHERE Login='$login'";
		$result = mysqli_query($this->mysql, $query);
		if ($result) {
			$swal = new Swal_select("success", "Informácie", "boli zmenené");
			$swal->print_msg();
			// echo "Success";
		} else {
			$swal = new Swal_select("error", "Informácie", "boli zmenené");
			$swal->print_msg();
			// echo "Failure";
		}

		$query = "DELETE FROM Spravca WHERE Login='$login'";
		$result = mysqli_query($this->mysql, $query);

		$query = "DELETE FROM Zamestnanec WHERE Login='$login'";
		$result = mysqli_query($this->mysql, $query);

		$query = "DELETE FROM Osoba WHERE Login='$login'";
		$result = mysqli_query($this->mysql, $query);
	}

	/* vytvor noveho uzivatela za podmienok ze si zadal vsetky potrebne udaje  + ochrana proti SQL injection */
	public function create_user()
	{
		if(!empty($_POST["meno"]) && !empty($_POST["login"]) && !empty($_POST["email"]) && !empty($_POST["heslo"]) && !empty($_POST["heslo_potvrd"]) && !empty($_POST["mesto"]) && !empty($_POST["psc"]) && !empty($_POST["typ"]) && !empty($_POST["rocnik"])) {

			$meno = mysqli_real_escape_string($this->mysql, $_POST["meno"]);
			$login = mysqli_real_escape_string($this->mysql, $_POST["login"]);
			$email = mysqli_real_escape_string($this->mysql, $_POST["email"]);
			$heslo = mysqli_real_escape_string($this->mysql, $_POST["heslo"]);
			$heslo_potvrd = mysqli_real_escape_string($this->mysql, $_POST["heslo_potvrd"]);
			$adresa = "";
			if (isset($_POST["adresa"]))
				$adresa = mysqli_real_escape_string($this->mysql, $_POST["adresa"]);
			$mesto = mysqli_real_escape_string($this->mysql, $_POST["mesto"]);
			$psc = mysqli_real_escape_string($this->mysql, $_POST["psc"]);
			$typ = mysqli_real_escape_string($this->mysql, $_POST["typ"]);
			$rocnik = mysqli_real_escape_string($this->mysql, $_POST["rocnik"]);
			$semester = 1 + (($rocnik - 1) * 2);

			if ($heslo != $heslo_potvrd) {
				break;
			}
			$heslo = base64_encode(hash("sha256", $heslo, true));
			$query = "INSERT INTO Osoba VALUES('$login', '$email', '$meno', '$heslo' , '$adresa', '$mesto', '$psc')";
			mysqli_query($this->mysql, $query);

			if (!empty($_POST["odbor"])) {
				// Student
				$odbor = $_POST["odbor"];
				$odbor = explode("-", $odbor);
				$query = "INSERT INTO Student VALUES ('$login', $rocnik, '$semester', '$odbor[0]',  $odbor[1])";
				$result = mysqli_query($this->mysql, $query);
				if ($result) {
					// $swal = new Swal_select("success", "Informácie", "boli zmenené");
					// $swal->print_msg();
					// echo "Succes";
				} else {
					// $swal = new Swal_select("error", "Informácie", "boli zmenené");
					// $swal->print_msg();
					// echo "Fail";
				}
			} else if (!empty($_POST["ustav"])) {
				// Zamestnanec / Spravca
				$ustav = $_POST["ustav"];
				$vyucuje = $_POST["vyucuje"];
				$query = "INSERT INTO Zamestnanec VALUES ('$login', '$ustav', $vyucuje)";
				mysqli_query($this->mysql, $query);
				if ($typ == "admin") {
					$query = "INSERT INTO Spravca VALUES ('$login')";
					mysqli_query($this->mysql, $query);
				}
			}

		}
	}

	/* uprav uzivatelove prava */
	public function edit_user()
	{
		$query = "SELECT Login FROM Osoba";
		$result= mysqli_query($this->mysql, $query);
		while($row = $result->fetch_assoc()) {
			$change = $_POST["{$row["Login"]}"];
			$query = "SELECT * FROM Student WHERE Login='" . $row["Login"] . "'";
			$vysledok = mysqli_query($this->mysql, $query);
			if ($this->change_user_permissions($change, $vysledok, "Student")) {
				continue;
			}
			$query = "SELECT * FROM Spravca WHERE Login='" . $row["Login"] . "'";
			$vysledok = mysqli_query($this->mysql, $query);
			if ($this->change_user_permissions($change, $vysledok, "Admin")) {
				continue;
			}
			$query = "SELECT * FROM Zamestnanec WHERE Login='" . $row["Login"] . "'";
			$vysledok = mysqli_query($this->mysql, $query);
			if ($this->change_user_permissions($change, $vysledok, "Garant")) {
				continue;
			}
		}
	}

	/* zobraz studijny program podla typu oboru */
	public function create_division()
	{
		if (!empty($_POST["skratka"]) && !empty($_POST["akredit"]) && !empty($_POST["doba"]) && !empty($_POST["garant"]) && !empty($_POST["pravidlo"])) {

			$skratka = mysqli_real_escape_string($this->mysql, $_POST["skratka"]);
			$akr = mysqli_real_escape_string($this->mysql, $_POST["akredit"]);
			$odbor = mysqli_real_escape_string($this->mysql, $_POST["odbor"]);
			$forma = mysqli_real_escape_string($this->mysql, $_POST["forma"]);
			$doba = mysqli_real_escape_string($this->mysql, $_POST["doba"]);
			$pravidlo = $_POST["pravidlo"];

			$typ = "Doktorandsky";
			if ($skratka[0] == 'M') {
				$typ = "Magistersky";
			} else if ($skratka[0] == 'B') {
				$typ = "Bakalarsky";
			}
			$query = "INSERT INTO Studijny_program VALUES ('$skratka', '$typ', '$odbor'," . date("Y") . ", $akr, $doba, '$forma', $pravidlo, '')";
			$result = mysqli_query($this->mysql, $query);

		}
	}

	/* ukaz v elemente select vsetky obory ktore ma databaza v sebe */
	public function show_odbor()
	{
		$query = "SELECT Skratka_programu, Ak_rok FROM Studijny_program";
		$result = mysqli_query($this->mysql, $query);
		while($row = $result->fetch_assoc()) {
			$prog = $row["Skratka_programu"];
			$rok = $row["Ak_rok"];
			echo "<option value='$prog-$rok'> $prog-$rok</option>";
		}
	}

	/* ukaz v elemente select vsetky pravidla ktore obsahuje databaza */
	public function show_rules()
	{
		$query = "SELECT * FROM Pravidlo";
		$result = mysqli_query($this->mysql, $query);
		while($row = $result->fetch_assoc()) {
			$id = $row["Id_pravidla"];
			$pocet = $row["Pocet_kreditov"] . "K-";
			$strop = $row["Rocny_kreditovy_strop"] . "Strop";
			$name = "pravidlo " . $pocet . $strop;
			// echo $name;
			echo "<option value='$id'>$name</option>";
		}
	}

	/* zmen uzivatelove prava, ak je student a chce byt garant sa prehodia iba ucty, pri zvyseni prav na administratora sa musi hodit do tabulky zamestnanec a zaroven do tabulky garant */
	private function change_user_permissions($change, $result, $old_permissions)
	{
		if ($result->num_rows > 0) {
			$data = mysqli_fetch_assoc($result);
			// echo $data["Login"] . $old_permissions . $change;
			if ($change == $old_permissions) {
				return false;
			} else {
				// change of permissions delete user and insert him to another table
				if ($old_permissions == "Student") {
					$query = "DELETE FROM Prihlasuje WHERE Login='" . $data["Login"]  . "'";
					mysqli_query($this->mysql, $query);

					$query = "DELETE FROM Student WHERE Login='" . $data["Login"]  . "'";
					$this->delete_user_only($query, $change, $data);
				} else if ($old_permissions == "Admin") {
					$query = "DELETE FROM Spravca WHERE Login='" . $data["Login"]  . "'";
					mysqli_query($this->mysql, $query);
					$query = "DELETE FROM Zamestnanec WHERE Login='" . $data["Login"]  . "'";
					$this->delete_user_only($query, $change, $data);
				} else {
					$query = "DELETE FROM Zamestnanec WHERE Login='" . $data["Login"]  . "'";
					$this->delete_user_only($query, $change, $data);
				}
				return true;
			}
		}
		return false;
	}

	/* vymaze uzivatela a vlozi podla typu */
	private function delete_user_only($query, $change, $data)
	{
		$result = mysqli_query($this->mysql, $query);
		$login = $data["Login"];
		$rocnik = 1;
		$semester = 1;
		$skratka = "BIT";
		$year = date("Y");
		if ($change == "Student") {
			$query = "INSERT INTO Student VALUES ('$login' , $rocnik, $semester, '$skratka', $year)";
			$result = mysqli_query($this->mysql, $query);
			if ($result) {
				// $swal = new Swal_select("success", "Informácie", "boli zmenené");
				// $swal->print_msg();
			} else {
				// $swal = new Swal_select("error", "Informácie", "boli zmenené");.
				// $swal->print_msg();
			}
		} else if ($change == "Garant") {
			$query = "INSERT INTO Zamestnanec VALUES ('$login', '', 0)";
			mysqli_query($this->mysql, $query);
		} else {
			$query = "INSERT INTO Zamestnanec VALUES ('$login', '', 0)";
			mysqli_query($this->mysql, $query);
			$query = "INSERT INTO Spravca VALUES('$login')";
			mysqli_query($this->mysql, $query);
		}
	}

	/* vytvor pravidlo a uloz ho do databaze */
	public function create_rule()
	{
		if (!empty($_POST["kredity"]) && !empty($_POST["strop"])) {
			$query="SELECT Id_pravidla FROM Pravidlo WHERE Id_pravidla	=(SELECT max(Id_pravidla) FROM Pravidlo)";
			$result = mysqli_query($this->mysql, $query);
			$data = mysqli_fetch_assoc($result);
			$max = $data["Id_pravidla"];
			$max++;
			$pocet = 0;
			if (!empty($_POST["pocet"]) && is_numeric($_POST["pocet"])) {
				$pocet = mysqli_real_escape_string($this->mysql, $_POST["pocet"]);
			}
			$kredity = 0;
			$strop = 0;
			if (is_numeric($_POST["kredity"]) && is_numeric($_POST["strop"])) {
				$kredity =  mysqli_real_escape_string($this->mysql, $_POST["kredity"]);
				$strop =  mysqli_real_escape_string($this->mysql, $_POST["strop"]);
			} else {
				break;
			}

			$query = "INSERT INTO Pravidlo VALUES($max, $kredity, $pocet, $strop)";
			mysqli_query($this->mysql, $query);
		}

	}
	/* uprav pravidlo */
	public function edit_rule()
	{
		if (is_numeric($_GET["id"])) {
			$id = mysqli_real_escape_string($this->mysql, $_GET["id"]);
			$query = "SELECT * FROM Pravidlo WHERE Id_pravidla=$id";
			$result = mysqli_query($this->mysql, $query);
			if ($result) {
				$data = mysqli_fetch_assoc($result);
				return $data;
			}
			return array("Pocet_kreditov" => "", "Max_pocet_registracii" => "", "Rocny_kreditovy_strop" => "");
		}
	}
	/* uprav databazu s pravidlom */
	public function update_rule()
	{

		if (!empty($_POST["kredity"]) && !empty($_POST["strop"]) && isset($_GET["id"])) {
			$pocet = 0;
			if (!empty($_POST["pocet"]) && is_numeric($_POST["pocet"])) {
				$pocet = mysqli_real_escape_string($this->mysql, $_POST["pocet"]);
			}
			$kredity = 0;
			$strop = 0;
			if (is_numeric($_POST["kredity"]) && is_numeric($_POST["strop"])) {
				$kredity =  mysqli_real_escape_string($this->mysql, $_POST["kredity"]);
				$strop =  mysqli_real_escape_string($this->mysql, $_POST["strop"]);
			} else {
				break;
			}
			if(is_numeric($_GET["id"]))
				$id = mysqli_real_escape_string($this->mysql, $_GET["id"]);
			else
				break;
			$query = "UPDATE Pravidlo SET Pocet_kreditov=$kredity, Max_pocet_registracii=$pocet, Rocny_kreditovy_strop=$strop WHERE Id_pravidla=$id";
			mysqli_query($this->mysql, $query);
		}
	}

	/* vypis vsetky pravidla */
	public function list_rules()
	{
		$query = "SELECT * FROM Pravidlo";
		$result = mysqli_query($this->mysql, $query);
		while($row = $result->fetch_assoc()) {
			echo <<<EOL
	      <tr>
	        <td>{$row["Id_pravidla"]}</td>
	        <td>{$row["Pocet_kreditov"]}</td>
	        <td>{$row["Max_pocet_registracii"]}</td>
	        <td>{$row["Rocny_kreditovy_strop"]}</td>
	        <td>
	          <button type="button" onclick="window.location.href='edit-rule.php?id={$row["Id_pravidla"]}'" class="btn btn-secondary btn-sm" >Uprav</button>
	          <button type="button" onclick="Pravidlo{$row["Id_pravidla"]}()" class="btn btn-danger btn-sm" >Vymaž</button>
	        </td>
	      </tr>
	      <script>
	      	function Pravidlo{$row["Id_pravidla"]}() {
	      		var redirect = "list-rules.php?id={$row['Id_pravidla']}";
	        	var result = confirm("Want to delete?");
	        	if (result) {
	        		window.location.href=redirect;
	        	}
	      	}
	      </script>
EOL;
		}
	}
	/* vymaz pravidlo a zavolaj zmazanie studijneho programu ktore vola dalsie potrebne zmazania vramci tabuliek studenta, prihlasovania a predmetov */
	public function delete_rule()
	{
		if (is_numeric($_GET["id"])){
			$id = $_GET["id"];
			$query = "SELECT DISTINCT Skratka_programu, Ak_rok FROM Pravidlo, Studijny_program WHERE $id=Studijny_program.Cislo_pravidla";
			$result = mysqli_query($this->mysql, $query);
			while($row = $result->fetch_assoc()) {
				$this->delete_program($row["Skratka_programu"], $row["Ak_rok"]);
			}
			$query = "DELETE FROM Pravidlo WHERE Id_pravidla=$id";
			mysqli_query($this->mysql, $query);
		}
	}

}


?>
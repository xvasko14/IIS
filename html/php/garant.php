<?php

class Garant {
	private $login;
	private $mysql;

	public function __construct($login)
	{
		require_once("./database/mysql_init.php");
		$this->mysql = new mysql_class();
		$this->mysql = $this->mysql->get_status();
		$this->login = $login;
	}

	private function check_update_query($post, $where, $type="Email") {
		$query = "UPDATE Osoba SET $type='$post'WHERE Osoba.Login='$where'";
		$result = mysqli_query($this->mysql, $query);
		return $result;
	}

	public function change_information()
	{
		$str_time = time();
		$_SESSION['timestamp'] = $str_time;
		$failure = false;
		if (!empty($_POST["heslo"]) && is_string($_POST["heslo"])) {
			if (!empty($_POST["heslo_potvrd"]) && is_string($_POST["heslo_potvrd"])) {
				if ($_POST["heslo"] != $_POST["heslo_potvrd"]) {
					$failure = true;
					$swal = new Swal_select("error", "Heslo", "sa nezhoduje, prosím vyplňte znova");
					$swal->print_msg();
				}
			} else {
				$failure = true;
			}
			$heslo = base64_encode(hash("sha256", $_POST["heslo"], true));
			$this->check_update_query($heslo, $this->login, "Heslo");
		}

		if(!empty($_POST["email"]) && is_string($_POST["email"])) {
			$this->check_update_query($_POST["email"], $this->login);
		}
		if (!empty($_POST["mesto"]) && is_string($_POST["mesto"])) {
			$this->check_update_query($_POST["mesto"], $this->login, "Mesto");
		}
		if (!empty($_POST["psc"]) && is_numeric($_POST["psc"])) {
			$this->check_update_query($_POST["psc"], $this->login, "PSC");
		}
		if (!empty($_POST["adresa"]) && is_string($_POST["adresa"])) {
			$this->check_update_query($_POST["adresa"], $this->login, "Adresa");
		}
		if (!$failure) {
			$swal = new Swal_select("success", "Informácie", "boli zmenené");
			$swal->print_msg();
		}

	}

	/* vypis vsetky mozne studijne programy a povol upravu */
	public function generate_division()
	{
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
						</div>
					 </div>
				</div>
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
						</div>
					 </div>
					 </div>
EOL;
				}
				if (($curr % 3) === 2) {
					echo "</div><br>";
				}
				$curr++;
			}
		}
	}
	/* vrat vsetky nastavene moznosti v hlavicke $_GET ak su nastavene predvypln ich */
	public function get_options()
	{
		if (isset($_GET["skr"]) && isset($_GET["rok"])) {
			$skratka = mysqli_real_escape_string($this->mysql, $_GET["skr"]);
			$rok = mysqli_real_escape_string($this->mysql, $_GET["rok"]);
			$query = "SELECT * FROM Studijny_program WHERE Skratka_programu='$skratka' AND Ak_rok=$rok";
			$result = mysqli_query($this->mysql, $query);
			$data = mysqli_fetch_assoc($result);
			return $data;
		}
		return array("Skratka_programu" => "", "Akreditacia" => "", "Odbor" => "");

	}

	/* uprav studijny program v databaze  */
	public function update_division()
	{
		if (isset($_GET["skr"]) && isset($_GET["rok"])) {
			$skratka_get = mysqli_real_escape_string($this->mysql, $_GET["skr"]);
			$rok_get = mysqli_real_escape_string($this->mysql, $_GET["rok"]);

			if (isset($_POST["skratka"]) && isset($_POST["akredit"]) && isset($_POST["odbor"]) && isset($_POST["forma"]) && isset($_POST["doba"])) {

				$skratka = mysqli_real_escape_string($this->mysql, $_POST["skratka"]);
				$akr = mysqli_real_escape_string($this->mysql, $_POST["akredit"]);
				$odbor = mysqli_real_escape_string($this->mysql, $_POST["odbor"]);
				$forma = mysqli_real_escape_string($this->mysql, $_POST["forma"]);
				$doba = mysqli_real_escape_string($this->mysql, $_POST["doba"]);
				$query = "UPDATE Studijny_program  SET Skratka_programu='$skratka', Akreditacia=$akr, Odbor='$odbor', Forma_studia='$forma', Doba_studia=$doba WHERE Skratka_programu='$skratka_get' AND Ak_rok='$rok_get'";
				// print_r($query);
				if (mysqli_query($this->mysql, $query)) {
					// echo "Success";
				} else {
					// echo "Fail";
				}
			}
		}
	}

	/* ukaz vsetky predmety a povol upravu a zmazanie predmetu */
	public function show_subjects($term="Zimny")
	{
		$query = "SELECT * FROM Predmet WHERE Predmet.Semester='$term'";
		$result = mysqli_query($this->mysql, $query);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo <<<HEREDOC
				<tr>
          <td>{$row['Skratka_predmetu']}</td>
					<td>{$row['Typ']}</td>
					<td>{$row['Pocet_kreditov']}</td>
					<td>{$row['Nazov']}</td>
					<td>{$row['Skratka_programu']}</td>
					<td>{$row['Fakulta']}</td>
					<td>{$row['Limit_prihlasenych']}</td>
					<td>{$row['Obsadenost']}</td>
          <td>{$row['Rocnik']}</td>
          <td>
            <button type="button" onclick="window.location.href='edit-subj.php?skr={$row['Skratka_predmetu']}&rok={$row['Ak_rok']}'" class="btn btn-secondary btn-sm" >Uprav</button>
            <button type="button" onclick="{$row['Skratka_predmetu']}_{$row["Ak_rok"]}()" class="btn btn-danger btn-sm" >Vymaž</button>
          </td>
        </tr>
        <script>
        function {$row['Skratka_predmetu']}_{$row["Ak_rok"]}() {
        	var redirect = "list-subj.php?subj={$row['Skratka_predmetu']}&rok={$row["Ak_rok"]}";
        	var result = confirm("Want to delete?");
        	if (result) {
        		window.location.href=redirect;
        	}
        }
        </script>
HEREDOC;
			}
		}
	}

	/* vymaz studenta ktory si prihlasuje predmet a dany predmet tlacidlom "Vymaz" */
	public function delete_subject_prihlasuje()
	{
		if (isset($_GET["subj"]) && isset($_GET["rok"])) {
			$subj = mysqli_real_escape_string($this->mysql, $_GET["subj"]);
			$rok = mysqli_real_escape_string($this->mysql, $_GET["rok"]);

			$query = "DELETE FROM Prihlasuje WHERE Skratka_predmetu='$subj' AND Ak_rok=$rok";
			$result = mysqli_query($this->mysql, $query);
			if ($result) {
				// echo "Success";
			} else {
				// echo "Fail";
			}

			$query = "DELETE FROM Predmet WHERE Skratka_predmetu='$subj' AND Ak_rok=$rok";
			$result = mysqli_query($this->mysql, $query);
		}
	}

	/* vrat informacie o predmete pri uprave predmetu */
	public function get_subject_info()
	{
		if (isset($_GET["skr"]) && isset($_GET["rok"])) {
			$subj = mysqli_real_escape_string($this->mysql, $_GET["skr"]);
			$rok = mysqli_real_escape_string($this->mysql, $_GET["rok"]);
			$query = "SELECT * FROM Predmet WHERE Skratka_predmetu='$subj' AND Ak_rok=$rok";
			$result = mysqli_query($this->mysql, $query);
			if ($result) {
				return mysqli_fetch_assoc($result);
			}
			return array();
		}
	}

	/* zmen v databaze dany predmet podla zadanych vstupnych poli*/
	public function edit_subject()
	{
		if (!empty($_POST["nazov"]) && !empty($_POST["skratka"]) && !empty($_POST["kredity"]) && !empty($_POST["cap"]) && !empty($_POST["faculty"]) && !empty($_POST["typ"]) && !empty($_POST["odbor"]) && !empty($_POST["semester"]) && !empty($_POST["rocnik"])) {

			$get_skratka = mysqli_real_escape_string($this->mysql, $_GET["skr"]);
			$get_rok = mysqli_real_escape_string($this->mysql, $_GET["rok"]);

			$nazov = mysqli_real_escape_string($this->mysql, $_POST["nazov"]);
			$skr = mysqli_real_escape_string($this->mysql, $_POST["skratka"]);
			$odbor = mysqli_real_escape_string($this->mysql, $_POST["odbor"]);
			$faculty = mysqli_real_escape_string($this->mysql, $_POST["faculty"]);
			$typ = mysqli_real_escape_string($this->mysql, $_POST["typ"]);
			$cap = mysqli_real_escape_string($this->mysql, $_POST["cap"]);
			$kredity = mysqli_real_escape_string($this->mysql, $_POST["kredity"]);
			$semester = mysqli_real_escape_string($this->mysql, $_POST["semester"]);
			$rocnik = mysqli_real_escape_string($this->mysql, $_POST["rocnik"]);

			$odbor = explode('-', $odbor);
			$query = "UPDATE Predmet SET Skratka_predmetu='$skr', Ak_rok='$odbor[1]', Nazov='$nazov', Typ='$typ', Obsadenost=$cap, Fakulta='$faculty', Semester='$semester', Limit_prihlasenych=$cap, Skratka_programu='$odbor[0]', Pocet_kreditov=$kredity, Rocnik=$rocnik WHERE Ak_rok='$get_rok' AND Skratka_predmetu='$get_skratka';";

			$result = mysqli_query($this->mysql, $query);
			if ($result) {
				$swal = new Swal_select("success", "Heslo", "sa nezhoduje, prosím vyplňte znova");
				$swal->print_msg();
			} else {
				$swal = new Swal_select("error", "Heslo", "sa nezhoduje, prosím vyplňte znova");
				$swal->print_msg();
			}
		}
	}

	/* vloz predmet */
	public function insert_subject()
	{
		if (!empty($_POST["nazov"]) && !empty($_POST["skratka"]) && !empty($_POST["kredity"]) && !empty($_POST["cap"]) && !empty($_POST["faculty"]) && !empty($_POST["typ"]) && !empty($_POST["odbor"]) && !empty($_POST["semester"]) && !empty($_POST["rocnik"])) {

			$nazov = mysqli_real_escape_string($this->mysql, $_POST["nazov"]);
			$skr = mysqli_real_escape_string($this->mysql, $_POST["skratka"]);
			$odbor = mysqli_real_escape_string($this->mysql, $_POST["odbor"]);
			$faculty = mysqli_real_escape_string($this->mysql, $_POST["faculty"]);
			$typ = mysqli_real_escape_string($this->mysql, $_POST["typ"]);
			$cap = mysqli_real_escape_string($this->mysql, $_POST["cap"]);
			$kredity = mysqli_real_escape_string($this->mysql, $_POST["kredity"]);
			$semester = mysqli_real_escape_string($this->mysql, $_POST["semester"]);
			$rocnik =  mysqli_real_escape_string($this->mysql, $_POST["rocnik"]);
			$odbor = explode('-', $odbor);

			$query = "INSERT INTO Predmet VALUES ('$skr','$odbor[1]', '$nazov', '$typ', $cap, 'ZaZk', '$faculty', '$semester', $cap, '$odbor[0]', $kredity, $rocnik);";
			$result = mysqli_query($this->mysql, $query);
			// print_r($query);
			if ($result) {
				$swal = new Swal_select("success", "Heslo", "sa nezhoduje, prosím vyplňte znova");
				$swal->print_msg();
				// echo "Success";
			} else {
				$swal = new Swal_select("error", "Heslo", "sa nezhoduje, prosím vyplňte znova");
				$swal->print_msg();
				// echo "failure";
			}
		} else {
			$swal = new Swal_select("error", "Heslo", "sa nezhoduje, prosím vyplňte znova");
			$swal->print_msg();
		}
	}

	public function show_odbor()
	{
		if (isset($_GET["skr"]) && isset($_GET["rok"])) {
			$query = "SELECT Skratka_programu, Ak_rok FROM Studijny_program NATURAL JOIN Predmet WHERE Skratka_predmetu='" . $_GET["skr"] . "' AND Ak_rok='" . $_GET["rok"] . "'";
			// echo $query;
			$result = mysqli_query($this->mysql, $query);
			$data = mysqli_fetch_assoc($result);
			$prog = $data["Skratka_programu"];
			$rok = $data["Ak_rok"];
			echo "<option value='$prog-$rok'> $prog-$rok</option>";;
		}
		$query = "SELECT Skratka_programu, Ak_rok FROM Studijny_program WHERE Skratka_programu<>'$prog'";
		$result = mysqli_query($this->mysql, $query);
		while($row = $result->fetch_assoc()) {
			$prog = $row["Skratka_programu"];
			$rok = $row["Ak_rok"];
			echo "<option value='$prog-$rok'> $prog-$rok</option>";
		}
	}

}

?>
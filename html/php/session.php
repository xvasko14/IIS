<?php
$save = 0;
if (isset($_POST["input_login"]) && isset($_POST["input_password"])) {
	$login_class = new Login($_POST["input_login"], $_POST["input_password"]);

	if ($login_class->compare_password() === 1) {
		$save = 1;
	} else if ($login_class->compare_password() === 0){
	}
}
?>
<?php
	session_save_path("../../tmp");
	session_start();
	session_destroy();
	header('Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/');
	exit();
?>
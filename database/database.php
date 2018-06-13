<?php
/* main */
require_once("mysql_init.php");
$mysql = new mysql_class();
$mysql = $mysql->get_status();

$commands = file_get_contents_utf8("./html/sql/mysql.sql");
if (mysqli_multi_query($mysql, $commands)) {
    do {
        /* store first result set */
if ($result = mysqli_store_result($mysql)) {
    while ($row = mysqli_fetch_row($result)) {
        // printf("%s\n", $row[0]);
    }
    mysqli_free_result($result);
}
/* print divider */
if (mysqli_more_results($mysql)) {
    //printf("INSERT\n");
}
} while (mysqli_next_result($mysql));
}
mysqli_close($mysql);
header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/"); /* Redirect browser */
// exit();

// die();


/* convert to utf8 */
function file_get_contents_utf8($fn) {
     $content = file_get_contents($fn);
      return mb_convert_encoding($content, 'UTF-8',
          mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}


/*echo "<pre>";
echo base64_encode(hash('sha256', 'hello', true)) . "\n";
echo base64_encode(hash('sha256', 'hi', true)). "\n";
echo base64_encode(hash('sha256', 'pass', true)). "\n"; */

/*echo base64_encode(hash('sha256', 'passwd', true)). "\n";
echo base64_encode(hash('sha256', 'password', true)). "\n";

echo "</pre>"; */
/*      echo "<pre>";
        echo base64_encode(hash('sha256', 'admin', true)). "\n";
        echo base64_encode(hash('sha256', 'smrcka', true)). "\n";
        echo base64_encode(hash('sha256', 'vojnar', true)). "\n";
        echo base64_encode(hash('sha256', 'potter', true)). "\n";
        echo base64_encode(hash('sha256', 'messi', true)). "\n";
        echo base64_encode(hash('sha256', 'pasty', true)). "\n";
        echo "</pre>";*/
?>
<?php
class mysql_class {
	private $mysql_status;
	public function __construct() {
		/* init mysql */
		$config = parse_ini_file('/home/users/xv/xvasko12/WWW/IIS/config/config.ini');

		$this->mysql_status = mysqli_connect(simple_crypt($config['db_host'], 'd'), simple_crypt($config['db_name'], 'd'), simple_crypt($config['db_pass'], 'd'), simple_crypt($config['db_name'], 'd'));
		if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		    // TODO swal
		}
		if (!mysqli_set_charset($this->mysql_status, 'utf8')) {
		    echo "Could not set charset!";
		    //TODO swal
		    exit();
		}
	}

	public function get_status() {
		return $this->mysql_status;
	}
}

/**
 * Encrypt and decrypt
 *
 * @author Nazmul Ahsan <n.mukto@gmail.com>
 * @link http://nazmulahsan.me/simple-two-way-function-encrypt-decrypt-string/
 *
 * @param string $string string to be encrypted/decrypted
 * @param string $action what to do with this? e for encrypt, d for decrypt
 */
function simple_crypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'simple_secret_key';
    $secret_iv = 'simple_secret_iv';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }

    return $output;
}

?>
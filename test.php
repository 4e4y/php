<?php
	require_once 'Crypt.class.php';

	function pkcs7_pad($data, $size)
	{
	    $length = $size - strlen($data) % $size;
	    return $data . str_repeat(chr($length), $length);
	}

	function pkcs7_unpad($data)
	{
	    return substr($data, 0, -ord($data[strlen($data) - 1]));
	}

	$password = 'passwordToBeHashed_1';
	$text = 'text to be Encrypted/Decrypted ... ';

	/// Password Hashing
	$random = openssl_random_pseudo_bytes(18);
	$salt = sprintf('$2y$%02d$%s',
	    13, // 2^n cost factor
	    substr(strtr(base64_encode($random), '+', '.'), 0, 22)
	);

	$hash = crypt($password, $salt);

	$hashToCheck = crypt('passwordToBeHashed_1', $hash);

	echo 'hash: ' . print_r($hash, true) . PHP_EOL;
	echo 'hashToCheck: ' . print_r($hashToCheck, true) . PHP_EOL;

	$verified = (password_verify($password, $hashToCheck)) ? 'true': 'false';

	echo 'Verify: ' . $verified . PHP_EOL;

	$key_size = 32; // 256 bits
	$encryption_key = openssl_random_pseudo_bytes($key_size, $strong);

	$iv_size = 16; // 128 bits
	$iv = openssl_random_pseudo_bytes($iv_size, $strong);

	$enc_name = openssl_encrypt(
	    pkcs7_pad($text, 16), // padded data
	    'AES-256-CBC',        // cipher and mode
	    $encryption_key,      // secret key
	    0,                    // options (not used)
	    $iv                   // initialisation vector
	);

	echo 'Encrypted text: -->' . print_r($enc_name, true) . '<--' . PHP_EOL;

	$name = pkcs7_unpad(openssl_decrypt(
	    $enc_name,
	    'AES-256-CBC',
	    $encryption_key,
	    0,
	    $iv
	));


	echo 'Decrypted text: ' . print_r($name, true) . PHP_EOL;

	die();

	/// END: Password Hashing
	// $key = '50904EFF1F5F4C2A5D2CD76B0E8F2CCF11B6EC34AE8C474954BD34380F7C00F8';

	echo password_hash($text, PASSWORD_DEFAULT) . PHP_EOL;
	echo password_hash($text, PASSWORD_BCRYPT, ['cost' => 14]) . PHP_EOL;

	die();

	$crypt = new Crypt();

	echo 'Rijndael 256-bit CBC Encryption Function' . PHP_EOL;
	$encrypted_data = $crypt->encrypt($text);
	echo 'Example #1: String Data' . PHP_EOL;
	echo 'Data to be Encrypted: ' . $text . PHP_EOL;
	echo 'Encrypted Data: ' . $encrypted_data . PHP_EOL;

	$crypt1 = new Crypt();
	echo 'Decrypted Data: -->' . $crypt1->decrypt($encrypted_data) . '<--' . PHP_EOL;

/*
	$data = array(1, 5, 8, new DateTime(), 22, 10, 61, array('apple' => array('red', 'green')));
	$encrypted_data = $crypt->encrypt($data);
	echo 'Example #2: Non-String Data' . PHP_EOL;
	echo 'Data to be Encrypted:' . PHP_EOL;
	print_r($data);
	echo PHP_EOL;
	echo 'Encrypted Data: ' . $encrypted_data . PHP_EOL;
	echo 'Decrypted Data: ' . PHP_EOL;
	print_r($crypt->decrypt($encrypted_data));
	echo PHP_EOL;
*/
?>
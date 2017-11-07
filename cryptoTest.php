<?php
	$algo = 'aes-256-gcm';
	$iv   = random_bytes(openssl_cipher_iv_length($algo));
	$key  = random_bytes(32); // 256 bit
	// $data = random_bytes(1024); // 1 Kb of random data
	$data = 'Test to be Encrypted'; // 1 Kb of random data
	$ciphertext = openssl_encrypt(
	    $data,
	    $algo,
	    $key,
	    OPENSSL_RAW_DATA,
	    $iv,
	    $tag
	);

	echo 'Encrypted text: ' . base64_encode($ciphertext) . PHP_EOL;
	echo 'Tag: ' . base64_encode($tag) . PHP_EOL;

	// Change 1 bit in ciphertext
	// $i = rand(0, mb_strlen($ciphertext, '8bit') - 1);
	// $ciphertext[$i] = $ciphertext[$i] ^ chr(1);
	$decrypt = openssl_decrypt(
	    $ciphertext,
	    $algo,
	    $key,
	    OPENSSL_RAW_DATA,
	    $iv,
	    $tag
	);
	if (false === $decrypt) {
	    throw new Exception(sprintf(
	        "OpenSSL error: %s", openssl_error_string()
	    ));
	}
	echo 'Decrypted text: ' . $decrypt . PHP_EOL;
	printf ("Decryption %s\n", $data === $decrypt ? 'Ok' : 'Failed');
?>
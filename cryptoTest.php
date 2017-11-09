<?php
	/*
	https://www.zimuel.it/blog/strong-cryptography-in-php
	*/
	echo 'Encrypting text ...' . PHP_EOL;
	$algo = 'aes-256-gcm';
	$iv   = random_bytes(openssl_cipher_iv_length($algo));
	$key  = random_bytes(32); // 256 bit
	// $data = random_bytes(1024); // 1 Kb of random data
	$data = 'Test to be Encrypted'; // 1 Kb of random data
	$pass = 'someP@ssword';
	$startEncrypt = microtime(TRUE);
	$ciphertext = openssl_encrypt(
	    $data,
	    $algo,
	    $key,
	    OPENSSL_RAW_DATA,
	    $iv,
	    $tag
	);
	$endEncrypt = microtime(TRUE);

	echo 'Encryption done ...' . PHP_EOL;
	echo 'Encrypted text: ' . base64_encode($ciphertext) . PHP_EOL;
	echo 'Tag: ' . base64_encode($tag) . PHP_EOL . PHP_EOL;

	// Change 1 bit in ciphertext
	// $ciphertext[6] = $ciphertext[6] ^ chr(1);
	echo 'Decrypting text ...' . PHP_EOL;
	$startDecrypt = microtime(TRUE);
	$decrypt = openssl_decrypt(
	    $ciphertext,
	    $algo,
	    $key,
	    OPENSSL_RAW_DATA,
	    $iv,
	    $tag
	);
	$endDecrypt = microtime(TRUE);
	if (false === $decrypt) {
		echo 'Error while decrypting ...';
	}
	else
	{
		echo 'Decrypted text: ' . $decrypt . PHP_EOL;
		printf ("Decryption %s\n", $data === $decrypt ? 'Ok' : 'Failed');
	}
	echo 'Decription done ...' . PHP_EOL . PHP_EOL;

	echo 'Hashing password ...' . PHP_EOL;
	$startHash = microtime(TRUE);
	$hashedPass = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
	$endHash = microtime(TRUE);
	echo 'Hashed password: ' . bin2hex($hashedPass) . PHP_EOL;
	echo 'Hashing done ...' . PHP_EOL . PHP_EOL;

	echo 'Checking validity ...' . PHP_EOL;
	$startVerify = microtime(TRUE);
	$result = password_verify($pass, $hashedPass);
	$endVerify = microtime(TRUE);
	echo 'Checking validity done ...' . PHP_EOL;
	echo 'Valid password: ' . $result . PHP_EOL . PHP_EOL;

	echo 'Statistics:' . PHP_EOL;
	echo 'Encryption: ' . ($endEncrypt - $startEncrypt) . PHP_EOL;
	echo 'Decryption: ' . ($endDecrypt - $startDecrypt) . PHP_EOL;
	echo 'Hashing: ' . ($endHash - $startHash) . PHP_EOL;
	echo 'Varivying: ' . ($endVerify - $startVerify) . PHP_EOL;
?>
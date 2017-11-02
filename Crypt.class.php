<?php
    require_once 'settings.php';
class Crypt {

    private $key;
    private $settings;

    function __construct(){
        global $cryptoSettings;
        $this->settings = $cryptoSettings;
        
        $this->setKey($this->settings->key);
    }

    public function encrypt($encrypt){
        $encrypt = serialize($encrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', $this->key);
        $mac = hash_hmac($this->settings->cryptoMethod, $encrypt, substr($this->key, -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt . $mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt) . '|' . base64_encode($iv);
        return $encoded;
    }

    public function decrypt($decrypt){
        $decrypt = explode('|', $decrypt.'|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
        $key = pack('H*', $this->key);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac($this->settings->cryptoMethod, $decrypted, substr($this->key, -32));
        if($calcmac !== $mac){
            return false;
        }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    }

    public function setKey($key){
        if(ctype_xdigit($key) && strlen($key) === 64){
            $this->key = $key;
        }else{
            trigger_error('Invalid key. Key must be a 32-byte (64 character) hexadecimal string.', E_USER_ERROR);
        }
    }

}

/*
$crypt = new Crypt('d0a7e7997b6d5fcd55f4b5c32611b87cd923e88837b63bf2941ef819dc8ca282');

echo '<h1>Rijndael 256-bit CBC Encryption Function</h1>';

$data = 'Super secret confidential string data.';
$encrypted_data = $crypt->encrypt($data);
echo '<h2>Example #1: String Data</h2>';
echo 'Data to be Encrypted: ' . $data . '<br/>';
echo 'Encrypted Data: ' . $encrypted_data . '<br/>';
echo 'Decrypted Data: ' . $crypt->decrypt($encrypted_data) . '</br>';

$data = array(1, 5, 8, new DateTime(), 22, 10, 61, array('apple' => array('red', 'green')));
$encrypted_data = $crypt->encrypt($data);
echo '<h2>Example #2: Non-String Data</h2>';
echo 'Data to be Encrypted: <pre>';
print_r($data);
echo '</pre><br/>';
echo 'Encrypted Data: ' . $encrypted_data . '<br/>';
echo 'Decrypted Data: <pre>';
print_r($crypt->decrypt($encrypted_data));
echo '</pre>';
*/
<?php

//define('ENCRYPTION_PASSWORD_FILE', '/private/.mpdtunes.passwd');
define('ENCRYPTION_PASSWORD_FILE', '/etc/.mpdtunes.passwd');

/**
 * Function encrypt_data encrypts the plaintext data that's passed in
 * @param $plaintext is the plaintext data
 * @return $encrypted_data is the encrypted data as a string
 */ 
function encrypt_data( $plaintext ) {

    $dataLength = strlen($plaintext);

    // Open the cipher
    $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', 'cbc', '');

    // Create the IV and determine the keysize length, use MCRYPT_RAND on Windows instead
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    $ks = mcrypt_enc_get_key_size($td);

    // open a file handle to the password file
    $fileHandle = fopen(ENCRYPTION_PASSWORD_FILE, 'rt');
            
    // read in the password file
    $password = fread($fileHandle, filesize(ENCRYPTION_PASSWORD_FILE));

    // close the handle to the password file
    fclose($fileHandle);
    
    // Create key
    $key1 = hash("sha512", $password);
    $key2 = hash("sha512", $key1);

    $key = substr($key1, 0, $ks/2) . substr(strtoupper($key2), (round(strlen($key2) / 2)), $ks/2);
    $key = substr($key.$key1.$key2.strtoupper($key1),0,$ks);
    
    // intialize encryption
    mcrypt_generic_init($td, $key, $iv);

    // encrypt data
    $encrypted_data = mcrypt_generic($td, $plaintext);

    // append the IV onto the end of the encrypted XML so we will be able to decrypt it later
    $encrypted_data = $encrypted_data.$iv;
    
    // terminate encryption handler
    mcrypt_generic_deinit($td);

    // close the mcrypt module
    mcrypt_module_close($td);

    /*echo "<br />Password: $password<br />";
    echo "key1: $key1 <br>key2: $key2<br>created key: $key <br />";
    echo "<br />IV: ".$iv."<br />";
    echo "<br />IV Length: ".strlen($iv)."<br />";
    echo "<br />IV: ".substr($encrypted_data, (strlen($encrypted_data) - 32), 32)."<br />";
    echo "<br />IV Length: ".strlen(substr($encrypted_data, (strlen($encrypted_data) - 32), 32))."<br />";
    echo "<br />Encrypted XML: ".$encrypted_data ."<br />";*/
    
    return $encrypted_data;
}

function hextostr($hex)
{
$str='';
for ($i=0; $i < strlen($hex)-1; $i+=2)
{
$str .= chr(hexdec($hex[$i].$hex[$i+1]));
}
return $str;
}

/**
 * Function decrypt_data decrypts the encrypted data that's passed in
 * @param $encrypted is the string of encrypted data
 * @return $decrypted_data is the decrypted config XML as a string
 */ 
function decrypt_data( $encrypted ) {

    // open the cipher
    $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', 'cbc', '');

    // retrieve the 16 bits of IV from the end of the encrypted data
    $iv = substr($encrypted, (strlen($encrypted) - 32), 32);
    
    // trim the 32 bits of IV from the end of the encrypted data
    $encrypted = substr($encrypted, 0, (strlen($encrypted) - 32));
 
    $ks = mcrypt_enc_get_key_size($td);

    // open a file handle to the password file
    $fileHandle = fopen(ENCRYPTION_PASSWORD_FILE, 'rt');
            
    // read in the password file
    $password = fread($fileHandle, filesize(ENCRYPTION_PASSWORD_FILE));

    // close the handle to the password file
    fclose($fileHandle);
    
    // create key
    $key1 = hash("sha512", $password);
    $key2 = hash("sha512", $key1);

    $key = substr($key1, 0, $ks/2) . substr(strtoupper($key2), (round(strlen($key2) / 2)), $ks/2);
    $key = substr($key.$key1.$key2.strtoupper($key1),0,$ks);

    // initialize encryption module for decryption
    mcrypt_generic_init($td, $key, $iv);
    
    // decrypt encrypted string
    $decrypted_data = mdecrypt_generic($td, $encrypted);

    $dataLength = strlen($decrypted_data);
    
    // terminate decryption handle and close module
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);

    /*echo "<br />Password: $password<br />";
    echo "<br />key1: $key1 <br>key2: $key2<br>created key: $key <br />"; 
    echo "<br />IV: ".$iv."<br />";
    echo "<br />IV: ".substr($encrypted, (strlen($encrypted) - 32), 32)."<br />";
    echo "<br />IV Length: ".strlen($iv)."<br />";
    echo "<br />Encrypted XML: ".$encrypted ."<br />";
    echo "<br />Decrypted XML: ".substr($decrypted_data, 0, $dataLength) . "<br />";*/

    return trim($decrypted_data);
}

function generate_password_mask($target_length) {
    
    $password_mask = "";

    $mask_options = array(0=>'#', 1=>'C', 2=>'c', 3=>'X', 4=>'!');

    for ($i=0; $i<$target_length; $i++){

        $randval = mt_rand();
        $remainder = $randval % 5;
        $password_mask .= $mask_options[$remainder]; 
    }

    return $password_mask;
}

// Mask Rules
// # - digit
// C - Caps Character (A-Z)
// c - Small Character (a-z)
// X - Mixed Case Character (a-zA-Z)
// ! - Custom Extended Characters
function create_password($mask) {

    $extended_chars = "!#$%^*()";
    
    $length = strlen($mask);
    
    $password = '';
    
    for ($c=0;$c<$length;$c++) {
        
        $ch = $mask[$c];
        
        switch ($ch) {
        
        case '#':
            $p_char = rand(0,9);
            break;

        case 'C':
            $p_char = chr(rand(65,90));
            break;

        case 'c':
            $p_char = chr(rand(97,122));
            break;

        case 'X':
            do {

                $p_char = rand(65,122);

            } while ($p_char > 90 && $p_char < 97);

            $p_char = chr($p_char);
            
            break;

        case '!':
            $p_char = $extended_chars[rand(0,strlen($extended_chars)-1)];
            break;
        }

        $password .= $p_char;
    }

    return $password;
}

// This pre-auth algorithm will at least prevent bots from attempting to brute force the login page.
// Although, if the bots refresh the page before each attempt, then it will just slow them down.
function generate_pre_auth_tokens($debug=false) {
    
    $pre_auth_tokens = array();

    // Generate a random 32 character password, then SHA512 hash it, and use it as a pre-auth token
    $first_password_mask = generate_password_mask(32);
    $first_password = create_password($first_password_mask);
    $first_secure_token_pre_auth = hash("SHA512", $first_password);

    $pre_auth_tokens['first'] = $first_secure_token_pre_auth;

    $second_password_mask = generate_password_mask(32);
    $second_password = create_password($second_password_mask);
    $second_secure_token_pre_auth = hash("SHA512", $second_password);

    $pre_auth_tokens['second'] = $second_secure_token_pre_auth;

    $xor_of_first_and_second = $first_secure_token_pre_auth ^ $second_secure_token_pre_auth;

    $pre_auth_tokens['xor'] = $xor_of_first_and_second;

    if ($debug) {

        // token recovery validation test
        print("xor_of_first_and_second       : ".$xor_of_first_and_second."<br />");
        print("first_secure_token_pre_auth   : ".$first_secure_token_pre_auth."<br />");
        print("second_secure_token_pre_auth  : ".$second_secure_token_pre_auth."<br />");
        $first_recovered = $xor_of_first_and_second ^ $second_secure_token_pre_auth;
        print("first_recovered               : ".$first_recovered."<br />");
        $second_recovered = $first_secure_token_pre_auth ^ $xor_of_first_and_second;
        print("second_recovered              : ".$second_recovered."<br />");
        print("pre_auth_tokens Array:<br />");
        var_dump($pre_auth_tokens);
        exit();
    }

    return $pre_auth_tokens;
}

function validate_pre_auth_tokens($first, $second, $xor, $debug=false) {

    // it wasn't validating properly until I started performing this conversion
    $first = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $first);
    
    // it wasn't validating properly until I started performing this conversion
    $first_recovered = iconv("UTF-8", "ISO-8859-1//TRANSLIT", ($xor ^ $second));
    
    if ($debug) {

        print("xor_of_first_and_second       : ".$xor."<br />");
        print("first_preauth_token           : ".$first."<br />");
        print("second_preauth_token          : ".$second."<br />");
        print("first_preauth_token_recovered : ".$first_recovered."<br />");
        print("do they match?                : ".(($first_recovered == $first) ? "YES" : "NOPE")."<br />");
        exit();
    }

    if ($first_recovered == $first) {

        return true;
    }

    return false;
}
?>

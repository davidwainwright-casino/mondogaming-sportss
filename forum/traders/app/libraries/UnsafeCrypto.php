<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
*  ==============================================================================
*  Author    :  Mian Saleem
*  Email      : saleem@tecdiary.com
*  For        : Stock Manager Advance
*  Web        : http://tecdiary.com
*  ==============================================================================
*/

class UnsafeCrypto
{
    const METHOD = 'AES-256-CTR';

    public function __get($var) {
        return get_instance()->$var;
    }

    public static function encrypt($message, $encode = false) {
        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            config_item('encryption_key'),
            OPENSSL_RAW_DATA,
            $nonce
        );

        if ($encode) {
            return base64_encode($nonce.$ciphertext);
        }
        return $nonce.$ciphertext;
    }

    public static function decrypt($message, $encoded = false)
    {
        if ($encoded) {
            $message = base64_decode($message, true);
            if ($message === false) {
                throw new Exception('Encryption failure');
            }
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            config_item('encryption_key'),
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plaintext;
    }

}

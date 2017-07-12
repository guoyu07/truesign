<?php

namespace Royal\Crypt;

abstract class RSA {
    public abstract function getPublicKey();

    public abstract function getPrivateKey();

    public function publicKeyEncrypt($str) {
        $key = $this->getPublicKey();
        openssl_public_encrypt($str, $encrypted, $key);
        return $encrypted;
    }

    public function publicKeyDecrypt($encrypted) {
        $key = $this->getPublicKey();
        openssl_public_decrypt($encrypted, $decrypted, $key);
        return $decrypted;
    }

    public function privateKeyEncrypt($str) {
        $key = $this->getPrivateKey();
        openssl_private_encrypt($str, $encrypted, $key);
        return $encrypted;
    }

    public function privateKeyDecrypt($encrypted) {
        $key = $this->getPrivateKey();
        openssl_private_decrypt($encrypted, $decrypted, $key);
        return $decrypted;
    }

    public function sign($data) {
        $key = $this->getPrivateKey();
        $res = openssl_get_privatekey($key);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }

    public function verify($data, $sign) {
        $key = $this->getPublicKey();
        $res = openssl_get_publickey($key);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);
        return $result;
    }
}

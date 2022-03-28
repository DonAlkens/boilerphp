<?php

namespace App\Hashing;

class Hash {

    private $inputCharacterSet = "0123456789abcdefghijklmnopqrstuvwxyz";

    private $outputCharacterSet = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";


    static public function create($string, $clean = false) 
    {
        
        if($clean == true)
        {
            $string = password_hash($string, PASSWORD_BCRYPT);
            $hash = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        }
        else
        {
            $hash = password_hash($string, PASSWORD_BCRYPT);
        }

        return $hash;
    }

    static public function verify($string, $hash) 
    {
        return password_verify($string, $hash);
    }

    private function convBase($numberInput, $fromBaseInput, $toBaseInput)
    {
        if ($fromBaseInput == $toBaseInput) return $numberInput;
        $fromBase = str_split($fromBaseInput, 1);
        $toBase = str_split($toBaseInput, 1);
        $number = str_split($numberInput, 1);
        $fromLen = strlen($fromBaseInput);
        $toLen = strlen($toBaseInput);
        $numberLen = strlen($numberInput);
        $retval = '';
        if ($toBaseInput == '0123456789') {
            $retval = 0;
            for ($i = 1; $i <= $numberLen; $i++)
                $retval = bcadd($retval, bcmul(array_search($number[$i - 1], $fromBase), bcpow($fromLen, $numberLen - $i)));
            return $retval;
        }
        if ($fromBaseInput != '0123456789')
            $base10 = $this->convBase($numberInput, $fromBaseInput, '0123456789');
        else
            $base10 = $numberInput;
        if ($base10 < strlen($toBaseInput))
            return $toBase[$base10];
        while ($base10 != '0') {
            $retval = $toBase[bcmod($base10, $toLen)] . $retval;
            $base10 = bcdiv($base10, $toLen, 0);
        }
        return $retval;
    }

    public function getEncodedBase(string $value) {
        $encode = $this->convBase($value, $this->inputCharacterSet, $this->outputCharacterSet);
        return $encode;
    }

    public function getDecodedBase(string $value) {
        $decode = $this->convBase($value, $this->outputCharacterSet, $this->inputCharacterSet);
        return $decode;
    }
}
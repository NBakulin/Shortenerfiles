<?php
class ShortenerModel
{
    private $possible_characters = 'abcdefghijkmnopqrstuvwxyz'.
    'ABCDEFGHIJKLMNPQRSTUVWXYZ'.
    '23456789';
    private $digits;
    private $base;

    public function __construct()
    {
        $this->digits = str_split($this->possible_characters);
        $this->base  = count($this->digits);
    }

    public function translate($number)
    {
        $result = '';
        if ($number < 0) {
            return false;
        }
        if ($number == 0) {
            $result = $this->digits[0];
        }
        while ($number > 0) {
            $carry = $number % $this->base;
            $result = $this->digits[$carry].$result;
            $number = floor($number/$this->base);
        }
        return $result;
    }
}
?>
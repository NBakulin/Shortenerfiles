<?php
use Models\ShortenerModel;;
use PHPUnit\Framework\TestCase;
//Sorry, there are something wrong with my tests :(
class ShortenerModelTest extends TestCase
{
    private $possible_characters = 'abcdefghijkmnopqrstuvwxyz'.
    'ABCDEFGHIJKLMNPQRSTUVWXYZ'.
    '23456789';
    private $digits;
    private $base;

    protected function setUp()
    {
        $this->digits = str_split($this->possible_characters);
        $this->base  = count($this->digits);
    }

    public function testTranslate()
    {
        $translator =  new ShortenerModel();
        $this->assertEquals('a', $translator->Translate(1));
    }
}

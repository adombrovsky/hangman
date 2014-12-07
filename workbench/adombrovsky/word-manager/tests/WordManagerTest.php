<?php
use Adombrovsky\WordManager\WordManager;

/**
 * Created by PhpStorm.
 * User: farw
 * Date: 07.12.14
 * Time: 19:38
 */

class WordManagerTest extends  TestCase
{
    /**
     * @var WordManager
     */
    public $wordManager;

    public function setUp()
    {
        parent::setUp();

        $this->wordManager = new  WordManager();

    }
    public function testGetSourceFilePath()
    {
        $filePath = $this->wordManager->getSourceFilePath();
        $this->assertTrue(!!$filePath);
    }

    public function testGetSourceFileShouldExist()
    {
        $filePath = $this->wordManager->getSourceFilePath();
        $this->assertTrue(file_exists($filePath));
    }

    public function testGetRandomWord()
    {
        $word = $this->wordManager->getRandomWord();
        $this->assertTrue(!!$word && strlen($word)>1);
    }

    public function testHideWord()
    {
        $word = $this->wordManager->getRandomWord();
        $hiddenWord = $this->wordManager->hideWord($word);

        $this->assertTrue(!!$hiddenWord);
    }

    public function testHideWordShouldReturnOnlyAsterisks()
    {
        $word = $this->wordManager->getRandomWord();
        $actualResult = $this->wordManager->hideWord($word);
        $expectedResult = str_repeat('*',strlen($word));
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testHideWordShouldReturnOneShownLetter()
    {
        $word = 'random';
        $actualResult = $this->wordManager->hideWord($word,array('r'));
        $expectedResult = 'r*****';
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testHideWordShouldReturnOneShownRepeatedLetter()
    {
        $word = 'test';
        $actualResult = $this->wordManager->hideWord($word,array('t'));
        $expectedResult = 't**t';
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testHideWordShouldReturnAllShownLetters()
    {
        $word = 'test';
        $actualResult = $this->wordManager->hideWord($word,array('t','e','s'));
        $expectedResult = 'test';
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testCheckLetterExistingShouldReturnTrue()
    {
        $word = 'test';
        $actualResult = $this->wordManager->checkLetterExisting($word,'t');
        $expectedResult = true;

        $this->assertEquals($expectedResult,$actualResult);
    }

    public function testCheckLetterExistingShouldReturnFalse()
    {
        $word = 'test';
        $actualResult = $this->wordManager->checkLetterExisting($word,'w');
        $expectedResult = false;

        $this->assertEquals($expectedResult,$actualResult);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: farw
 * Date: 30.11.14
 * Time: 20:24
 */

namespace Adombrovsky\WordManager;

/**
 * Class WordManager
 * @package Adombrovsky\WordManager
 */
class WordManager
{
    const LETTER_PLACEHOLDER = '.';
    const WORDS_SOURCE_FILENAME = 'words.english';

    /**
     * @return string
     */
    public function getRandomWord()
    {
        $randomWord = '';
        $sourcePath = $this->getSourceFilePath();
        $words = file($sourcePath,FILE_IGNORE_NEW_LINES);
        if (is_array($words))
        {
            $randomWord = $words[array_rand($words)];
        }

        return trim($randomWord);
    }

    /**
     * @param $word
     * @param $letter
     *
     * @return bool
     */
    public function checkLetterExisting($word, $letter)
    {
        return (false !== strpos($word,$letter));
    }

    /**
     * @param string $word
     * @param array  $guessedLetters
     *
     * @return string
     */
    public function hideWord($word, $guessedLetters = array())
    {
        $result = '';
        for ($i=0;$i<strlen($word);$i++)
        {
            $result.=(in_array($word[$i],$guessedLetters)) ? $word[$i]:self::LETTER_PLACEHOLDER;
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getSourceFilePath()
    {
        return dirname(__FILE__).'/'.self::WORDS_SOURCE_FILENAME;
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: farw
 * Date: 30.11.14
 * Time: 20:26
 */

namespace Adombrovsky\WordManager\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class WordManager
 * @package Adombrovsky\WordManager\Facades
 */
class WordManager  extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'WordManager';
    }
} 
<?php
/**
 * Created by PhpStorm.
 * User: farw
 * Date: 06.12.14
 * Time: 21:43
 */

class Letter  extends \Eloquent
{
    public $fillable = array('letter','game_id');

    public static $rules = array(
        'default' => array(
            'letter'=>array('required','size:1','regex:/[a-z]/'),
            'game_id'=>array('required','exists:games,id,status,busy'),
        )
    );

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function game()
    {
        return $this->belongsTo('Game');
    }

    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    protected function getDateFormat()
    {
        return 'U';
    }
}

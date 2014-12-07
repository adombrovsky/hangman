<?php
use Adombrovsky\WordManager\Facades\WordManager;

/**
 * Created by PhpStorm.
 * User: farw
 * Date: 06.12.14
 * Time: 21:43
 */

class Game  extends Eloquent
{
    protected $fillable = array('word','tries_left','status');

    const BUSY_STATUS = 'busy';
    const FAIL_STATUS = 'fail';
    const SUCCESS_STATUS = 'success';
    const TRIES_COUNT = 11;

    public static $rules = array(
        'default' => array(
            'word'          => 'required|min:2',
            'tries_left'    => 'required|integer|max:11|min:11',
            'status'        => 'required|in:busy,fail,success'
        )
    );
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function letters()
    {
        return $this->hasMany('Letter');
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

    /**
     * Returns list of available game statuses
     * @return array
     */
    public static function getStatusLabels()
    {
        return array(
            self::BUSY_STATUS => 'Busy',
            self::FAIL_STATUS => 'Fail',
            self::SUCCESS_STATUS => 'Success',
        );
    }

    /**
     * Returns text label for the specific status
     * @param $key
     *
     * @return mixed
     */
    public static function getStatusLabel($key)
    {
        $statuses = self::getStatusLabels();

        return isset($statuses[$key]) ? $statuses[$key] : $key;
    }

    /**
     * @param $letter
     *
     * @return bool
     */
    public function guessLetter($letter)
    {
        if ($this->status !== self::BUSY_STATUS || $this->tries_left < 1) return false;
        return WordManager::checkLetterExisting($this->word,$letter);
    }

    /**
     * @param $letters
     *
     * @return string
     */
    public function hideWord($letters = array())
    {
        return WordManager::hideWord($this->word, $letters);
    }

    /**
     * @return int
     */
    public function decreaseTriesNumber()
    {
        if ($this->tries_left > 0)
        {
            $this->tries_left = $this->tries_left - 1;
        }

        return (int)$this->tries_left;
    }

    public function updateGameStatus()
    {
        if ($this->tries_left < 1)
        {
            $this->status = self::FAIL_STATUS;
        }

        $letters = Letter::where('game_id','=',$this->id)->lists('letter');
        if ($this->word === $this->hideWord($letters))
        {
            $this->status = self::SUCCESS_STATUS;
        }
    }
} 
<?php
use Adombrovsky\WordManager\Facades\WordManager;

/**
 * Class EloquentLetterRepository
 */
class EloquentLetterRepository implements LetterRepositoryInterface
{

    /**
     * @param $id
     *
     * @throws NotFoundException
     * @return Letter
     */
    public function findById ($id)
    {
        /**
         * @var $letter Letter
         */
        $letter = Letter::find($id);
        if (!$letter)
        {
            throw new NotFoundException('Letter not found');
        }
        return $letter;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAll ()
    {
        return Letter::all();
    }

    /**
     * @param $data
     *
     * @return Letter
     */
    public function store ($data = array())
    {
        $this->validate($data);

        $letter = new Letter();
        $letter->fill($data);
        $letter->save();

        return $letter;
    }

    /**
     * @param array $data
     *
     * @return Letter
     */
    public function instance ($data = array())
    {
        return new Letter($data);
    }

    /**
     * @param array  $data
     *
     * @param string $scenario
     *
     * @param array  $rules
     *
     * @throws ValidationException
     * @throws ValidationScenarioException
     * @return bool
     */
    public function validate ($data, $scenario = 'default',$rules = array())
    {
        if (!isset(Letter::$rules[$scenario])) throw new ValidationScenarioException("Scenario: ".$scenario." not found in Letter model");

        $rules = array_merge_recursive($rules,Letter::$rules[$scenario]);
        $validator = Validator::make($data, $rules);
        if($validator->fails()) throw new ValidationException($validator);
        return true;
    }
}
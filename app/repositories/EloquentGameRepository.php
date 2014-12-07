<?php
use Adombrovsky\WordManager\Facades\WordManager;

/**
 * Class EloquentGameRepository
 */
class EloquentGameRepository implements GameRepositoryInterface
{
    /**
     * @var LetterRepositoryInterface
     */
    protected $letter;

    /**
     * @param LetterRepositoryInterface $letterRepositoryInterface
     */
    public function __construct(LetterRepositoryInterface $letterRepositoryInterface)
    {
        $this->letter = $letterRepositoryInterface;
    }

    /**
     * @param      $id
     *
     * @param bool $hideWord
     *
     * @throws NotFoundException
     * @return Game
     */
    public function findById ($id, $hideWord = true)
    {
        /**
         * @var $game Game
         */
        $game = Game::find($id);
        if (!$game)
        {
            throw new NotFoundException('Game not found');
        }
        if ($hideWord)
        {
            $letters = Letter::where('game_id','=',$id)->lists('letter');
            $game->word = $game->hideWord($letters);
        }
        return $game;
    }

    /**
     * @param bool $hideWord
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAll ($hideWord = true)
    {
        $games = Game::all();

        if ($hideWord)
        {
            foreach ($games as $index=>$game)
            {
                $letters = Letter::where('game_id','=',$game->id)->lists('letter');
                $games[$index]->word = $game->hideWord($letters);
            }
        }
        return $games;
    }

    /**
     * @param null $limit
     *
     * @param bool $hideWord
     *
     * @return \Illuminate\Pagination\Paginator
     */
    public function paginate ($limit = NULL, $hideWord = true)
    {
        $games = Game::paginate($limit);
        if ($hideWord)
        {
            foreach ($games as $index=>$game)
            {
                $letters = Letter::where('game_id','=',$game->id)->lists('letter');
                $games[$index]->word = $game->hideWord($letters);
            }
        }
        return $games;
    }

    /**
     * @param bool $hideWord
     *
     * @return Game
     */
    public function store ($hideWord = true)
    {
        $dataToStore = array(
            'tries_left' => Game::TRIES_COUNT,
            'word' => WordManager::getRandomWord(),
        );
        $this->validate($dataToStore);

        $game               = new Game();
        $game->tries_left   = $dataToStore['tries_left'];
        $game->word         = $dataToStore['word'];
        $game->status       = Game::BUSY_STATUS;
        $game->save();

        if ($hideWord)
        {
            $game->word = $game->hideWord();
        }
        return $game;
    }

    /**
     * @param      $id
     *
     * @param      $data
     *
     * @param bool $hideWord
     *
     * @return Game
     */
    public function guess($id, $data, $hideWord = true)
    {
        $this->letter->validate($data,'default',array('letter'=>array(
            'required',
            'size:1',
            'regex:/[a-z]/',
            'unique:letters,letter,NULL,id,game_id,'.$id
        )));

        $game = $this->findById($id, false);
        if ($game->guessLetter($data['letter']))
        {
            $this->letter->store($data);
        }
        else
        {
            $game->decreaseTriesNumber();
        }

        $game->updateGameStatus();
        $game->save();

        if ($hideWord)
        {
            $letters = Letter::where('game_id','=',$game->id)->lists('letter');
            $game->word = $game->hideWord($letters);
        }
        return $game;
    }

    /**
     * @param array $data
     *
     * @return Game
     */
    public function instance ($data = array())
    {
        return new Game($data);
    }


    /**
     * @param array $data
     *
     * @param string $scenario
     *
     * @throws ValidationException
     * @throws ValidationScenarioException
     * @return bool
     */
    public function validate ($data, $scenario = 'default')
    {
        if (!isset(Game::$rules[$scenario])) throw new ValidationScenarioException("Scenario: ".$scenario." not found in Game model");
        $validator = Validator::make($data, Game::$rules[$scenario]);
        if($validator->fails()) throw new ValidationException($validator);
        return true;
    }
}
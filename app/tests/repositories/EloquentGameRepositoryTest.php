<?php

/**
 * Class EloquentGameRepositoryTest
 */
class EloquentGameRepositoryTest extends TestCase
{
    /**
     * @var EloquentGameRepository
     */
    private $repo;

    private $letterFakeData;

    public function setUp ()
    {
        parent::setUp();

        $this->repo = App::make("EloquentGameRepository");
        $this->letterFakeData = array(
            'game_id'=>Game::where('status','=',Game::BUSY_STATUS)->first()->id,
            'letter'=>'a'
        );
    }

    public function testFindByIdReturnsModel()
    {
        $game = $this->repo->findById(Game::first()->id);

        $this->assertTrue($game instanceof Illuminate\Database\Eloquent\Model);
    }

    public function testFindAllReturnsModel()
    {
        $game = $this->repo->findAll();

        $this->assertTrue($game instanceof Illuminate\Database\Eloquent\Collection);
    }

    public function testPaginateReturnsPaginator()
    {
        $game = $this->repo->paginate(10);

        $this->assertTrue($game instanceof Illuminate\Pagination\Paginator);
    }

    public function testStoreReturnsModel()
    {
        $game = $this->repo->store();

        $this->assertTrue($game instanceof \Illuminate\Database\Eloquent\Model);
    }

    public function testValidateReturnsTrue()
    {
        $validationResult = $this->repo->validate(array('word'=>'test','tries_left'=>11));

        $this->assertTrue($validationResult);
    }

    public function testInstanceReturnsModel()
    {
        $game = $this->repo->instance();

        $this->assertTrue($game instanceof \Illuminate\Database\Eloquent\Model);
    }

    public function testGuessReturnModel()
    {
        $model = $this->repo->store(false);
        $game = $this->repo->guess($model->id, array('letter'=>$model->word[0],'game_id'=>$model->id));

        $this->assertTrue($game instanceof \Illuminate\Database\Eloquent\Model);
    }

    public function testFindByIdShouldThrowException()
    {
        try
        {
            $this->repo->findById(-1);
        }
        catch (NotFoundException $e)
        {
            return;
        }

        $this->fail('Exception was not raised');
    }

    public function testPaginateShouldReturnCorrectNumberOfItems()
    {
        $pageSize = 10;
        $gamesWithPaginator = $this->repo->paginate($pageSize);
        $gamesTotalList = $this->repo->findAll();

        $totalItemCountMoreThanPageSize = (count($gamesTotalList) > $pageSize && count($gamesWithPaginator) == $pageSize);
        $totalItemCountLessThanPageSize = (count($gamesTotalList) <= $pageSize && count($gamesWithPaginator) == count($gamesTotalList));

        $this->assertTrue($totalItemCountMoreThanPageSize || $totalItemCountLessThanPageSize );
    }

    public function testValidationShouldThrowValidationException()
    {
        $data = array();

        try
        {
            $this->repo->validate($data);
        }
        catch (ValidationException $e)
        {
            return;
        }

        $this->fail('ValidationException was not raised');
    }

    public function testValidationShouldThrowValidationScenarioException()
    {
        $data = array();
        $fakeScenario = 'test';
        try
        {
            $this->repo->validate($data, $fakeScenario);
        }
        catch (ValidationScenarioException $e)
        {
            return;
        }

        $this->fail('ValidationScenarioException was not raised');
    }

    public function testStoreShouldReturnCorrectModel()
    {
        $game = $this->repo->store();
        $gameFromDataBase = $this->repo->findById($game->id);

        $this->assertTrue(!!$gameFromDataBase);
        $this->assertTrue($game->tries_left === Game::TRIES_COUNT);
    }



    public function testGuessShouldThrowValidationException()
    {
        try
        {
            $this->repo->guess(Game::first()->id, array());
        }
        catch (ValidationException $e)
        {
            return;
        }

        $this->fail('ValidationException was not raised');
    }

    public function testGuessShouldCreateNewLetterRecord()
    {
        $game = $this->repo->store(false);
        $word = $game->word;
        $this->repo->guess($game->id,array('letter'=>$word[0],'game_id'=>$game->id));

        $letters = Letter::where('game_id','=',$game->id)->get();
        $this->assertTrue(count($letters)>0);
    }

    public function testGuessShouldDecreaseTriesNumber()
    {
        $game = Game::create(array('tries_left'=>Game::TRIES_COUNT,'word'=>'test','status'=>Game::BUSY_STATUS));
        $initialTriesLeftValue = $game->tries_left;
        $updatedGame = $this->repo->guess($game->id,array('letter'=>'q','game_id'=>$game->id));
        $triesValueAfterUpdate = $updatedGame->tries_left;
        $this->assertTrue(($initialTriesLeftValue - $triesValueAfterUpdate) == 1);
    }

    public function testGuessShouldShouldSetSuccessStatus()
    {
        $game = Game::create(array('tries_left'=>Game::TRIES_COUNT,'word'=>'test','status'=>Game::BUSY_STATUS));
        $letters = array('t','e');
        foreach ($letters as $letter)
        {
            Letter::create(array('letter'=>$letter,'game_id'=>$game->id));
        }
        $updatedGame = $this->repo->guess($game->id,array('letter'=>'s','game_id'=>$game->id));
        $this->assertTrue($updatedGame->status === Game::SUCCESS_STATUS);
    }

    public function testGuessShouldShouldSetFailedStatus()
    {
        $game = Game::create(array('tries_left'=>1,'word'=>'test','status'=>Game::BUSY_STATUS));
        $updatedGame = $this->repo->guess($game->id,array('letter'=>'v','game_id'=>$game->id));
        $this->assertTrue($updatedGame->status === Game::FAIL_STATUS);
    }
}
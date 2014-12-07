<?php

/**
 * Class EloquentLetterRepositoryTest
 */
class EloquentLetterRepositoryTest extends TestCase
{
    /**
     * @var EloquentLetterRepository
     */
    private $repo;

    protected $fakeData;

    public function setUp ()
    {
        parent::setUp();

        $this->repo = App::make("EloquentLetterRepository");
        $this->fakeData = array(
            'game_id'=>Game::where('status','=',Game::BUSY_STATUS)->first()->id,
            'letter'=>'a'
        );
    }

    public function testFindByIdReturnsModel()
    {
        $game = $this->repo->findById(Letter::first()->id);

        $this->assertTrue($game instanceof Illuminate\Database\Eloquent\Model);
    }

    public function testFindAllReturnsModel()
    {
        $game = $this->repo->findAll();

        $this->assertTrue($game instanceof Illuminate\Database\Eloquent\Collection);
    }

    public function testStoreReturnsModel()
    {
        $game = $this->repo->store($this->fakeData);

        $this->assertTrue($game instanceof \Illuminate\Database\Eloquent\Model);
    }

    public function testValidateReturnsTrue()
    {
        $validationResult = $this->repo->validate($this->fakeData);

        $this->assertTrue($validationResult);
    }

    public function testInstanceReturnsModel()
    {
        $game = $this->repo->instance();

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
        $game = $this->repo->store($this->fakeData);
        $gameFromDataBase = $this->repo->findById($game->id);

        $this->assertTrue(!!$gameFromDataBase);
        $this->assertTrue($game->letter === $this->fakeData['letter']);
        $this->assertTrue($game->game_id === $this->fakeData['game_id']);
    }
}
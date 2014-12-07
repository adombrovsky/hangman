<?php
/**
 * Created by PhpStorm.
 * User: farw
 * Date: 07.12.14
 * Time: 15:49
 */

/**
 * Class GamesControllerTest
 */
class GamesControllerTest  extends TestCase
{
    public function testIndex()
    {
        $this->call('GET',route('api.games.index'));
        $this->assertResponseOk();
    }

    public function testStore()
    {
        $this->call('POST',route('api.games.store'));
        $this->assertResponseOk();
    }

    public function testShow()
    {
        $this->call('GET',route('api.games.show',array(Game::first()->id)));
        $this->assertResponseOk();
    }

    public function testGuess()
    {
        $this->call('GET',route('api.games.guess',array(Game::first()->id)));
        $this->assertResponseOk();
    }

    public function testIndexShouldCallFindAll()
    {
        $mock = Mockery::mock("GameRepositoryInterface");
        $mock->shouldReceive('findAll')->once()->andReturn('array');

        App::instance("GameRepositoryInterface",$mock);

        $response = $this->call('GET',route('api.games.index'));

        $this->assertTrue(!!$response->original);
    }

    public function testStoreShouldCallStore()
    {
        $mock = Mockery::mock("GameRepositoryInterface");
        $mock->shouldReceive('store')->once()->andReturn('array');

        App::instance("GameRepositoryInterface",$mock);

        $response = $this->call('POST',route('api.games.store',array(Game::first()->id)));

        $this->assertTrue(!!$response->original);
    }

    public function testShowShouldCallFindById()
    {
        $mock = Mockery::mock("GameRepositoryInterface");
        $mock->shouldReceive('findById')->once()->andReturn('array');

        App::instance("GameRepositoryInterface",$mock);

        $response = $this->call('GET',route('api.games.show',array(Game::first()->id)));

        $this->assertTrue(!!$response->original);
    }

    public function testGuessShouldCallGuess()
    {
        $mock = Mockery::mock("GameRepositoryInterface");
        $mock->shouldReceive('guess')->once()->andReturn('array');

        App::instance("GameRepositoryInterface",$mock);

        $response = $this->call('POST',route('api.games.guess',array(Game::first()->id)));

        $this->assertTrue(!!$response->original);

    }
}
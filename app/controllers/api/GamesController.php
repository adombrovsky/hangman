<?php
namespace api;

use BaseController;
use GameRepositoryInterface;
use Input;

/**
 * Class GamesController
 * @package api
 */
class GamesController extends BaseController
{
    /**
     * @var GameRepositoryInterface
     */
    protected $game;

    /**
     * @param GameRepositoryInterface $gameRepositoryInterface
     */
    public function __construct(GameRepositoryInterface $gameRepositoryInterface)
    {
        $this->game = $gameRepositoryInterface;
    }

	/**
	 * Display a listing of the resource.
	 * GET /games
	 *
     * @return mixed
     */
    public function index()
	{
        return $this->game->findAll();
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /games
	 *
     * @return mixed
     */
    public function store()
	{
        return $this->game->store();
	}

    /**
     * Display the specified resource.
     * GET /games/{id}
     *
     * @param  int $id
     *
     * @return mixed
     */
	public function show($id)
	{
        return $this->game->findById($id);
	}

    /**
     * Guest letter.
     * POST /games/{id}
     *
     * @param  int $id
     *
     * @return mixed
     */
	public function guess($id)
	{
        return $this->game->guess($id,Input::all());
	}
}
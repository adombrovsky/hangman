<?php

/**
 * Class GameRepositoryInterface
 */
interface GameRepositoryInterface
{
    /**
     * @param      $id
     *
     * @param bool $hideWord
     *
     * @return mixed
     */
    public function findById($id, $hideWord = true);

    /**
     * @param bool $hideWord
     *
     * @return mixed
     */
    public function findAll($hideWord = true);

    /**
     * @param null $limit
     *
     * @param bool $hideWord
     *
     * @return mixed
     */public function paginate($limit = null, $hideWord = true);

    /**
     * @param bool $hideWord
     *
     * @internal param $data
     *
     * @return mixed
     */
    public function store($hideWord = true);

    /**
     * @param      $id
     * @param      $data
     *
     * @param bool $hideWord
     *
     * @return mixed
     */public function guess($id, $data, $hideWord = true);

    /**
     * @return mixed
     */
    public function instance();

    /**
     * @param $data
     *
     * @return mixed
     */
    public function validate($data);
}
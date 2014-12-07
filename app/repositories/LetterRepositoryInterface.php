<?php

/**
 * Class LetterRepositoryInterface
 */
interface LetterRepositoryInterface
{
    /**
     * @param $id
     *
     * @return mixed
     */
    public function findById($id);

    /**
     * @return mixed
     */
    public function findAll();

    /**
     * @param $data
     *
     * @return mixed
     */public function store($data);

    /**
     * @return mixed
     */
    public function instance();

    /**
     * @param       $data
     *
     * @param       $scenario
     * @param array $rules
     *
     * @return mixed
     */
    public function validate($data,$scenario = 'default',$rules = array());
}
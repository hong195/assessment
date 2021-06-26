<?php


namespace App\Domain\Shared;


use App\Exceptions\NotFoundEntityException;

interface AbstractRepository
{
    /**
     * @param $id
     * @throws NotFoundEntityException
     * @return mixed
     */
    public function findOrFail($id);
}

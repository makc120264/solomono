<?php

namespace code\Interfaces;

interface EntityInterface
{
    /**
     * @return mixed
     */
    public function getAll(): mixed;

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed;

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data): mixed;

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;
}
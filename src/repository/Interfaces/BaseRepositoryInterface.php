<?php

namespace Luuka\LaravelBaseRepository\Repository\Interfaces;

interface BaseRepositoryInterface
{
    public function getAll(array $params=['*']);

    public function find(string $id, array $column = ['*']);

    public function findOrFail(string $id, $column = ['*']);

    // public function findByField($data = [], $column=['*']);

    public function create(array $data);

    public function update(array $data = ['*'], string $id);

    public function destroy(string $id);
}
<?php

namespace App\Contracts;

interface CRUDContract
{

    public function getAll();

    public function getAllByParams(array $params);

    public function getById($id);

    public function getByParams(array $params);

    public function getFirst();

    public function getLast();

    public function insert(array $data);

    public function delete($id);

    public function update(array $data, $id);

    public function truncate();

    public function runQuery($sql, array $params);

    public function count();
}

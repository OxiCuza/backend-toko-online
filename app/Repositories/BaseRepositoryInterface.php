<?php


namespace App\Repositories;


interface BaseRepositoryInterface
{

    public function getByPrimaryKey(int $primary_key);

    public function getByColumn(string $column, $value);

    public function getAllData(array $params = []);

    public function store($data);

    public function updateByPrimaryKey(int $primary_key, $data);

    public function updateByColumn(string $column, $value, $data);

    public function deleteByPrimaryKey(int $primary_key);

    public function deleteByColumnKey(string $column, $value);

}

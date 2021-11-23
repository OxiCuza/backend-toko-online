<?php

namespace App\Repositories\Book;

use App\Models\Book;
use App\Repositories\BaseRepositoryEloquent;

/**
 * Class BookRepository
 * @package App\Repositories\Book
 */
class BookRepository extends BaseRepositoryEloquent implements BookRepositoryInterfaces
{
    /**
     * BookRepository constructor.
     * @param Book $model
     */
    public function __construct(Book $model)
    {
        parent::__construct($model);
    }
}

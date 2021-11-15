<?php

namespace App\Repositories\Category;

use App\Repositories\BaseRepositoryInterface;
//use Your Model

/**
 * Interface CategoryRepositoryInterfaces
 * @package App\Repositories\Category
 */
interface CategoryRepositoryInterfaces extends BaseRepositoryInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function deletePermanent(int $id);

}

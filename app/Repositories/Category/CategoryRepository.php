<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepositoryEloquent;

/**
 * Class CategoryRepository
 * @package App\Repositories\Category
 */
class CategoryRepository extends BaseRepositoryEloquent implements CategoryRepositoryInterfaces
{
    /**
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $id
     * @return mixed|string
     */
    public function restore(int $id)
    {
        try {
            $category = Category::withTrashed()->find($id);

            if ($category->trashed()) {
                $category->restore();
                return 'Category successfully restored';

            } else {
                return 'Category is not in trash';
            }

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    /**
     * @param int $id
     * @return mixed|string
     */
    public function deletePermanent(int $id)
    {
        try {
            $category = Category::withTrashed()->find($id);

            if (!$category->trashed()){
                return 'Can not delete permanent active category';

            } else {
                $category->forceDelete();
                return 'Category permanently deleted';
            }

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
}

<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryEloquent;
//use Your Model
use App\Models\User;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepositoryEloquent implements UserRepositoryInterfaces
{
    /**
     * @return string
     *  Return the model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}

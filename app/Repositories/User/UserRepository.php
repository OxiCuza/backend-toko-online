<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryEloquent;
use App\Models\User;

/**
 * Class UserRepository
 * @package App\Repositories\User
 */
class UserRepository extends BaseRepositoryEloquent implements UserRepositoryInterfaces
{
    /**
     * UserRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}

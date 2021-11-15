<?php


namespace App\Repositories;

/**
 * Class BaseRepositoryEloquent
 * @package App\Repositories
 */
class BaseRepositoryEloquent
{
    /**
     * @var $model = Model yang digunakan pada repository ini.
     */
    protected $model;

    /**
     *
     * Constructor, Parameter Model
     *
     * @param $model
     *
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     *
     * Get Data Single dengan menggunakan primary key
     *
     * @param int $primary_key
     * @return mixed
     */
    public function getByPrimaryKey(int $primary_key)
    {
        return $this->model->find($primary_key);
    }

    /**
     *
     * Get Data dengan parameter id
     *
     * @param string $column
     * @param $value
     * @return mixed
     */
    public function getByColumn(string $column, $value)
    {
        return $this->model->where($column,$value)->first();
    }

    /**
     *
     * Get Data dengan Beberapa Parameter Array
     *
     * $params['select']
     * $params['where']
     * $params['where_in']
     * $params['sort_by']
     * $params['offset']
     * $params['limit']
     * $params['relations']
     *
     * @param array $params
     * @return mixed
     */

    public function getAllData(array $params = [])
    {
        if(isset($params['relations'])){
            $this->model =  $this->model->with($params['relations']);
        }

        # Jika mau Seleksi beberapa kolom saja
        if(isset($params['select'])) {
            $this->model = $this->model->select($params['select']);
        }

        #inner join
        if(isset($params['join'])) {
            $this->model = $this->model->join($params['join']['table'],$params['join']['condition']);
        }

        # Jika mau mencari sesuatu single
        if(isset($params['where'])) {
            $this->model = $this->model->where($params['where']);
        }

        # Jika mau mencari data yang ada di relation dengan where in
        if(isset($params['where_in'])) {
            $this->model = $this->model->whereIn($params['where_in']);
        }

        /* Scope */
        if(isset($params['scope'])){
            $this->model = $this->modelScope($params['scope'],$this->model);
        }

        #Order By
        if(isset($params['order_by'])){
            foreach($params['order_by'] as $order){
                $this->model = $this->model->orderBy($order['column'],$order['direction']);
            }
        }

        #Group By
        if(isset($params['group_by'])){
            $this->model = $this->model->groupBy($params['group_by']);
        }

        /* Bawah Sendiri */
        if(isset($params['count'])){
            return $this->model->count();
        }

        if(isset($params['sum'])){
            return $this->model->sum($params['sum']);
        }

        if(isset($params['single'])){
            return $this->model->first();
        }

        if(isset($params['find'])){
            return $this->model->find($params['find']);
        }

        if (isset($params['only_trashed'])) {
            return $this->model->onlyTrashed();
        }

        if (isset($params['with_trashed'])) {
            return $this->model->withTrashed();
        }

        if(isset($params['paginate'])){
            return $this->model->paginate($params['paginate']);
        }

        # Jika menggunakan limit dan offset
        if(isset($params['limit'])){
            $offset = 0;
            if(isset($params['offset'])){
                $offset = $params['offset'];
            }

            return $this->model->offset($offset)->limit($params['limit'])->get();
        }

        return $this->model->get();

        /* End Bawah Sendiri */
    }

    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $model = new $this->model;
        $model = $model->fill($data);
        $model->save();
        return $model;
    }

    /**
     * @param int $primary_key
     * @param $data
     * @return mixed
     */
    public function updateByPrimaryKey(int $primary_key, $data)
    {
        $model = $this->model->find($primary_key);
        $model->fill($data);
        $model->save();
        return $model;
    }

    /**
     * @param string $column
     * @param $value
     * @param $data
     * @return mixed
     */
    public function updateByColumn(string $column, $value, $data)
    {
        $model = $this->model->where($column,$value)->first();
        $model->fill($data);
        $model->save();
        return $model;
    }

    /**
     * @param int $primary_key
     * @return bool
     */
    public function deleteByPrimaryKey(int $primary_key){
        $model = $this->model->find($primary_key);

        if($model){
            return $model->delete();
        }else{
            return false;
        }
    }

    /**
     * @param string $column
     * @param $value
     * @return bool
     */
    public function deleteByColumnKey(string $column, $value){
        $model = $this->model->where($column,$value)->first();

        if($model){
            return $model->delete();
        }else{
            return false;
        }
    }

    protected function modelScope($params,$model){
        return $model;
    }
}

<?php

namespace App\Repositories\Eloquent;

use Illuminate\Container\Container;
use App\Repositories\Contracts\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    private $container;

    protected $model;

    /**
     * Repository constructor.
     *
     * @param Container $container
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->setModel();
    }

    /**
     * 抽象函数 动态获取模型
     *
     *
     * @return mixed
     */
    abstract public function model();

    /**
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return mixed
     */
    public function setModel()
    {
        $model = $this->container->make($this->model());

        return $this->model = $model;
    }

    /**
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * @Author xxx
     * http://litblc.com
     *
     * @param int   $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($perPage = 10, $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @Author xxx
     * http://litblc.com
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @Author xxx
     * http://litblc.com
     *
     * @param array $data
     * @param $id
     * @param string $attribute
     *
     * @return mixed
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * @Author xxx
     * http://litblc.com
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @Author xxx
     * http://litblc.com
     *
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @Author xxx
     * http://litblc.com
     *
     * @param $attribute
     * @param $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = ['*'])
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }
}

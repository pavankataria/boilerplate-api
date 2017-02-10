<?php

namespace PavanKataria\BoilerplateApi\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Model;


use PavanKataria\BoilerplateApi\Responses\PKResponseResource;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceCreateError;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceCreateSuccessful;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceDeleteSuccessful;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceNotFound;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceUpdateError;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceUpdateMassAssignmentError;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceUpdateSuccessful;
use PavanKataria\BoilerplateApi\Traits\HasCustomKeyForIndexing;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
abstract class BaseRepository
{
    /** @var Model */
    protected $model;

    /**
     * @var
     */
    protected $query;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    function __construct (Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $filters
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($filters = [], $columns = array('*'))
    {
        if(empty($filters)){
            return $this->model->all();
        }
        $this->query = $this->model->newQuery();

        foreach($filters as $name => $value){
            $method = "filterBy".ucfirst($name);
            if (method_exists($this, $method)){
                call_user_func_array([$this, $method], array_filter([$value]));
            }
        }
        return $this->query->get($columns);
    }

    /**
     * @param Builder $builder
     */
    public function apply(Builder $builder){

    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 20, $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return PKResponseResourceCreateSuccessful|PKResponseResourceCreateError
     */
    public function create(array $data)
    {
        $resource = new $this->model;
        $resource->fill($data);
        if(!$resource->save()){
            return new PKResponseResourceCreateError;
        }
        return new PKResponseResourceCreateSuccessful($resource);
    }


    /**
     * @param array $data
     * @param $id
     * @return PKResponseResourceNotFound|PKResponseResourceUpdateError|PKResponseResourceUpdateSuccessful
     */
    public function update(array $data, $id)
    {
        $resource = $this->findResource($id);
        if(!$resource){
            return new PKResponseResourceNotFound;
        }
        try{
            $success = $resource->update($data);
        }
        catch(MassAssignmentException $e){
            return new PKResponseResourceUpdateMassAssignmentError;
        }
        if(!$success){
            return new PKResponseResourceUpdateError;
        }
        return new PKResponseResourceUpdateSuccessful($resource);
    }


    /**
     * @param $id
     * @return PKResponseResourceDeleteError|PKResponseResourceDeleteSuccessful|PKResponseResourceNotFound
     */
    public function delete($id)
    {
        $resource = $this->findResource($id);
        if(!$resource){
            return new PKResponseResourceNotFound;
        }
        if(!$resource->delete($id)){
            return new PKResponseResourceDeleteError;
        }
        return new PKResponseResourceDeleteSuccessful;
    }

    /**
     * @param $id
     * @param array $columns
     * @return PKResponseResource|PKResponseResourceNotFound
     */
    public function find($id, $columns = array('*'))
    {
        $resource = $this->findResource($id, $columns);
        if (!$resource) {
            return new PKResponseResourceNotFound;
        }
        return new PKResponseResource($resource);
    }

    /**
     * @param $id
     * @param $columns
     * @return mixed
     */
    private function findResource($id, $columns = array('*'))
    {
        $indexKey = $this->getModelIndexKeyName();
        $resource = $this->findBy($indexKey, $id, $columns);
        return $resource;
    }

    /**
     * @return string
     */
    private function getModelIndexKeyName()
    {
        $indexKey = $this->model->getKeyName();
        if (in_array(HasCustomKeyForIndexing::class, class_uses($this->model))) {
            if ($this->model->usesCustomKeyForIndexing()) {
                $indexKey = $this->model->customKeyForIndexing();
            }
        }
        return $indexKey;
    }
    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    private function findBy($attribute, $value, $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }
}
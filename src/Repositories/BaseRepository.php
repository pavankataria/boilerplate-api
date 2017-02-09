<?php

namespace App\Repositories;

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
use Ramsey\Uuid\Uuid;

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
     * @param string $guid
     * @return PKResponseResourceNotFound|PKResponseResourceUpdateError|PKResponseResourceUpdateSuccessful
     */
    public function update(array $data, $guid)
    {
        $resource = $this->model->whereGuid($guid)->first();
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
     * @return PKResponseResourceDeleteSuccessful|PKResponseResourceNotFound|PKResponseResourceDeleteError
     */
    public function delete($id)
    {
        $resource = $this->model->whereId($id)->first();
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
        $resource = $this->model->find($id, $columns);
        if(!$resource){
            return new PKResponseResourceNotFound;
        }
        return new PKResponseResource($resource);
    }

    /**
     * @param $value
     * @return PKResponseResource|PKResponseResourceNotFound
     */
    public function findGuid($value){
        $resource = $this->model->whereGuid($value)->first();
        if(!$resource){
            return new PKResponseResourceNotFound;
        }
        return new PKResponseResource($resource);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return PKResponseResource|PKResponseResourceNotFound
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {

//        dd($this->model->where("id", "=", $value)->first());

//        $resource = $this->mgit addodel->where($attribute, '=', $value)->first($columns);
//          dd($resource);
// WORKS
//        dd($this->model->whereId($value)->get());
//        dd($this->model->where('id', '=', $value)->first());
//        dd($this->model->where('guid', '=', $value)->first());
//        dd("this: {$this->model}, attribute: {$attribute}, value: {$value}");
        dd($this->model->whereId(10)->first());
        $resource = $this->model->where("id", '=', $value)->first($columns);

//        return new PKResponseResource($this->model->whereGuid($value)->first());

        if(!$resource){
            return new PKResponseResourceNotFound;
        }
        return new PKResponseResource($resource);
    }

}
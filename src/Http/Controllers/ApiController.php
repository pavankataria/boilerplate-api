<?php
namespace PavanKataria\BoilerplateApi\Http\Controllers;

use League\Fractal\Serializer\JsonApiSerializer;
use PavanKataria\BoilerplateApi\Http\ApiResponseManager;
use PavanKataria\BoilerplateApi\Responses\PKResponseBadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use PavanKataria\BoilerplateApi\Responses\PKResponseResourceNotFound;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceCreateError;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceCreateSuccessful;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceDeleteSuccessful;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceUpdateSuccessful;
use PavanKataria\BoilerplateApi\Responses\PKResponseResourceUpdateMassAssignmentError;


/**
 * Class ApiController
 * @package PavanKataria\BoilerplateApi\Http\Controllers
 */
class ApiController extends Controller {

    /* @var Manager Manager */
    protected $fractalManager;


    /** @var BaseRepository */
    protected $queryRepository;


    /** @var AbstractTransformer Transformer */
    protected $transformer;


    /**
     * A variable to store custom form request classes for children.
     * @var array */
    protected $requestClasses = [];

    /** @var ApiResponseManager */
    protected $apiManager;

    /**
     * @var mixed
     */
    protected $request;

    /**
     * ApiController constructor.
     * @param null $repository
     * @param null $transformer
     */
    function __construct($repository = null, $transformer = null)
    {
        $this->fractalManager = App::make(Manager::class);
//        $this->fractalManager->setSerializer(new JsonApiSerializer());
        $this->apiManager = App::make(ApiResponseManager::class);
        $this->request = $this->initialiseRequest();
        $this->queryRepository = $repository;
        $this->transformer = $transformer;
    }

    /**
     * This checks to see if there is a Custom Form Request set in the child controller
     * If so, then use that custom form request and instantiate it so that it can be
     * accessed. If not, then a default class is created. The request is returned
     *
     * @return mixed
     */
    protected function initialiseRequest()
    {
        // Find out the method before that called the getRequest method,
        // Whether it was the store or update method.
        $methodName = debug_backtrace()[1]['function'];

        // The custom form request classes set in the child controller classes
        // and see if any classes have been set for the method. If not then
        // use the default Illuminate\request class to capture the data.
        $requestClasses = $this->requestClasses;
        $class = array_get($requestClasses, $methodName, Request::class);
        // Instantiate the request class
        return App::make($class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $queryParameters = $this->request->all();
        if (is_null($this->queryRepository)){
            return $this->apiManager->respondNotFound('Items do not exist.');
        }
        $items = $this->queryRepository->all($queryParameters);
        if ($items->isEmpty()) {
            return $this->apiManager->respondNotFound('Items do not exist.');
        }
        $processedItems = $this->processItems($items) ?? $items;
        return $this->apiManager->respond($processedItems);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $this->initialiseRequest();
        $response = $this->queryRepository->find($id);
        if ($response instanceof PKResponseResourceNotFound) {
            return $this->apiManager->respondNotFound('Resource not found.');
        }
        if(is_null($this->transformer)){
            return $this->respondWithArray($response->resource);
        }
        $itemResource = new Item($response->resource, $this->transformer, $this->transformer->resourceKey);
        $processedItems = $this->fractalManager->createData($itemResource);
        return $this->apiManager->respond($processedItems->toArray());
    }

    /**
     * @return mixed
     */
    public function store()
    {
        $data = $this->initialiseRequest()->all();
        $response = $this->queryRepository->create($data);
        if ($response instanceof PKResponseResourceCreateError) {
            return $this->apiManager->setStatusCode($response->statusCode)->respondWithError($response->message);
        }
        else if($response instanceof PKResponseResourceCreateSuccessful){
            $itemResource = new Item($response->resource, $this->transformer);
            $processedItems = $this->fractalManager->createData($itemResource);
            return $this->apiManager->setStatusCode($response->statusCode)->respond($processedItems->toArray());
        }
        else{
            return $this->apiManager->setStatusCode($response->statusCode)->respondWithMessage($response->message);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @return Response
     */
    public function update($id)
    {
        $requestParameters = $this->initialiseRequest()->all();
        $requestResponse = $this->requestParametersOrFail($requestParameters);

        if ($requestResponse instanceOf PKResponseBadRequest) {
            return $this->apiManager->returnResponseWithoutResource($requestResponse);
        }

        $response = $this->queryRepository->update($requestResponse, $id);

        if( $response instanceof PKResponseResourceNotFound ||
            $response instanceof PKResponseResourceUpdateError ||
            $response instanceof PKResponseResourceUpdateMassAssignmentError
        ){
            return $this->apiManager->setStatusCode($response->statusCode)->respondWithError($response->message);
        }
        else if($response instanceof PKResponseResourceUpdateSuccessful){
            $itemResource = new Item($response->resource, $this->transformer);
            $processedItems = $this->fractalManager->createData($itemResource);
            return $this->apiManager->setStatusCode($response->statusCode)->respond($processedItems->toArray());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->initialiseRequest();
        $response = $this->queryRepository->delete($id);
        if( $response instanceof PKResponseResourceNotFound ||
            $response instanceof PKResponseResourceDeleteError){
            return $this->apiManager->setStatusCode($response->statusCode)->respondWithError($response->message);
        }
        else if($response instanceof PKResponseResourceDeleteSuccessful){
            return $this->apiManager->setStatusCode($response->statusCode)->respondWithSuccess($response->message);
        }
    }

    /**
     * This method returns either a bad request response or an array of column fields that need updating
     * @param array $requestParameters
     * @return PKResponseBadRequest|array
     */
    protected function requestParametersOrFail(array $requestParameters)
    {
        if (empty($requestParameters)){
            return new PKResponseBadRequest('No update parameters specified');
        }
        return $requestParameters;
    }

    /**
     * @param $items
     * @param $transformer
     * @return array
     */
    function processItemsWithTransformer($items, $transformer)
    {
        $itemsResource = new Collection($items, $transformer);
        $processedItems = $this->fractalManager->createData($itemsResource)->toArray();
        return $processedItems;
    }

    /**
     * @param $item
     * @param $transformer
     * @return array
     */
    function processItemWithTransformer($item, $transformer)
    {
        $itemResource = new Item($item, $transformer);
        $processedItems = $this->fractalManager->createData($itemResource)->toArray();

//        $itemsResource = new Collection($items, $transformer);
//        $processedItems = $this->fractalManager->createData($itemsResource)->toArray();
        return $processedItems;
    }
    /**
     * @param $items
     * @return array|null
     */
    private function processItems($items)
    {
        if (is_null($this->transformer)) {
            return null;
        }
        $itemsResource = new Collection($items, $transformer ?? $this->transformer, $this->transformer->resourceKey);
        $processedItems = $this->fractalManager->createData($itemsResource)->toArray();
        return $processedItems;
    }

    /**
     * @param $array
     * @return mixed
     */
    public function respondWithArray($array)
    {
        return $this->apiManager->respond([
            'data' => $array
        ]);
    }


    /**
     * @param int $code
     * @param string $message
     * @return mixed
     */
    public function respondWithCodeAndError($code, $message){
        return $this->apiManager->setStatusCode($code)->respondWithError($message);
    }
}
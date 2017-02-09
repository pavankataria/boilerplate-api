<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 01/10/15
 * Time: 12:54
 */

namespace PavanKataria\BoilerplateApi\Responses;


use Illuminate\Http\Response;

class PKResponseResourceCreateSuccessful extends PKResponse {

    function __construct($resource)
    {
        $this->responseType = PKResponse::RESPONSE_SUCCESS;
        $this->message = 'The resource was successfully created';
        $this->statusCode = Response::HTTP_CREATED;
        $this->resource = $resource;
    }
}
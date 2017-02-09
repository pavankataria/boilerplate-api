<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 01/10/15
 * Time: 12:33
 */

namespace App\Http\Responses;

use Illuminate\Http\Response;

class PKResponseResourceUpdateSuccessful extends PKResponse{

    function __construct($resource)
    {
        $this->responseType = PKResponse::RESPONSE_SUCCESS;
        $this->message = 'The resource has successfully been updated';
        $this->statusCode = Response::HTTP_OK;
        $this->resource = $resource;
    }
}
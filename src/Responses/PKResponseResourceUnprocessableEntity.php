<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 31/12/15
 * Time: 14:04
 */

namespace App\Http\Responses;

use Illuminate\Http\Response;

class PKResponseResourceUnprocessableEntity extends PKResponse {
    function __construct($resource)
    {
        $this->responseType = PKResponse::RESPONSE_ERROR;
        $this->message = "Unauthorised";
        $this->statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        $this->resource = $$resource;
    }
}
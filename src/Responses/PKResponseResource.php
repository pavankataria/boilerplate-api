<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 07/10/15
 * Time: 16:04
 */

namespace App\Http\Responses;


use Illuminate\Http\Response;

class PKResponseResource extends PKResponse {
    public function __construct($resource)
    {
        $this->responseType = PKResponse::RESPONSE_SUCCESS;
        $this->statusCode = Response::HTTP_OK;
        $this->resource = $resource;
    }
}
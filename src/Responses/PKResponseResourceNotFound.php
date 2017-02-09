<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 01/10/15
 * Time: 11:59
 */

namespace PavanKataria\BoilerplateApi\Responses;

use Illuminate\Http\Response;

class PKResponseResourceNotFound extends PKResponse {
    function __construct()
    {
        $this->responseType = PKResponse::RESPONSE_ERROR;
        $this->message = 'Resource not found';
        $this->statusCode = Response::HTTP_NOT_FOUND;
    }
}
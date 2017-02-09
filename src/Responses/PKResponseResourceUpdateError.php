<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 01/10/15
 * Time: 12:20
 */

namespace PavanKataria\BoilerplateApi\Responses;


use Illuminate\Http\Response;

class PKResponseResourceUpdateError extends PKResponse{
    function __construct()
    {
        $this->responseType = PKResponse::RESPONSE_ERROR;
        $this->message = 'The resource could not be updated at this time';
        $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 01/10/15
 * Time: 16:09
 */

namespace PavanKataria\BoilerplateApi\Responses;


use PavanKataria\BoilerplateApi\Responses;

class PKResponseResourceDeleteError extends PKResponse{
    function __construct()
    {
        $this->responseType = PKResponse::RESPONSE_ERROR;
        $this->message = 'The resource could not be deleted at this time';
        $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 02/10/15
 * Time: 16:23
 */

namespace PavanKataria\BoilerplateApi\Responses;


use Illuminate\Http\Response;

class PKResponseResourceUpdateMassAssignmentError extends PKResponse{
    function __construct()
    {
        $this->responseType = PKResponse::RESPONSE_ERROR;
        $this->message = 'Mass assignment issue. The request could not be understood due to a bad request';
        $this->statusCode = Response::HTTP_BAD_REQUEST;
    }
} 
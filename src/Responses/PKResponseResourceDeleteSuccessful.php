<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 01/10/15
 * Time: 16:12
 */

namespace PavanKataria\BoilerplateApi\Responses;


use Illuminate\Http\Response;

class PKResponseResourceDeleteSuccessful extends PKResponse {
    function __construct()
    {
        $this->responseType = PKResponse::RESPONSE_SUCCESS;
        $this->message = 'The resource has been successfully deleted';
        $this->statusCode = Response::HTTP_OK;

    }
} 
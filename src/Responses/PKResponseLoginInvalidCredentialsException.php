<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 12/10/15
 * Time: 07:00
 */

namespace PavanKataria\BoilerplateApi\Responses;


use Illuminate\Http\Response;

/**
 * Class PKResponseLoginInvalidCredentialsException
 * @package PavanKataria\BoilerplateApi\Responses
 */
class PKResponseLoginInvalidCredentialsException extends PKResponse{

    function __construct()
    {
        $this->responseType = PKResponse::RESPONSE_ERROR;
        $this->message = "Unauthorised";
        $this->statusCode = Response::HTTP_UNAUTHORIZED;
    }
}
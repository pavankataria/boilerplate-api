<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 01/02/2017
 * Time: 19:36
 */

namespace App\Http\Responses;


use Illuminate\Http\Response;

class PKResponseBadRequest extends PKResponse
{
    function __construct($message = 'This request is a bad one. Please check the request and try again.')
    {
        $this->responseType = PKResponse::RESPONSE_ERROR;
        $this->message = $message;
        $this->statusCode = Response::HTTP_BAD_REQUEST;
    }
}
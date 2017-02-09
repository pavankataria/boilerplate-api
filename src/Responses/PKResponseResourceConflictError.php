<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 06/02/2017
 * Time: 13:08
 */

namespace PavanKataria\BoilerplateApi\Responses;
use Illuminate\Http\Response;



class PKResponseResourceConflictError extends PKResponse
{
    public function __construct($message)
    {
        $this->responseType = PKResponse::RESPONSE_ERROR;
        $this->statusCode = Response::HTTP_CONFLICT;
        $this->message = $message;
    }
}
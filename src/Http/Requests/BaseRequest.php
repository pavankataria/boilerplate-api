<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 01/02/2017
 * Time: 16:29
 */

namespace PavanKataria\BoilerplateApi\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

/**
 * Class BaseRequest
 * @package PavanKataria\BoilerplateApi\Http\Requests
 */
class BaseRequest extends FormRequest
{
    /**
     * @param array $errors
     * @return mixed
     */
    public function response(array $errors)
    {
        $transformedErrorMessages = [];
        foreach ($errors as $field => $message){
            $transformedErrorMessages[] = [
                'field' => $field,
                'message' => $message[0]
            ];
        }
        return response()->json([
//            'response' => [
            'errors' => $transformedErrorMessages
//                ]
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}

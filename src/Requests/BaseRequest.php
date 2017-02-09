<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 01/02/2017
 * Time: 16:29
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class BaseRequest extends FormRequest
{
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

//DRAFT 0
/*
 * `{
  "status": [
    "The selected status is invalid."
  ]
}
 */
//Draft 1
// no point having errors attribute because anything above 299 is an error
/*    public function response(array $errors)
    {
        return response()->json([
            'errors' => true,
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'messages' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }


Example:
{
  "status": 422,
  "errors" : true,
  "messages": {
    "status": [
      "The selected status is invalid."
    ]
  }
}
*/
//Draft 2
/*    public function response(array $errors)
    {
        return response()->json([
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'messages' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
Example:
{
  "status": 422,
  "messages": {
    "status": [
      "The selected status is invalid."
    ]
  }
}*/
//FINAL:
/*
 * public function response(array $errors)
    {
        $transformedErrorMessages = [];
        foreach ($errors as $field => $message){
            $transformedErrorMessages[] = [
                'field' => $field,
                'message' => $message[0]
            ];
        }
        return response()->json([
            'errors' => $transformedErrorMessages
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
//Final result
/*{
  "errors": [
    {
      "field": "status",
      "message": "The selected status is invalid."
    }
  ],
  "status": 422
}
 */

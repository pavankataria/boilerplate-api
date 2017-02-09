<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 01/10/15
 * Time: 11:58
 */

namespace PavanKataria\BoilerplateApi\Responses;

/**
 * Class PKResponse
 * @package PavanKataria\BoilerplateApi\Responses
 */
/**
 * Class PKResponse
 * @package PavanKataria\BoilerplateApi\Responses
 */
abstract class PKResponse {
    const RESPONSE_ERROR = 'error';
    const RESPONSE_SUCCESS = 'success';
    /**
     * @var
     */
    public $message;
    /**
     * @var
     */
    public $statusCode;

    /**
     * @var null
     */
    public $resource;

    /**
     * @var
     */
    public $responseType;

    /**
     * @param $statusCode
     * @param $message
     * @param null $resource
     * @param $responseType
     */
    function __construct($statusCode, $message, $resource = null, $responseType)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->resource = $resource;
        $this->responseType = $responseType;
    }
} 
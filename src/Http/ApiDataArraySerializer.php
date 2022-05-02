<?php

namespace PavanKataria\BoilerplateApi\Http;

use League\Fractal\Serializer\ArraySerializer;

class ApiDataArraySerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return ['data' => $data];
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return ['data' => $data];
    }

    /**
     * Serialize null resource.
     *
     */
    public function null()
    {
        return null;//['data' => []];
    }
}

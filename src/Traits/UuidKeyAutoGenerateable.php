<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 24/09/2016
 * Time: 18:13
 */

namespace App\Traits;


use Ramsey\Uuid\Uuid;

/**
 * Class UuidKeyAutoGenerateable
 * @package App\Traits
 */
trait UuidKeyAutoGenerateable {
    /**
     * Boot method automatically gets called for models
     */
    public static function bootUuidKeyAutoGenerateable() {
        static::creating(function($entity) {
            $entity->guid = Uuid::uuid4()->toString();
        });
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Pavan Kataria
 * Date: 10/02/2017
 * Time: 14:09
 */

namespace PavanKataria\BoilerplateApi\Traits;

/**
 * Class HasCustomKeyForIdentifying
 * @package App\Traits
 * Pavan Kataria
 */
trait HasCustomKeyForIndexing
{
    /**
     * Sometimes you want the internal of laravel to use the default primary key for indexing
     * but also alow the search for models based on a custom key passed from a request, like a guid field.
     *
     * @var bool
     */
    private $customKey = true;

    /**
     * Determine if the model uses a custom key for identifying itself.
     *
     * @return bool
     */
    public function usesCustomKeyForIndexing()
    {
        return $this->customKey;
    }

    /**
     * Retrieve the key to use for database retrieval
     *
     * @return string
     */
    abstract public function customKeyForIndexing();
}
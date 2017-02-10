<?php
/**
 * Created by PhpStorm.
 * User: pavankataria
 * Date: 10/02/2017
 * Time: 21:06
 */

namespace PavanKataria\BoilerplateApi\Services;


use Illuminate\Database\Eloquent\Model;

class BaseEditor
{
    /**
     * @param Model $model
     * @param $id //can sometimes be a string or an integer, and it can have different index search, id, guid, or something custom.
     * @param string $relationName
     * @return string|void
     */
    protected function updateBelongsToIdRelationOnModel(Model $model, $id, string $relationName){
        if ($model == null || $id == null || $relationName == null) {
            return;
        }
        //Check if the id is already the same, no point updating if the related models are the same
        $relatedModel = $model->getRelation($relationName);
        //This grabs the key name of the related model that's currently attached to the agent object
        $relatedModelPreferredIndexKeyName = $this->getModelPreferredIndexKeyName($relatedModel);
        //This finds a related entity that matches the id
        if ($newRelatedModel = $relatedModel->where($relatedModelPreferredIndexKeyName , '=', $id)->first()) {
            //Set the new model as a relation to the agent model
            $model->setRelation($relationName, $newRelatedModel);
            //Associate the related model to the model
            $model->{$relationName}()->associate($model->{$relationName});
            $model->save();
        }
    }

    /**
     * @param Model $model
     * @return string
     */
    protected function getModelPreferredIndexKeyName(Model $model)
    {
        $indexKey = $model->getKeyName();
        if (in_array(HasCustomKeyForIndexing::class, class_uses($model))) {
            if ($model->usesCustomKeyForIndexing()) {
                $indexKey = $model->customKeyForIndexing();
            }
        }
        return $indexKey;
    }
}
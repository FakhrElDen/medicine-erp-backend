<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait CheckNestedRelations
{
    public function checkRelation($relation, $model = null)
    {
        $model ??= $this;

        $relations = explode('.', $relation);

        if (empty($relations)) {
            return false;
        }

        foreach ($relations as $rel) {
            if (is_null($model)) {
                return false;
            }
            if ($model->relationLoaded($rel)) {
                $model = $model->{$rel} instanceof Collection ? $model->{$rel}->first() : $model->{$rel};
            } else {
                return false;
            }
        }

        return true;
    }
}

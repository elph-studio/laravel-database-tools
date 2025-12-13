<?php

declare(strict_types=1);

namespace Elph\LaravelDatabase\Repository;

use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Repository
{
    public function save(EloquentModel $model): EloquentModel
    {
        $original = $model->getOriginal();

        $model->save();

        if (method_exists($model, 'appendChangesList') === true) {
            $model->appendChangesList($model, $original);
        }

        return $model->refresh();
    }

    public function delete(EloquentModel $model): bool
    {
        return $model->delete() === true;
    }
}

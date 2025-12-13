<?php

declare(strict_types=1);

namespace Elph\LaravelDatabase\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
abstract class Model extends EloquentModel
{
    use ModelTrait;

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}

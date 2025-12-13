<?php

declare(strict_types=1);

namespace Elph\LaravelDatabase\Seeder;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Seeder as IlluminateSeeder;

abstract class Seeder extends IlluminateSeeder
{
    use WithoutModelEvents;

    abstract public function getModel(): EloquentModel;
    abstract public function run(): void;

    public function getDependencies(): array
    {
        return [];
    }
}

<?php

declare(strict_types=1);

use Elph\LaravelDatabase\Migration\Blueprint;
use Elph\LaravelDatabase\Migration\Migration;
use Elph\LaravelHelpers\Entity\Environment;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Environment::isTesting() === true) {
            return;
        }

        Schema::table('migrations', static function (Blueprint $table) {
            $table
                ->dateTime('migrated_at')
                ->useCurrent();
        });
    }
};

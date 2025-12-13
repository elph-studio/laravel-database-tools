<?php

declare(strict_types=1);

namespace Elph\LaravelDatabase\Migration;

use Illuminate\Database\Migrations\Migration as BaseMigration;
use JetBrains\PhpStorm\Deprecated;
use RuntimeException;

abstract class Migration extends BaseMigration
{
    /**
     * @Deprecated and blocked due to risks of breaking down production database
     *
     * If you want to discuss why this method is deprecated, think of situations when you are going to use it.
     *
     * ## When migration failed
     * So if you failed to create correct migration, do you really think rolling it back to previous version
     * will succeed? Probably, it will fail too, and you will have to solve two problems instead of one.
     * If you need to rollback database, create new migration or do it manually.
     *
     * ## When you need to rollback codebase to previous version, but database does not fit older code requirements
     * This is improper database structure handling case.
     * Migrations must be compatible both with latest and new codebase.
     * This will not only help to prevent database rollbacks, but will also prevent application errors when
     * application is horizontally scaled and several versions of the same app are running while deploying.
     *
     * ## When you need to run migrations up and down locally
     * Write proper seeds and fakers (factories) and run `artisan migrate:fresh --seed` to renew local database.
     *
     * ## For more information Google or AI: Why it is bad practice to use database migrations rollbacks?
     */
    final public function down(): void
    {
        throw new RuntimeException(
            'Migration rollbacks are deprecated due to risks of breaking down production database'
        );
    }
}

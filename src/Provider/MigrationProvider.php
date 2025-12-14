<?php

declare(strict_types=1);

namespace Elph\LaravelDatabase\Provider;

use Elph\LaravelHelpers\Entity\Environment;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Database\Console\Migrations\InstallCommand;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Console\Migrations\RefreshCommand;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Database\MigrationServiceProvider as BaseMigrationServiceProvider;
use Illuminate\Support\Collection;

class MigrationProvider extends BaseMigrationServiceProvider
{
    protected const array CUSTOM_MIGRATION_FOLDERS = [
        'src/Migration/Migration',
        'vendor/elph-studio/*/src/Migration',
        'vendor/elph-studio/*/src/Database/Migration',
        'vendor/laravel/telescope/database/migrations',
    ];

    protected $commands = [
        'MigrateInstall' => InstallCommand::class,
        'Migrate' => MigrateCommand::class,
        'MigrateStatus' => StatusCommand::class,
    ];

    protected array $devCommands = [
        'MigrateFresh' => FreshCommand::class,
        'MigrateRefresh' => RefreshCommand::class,
        'MigrateReset' => ResetCommand::class,
        'MigrateRollback' => RollbackCommand::class,
    ];

    public function boot(): void
    {
        $this
            ->getExistingMigrationDirectories()
            ->each(function ($migration) {
                $this->loadMigrationsFrom($migration);
            });

        $this->registerCommands(
            Environment::isDevelopment() === true
                ? collect($this->commands)->merge($this->devCommands)->toArray()
                : $this->commands
        );
    }

    private function getExistingMigrationDirectories(): Collection
    {
        $migrationDirectories = new Collection();

        collect(self::CUSTOM_MIGRATION_FOLDERS)
            ->each(static function (string $directory) use (&$migrationDirectories) {
                collectDirectories($directory, 3)
                    ->each(static function (string $directory) use (&$migrationDirectories) {
                        $migrationDirectories->push($directory);
                    });
            });

        return $migrationDirectories;
    }
}

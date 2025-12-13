<?php

declare(strict_types=1);

namespace Elph\LaravelDatabase\Seeder;

use Elph\LaravelHelpers\Entity\Environment;
use Elph\LaravelHelpers\Service\ClassCollector\CommonPathsFilesCollector;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Console\OutputStyle;
use Illuminate\Database\Seeder as IlluminateSeeder;
use Illuminate\Support\Collection;

/**
 * Extend this file in Application database/seeders/DatabaseSeeder.php and leave class empty.
 */
class SeedersRunner extends IlluminateSeeder
{
    use InteractsWithIO;

    private const string ENTITY = 'seeders';
    private const string SUFFIX = 'Seeder.php';
    private const array ALWAYS_IGNORE_FILES = [
        'database/seeders/DatabaseSeeder.php',
    ];

    public function __construct(OutputStyle $output)
    {
        $this->setOutput($output);
    }

    // phpcs:disable OpaySniffs.Files.ForbiddenDumps
    public function run(): void
    {
        if (Environment::isLocal() === false) {
            $this->error('Seeders can be ran only in Local environment.');

            exit;
        }

        $seeders = $this->collectSeeders();
        if ($seeders->isEmpty()) {
            $this->warn('No seeders found.');

            exit;
        }

        $this->call($seeders->toArray());
    }

    /*
     * Set list of *Seeder.php files that should be skipped when running seeders
     */
    protected function getIgnores(): array
    {
        return [];
    }

    private function collectSeeders(): Collection
    {
        $seeders = new CommonPathsFilesCollector(self::ENTITY, self::SUFFIX)->get();

        collect(self::ALWAYS_IGNORE_FILES)
            ->merge($this->getIgnores())
            ->each(static function ($file) use (&$seeders) {
                $seeders = $seeders->reject(static fn ($value) => $value === $file);
            });

        $seeders->map(static function ($seeder, $key) use (&$seeders) {
            $seeders[$key] = pathToNamespace($seeder);
        });

        return $seeders->isEmpty() ? $seeders : $this->sortSeeders($seeders);
    }

    private function sortSeeders(Collection $seeders): Collection
    {
        $visitedSeeders = new Collection();
        $sortedSeeders = new Collection();

        $seeders->each(function ($seederClass) use (&$visitedSeeders, $sortedSeeders) {
            $seeder = resolve($seederClass);
            $this->visitSeeder($seeder, $visitedSeeders, $sortedSeeders);
        });

        return $this->validateSeeders($sortedSeeders);
    }

    // phpcs:disable OpaySniffs.Files.ForbiddenDumps
    private function visitSeeder(Seeder $seeder, Collection $visitedSeeders, Collection $sortedSeeders): void
    {
        if ($visitedSeeders->has($seeder::class) === false) {
            $visitedSeeders->put($seeder::class, $seeder);

            $dependencies = collect($seeder->getDependencies());
            $dependencies->each(function ($dependencyClass) use ($visitedSeeders, $sortedSeeders) {
                $dependency = resolve($dependencyClass);
                $this->visitSeeder($dependency, $visitedSeeders, $sortedSeeders);
            });

            $sortedSeeders->put($seeder::class, $seeder);
        }

        if ($sortedSeeders->has($seeder::class) === false) {
            $this->error(sprintf('Circular dependency found in %s.', $seeder::class));

            exit;
        }
    }

    public function validateSeeders(Collection $seeders): Collection
    {
        $validatedSeeders = new Collection();
        $seeders->each(function (Seeder $class, string $namespace) use (&$validatedSeeders) {
            $model = $class->getModel();
            if ($model::query()->exists()) {
                $this->warn(sprintf('Table "%s" is already seeded or filled with data. Skipping.', $model->getTable()));

                return;
            }

            $validatedSeeders->add($namespace);
        });

        return $validatedSeeders;
    }
}

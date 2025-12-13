<?php

declare(strict_types=1);

namespace Elph\LaravelDatabase\Model;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait ModelTrait
{
    private Collection $changesList;

    public function getRouteKeyName(): string
    {
        return 'identifier';
    }

    public function getDateFormat(): string
    {
        return config('app.date_format', 'Y-m-d\TH:i:sP');
    }

    public function appendChangesList(self|EloquentModel $current, array $original): void
    {
        if (isset($this->changesList) === false) {
            $this->changesList = new Collection();
        }

        $changes = collect($current->getChanges());
        if ($current->wasRecentlyCreated === true) {
            $this->changesList->add(['create' => $current->toArray()]);

            return;
        }

        $changesBlock = new Collection();

        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
        $changes->each(static function ($value, $key) use ($current, $original, &$changesBlock) {
            $changesBlock->put($key, [
                'before' => $original[$key] ?? null,
                'after' => $current->$key ?? 'n/a',
            ]);
        });

        $this->changesList->add(['update' => $changesBlock]);
    }

    public function getChangesList(): Collection
    {
        return $this->changesList ?? new Collection();
    }

    /**
     * This method returns model changes between getting from database and now, even after saving
     *
     * @return Collection<string, array{
     *     before: string|null,
     *     after: string|null,
     * }>
     */
    public function getFinalChangesList($includeCreate = true): Collection
    {
        $finalChanges = collect();
        $this->getChangesList()
            ->each(static function (array $changes) use ($includeCreate, &$finalChanges) {
                $createChanges = Arr::get($changes, 'create', []);

                if ($createChanges !== [] && $includeCreate === true) {
                    $finalChanges = collect($createChanges)
                        ->mapWithKeys(static fn ($value, $key) => [$key => ['before' => null, 'after' => $value]]);
                }

                collect(Arr::get($changes, 'update', []))
                    ->each(static function (array $change, string $key) use (&$finalChanges) {
                        $olderChange = $finalChanges->get($key);

                        if ($olderChange === null) {
                            $finalChanges->put($key, $change);

                            return;
                        }

                        $finalChanges->put($key, [
                            'before' => $olderChange['before'],
                            'after' => $change['after'],
                        ]);
                    });
            });

        return $finalChanges;
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->getDateFormat());
    }
}

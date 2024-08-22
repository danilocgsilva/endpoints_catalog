<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories;

use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;

interface RepositoryInterface
{
    public function save(TraitModel $model): void;

    public function get(int $id): TraitModel;

    public function replace(int $id, TraitModel $model): void;

    public function delete(int $id): void;

    public function list(): array;
}

<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories\Interfaces;

use Danilocgsilva\EndpointsCatalog\Models\ModelsInterface;

interface BaseRepositoryInterface
{
    public function save(ModelsInterface $model): void;

    public function get(int $id): ModelsInterface;

    public function replace(int $id, ModelsInterface $model): void;

    public function delete(int $id): void;

    public function list(): array;
}

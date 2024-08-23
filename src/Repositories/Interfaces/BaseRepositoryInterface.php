<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories\Interfaces;

/**
 * @template T
 */
interface BaseRepositoryInterface
{
    /**
     * @param T $model
     * @return void
     */
    public function save($model): void;

    /**
     * @param int $id
     * @return T
     */
    public function get(int $id);

    /**
     * @param int $id
     * @param T $model
     * @return void
     */
    public function replace(int $id, $model): void;

    public function delete(int $id): void;

    /**
     * @return array<T>
     */
    public function list(): array;
}

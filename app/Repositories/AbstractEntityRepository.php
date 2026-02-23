<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractEntityRepository
{
    public function __construct(protected readonly Model $entity)
    {}

    /**
     * Get a new query builder instance for the underlying entity
     *
     * @return Builder
     */
    protected function entity(): Builder
    {
        return $this->entity->newQuery();
    }
}

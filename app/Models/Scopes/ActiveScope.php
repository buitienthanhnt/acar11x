<?php

namespace App\Models\Scopes;

use App\Models\Types\Base\ShareInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Viết phạm vi toàn cầu
 * fill the active attribute for model collection.
 * https://laravel.com/docs/12.x/eloquent#global-scopes
 */
class ActiveScope implements Scope
{

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (isAdminEnv()) {
            return;
        }
        $builder->where(ShareInterface::ACTIVE, '=', true);
    }
}

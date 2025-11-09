<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class SortScope implements Scope
{
    /**
     * the scope for sort builder query of model.
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        /**
         * get orderBy attribute: in_array: $modelAttributes
         * exp: [id, name, title, created_at, updated_at, ...]
         */
        $orderBy = request()->query('order', 'id'); // attribute
        /**
         * get sortBy value: asc|desc
         */
        $sortBy = strtoupper(request()->query('sort'));
        /**
         * check if has: $orderBy
         * check if $orderBy attribute in list field of model
         * check if $sortBy value === asc || desc
         */
        if ($sortBy && in_array($sortBy, ['ASC', 'DESC'])) {
            /**
             * get all attributes of model table
             * hàm tính này mất khá nhiều thời gian cho nên hạn chế gọi nhất có thể.
             * @var array $modelAttributes
             */
            $modelAttributes = Schema::getColumnListing($model->getTable());
            if (in_array($orderBy, $modelAttributes)) {
                $builder->orderBy($orderBy, $sortBy);
            }
        }
    }
}

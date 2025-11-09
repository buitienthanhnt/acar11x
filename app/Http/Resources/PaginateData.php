<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * create reource cmd: php artisan make:resource PaginateData
 */
class PaginateData extends JsonResource
{
    /**
     * Transform the resource into an array.
     * use for response not use [links] array of paginate data.
     * the links use many memory for response so it should be remove.
     * use: return new PaginateData($model->paginate($limit))
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * get input resource array data
         */
        $resourceArrayData = $this->resource->toArray();
        /**
         * unset [links] key item value of response data.
         */
        if (isset($resourceArrayData['links'])) {
            unset($resourceArrayData['links']);
        }
        /**
         * return new source without of [links]
         */
        return ($resourceArrayData);
        // return parent::toArray($request);
    }
}

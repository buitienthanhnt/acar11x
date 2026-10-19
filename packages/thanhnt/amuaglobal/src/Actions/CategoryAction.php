<?php

namespace Thanhnt\Amuaglobal\Actions;

use Illuminate\Http\Request;
use Thanhnt\Amuaglobal\Models\Category;

final class CategoryAction
{
    public function __construct()
    {
        // throw new \Exception('Not implemented');
    }

    /**
     * action for register new category
     * @param Request $request
     */
    public function register(Request $request)
    {
        $modelData = $request->toArray();
        $newCategory = Category::create($modelData);
        return $newCategory;
    }
}

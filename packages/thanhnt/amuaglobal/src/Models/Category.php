<?php

namespace Thanhnt\Amuaglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;
use Thanhnt\Amuaglobal\Models\ShareActions\{ActiveAttrModel, AliasAttrModel};

final class Category extends Model implements CategoryInterface
{
    use ActiveAttrModel;
    use AliasAttrModel;

    public function categoryOptions() {
        $categories = Category::all();
        return $categories;
    }

    public function childrents() {}

    public function parent() {}

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

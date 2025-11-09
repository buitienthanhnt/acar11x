<?php

namespace App\Models\ShareAction;

use App\Helper\StringHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

trait AliasAttrModel
{
    /**
     * format alias path of model if input value null object will use value of title
     */
    public function alias(): Attribute
    {
        /**
         * $this is the model current(has origin attributes).
         */
        return Attribute::make(set: function (string|null $input) {
            return Str::snake(StringHelper::vn_to_str($input ?: $this->{self::TITLE ?? self::NAME}, true, ["." => "", ":" => "", "/" => "", "," => "", "_" => "-"]), '-');
        });
    }
}

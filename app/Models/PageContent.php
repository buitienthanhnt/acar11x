<?php

namespace App\Models;

use App\Models\ShareAction\ActiveAttrModel;
use App\Models\Types\FormInterface;
use App\Models\Types\PageContentInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageContent extends Model implements PageContentInterface
{
    use HasFactory;

    use SoftDeletes;
    use ActiveAttrModel;

    protected $fillable = self::FILLED_FILEDS;

    /**
     * define for hidden attributes of this model
     */
    protected $hidden = self::HIDDEN_FIELDS;

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    function value(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                switch ($this->{self::TYPE}) {
                    case FormInterface::TYPE_FILE:
                    case FormInterface::TYPE_IMAGE_CHOOSE:
                        return storagePathToUrl($value);
                        break;
                    default:
                        return $value;
                        break;
                }
            }
        );
    }
}

<?php

namespace App\Models;

use App\Models\Types\TagInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model implements TagInterface
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = self::FILLED_FILEDS;

    /**
     * define for hidden attributes of this model
     */
    protected $hidden = self::HIDDEN_FIELDS;

    /**
     * link from tag to page.
     * One to Many (Inverse) / Belongs To
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pages(): BelongsTo
    {
        return $this->BelongsTo(Page::class, TagInterface::TARGET_ID);
    }
}

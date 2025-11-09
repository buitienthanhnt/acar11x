<?php

namespace App\Models;

use App\Models\Types\DesignInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Design extends Model implements DesignInterface
{
    use HasFactory;
    /**
     * define for auto fill.
     */
    protected $fillable = self::FILLED_FILEDS;

    /**
     * khai báo chuyển đổi kiểu dữ liệu
     */
    protected $casts = [
        self::VALUE => 'array',  // Casts the 'value' json column to an array
    ];

    protected $hidden = ['created_at', "deleted_at", 'id',];

    /**
     * define for type of page design automatic.
     * @return array
     */
    public static function typeOptions(): array
    {
        return [
            ["label" => "home", "value" => "home-page"],
            ["label" => "page-detail", "value" => "page-detail"],
            ["label" => "page-list", "value" => "page-list"],
            ["label" => "writer-list", "value" => "writer-list"],
        ];
    }

    /**
     * define type for load component
     * @return array
     */
    public static function loadType(): array
    {
        return [
            ["label" => "tải đồng bộ", "value" => "sync"],
            ["label" => "tải không đồng bộ(defer)", "value" => "async"],
            ["label" => "tải khi có lệnh(when-visible)", "value" => "optional"],
        ];
    }
}

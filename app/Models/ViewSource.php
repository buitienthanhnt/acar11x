<?php

namespace App\Models;

use App\Models\Types\ViewSourceInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewSource extends Model implements ViewSourceInterface
{
    use HasFactory;

    /**
     * khai báo cho phép gán hàng loạt.
     */
    protected $fillable = self::FILLED_FIELDS;

    /**
     * khai báo chuyển đổi kiểu dữ liệu
     */
    protected $casts = [
        self::VALUE => 'array',  // Casts the 'value' column to an array
    ];

    // khai báo ẩn các thuộc tính sau khi truy vấn.
    protected $hidden = ['created_at', 'updated_at', self::TYPE];
}

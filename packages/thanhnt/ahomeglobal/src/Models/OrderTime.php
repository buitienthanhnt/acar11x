<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;

/**
 * sử dụng homeId để phân loại thì có thể lấy nhanh các order time của home đó
 * tuy nhiên có vấn đề là nó làm tăng số bản ghi trong bảng order_times
 * giả sử: 1 năm với 365 ngày có 200 hotel thì số bản ghi trong 1 năm có thể lên tới 730.000
 * dẫn tới 10 năm là 7.300.000 bản ghi.
 * (hiện tại vẫn để phân loại theo home_id, sau sẽ xem xét tối ưu lấy các booked date theo các room trong homedetail) 
 * lưu ý: bỏ: RoomInterface::BOOKED_DATE trong $home?->rooms->setHidden([RoomInterface::BOOKED_DATE, ...RoomInterface::HIDDEN_FIELDS]); của HomeApi::getHomeDetail()
 */
class OrderTime extends Model implements OrderTimeInterface
{
    use SoftDeletes;

    /**
     * khai báo chuyển đổi kiểu dữ liệu
     */
    protected $casts = [
        self::ORDER_IDS => 'array',
        self::ROOM_IDS => 'array',  // Casts the 'ROOM_IDS' column to an array
    ];

    protected $fillable = self::FILLED_FILEDS;
    protected $hidden = self::HIDDEN_FIELDS;
}

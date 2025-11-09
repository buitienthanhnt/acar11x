<?php
namespace App\Enums;

/**
 * doc for enum
 * https://www.php.net/manual/en/language.types.enumerations.php
 * https://stitcher.io/blog/php-enums
 * use:
 * PageEnum::Pending->value
 * PageEnum::Pending->key
 */
enum ShareEnum: string {
	case Demo = 'demo';
	case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

	/**
	 * function for check
	 * test outline.
	 * ShareEnum::processOrder(ShareEnum::Approved)
	 */
	public static function processOrder(ShareEnum $input){
		return match ($input) {
			ShareEnum::Pending => "Đang xử lý đơn hàng...",
			ShareEnum::Approved => "Đơn hàng đã được duyệt.",
			ShareEnum::Rejected => "Đơn hàng đã bị từ chối.",
		};
	}

	/**
	 * test inline
	 * $status = Status::ARCHIVED;
	 * $status->color(); // 'red'
	 */
	public function color(): string
    {
        return match($this) 
        {
            ShareEnum::Pending => "Đang xử lý đơn hàng...",
			ShareEnum::Approved => "Đơn hàng đã được duyệt.",
			ShareEnum::Rejected => "Đơn hàng đã bị từ chối.",
        };
    }
}
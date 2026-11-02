<?php

namespace Thanhnt\Amuaglobal\Observers;

use Illuminate\Support\Facades\Storage;
use Thanhnt\Amuaglobal\Models\Category;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;

/**
 * create observer model event
 * Tạo lắng nghe sự kiện cho Eloquent Model
 * Khai báo trong Eloquent model: #[ObservedBy([CategoryObserver::class])]
 */
class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        /**
         * lấy đường dẫn trực tiếp từ database chưa qua cast
         * vì nếu lấy bình thường thì giá trị đã bị cast chuyển đổi qua dạng url(thêm đầu storage/)
         * và không phải đường dẫn file trong storage.
         * vd trực tiếp: categories/loc-dau.jfif
         * sau cast:     /storage/categories/loc-dau.jfif
         */
        $file_path_storage = $category->getAttributes()[CategoryInterface::IMAGE] ?? null;
        /**
         * delete image of category
         */
        $file_path_storage && $status = Storage::disk('public')->delete($file_path_storage);
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}

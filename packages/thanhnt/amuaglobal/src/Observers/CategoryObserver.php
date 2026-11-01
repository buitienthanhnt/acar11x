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
         * delete image of category
         */
        Storage::delete($category->{CategoryInterface::IMAGE});
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

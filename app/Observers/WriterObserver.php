<?php

namespace App\Observers;

use App\Models\Writer;

/**
 * create observer model event: php artisan make:observer WriterObserver --model=Writer
 * Tạo lắng nghe sự kiện cho Eloquent Model
 * Khai báo trong Eloquent model: #[ObservedBy([UserObserver::class])]
 */
class WriterObserver
{
    /**
     * Handle the Writer "created" event.
     */
    public function created(Writer $writer): void
    {
        //
    }

    /**
     * Handle the Writer "updated" event.
     */
    public function updated(Writer $writer): void
    {
        //
    }

    /**
     * Handle the Writer "deleted" event.
     */
    public function deleted(Writer $writer): void
    {
        //
    }

    /**
     * Handle the Writer "restored" event.
     */
    public function restored(Writer $writer): void
    {
        //
    }

    /**
     * Handle the Writer "force deleted" event.
     */
    public function forceDeleted(Writer $writer): void
    {
        //
    }
}

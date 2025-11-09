<?php

namespace App\Listeners;

use App\Helper\CacheHelper;
use App\Models\Types\PageInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;

class PageSavedListen
{
    protected $request;

    /**
     * cache helper define
     * @var \App\Helper\CacheHelper $cacheHelper
     */
    protected $cacheHelper;

    /**
     * Create the event listener.
     */
    public function __construct(
        Request $request,
        CacheHelper $cacheHelper,
    ) {
        $this->request = $request;
        $this->cacheHelper = $cacheHelper;
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        /**
         * define in event class define.
         */
        $page = $event->page;

        /**
         * clear cache detail by alias key(use by frontend view detail).
         */
        $this->cacheHelper->clear('p_' . PageInterface::ALIAS . "=" . $page->{PageInterface::ALIAS});

        /**
         * dung sync se dam bao xay dung ban ghi 1-1 khong bi trung lap trong bang trung gian
         * https://laravel.com/docs/12.x/eloquent-relationships#updating-many-to-many-relationships
         * Syncing Associations
         */
        $page->categories()->sync($this->request->get(PageInterface::CATEGORY));
    }
}

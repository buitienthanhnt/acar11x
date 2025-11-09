<?php

namespace App\Listeners;

use App\Helper\StringHelper;
use App\Models\Page;
use App\Models\Types\PageInterface;
use App\Models\Types\TagInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class PageTagListen
{
    protected $request;

    /**
     * Create the event listener.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        /**
         * @var Page $page
         */
        $page = $event->page;
        /**
         * force delete old value
         */
        $page->tags()->forceDelete();
        /**
         * insert new data.
         */
        $page->tags()->createMany($this->tagFormsValue($page->id));
    }

    /**
     * format list tags value submited of page.
     * @param int $page_id
     * @return array
     */
    protected function tagFormsValue(int $page_id): array
    {
        $formSubmit = $this->request->get(PageInterface::TAGS, []);
        $tagFormValues = [];
        foreach ($formSubmit as $value) {
            $tagFormValues[] = [
                TagInterface::KEY => Str::snake(StringHelper::vn_to_str($value)),
                TagInterface::VALUE => $value,
                TagInterface::TYPE => 'page',
                TagInterface::TARGET_ID => $page_id
            ];
        }
        return $tagFormValues;
    }
}

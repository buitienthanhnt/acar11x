<?php

namespace App\Listeners;

use App\Helper\ImageHelper;
use App\Models\Types\FormInterface;
use App\Models\Types\PageContentInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;

class PageContentListen
{
    use ImageHelper;

    protected $request;

    /**
     * Create the event listener.
     */
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        /**
         * get page pass from event
         */
        $page = $event->page;
        /**
         * get submit form data.
         */
        $formValue = $this->pageContentValue($page->id);
        /**
         * delete old content data and
         * insert content from data into page_content.
         */
        $page->pageContents()->forceDelete();
        $page->pageContents()->createMany($formValue);
    }

    /**
     * format page content form data
     * @param int $page_id
     * @return array
     */
    protected function pageContentValue(int $page_id): array
    {
        $formValue = [];
        $listKey = explode('|', $this->request->get('key-sort'));
        foreach ($listKey as $key) {
            $type = explode('-', $key, 2)[0];
            $value = $this->request->get($key);
            switch ($type) {
                case FormInterface::TYPE_FILE:
                    if (!$imageUploaded = $this->uploadImage($this->request->file($key), PageContentInterface::SAVED_IMAGE_FOLDER . '/' . $page_id)) {
                        break;
                    }
                    $value = $imageUploaded['public_path'];
                    break;
                case FormInterface::TYPE_IMAGE_CHOOSE:
                    $value = urlToStoragePath($value) ?: null;
                    break;
                case FormInterface::CAROUSEL:
                    $arrayData = json_decode($this->request->get($key), true);
                    $formatVal = array_map(function ($item) {
                        return [
                            'title' => $item['title'],
                            'imagePath' => urlToStoragePath($item['imagePath']),
                        ];
                    }, $arrayData);
                    $value = json_encode($formatVal);
                    break;
                default:
                    break;
            }
            $formValue[] = [
                PageContentInterface::KEY => $key,
                PageContentInterface::VALUE => $value,
                PageContentInterface::EXTEND_VALUE => null,
                PageContentInterface::TYPE => $type,
                PageContentInterface::PAGE_ID => $this->request->get('page_id') ?: $page_id,
            ];
        }
        return $formValue;
    }
}

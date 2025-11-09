<?php

namespace App\Listeners;

use App\Helper\ImageHelper;
use App\Models\Types\CategoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;

class CategorySavedListen
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
         * this variable define in event Class 
         * @var \App\Models\Category $model
         */
        $model = $event->category;
        /**
         * upload file for category
         */
        if (!$imageUploaded = $this->uploadImage($this->request->file(CategoryInterface::IMAGE_PATH), 'categories/' . $model->id)) {
            return;
        }

        /**
         * update category model with image path after upload file
         */
        $model->{CategoryInterface::IMAGE_PATH} = $imageUploaded['public_path'];
        /**
         * save with no trigger event.
         */
        $model->saveQuietly();
    }
}

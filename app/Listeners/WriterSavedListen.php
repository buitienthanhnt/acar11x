<?php

namespace App\Listeners;

use App\Helper\ImageHelper;
use App\Helper\StringHelper;
use App\Models\Types\WriterInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WriterSavedListen
{
    use ImageHelper;

    protected $request;

    const SAVE_FOLDER = 'public/images/';

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
         * 1. save image into storage
         * 2. get image path of file saved
         * 3. update for the writer saved in the event. 
         */
        $uploadedData = $this->uploadWriterImage($event->writer);
        if ($uploadedData) {
            $this->updateWriterImagePath($event->writer, $uploadedData);
        }
    }

    /**
     * thực hiện tải ảnh lên và cập nhật lại đường dẫn vào trong database cho writer model.
     * @param \App\Models\Writer $writer
     * @return mixed|void
     */
    function uploadWriterImage(\App\Models\Writer $writer)
    {
        return $this->uploadImage(
            $this->request->file(WriterInterface::IMAGE_PATH),
            self::SAVE_FOLDER . 'writers/' . $writer->{WriterInterface::ID},
            Str::snake(StringHelper::vn_to_str($writer->{WriterInterface::NAME}), '-')
        );
    }

    /**
     * @param \App\Models\Writer $writer
     * @param mixed $uploadedData
     */
    function updateWriterImagePath(\App\Models\Writer $writer, $uploadedData): void
    {
        /**
         * update mà không kích hoạt sự kiện nào khác.
         */
        if (isset($uploadedData['public_path']) && !empty($uploadedData['public_path'])) {
            // $writer->updateQuietly(
            //     [WriterInterface::IMAGE_PATH => $uploadedData['public_path']]
            // );
            /**
             * chuyển từ sử dụng: updateQuietly -> saveQuietly
             * do loại bỏ cơ chế gán hàng loạt cho IMAGE_PATH
             * IMAGE_PATH sẽ được gán cụ thể  và lưu dạng yên tĩnh không kích hoạt sự kiện.
             */
            $writer->{WriterInterface::IMAGE_PATH} = $uploadedData['public_path'];
            $writer->saveQuietly();
        }
    }
}

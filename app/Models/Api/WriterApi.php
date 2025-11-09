<?php

namespace App\Models\Api;

use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class WriterApi
{
    protected $request;
    protected $writer;

    function __construct(
        Request $request,
        Writer $writer,
    ) {
        $this->request = $request;
        $this->writer = $writer;
    }

    /**
     * get list of writer pagination
     * may be format data.
     */
    public function writerPagination(int $limit = 12): LengthAwarePaginator
    {
        return $this->writer->paginate($limit);
    }

    /**
     * get writer by id
     * @param int $id
     * @return Writer
     */
    public function getById(int $id) {
        return $this->writer->find($id);
    }

    /**
     * get papers of writer
     */
    public function getPapers(int $writerId) {
        return $this->writer->find($writerId)->pages();
    }
}

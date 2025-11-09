<?php
namespace App\Api\Data;

class BaseData
{
    protected $data = null;

    /**
     * @param mixed $data
     * @return $this
     */
    function setData(mixed $data) {
        return $this->data = $data;
    }

    /**
     *
     */
    function getData() : mixed {
        return $this->data;
    }
}

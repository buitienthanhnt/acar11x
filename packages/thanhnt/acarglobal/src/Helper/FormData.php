<?php

namespace Thanhnt\Acarglobal\Helper;

trait FormData
{
    /**
     * get form data for model(filled form value)
     * @param string[] $formFields
     * @param array|\Illuminate\Http\Request $data
     * @return array
     */
    public function formData(array $formFields, array|\Illuminate\Http\Request $data): array
    {
        $formatData = [];
        /**
         * lưu ý là để gán hàng loạt khi lưu 1 Model thì phải định nghĩa đầy đủ các thuộc tính truyền vào.
         * kể cả khi giá trị đó bằng null thì cũng vẫn phải định ngĩa khóa truyên với giá trị null.
         * ex: ['name' => 'abc', 'alias' => null, ...];
         */
        if ($data instanceof ('Illuminate\Http\Request')) {
            $data =  $data->all();
        }

        foreach ($formFields as $field) {
            $formatData[$field['name']] = $data[$field['name']] ?? null;
        }
        return $formatData;
    }
}

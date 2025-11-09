<?php

namespace App\Http\Requests;

use App\Models\Types\CommentInterface;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @extend Illuminate\Http\Request.
 */
class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Hàm này kiểm tra quyền thực thi tiếp theo của yêu cầu.
     * trả về: true là được tiếp tục, false là báo lỗi 302.
     */
    public function authorize(): bool
    {
        /**
         * this function for get user object|null
         * Illuminate\Http\Request $this
         */
        return !!$this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // CommentInterface::TARGET_ID => ['required'],
            // CommentInterface::CONTENT => ['required, min:6, max:256']
        ];
    }
}

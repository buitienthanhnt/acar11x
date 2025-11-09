<?php

namespace App\Http\Requests;

use App\Models\Types\WriterInterface;
use Illuminate\Foundation\Http\FormRequest;

class StoreWriterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            WriterInterface::NAME => ['required', 'string', 'max:255'],
            WriterInterface::EMAIL => ['required', 'string', 'lowercase', 'email', 'max:255']
        ];
    }
}

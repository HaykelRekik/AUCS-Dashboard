<?php

namespace App\Modules\Book\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            Book::COLUMN_TITLE => 'required|string',
            Book::COLUMN_YEAR => 'required|integer',
            Book::COLUMN_AUTHOR => 'required|string|min:3',
            Book::COLUMN_STOCK => 'required|integer|min:0',
            
        ];
    }
}

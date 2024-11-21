<?php

namespace App\Http\Requests\API\V1\NYT;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ISBN;

class BestSellersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'author' => [
                'required_without_all:isbn,title,offset',
                'nullable',
                'string',
            ],
            'isbn' => [
                'required_without_all:author,title,offset',
                'nullable',
                'array',
                new ISBN,
            ],
            'title' => [
                'required_without_all:author,isbn,offset',
                'nullable',
                'string',
            ],
            'offset' => [
                'required_without_all:author,isbn,title',
                'nullable',
                'integer',
                'multiple_of:20',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'author.required_without_all' => 'At least one of author, isbn, title, or offset must be provided',
            'author.string' => 'Author must be a string if provided',
            'isbn.required_without_all' => 'At least one of author, isbn, title, or offset must be provided',
            'isbn.array' => 'ISBN must be an array if provided',
            'isbn.*' => 'Each ISBN must be a valid 10 or 13 digit ISBN',
            'title.required_without_all' => 'At least one of author, isbn, title, or offset must be provided',
            'title.string' => 'Title must be a string if provided',
            'offset.required_without_all' => 'At least one of author, isbn, title, or offset must be provided',
            'offset.integer' => 'Offset must be an integer if provided',
            'offset.multiple_of' => 'Offset must be a multiple of 20',
        ];
    }
}

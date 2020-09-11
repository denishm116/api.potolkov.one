<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CatalogRequest extends FormRequest
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
       $rules =  [
            'title' => 'required|string|min:2',
            'slug' => 'string|min:2',

           'description' => 'string|min:2',
           'files' => 'max:5'
        ];

        switch ($this->getMethod())
        {
            case 'POST':
                return $rules;
            case 'PUT':
                return [
                        'catalog_id' => 'required|integer|exists:catalogs,id', //должен существовать. Можно вот так: unique:games,id,' . $this->route('game'),
                    ] + $rules; // и берем все остальные правила
            // case 'PATCH':
            case 'DELETE':
                return [
                    'catalog_id' => 'required|integer|exists:catalogs,id'
                ];
        }
    }
}

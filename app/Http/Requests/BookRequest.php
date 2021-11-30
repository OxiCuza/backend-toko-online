<?php

namespace App\Http\Requests;

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
        switch ($this->method()) {
            case 'POST':
                $this->rulesStore();
                break;

            case 'PUT':
            case 'PATCH':
                $this->rulesUpdate();
                break;

            default: break;
        }
    }

    public function rulesStore()
    {
        return [
            'title' => 'required|min:5|max:190',
            'description' => 'required|min:20|max:1000',
            'author' => 'required|min:3|max:100',
            'publisher' => 'required|min:3|max:200',
            'price' => 'required|digits_between:0,10',
            'stock' => 'required|digits_between:0,10',
            'cover' => 'required'
        ];
    }

    public function rulesUpdate()
    {
        return [
            'title' => 'required|min:5|max:200',
            'slug' => 'required',
            'description' => 'required|min:20|max:1000',
            'author' => 'required|min:3|max:100',
            'publisher' => 'required|min:3|max:200',
            'price' => 'required|digits_between:0,10',
            'stock' => 'required|digits_between:0,10',
        ];
    }
}

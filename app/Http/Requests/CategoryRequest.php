<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            case 'STORE':
                $this->rulesStore();
                break;

            case 'PUT':
            case 'PATCH':
                $this->rulesUpdate();
                break;

            default: break;
        }

    }

    /**
     * @return string[]
     */
    public function rulesStore()
    {
        return [
            'name' => 'required|min:3|max:20',
            'image' => 'required',
        ];
    }

    /**
     * @return string[]
     */
    public function rulesUpdate()
    {
        return [
            'name' => 'required|min:3|max:20',
            'image' => 'required',
            'slug' => 'required',
        ];
    }
}

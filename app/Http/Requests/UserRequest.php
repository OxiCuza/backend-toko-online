<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     *
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

            default:break;
        };
    }

    /**
     * @return string[]
     */
    public function rulesStore()
    {
        return [
            'name' => 'required|min:5|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'username' => 'required|unique:users',
            'address' => 'required',
            'phone' => 'required|digits_between:10,12',
            'avatar' => 'required',
        ];
    }

    /**
     * @return string[]
     */
    public function rulesUpdate()
    {
        return [
            'name' => 'required|min:5|max:100',
            'address' => 'required',
            'phone' => 'required|digits_between:10,12',
            'roles' => 'required',
        ];
    }
}

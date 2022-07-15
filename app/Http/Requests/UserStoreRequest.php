<?php

namespace FireflyIII\Http\Requests;

use FireflyIII\Support\Request\ChecksLogin;
use FireflyIII\Support\Request\ConvertsDataTypes;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    use ConvertsDataTypes, ChecksLogin;

    /**
     * Get data for controller.
     *
     * @return array
     */
    public function getUserData(): array
    {
        return [
            'email'        => $this->convertString('email'),
            'password'     => $this->convertString('password'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'    => 'email|required',
            'password' => 'confirmed|secure_password',
        ];
    }
}

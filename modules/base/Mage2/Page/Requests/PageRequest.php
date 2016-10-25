<?php

namespace Mage2\Page\Requests;

use Mage2\Framework\Http\Request;

class PageRequest extends Request
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
        $validationRule = [];
        $validationRule['title'] = 'required|max:255';
        $validationRule['content'] = 'required';
        if ($this->getMethod() == 'POST') {
            $validationRule['slug'] = 'required|max:255|alpha_dash|unique:pages';
        }
        if ($this->getMethod() == 'PUT') {
            $validationRule['slug'] = 'required|max:255|alpha_dash';
        }

        return $validationRule;
    }
}

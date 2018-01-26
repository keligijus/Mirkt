<?php

namespace App\Http\Requests\SubCategories;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user()->isAdmin() || $this->user()->isModerator());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'category_id' => 'required|numeric|exists:categories,id',
			'id'          => 'required|numeric|exists:sub_categories,id',
			'name'        => [
				'required', 'string', 'min:3', 'max:15', Rule::unique('sub_categories')->ignore($this->id)
			],
        ];
    }
}

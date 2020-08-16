<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoryPartRequest extends FormRequest
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
            'content' => 'required',
            'is_image' => 'required',
            'story_id' => 'required|exists:App\Models\Story,id'
        ];
    }

    public function all($keys = null)
    {
        // Add route parameters to validation data
        return array_merge(parent::all(), $this->route()->parameters());
    }
}

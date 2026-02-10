<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'negotiable' => $this->boolean('negotiable'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'min:30', 'max:5000'],
            'category_id' => ['required', 'exists:categories,id'],
            'condition' => ['required', Rule::in(Item::CONDITIONS)],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'negotiable' => ['nullable', 'boolean'],
            'county' => ['required', 'string', 'max:120'],
            'town' => ['required', 'string', 'max:120'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::in(Item::STATUSES)],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'removed_image_ids' => ['nullable', 'array'],
            'removed_image_ids.*' => ['integer', 'exists:item_images,id'],
        ];
    }
}

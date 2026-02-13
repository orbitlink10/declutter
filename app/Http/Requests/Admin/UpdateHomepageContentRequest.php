<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomepageContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hero_title' => ['required', 'string', 'max:120'],
            'hero_description' => ['required', 'string', 'max:500'],
            'hero_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'about_page_title' => ['required', 'string', 'max:120'],
            'about_page_content' => ['required', 'string', 'max:50000'],
            'services_page_title' => ['required', 'string', 'max:120'],
            'services_page_content' => ['required', 'string', 'max:50000'],
            'why_choose_title' => ['required', 'string', 'max:120'],
            'why_choose_description' => ['required', 'string', 'max:500'],
            'products_section_title' => ['required', 'string', 'max:120'],
            'home_page_content' => ['required', 'string', 'max:50000'],
            'primary_cta_label' => ['required', 'string', 'max:40'],
            'auth_secondary_cta_label' => ['required', 'string', 'max:40'],
            'guest_secondary_cta_label' => ['required', 'string', 'max:40'],
            'search_label' => ['required', 'string', 'max:60'],
            'search_placeholder' => ['required', 'string', 'max:80'],
            'category_filter_label' => ['required', 'string', 'max:40'],
            'search_button_label' => ['required', 'string', 'max:40'],
            'categories_section_title' => ['required', 'string', 'max:80'],
            'categories_section_link_label' => ['required', 'string', 'max:40'],
            'categories_empty_state' => ['required', 'string', 'max:200'],
            'latest_section_title' => ['required', 'string', 'max:80'],
            'latest_section_link_label' => ['required', 'string', 'max:40'],
            'latest_empty_state' => ['required', 'string', 'max:200'],
        ];
    }
}

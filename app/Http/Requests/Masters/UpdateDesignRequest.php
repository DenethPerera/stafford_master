<?php

namespace App\Http\Requests\Masters;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDesignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $designId = $this->route('design') ? ($this->route('design')->id ?? $this->route('design')) : $this->id;

        return [
            'client_id'          => ['required', 'exists:clients,id'],
            'style_no'           => ['required', 'string', 'max:100'],
            'design_no'          => ['required', 'string', 'max:100', Rule::unique('styles', 'design_no')->ignore($designId)],
            'size_id'            => ['nullable', 'exists:opma_sizes,id'],
            'base_size'          => ['nullable', 'string', 'max:50'],
            'scale_id'           => ['nullable', 'exists:scales,id'],
            'design_position_id' => ['nullable', 'exists:design_positions,id'],
            'stitch_count'       => ['nullable', 'integer', 'min:0'],
            'design_images'      => ['nullable', 'array'],
            'design_images.*'    => ['image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240'],
            'delete_image_ids'   => ['nullable', 'array'],
            'delete_image_ids.*' => ['exists:design_images,id'],
            'status'             => ['required', 'in:Active,Inactive'],
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'client_id.required'     => 'Please select a client.',
            'client_id.exists'       => 'Selected client is invalid.',
            'design_no.unique'       => 'The Design Number has already been taken.',
            'design_images.*.image'  => 'Each artwork file must be a valid image format.',
            'design_images.*.mimes'  => 'Artwork images must be of type: jpeg, png, jpg, webp, svg.',
            'design_images.*.max'    => 'Each artwork image size cannot exceed 10MB.',
        ];
    }
}


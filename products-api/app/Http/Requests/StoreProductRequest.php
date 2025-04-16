<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'sale_price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'description' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Allow only <p>, <br>, <b>, and <strong>
                    if (preg_replace('/<(\/?p|br|b|strong)>/', '', $value) !== strip_tags($value)) {
                        $fail('A descrição só pode conter as tags <p>, <br>, <b> e <strong>.');
                    }
                },
            ],
            'images.*' => 'nullable|image|mimes:jpg,png|max:2048',
        ];
    }


    public function messages(): array
    {
        return [
            'title.required' => 'O título do produto é obrigatório.',
            'sale_price.required' => 'O preço de venda é obrigatório.',
            'cost.required' => 'O custo é obrigatório.',
            'images.*.mimes' => 'Só são permitidas imagens em jpg e png.',
            'images.*.max' => 'Cada imagem não pode ultrapassar 2MB.',
        ];
    }
}

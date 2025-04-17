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
            'sale_price' => 'required|numeric|min:0|max:999999.99',
            'cost' => 'required|numeric|min:0|max:999999.99',
            'active' => 'required|boolean',
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

    protected function prepareForValidation()
    {
        $this->merge([
            'active' => filter_var($this->input('active'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título do produto é obrigatório.',
            'title.string' => 'O título do produto deve ser um texto.',
            'title.max' => 'O título do produto não pode ter mais que 255 caracteres.',

            'description.required' => 'A descrição do produto é obrigatória.',
            'description.string' => 'A descrição do produto deve ser um texto.',

            'sale_price.required' => 'O preço de venda é obrigatório.',
            'sale_price.numeric' => 'O preço de venda deve ser um número.',
            'sale_price.min' => 'O preço de venda não pode ser negativo.',
            'sale_price.max' => 'O preço de venda não pode ser maior que 999999.99.',

            'cost.required' => 'O custo é obrigatório.',
            'cost.numeric' => 'O custo deve ser um número.',
            'cost.min' => 'O custo não pode ser negativo.',
            'cost.max' => 'O custo não pode ser maior que 999999.99.',

            'active.required' => 'O campo ativo é obrigatório.',
            'active.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',

            'images.*.image' => 'Cada arquivo deve ser uma imagem válida.',
            'images.*.mimes' => 'Só são permitidas imagens em jpg e png.',
            'images.*.max' => 'Cada imagem não pode ultrapassar 2MB.',
        ];
    }
}

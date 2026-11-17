<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromoRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'discount_type' => ['required', 'in:percentage,nominal'],
            'discount_value' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) {
                    if ($this->discount_type === 'percentage' && (float) $value > 100) {
                        $fail('Diskon persen tidak boleh lebih dari 100%.');
                    }
                },
            ],
            'min_purchase' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value && $this->starts_at && $value < $this->starts_at) {
                        $fail('Tanggal berakhir tidak boleh sebelum tanggal mulai.');
                    }
                },
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama promo wajib diisi.',
            'discount_type.required' => 'Jenis diskon wajib dipilih.',
            'discount_type.in' => 'Jenis diskon harus persen atau nominal.',
            'discount_value.required' => 'Nilai diskon wajib diisi.',
            'discount_value.numeric' => 'Nilai diskon harus berupa angka.',
            'discount_value.min' => 'Nilai diskon minimal 0.01.',
            'min_purchase.min' => 'Minimal pembelian tidak boleh negatif.',
            'starts_at.date' => 'Tanggal mulai tidak valid.',
            'ends_at.date' => 'Tanggal berakhir tidak valid.',
        ];
    }
}
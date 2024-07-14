<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
            'sunday' => 'required|string|max:255',
            'monday' => 'required|string|max:255',
            'tuesday' => 'required|string|max:255',
            'wednesday' => 'required|string|max:255',
            'thursday' => 'required|string|max:255',
            'friday' => 'required|string|max:255',
            'saturday' => 'required|string|max:255',
            'totalAmount' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'sunday.required' => '日曜日のメニューは必須です。',
            'monday.required' => '月曜日のメニューは必須です。',
            'tuesday.required' => '火曜日のメニューは必須です。',
            'wednesday.required' => '水曜日のメニューは必須です。',
            'thursday.required' => '木曜日のメニューは必須です。',
            'friday.required' => '金曜日のメニューは必須です。',
            'saturday.required' => '土曜日のメニューは必須です。',
            'totalAmount.required' => '金額は必須です。',
            'totalAmount.numeric' => '金額は数値である必要があります。',
            'totalAmount.min' => '金額は0以上でなければなりません。',
        ];
    }
}

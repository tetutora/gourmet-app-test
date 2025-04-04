<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'reservation_date' => ['required', 'after_or_equal:today'],
            'reservation_time' => ['required'],
            'num_people' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'reservation_date.required' => '日付を選択してください。',
            'reservation_date.after_or_equal' => '過去の日付は選択できません。',
            'reservation_time.required' => '予約時間を選択してください。',
            'num_people.required' => '予約人数を入力してください。',
            'num_people.integer' => '予約人数は整数で指定してください。',
            'num_people.min' => '最低1人以上で予約してください。',
        ];
    }
}

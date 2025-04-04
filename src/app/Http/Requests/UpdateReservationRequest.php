<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateReservationRequest extends FormRequest
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
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|date_format:H:i',
            'num_people' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'reservation_date.required' => '予約日を選択してください。',
            'reservation_date.date' => '有効な日付を選択してください。',
            'reservation_date.after_or_equal' => '予約日は本日以降の日付を指定してください。',
            'reservation_time.required' => '予約時間を選択してください。',
            'reservation_time.date_format' => '予約時間が正しくありません。',
            'num_people.required' => '予約人数を入力してください。',
            'num_people.integer' => '予約人数は整数で入力してください。',
            'num_people.min' => '予約人数は1人以上で入力してください。',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 422)
        );
    }

}

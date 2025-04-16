<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => '評価は必須です。',
            'rating.integer' => '評価は整数でなければなりません。',
            'rating.min' => '評価は:min以上でなければなりません。',
            'rating.max' => '評価は:max以下でなければなりません。',
            'comment.required' => 'コメントは必須です。',
            'comment.max' => 'コメントは:max文字以内でなければなりません。',
            'comment.string' => 'コメントは文字列でなければなりません。',
        ];
    }
}

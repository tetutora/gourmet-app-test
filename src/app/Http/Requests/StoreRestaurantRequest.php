<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRestaurantRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'region_id' => 'required',
            'genre_ids' => 'nullable|array',
            'new_genres' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '店舗名は必須です。',
            'name.string' => '店舗名は文字列である必要があります。',
            'name.max' => '店舗名は:max文字以内で入力してください。',
            'description.required' => '店舗説明は必須です。',
            'region_id.required' => '地域を選択してください。',
            'new_genres.string' => '新しいジャンル名は文字列である必要があります。',
            'image_url.image' => '画像ファイルを選択してください。',
            'image_url.mimes' => '画像はjpg、jpeg、png、gifのいずれかである必要があります。',
            'image_url.max' => '画像の最大サイズは2MBです。',
        ];
    }
}

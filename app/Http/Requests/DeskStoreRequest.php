<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeskStoreRequest extends FormRequest//Для валидации выводимых данных
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'required|max:10',
            'name' => 'required|max:255',//'name' - поле для валидации//'required|max:255' - поле будет required и его максимальная длина 255 символов
        ];
    }
}

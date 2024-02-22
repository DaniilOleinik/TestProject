<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlayerRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:2',
            'position' => [
                'required',
                Rule::in(PlayerPosition::arrayValues()),
            ],
            'playerSkills' => 'required',
            'playerSkills.*.skill' => [
                'required|distinct',
                Rule::in(PlayerSkill::arrayValues()),
            ],
            'playerSkills.*.value' => 'numeric|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'position.in' =>
                'The position :input is incorrect, position field can be only one of this value - ' . implode(',', PlayerPosition::arrayValues()),
            'playerSkills.required' => 'The playerSkills field is required.',
            'playerSkills.*.skill.required' => 'The skill field is required.',
            'playerSkills.*.skill.string' => 'The skill field must be a string.',
            'playerSkills.*.skill.in' => 'The skill :input is incorrect, skill field can be only one of this value - ' . implode(',',PlayerSkill::arrayValues()),
            'playerSkills.*.value.numeric' => 'The value field must be a numeric.',
        ];
    }
}

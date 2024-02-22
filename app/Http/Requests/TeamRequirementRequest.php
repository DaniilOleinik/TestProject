<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TeamRequirementRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws ValidationException
     */
    public function rules(): array
    {
        return [
            'required',
            '*.position' => [
                'required',
                Rule::in(PlayerPosition::arrayValues()),
            ],
            '*.mainSkill' => [
                'required',
                Rule::in(PlayerSkill::arrayValues())
            ],
            '*.numberOfPlayers' => 'required|numeric|not_in:0|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            '0' => 'Team requirements is required, at least one object',
            '*.not_in:0' => 'Number of player can\'t be 0',
            '*.position.in' =>
                'The position :input is incorrect, position field can be only one of this value - ' . implode(',', PlayerPosition::arrayValues()),
            '*.mainSkill.in' => 'The mainSkill :input is incorrect, mainSkill field can be only one of this value - ' . implode(',',PlayerSkill::arrayValues()),
        ];
    }

    /**
     * @throws ValidationException
     */
    public function checkForUniquePlayer(): void
    {
        $positions = [];
        $skills = [];
        foreach ($this->request as $player) {
            if (
                in_array($player['position'], $positions)
                && in_array($player['mainSkill'], $skills)
            ) {
                throw ValidationException::withMessages([
                    'mainSkill' =>
                        __(
                            'The player requirements is not correct,'
                            . 'players with same position and skill shouldn\'t exist'
                        )
                ]);
            }
            $positions[] = $player['position'];
            $skills[] =$player['mainSkill'];
        }
    }
}

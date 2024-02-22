<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    private bool $idsNotNeeded;

    public function __construct($resource, bool $idsNotNeeded = false)
    {
        parent::__construct($resource);
        $this->idsNotNeeded = $idsNotNeeded;
    }

    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position,
            'playerSkills' => PlayerSkillResource::collection($this->skills)
        ];

        if ($this->idsNotNeeded) {
            unset($data['id']);
            $data['playerSkills'] = array_map(function ($playerSkill) {
                return ['skill' => $playerSkill->skill, 'value' => $playerSkill->value];
            }, $data['playerSkills']->all());
        }

        return $data;
    }
}

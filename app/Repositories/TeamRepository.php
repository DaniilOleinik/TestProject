<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\TeamRequirementRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Models\PlayerSkill;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TeamRepository
{
    public function bestTeamSelection(TeamRequirementRequest $requirementRequest): array
    {
        $bestPlayers = [];
        $bestPlayersIds = [];

        foreach ($requirementRequest->request as $requirements) {
           $bestPlayersByPosition = Player::where('position', $requirements['position'])
               ->whereHas('skills', function ($skills) use ($requirements) {
                   $skills->select('skill', 'value')->where('skill', $requirements['mainSkill'])
                       ->orWhereIn('skill', \App\Enums\PlayerSkill::arrayValues())->orderByDesc('value')->limit(1);
               })
               ->whereNotIn('id', $bestPlayersIds)
               ->orderByDesc(
                   PlayerSkill::select('value')->whereColumn('player_id', 'players.id')->orderByDesc('value')
                       ->limit(1)
               )
               ->limit($requirements['numberOfPlayers'])
               ->get();

           if ($bestPlayersByPosition->count() < $requirements['numberOfPlayers']) {
               throw new BadRequestException('Insufficient number of players for position: ' . $requirements['position'], 422);
           }

            foreach ($bestPlayersByPosition as $bestPlayerByPosition) {
                $bestPlayersIds[] = $bestPlayerByPosition->id;
                $bestPlayers[] = new PlayerResource($bestPlayerByPosition, true);
            }
        }

        return $bestPlayers;
    }
}

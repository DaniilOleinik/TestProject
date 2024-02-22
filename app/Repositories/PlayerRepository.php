<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Models\PlayerSkill;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PlayerRepository
{
    /**
     * @throws Exception
     */
    public function addPlayer(PlayerRequest $playerRequest): PlayerResource
    {
        try {
            DB::beginTransaction();
            $createdPlayer = Player::create([
                'name' => $playerRequest->name,
                'position' => $playerRequest->position,
            ]);

            $this->playerSkillsInsertion($playerRequest->playerSkills, $createdPlayer->id);
            DB::commit();

            return new PlayerResource($createdPlayer);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
            //need to be handled
        }
    }

    /**
     * @throws Exception
     */
    public function updatePlayer(int $playerId, PlayerRequest $playerRequest): PlayerResource
    {
        try {
            DB::beginTransaction();
            $player = Player::find($playerId);

            if (!$player) {
                throw new BadRequestException('Player with id = '. $playerId .' doesn\'t exist', 404);
            }

            $player->update([
                'name' => $playerRequest->name,
                'position' => $playerRequest->position,
            ]);

            PlayerSkill::where('player_id', $playerId)->delete();

            $this->playerSkillsInsertion($playerRequest->playerSkills, $playerId);
            DB::commit();

            return new PlayerResource(Player::find($playerId));
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
            //need to be handled
        }
    }

    private function playerSkillsInsertion(array $skills, int $playerId): void
    {
        $playerSkills = [];

        foreach ($skills as $skill) {
            $playerSkills[] = [
                'skill' => $skill['skill'],
                'value' => $skill['value'],
                'player_id' => $playerId
            ];
        }

        PlayerSkill::insert($playerSkills);
    }

    /**
     * @throws Exception
     */
    public function getPlayers(): array
    {
        $players = [];

        foreach (Player::get() as $player) {
            $players[] = new PlayerResource($player);
        }

        return $players;
    }

    public function findPlayerById(int $playerId): PlayerResource
    {
        $player = Player::find($playerId);

        if (null === $player) {
            throw new BadRequestException('Player with id = '. $playerId .' doesn\'t exist', 404);
        }

        return new PlayerResource($player);
    }

    /**
     * @throws Exception
     */
    public function deletePlayer(int $playerId): void
    {
        $player = Player::find($playerId);

        if (!$player) {
            throw new BadRequestException('Player with id = '. $playerId .' doesn\'t exist', 404);
        }

        DB::beginTransaction();

        try {
            $player->delete();

            PlayerSkill::where('player_id', $playerId)->delete();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
            //need to be handled
        }
    }
}

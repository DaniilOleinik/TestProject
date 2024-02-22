<?php
declare(strict_types=1);

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;
use App\Http\Requests\PlayerRequest;
use App\Repositories\PlayerRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class PlayerController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(
        playerRepository $playerRepository
    ): Response|Application|ResponseFactory {
        return response($playerRepository->getPlayers(), 200);
    }

    /**
     * @throws Exception
     */
    public function show(
        int $playerId,
        PlayerRepository $playerRepository
    ): Response|Application|ResponseFactory {
        return response($playerRepository->findPlayerById($playerId), 200);
    }

    /**
     * @throws Exception
     */
    public function store(
        PlayerRequest $playerRequest,
        PlayerRepository $playerRepository
    ): Response|Application|ResponseFactory {
        return response(
            $playerRepository->addPlayer($playerRequest),
            201
        );
    }

    /**
     * @throws Exception
     */
    public function update(
        int $playerId,
        PlayerRequest $playerRequest,
        PlayerRepository $playerRepository
    ): Response|Application|ResponseFactory {
        return response(
            $playerRepository->updatePlayer($playerId, $playerRequest),
            200
        );
    }

    /**
     * @throws Exception
     */
    public function destroy(
        int $playerId,
        PlayerRepository $playerRepository
    ): Response|Application|ResponseFactory {
        $playerRepository->deletePlayer($playerId);

        return response("Player successfully deleted", 200);
    }
}

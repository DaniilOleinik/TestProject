<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TeamRequirementRequest;
use App\Repositories\TeamRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TeamController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function bestTeamSelection(
        TeamRequirementRequest $requirementRequest,
        TeamRepository $teamRepository
    ): Response|Application|ResponseFactory {
        $requirementRequest->checkForUniquePlayer();

        return response($teamRepository->bestTeamSelection($requirementRequest), ResponseAlias::HTTP_OK);
    }
}

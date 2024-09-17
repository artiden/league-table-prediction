<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\FicturesAlreadyScheduledException;
use App\Exceptions\NoScheduledFicturesException;
use App\Exceptions\NotEnoughCommandsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SimulateFicturesRequest;
use App\Models\Team;
use App\Repositories\FictureRepository;
use App\Repositories\TeamRepository;
use App\Services\FictureSchedulerService;
use App\Services\FictureSimulatorService;
use App\Services\WinProbabilityCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class FictureController extends Controller
{
    const DEFAULT_TEAMS_COUNT = 4;

    public function __construct(
        protected FictureSchedulerService $fictureScheduler,
        protected FictureSimulatorService $fictureSimulator,
        protected WinProbabilityCalculatorService $winProbabilityCalculator,
        protected FictureRepository       $fictureRepository,
        protected TeamRepository          $teamRepository
    ) {}

    public function generateTeams() {
        $this->teamRepository->deleteAll();
        for ($i = 0; $i < self::DEFAULT_TEAMS_COUNT; $i++) {
            $name = sprintf('Team %s', chr(65 + $i));
            $strength = Team::AVERAGE_STRENGTH + rand(-Team::STRENGTH_DEVIATION, Team::STRENGTH_DEVIATION);
            $this->teamRepository->create($name, $strength);
        }

        return \response();
    }

    public function simulate(SimulateFicturesRequest $request) {
        $week = $request->safe()->integer('week');
        $lastSimulatedWeek = $this->fictureRepository->getLastSimulatedWeek();
        if ($lastSimulatedWeek) {
            if ($week - $lastSimulatedWeek > 1) {
                $week = $lastSimulatedWeek + 1;
            }
        }
        if ($week == 0) {
            $week = 1;
        }
        $scheduledFictures = $this->fictureRepository->getByAttribute('week_number', $week);
        if ($scheduledFictures->isEmpty()) {
            try {
                $this->fictureScheduler->scheduleFictures($week);
            } catch (FicturesAlreadyScheduledException $exception) {
                // Fictures already scheduled, so we need to do nothing
            } catch (NotEnoughCommandsException $exception) {
                return \response()
                    ->json([
                        'noTeams' => true,
                    ], 422);
            }
        }

        try {
            $fictures = $this->fictureSimulator->simulateFictures($week);
        } catch (NoScheduledFicturesException $e) {
            return \response()
                ->json([
                    'noFictures' => true,
                ], 422);
        }

        $response = [
            'league' => $this->teamRepository->getOrdered(),
            'fictures' => $fictures->load('teamA', 'teamB'),
            'predictions' => $week >= 4 ? $this->winProbabilityCalculator->calculateWinProbability() : null,
            'week' => $week,
        ];

        return \response()
            ->json($response);
    }

    public function reset(Request $request) {
        $this->fictureRepository->deleteAll();
        $this->teamRepository->resetScores();

        return \response()
            ->json([
                'message' => 'An application state has been restored',
            ]);
    }

    public function listWeekHistory() {
        $fictures = $this->fictureRepository->getAll()
            ->load('teamA', 'teamB');

        $groupped = $fictures->groupBy('week_number')
            ->sortBy('week_number');

        return \view('history', [
            'matches' => $groupped,
        ]);
    }
}

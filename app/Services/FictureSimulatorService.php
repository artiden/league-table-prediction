<?php

namespace App\Services;

use App\Exceptions\NoScheduledFicturesException;
use App\Models\Ficture;
use App\Models\Team;
use App\Repositories\FictureRepository;
use App\Repositories\TeamRepository;
use Illuminate\Database\Eloquent\Collection;
use Mockery\Exception;

class FictureSimulatorService {
    public function __construct(
        protected FictureRepository $fictureRepository,
        protected TeamRepository $teamRepository,
        protected TeamStrengthCalculatorService $strengthCalculatorService,
    ) {}

    public function simulateFictures(int $week): Collection {
        $fictures = $this->fictureRepository->getByAttribute('week_number', $week);
        if ($fictures->isEmpty()) {
            throw new NoScheduledFicturesException('No fictures for a given week');
        }

        $fictures->each(function (Ficture $ficture){
            if ($ficture->done) {
                return;
            }
            $teamA = $ficture->teamA;
            $teamB = $ficture->teamB;
            $goalsA = $this->strengthCalculatorService->calculatePossibleGoals($teamA);
            $goalsB = $this->strengthCalculatorService->calculatePossibleGoals($teamB);
            $ficture->win_team_goals = max($goalsA, $goalsB);
            $ficture->lost_team_goals = min($goalsA, $goalsB);
            $ficture->done = true;

            $this->saveResult($teamA, $teamB, $goalsA, $goalsB);
            $ficture->save();
        });

        return$fictures;
    }

    protected function saveResult(Team $teamA, Team $teamB, int $goalsA, int $goalsB): void {
        $resultA = [
            'played' => $teamA->played + 1,
            'goals_for' => $teamA->goals_for + $goalsA,
            'goals_against' => $teamA->goals_against + $goalsB,
        ];
        $resultB = [
            'played' => $teamB->played + 1,
            'goals_for' => $teamB->goals_for + $goalsB,
            'goals_against' => $teamB->goals_against + $goalsA,
        ];

        if ($goalsA > $goalsB) {
            $resultA['won'] = $teamA->won + 1;
            $resultA['points'] = $teamA->points + 3;

            $resultB['lost'] = $teamB->lost + 1;
        } elseif ($goalsB > $goalsA) {
            $resultB['won'] = $teamB->won + 1;
            $resultB['points'] = $teamB->points + 3;

            $resultA['lost'] = $teamA->lost + 1;
        } else {
            $resultA['drawn'] = $teamA->drawn + 1;
            $resultA['points'] = $teamA->points + 1;

            $resultB['drawn'] = $teamB->drawn + 1;
            $resultB['points'] = $teamB->points + 1;
        }

        $this->teamRepository->update($teamA->id, $resultA);
        $this->teamRepository->update($teamB->id, $resultB);
    }
}

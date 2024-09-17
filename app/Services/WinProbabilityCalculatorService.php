<?php

namespace App\Services;

use App\Repositories\TeamRepository;
use function Laravel\Prompts\search;

class WinProbabilityCalculatorService {
    const POINTS_WEIGHT = 40;
    const GOALS_FOR_WEIGHT = 30;
    const GOALS_AGAINST_WEIGHT = 20;
    const GOAL_DIFFERENCE_WEIGHT = 10;

    public function __construct(
        protected TeamRepository $teamRepository
    ) {}

    public function calculateWinProbability(): array {
        $teams = $this->teamRepository->getAll();

        $totalPoints = $teams->sum('points');
        $totalGoalsFor = $teams->sum('goals_for');
        $totalGoalsAgainst = $teams->sum('goals_against');
        $totalGoalDifference = $teams->sum('goal_difference');

        $probabilities = [];

        foreach ($teams as $team) {
            $pointsWeight = $totalPoints > 0 ? ($team->points / $totalPoints) * self::POINTS_WEIGHT : 0;
            $goalsForWeight = $totalGoalsFor > 0 ? ($team->goals_for / $totalGoalsFor) * self::GOALS_FOR_WEIGHT : 0;
            $goalsAgainstWeight = $totalGoalsAgainst > 0 ? ((1 - ($team->goals_against / $totalGoalsAgainst)) * self::GOALS_AGAINST_WEIGHT) : 0;
            $goalDifferenceWeight = $totalGoalDifference > 0 ? ($team->goal_difference / $totalGoalDifference) * self::GOAL_DIFFERENCE_WEIGHT : 0;

            $winProbability = $pointsWeight + $goalsForWeight + $goalsAgainstWeight + $goalDifferenceWeight;

            $probabilities[] = [
                'team' => $team,
                'win_probability' => round($winProbability, 2),
            ];
        }

        usort($probabilities, fn($a, $b) => $b['win_probability'] <=> $a['win_probability']);

        return $probabilities;
    }
}

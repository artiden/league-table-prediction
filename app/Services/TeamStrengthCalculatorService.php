<?php

namespace App\Services;

use App\Models\Team;

class TeamStrengthCalculatorService {
    const MAX_GOALS = 5;
    const POINTS_FACTOR = 0.2;
    const GOALS_SCORED_FACTOR = 0.3;
    const GOALS_CONCEDED_FACTOR = -0.3;

    public function calculateStrength(Team $team): float {
        $baseStrength = $team->strength;
        $pointsFactor = $team->points * self::POINTS_FACTOR;
        $goalsScoredFactor = $team->goals_for * self::GOALS_SCORED_FACTOR;
        $goalsConcededFactor = $team->goals_against * self::GOALS_CONCEDED_FACTOR;

        return $baseStrength + $pointsFactor + $goalsScoredFactor + $goalsConcededFactor;
    }

    public function calculatePossibleGoals(Team $team): int {
        $teamStrength = $this->calculateStrength($team);
        $normalizedStrength = max(0, min(1, $teamStrength / 100));
        $possibleGoals = 0;
        do {
            $possibleGoals++;
        } while (mt_rand() / mt_getrandmax() < $normalizedStrength);

        return $possibleGoals;
    }
}

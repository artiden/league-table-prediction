<?php

namespace App\Services;

use App\Exceptions\FicturesAlreadyScheduledException;
use App\Exceptions\NotEnoughCommandsException;
use App\Repositories\FictureRepository;
use App\Repositories\TeamRepository;
use Illuminate\Support\Collection;
use Mockery\Exception;

class FictureSchedulerService {
    public function __construct(
        protected TeamRepository $teamRepository,
        protected FictureRepository $fictureRepository
    ) {}

    public function scheduleFictures(int $week = null) {
        $scheduledFictures = $this->fictureRepository->getByAttribute('week_number', $week);
        if (!$scheduledFictures->isEmpty()) {
            throw new FicturesAlreadyScheduledException('Fictures already scheduled for a given week');
        }

        $teams = $this->teamRepository->getAll();
        if ($teams->count() < 2) {
            throw new NotEnoughCommandsException('Minimum 2 teams needed to schedule a ficture');
        }

        $pairs = $this->generatePairs($teams);
        shuffle($pairs);

        foreach ($pairs as $pair) {
            $this->fictureRepository->create(array_shift($pair), array_shift($pair), $week);
        }
    }

    protected function generatePairs(Collection $teams): array {
        $pairs = [];
        $teamsCount = $teams->count();

        for ($i = 0; $i < $teamsCount; $i++) {
            for ($j = $i+1; $j < $teamsCount; $j++) {
                $pairs[] = [$teams[$i], $teams[$j]];
            }
        }

        return $pairs;
    }
}

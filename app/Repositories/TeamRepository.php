<?php

namespace App\Repositories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository {
    public function create(string $name, int $strength) {
        return Team::create([
            'name' => $name,
            'strength' => $strength,
        ]);
    }

    public function getById(int $id): Team {
        return Team::find($id);
    }

    public function getAll(): Collection {
        return Team::all();
    }

    public function getOrdered(): Collection {
        return Team::orderBy('points', 'desc')
            ->orderBy('goal_difference', 'desc')
            ->orderBy('goals_for', 'desc')
            ->get();
    }
    public function update(int $id, array $data = []): bool {
        return Team::find($id)
            ->update($data);
    }

    public function resetScores(): void {
        $teams = $this->getAll();
        $teams->each(fn(Team $team) => $team->update(Team::DEFAULT_VALUES));
    }

    public function deleteAll() {
        $teams = $this->getAll();
        $teams->each(fn(Team $team) => $team->delete());
    }
}

<?php

namespace App\Repositories;

use App\Models\Ficture;
use App\Models\Team;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Collection;

class FictureRepository{
    public function getAll(): Collection {
        return Ficture::all();
    }

    public function getById(int $id): Ficture {
        return Ficture::find($id);
    }

    public function getByAttribute(string $attribute, mixed $value): Collection {
        return Ficture::where($attribute, $value)
            ->get();
    }

    public function create(Team $teamA, Team $teamB, int $week = 1): Ficture {
        if (is_null($week)) {
            $week = 1;
        }
        $ficture = new Ficture();
        $ficture->week_number = $week;
        $ficture->teamA()->associate($teamA);
        $ficture->teamB()->associate($teamB);
        $ficture->save();

        return $ficture;
    }

    public function deleteAll(): void {
        $fictures = $this->getAll();
        $fictures->each(fn(Ficture $ficture) => $ficture->delete());
    }

    public function getLastSimulatedWeek(): int {
        return intval(Ficture::max('week_number'));
    }
}

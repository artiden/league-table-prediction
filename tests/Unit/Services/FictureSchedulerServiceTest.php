<?php

namespace Tests\Unit\Services;

use App\Exceptions\FicturesAlreadyScheduledException;
use App\Exceptions\NotEnoughCommandsException;
use App\Models\Ficture;
use App\Models\Team;
use App\Repositories\FictureRepository;
use App\Repositories\TeamRepository;
use App\Services\FictureSchedulerService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

class FictureSchedulerServiceTest extends TestCase {
    protected $teamRepositoryMock;
    protected $fictureRepositoryMock;
    protected $service;

    protected function setUp(): void {
        parent::setUp();
        $this->teamRepositoryMock = Mockery::mock(TeamRepository::class);
        $this->fictureRepositoryMock = Mockery::mock(FictureRepository::class);

        $this->service = new FictureSchedulerService(
            $this->teamRepositoryMock,
            $this->fictureRepositoryMock
        );
    }

    protected function tearDown(): void {
        Mockery::close();
        parent::tearDown();
    }

    public function testThrowsExceptionIfFicturesAlreadyScheduled() {
        $week = 1;
        $scheduledFictures = new Collection([
            [
                'team_a_id' => 1,
                'team_b_id' => 2
            ],
        ]);

        $this->fictureRepositoryMock
            ->shouldReceive('getByAttribute')
            ->once()
            ->with('week_number', $week)
            ->andReturn($scheduledFictures);

        $this->expectException(FicturesAlreadyScheduledException::class);

        $this->service->scheduleFictures($week);
    }

    public function testThrowsExceptionIfNotEnoughTeams() {
        $week = 1;
        $scheduledFictures = new Collection();
        $teams = new Collection([
            [
                'id' => 1
            ]
        ]);

        $this->fictureRepositoryMock
            ->shouldReceive('getByAttribute')
            ->once()
            ->with('week_number', $week)
            ->andReturn($scheduledFictures);

        $this->teamRepositoryMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($teams);

        $this->expectException(NotEnoughCommandsException::class);

        $this->service->scheduleFictures($week);
    }
}

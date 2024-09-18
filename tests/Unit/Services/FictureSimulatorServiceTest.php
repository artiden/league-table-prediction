<?php

namespace Tests\Services;

use App\Exceptions\NoScheduledFicturesException;
use App\Models\Ficture;
use App\Models\Team;
use App\Repositories\FictureRepository;
use App\Repositories\TeamRepository;
use App\Services\FictureSimulatorService;
use App\Services\TeamStrengthCalculatorService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

class FictureSimulatorServiceTest extends TestCase {
    protected $fictureRepositoryMock;
    protected $teamRepositoryMock;
    protected $strengthCalculatorServiceMock;
    protected $service;

    protected function setUp(): void {
        parent::setUp();

        $this->fictureRepositoryMock = Mockery::mock(FictureRepository::class);
        $this->teamRepositoryMock = Mockery::mock(TeamRepository::class);
        $this->strengthCalculatorServiceMock = Mockery::mock(TeamStrengthCalculatorService::class);

        $this->service = new FictureSimulatorService(
            $this->fictureRepositoryMock,
            $this->teamRepositoryMock,
            $this->strengthCalculatorServiceMock
        );
    }

    protected function tearDown(): void {
        Mockery::close();
        parent::tearDown();
    }

    public function testThrowsExceptionIfNoScheduledFictures() {
        $week = 1;
        $fictures = new Collection();

        $this->fictureRepositoryMock
            ->shouldReceive('getByAttribute')
            ->once()
            ->with('week_number', $week)
            ->andReturn($fictures);

        $this->expectException(NoScheduledFicturesException::class);

        $this->service->simulateFictures($week);
    }
}

<?php

namespace Tests\Unit\Services;

use App\Models\Team;
use App\Services\TeamStrengthCalculatorService;
use PHPUnit\Framework\TestCase;
use Mockery;

class TeamStrengthCalculatorServiceTest extends TestCase {
    protected $service;

    protected function setUp(): void {
        parent::setUp();
        $this->service = new TeamStrengthCalculatorService();
    }

    protected function tearDown(): void {
        Mockery::close();
        parent::tearDown();
    }

    public function testCalculateStrength() {
        $team = new Team([
            'strength' => 50,
            'points' => 20,
            'goals_for' => 15,
            'goals_against' => 10
        ]);

        $strength = $this->service->calculateStrength($team);

        $expectedStrength = 50 // baseStrength
            + (20 * 0.2) // pointsFactor
            + (15 * 0.3) // goalsScoredFactor
            + (10 * -0.3); // goalsConcededFactor

        $this->assertEquals($expectedStrength, $strength);
    }

    public function testCalculatePossibleGoals() {
        $team = new Team([
            'strength' => 80,
            'points' => 30,
            'goals_for' => 25,
            'goals_against' => 5
        ]);

        $serviceMock = Mockery::mock(TeamStrengthCalculatorService::class)->makePartial();

        $serviceMock->shouldReceive('calculateStrength')
            ->with($team)
            ->andReturn(75);

        $goals = $serviceMock->calculatePossibleGoals($team);

        $this->assertGreaterThanOrEqual(1, $goals);
        $this->assertLessThanOrEqual(TeamStrengthCalculatorService::MAX_GOALS, $goals);
    }
}

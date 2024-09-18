<?php

namespace Tests\Unit\Services;

use App\Models\Team;
use App\Repositories\TeamRepository;
use App\Services\WinProbabilityCalculatorService;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;
use Mockery;

class WinProbabilityCalculatorServiceTest extends TestCase {
    protected $teamRepositoryMock;
    protected $service;

    protected function setUp(): void {
        parent::setUp();

        $this->teamRepositoryMock = Mockery::mock(TeamRepository::class);
        $this->service = new WinProbabilityCalculatorService($this->teamRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCalculateWinProbability() {
        $team1 = new Team([
            'name' => 'Team A',
            'points' => 30,
            'goals_for' => 25,
            'goals_against' => 10,
            'goal_difference' => 15
        ]);

        $team2 = new Team([
            'name' => 'Team B',
            'points' => 20,
            'goals_for' => 20,
            'goals_against' => 15,
            'goal_difference' => 5
        ]);

        $team3 = new Team([
            'name' => 'Team C',
            'points' => 10,
            'goals_for' => 10,
            'goals_against' => 20,
            'goal_difference' => -10
        ]);

        $this->teamRepositoryMock->shouldReceive('getAll')
            ->once()
            ->andReturn(new Collection([
                $team1,
                $team2,
                $team3
            ]));

        $probabilities = $this->service->calculateWinProbability();

        $this->assertCount(3, $probabilities);

        $this->assertEquals('Team A', $probabilities[0]['team']->name);
        $this->assertEquals('Team B', $probabilities[1]['team']->name);
        $this->assertEquals('Team C', $probabilities[2]['team']->name);

        $this->assertGreaterThan($probabilities[1]['win_probability'], $probabilities[0]['win_probability']);
        $this->assertGreaterThan($probabilities[2]['win_probability'], $probabilities[1]['win_probability']);
    }

    public function testCalculateWinProbabilityWithZeroValues() {
        $team1 = new Team([
            'name' => 'Team A',
            'points' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0
        ]);

        $team2 = new Team([
            'name' => 'Team B',
            'points' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0
        ]);

        $this->teamRepositoryMock->shouldReceive('getAll')
            ->once()
            ->andReturn(new Collection([
                $team1,
                $team2
            ]));

        $probabilities = $this->service->calculateWinProbability();

        $this->assertCount(2, $probabilities);

        foreach ($probabilities as $probability) {
            $this->assertEquals(0, $probability['win_probability']);
        }
    }
}

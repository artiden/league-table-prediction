<?php

namespace App\Observers;

use App\Models\Team;

class TeamObserver
{
    /**
     * Handle the Team "created" event.
     */
    public function created(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "updating" event.
     */
    public function updating(Team $team): void
    {
        $team->goal_difference = $team->goals_for - $team->goals_against;
    }

    /**
     * Handle the Team "updated" event.
     */
    public function updated(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "deleting" event.
     */
    public function deleting(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "deleted" event.
     */
    public function deleted(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "restored" event.
     */
    public function restored(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "force deleted" event.
     */
    public function forceDeleted(Team $team): void
    {
        //
    }
}

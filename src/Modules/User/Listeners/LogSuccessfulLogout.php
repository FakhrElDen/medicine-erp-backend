<?php

namespace Modules\User\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Modules\User\Entities\User;

class LogSuccessfulLogout implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle()
    {
        $user = User::find(auth()->user()->id);

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->event('Logout')
            ->log('Logged Out at: '. Carbon::now());
    }
}

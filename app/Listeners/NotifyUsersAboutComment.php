<?php

namespace App\Listeners;

use App\Events\CommentPostedEvent;
use App\Jobs\NotifyUsersPostWasCommented;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUsersAboutComment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommentPostedEvent $event)
    {
        NotifyUsersPostWasCommented::dispatch($event->comment)
        ->onQueue('high');
    }
}

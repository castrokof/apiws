<?php

namespace App\Listeners;


use App\Events\DispensadoUpdatedEvent;
use App\Events\DispensadoCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DispensadoCreatedListener
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
     * @param  DispensadoUpdatedEvent  $event
     * @return void
     */
    public function handle(DispensadoUpdatedEvent $event)
    {
        // Emitir el evento de broadcasting
        broadcast(new \App\Events\DispensadoUpdatedEvent($event->dispensado));
    }
}

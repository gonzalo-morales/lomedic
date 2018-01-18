<?php
namespace App\Listeners;

use App\Events\LogModulos;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Models\Logs;

class SaveLogDB
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
     * @param  LogModulos  $event
     * @return void
     */
    public function handle(LogModulos $event)
    {
        $entity = $event->options['entity']; 
        $company = $event->options['company'];
        $primary = is_a($entity, 'ModelBase') ? $entity->getKeyName() : null;
        $id = is_a($entity, 'ModelBase') ? ($entity->{$primary} ?? null) : null;
        $action = $event->options['action'];
        $comment = $event->options['comment'];
        
        Logs::createLog($entity->getTable(), $company, $id, $action, $comment);
    }
}
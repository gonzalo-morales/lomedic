<?php
namespace App\Listeners;

use App\Events\EventBase;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Http\Models\Administracion\Usuarios;

class Notify
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
     * @param  EventBase  $event
     * @return void
     */
    public function handle(EventBase $event)
    {
        $user = Usuarios::find($event->userId)->toArray();
        
        $user['Nombre'] = $user['nombre_corto'];
        $user['Usuario'] = $user['usuario'];
        $user['Password'] = $user['password'];
        
        
        Mail::send('email.email', $user, function($message) use ($user) {
            $message->to($user['email']);
            $message->subject('Event Testing');
        });
    }
}

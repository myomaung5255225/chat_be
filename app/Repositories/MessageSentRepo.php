<?php

namespace App\Repositories;

use App\Events\MessageSent;
use Pusher\Pusher;

class MessageSentRepo
{
    public static function send($message){
        $options = array('cluster'=>env('PUSHER_APP_CLUSTER'),'encrypted'=>true);

        $pusher = new Pusher(env('PUSHER_APP_KEY'),env('PUSHER_APP_SECRET'),env('PUSHER_APP_ID'),$options);
        $pusher->trigger('private','messagesend',$message);
    }
}

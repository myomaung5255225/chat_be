<?php

namespace App\Repositories;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;

class ChatRepo
{
    public static function getMessages($request, $userId)
    {

        $returnObj = array();
        $returnObj['statusCode'] = 500;
        try {

            $returnObj['messages'] = Message::with('user', 'photos', 'reciver')->where(['user_id' => $request->user('api')->id, "reciver_id" => $userId])->get();
            $returnObj['statusCode'] = 200;
        } catch (\Throwable $th) {
            $returnObj['statusCode'] = 500;
            $returnObj['message'] = $th->getMessage();
        }
        return $returnObj;
    }

    public static function sendMessage($request)
    {
        $returnObj = array();
        $returnObj['statusCode'] = 500;
        try {
            $user = $request->user('api');
            if ($request['body']) {
                $message = Message::create([
                    'user_id' => $user->id,
                    'body' => $request['body'],
                    'reciver_id' => $request['reciver_id']
                ]);
                if ($message) {

                    event(new MessageSent($user, $message, $message->photos));
                    $returnObj['statusCode'] = 200;
                    $returnObj['message'] = 'sent';
                } else {
                    $returnObj['statusCode'] = 422;
                    $returnObj['message'] = 'fail';
                }
            }
            if ($request['images'] ) {
                $message = Message::create([
                    'user_id' => $user->id,
                    'reciver_id' => $request['reciver_id']
                ]);

                    $message->photos()->create([
                        'photoable_id' => $message->id,
                        'photoable_type' => get_class($message),
                        'src' => UploadRepo::upload($request['images'])

                    ]);




                if ($message) {

                    event(new MessageSent($user, $message, $message->photos));
                    $returnObj['statusCode'] = 200;
                    $returnObj['message'] = 'sent';
                } else {
                    $returnObj['statusCode'] = 422;
                    $returnObj['message'] = 'fail';
                }
            }
        } catch (\Throwable $th) {
            $returnObj['statusCode'] = 500;
            $returnObj['message'] = $th->getMessage();
        }
        return $returnObj;
    }
}

<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\ChatRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatsController extends Controller
{
    public function getMessages(Request $request,$userId)
    {
        $messages = ChatRepo::getMessages($request,$userId);
        return response()->json($messages, 200);
    }

    public function sentMessage(Request $request)
    {
       $returnObj = array();
       $returnObj['statusCode']=500;
       try {
          $validator = Validator::make($request->all(),[
            'reciver_id'=>'required'
          ]);
          if($validator->fails()){
            $returnObj['statusCode']=422;
            $returnObj['errros'] = $validator->errors();
          }
          else{
            $message = ChatRepo::sendMessage($request);
             $returnObj['statusCode']=200;
             $returnObj['message']= $message;
          }
       } catch (\Throwable $th) {
         $returnObj['statusCode']=500;
         $returnObj['message']= $th->getMessage();
       }
       return response()->json($returnObj,200);
    }
}

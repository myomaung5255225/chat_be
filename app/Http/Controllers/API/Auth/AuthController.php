<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepsositry = $userRepository;
    }

    public function getUser(Request $request)
    {
        $user = UserRepository::getUser($request);
        return response()->json($user);
    }

    public function getUsers(Request $request)
    {
        $users = UserRepository::allUsers();
        return response()->json($users);
    }

    public function registerUser(Request $request)
    {
        $returnObj = array();
        $returnObj['statusCode'] = 500;
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'c_password' => 'required|same:password'
            ]);
            if ($validator->fails()) {
                $returnObj['errors'] = $validator->errors();
                $returnObj['statusCode'] = 422;
            } else {
                $returnObj = UserRepository::registerUser($request);
            }
        } catch (\Throwable $th) {
            $returnObj['statusCode'] = 500;
            $returnObj['message'] = $th->getMessage();
        }
        return response()->json($returnObj, $returnObj['statusCode']);
    }

    public function loginUser(Request $request)
    {
        $returnObj = array();
        $returnObj['statusCode'] = 500;
        try {
            $validator = Validator::make($request->all(), [

                'email' => 'required|email',
                'password' => 'required',

            ]);
            if ($validator->fails()) {
                $returnObj['errors'] = $validator->errors();
                $returnObj['statusCode'] = 422;
            } else {
                $returnObj = UserRepository::loginUser($request);
            }
        } catch (\Throwable $th) {
            $returnObj['statusCode'] = 500;
            $returnObj['message'] = $th->getMessage();
        }
        return response()->json($returnObj, $returnObj['statusCode']);
    }

    public function logoutUser(Request $request)
    {
        $returnObj = array();
        $returnObj['statusCode'] = 500;
        try {
             $returnObj = UserRepository::logout($request);
        } catch (\Throwable $th) {
            $returnObj['statusCode'] = 500;
            $returnObj['message'] = $th->getMessage();
        }
        return response()->json($returnObj, $returnObj['statusCode']);
    }


}

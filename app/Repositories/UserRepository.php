<?php

namespace App\Repositories;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public static function getUser($request)
    {
        $returnObj = array();
        try {

            $returnObj['data'] = $request->user('api');
            $returnObj['statusCode'] = 200;
        } catch (\Throwable $th) {
            $returnObj["statusCode"] = 500;
            $returnObj["message"] = $th->getMessage();
        }
        return $returnObj;
    }
    public static function allUsers()
    {
        $returnObj = array();
        try {

            $returnObj['data'] = User::with("photo")->orderBy("created_at")->get();
            $returnObj['statusCode'] = 200;
        } catch (\Throwable $th) {
            $returnObj["statusCode"] = 500;
            $returnObj["message"] = $th->getMessage();
        }
        return $returnObj;
    }


    public static function registerUser($request)
    {
        $returnObj = array();
        $returnObj["statusCode"] = 500;
        try {
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password'])
            ]);
            if ($user) {
                $returnObj['accessToken'] = $user->createToken("chatapp")->accessToken;
                $returnObj['user'] = $user;
                $returnObj['statusCode'] = 201;
            } else {
                $returnObj['statusCode'] = 422;
                $returnObj['message'] = 'Register fail';
            }
        } catch (\Throwable $th) {
            $returnObj["statusCode"] = 500;
            $returnObj["message"] = $th->getMessage();
        }
        return $returnObj;
    }

    public static function loginUser($request)
    {
        $returnObj = array();
        $returnObj["statusCode"] = 500;
        try {
            $user = User::where('email', $request['email'])->first();
            if ($user) {
                if (Hash::check($request['password'], $user->password)) {
                    $returnObj['accessToken'] = $user->createToken("chatapp")->accessToken;

                    $returnObj['user'] = $user;
                    $returnObj['statusCode'] = 200;
                } else {
                    $returnObj['statusCode'] = 422;
                    $returnObj['message'] = `Password doesn't match`;
                }
            } else {
                $returnObj['statusCode'] = 422;
                $returnObj['message'] = `User doesn't exist`;
            }
        } catch (\Throwable $th) {
            $returnObj["statusCode"] = 500;
            $returnObj["message"] = $th->getMessage();
        }
        return $returnObj;
    }

    public static function logout($request)
    {
        $returnObj = array();
        $returnObj["statusCode"] = 500;
        try {

            $request->user('api')->token()->revoke();
            $returnObj['statusCode'] = 200;
            $returnObj['message'] = 'You have been successfully logged out!';

        } catch (\Throwable $th) {
            $returnObj["statusCode"] = 500;
            $returnObj["message"] = $th->getMessage();
        }
        return $returnObj;
    }
}

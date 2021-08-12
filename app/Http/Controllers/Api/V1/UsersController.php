<?php


namespace App\Http\Controllers\Api\V1;


use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;


class UsersController extends BaseController
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $data = $request->input();
        $phone = $data['phone'];

        $user = Users::where(['phone' => $phone])->first();
        if(!$user){
            $user = new Users();
            $user->phone = $phone;
            $user->email = '';
            $user->remember_token = '';
            $user->save();
        }


//        $token = JWTAuth::fromUser($user);

        $token = auth('api')->login($user);

        //必须使用密码登录
//        $token = auth('api')->attempt($user->toArray());

        if(!$token ){
            return response()->json(['error' => 'Unauthorized'],401);
        }

        return $this->respondWithToken($token,auth('api')->user());


    }


    public function test()
    {
        $user = $this->getUserInfo();
        var_dump($user);
        var_dump($user->id);
    }

}

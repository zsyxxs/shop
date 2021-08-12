<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

class BaseController extends Controller
{
    //接口帮助调用
    use Helpers;


    // 工具函数
    // 返回错误的请求
    protected function errorBadRequest($validator)
    {
        //throw new ValidationHttpException($validator->errors());
        $result = [];
        $messages = $validator->errors()->toArray();
        if ($messages) {
            foreach ($messages as $field => $errors) {
                foreach ($errors as $error) {
                    $result[] = [
                        'field' => $field,
                        'code' => $error,
                    ];
                }
            }
        }
        $this->responseValidationError($result);
    }

    // 请求成功时对数据进行格式处理
    public function responseSuccess($msg, $data = null)
    {
        return response()->json([
            'code' => '200',
            'msg' => $msg,
            'data' => $data
        ]);
    }

    // 响应失败时返回自定义错误信息
    public function responseError($msg)
    {
        return response()->json([
            'code' => '400',
            'msg' => $msg
        ]);
    }

    // 响应校验失败时返回自定义的信息（基本用不上）
    public function responseValidationError($msgs)
    {
        return response()->json([
            'code' => '401',
            'msgs' => $msgs
        ]);
    }


    // 错误提示方法
    public function onError($msgs)
    {
        return response()->json([
            'code' => 'error',
            'msgs' => $msgs
        ]);
    }



    protected function respondWithToken($token, $data)
    {
        return response()->json([
            'data' => $data,
            'access_token' =>'bearer '.$token,
            'token_type' => 'bearer'
        ]);
    }


    //获取登录用户的用户信息
    protected function getUserInfo()
    {
        return auth()->user();
    }

}

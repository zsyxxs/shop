<?php


namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Auth;

class RefreshToken extends BaseMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     * @throws JWTException
     */
    public function handle($request , Closure $next)
    {
        //检查此次请求中，是否携带token，如果没有则抛出异常
        $this->checkForToken($request);

        try{
            //检测用户登录状态，如果正常，则通过
            if($this->auth->parseToken()->authenticate()){
                return $next($request);
            }

            throw new UnauthorizedHttpException('jwt-auth',json_encode(['status' => 401,'msg' => '未登录']));

        }catch (TokenExpiredException $exception){

            try{
                //刷新用户token，并放到头部
                $token = $this->auth->refresh();

                //使用一次性登录，保证请求成功
                Auth::guard('api')->onceUsingId($this->auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['sub']);

            }catch (JWTException $exception){
                //如果走到这里，说明refresh也过期了，需要重新登录

                throw new UnauthorizedHttpException('jwt-auth',json_encode(['status' => 401 , 'msg' => '未登录']));
            }
        }

        //在响应头中返回新的token

        return $this->setAuthenticationHeader($next($request),$token);


    }


}

<?php

namespace App\Http\Controllers\Api;

use App\Model\Code;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * method: 新用户注册
     * author: hongwenyang
     * param: code 验证码 account 账号 password 密码
     */
    public function register(Request $request)
    {
        $data = $request->except(['s']);
        $status = User::register($data);
        if ($status == 200) {
            // 注册成功 TODO::这里还要添加一个随机分配以太坊账户的动作
            $return = returnMsg(200, "注册成功");
        }
        if ($status == 400) {
            // 验证码错误
            $return = returnMsg(401, "验证码有误");
        }
        if ($status == 401) {
            $return = returnMsg(401, "该账户已经注册");
        }

        return response()->json($return);
    }


    /**
     * method: 登录
     * author: hongwenyang
     * param: account 账户  password 密码
     */
    public function login(Request $request){
        $data               = $request->except(['s']);
        $data['password']   = sha1($data['password']);
        $userId             = User::where(['account'=>$data['account'],'password'=>$data['password']])->value('id');
        if($userId){
            if(isset($data['cid'])){
                User::where('id',$userId)->update(['cid'=>$data['cid']]);
            }
            return response()->json(returnDataSuccess($userId));
        }
        return response()->json(['code'=>403,'msg'=>'账号或密码错误','data'=>'']);
    }

    /**
     * method: 修改密码
     * author: hongwenyang
     * param:  accoount 账户  code 短信验证码  password 密码
     */
    public function changePassword(Request $request){
        $data   = $request->except(['s']);
        $status = User::changePassword($data);

        if($status == 200){
            $return = returnMsg(200,"修改成功");
        }
        if($status == 400){
            $return = returnMsg(403,"短信验证码有误");
        }else if($status == 403){
            $return = returnMsg(403,'账户不存在无法修改');
        }
        return response()->json($return);
    }
}

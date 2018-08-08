<?php

namespace App\Http\Controllers\Api;

use App\Model\AccountEth;
use App\Model\Code;
use App\Model\Feedback;
use App\Model\Get;
use App\Model\Notice;
use App\Model\User;
use App\Model\UserAccount;
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


    /**
     * method: 修改昵称，登录密码，支付密码
     * author: hongwenyang
     * param:  id:用户id  type:修改类型 change:修改的内容
     */
    public function updateInfo(Request $request){
        $data = $request->except(['s']);
        $status = User::changeInfo($data);
        if($status == 200){
            $return = returnMsg(200,"修改成功");
        }else{
            $return = returnMsg(403,"修改异常");
        }
        return response()->json($return);
    }

    /**
     * method: 消息列表
     * author: hongwenyang
     * param:  id 用户id
     */
    public function getNotice(Request $request){
        $return     = [];
        $data       = $request->input('id');
        $return     = Notice::where('user_id',$data)
            ->select('type','abbreviations','created_at','id','is_read')
            ->get();
        return response()->json(returnDataSuccess($return));

    }

    /**
     * method: 消息详情
     * author: hongwenyang
     * param:  id 消息id
     */
    public function noticeRead(Request $request){
        $data   = $request->input('id');
        $return = Notice::where('id',$data)->select('title','content','created_at')->first();
        Notice::where('id',$data)->update(['is_read'=>1]);
        return response()->json(returnDataSuccess($return));
    }

    /**
     * method: 意见反馈
     * author: hongwenyang
     * param:  id content
     */
    public function addFeedback(Request $request){
        $data               = $request->except(['s']);
        $data['user_id']    = $data['id'];
        Feedback::create($data);
        return response()->json(returnMsg(200,"提交成功"));
    }


    /**
     * method: 用户的接受eth列表
     * author: hongwenyang
     * param:
     */
    public function getEth(Request $request){
        $data = $request->input('id');
        $return = [];
        $return = Get::where('user_id',$data)->select('hash','created_at','value')->get();
        return response()->json(returnDataSuccess($return));
    }
}

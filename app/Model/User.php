<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected  $table = "user";
    protected  $fillable = [
        'account','password','pay','nickname','avatar','cid'
    ];

    public function setPasswordAttribute($value){
        $this->attributes['password'] = sha1($value);
    }

    /**
     * method: 注册
     * author: hongwenyang
     * param:
     */
    public static function register($data){
        // 判断短信验证码是否正确
        $codeRight = Code::where(['code'=>$data['code'],'account'=>$data['account'],'is_use'=>0])->value('id');
        // 判断账号是否已经注册
        $userHave = User::where('account',$data['account'])->value('id');
        if($userHave){
            // 已经注册
            return 401;
        }else{
            if($codeRight){
                // 开始进行注册 TODO::暂时先关闭
                Code::changeStatus($codeRight);
                // 添加新用户
                User::create($data);
                return 200;
            }
            // 返回400 表示短信验证码有问题
            return 400;
        }
    }


    public static function changePassword($data){
        $codeRight = Code::where(['code'=>$data['code'],'account'=>$data['account'],'is_use'=>0])->value('id');
        $userHave = User::where('account',$data['account'])->value('id');
        if($userHave){
            if($codeRight){
                Code::changeStatus($codeRight);
                User::where('account',$data['account'])->update(['password'=>sha1($data['password'])]);
                return 200;
            }
            // 验证码错误
            return 400;
        }
        // 账号不存在
        return 403;
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected  $table = "user";
    protected  $fillable = [
        'account','password','pay','nickname','avatar','cid'
    ];

    public function userAccount(){
        return $this->hasOne(UserAccount::class,'user_id','id');
    }

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

                $user_id = User::where('account',$data['account'])->value('id');
                // 分配以太坊账户
                AccountEth::userAddaccount($user_id);

                return 200;
            }
            // 返回400 表示短信验证码有问题
            return 400;
        }
    }

    /**
     * method:
     * author: hongwenyang
     * param:
     */
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

    /**
     * method: 修改昵称，登录密码，支付密码
     * author: hongwenyang
     * param:  id:用户id  type:修改类型 change:修改的内容
     */
    public static function changeInfo($data){
        if($data['type'] == 1){
            // 修改昵称
            $fillable = ['nickname'=>$data['change']];
        }else if($data['type'] == 2){
            // 修改登录密码
            $fillable = ['password'=>sha1($data['change'])];
        }else if($data['type'] == 3){
            // 修改支付密码
            $fillable = ['pay'=>sha1($data['change'])];
        }else{
            // 异常请求
            return 403;
        }
        $status = User::where('id',$data['id'])->update($fillable);
        if($status){
            return 200;
        }
        return 403;
    }
}

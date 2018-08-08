<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AccountEth extends Model
{
    protected  $table = "account_eth";
    /**
     * method: 新注册用户分配以太坊账户
     * author: hongwenyang
     * param:  user_id
     */
    public static function userAddaccount($data){
        // 先判断是否有还未分配的账号
        $account = AccountEth::where('is_use',0)->value('id');
        if($account){
            // 开始分配账号
            $create['user_id']      = $data;
            $create['account_id']   = $account;

            $status = UserAccount::create($create);

            if($status){
                AccountEth::where('id',$account)->update(['is_use'=>1]);
                return 200;
            }
            // 分配异常
            return 401;
        }
        // 账号已经分配完了
        return 400;
    }
}

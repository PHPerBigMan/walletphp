<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Code
 * @package App\Model
 * 短信验证码
 */
class Code extends Model
{
    protected  $table = "code";
    protected  $fillable= [
        'code','account','is_use'
    ];

    /**
     * method: 将短信验证码修改为已使用
     * author: hongwenyang
     * param:  id 数据id
     */
    public static  function changeStatus($id){
        if(CODECHAGEOPEN){
            Code::where('id',$id)->update(['is_use'=>1]);
        }
    }

    /**
     * method: 增加验证码
     * author: hongwenyang
     * param:
     */
    public static function addCode($account,$code){
        $create['account'] = $account;
        $create['code']    = $code;
        Code::create($create);
    }
}

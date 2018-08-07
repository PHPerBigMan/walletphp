<?php
/**
 * Created by HongWenYang
 * User: hwy
 * Date: 2018/8/7
 * Time: 14:49
 * 说明:
 */

function returnMsg($code,$msg){
    $returnCode = 200;
     if($code != 200){
         $returnCode = 403;
     }
     return [
         'code' =>$returnCode,
         'msg'  =>$msg
     ];
}

function returnDataSuccess($data){
    return [
        'code'=>200,
        'msg' =>'获取成功',
        'data'=>$data
    ];
}


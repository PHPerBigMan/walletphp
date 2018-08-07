<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AliSmsController;
use App\Model\Code;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CodeController extends Controller
{
    /**
     * method: 短信发送
     * author: hongwenyang
     * param:
     */
    public function message(Request $request){
        $account = $request->input('account');
        $code    = rand(100000,999999);
        $status = AliSmsController::sms($account,"澜悦帝景湾","SMS_140070106",$code);
        if($status == 200){
            Code::addCode($account,$code);
            $return = returnMsg(200,"短信发送成功");
        }else{
            $return  = returnMsg(403,"短信发送失败");
        }
        return response()->json($return);
    }
}

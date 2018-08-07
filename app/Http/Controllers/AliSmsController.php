<?php

namespace App\Http\Controllers;

use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;
use Illuminate\Http\Request;

/**
 * Class AliSmsController
 * @package App\Http\Controllers
 * 封装短信发送方法
 */
class AliSmsController extends Controller
{
    public static function sms($phone,$signName,$TemCode,$num){
        $config = config('alisms');
        $client = new Client($config);
        $sendSms = new SendSms();
        $sendSms->setPhoneNumbers($phone);
        $sendSms->setSignName("$signName");
        $sendSms->setTemplateCode("$TemCode");
        $sendSms->setTemplateParam(['code' => $num]);
        $result = $client->execute($sendSms);
        if($result->Code == "OK"){
            return 200;
        }
        return 403;
    }
}

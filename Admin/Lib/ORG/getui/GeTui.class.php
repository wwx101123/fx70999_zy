<?php
require_once (dirname(__FILE__) . '/' . 'IGt.Push.php');
require_once (dirname(__FILE__) . '/' . 'igetui/IGt.AppMessage.php');
require_once (dirname(__FILE__) . '/' . 'igetui/IGt.APNPayload.php');
require_once (dirname(__FILE__) . '/' . 'igetui/template/IGt.BaseTemplate.php');
require_once (dirname(__FILE__) . '/' . 'IGt.Batch.php');
require_once (dirname(__FILE__) . '/' . 'igetui/utils/AppConditions.php');

class GeTui
{

    public function pushToAndroidApp($title, $content, $message)
    {
        $APPKEY = C('GETUI_APP_KEY');
        $APPID = C('GETUI_APPID');
        $MASTERSECRET = C('GETUI_MASTERSECRET');
        $HOST = 'http://sdk.open.api.igexin.com/apiex.htm';
        $igt = new \IGeTui($HOST, $APPKEY, $MASTERSECRET);
        // $igt = new IGeTui('',APPKEY,MASTERSECRET);//此方式可通过获取服务端地址列表判断最快域名后进行消息推送，每10分钟检查一次最快域名
        // 消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        
        // $template = IGtNotyPopLoadTemplateDemo();
        // $template = IGtLinkTemplateDemo();
        // $template = IGtNotificationTemplateDemo();
        // $template = IGtTransmissionTemplateDemo();
        
        $template = new \IGtTransmissionTemplate();
        $template->set_appId($APPID); // 应用appid
        $template->set_appkey($APPKEY); // 应用appkey
        $template->set_transmissionType(2); // 透传消息类型
        $template->set_transmissionContent(json_encode($message)); // 透传内容
                                                                   
        // 个推信息体
                                                                   // 基于应用消息体
        $message = new \IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime(3600 * 12 * 1000); // 离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);
        $message->set_PushNetWorkType(0); // 设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
        $message->set_speed(1000); // 设置群推接口的推送速度，单位为条/秒，例如填写100，则为100条/秒。仅对指定应用群推接口有效。
        $message->set_appIdList(array(
            $APPID
        ));
        $message->set_phoneTypeList(array(
            'ANDROID'
        ));
        // $message->set_provinceList(array('浙江','上海','北京'));
        // $message->set_tagList(array('开心'));
        $res = $igt->pushMessageToApp($message);
        return $res;
    }
    
    // 单推接口案例
    public function pushMessageToSingle1($CID, $message)
    {
        $APPKEY = C('GETUI_APP_KEY');
        $APPID = C('GETUI_APPID');
        $MASTERSECRET = C('GETUI_MASTERSECRET');
        
        $HOST = 'http://sdk.open.api.igexin.com/apiex.htm';
        $igt = new \IGeTui($HOST, $APPKEY, $MASTERSECRET);
        
        // 消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        
        // $template = IGtNotyPopLoadTemplateDemo();
        // $template = IGtLinkTemplateDemo();
        // $template = IGtNotificationTemplateDemo();
        $template = new \IGtTransmissionTemplate();
        $template->set_appId($APPID); // 应用appid
        $template->set_appkey($APPKEY); // 应用appkey
        $template->set_transmissionType(1); // 透传消息类型
        $template->set_transmissionContent($message); // 透传内容
                                                      // 个推信息体
        $message = new \IGtSingleMessage();
        
        $message->set_isOffline(true); // 是否离线
        $message->set_offlineExpireTime(3600 * 12 * 1000); // 离线时间
        $message->set_data($template); // 设置推送消息类型
                                       // $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
                                       // 接收方
        $target = new \IGtTarget();
        $target->set_appId($APPID);
        $target->set_clientId($CID);
        // $target->set_alias(Alias);
        
        try {
            file_put_contents('dddddd.txt', $message . $target, FILE_APPEND);
            $rep = $igt->pushMessageToSingle($message, $target);
            return $rep;
        } catch (RequestException $e) {
            $requstId = $e->getRequestId();
            file_put_contents('errot.txt', $message);
            $rep = $igt->pushMessageToSingle($message, $target, $requstId);
            return $rep;
        }
    }
    
    // 群推接口案例
    function pushMessageToApp($content)
    {
        $APPKEY = C('GETUI_APP_KEY');
        $APPID = C('GETUI_APPID');
        $MASTERSECRET = C('GETUI_MASTERSECRET');
        
        $HOST = 'http://sdk.open.api.igexin.com/apiex.htm';
        
        $igt = new IGeTui($HOST, $APPKEY, $MASTERSECRET);
        $template = $this->IGtTransmissionTemplateDemo($content);
        // $template = IGtLinkTemplateDemo();
        // 个推信息体
        // 基于应用消息体
        $message = new IGtAppMessage();
        $message->set_isOffline(false);
        $message->set_offlineExpireTime(10 * 60 * 1000); // 离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);
        
        $appIdList = array(
            $APPID
        );
        
        // 用户属性
        // $age = array("0000", "0010");
        
        // $cdt = new AppConditions();
        // $cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);
        // $cdt->addCondition(AppConditions::REGION, $provinceList);
        // $cdt->addCondition(AppConditions::TAG, $tagList);
        // $cdt->addCondition("age", $age);
        
        $message->set_appIdList($appIdList);
        // $message->set_conditions($cdt->getCondition());
        
        $rep = $igt->pushMessageToApp($message, "任务组名");
        
//         var_dump($rep);
//         echo ("<br><br>");
    }

    function IGtTransmissionTemplateDemo($content)
    { 
        $APPKEY = C('GETUI_APP_KEY');
        $APPID = C('GETUI_APPID');
        $MASTERSECRET = C('GETUI_MASTERSECRET');
        $template = new IGtTransmissionTemplate();
        $template->set_appId($APPID); // 应用appid
        $template->set_appkey($APPKEY); // 应用appkey
        $template->set_transmissionType(2); // 透传消息类型
        $template->set_transmissionContent($content); // 透传内容
                                                       // $template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
                                                       // APN简单推送
                                                       // $template = new IGtAPNTemplate();
                                                       // $apn = new IGtAPNPayload();
                                                       // $alertmsg=new SimpleAlertMsg();
                                                       // $alertmsg->alertMsg="";
                                                       // $apn->alertMsg=$alertmsg;
                                                       // // $apn->badge=2;
                                                       // // $apn->sound="";
                                                       // $apn->add_customMsg("payload","payload");
                                                       // $apn->contentAvailable=1;
                                                       // $apn->category="ACTIONABLE";
                                                       // $template->set_apnInfo($apn);
                                                       // $message = new IGtSingleMessage();
                                                       
        // APN高级推送
        $apn = new IGtAPNPayload();
        $alertmsg = new DictionaryAlertMsg();
        $alertmsg->body =$content;
        $alertmsg->actionLocKey = "ActionLockey";
        $alertmsg->locKey = "LocKey";
        $alertmsg->locArgs = array(
            "locargs"
        );
        $alertmsg->launchImage = "launchimage";
        // IOS8.2 支持
        $alertmsg->title = "Title";
        $alertmsg->titleLocKey = "TitleLocKey";
        $alertmsg->titleLocArgs = array(
            "TitleLocArg"
        );
        
        $apn->alertMsg = $alertmsg;
        $apn->badge = 7;
        $apn->sound = "";
        $apn->add_customMsg("payload", "payload");
        $apn->contentAvailable = 1;
        $apn->category = "ACTIONABLE";
          $template->set_apnInfo($apn);
        
        // PushApn老方式传参
        // $template = new IGtAPNTemplate();
        // $template->set_pushInfo("", 10, "", "com.gexin.ios.silence", "", "", "", "");
        
        return $template;
    }
}
    
    
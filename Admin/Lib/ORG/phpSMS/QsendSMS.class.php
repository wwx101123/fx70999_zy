<?php

class sendSMS
{

    /**
     * 架构函数
     *
     * @access public
     * @param array $config
     *            上传参数
     */
    public function __construct($config = array())
    {
        if (is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }
    }

    /*
     * 乱码问题解决方案，1、GBK编码提交的首先urlencode短信内容（content），然后在API请求时，带入encode=gbk
     *
     * 2、UTF-8编码的将content 做urlencode编码后，带入encode=utf8或utf-8
     * 实例：http://m.5c.com.cn/api/send/index.php?username=XXX&password_md5=XXX&apikey=XXX&mobile=XXX&content=%E4%BD%A0%E5%A5%BD%E6%89%8D%E6%94%B6%E7%9B%8A%E9%9F%A6&encode=utf8
     *
     * 关于内容转码问题。 UTF-8 转 GBK：$content = iconv("UTF-8","GBK//IGNORE",$content);GBK 转 UTF-8：$content = iconv("GBK","UTF-8",$content);
     *
     * username 用户名
     * password_md5 密码
     * mobile 手机号
     * apikey apikey秘钥
     * content 短信内容
     * startTime UNIX时间戳，不写为立刻发送，http://tool.chinaz.com/Tools/unixtime.aspx （UNIX时间戳网站）
     *
     * success:msgid 提交成功。
     * error:msgid 提交失败
     * error:Missing username 用户名为空
     * error:Missing password 密码为空
     * error:Missing apikey APIKEY为空
     * error:Missing recipient 手机号码为空
     * error:Missing message content 短信内容为空
     * error:Account is blocked 帐号被禁用
     * error:Unrecognized encoding 编码未能识别
     * error:APIKEY or password error APIKEY或密码错误
     * error:Unauthorized IP address 未授权 IP 地址
     * error:Account balance is insufficient 余额不足
     */
    public $encode = 'UTF-8';

    // 页面编码和短信内容编码为GBK。重要说明：如提交短信后收到乱码，请将GBK改为UTF-8测试。如本程序页面为编码格式为：ASCII/GB2312/GBK则该处为GBK。如本页面编码为UTF-8或需要支持繁体，阿拉伯文等Unicode，请将此处写为：UTF-8
    public $username = 'yinxinlian';

    // 用户名
    public $password_md5 = 'asdf12345';
    
    // 32位MD5密码加密，不区分大小写
    public $appid = '1400156804'; 
    public $apikey = '19c97a66e55da1f57b2efe5a10ea9d14';

    // apikey秘钥（请登录 http://m.5c.com.cn 短信平台-->账号管理-->我的信息 中复制apikey）
    public $mobile = '15052939438';

    // 手机号,只发一个号码：13800000001。发多个号码：13800000001,13800000002,...N 。使用半角逗号分隔。
    public $content = '您好，您的验证码是：12345【美联】';

    // 要发送的短信内容，特别注意：签名必须设置，网页验证码应用需要加添加【图形识别码】。
    
    // 发送接口
    public function sendSMS($username, $password_md5, $appid, $apikey, $mobile, $contentUrlEncode, $encode)
    {
        $random = $this->getRandom();
        $NOW= time(); 
        // 发送链接（用户名，密码，apikey，手机号，内容）
        $url = "https://yun.tim.qq.com/v5/tlssmssvr/sendisms?sdkappid=" .$appid . "&random=" . $random; // 如连接超时，可能是您服务器不支持域名解析，请将下面连接中的：【m.5c.com.cn】修改为IP：【115.28.23.78】
        $tel =ARRAY();
        $tel['mobile'] =$mobile.'';
        $tel['nationcode'] = '86'.'';
        
        $data =ARRAY();
        $data['ext'] = '';
        $data['extend'] ='';
        $data['msg'] = $contentUrlEncode;
        $data['sig'] = $this->calculateSigForTempl($apikey, $random, $NOW, $mobile);
        $data['tel'] = $tel;
        $data['time'] = $NOW;
        $data['type'] = 0; 
        $result = $this->sendCurlPost($url, $data);
        
        // print_r($data); //测试
        return $result;
    }

    public function curlSMS($url, $post_fields = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // 用PHP取回的URL地址（值将被作为字符串）
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 使用curl_setopt获取页面内容或提交数据，有时候希望返回的内容作为变量存储，而不是直接输出，这时候希望返回的内容作为变量
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30秒超时限制
        curl_setopt($ch, CURLOPT_HEADER, 1); // 将文件头输出直接可见。
        curl_setopt($ch, CURLOPT_POST, 1); // 设置这个选项为一个零非值，这个post是普通的application/x-www-from-urlencoded类型，多数被HTTP表调用。
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); // post操作的所有数据的字符串。
        $data = curl_exec($ch); // 抓取URL并把他传递给浏览器
        curl_close($ch); // 释放资源
        $res = explode("\r\n\r\n", $data); // explode把他打散成为数组
        return $res[2]; // 然后在这里返回数组。
    }
    
    
    
    //腾讯云短信
    public function getRandom() {
        return rand(100000, 999999);
    }
    public function calculateSig($appkey, $random, $curTime, $phoneNumbers) {
        $phoneNumbersString = $phoneNumbers[0];
        for ($i = 1; $i < count($phoneNumbers); $i++) {
            $phoneNumbersString .= ("," . $phoneNumbers[$i]);
        }
        return hash("sha256", "appkey=".$appkey."&random=".$random
            ."&time=".$curTime."&mobile=".$phoneNumbersString);
    }
    
    public    function calculateSigForTemplAndPhoneNumbers($appkey, $random, $curTime, $phoneNumbers) {
        $phoneNumbersString = $phoneNumbers[0];
        for ($i = 1; $i < count($phoneNumbers); $i++) {
            $phoneNumbersString .= ("," . $phoneNumbers[$i]);
        }
        return hash("sha256", "appkey=".$appkey."&random=".$random
            ."&time=".$curTime."&mobile=".$phoneNumbersString);
    }
    
    public  function phoneNumbersToArray($nationCode, $phoneNumbers) {
        $i = 0;
        $tel = array();
        do {
            $telElement = new \stdClass();
            $telElement->nationcode = $nationCode;
            $telElement->mobile = $phoneNumbers[$i];
            array_push($tel, $telElement);
        } while (++$i < count($phoneNumbers));
        return $tel;
    }
    
    public function calculateSigForTempl($appkey, $random, $curTime, $phoneNumber) {
        $phoneNumbers = array($phoneNumber);
        return $this->calculateSigForTemplAndPhoneNumbers($appkey, $random, $curTime, $phoneNumbers);
    }
    
    function sendCurlPost($url, $dataObj) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dataObj));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec($curl);
        if (false == $ret) {
            // curl_exec failed
            $result = "{ \"result\":" . -2 . ",\"errmsg\":\"" . curl_error($curl) . "\"}";
        } else {
            $rsp = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "{ \"result\":" . -1 . ",\"errmsg\":\"". $rsp . " " . curl_error($curl) ."\"}";
            } else {
                $result = $ret;
            }
        }
        curl_close($curl);
        return $result;
    }
    function sendWithParam($nationCode, $phoneNumber, $templId = 0, $params, $sign = "", $extend = "", $ext = "") {
        $url = "https://yun.tim.qq.com/v5/tlssmssvr/sendsms";
        
        $random =$this-> getRandom();
        $curTime = time();
        $wholeUrl = $url . "?sdkappid=" . $this->appid . "&random=" . $random;
        
        // 按照协议组织 post 包体
      
        $tel = new \stdClass();
        $tel->nationcode = "".$nationCode;
        $tel->mobile = "".$phoneNumber;
        $data = new \stdClass();
        $data->tel = $tel;
        $data->sig = $this->calculateSigForTempl($this->apikey, $random, $curTime, $phoneNumber);
        $data->tpl_id = $templId;
        $data->params = $params;
        $data->sign = $sign;
        $data->time = $curTime;
        $data->extend = $extend;
        $data->ext = $ext;
        return $this->sendCurlPost($wholeUrl, $data);
    }
}
?>
<?php
// 本类供测试用途
class WeixinAction extends AuthAction
{

    public function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        // initialize
        parent::_initialize();
    }

    public function index()
    {
        $current_url = $this->get_url();
        $this->wechatWebAuth();
    }

    public function notify()
    {
        if ($_REQUEST["code"] == '' || $_REQUEST["code"] == null) {
            $res = $this->wechatWebAuth();
        }
        $res = $this->wxGetTokenWithCode($_REQUEST["code"]);
        session('openid', $res["openid"]);
        $this->assign('openid', $res["openid"]);
        $this->assign('access_token', $res["access_token"]);
        S("wx_access_token", $res["access_token"]);
        $res = $this->get_user_info();
        
        $this->assign('user_info', json_encode($res));
        $fck = M('fck');
        $where = array();
        $where['unionid'] = $res['unionid'];
        
        $count = $fck->where($where)->count();
        $this->assign('count', $count);
        if ($count > 0) {
            $user = $fck->where($where)->order('ID DESC')->find();
            
            
            $fck->where('id='.$user['id'])->setField('gzh_openid', $res['openid']);
        }
        
        $fck->where("id=1")->setField('gzh_unionid', $res['unionid']);
        
        $this->assign('res', $res);
        // $this->assign('wx_appID', C("wx_appID"));
        // $this->assign('wx_appsecret', C("wx_appsecret"));
        
        $this->display();
    }

    public function wxInfo()
    {
        $user_id= I('post.user_id');
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".C("wx_appID")."&secret=".C("wx_appsecret")."";
        $access_msg = json_decode(file_get_contents($access_token));
        $token = $access_msg->access_token;
       
        $form = M('fck');
        $model = $form->where('id=' . $user_id)->find();
        $subscribe_msg = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$model['gzh_openid'];
        $subscribe = json_decode(file_get_contents($subscribe_msg));
       
        $gzxx = $subscribe->subscribe;
      
        if ($gzxx === 1) {
            $this->error('已关注公众号！');
            exit();
        } else {
           $this->error('对不起，先关注公众号！'.$model['gzh_openid']);
            exit();
        }
    }
}
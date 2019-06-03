<?php

class PublicAction extends CommonAction
{

    public function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        header("Content-Type:text/html; charset=utf-8");
        // $this->_inject_check(1); // 调用过滤函数
        $this->_Config_name(); // 调用参数
    }

    // 过滤查询字段
    function _filter(&$map)
    {
        $map['title'] = array(
            'like',
            "%" . $_POST['name'] . "%"
        );
    }

    // 顶部页面
    public function top()
    {
        C('SHOW_RUN_TIME', false); // 运行时间显示
        C('SHOW_PAGE_TRACE', false);
        $this->display();
    }

    // 尾部页面
    public function header()
    {
        $id = $_SESSION[C('USER_AUTH_KEY')]; // 登录AutoId
        $fck = M('fck');
        $fck_rs = $fck->find($id);
        
        $roleModel = M('manager_role')->where('id=' . $fck_rs['role_id'])->find(); // 获得管理角色信息
        if ($roleModel!= null) {
            $fck_rs['role_name']=$roleModel['role_name'];
        }
        
        
        
        
        
        
        
        
        
        
        
        $this->assign('fck_rs', $fck_rs);
        
        $arss = $this->_cheakPrem();
        $this->assign('arss', $arss);
        
        $this->display();
    }

    // 尾部页面
    public function footer()
    {
        C('SHOW_RUN_TIME', false); // 运行时间显示
        C('SHOW_PAGE_TRACE', false);
        $this->display();
    }

    // 菜单页面
    public function menu()
    {
        $this->_checkUser();
        $map = array();
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $field = '*';
        
        $map = array();
        $map['s_uid'] = $id; // 会员ID
        $map['s_read'] = 0; // 0 为未读
        $info_count = M('msg')->where($map)->count(); // 总记录数
        $this->assign('info_count', $info_count);
        
        $fck = M('fck');
        $fwhere = array();
        $fwhere['ID'] = $_SESSION[C('USER_AUTH_KEY')];
        $frs = $fck->where($fwhere)
            ->field('*')
            ->find();
        // dump($frs);
        $HYJJ = '';
        $this->_levelConfirm($HYJJ, 1);
        $this->assign('voo', $HYJJ);
        
        $this->assign('fck_rs', $frs);
        $this->display('menu');
    }

    // 后台首页 查看系统信息
    public function main()
    {
        $this->_checkUser();
        $ppfg = $_POST['ppfg'];
        $id = $_SESSION[C('USER_AUTH_KEY')]; // 登录AutoId
        $fck = M('fck');
        $cash = M('cash');
        // =================================================
        
        $form = M('form');
        $map = array();
        $map['status'] = array(
            'eq',
            1
        );
        $field = '*';
        $newslist = $form->where($map)
            ->field($field)
            ->order('baile desc,id desc')
            ->limit(10)
            ->select();
        $this->assign('newslist', $newslist); // 数据输出到模板
        
        $map = array();
        $map['s_uid'] = $id; // 会员ID
        $map['s_read'] = 0; // 0 为未读
        $info_count = M('msg')->where($map)->count(); // 总记录数
        $this->assign('info_count', $info_count);
        
        // 会员级别
        $urs = $fck->where('id=' . $id)
            ->field('*')
            ->find();
        
         
            
            
        $this->assign('fck_rs', $urs); // 总奖金
        
        $all_nn = $fck->where('re_path like "%,' . $id . ',%" and is_pay=1')->count();
        $this->assign('all_nn', $all_nn);
        
        $nowdate = strtotime(date('Y-m-d'));
        $all_nmoney = $fck->where('re_path like "%,' . $id . ',%" and is_pay=1 and pdt>' . $nowdate)->sum('cpzj');
        if (empty($all_nmoney)) {
            $all_nmoney = 0.00;
        }
        $this->assign('all_nmoney', $all_nmoney);
        
        $fee = M('fee');
        $fee_rs = $fee->field('s3,s12,str1,str7,str9,str12,str21,str22,str23,gp_one')->find();
        $str21 = $fee_rs['str21'];
        $str22 = $fee_rs['str22'];
        $str23 = $fee_rs['str23'];
        $str12 = $fee_rs['str12'];
        $all_img = $str21 . "|" . $str22 . "|" . $str23;
        $this->assign('all_img', $all_img);
        $s3 = explode("|", $fee_rs['s3']);
        $s12 = $fee_rs['s12'];
        $str1 = $fee_rs['str1'];
        $str5 = explode("|", $fee_rs['str7']);
        $str9 = $fee_rs['str9'];
        $one_price = $fee_rs['gp_one'];
        $this->assign('s3', $s3);
        $this->assign('s12', $s12);
        $this->assign('str1', $str1);
        $this->assign('str9', $str9);
        
        // 股票(股)价格
        $this->assign('one_price', $one_price);
        $gupiaojz = $one_price * $urs['live_gupiao'];
        $this->assign('gupiaojz', $gupiaojz);
        
        $maxqq = 4;
        if (count($str5) > $maxqq) {
            $lenn = $maxqq;
        } else {
            $lenn = count($str5);
        }
        for ($i = 0; $i < $lenn; $i ++) {
            $qqlist[$i] = $str5[$i];
        }
        $this->assign('qlist', $qqlist);
        
        // 计算预计配送时间
        $peragcash = $fck->where('is_pay>0 and pdt<=' . $urs['pdt'])->sum('agent_gp'); // 排在自己之前的收藏品数量
        if (empty($peragcash)) {
            $peragcash = 0;
        }
        $pernum = (int) $peragcash / 3;
        $daynum = 0;
        if ($str12 > 0) {
            $daynum = floor($pernum / $str12);
        }
        // 预计时间
        $psdatetime = $urs['pdt'] + $daynum * 24 * 3600;
        $this->assign('psdatetime', $psdatetime);
        
        $HYJJ = "";
        $this->_levelConfirm($HYJJ, 1);
        $this->assign('voo', $HYJJ); // 会员级别
        
        $see = $_SERVER['HTTP_HOST'] . __APP__;
        $see = str_replace("//", "/", $see);
        $this->assign('server', $see);
        $this->display();
    }

    // 用户登录页面
    public function login()
    {
        $fee = M('fee');
        $fee_rs = $fee->field('str24,str27,i3')->find();
        // echo "<script>layui.use('layer',function(){layer.msg('".$fee_rs['str27']."')});</script>";
        if ($fee_rs['i3'] == '0') {
            $this->assign('str27', $fee_rs['str27']);
        }
        $this->assign('wentilist', explode('|', $fee_rs['str24']));
        // var_dump($fee_rs);
        unset($fee, $fee_rs);
        $this->display('login');
    }

    public function index()
    {
        // 如果通过认证跳转到首页
        redirect(__APP__);
    }

    // 用户登出
    public function LogOut()
    {
        $_SESSION = array();
        // unset($_SESSION);
        $this->assign('jumpUrl', __URL__ . '/login/');
        $this->success('退出成功！');
    }

    /**
     * 登陆控制器
     *
     * @return [type] [description]
     */
    public function checkLogin()
    {
        $is_mobile = $_POST['is_mobile'];
        $r_smstxt = $_POST['r_smstxt'];
        // var_dump($this->_post());
        // return json_encode($this->_post());die();
        if ($this->_post('username') == "") {
            if ($is_mobile == 1) {
                $this->ajaxError('请输入帐号！');
                exit();
            } else {
                $this->error('请输入帐号！');
            }
        } elseif ($this->_post('password') == "") {
            if ($is_mobile == 1) {
                $this->ajaxError('请输入密码！');
                exit();
            } else {
                $this->error('请输入密码！');
            }
        } elseif ($this->_post('verify') == "" && $is_mobile == 0) {
            $this->error('请输入验证码！');
        } elseif ($_SESSION['verify'] != md5($_POST['verify']) && $is_mobile == 0) {
            $this->error('验证码错误！');
        } elseif ($this->_post('r_smstxt') == "" && $is_mobile == 1) {
            // $this->error('请输入手机验证码！');
        }
        
        $user_code = M('user_code');
        $sms_template = M('sms_template')->where(array(
            'id' => array(
                'eq',
                '1'
            )
        ))->find();
        
        $username = $this->_post('username');
        $usercode = $user_code->where("user_name='" . $username . "' AND type='" . $sms_template['call_index'] . "' and status=1 ")
            ->order('id desc')
            ->find();
        
        if ($is_mobile == 1) {
            // if ($usercode != null) {
            // $now = time();
            // if ($usercode['eff_time'] < $now && $is_mobile == 1) {
            // $this->error('验证码已过期,请重新获取！');
            // exit();
            // }
            
            // if ($usercode['str_code'] != $_POST['r_smstxt'] && $is_mobile == 1) {
            // $this->error('手机验证码错误！');
            // exit();
            // }
            // $user_code->where("user_name='" . $username . "' ")->setField('status', 0);
            // } else {
            
            // $this->error('请获取手机验证码！');
            // }
        }
        
        $map = array();
        $map['user_id'] = $this->_post('username');
        import('@.ORG.RBAC');
        $fck = M('fck');
        $field = '*';
        $authInfo = $fck->where($map)
            ->field($field)
            ->find();
        // 使用用户名、密码和状态的方式进行认证
        if (false == $authInfo) {
            $this->ajaxError('帐号不存在！');
        } else {
            if ($authInfo['password'] != md5($this->_post('password'))) {
                if ($is_mobile == 1) {
                    $this->ajaxError('密码错误！');
                    exit();
                } else {
                    $this->error('密码错误！');
                    exit();
                }
            }
            if ($authInfo['is_pay'] == 0) {
                if ($is_mobile == 1) {
//                     $this->ajaxError('用户尚未激活，暂时不能登录系统！');
//                     exit();
                } else {
                    $this->error('用户尚未激活，暂时不能登录系统！');
                    exit();
                }
            }
            if ($authInfo['is_lock'] != 0) {
                if ($is_mobile == 1) {
                    $this->ajaxError('用户已锁定，请与管理员联系！');
                    exit();
                } else {
                    $this->error('用户已锁定，请与管理员联系！');
                    exit();
                }
            }
            if ($authInfo['is_boss'] == 0 && $is_mobile != 1) {
//                 if ($is_mobile == 1) {
//                     $this->ajaxError('管理平台入口，请与管理员联系！');
//                     exit();
//                 } else {
//                     $this->error('管理平台入口，请与管理员联系！');
//                     exit();
//                 }
            }
            // 第一次登陆系统修改密码
            
            if ($authInfo['is_boss'] != '1') {
                $fee = M('fee');
                $fee_rs = $fee->field('str27,i3')->find();
                if ($fee_rs['i3'] == '0') {
                    $this->ajaxSuccess($fee_rs['str27']);
                    exit();
                }
            }
            $_SESSION[C('USER_AUTH_KEY')] = $authInfo['id'];
            if ($authInfo['password'] == md5('789789')) {
                $this->ajaxSuccess('第一次登陆系统请修改密码！', U('Public/changepwd'));
            }
            $_SESSION['loginUseracc'] = $authInfo['user_id']; // 用户名
            $_SESSION['loginNickName'] = $authInfo['nickname']; // 会员名
            $_SESSION['loginUserName'] = $authInfo['user_name']; // 开户名
            $_SESSION['lastLoginTime'] = date('Y-m-d H:i:s', $authInfo['last_login_time']);
            $_SESSION['login_count'] = $authInfo['login_count'] + 1;
            $_SESSION['login_isAgent'] = $authInfo['is_agent']; // 是否服务中心
            $_SESSION['UserMktimes'] = mktime();
            $_SESSION['number'] = 'No.8000';
            
            // 身份确认 = 用户名+识别字符+密码
            $_SESSION['login_sf_list_u'] = md5($authInfo['user_id'] . 'wodetp_new_1012!@#' . $authInfo['password'] . $_SERVER['HTTP_USER_AGENT']);
            
            // 登录状态
            $user_type = md5($_SERVER['HTTP_USER_AGENT'] . 'wtp' . rand(0, 999999));
            $_SESSION['login_user_type'] = $user_type;
            $data['id'] = $authInfo['id'];
            // $fck->where($where)->setField('last_login_time',mktime());
            // 管理员
            $data['user_type'] = $user_type;
            $data['login_count'] = $authInfo['login_count'] + 1;
            $data['last_login_time'] = $authInfo['new_login_time'];
            $data['new_login_time'] = time();
            $data['new_login_ip'] = $_SERVER['REMOTE_ADDR'];
            $fck->where($where)->save($data);
            $parmd = $this->_cheakPrem();
            if ($authInfo['id'] == 1 || $parmd[11] == 1) {
                $_SESSION['administrator'] = 1;
            } else {
                $_SESSION['administrator'] = 2;
            }
            
            // //管理员
            if ($authInfo['is_boss'] == 2) {
                $_SESSION['administrator'] = 1;
            }
            // 缓存访问权限
            
            RBAC::saveAccessList();
            if ($is_mobile == 0) {
                $this->success('登录成功！', U('Index/index'));
            } else {
                
                $authInfo = get_user_info($authInfo, $authInfo['id']);
                
                $fee = M('fee');
                $fee_rs = $fee->field('recommend_award')->find(1);
                $money = $fee_rs['recommend_award'];
                
                login_recommend_award($authInfo, $money);
                $os = $_POST['app_os'];
                if (! empty($os)) {
                    M('fck')->where('id=' . $authInfo['id'])->setField('os', $os);
                }
//                 $authInfo['nickname']=  $authInfo['user_id'];
//                 $authInfo['mobile']=  $authInfo['user_id'];

                
                $bi = $this->_Config_name();
                $authInfo['bi'] = $bi;
                
                
                $authInfo['re_share_sub_title'] = C('re_share_sub_title');
                $authInfo['re_share_title'] = C('re_share_title');
                
                $authInfo['portrait']= 'http://'.   $_SERVER['SERVER_NAME'].'/'.    $authInfo['avatar'];
                $data = array();
                $data['info'] = '登录成功';
                $data['status'] = 1;
                $data['url'] = 'user.html';
                $data['is_login'] = 1;
                $data['data'] = $authInfo;
                $this->ajaxReturn($data);
            }
        }
    }

    // 二级密码验证
    public function cody()
    {
        $UrlID = (int) $_GET['c_id'];
        if (empty($UrlID)) {
            $this->error('二级密码错误!');
            exit();
        }
        if (! empty($_SESSION['user_pwd2'])) {
            $url = __URL__ . "/codys/Urlsz/$UrlID";
            $this->_boxx($url);
            exit();
        }
        $fck = M('cody');
        $list = $fck->where("c_id=$UrlID")->getField('c_id');
        if (! empty($list)) {
            $this->assign('vo', $list);
            $this->display('cody');
            exit();
        } else {
            $this->error('二级密码错误!');
            exit();
        }
    }

    // 二级验证后调转页面
    public function codys()
    {
        $Urlsz = $_POST['Urlsz'];
        if (empty($_SESSION['user_pwd2'])) {
            $pass = $_POST['oldpassword'];
            $fck = M('fck');
            if (! $fck->autoCheckToken($_POST)) {
                $this->error('页面过期请刷新页面!');
                exit();
            }
            if (empty($pass)) {
                $this->error('二级密码错误!');
                exit();
            }
            
            $where = array();
            $where['id'] = $_SESSION[C('USER_AUTH_KEY')];
            $where['passopen'] = md5($pass);
            $list = $fck->where($where)
                ->field('id')
                ->find();
            if ($list == false) {
                $this->error('二级密码错误!');
                exit();
            }
            $_SESSION['user_pwd2'] = 1;
        } else {
            $Urlsz = $_GET['Urlsz'];
        }
        switch ($Urlsz) {
            case 1:
                $_SESSION['DLTZURL02'] = 'updateUserInfo';
                $bUrl = __URL__ . '/updateUserInfo'; // 修改资料
                $this->_boxx($bUrl);
                break;
            case 2:
                $_SESSION['DLTZURL01'] = 'password';
                $bUrl = __URL__ . '/password'; // 修改密码
                $this->_boxx($bUrl);
                break;
            case 3:
                $_SESSION['DLTZURL01'] = 'pprofile';
                $bUrl = __URL__ . '/pprofile'; // 修改密码
                $this->_boxx($bUrl);
                break;
            case 4:
                $_SESSION['DLTZURL01'] = 'OURNEWS';
                $bUrl = __URL__ . '/News'; // 修改密码
                $this->_boxx($bUrl);
                break;
            default:
                $this->error('二级密码错误!');
                break;
        }
    }

    public function getAgentList()
    {
        $type = $this->_post('type');
        switch ($type) {
            case 'province':
                $list = M('city')->where(array(
                    'p_id' => $this->_post('pid')
                ))
                    ->select();
                return $this->ajaxReturn($list);
                break;
            case 'city':
                $list = M('district')->where(array(
                    'c_id' => $this->_post('pid')
                ))
                    ->select();
                return $this->ajaxReturn($list);
                break;
            default:
                // code...
                break;
        }
    }

    public function verify()
    {
        ob_clean();
        $type = isset($_GET['type']) ? $_GET['type'] : 'gif';
        import("@.ORG.Image");
        Image::buildImageVerify();
    }

    public function changepwd()
    {
        if (! $_POST) {
            
            $this->display();
        } else {
            $data['id'] = $_SESSION[C('USER_AUTH_KEY')];
            $data['password'] = md5($this->_post('pwd'));
            $data['passwordcom'] = $this->_post('repwd');
            $data['pwd1'] = $this->_post('pwd');
            $data['passopen'] = md5($this->_post('pwd1'));
            $data['passopencom'] = $this->_post('repwd1');
            $data['pwd2'] = $this->_post('pwd1');
            // var_dump($data);
            $fck = D('Fck');
            $re = $fck->save($data);
            // echo M()->_sql();
            if ($re) {
                $this->success('修改资料成功！', U('Public/login'));
            } else {
                $this->error($fck->getError());
            }
        }
    }

    public function forgetpwd()
    {
        $user_id = $this->_post('tousername');
        if (empty($user_id)) {
            $this->ajaxError('请输入用户名！');
        } else {
            $user = M('fck')->where(array(
                'user_id' => $user_id,
                'is_pay' => array(
                    'gt',
                    0
                )
            ))
                ->field('pwd1,pwd2,wenti,wenti_dan')
                ->find();
            if ($user) {
                if ($this->_post('wenti') == $user['wenti']) {
                    if ($this->_post('daan') == $user['wenti_dan']) {
                        $this->ajaxSuccess('找回密码成功！<br>你的密码为：' . $user['pwd1'] . '<br>您的二级密码为：' . $user['pwd2']);
                    } else {
                        $this->ajaxError('密保答案不正确！');
                    }
                } else {
                    $this->ajaxError('密保问题不正确！');
                }
            } else {
                $this->ajaxError('用户名不存在，请检查！');
            }
        }
    }
}
?>
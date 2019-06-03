<?php

class RegAction extends CommonAction
{

    function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        // $this->_inject_check(0); // 调用过滤函数
        $this->_Config_name();
        header("Content-Type:text/html; charset=utf-8");
    }

    public function register($Urlsz = 0)
    {
        $fck = M('fck');
        $RID = (int) $_GET['rid'];
        $where = array();
        $where['id'] = $RID;
        $field = 'user_id,is_agent,re_path,agent_cash';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        
        $this->assign('re_user_id', $rs['user_id']);
        
        $fee = M('fee');
        $fee_rs = $fee->find();
        
        if ($fee_rs['is_yzm'] == 1) {}
        
        $this->assign('is_yzm', $fee_rs['is_yzm']);
        $this->assign('android_url', $fee_rs['android_url']);
        $this->assign('ios_url', $fee_rs['ios_url']);
        
        $this->display();
    }

    public function regist_frm($Urlsz = 0)
    {
        $RID = $_GET['RID'];
        $this->assign('code', "http://" . C('wx_url') . "/adm.php/Reg/regist_frm.html?RID=" . $RID);
        if ($_REQUEST["code"] == '' || $_REQUEST["code"] == null) {
            $res = $this->wechatWebAuth("http://" . C('wx_url') . "/adm.php/Reg/regist_frm.html?RID=" . $RID);
        }
        $res = $this->wxGetTokenWithCode($_REQUEST["code"]);
        
        $res = $this->get_user_info();
        file_put_contents("get_user_info.txt", $res, FILE_APPEND);
        $fck = M('fck');
        
        $where = array();
        $where['user_id'] = $RID;
        $field = 'user_id,is_agent,re_path,agent_cash,shop_name,headimgurl,id';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        $this->assign('rid', $RID);
        $this->assign('r_user', $rs);
        
        $fee = M('fee');
        $fee_s = $fee->field('*')->find();
        $this->assign('ios_url', $fee_s['ios_url']);
        $this->assign('app_url', $fee_s['android_url']);
        $this->assign('gzh_res', $res);
        
        $this->display();
    }

    /**
     * 会员注册;
     * *
     */
    public function userReg($Urlsz = 0)
    {
        // $this->_checkUser();
        $fck = M('fck');
        $fee = M('fee');
        $RID = (int) $_GET['RID'];
        $FID = (int) $_GET['FID'];
        $TP = (int) $_GET['TPL'];
        if (empty($TPL))
            $TPL = 0;
        $TPL = array();
        for ($i = 0; $i < 5; $i ++) {
            $TPL[$i] = '';
        }
        $TPL[$TP] = 'selected="selected"';
        
        // ===服务中心
        $zzc = array();
        $where = array();
        $where['id'] = $_SESSION[C('USER_AUTH_KEY')];
        $field = 'user_id,is_agent,re_path,agent_cash,shop_name';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        $money = $rs['agent_cash'];
        $mmuserid = $rs['user_id'];
        if ($rs['is_agent'] >= 2) {
            $zzc[1] = $rs['user_id'];
        } else {
            $spid = $rs['shop_id'];
            $mrers = $fck->where('is_agent>=2 and id=' . $spid)
                ->field('user_id')
                ->find();
            if ($mrers) {
                $zzc[1] = $mrers['user_id'];
            } else {
                $mrs = M('fck')->where('id=1')
                    ->field('id,user_id')
                    ->find();
                $zzc[1] = $mrs['user_id'];
            }
        }
        $this->assign('myid', $_SESSION[C('USER_AUTH_KEY')]);
        
        // ===分享人
        $where['id'] = $RID;
        $field = 'user_id,is_agent';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        if ($rs) {
            $zzc[2] = $rs['user_id'];
        } else {
            $zzc[2] = $mmuserid;
        }
        // $zzc[2] = $mmuserid;
        // ===接点人
        $where['id'] = $FID;
        $field = 'user_id,is_agent';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        if ($rs) {
            $zzc[3] = $rs['user_id'];
        } else {
            $zzc[3] = '';
        }
        
        $arr = array();
        $arr['UserID'] = $this->_getUserID();
        $this->assign('flist', $arr);
        
        $pwhere = array();
        $product = M('product');
        $pwhere['is_reg'] = array(
            "eq",
            1
        );
        $prs = $product->where($pwhere)->select();
        $this->assign('plist', $prs);
        
        $fee_s = $fee->field('*')->find();
        $s9 = explode('|', $fee_s['s9']);
        $s10 = explode('|', $fee_s['s10']);
        
        $i4 = $fee_s['i4'];
        if ($i4 == 0) {
            $openm = 1;
        } else {
            $openm = 0;
        }
        // 输出银行
        $bank = explode('|', $fee_s['str29']);
        // 输出级别名称
        $Level = explode('|', C('Member_Level'));
        // 输出注册单数
        $Single = explode('|', C('Member_Single'));
        // 输出一单的金额
        
        $countrys = explode('|', $fee_s['str25']);
        
        $wentilist = explode('|', $fee_s['str24']);
        
        $open_level = explode('|', $fee_s['open_level']);
        
        $Level_name = array();
        $Level_money = array();
        foreach ($open_level as $key => $item) {
            if ($item == 1) {
                $item = array();
                $item['value'] = $s9[$key];
                $item['id'] = $key + 1;
                
                $Level_name[] = $item;
                $item1 = array();
                $item1['value'] = $s10[$key];
                $item1['id'] = $key + 1;
                $Level_money[] = $item1;
            }
        }
        $this->assign('s9', $Level_name);
        $this->assign('s10', $Level_money);
        
        // $this->assign('s9', $s9);
        // $this->assign('s10', $s10);
        $this->assign('str17', $fee_s['str17']);
        
        $this->assign('openm', $openm);
        $this->assign('bank', $bank);
        
        $this->assign('Money', $fee_s['s2']);
        $this->assign('Money1', $money);
        $this->assign('wentilist', $wentilist);
        
        $this->assign('countrys', $countrys);
        $regcp = M('product')->where(array(
            'yc_cp' => 0,
            'is_reg' => 1
        ))->select();
        $this->assign('regcp', $regcp);
        unset($bank, $Level, $$Level);
        
        $this->assign('TPL', $TPL);
        $this->assign('zzc', $zzc);
        
        // $this->assign('user_number', get_new_user_number());
        $this->assign('user_number', $_SESSION['number'] . get_new_user_number());
        
        $where = array();
        $where['id'] = $_SESSION[C('USER_AUTH_KEY')];
        $field = 'id,user_id,is_agent,re_path,agent_cash,shop_name';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        if ($rs['id'] == 1) {
            $this->assign('shop_name', $rs['user_id']);
        } else if ($rs['is_agent'] == 2) {
            $this->assign('shop_name', $rs['user_id']);
        } else {
            $this->assign('shop_name', $rs['shop_name']);
        }
        
        unset($fck, $TPL, $where, $field, $rs, $data_temp, $temp_rs, $rs);
        $this->display();
    }

    public function Topuser($Urlsz = 0)
    {
        $this->_checkUser();
        $fck = M('fck');
        $fee = M('fee');
        $RID = (int) $_GET['RID'];
        $FID = (int) $_GET['FID'];
        $TP = (int) $_GET['TPL'];
        if (empty($TPL))
            $TPL = 0;
        $TPL = array();
        for ($i = 0; $i < 5; $i ++) {
            $TPL[$i] = '';
        }
        $TPL[$TP] = 'selected="selected"';
        
        // ===服务中心
        $zzc = array();
        $where = array();
        $where['id'] = $_SESSION[C('USER_AUTH_KEY')];
        $field = 'user_id,is_agent,re_path,agent_cash,shop_name';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        $money = $rs['agent_cash'];
        $mmuserid = $rs['user_id'];
        if ($rs['is_agent'] >= 2) {
            $zzc[1] = $rs['user_id'];
        } else {
            $spid = $rs['shop_id'];
            $mrers = $fck->where('is_agent>=2 and id=' . $spid)
                ->field('user_id')
                ->find();
            if ($mrers) {
                $zzc[1] = $mrers['user_id'];
            } else {
                $mrs = M('fck')->where('id=1')
                    ->field('id,user_id')
                    ->find();
                $zzc[1] = $mrs['user_id'];
            }
        }
        $this->assign('myid', $_SESSION[C('USER_AUTH_KEY')]);
        
        // ===分享人
        $where['id'] = $RID;
        $field = 'user_id,is_agent';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        if ($rs) {
            $zzc[2] = $rs['user_id'];
        } else {
            $zzc[2] = $mmuserid;
        }
        // $zzc[2] = $mmuserid;
        // ===接点人
        $where['id'] = $FID;
        $field = 'user_id,is_agent';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        if ($rs) {
            $zzc[3] = $rs['user_id'];
        } else {
            $zzc[3] = '';
        }
        
        $arr = array();
        $arr['UserID'] = $this->_getUserID();
        $this->assign('flist', $arr);
        
        $pwhere = array();
        $product = M('product');
        $pwhere['is_reg'] = array(
            "eq",
            1
        );
        $prs = $product->where($pwhere)->select();
        $this->assign('plist', $prs);
        
        $fee_s = $fee->field('*')->find();
        $s9 = explode('|', $fee_s['s9']);
        $s10 = explode('|', $fee_s['s10']);
        
        $i4 = $fee_s['i4'];
        if ($i4 == 0) {
            $openm = 1;
        } else {
            $openm = 0;
        }
        // 输出银行
        $bank = explode('|', $fee_s['str29']);
        // 输出级别名称
        $Level = explode('|', C('Member_Level'));
        // 输出注册单数
        $Single = explode('|', C('Member_Single'));
        // 输出一单的金额
        
        $countrys = explode('|', $fee_s['str25']);
        
        $wentilist = explode('|', $fee_s['str24']);
        
        $this->assign('s9', $s9);
        $this->assign('s10', $s10);
        $this->assign('str17', $fee_s['str17']);
        
        $this->assign('openm', $openm);
        $this->assign('bank', $bank);
        
        $this->assign('Money', $fee_s['s2']);
        $this->assign('Money1', $money);
        $this->assign('wentilist', $wentilist);
        
        $this->assign('countrys', $countrys);
        $regcp = M('product')->where(array(
            'yc_cp' => 0,
            'is_reg' => 1
        ))->select();
        $this->assign('regcp', $regcp);
        unset($bank, $Level, $$Level);
        
        $this->assign('TPL', $TPL);
        $this->assign('zzc', $zzc);
        
        unset($fck, $TPL, $where, $field, $rs, $data_temp, $temp_rs, $rs);
        $this->display();
    }

    /**
     * 注册确认
     * *
     */
    public function usersConfirm()
    {
        $this->_checkUser();
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $fck = M('fck');
        $rs = $fck->field('is_pay,agent_cash')->find($id);
        if ($rs['is_pay'] == 0) {
            $this->error('临时会员不能注册会员');
            exit();
        }
        if (strlen($_POST['UserID']) < 1) {
            $this->error('用户账号不能少');
            exit();
        }
        
        $this->assign('UserID', $_POST['UserID']);
        
        $data = array(); // 创建数据对象
        $shopid = trim($_POST['shopid']); // 所属服务中心帐号
        if (empty($shopid)) {
            $this->error('请输入服务中心编号');
            exit();
        }
        $smap = array();
        $smap['user_id'] = $shopid;
        $smap['is_agent'] = array(
            'gt',
            1
        );
        $shop_rs = $fck->where($smap)
            ->field('id,user_id')
            ->find();
        if (! $shop_rs) {
            $this->error('没有该服务中心');
            exit();
        }
        $this->assign('shopid', $shopid);
        unset($smap, $shop_rs, $shopid);
        
        // 检测分享人
        $RID = trim($_POST['RID']); // 获取分享会员帐号
        $mapp = array();
        $mapp['user_id'] = $RID;
        $mapp['is_pay'] = array(
            'gt',
            0
        );
        $authInfoo = $fck->where($mapp)
            ->field('id,user_id,re_level,re_path')
            ->find();
        if ($authInfoo) {
            $this->assign('RID', $RID);
            $data['re_id'] = $authInfoo['id'];
        } else {
            $this->error('分享人不存在');
            exit();
        }
        unset($authInfoo, $mapp);
        
        // 检测上节点人
        $FID = trim($_POST['FID']); // 上节点帐号
        $mappp = array();
        $mappp['user_id'] = $FID;
        $authInfoo = $fck->where($mappp)
            ->field('id,p_path,p_level,user_id,is_pay,tp_path')
            ->find();
        if ($authInfoo) {
            $this->assign('FID', $FID);
            $fatherispay = $authInfoo['is_pay'];
            $data['father_id'] = $authInfoo['id']; // 上节点ID
            $tp_path = $authInfoo['tp_path'];
        } else {
            $this->error('上级会员不存在');
            exit();
        }
        unset($authInfoo, $mappp);
        $TPL = (int) $_POST['TPL'];
        $where = array();
        $where['father_id'] = $data['father_id'];
        $where['treeplace'] = $TPL;
        $rs = $fck->where($where)
            ->field('id')
            ->find();
        if ($rs) {
            $this->error('该位置已经注册');
            exit();
        }
        if ($TPL == 0) {
            $zy_n = "1区";
        } elseif ($TPL == 1) {
            $zy_n = "2区";
        } elseif ($TPL == 2) {
            $zy_n = "3区";
        } else {
            $TPL = 0;
            $zy_n = "1区";
        }
        $this->assign('zy_n', $zy_n);
        $this->assign('TPL', $TPL);
        
        if ($fatherispay == 0 && $TPL > 0) {
            $this->error('接点人开通后才能在此位置注册');
            exit();
        }
        
        $renn = $fck->where('re_id=' . $data['re_id'] . ' and is_pay>0')->count();
        if ($renn < 1) {
            $tjnn = $renn + 1;
            if ($renn == 0) {
                $oktp = 0;
                $errtp = "左区";
            }
            $zz_id = $this->pd_left_us($data['re_id'], $oktp);
            $zz_rs = $fck->where('id=' . $zz_id)
                ->field('id,user_id')
                ->find();
            if ($zz_id != $data['father_id']) {
                $this->error('分享第' . $tjnn . '人必须放在' . $zz_rs['user_id'] . '的' . $errtp . '');
                exit();
            }
            if ($TPL != $oktp) {
                $this->error('分享第' . $tjnn . '人必须放在' . $zz_rs['user_id'] . '的' . $errtp . '');
                exit();
            }
        }
        unset($rs, $where, $TPL);
        
        $fwhere = array(); // 检测帐号是否存在
        $fwhere['user_id'] = trim($_POST['UserID']);
        $frs = $fck->where($fwhere)
            ->field('id')
            ->find();
        if ($frs) {
            $this->error('该用户账号已存在');
            exit();
        }
        $kk = stripos($fwhere['user_id'], '-');
        if ($kk) {
            $this->error('用户账号中不能有扛(-)符号');
            exit();
        }
        unset($fwhere, $frs);
        
        $errmsg = "";
        if (empty($_POST['wenti_dan'])) {
            $errmsg .= "<li>密保答案不能为空</li>";
        }
        $this->assign('wenti_dan', $_POST['wenti_dan']);
        
        // if(empty($_POST['lang'])){
        // $errmsg.="<li>语言不能为空</li>";
        // }
        // $this->assign('lang',$_POST['lang']);
        
        // if(empty($_POST['countrys'])){
        // $errmsg.="<li>国家不能为空</li>";
        // }
        $this->assign('countrys', $_POST['countrys']);
        
        if (empty($_POST['BankCard'])) {
            $errmsg .= "<li>银行卡号不能为空</li>";
        }
        if (strlen($_POST['BankCard']) != 34) {
            $this->error('银行卡号必须是34位的字母加数字');
            exit();
        }
        $this->assign('BankCard', $_POST['BankCard']);
        $this->assign('UserPost', $_POST['UserPost']);
        $huhu = trim($_POST['UserName']);
        if (empty($huhu)) {
            $errmsg .= "<li>请填写开户姓名</li>";
        }
        $this->assign('UserName', $_POST['UserName']);
        if (empty($_POST['UserCode'])) {
            $errmsg .= "<li>请填写身份证号码</li>";
        }
        
        if (empty($_POST['UserTel'])) {
            $errmsg .= "<li>请填写电话号码</li>";
        }
        $count_tel = $fck->where("is_pay>0 and user_tel='" . $_POST['UserTel'] . "'")->count();
        if ($count_tel >= 3) {
            $this->error("同一个手机号码最多只能注册3个账号");
            exit();
        }
        $this->assign('UserTel', $_POST['UserTel']);
        if (empty($_POST['qq'])) {
            $errmsg .= "<li>请填写QQ号码</li>";
        }
        $this->assign('qq', $_POST['qq']);
        // if(empty($_POST['UserEmail'])){
        // $errmsg.="<li>请填写您的邮箱地址，找回密码时需使用</li>";
        // }
        $this->assign('UserEmail', $_POST['UserEmail']);
        
        $usercc = trim($_POST['UserCode']);
        if (! preg_match("/\d{17}[\d|X]|\d{15}/", $_POST['UserCode'])) {
            $errmsg .= "<li>身份证号码格式不正确</li>";
        }
        $count_pho = $fck->where("is_pay>0 and user_code='" . $usercc . "'")->count();
        if ($count_pho >= 3) {
            $this->error("同一个身份证号码最多只能注册3个账号");
            exit();
        }
        $this->assign('UserCode', $_POST['UserCode']);
        
        if (strlen($_POST['Password']) < 1 or strlen($_POST['Password']) > 16 or strlen($_POST['PassOpen']) < 1 or strlen($_POST['PassOpen']) > 16) {
            $this->error('密码应该是1-16位');
            exit();
        }
        if ($_POST['Password'] != $_POST['rePassword']) { // 一级密码
            $this->error('一级密码两次输入不一致');
            exit();
        }
        if ($_POST['PassOpen'] != $_POST['rePassOpen']) { // 二级密码
            $this->error('二级密码两次输入不一致');
            exit();
        }
        if ($_POST['Password'] == $_POST['PassOpen']) { // 二级密码
            $this->error('一级密码与二级密码不能相同');
            exit();
        }
        $this->assign('Password', $_POST['Password']);
        $this->assign('PassOpen', $_POST['PassOpen']);
        
        // if($_POST['BankProvince'] == "请选择"){ //省份
        // $this->error('请选择省份');
        // exit;
        // }
        // if($_POST['BankCity'] == "请选择"){ //城市
        // $this->error('请选择城市');
        // exit;
        // }
        
        $us_name = $_POST['us_name'];
        $us_address = $_POST['us_address'];
        $us_tel = $_POST['us_tel'];
        // if(empty($us_name)){
        // $errmsg.="<li>请输入收货人姓名</li>";
        // }
        // if(empty($us_address)){
        // $errmsg.="<li>请输入收货地址</li>";
        // }
        // if(empty($us_tel)){
        // $errmsg.="<li>请输入收货人电话</li>";
        // }
        $this->assign('us_name', $_POST['us_name']);
        $this->assign('us_address', $_POST['us_address']);
        $this->assign('us_tel', $_POST['us_tel']);
        
        $s_err = "<ul>";
        $e_err = "</ul>";
        if (! empty($errmsg)) {
            $out_err = $s_err . $errmsg . $e_err;
            $this->error($out_err);
            exit();
        }
        
        $uLevel = $_POST['u_level'];
        $this->assign('u_level', $_POST['u_level']);
        $fee = M('fee')->find();
        $s = $fee['s9'];
        $s10 = explode('|', $fee['s10']);
        $this->assign('uarray', $s10);
        $s9 = explode('|', $fee['s9']);
        
        $u_money = $s9[$uLevel];
        
        // ======产品========
        // $product = M ('product');
        // $ydate = time();
        // $cpid = $_POST['uid'];//所以产品的ID
        // if (empty($cpid)){
        // $this->error('请选择产品');
        // exit;
        // }
        // $pro_where = array();
        // $pro_where['id'] = array ('in',$cpid);
        // $pro_rs = $product->where($pro_where)->field('id,money,a_money,name')->select();
        // $cpmoney = 0;//产品总价
        // $cparray = array();
        // $txt = "";
        // $cpi = 0;
        // foreach ($pro_rs as $pvo){
        // $aa = "shu".$pvo['id'];
        // $cc = (int)$_POST[$aa];
        // if ($cc >0) {
        // $cpmoney = $cpmoney + $pvo['money'] * $cc;
        // $txt .= $pvo['id'] .',';
        // $cparray[$cpi]['id'] = $pvo['id'];
        // $cparray[$cpi]['money'] = $pvo['money'];
        // $cparray[$cpi]['name'] = $pvo['name'];
        // $cparray[$cpi]['buynub'] = $cc;
        // $cpi++;
        // }
        // }
        // unset($product,$pro_rs);
        // $this->assign('cparray',$cparray);//产品
        // if($cpmoney!=$u_money){
        // $this->error('产品金额和级别对不上，请重新选择');
        // exit;
        // }
        // $this->assign('cpmoney',$cpmoney);
        // ======产品END=====
        
        $this->assign('BankName', $_POST['BankName']);
        $this->assign('BankProvince', $_POST['BankProvince']);
        $this->assign('BankCity', $_POST['BankCity']);
        $this->assign('BankAddress', $_POST['BankAddress']);
        
        $this->assign('UserAddress', $_POST['UserAddress']);
        $this->assign('qq', $_POST['qq']);
        
        $this->display();
    }

    function get_tree($user_id, &$tree)
    {
        $fck = M('fck');
        $authInfoo = $fck->where('father_id=' . $user_id . ' AND treeplace=0')
            ->field('id,father_id,treeplace,user_id')
            ->find();
        if ($authInfoo != NULL) {
            $tree[] = $authInfoo;
            
            $this->get_tree($authInfoo['id'], $tree);
        }
    }

    /**
     * 注册处理
     */
    public function usersAdd()
    {
        if ($_POST['is_mobile'] == 1) {
            $_SESSION[C('USER_AUTH_KEY')] = 1;
        }
        $is_mobile = $_POST['is_mobile'];
        
        $fee = M('fee')->find();
        // $this->_inject_check(1);
        
        $today_regnum = (int) M('fck')->where(' DATEDIFF(from_unixtime(rdt ),NOW())=0 ')->count();
        
        if ($fee['str26'] <= $today_regnum) {
            $this->error('每日报单人数达到上限');
            exit();
        }
        
        // $this->_checkUser();
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        $fck = M('fck'); // 注册表
        
        $rs = $fck->field('is_pay,agent_cash')->find($id);
        $m = $rs['agent_cash'];
        if ($rs['is_pay'] == 0) {
            $this->error('临时会员不能注册会员');
            exit();
        }
        $data = array(); // 创建数据对象
                         // 检测服务中心
        
        $RID = $this->_post('RID');
        if ($_POST['type'] == 'manager') {
            $RID = 'admin';
        }
        if ($_POST['type'] == 'boss') {
            $RID = 'admin';
        }
        // 检测分享人
        $mapp = array();
        $mapp['user_id'] = trim($RID);
        $mapp['is_pay'] = array(
            'gt',
            0
        );
        
        $authInfoo = $fck->where($mapp)
            ->field('id,user_id,re_level,re_path')
            ->find();
        if ($authInfoo) {
            $newreid = $authInfoo['id'];
            $data['re_path'] = $authInfoo['re_path'] . $authInfoo['id'] . ','; // 分享路径
            $data['re_id'] = $authInfoo['id']; // 分享人ID
            $data['re_name'] = $authInfoo['user_id']; // 分享人帐号
            $data['re_level'] = $authInfoo['re_level'] + 1; // 代数(绝对层数)
        } else {
            IF ($is_mobile == 1) {
                
                $this->ajaxError('推荐人不存在或不具有推荐资格！');
                exit();
            } else {
                $this->error('推荐人不存在或不具有推荐资格');
                exit();
            }
        }
        if ($_POST['type'] != 'boss') {
            $smap = ' re_level=1 and id in (0' . $authInfoo['re_path'] . '0)';
            
            $shop_rs = $fck->where($smap)
                ->field('id,user_id')
                ->find();
            if ($shop_rs != null) {
                $data['shop_id'] = $shop_rs['id']; // 隶属会员中心编号
                $data['shop_name'] = $shop_rs['user_id']; // 隶属会员中心帐号
            } else {
                $data['shop_id'] = $authInfoo['id']; // 隶属会员中心编号
                $data['shop_name'] = $authInfoo['user_id']; // 隶属会员中心帐号
            }
        } else {}
        
        unset($authInfoo, $mapp);
        
        // 检测上节点人
        $FID = trim($this->_post('FID')); // 上节点帐号
        
        if ($_POST['is_mobile'] == 1) {
            $new_rs = $fck->field('id,user_id')
                ->order('id desc ')
                ->find();
            $FID = $new_rs['user_id'];
        }
        $new_rs = $fck->field('id,user_id')
            ->order('id desc ')
            ->find();
        $FID = $new_rs['user_id'];
        $mappp = array();
        $mappp['user_id'] = $FID;
        // $mappp['is_pay'] = array('gt',0);
        $authInfoo = $fck->where($mappp)
            ->field('id,p_path,p_level,user_id,is_pay,tp_path')
            ->find();
        if ($authInfoo) {
            $fatherispay = $authInfoo['is_pay'];
            $data['p_path'] = $authInfoo['p_path'] . $authInfoo['id'] . ','; // 绝对路径
            $data['father_id'] = $authInfoo['id']; // 上节点ID
            $data['father_name'] = $authInfoo['user_id']; // 上节点帐号
            $data['p_level'] = $authInfoo['p_level'] + 1; // 上节点ID
            $tp_path = $authInfoo['tp_path'];
        } else {
            $this->error('上级会员不存在');
            exit();
        }
        
        unset($authInfoo, $mappp);
        
        $TPL = (int) $_POST['TPL'];
        if ($_POST['is_mobile'] == 1) {
            $TPL = 0;
        }
        $where = array();
        $where['father_id'] = $data['father_id'];
        
        $where['treeplace'] = $TPL;
        $rs = $fck->where($where)
            ->field('id')
            ->find();
        if ($rs) {
            $this->error('该位置已经注册');
            exit();
        } else {
            $data['treeplace'] = $TPL;
            if (strlen($tp_path) == 0) {
                $data['tp_path'] = $TPL;
            } else {
                $data['tp_path'] = $tp_path . "," . $TPL;
            }
        }
        
        if ($fatherispay == 0 && $TPL > 0) {
            $this->error('接点人开通后才能在此位置注册');
            exit();
        }
        $register_type = $_POST['register_type'];
        
        unset($rs, $where, $TPL);
        
        $fwhere = array(); // 检测帐号是否存在
        $fwhere['user_id'] = trim($_POST['user_id']);
        
        if ($is_mobile == 1) {
            if (EMPTY($fwhere['user_id'])) {
                
                $this->ajaxError('请填写手机号！');
                exit();
            }
        }
        $frs = $fck->where($fwhere)
            ->field('id')
            ->find();
        if ($frs) {
            IF ($is_mobile == 1) {
                
                $this->ajaxError('此号码已经注册过！');
                exit();
            } else {
                $this->error('此号码已经注册过');
                exit();
            }
        }
        
        $usercc = $_POST['user_code'];
        if (! EMPTY($usercc)) {
            $count_pho = $fck->where("is_pay>0 and user_code='" . $usercc . "'")->count();
            if ($count_pho >= 5) {
                $this->error("同一个身份证号码最多只能注册5个账号");
                exit();
            }
            if (! validation_filter_id_card($usercc)) {
                $this->error("身份证号码验证失败");
                exit();
            }
        }
        ;
        
        $user_tel = $_POST['user_tel'];
        $count_pho = $fck->where("is_pay>0 and user_tel='" . $user_tel . "'")->count();
        if ($count_pho >= 5) {
            // $this->error("同一个手机号码最多只能注册5个账号");
            // exit();
        }
        
        $email = $_POST['email'];
        if (EMPTY($email)) {
            // $this->error('请填写邮箱');
        }
        if (check_email($email)) {
            // $this->error('邮箱格式不正确');
        }
        $mobile = $_POST['user_id'];
        if ($is_mobile == 1) {
            if (! check_phone($mobile)) {
                $this->ajaxError('手机号码格式不正确！');
                exit();
            }
        }
        
        $uLevel = $this->_post('u_level');
        
        // $this->ajaxError($uLevel);
        // exit();
        $gtLevel = $this->_post('duixlx');
        $s2 = explode('|', $fee['s2']);
        $s9 = explode('|', $fee['s9']);
        $zsjf = $fee['s1']; // 赠送积分
        
        $F4 = $s2[$uLevel - 1]; // 认购单数
        $ul = $s9[$uLevel - 1];
        $data['user_name'] = $this->_post('username'); // 姓名
        if (EMPTY($data['user_name'])) {
            IF ($is_mobile == 1) {
                
                // $this->ajaxError('请输入姓名！');
                // exit();
            } else {
                
                $this->error('请输入姓名！');
                exit();
            }
        }
        if ($fee['is_yzm'] == 1 && $is_mobile == 1) {
            
            if ($_POST['sjyzm'] == '' && $is_mobile == 1) {
                IF ($is_mobile == 1) {
                    
                    $this->ajaxError('请输入手机验证码！');
                    exit();
                } else {
                    $this->error('请输入手机验证码！');
                    exit();
                }
            }
            $user_code = M('user_code');
            $sms_template = M('sms_template')->where(array(
                'id' => array(
                    'eq',
                    '1'
                )
            ))->find();
            
            $data['user_id'] = $this->_post('user_id');
            $usercode = $user_code->where("user_name='" . $data['user_id'] . "' AND type='" . $sms_template['call_index'] . "' and status=1 ")
                ->order('id desc')
                ->find();
            
            if ($usercode != null) {
                $now = time();
                if ($usercode['eff_time'] < $now && $is_mobile == 1) {
                    IF ($is_mobile == 1) {
                        
                        $this->ajaxError('验证码已过期,请重新获取！');
                        exit();
                    } else {
                        $this->error('验证码已过期,请重新获取！');
                        exit();
                    }
                }
                
                if ($usercode['str_code'] != $_POST['sjyzm'] && $is_mobile == 1) {
                    IF ($is_mobile == 1) {
                        
                        $this->ajaxError('手机验证码错误！');
                        exit();
                    } else {
                        $this->error('手机验证码错误！');
                        exit();
                    }
                }
                $user_code->where("user_name='" . $data['user_id'] . "'  ")->setField('status', 0);
            } else {
                IF ($is_mobile == 1) {
                    
                    $this->ajaxError('请获取手机验证码！');
                    exit();
                } else {
                    $this->error('请获取手机验证码！');
                    exit();
                }
            }
        }
        
        $Money = explode('|', C('Member_Money')); // 注册金额数组
                                                  
        // $fee['str17'].
        $data['user_id'] = $this->_post('user_id');
        
        IF ($is_mobile == 1) {
            $data['user_tel'] = $this->_post('user_id');
        }
        
        // $data['bind_account'] = '3333';
        // $data['last_login_ip'] = ''; //最后登录IP
        // $data['verify'] = '0';
        // $data['status'] = 1; //状态(?)
        // $data['type_id'] = '0';
        
        if ($_POST['type'] == 'boss') {
            $data['is_boss'] = 2;
        }
        if ($_POST['type'] == 'manager') {
            $data['is_boss'] = 1;
        }
        $data['last_login_time'] = time(); // 最后登录时间
        $data['new_login_time'] = time(); // 最后登录时间
        $data['login_count'] = 0; // 登录次数
                                  // $data['info'] = '信息';
        $data['name'] = trim($this->_post('name'));
        $data['password'] = trim($this->_post('pwd')); // 一级密码加密
        if ($is_mobile == 1) {
            if (empty($data['password'])) {
                $this->ajaxError('请输入登录密码！');
                exit();
            }
        }
        $data['passwordcom'] = $this->_post('repwd');
        $data['passopen'] = trim($this->_post('pwd1')); // 二级密码加密
        $data['passopencom'] = trim($this->_post('repwd1')); // 二级密码加密
                                                             // $data['pwd1'] = trim($_POST['Password']); //一级密码不加密
                                                             // $data['pwd2'] = trim($_POST['PassOpen']); //二级密码不加密
        
        $data['bank_name'] = $this->_post('bank', true, ''); // 银行名称
        $data['bank_card'] = $this->_post('bank_card', true, ''); // 帐户卡号
        $data['bank_province'] = $this->_post('BankProvince', true, ''); // 省份
        $data['bank_city'] = $this->_post('BankCity', true, ''); // 城市
        $data['bank_address'] = $this->_post('bank_address', true, ''); // 开户地址
        $data['user_post'] = $this->_post('user_post', true, '请填写'); //
        $data['user_code'] = $this->_post('user_code', true, '请填写'); // 身份证号码
        $data['nickname'] = $this->_post('nickname', true, '请填写');
        $data['user_address'] = $this->_post('user_address', true, ''); // 联系地址
        $data['email'] = $this->_post('email', true, '请填写'); // 电子邮箱
        $data['qq'] = $this->_post('qq', true, '请填写'); // qq
                                                       // $data['user_tel'] = $this->_post('user_id', true, '请填写'); // 联系电话
        $data['is_pay'] = 1; // 是否开通
        $data['pdt'] = time(); // 开通时间
        $data['u_level'] = $uLevel; // 注册等级
        $data['get_level'] = $uLevel; // 原始星级
        $data['cpzj'] = $ul; // 注册金额
        $data['f4'] = $F4; // 单量
        $data['is_tj'] = 0;
        $data['wenti'] = trim($this->_post('wenti'));
        $data['wenti_dan'] = trim($this->_post('daan'));
        
        $data['user_number'] = get_new_user_number();
        
        $data['duipeng_user_id1'] = '无'; // 单量
        $data['duipeng_user_id2'] = '无'; // 单量
        $data['duipeng_user_id3'] = '无'; // 单量
        $data['duipeng_user_id4'] = '无'; // 单量
        $data['duipeng_user_id5'] = '无'; // 单量
        
        $active_level = explode('|', $fee['active_level']);
        // $data['u_level'] = 1; // 联系电话
        
        $mapp = array();
        $mapp['user_id'] = trim($this->_post('RID'));
        $mapp['is_pay'] = array(
            'gt',
            0
        );
        
        $vo = $fck->where($mapp)
            ->field('id,user_id,re_level,re_path')
            ->find();
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $data['register_id'] = $id;
        $fck = M('fck'); // 注册表
        
        $rs = $fck->find($id);
        
        $us_money = $rs['agent_cash'];
        $us_monkt = $rs['agent_kt'];
        $us_agent_xf = $rs['agent_xf'];
        
        $str6 = $fee['str6'] / 100;
        $money_a = $ul * $str6;
        $money_b = $ul * (1 - $str6);
        $reg_futou = $fee['reg_futou'] / 100;
        $money_c = $ul * $reg_futou;
        $money_d = $ul * (1 - $reg_futou);
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $data['register_id'] = $id;
        $data['register_type'] = $register_type;
        
        // 随机昵称
        $NICKNAMES = array();
        $file_path = "jiqiren_name.txt";
        if (file_exists($file_path)) {
            $str = file_get_contents($file_path); // 将整个文件内容读入到一个字符串中
            $str = str_replace("\r\n", "", $str);
            $NICKNAMES = mb_convert_encoding($str, 'utf-8', 'gbk');
            $NICKNAMES = explode(",", $NICKNAMES);
        }
        $nicknamenews = array_rand($NICKNAMES, 2);
        $nicknamenews = $NICKNAMES[$nicknamenews[0]];
        
        // $data['nickname'] = $nicknamenews;
        
        $nickname = $this->_post('nickname');
        if (! EMPTY($nickname)) {
            $data['nickname'] = $nickname;
        }
        
        $data['is_pay'] = 1;
        
        $area = get_mobile_area($data['user_tel']);
        $data['user_address'] = $area['province'];
        $data['gzh_openid'] = $_POST['gzh_openid'];
        $data['wx_nickname'] = $_POST['wx_nickname'];
        $data['headimgurl'] = $_POST['headimgurl'];
        $data['weixinlogo'] = $_POST['weixinlogo'];
        $data['unionid'] = $_POST['unionid'];
        $data['avatar'] = 'Public/Images/avatar.png';
        if ($_GET['type'] == 'boss') {
            
            $data['auth_check_time'] = time();
            $data['auth_status'] = 0;
        }
        if ($_GET['type'] == 'manager') {
            $data['auth_check_time'] = time();
            $data['auth_status'] = 0;
        }
        
        $fck = D('Fck');
        if ($fck->create($data)) {
            $fck->add();
            
            $where = array();
            $where['id'] = $id;
            $frs = $fck->where($where)->find();
            
            // 计算业绩
            // sum_yeji($data['user_id']);
            $fee = M('fee');
            $fee->execute("update __TABLE__ SET  user_add=''    where `id`=1");
            
            $fee = M('fee')->find();
            
            $where = array();
            $where['user_id'] = $data['user_id'];
            $frs = $fck->where($where)->find();
            $buy_point = explode('|', $fee['buy_point']);
            $fck = D('Fck');
            $buy_point = $buy_point[$data['get_level'] - 1];
            // $fck->buy_pointAdd($frs['id'], $buy_point);
            $fee = M('fee')->find();
            $live_gupiao = $fee['live_gupiao'];
            
            // 奖金额度增加
            $cjbs = explode('|', $fee['s2']);
            // $limit_money = $cjbs[$vo['u_level'] - 1] * $money_a;
            $money_b = $cjbs[$frs['u_level'] - 1];
            $kt_cont = '注册会员赠送';
            
            if($money_b>0){
            
            $fck->query("update __TABLE__ set  agent_cash=agent_cash+" . $money_b . "  where `id`=" . $frs['id']);
            $fck->addencAdd($frs['id'], $frs['user_id'], $money_b, 19, 0, 0, 0, $kt_cont . '-' . C('agent_cash'));
            }
            // 创建店铺
            
            $seller = array();
            $seller['title'] = $frs['user_id'] . '店铺';
            
            // $data['seller_no'] = 'BD' . rand(1000000000, 2000000000);
            // $data['all_stock'] = $this->_post('stock');
            $seller['add_time'] = time();
            $seller['address'] = '';
            $seller['province'] = '';
            $seller['city'] = '';
            $seller['area'] = '';
            
            $seller['phone'] = $frs['user_id'];
            $seller['user_id'] = $frs['id'];
            // min_fan_money
            $seller['status'] = 0;
            $seller['img'] = '/Public/Images/logo.png';
            
            M('seller')->add($seller);
            
            // 升级检测
            init_user_level($frs['re_path']);
            unset($data, $fck);
            if ($_POST['is_mobile'] == 1) {
                
                $fee = M('fee');
                $fee_s = $fee->field('ios_url,android_url')->find();
                $data = array();
                
                $txt = '注册成功';
                
                $data['info'] = $txt;
                $data['msg'] = $txt;
                $data['url'] = 'user.html';
                $data['user'] = $frs;
                $data['ios_url'] = $fee_s['ios_url'];
                $data['app_url'] = $fee_s['android_url'];
                $data['status'] = 1;
                $this->ajaxReturn($data);
            } else {
                $txt = '注册成功';
                $url = U('YouZi/adminMenber');
                if ($_GET['type'] == 'boss') {
                    $txt = '股东注册成功';
                    $url = U('YouZi/adminMenber', array(
                        'type' => '1'
                    ));
                }
                if ($_GET['type'] == 'manager') {
                    $txt = '员工注册成功';
                    $url = U('YouZi/adminMenber', array(
                        'type' => '2'
                    ));
                }
                $this->success($txt, $url);
                exit();
            }
        } else {
            $this->error($fck->getError());
            unset($data, $fck);
            exit();
        }
    }

    /**
     * 注册完成
     * *
     */
    public function users_ok()
    {
        $this->_checkUser();
        $gourl = __APP__ . "/Reg/users/";
        if (! empty($_SESSION['new_user_reg_id'])) {
            
            $fck = M('fck');
            $fee_rs = M('fee')->find();
            
            $this->assign('s8', $fee_rs['s8']);
            $this->assign('alert_msg', $fee_rs['str28']);
            $this->assign('s17', $fee_rs['s17']);
            
            $myrs = $fck->where('id=' . $_SESSION['new_user_reg_id'])->find();
            $this->assign('myrs', $myrs);
            
            $this->assign('gourl', $gourl);
            unset($fck, $fee_rs);
            $this->display();
        } else {
            echo "<script>window.location='" . $gourl . "';</script>";
            exit();
        }
    }

    /**
     * 注册完成
     * *
     */
    public function scusers_ok()
    {
        $this->_checkUser();
        $gourl = __APP__;
        if (! empty($_SESSION['new_user_reg_id'])) {
            
            $fck = M('fck');
            $fee_rs = M('fee')->find();
            
            $this->assign('s8', $fee_rs['s8']);
            $this->assign('alert_msg', $fee_rs['str28']);
            $this->assign('s17', $fee_rs['s17']);
            
            $myrs = $fck->where('id=' . $_SESSION['new_user_reg_id'])->find();
            $this->assign('myrs', $myrs);
            
            $this->assign('gourl', $gourl);
            unset($fck, $fee_rs);
            $this->display();
        } else {
            echo "<script>window.location='" . $gourl . "';</script>";
            exit();
        }
    }

    /**
     * 会员注册
     * *
     */
    public function users_have($Urlsz = 0)
    {
        $fee = M('fee');
        $this->assign('myid', $_SESSION[C('USER_AUTH_KEY')]);
        
        $fee_s = $fee->field('*')->find();
        // 输出银行
        $bank = explode('|', $fee_s['str29']);
        $this->assign('bank', $bank);
        $this->display('users_have');
    }

    /**
     * 注册处理
     * *
     */
    public function usersmodefiy()
    {
        // $this->_checkUser();
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $fck = M('fck'); // 注册表
        
        $rs = $fck->field('is_pay,agent_cash')->find($id);
        $m = $rs['agent_cash'];
        
        $data = array(); // 创建数据对象
                         // 检测服务中心
        $shopid = trim($_POST['shopid']); // 所属服务中心帐号
        if (empty($shopid)) {
            $this->error('请输入服务中心编号');
            exit();
        }
        $smap = array();
        $smap['shop_code'] = $shopid;
        $smap['is_agent'] = array(
            'gt',
            1
        );
        $shop_rs = $fck->where($smap)
            ->field('id,user_id')
            ->find();
        if (! $shop_rs) {
            $this->error('没有该服务中心');
            exit();
        } else {
            $data['shop_id'] = $shop_rs['id']; // 隶属会员中心编号
            $data['shop_name'] = $shop_rs['shop_code']; // 隶属会员中心帐号
        }
        unset($smap, $shop_rs, $shopid);
        
        // 检测分享人
        $RID = trim($_POST['RID']); // 获取分享会员帐号
        $mapp = array();
        $mapp['user_id'] = $RID;
        $mapp['is_pay'] = array(
            'gt',
            0
        );
        $authInfoo = $fck->where($mapp)
            ->field('id,user_id,re_level,re_path')
            ->find();
        if ($authInfoo) {
            $newreid = $authInfoo['id'];
            $data['re_path'] = $authInfoo['re_path'] . $authInfoo['id'] . ','; // 分享路径
            $data['re_id'] = $authInfoo['id']; // 分享人ID
            $data['re_name'] = $authInfoo['user_id']; // 分享人帐号
            $data['re_level'] = $authInfoo['re_level'] + 1; // 代数(绝对层数)
        } else {
            $this->error('分享人不存在');
            exit();
        }
        unset($authInfoo, $mapp);
        
        // 检测上节点人
        $FID = trim($_POST['FID']); // 上节点帐号
        $mappp = array();
        $mappp['user_id'] = $FID;
        // $mappp['is_pay'] = array('gt',0);
        $authInfoo = $fck->where($mappp)
            ->field('id,p_path,p_level,user_id,is_pay,tp_path')
            ->find();
        if ($authInfoo) {
            $fatherispay = $authInfoo['is_pay'];
            $data['p_path'] = $authInfoo['p_path'] . $authInfoo['id'] . ','; // 绝对路径
            $data['father_id'] = $authInfoo['id']; // 上节点ID
            $data['father_name'] = $authInfoo['user_id']; // 上节点帐号
            $data['p_level'] = $authInfoo['p_level'] + 1; // 上节点ID
            $tp_path = $authInfoo['tp_path'];
        } else {
            $this->error('上级会员不存在');
            exit();
        }
        unset($authInfoo, $mappp);
        $TPL = (int) $_POST['TPL'];
        $where = array();
        $where['father_id'] = $data['father_id'];
        $where['treeplace'] = $TPL;
        $rs = $fck->where($where)
            ->field('id')
            ->find();
        if ($rs) {
            $this->error('该位置已经注册');
            exit();
        } else {
            $data['treeplace'] = $TPL;
            if (strlen($tp_path) == 0) {
                $data['tp_path'] = $TPL;
            } else {
                $data['tp_path'] = $tp_path . "," . $TPL;
            }
        }
        
        if ($fatherispay == 0 && $TPL > 0) {
            $this->error('接点人开通后才能在此位置注册');
            exit();
        }
        
        // $renn = $fck->where('re_id='.$data['re_id'].' and is_pay>0')->count();
        // if($renn<1){
        // $tjnn = $renn+1;
        // if($renn==0){
        // $oktp = 0;
        // $errtp = "左区";
        // }
        // $zz_id = $this->pd_left_us($data['re_id'],$oktp);
        // $zz_rs = $fck->where('id='.$zz_id)->field('id,user_id')->find();
        // if($zz_id!=$data['father_id']){
        // $this->error('分享第'.$tjnn.'人必须放在'.$zz_rs['user_id'].'的'.$errtp.'');
        // exit;
        // }
        // if($TPL!=$oktp){
        // $this->error('分享第'.$tjnn.'人必须放在'.$zz_rs['user_id'].'的'.$errtp.'');
        // exit;
        // }
        // }
        // unset($rs,$where,$TPL);
        
        $errmsg = "";
        if (empty($_POST['BankCard'])) {
            $errmsg .= "<li>银行卡号不能为空</li>";
        }
        
        if (empty($_POST['qq'])) {
            $errmsg .= "<li>请填写QQ号码</li>";
        }
        // if(empty($_POST['UserEmail'])){
        // $errmsg.="<li>请填写您的邮箱地址，找回密码时需使用</li>";
        // }
        
        $us_name = $_POST['us_name'];
        $us_address = $_POST['us_address'];
        $us_tel = $_POST['us_tel'];
        
        $this->assign('us_name', $_POST['us_name']);
        $this->assign('us_address', $_POST['us_address']);
        $this->assign('us_tel', $_POST['us_tel']);
        
        $s_err = "<ul>";
        $e_err = "</ul>";
        if (! empty($errmsg)) {
            $out_err = $s_err . $errmsg . $e_err;
            $this->error($out_err);
            exit();
        }
        
        $uLevel = $_POST['u_level'];
        $fee = M('fee')->find();
        $s = $fee['s9'];
        $s2 = explode('|', $fee['s2']);
        $s9 = explode('|', $fee['s9']);
        
        $F4 = $s2[$uLevel]; // 认购单数
        $ul = $s9[$uLevel];
        
        // $fck->query("update __TABLE__ set `re_nums`=re_nums+1 where `id`=".$newreid);
        $Money = explode('|', C('Member_Money')); // 注册金额数组
        
        $new_userid = $_SESSION['loginUseracc'];
        
        $data['id'] = $_SESSION[C('USER_AUTH_KEY')];
        $data['wenti'] = trim($_POST['wenti']); // 密保问题
        $data['wenti_dan'] = trim($_POST['wenti_dan']); // 密保答案
        
        $data['lang'] = $_POST['lang']; // 语言
        $data['countrys'] = $_POST['countrys']; // 国家
        
        $data['bank_name'] = $_POST['BankName']; // 银行名称
        $data['bank_card'] = $_POST['BankCard']; // 帐户卡号
        $data['nickname'] = $_SESSION['loginUseracc']; // $_POST['nickname']; //昵称
        $data['bank_province'] = $_POST['BankProvince']; // 省份
        $data['bank_city'] = $_POST['BankCity']; // 城市
        $data['bank_address'] = $_POST['BankAddress']; // 开户地址
        $data['user_post'] = $_POST['UserPost']; //
        $data['user_address'] = $_POST['UserAddress']; // 联系地址
        $data['email'] = $_POST['UserEmail']; // 电子邮箱
        $data['qq'] = $_POST['qq']; // qq
        $data['is_pay'] = 1; // 是否开通
        $data['pdt'] = time();
        
        $result = $fck->save($data);
        
        unset($data, $fck);
        if ($result) {
            
            M('fee')->query("update __TABLE__ set us_num=us_num+1");
            
            $_SESSION['new_user_reg_id'] = $result;
            
            echo "<script>window.location='" . __URL__ . "/scusers_ok/';</script>";
            exit();
        } else {
            $this->error('会员注册失败');
            exit();
        }
    }

    // 商城注册
    public function scus_reg()
    {
        $fck = M('fck');
        $fee = M('fee');
        $reid = (int) $_GET['rid'];
        
        $fee_rs = $fee->field('s2,s9,str21,str27,str29,str99')->find();
        
        $arr = array();
        $arr['UserID'] = $this->_getUserID();
        $this->assign('flist', $arr);
        
        $this->display();
    }

    // 前台注册处理
    public function scus_regAC()
    {
        $fck = M('fck'); // 注册表
        
        if (strlen($_POST['txtUserid']) < 1) {
            $this->error('账户名不能少');
            exit();
        }
        
        $fwhere = array(); // 检测帐号是否存在
        $fwhere['user_id'] = trim($_POST['txtUserid']);
        $frs = $fck->where($fwhere)
            ->field('id')
            ->find();
        if ($frs) {
            $this->error('该账户名已存在');
            exit();
        }
        $kk = stripos($fwhere['user_id'], '-');
        if ($kk) {
            $this->error('账户名中不能有扛(-)符号');
            exit();
        }
        unset($fwhere, $frs);
        
        $errmsg = "";
        $huhu = trim($_POST['txtname']);
        if (empty($huhu)) {
            $errmsg .= "<li>请填写姓名</li>";
        }
        if (empty($_POST['txtPhone'])) {
            $errmsg .= "<li>请填写电话号码</li>";
        }
        if (empty($_POST['txtCode'])) {
            $errmsg .= "<li>请填写身份证号码</li>";
        }
        if (! preg_match("/\d{17}[\d|X]|\d{15}/", $_POST['txtCode'])) {
            $errmsg .= "<li>身份证号码格式不正确</li>";
        }
        if (strlen($_POST['newPassword']) < 1 or strlen($_POST['newPassword']) > 16) {
            $this->error('密码应该是1-16位');
            exit();
        }
        
        $s_err = "<ul>";
        $e_err = "</ul>";
        if (! empty($errmsg)) {
            $out_err = $s_err . $errmsg . $e_err;
            $this->error($out_err);
            exit();
        }
        
        $pwdopen = substr($_POST['txtCode'], - 6);
        // echo $pwdopen;
        $new_userid = $_POST['txtUserid'];
        
        $data['user_id'] = $new_userid;
        $data['bind_account'] = '3333';
        $data['last_login_ip'] = ''; // 最后登录IP
        $data['verify'] = '0';
        $data['status'] = 1; // 状态(?)
        $data['type_id'] = '0';
        $data['last_login_time'] = time(); // 最后登录时间
        $data['login_count'] = 0; // 登录次数
        $data['info'] = '信息';
        $data['name'] = '名称';
        $data['password'] = md5(trim($_POST['newPassword'])); // 一级密码加密
        $data['pwd1'] = trim($_POST['newPassword']); // 一级密码不加密
        $data['passopen'] = md5(trim($pwdopen)); // 一级密码加密
        $data['pwd2'] = trim($pwdopen); // 一级密码不加密
        
        $data['user_name'] = $_POST['txtname']; // 姓名
        $data['nickname'] = $_POST['txtUserid']; // 昵称
        $data['user_code'] = $_POST['txtCode']; // 昵称
        $data['user_tel'] = $_POST['txtPhone']; // 联系电话
        $data['is_pay'] = 0; // 是否开通
        $data['rdt'] = time(); // 注册时间
        $data['u_level'] = 1; // 注册等级
        $data['cpzj'] = 0; // 注册金额
        $data['f4'] = 1; // 单量
        
        $result = $fck->add($data);
        
        unset($data, $fck);
        if ($result) {
            // 增加商城会员的数据
            $shopUser = D("ShopUser");
            $hyUser = M('fck');
            $hyUser->find($result);
            $shopUser->insertShopUser($hyUser);
            
            echo "<script>";
            echo "alert('恭喜您注册成功，您的账户编号：" . $new_userid . "，请及时开通正式会员');";
            echo "window.location='/y6815/shop';";
            echo "</script>";
            exit();
        } else {
            $this->error('会员注册失败');
            exit();
        }
    }

    // 前台注册
    public function us_reg()
    {
        $fck = M('fck');
        $fee = M('fee');
        $reid = (int) $_GET['rid'];
        
        $fee_rs = $fee->field('s2,s9,str21,str27,str29,str99')->find();
        $this->assign('fflv', $fee_rs['str21']);
        $this->assign('str27', $fee_rs['str27']);
        $s9 = $fee_rs['s9'];
        $s9 = explode('|', $s9);
        $this->assign('s9', $s9);
        $s2 = explode('|', $fee_rs['s2']);
        $this->assign('s2', $s2);
        $bank = explode('|', $fee_rs['str29']);
        $this->assign('bank', $bank);
        $wentilist = explode('|', $fee_rs['str99']);
        $this->assign('wentilist', $wentilist);
        
        $arr = array();
        $arr['UserID'] = $this->_getUserID();
        $this->assign('flist', $arr);
        
        // 检测分享人
        $where = array();
        $where['id'] = $reid;
        $where['is_pay'] = array(
            'gt',
            0
        );
        $field = 'id,user_id,nickname,us_img,is_agent,shop_name';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        if ($rs) {
            if (empty($rs['us_img'])) {
                $rs['us_img'] = "__PUBLIC__/Images/tirns.jpg";
            }
            if ($rs['is_agent'] == 2) {
                $this->assign('shopname', $rs['user_id']);
            } else {
                $shopname = 'admin';
                $this->assign('shopname', $shopname);
            }
            $this->assign('rs', $rs);
            $this->assign('reid', $rs['user_id']);
        } else {
            $shopname = 'admin';
            $this->assign('shopname', $shopname);
        }
        $plan = M('plan');
        $prs = $plan->find(4);
        $this->assign('prs', $prs);
        $this->display();
    }

    public function us_regAC()
    {
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $fck = M('fck'); // 注册表
        
        $rs = $fck->field('is_pay,agent_cash')->find($id);
        $m = $rs['agent_cash'];
        if ($rs['is_pay'] == 0) {
            $this->error('临时会员不能注册会员');
            exit();
        }
        $data = array(); // 创建数据对象
                         // 检测服务中心
        
        $smap = array();
        $smap['user_id'] = trim($this->_post('shopid'));
        $smap['is_agent'] = array(
            'gt',
            1
        );
        $shop_rs = $fck->where($smap)
            ->field('id,user_id')
            ->find();
        if (! $shop_rs) {
            $this->error('没有该服务中心');
            exit();
        } else {
            $data['shop_id'] = $shop_rs['id']; // 隶属会员中心编号
            $data['shop_name'] = $shop_rs['user_id']; // 隶属会员中心帐号
        }
        unset($smap, $shop_rs, $shopid);
        
        // 检测分享人
        $mapp = array();
        $mapp['user_id'] = trim($this->_post('RID'));
        $mapp['is_pay'] = array(
            'gt',
            0
        );
        $authInfoo = $fck->where($mapp)
            ->field('id,user_id,re_level,re_path')
            ->find();
        if ($authInfoo) {
            $newreid = $authInfoo['id'];
            $data['re_path'] = $authInfoo['re_path'] . $authInfoo['id'] . ','; // 分享路径
            $data['re_id'] = $authInfoo['id']; // 分享人ID
            $data['re_name'] = $authInfoo['user_id']; // 分享人帐号
            $data['re_level'] = $authInfoo['re_level'] + 1; // 代数(绝对层数)
        } else {
            $this->error('分享人不存在');
            exit();
        }
        unset($authInfoo, $mapp);
        
        // 检测上节点人
        // $FID = trim($this->_post('FID')); //上节点帐号
        // $mappp = array();
        // $mappp['user_id'] = $FID;
        // // $mappp['is_pay'] = array('gt',0);
        // $authInfoo = $fck->where($mappp)->field('id,p_path,p_level,user_id,is_pay,tp_path')->find();
        // if ($authInfoo){
        // $fatherispay = $authInfoo['is_pay'];
        // $data['p_path'] = $authInfoo['p_path'].$authInfoo['id'].','; //绝对路径
        // $data['father_id'] = $authInfoo['id']; //上节点ID
        // $data['father_name'] = $authInfoo['user_id']; //上节点帐号
        // $data['p_level'] = $authInfoo['p_level'] + 1; //上节点ID
        // $tp_path = $authInfoo['tp_path'];
        // }else{
        // $this->error('上级会员不存在');
        // exit;
        // }
        unset($authInfoo, $mappp);
        
        // $TPL = (int)$_POST['TPL'];
        // $where = array();
        // $where['father_id'] = $data['father_id'];
        // $where['treeplace'] = $TPL;
        // $rs = $fck->where($where)->field('id')->find();
        // if ($rs){
        // $this->error('该位置已经注册');
        // exit;
        // }else{
        // $data['treeplace'] = $TPL;
        // if(strlen($tp_path)==0){
        // $data['tp_path'] = $TPL;
        // }else{
        // $data['tp_path'] = $tp_path.",".$TPL;
        // }
        // }
        
        // if($fatherispay==0&&$TPL>0){
        // $this->error('接点人开通后才能在此位置注册');
        // exit;
        // }
        // $renn = $fck->where('re_id='.$data['re_id'].' and is_pay>0')->count();
        // if($renn<1){
        // $tjnn = $renn+1;
        // if($renn==0){
        // $oktp = 0;
        // $errtp = "左区";
        // }
        // $zz_id = $this->pd_left_us($data['re_id'],$oktp);
        // $zz_rs = $fck->where('id='.$zz_id)->field('id,user_id')->find();
        // if($zz_id!=$data['father_id']){
        // $this->error('分享第'.$tjnn.'人必须放在'.$zz_rs['user_id'].'的'.$errtp.'');
        // exit;
        // }
        // if($TPL!=$oktp){
        // $this->error('分享第'.$tjnn.'人必须放在'.$zz_rs['user_id'].'的'.$errtp.'');
        // exit;
        // }
        // }
        unset($rs, $where, $TPL);
        
        // $us_name = $_POST['us_name'];
        // $us_address = $_POST['us_address'];
        // $us_tel = $_POST['us_tel'];
        // if(empty($us_name)){
        // $errmsg.="<li>请输入收货人姓名</li>";
        // }
        // if(empty($us_address)){
        // $errmsg.="<li>请输入收货地址</li>";
        // }
        // if(empty($us_tel)){
        // $errmsg.="<li>请输入收货人电话</li>";
        // }
        
        // $this->assign('us_name',$_POST['us_name']);
        // $this->assign('us_address',$_POST['us_address']);
        // $this->assign('us_tel',$_POST['us_tel']);
        
        // $s_err = "<ul>";
        // $e_err = "</ul>";
        // if(!empty($errmsg)){
        // $out_err = $s_err.$errmsg.$e_err;
        // $this->error($out_err);
        // exit;
        // }
        
        $uLevel = $this->_post('u_level');
        $gtLevel = $this->_post('duixlx');
        $fee = M('fee')->find();
        $s2 = explode('|', $fee['s2']);
        $s9 = explode('|', $fee['s9']);
        $zsjf = $fee['s1'];
        $F4 = $s2[$uLevel - 1]; // 认购单数
        $ul = $s9[$uLevel - 1];
        
        // //======产品========
        // $product = M ('product');
        // $gouwu = M ('gouwu');
        // $ydate = time();
        // $cpid = $_POST['uid'];//所以产品的ID
        // if (empty($cpid)){
        // $this->error('请选择产品');
        // exit;
        // }
        // $pro_where = array();
        // $pro_where['id'] = array ('in',$cpid);
        // $pro_rs = $product->where($pro_where)->field('id,money,a_money,name')->select();
        // $cpmoney = 0;//产品总价
        // $txt = "";
        // foreach ($pro_rs as $pvo){
        // $aa = "shu".$pvo['id'];
        // $cc = (int)$_POST[$aa];
        // if ($cc >0) {
        // $cpmoney = $cpmoney + $pvo['money'] * $cc;
        // $txt .= $pvo['id'] .',';
        // }
        // }
        // unset($pro_rs);
        // if($cpmoney!=$ul){
        // $this->error('产品金额和级别对不上，请重新选择');
        // exit;
        // }
        // //======产品END=====
        
        $Money = explode('|', C('Member_Money')); // 注册金额数组
        
        $data['father_id'] = 0;
        $data['father_name'] = '0';
        $data['treeplace'] = 0;
        $data['user_id'] = $this->_post('user_id'); // $fee['str17'].
        $data['user_name'] = $this->_post('username'); // 姓名
                                                       // $data['bind_account'] = '3333';
                                                       // $data['last_login_ip'] = ''; //最后登录IP
                                                       // $data['verify'] = '0';
                                                       // $data['status'] = 1; //状态(?)
                                                       // $data['type_id'] = '0';
        $data['last_login_time'] = time(); // 最后登录时间
        $data['new_login_time'] = time(); // 最后登录时间
        $data['login_count'] = 0; // 登录次数
                                  // $data['info'] = '信息';
        $data['name'] = trim($this->_post('name'));
        $data['password'] = trim($this->_post('pwd')); // 一级密码加密
        $data['passwordcom'] = $this->_post('repwd');
        $data['passopen'] = trim($this->_post('pwd1')); // 二级密码加密
        $data['passopencom'] = trim($this->_post('repwd1')); // 二级密码加密
                                                             // $data['pwd1'] = trim($_POST['Password']); //一级密码不加密
                                                             // $data['pwd2'] = trim($_POST['PassOpen']); //二级密码不加密
        
        $data['bank_name'] = $this->_post('bank'); // 银行名称
        $data['bank_card'] = $this->_post('bank_card'); // 帐户卡号
        $data['bank_province'] = $this->_post('BankProvince', true, '请填写'); // 省份
        $data['bank_city'] = $this->_post('BankCity', true, '请填写'); // 城市
        $data['bank_address'] = $this->_post('bank_address', true, '请填写'); // 开户地址
        $data['user_post'] = $this->_post('user_post', true, '请填写'); //
        $data['user_code'] = $this->_post('user_code', true, '请填写'); // 身份证号码
        $data['nickname'] = $this->_post('nickname', true, '请填写');
        $data['user_address'] = $this->_post('user_address', true, '请填写'); // 联系地址
        $data['email'] = $this->_post('email', true, '请填写'); // 电子邮箱
        $data['qq'] = $this->_post('qq', true, '请填写'); // qq
        $data['user_tel'] = $this->_post('user_tel', true, '请填写'); // 联系电话
        $data['is_pay'] = 1; // 是否开通
        $data['pdt'] = time(); // 开通时间
        $data['u_level'] = $uLevel; // 注册等级
        $data['get_level'] = $gtLevel;
        $data['agent_cf'] = $zsjf;
        $data['cpzj'] = $ul; // 注册金额
        $data['f4'] = $F4; // 单量
        $data['is_tj'] = I('regcp', 0);
        $data['wenti'] = trim($this->_post('wenti'));
        $data['wenti_dan'] = trim($this->_post('daan'));
        $fck = D('Fck');
        if ($fck->create($data)) {
            $fck->add();
            
            unset($data, $fck);
            $this->success('会员注册成功', U('User/myTeam'));
            exit();
        } else {
            $this->error($fck->getError());
            unset($data, $fck);
            exit();
        }
    }

    // 生成用户账号
    private function _getUserID()
    {
        $fck = M('fck');
        // $fee = M('fee');
        // $fee_rs = $fee->field('us_num')->find(1);
        // $us_num = $fee_rs['us_num'];
        // $first_n = 800000000;
        // $mynn = $first_n+$us_num;
        
        $mynn = '80' . rand(10000, 99999);
        
        // if($us_num<10){
        // $mynn = "00000".$us_num;
        // }elseif($us_num<100){
        // $mynn = "0000".$us_num;
        // }elseif($us_num<1000){
        // $mynn = "000".$us_num;
        // }elseif($us_num<10000){
        // $mynn = "00".$us_num;
        // }elseif($us_num<100000){
        // $mynn = "0".$us_num;
        // }else{
        // $mynn = $us_num;
        // }
        $fwhere['user_id'] = $mynn;
        $frss = $fck->where($fwhere)
            ->field('id')
            ->find();
        if ($frss) {
            return $this->_getUserID();
        } else {
            unset($fck, $fee);
            return $mynn;
        }
    }

    // 判断最左区
    public function pd_left_us($uid, &$tp)
    {
        $fck = M('fck');
        $c_l = $fck->where('father_id=' . $uid . ' and treeplace=' . $tp . '')
            ->field('id')
            ->find();
        if ($c_l) {
            $n_id = $c_l['id'];
            $tp = 0;
            $ren_id = $this->pd_left_us($n_id, $tp);
        } else {
            $ren_id = $uid;
        }
        unset($fck, $c_l);
        return $ren_id;
    }

    //
    public function find_agent()
    {
        $fck = M('fck');
        $where = "is_agent=2 and is_pay>0";
        $s_echo = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tab1"><tr><td>';
        $e_echo = '</td></tr></table>';
        $m_echo = "";
        $c_l = $fck->where($where)
            ->field('user_id,user_name,shop_a')
            ->select();
        foreach ($c_l as $ll) {
            $m_echo .= "<li><b>" . $ll['user_id'] . "</b>(" . $ll['user_name'] . ")<br>" . $ll['shop_a'] . "</li>";
        }
        unset($fck, $c_l);
        echo $s_echo . $m_echo . $e_echo;
    }

    // 找回密码1
    public function find_pw()
    {
        $_SESSION['us_openemail'] = "";
        $this->display('find_pw');
    }

    // 找回密码2
    public function find_pw_s()
    {
        if (empty($_SESSION['us_openemail'])) {
            if (empty($_POST['us_name']) && empty($_POST['us_email'])) {
                $_SESSION = array();
                $this->display('Public:LinkOut');
                return;
            }
            $ptname = $_POST['us_name'];
            $us_email = $_POST['us_email'];
            $fck = M('fck');
            $rs = $fck->where("user_id='" . $ptname . "'")
                ->field('id,email,user_id,user_name,pwd1,pwd2')
                ->find();
            if ($rs == false) {
                $errarry['err'] = '<font color=red>注：找不到此用户账号</font>';
                $this->assign('errarry', $errarry);
                $this->display('find_pw');
            } else {
                if ($us_email != $rs['email']) {
                    $errarry['err'] = '<font color=red>注：邮箱验证失败</font>';
                    $this->assign('errarry', $errarry);
                    $this->display('find_pw');
                } else {
                    
                    $passarr = array();
                    $passarr[0] = $rs['pwd1'];
                    $passarr[1] = $rs['pwd2'];
                    
                    $title = '感谢您使用密码找回';
                    
                    $body = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-size:12px; line-height:24px;\">";
                    $body = $body . "<tr>";
                    $body = $body . "<td height=\"30\">尊敬的客户:" . $rs['user_name'] . "</td>";
                    $body = $body . "</tr>";
                    $body = $body . "<tr>";
                    $body = $body . "<td height=\"30\">你的账户编号:" . $rs['user_id'] . "</td>";
                    $body = $body . "</tr>";
                    $body = $body . "<tr>";
                    $body = $body . "<td height=\"30\">一级密码为:" . $rs['pwd1'] . "</td>";
                    $body = $body . "</tr>";
                    $body = $body . "<tr>";
                    $body = $body . "<td height=\"30\">二级密码为:" . $rs['pwd2'] . "</td>";
                    $body = $body . "</tr>";
                    $body = $body . "此邮件由系统发出，请勿直接回复。<br>";
                    $body = $body . "</td></tr>";
                    $body = $body . "<tr>";
                    $body = $body . "<td height=\"30\" align=\"right\">" . date("Y-m-d H:i:s") . "</td>";
                    $body = $body . "</tr>";
                    $body = $body . "</table>";
                    
                    $this->send_email($us_email, $title, $body);
                    
                    $_SESSION['us_openemail'] = $us_email;
                    $this->find_pw_e($us_email);
                }
            }
        } else {
            $us_email = $_SESSION['us_openemail'];
            $this->find_pw_e($us_email);
        }
    }

    // 找回密码3
    public function find_pw_e($us_email)
    {
        $this->assign('myask', $us_email);
        $this->display('find_pw_s');
    }

    public function send_email($useremail, $title = '', $body = '')
    {
        require_once "stemp/class.phpmailer.php";
        require_once "stemp/class.smtp.php";
        
        $arra = array();
        
        $mail = new PHPMailer();
        $mail->IsSMTP(); // send via SMTP
        $mail->Host = "smtp.163.com"; // SMTP servers
        $mail->SMTPAuth = true; // turn on SMTP authentication
        $mail->Username = "yuyangtaoyecn"; // SMTP username 注意：普通邮件认证不需要加 @域名
        $mail->Password = "yuyangtaoyecn666"; // SMTP password
        $mail->From = "yuyangtaoyecn@163.com"; // 发件人邮箱
        $mail->FromName = "传奇梦"; // 发件人
        $mail->CharSet = "utf-8"; // 这里指定字符集
        $mail->Encoding = "base64";
        $mail->AddAddress("" . $useremail . "", "" . $useremail . ""); // 收件人邮箱和姓名
                                                                       // $mail->AddAddress("119515301@qq.com","text"); // 收件人邮箱和姓名
        $mail->AddReplyTo("" . $useremail . "", "163.com");
        $mail->IsHTML(true); // send as HTML
        $mail->Subject = $title; // 邮件主题
        $mail->Body = "" . $body . ""; // 邮件内容
        $mail->AltBody = "text/html";
        // $mail->Send();
        
        if (! $mail->Send()) {
            echo "Message could not be sent. <p>";
            echo "Mailer Error: " . $mail->ErrorInfo;
            exit();
        }
        // echo "Message has been sent";
    }

    public function check_phone()
    {
        $user_tel = I('post.user_tel');
        $fee = M('fee');
        $fee_s = $fee->field('ios_url,android_url')->find();
        $fck = M('fck');
        $rs = $fck->where("user_id='" . $user_tel . "'")
            ->field('id,email,user_id,user_name,pwd1,pwd2')
            ->find();
        if ($rs != NULL) {
            $data = array(
                'status' => 0,
                'info' => '手机号已存在，是否跳转下载页面',
                'go_down' => 1,
                'ios_url' => $fee_s['ios_url'],
                'app_url' => $fee_s['android_url']
            );
            $this->ajaxReturn($data);
        }
    }

    public function fasongyanzhengma()
    {
        $content = I('post.content');
        $user_tel = I('post.user_tel', 15052939438);
        $type = I('post.type');
        
        $phonenumber = $user_tel;
        if (preg_match("/^1[345678]{1}\d{9}$/", $phonenumber)) {} else {
            $data = array(
                'status' => 0,
                'info' => '请输入正确的手机号'
            );
            $this->ajaxReturn($data);
        }
        
        $fck = M('fck');
        $rs = $fck->where("user_id='" . $user_tel . "'")
            ->field('id,email,user_id,user_name,pwd1,pwd2')
            ->find();
        
        $fee = M('fee');
        $fee_s = $fee->field('ios_url,android_url')->find();
        
        if ($type == 'register') {
            if ($rs != NULL) {
                // $data = array(
                // 'status' => 0,
                // 'info' => '您已注册过，是否跳转下载页面',
                // 'go_down' => 1,
                // 'ios_url' => $fee_s['ios_url'],
                // 'app_url' => $fee_s['android_url']
                // );
                // $this->ajaxReturn($data);
            }
        }
        if ($type == 'login') {
            if ($rs == NULL) {
                $data = array(
                    'status' => 0,
                    'info' => '此号码未注册'
                );
                $this->ajaxReturn($data);
            }
        }
        // 检查手机
        if (empty($user_tel)) {
            $data = array(
                'status' => 0,
                'info' => '发送失败，请填写手机号码！'
            );
            $this->ajaxReturn($data);
        }
        $TemplateCode = C('ali_sms_tid');
        if ($type == 'tiqu') {
            $TemplateCode = C('ali_sms_tid_change_wx');
        }
        
        $result = send_verify_sms_code($user_tel, $TemplateCode);
        if ($result != "success") {
            
            $this->ajaxReturn(json_decode($result));
        }
        
        $data = array(
            'status' => 1,
            'info' => '手机验证码发送成功',
            'time' => C('regsmsexpired')
        );
        $this->ajaxReturn($data);
    }

    public function send_change_msg()
    {
        $content = I('post.content');
        $user_tel = I('post.user_tel', 15052939438);
        $type = I('post.type');
        
        $phonenumber = $user_tel;
        if (preg_match("/^1[345678]{1}\d{9}$/", $phonenumber)) {} else {
            $data = array(
                'status' => 0,
                'info' => '请输入正确的手机号'
            );
            $this->ajaxReturn($data);
        }
        
        $fck = M('fck');
        $rs = $fck->where("user_id='" . $user_tel . "'")
            ->field('id,email,user_id,user_name,pwd1,pwd2')
            ->find();
        
        $fee = M('fee');
        $fee_s = $fee->field('ios_url,android_url')->find();
        
        // 检查手机
        if (empty($user_tel)) {
            $data = array(
                'status' => 0,
                'info' => '发送失败，请填写手机号码！'
            );
            $this->ajaxReturn($data);
        }
        $TemplateCode = C('ali_sms_tid_change_bank');
        $result = send_verify_sms_code($user_tel, $TemplateCode);
        if ($result != "success") {
            
            $this->ajaxReturn(json_decode($result));
        }
        
        $data = array(
            'status' => 1,
            'info' => '手机验证码发送成功',
            'time' => C('regsmsexpired')
        );
        $this->ajaxReturn($data);
    }

    public function test_fasongyanzhengma()
    {
        $content = I('post.content');
        $user_tel = I('post.user_tel', 15052939438);
        $type = I('post.type');
        
        $result = test_sms_code($user_tel);
        if ($result != "success") {
            
            $this->ajaxReturn(json_decode($result));
        }
        
        $data = array(
            'status' => 1,
            'info' => '手机验证码发送成功',
            'time' => C('regsmsexpired')
        );
        $this->ajaxReturn($data);
    }

    public function union_frm()
    {
        $RID = $_GET['id'];
        $eweima_time = $_GET['eweima_time'];
        $this->assign('code', "http://" . $_SERVER['SERVER_NAME'] . "/adm.php/Reg/union_frm.html?id=" . $RID . '&&eweima_time=' . $eweima_time);
        if ($_REQUEST["code"] == '' || $_REQUEST["code"] == null) {
            $res = $this->wechatWebAuth("http://" . $_SERVER['SERVER_NAME'] . "/adm.php/Reg/union_frm.html?id=" . $RID . '&&eweima_time=' . $eweima_time);
        }
        $res = $this->wxGetTokenWithCode($_REQUEST["code"]);
        
        $this->assign('over_time', 0);
        $res = $this->get_user_info();
        if ($eweima_time < time()) {
            $this->assign('over_time', time());
            $this->display();
            return;
        }
        
        $fck = M('fck');
        
        $where = array();
        $where['id'] = $RID;
        $field = 'user_id,is_agent,re_path,agent_cash,shop_name,headimgurl,id';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        $this->assign('rid', $RID);
        $this->assign('r_user', $rs);
        
        $fee = M('fee');
        $fee_s = $fee->field('*')->find();
        $this->assign('ios_url', $fee_s['ios_url']);
        $this->assign('app_url', $fee_s['android_url']);
        $this->assign('gzh_res', $res);
        
        M('fck')->where($where)->setField('wx_nickname', $res['nickname']);
        M('fck')->where($where)->setField('weixinlogo', $res['headimgurl']);
        M('fck')->where($where)->setField('gzh_openid', $res['openid']);
        M('fck')->where($where)->setField('gzh_unionid', $res['unionid']);
        
        $this->assign('res', $res);
        $this->display();
    }
}
?>
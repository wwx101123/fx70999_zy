<?php

// 注册模块
class TransferAction extends CommonAction
{

    public function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        header("Content-Type:text/html; charset=utf-8");
        $this->_inject_check(0); // 调用过滤函数
        $this->_Config_name(); // 调用参数
        $this->_checkUser();
        $this->check_us_gq();
        // $this->_inject_check(1);//调用过滤函数
        $is_mobile = $_POST['is_mobile'];
        if ($is_mobile == 1) {
            $_SESSION[C('USER_AUTH_KEY')] = (int) $_POST['user_id'];
            $_SESSION['loginUseracc'] = $_POST['user_name'];
        }
    }

    public function cody()
    {
        // ===================================二级验证
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
        $cody = M('cody');
        $list = $cody->where("c_id=$UrlID")
            ->field('c_id')
            ->find();
        if ($list) {
            $this->assign('vo', $list);
            $this->display('Public:cody');
            exit();
        } else {
            $this->error('二级密码错误!');
            exit();
        }
    }

    public function codys()
    {
        // =============================二级验证后调转页面
        $Urlsz = (int) $_POST['Urlsz'];
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
                ->field('id,is_agent')
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
                $_SESSION['Urlszpass'] = 'MyssFenYingTao';
                $bUrl = __URL__ . '/transferMoney'; // 货币支付
                $this->_boxx($bUrl);
                break;
            case 2:
                $_SESSION['UrlPTPass'] = 'MyssTransfer';
                $bUrl = __URL__ . '/adminTransferMoney'; // 货币支付
                $this->_boxx($bUrl);
                break;
            default:
                $this->error('二级密码错误!');
                exit();
        }
    }

    public function getUserName()
    {
        $user_id = $this->_get('userid');
        $rs = M('fck')->where(array(
            'user_id' => $user_id
        ))->getField('user_name');
        if (! $rs) {
            $this->ajaxError('会员不存在');
        } else {
            $this->ajaxSuccess($rs);
        }
    }

    public function transferTob()
    {
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $user = M('fck')->where(array(
            'id' => $id
        ))
            ->field('agent_use,agent_kt,agent_cf,agent_xf,agent_gp,agent_cash')
            ->find();
        $this->assign('rs', $user);
        $this->display();
    }

    // =============================================购物券转帐(会员之间的购物券转帐)
    public function transferMoney($Urlsz = 0)
    {
        $page_index = I('page_index', 1);
        $is_mobile = I('is_mobile', 0);
        $user_id = I('user_id', 0);
        $size = I('page_num', 10);
        $zhuanj = M('zhuanj');
        $map['t.in_uid|t.out_uid'] = $_SESSION[C('USER_AUTH_KEY')];
        if ($is_mobile == 1) {
            $map['T.out_uid'] = $user_id;
        }
        $time1 = $this->_post('time1') ? $this->_post('time1') : 0;
        $time2 = $this->_post('time2') ? $this->_post('time2') : "2037-12-1";
        
        $map['t.rdt'] = array(
            array(
                'egt',
                strtotime($time1)
            ),
            array(
                'elt',
                strtotime($time2)
            )
        );
        
        $field = 't.*,FROM_UNIXTIME(t.pdt,"%m月%d日 %H:%i:%S") AS time_str,g.avatar ';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $zhuanj->where($map)->count(); // 总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $size = C('ONE_PAGE_RE');
        if ($is_mobile == 1) {
            $p = $page_index;
            $size = I('page_num', 10);
        }
        $list = $zhuanj->alias('t')
            ->join("xt_fck AS g ON   g.id = t.in_uid     ", 'left')
            ->where($map)
            ->field($field)
            ->order('t.id desc')
            ->page($p . ',' . $size)
            ->select();
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        $fck = M('fck');
        $where = array();
        $where['id'] = $_SESSION[C('USER_AUTH_KEY')];
        $field = '*';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        $this->assign('rs', $rs);
        
        if ($this->_post('time1')) {
            $this->assign('time1', $time1);
        }
        if ($this->_post('time2')) {
            $this->assign('time2', $time2);
        }
        if ($is_mobile == 1) {
            
            $data = array();
            $data['data'] = $list;
            $data['current_count'] = count($list);
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display('transferMoney');
            return;
        }
    }

    public function transferMoneyAC()
    {
        $UserID = I('tousername', 'jqr13156552029'); // 转入会员帐号(进帐的用户帐号)
        $ePoints = I('price', 1); // 转入金额
                                  // $content = $_POST['content']; //转帐说明
        $select = I('zztype', 2); // 转帐类型
        $password = I('password'); // 转帐类型
        $user_id = I('user_id', 1); // 转帐类型
        $now = time();
        $now = strtotime(date("Y-m-d H:i:s", $now), '-20 second');
        $chongzhi = M('fck');
        $zhuanj = M('zhuanj');
        $task_robot_time = $zhuanj->where('out_uid=' . $user_id)
            ->order('id desc')
            ->find();
        if ($task_robot_time != null) {
            if ($task_robot_time != null && $now < strtotime("+20 second", $task_robot_time['pdt'])) {
                $this->ajaxError('转换过于频繁!');
                exit();
            }
        }
        $fck = M('fck');
        $where = array();
        $where['id'] = $user_id;
        
        $f = $fck->where($where)
            ->field('user_id,passopen')
            ->find();
        if (md5($password) != $f['passopen']) {
            // $this->error('二级密码错误!');
            // exit();
        }
        
        if ($select == 2 || $select == 3 || $select == 4 || $select == 6 || $select == 7 || $select == 8 || $select == 9 || $select == 10 || $select == 11 || $select == 13)
            // $UserID = $_SESSION['loginUseracc'];
            
            $fck = M('fck');
        if (! $fck->autoCheckToken($_POST)) {
            $this->ajaxError('页面过期，请刷新页面！');
            exit();
        }
        if (empty($ePoints) || ! is_numeric($ePoints)) {
            $this->ajaxError('输入不能为空!');
            exit();
        }
        if ($ePoints <= 0) {
            $this->ajaxError('输入的金额有误!');
            exit();
        }
        
        switch ($select) {
            case '0':
                if ($UserID == $f['user_id']) {
                    $this->ajaxError('不能转给自己!');
                    exit();
                }
                break;
            case '1':
                if ($UserID == $f['user_id']) {
                    $this->ajaxError('不能转给自己');
                    exit();
                }
                break;
            case '2':
                if ($UserID == $f['user_id']) {
                    $this->ajaxError('不能转给自己');
                    exit();
                }
                break;
            case '3':
                if ($UserID != $f['user_id']) {
                    $this->ajaxError('只能转自己');
                    exit();
                }
                break;
            case '4':
                if ($UserID != $f['user_id']) {
                    $this->ajaxError('只能转自己');
                    exit();
                }
                break;
            
            case '5':
                if ($UserID == $f['user_id']) {
                    $this->ajaxError('不能转给自己');
                    exit();
                }
                break;
            case '6':
                if ($UserID != $f['user_id']) {
                    $this->ajaxError('只能转自己');
                    exit();
                }
                break;
            case '7':
                if ($UserID != $f['user_id']) {
                    $this->ajaxError('只能转自己');
                    exit();
                }
                break;
            case '8':
                if ($UserID != $f['user_id']) {
                    $this->ajaxError('只能转自己');
                    exit();
                }
                break;
            case '9':
                if ($UserID != $f['user_id']) {
                    $this->ajaxError('只能转自己');
                    exit();
                }
                break;
            case '10':
                if ($UserID != $f['user_id']) {
                    $this->ajaxError('只能转自己');
                    exit();
                }
                break;
            case '11':
                if ($UserID != $f['user_id']) {
                    $this->ajaxError('只能转自己');
                    exit();
                }
                break;
            
            case '13':
                if ($UserID != $f['user_id']) {
                    $this->ajaxError('只能转自己');
                    exit();
                }
                break;
            case '14':
                if ($UserID != $f['user_id']) {
                    $this->ajaxError('只能转自己');
                    exit();
                }
                break;
            case '15':
                if ($UserID != $f['user_id']) {
                    $this->ajaxError('只能转自己');
                    exit();
                }
                break;
            default:
                $this->ajaxError('系统繁忙');
                exit();
                break;
        }
        $this->_transferMoneyConfirm($UserID, $ePoints, $content, $select);
    }

    private function _transferMoneyConfirm($UserID = '0', $ePoints = 0, $content = null, $select = 0)
    {
        $fck = M('fck');
        $where = array();
        $ID = $_SESSION[C('USER_AUTH_KEY')]; // 登录会员AutoId
        $inUserID = $_SESSION['loginUseracc']; // 登录的会员帐号 user_id
                                               // 转出
        $history = M('history'); // 明细表
        $zhuanj = M('zhuanj'); // 转帐表
        
        $myww = array();
        $myww['id'] = array(
            'eq',
            $ID
        );
        $mmrs = $fck->where($myww)->find();
        $mmid = $mmrs['id'];
        $mmisagent = $mmrs['is_agent'];
        $agentuse = $mmrs['agent_use'];
        $agentcash = $mmrs['agent_cash'];
        $agentkt = $mmrs['agent_kt'];
        $agentgp = $mmrs['agent_gp'];
        $agent_cf = $mmrs['agent_cf'];
        $buy_point = $mmrs['buy_point'];
        $ssb = $mmrs['ssb'];
        $live_gupiao = $mmrs['live_gupiao'];
        if ($mmid == 1) {
            $mmisagent = 0;
        }
        
        // 转入会员
        $fck_where = array();
        $fck_where['user_id'] = $UserID; // strtolower($UserID);
        $field = "id,user_id,is_agent,p_path,user_tel";
        $vo = $fck->where($fck_where)
            ->field($field)
            ->find(); // 找出转入会员记录
        
        if ($vo == NULL) {
            $this->ajaxError('转入会员不存在!');
            exit();
        }
        
        // if($select==0){
        // $ks = strpos($vo['p_path'],','.$ID.',');
        // if(!is_numeric($ks)){
        // $this->error('现金点值只能向下转帐!');
        // exit;
        // }
        // }
        
        $b_agent = $vo['is_agent'];
        
        $fee_rs = M('fee')->find();
        $str3 = $fee_rs['str3'];
        $str16 = explode('|', $fee_rs['str16']);
        $cur_one_price = $fee_rs['gp_one'];
        
        $is_agentuse_agentkt = $fee_rs['is_agentuse_agentkt'];
        $is_agentcash = $fee_rs['is_agent_cash'];
        $hB = $str16[1]; // 倍数
        $mmB = $str16[0]; // 最低额
        
        if ($ePoints < $mmB) {
            $this->ajaxError('支付最低额度必须为 ' . $mmB . ' ！');
            exit();
        }
        if ($ePoints % $hB) {
            $this->ajaxError('额度必须为 ' . $hB . ' 的整数倍!');
            exit();
        }
        
        if ($select == 0) {
            if ($agentkt < $ePoints) {
                $this->ajaxError('激活积分余额不足!');
                exit();
            }
        }
        
        if ($select == 1) {
            if ($agentcash < $ePoints) {
                $this->ajaxError(C('agent_cash') . '不足!');
                exit();
            }
        }
        
        if ($select == 2) {
            if ($ssb < $ePoints) {
                $this->ajaxError(C('ssb') . '余额不足!');
                exit();
            }
        }
        
        if ($select == 3) {
            if ($agentuse < $ePoints) {
                $this->ajaxError('配送积分余额不足!');
                exit();
            }
        }
        if ($select == 4) {
            if ($agent_cf < $ePoints) {
                $this->ajaxError('幸运积分余额不足!');
                exit();
            }
        }
        if ($select == 5) {
            if ($mmrs['user_tel'] != $vo['user_tel']) {
                $this->ajaxError('手机号不一样,不可支付!');
                exit();
            }
            
            if ($buy_point < $ePoints) {
                $this->ajaxError('购物积分余额不足!');
                exit();
            }
        }
        if ($select == 6) {
            if ($agentuse < $ePoints) {
                $this->ajaxError('现金点值余额不足!');
                exit();
            }
        }
        if ($select == 7) {
            if ($agentuse < $ePoints) {
                $this->ajaxError('现金点值余额不足!');
                exit();
            }
        }
        if ($select == 8) {
            if ($live_gupiao < $ePoints) {
                $this->ajaxError('股票点值余额不足!');
                exit();
            }
        }
        if ($select == 9) {
            if ($live_gupiao < $ePoints) {
                $this->ajaxError('股票点值余额不足!');
                exit();
            }
        }
        
        if ($select == 10) {
            if ($buy_point < $ePoints) {
                $this->ajaxError('购物积分余额不足!');
                exit();
            }
        }
        if ($select == 11) {
            if ($agentuse < $ePoints) {
                $this->ajaxError('现金点值余额不足!');
                exit();
            }
        }
        if ($select == 13) {
            if ($agentuse < $ePoints) {
                $this->ajaxError(C('agent_use') . '不足!');
                exit();
            }
            if ($fee_rs['all_ssb_money'] < $ePoints) {
                $this->ajaxError('总后台' . C('ssb') . '不足,请联系管理员!');
                exit();
            }
        }
        if ($select == 14) {
            
            if ($is_agentuse_agentkt == 0) {
                $this->ajaxError('禁止' . C('agent_use') . '转' . C('limit_money'));
                exit();
            }
            
            if ($agentuse < $ePoints) {
                $this->ajaxError(C('agent_use') . '不足!');
                exit();
            }
        }
        if ($select == 15) {
            
            if ($is_agentcash == 0) {
                $this->ajaxError('禁止' . C('agent_cash') . '转' . C('limit_money'));
                exit();
            }
            
            if ($agentcash < $ePoints) {
                $this->ajaxError(C('agent_cash') . '余额不足!');
                exit();
            }
        }
        $history->startTrans(); // 开始事物处理
        if ($select == 0) {
            $zz_content = C('agent_kt') . " 转给 其他会员";
            $fck->execute("update `xt_fck` Set `agent_kt`=agent_kt-" . $ePoints . " where `id`=" . $ID);
            $fck->execute("update `xt_fck` Set `agent_kt`=agent_kt+" . $ePoints . " where `id`=" . $vo['id']);
        }
        if ($select == 1) {
            $zz_content = C('agent_cash') . " 转给 其他会员";
            // $fck->execute("update `xt_fck` Set `agent_cash`=agent_cash-" . $ePoints . " where `id`=" . $ID);
            // $fck->execute("update `xt_fck` Set `agent_cash`=agent_cash+" . $ePoints . " where `id`=" . $vo['id']);
            $value = 1;
        }
        if ($select == 2) {
            $zz_content = C('ssb') . " 转给 其他会员";
            $value = 1;
        }
        if ($select == 3) {
            $zz_content = "现金点值 转到 幸运积分";
            $fck->execute("update `xt_fck` Set `agent_cf`=agent_cf+" . $ePoints . " ,`agent_use`=agent_use-" . $ePoints . " where `id`=" . $ID);
        }
        if ($select == 4) {
            $zz_content = "幸运积分 转到 购物积分";
            $fck->execute("update `xt_fck` Set `buy_point`=buy_point+" . $ePoints . " ,`agent_cf`=agent_cf-" . $ePoints . " where `id`=" . $ID);
        }
        if ($select == 5) {
            $zz_content = "购物积分 转给 其他会员";
            $fck->execute("update `xt_fck` Set `buy_point`=buy_point-" . $ePoints . "  where `id`=" . $ID);
            $fck->execute("update `xt_fck` Set `buy_point`=buy_point+" . $ePoints . " where `id`=" . $vo['id']);
        }
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('s16')->find();
        $fee_s7 = explode('|', $fee_rs['s16']);
        $percent = 0;
        $real_epoint = 0;
        if ($select == 6) {
            $zz_content = "现金点值 转到 购物积分";
            
            $fck->execute("update `xt_fck` Set  `agent_use`=agent_use-" . $ePoints . " where `id`=" . $ID);
            $percent = explode(':', $fee_s7[0]);
            $value = $percent[1] / $percent[0];
        }
        if ($select == 7) {
            $percent = explode(':', $fee_s7[0]);
            $value = $percent[1] / $percent[0];
            
            $zz_content = "现金点值 转到 激活点值";
            $fck->execute("update `xt_fck` Set  `agent_use`=agent_use-" . $ePoints . " where `id`=" . $ID);
        }
        if ($select == 8) {
            $percent = explode(':', $fee_s7[1]);
            $value = $percent[1] / $percent[0];
            $zz_content = "股票点值 转到 双闪币";
            $fck->execute("update `xt_fck` Set `live_gupiao`=live_gupiao-" . $ePoints . " where `id`=" . $ID);
        }
        
        if ($select == 9) {
            $percent = explode(':', $fee_s7[2]);
            $value = $percent[1] / $percent[0];
            $zz_content = "股票点值 转 购物积分";
            $fck->execute("update `xt_fck` Set `live_gupiao`=live_gupiao-" . $ePoints . " where `id`=" . $ID);
        }
        
        if ($select == 10) {
            $percent = explode(':', $fee_s7[3]);
            $value = $percent[1] / $percent[0];
            $zz_content = "购物积分 转 股票点值";
            $fck->execute("update `xt_fck` Set `buy_point`=buy_point-" . $ePoints . " where `id`=" . $ID);
        }
        if ($select == 11) {
            $percent = explode(':', $fee_s7[0]);
            $value = $percent[1] / $percent[0];
            $zz_content = "现金点值  转 股票点值";
            $fck->execute("update `xt_fck` Set `agent_use`=agent_use-" . $ePoints . " where `id`=" . $ID);
        }
        $fee_rs = $fee->field('ssb_money')->find();
        $ssb_money = $fee_rs['ssb_money'];
        if ($select == 13) {
            
            $percent = 0;
            $percent = explode(':', $fee_s7[0]);
            $value = $percent[1] / $percent[0];
            $zz_content = C('agent_use') . "  转  " . C('ssb');
            $fck->execute("update `xt_fck` Set `agent_use`=agent_use-" . $ePoints . " where `id`=" . $ID);
            
            $fee = M('fee');
            $fee->execute("update `xt_fee` Set `all_ssb_money`=all_ssb_money-" . $ePoints . " where `id`=1");
        }
        if ($select == 14) {
            
            $percent = 0;
            $percent = explode(':', $fee_s7[1]);
            $value = $percent[1] / $percent[0];
            $zz_content = C('agent_use') . "  转  " . C('limit_money');
            $fck->execute("update `xt_fck` Set `agent_use`=agent_use-" . $ePoints . " where `id`=" . $ID);
        }
        if ($select == 15) {
            
            $percent = 0;
            $percent = explode(':', $fee_s7[2]);
            $value = $percent[1] / $percent[0];
            $zz_content = C('agent_cash') . "  转  " . C('limit_money');
            $fck->execute("update `xt_fck` Set `agent_cash`=agent_cash-" . $ePoints . " where `id`=" . $ID);
        }
        $real_epoint = $ePoints * $value;
        $nowdate = time();
        $data = array();
        $data['uid'] = $ID; // 转出会员ID
        $data['user_id'] = $UserID;
        $data['did'] = $vo['id']; // 转入会员ID
        $data['user_did'] = $vo['user_id'];
        $data['action_type'] = 20; // 转入还是转出
        $data['pdt'] = $nowdate; // 转帐时间
        $data['epoints'] = $ePoints; // 进出金额
        $data['allp'] = 0;
        $data['bz'] = $zz_content; // 备注
        $data['type'] = 1; // 1转帐
        $history->create();
        // $rs2 = $history->add($data);
        unset($data);
        // 支付表
        $data = array();
        $data['in_uid'] = $vo['id']; // 转入会员ID
        $data['out_uid'] = $ID;
        $data['in_userid'] = $vo['user_id']; // 转入会员的登录帐号
        $data['out_userid'] = $inUserID;
        $data['epoint'] = $ePoints; // 进出金额
        $data['rdt'] = $nowdate; // 转帐时间
        $data['status'] = 1; // 1待审核
        if ($select == 9) {
            $data['sm'] = $cur_one_price; // 转帐说明
        }
        if ($select == 13) {
            $data['sm'] = $ssb_money; // 转帐说明
        }
        $remark = I('remark');
        $data['sm'] = $remark;
        
        $data['real_epoint'] = $real_epoint;
        $data['type'] = $select;
        $data['percent'] = $value * 100;
        
        $zhuanj->create();
        $rs4 = $zhuanj->add($data);
        unset($data);
        
        // 无错误提交数据
        if ($rs4) {
            $history->commit(); // 提交事务
            $this->_adminCurrencyTransferMoneyOpen($rs4);
        } else {
            $history->rollback(); // 事务回滚：
            $this->ajaxError('输入数据错误！');
        }
    }

    public function adminTransferMoney()
    {
        $zhuanj = M('zhuanj');
        
        $type = $_GET['type'];
        $UserID = $_REQUEST['UserID'];
        $bi_agent = I('get.bi', 'adminTransferMoney');
        $this->assign('bi_agenttran', $bi_agent . 'tran');
        $map = ' 1=1  and (type=2 ) ';
        if (! empty($UserID)) {
            import("@.ORG.KuoZhan"); // 导入扩展类
            $KuoZhan = new KuoZhan();
            if ($KuoZhan->is_utf8($UserID) == false) {
                $UserID = iconv('GB2312', 'UTF-8', $UserID);
            }
            unset($KuoZhan);
            // $map['in_userid'] = array('like',"%".$UserID."%");
            $map = $map . " AND (t.in_userid like '%" . $UserID . "%') or (t.out_userid  like '%" . $UserID . "%') ";
            $UserID = urlencode($UserID);
        }
        $map = $map . ' and t.real_epoint>0   ';
        $username = $_GET['username'];
        $this->assign('username', $username);
        $this->assign('type', $type);
        
        // 转入方查询
        if ($type == 1) {
            $map = $map . ' and T.in_userid like "%' . $username . '%"   ';
        }
        // 转出方查询
        if ($type == 2) {
            
            $map = $map . ' and g.user_id like "%' . $username . '%"   ';
        }
        
        if (! empty($bi_agent)) {
            
            if ($bi_agent == 'agent_cash') {
                $map = $map . ' and  T.type=15 ';
            }
            
            if ($bi_agent == 'agent_use') {
                $map = $map . ' and  T.type=14 ';
            }
        }
        
        import("@.ORG.ZQPage"); // 导入分页类
        $count = $zhuanj->alias('t')
            ->join("xt_fck AS g ON   g.id = t.out_uid ", 'LEFT')
            ->where($map)
            ->count(); // 总页数
        $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
        $page_where = 'UserID=' . $UserID; // 分页条件
        $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $list = $zhuanj->alias('t')
            ->join("xt_fck AS g ON   g.id = t.out_uid ", 'LEFT')
            ->where($map)
            ->field('T.*')
            ->order('t.rdt desc,t.id desc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        
        foreach ($list as $key => $value) {
            $user = M('fck')->field('user_name')
                ->where('id=' . $value['in_uid'])
                ->find();
            $list[$key]['user_name'] = $user['user_name'];
            
            $user = M('fck')->field('user_id')
                ->where('id=' . $value['out_uid'])
                ->find();
            $list[$key]['out_userid'] = $user['user_id'];
        }
        
        $this->assign('list', $list);
        
        $this->display('adminTransferMoney');
    }

    public function adminTransferMoney2()
    {
        $is_mobile = I('is_mobile');
        if ($is_mobile == 0) {
            $zhuanj = M('zhuanj');
            
            $UserID = $_REQUEST['UserID'];
            $map = ' 1=1  ';
            if (! empty($UserID)) {
                import("@.ORG.KuoZhan"); // 导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false) {
                    $UserID = iconv('GB2312', 'UTF-8', $UserID);
                }
                unset($KuoZhan);
                // $map['in_userid'] = array('like',"%".$UserID."%");
                $map = $map . " AND (in_userid like '%" . $UserID . "%') or (out_userid  like '%" . $UserID . "%') ";
                $UserID = urlencode($UserID);
            }
            $map = $map . '   ';
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $zhuanj->where($map)->count(); // 总页数
            $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
            $page_where = 'UserID=' . $UserID; // 分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $list = $zhuanj->where($map)
                ->field('*')
                ->order('rdt desc,id desc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            
            foreach ($list as $key => $value) {
                $user = M('fck')->field('user_name')
                    ->where('id=' . $value['in_uid'])
                    ->find();
                $list[$key]['user_name'] = $user['user_name'];
                $list[$key]['percent'] = (1 - $value['percent'] / 100) * $value['epoint'];
                $user = M('fck')->field('user_name')
                    ->where('id=' . $value['out_uid'])
                    ->find();
                $list[$key]['out_userid'] = $user['user_name'];
            }
            
            $this->assign('list', $list);
            
            $this->display('adminTransferMoney2');
        } else {
            
            $page_index = I('page_index', 1);
            $size = I('page_num', 10);
            $is_mobile = I('is_mobile');
            $user_id = I('user_id');
            $type = I('type');
            
            $str = "";
            
            $list = M('zhuanj')->field(" FROM_UNIXTIME(pdt, '%Y-%m-%d') as time ,FROM_UNIXTIME(pdt, '%Y-%m') as month ,FROM_UNIXTIME(pdt, '%Y年%m月') as month_str ,FROM_UNIXTIME(pdt, '%d') as day_time ,sum(epoint) AS all_epoints ")
            ->where('  (in_uid=' . $user_id.' OR out_uid='.$user_id.')' . $str)
                ->group("FROM_UNIXTIME(pdt, '%Y-%m') ")
                ->order(' pdt desc ')
                ->page($page_index . ',' . $size)
                ->select();
            
            foreach ($list as $key => $value) {
                
                $map['_string'] = "  (in_uid=" . $user_id." OR out_uid=".$user_id.")  and   FROM_UNIXTIME(pdt, '%Y-%m')  = '" . $value['month'] . "'" . $str;
                
                $list1 = M('zhuanj')->where($map)
                    ->field(' *,FROM_UNIXTIME(pdt,"%m月%d日 %H:%i:%S") AS time_str')
                    ->order(' pdt desc ')
                    ->select();
                
                foreach ($list1 as $key1 => $value1) {
                    IF($user_id==$value1['in_uid']){
                        $user = M('fck')->field('user_name')
                        ->where('id=' . $value1['out_uid'])
                        ->find();
                       
                        $list1[$key1]['bz'] = '转自'. $user['user_name'];
                    }ELSE{
                        
                        $list1[$key1]['bz'] = '转给'. $value1['in_userid'];
                    }
                }
                
                $list[$key]['list'] = $list1;
                $map['_string'] = "  (in_uid=" . $user_id." )  and   FROM_UNIXTIME(pdt, '%Y-%m')  = '" . $value['month'] . "' and epoint>0 " . $str;
                // 统计收入
                $sum1 = M('zhuanj')->where($map)->sum('epoint');
                if ($sum1 == NULL) {
                    $sum1 = 0;
                }
                $map['_string'] = " ( out_uid=".$user_id.")  and   FROM_UNIXTIME(pdt, '%Y-%m')  = '" . $value['month'] . "' and epoint>0 " . $str;
                // 统计支出
                $sum2 = M('zhuanj')->where($map)->sum('epoint');
                if ($sum2 == NULL) {
                    $sum2 = 0;
                }
                $list[$key]['sum1'] = $sum1;
                $list[$key]['sum2'] = $sum2;
            }
            
            if ($is_mobile == 1) {
                
                $data = array();
                $data['data'] = $list;
                $data['current_count'] = count($list);
                $data['status'] = 1;
                $this->ajaxReturn($data);
            }
        }
    }

    public function adminCurrencyTransferMoneyAC()
    {
        $this->_Admin_checkUser();
        $action = $this->_get('action');
        $PTid = $this->_get('id');
        $fck = M('fck');
        if (! $fck->autoCheckToken($_POST)) {
            $this->error('页面过期，请刷新页面！');
            exit();
        }
        if (! isset($PTid) || empty($PTid)) {
            $this->ajaxError('请选择！');
        }
        switch ($action) {
            case 'confirm':
                $this->_adminCurrencyTransferMoneyOpen($PTid);
                break;
            case 'deny':
                $this->_adminCurrencyTransferMoneyDel($PTid);
                break;
            default:
                $this->ajaxError('系统繁忙！');
                break;
        }
    }

    private function _adminCurrencyTransferMoneyOpen($PTid)
    {
        $chongzhi = M('zhuanj');
        $fck = D('Fck'); //
        $where = array();
        $where['status'] = 1;
        $where['id'] = $PTid;
        $rs = $chongzhi->where($where)->find();
        $history = M('history');
        $fee_rs = M('fee')->find();
        $str3 = $fee_rs['str3'];
        $str16 = explode('|', $fee_rs['str16']);
        $cur_one_price = $fee_rs['gp_one'];
        
        $is_agentuse_agentkt = $fee_rs['is_agentuse_agentkt'];
        $fck_where['id'] = $rs['in_uid'];
        
        $stype = $rs['type'];
        $rsss = $fck->where($fck_where)
            ->field('id,user_id,is_agent')
            ->find();
        if ($stype == 6) {
            $zz_content = "现金点值 转到 购物积分";
        } elseif ($stype == 2) {
            
            // $zz_content = '收到'..'的支付【'.C('agent_use').'】';
        } elseif ($stype == 7) {
            
            $zz_content = "现金点值 转到 激活积分";
        } elseif ($stype == 8) {
            $zz_content = "股票点值 转到 双闪币";
        } elseif ($stype == 9) {
            $zz_content = "股票点值 转 购物积分";
        } elseif ($stype == 10) {
            $zz_content = "购物积分 转 股票点值";
        } elseif ($stype == 11) {
            $zz_content = "现金点值 转 股票点值";
        } elseif ($stype == 13) {
            $zz_content = C('agent_use') . " 转 " . C('ssb');
        } elseif ($stype == 14) {
            
            $zz_content = C('agent_use') . " 转 " . C('limit_money');
        } elseif ($stype == 15) {
            
            $zz_content = C('agent_cash') . " 转 " . C('limit_money');
        }
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('s16')->find();
        $fee_s7 = explode('|', $fee_rs['s16']);
        
        if ($rsss) {
            // 开始事务处理
            $fck->startTrans();
            
            $fck_where['id'] = $rs['out_uid'];
            
            $stype = $rs['type'];
            $out_rsss = $fck->where($fck_where)
                ->field('id,user_id,is_agent')
                ->find();
            $fck_where['id'] = $rs['in_uid'];
            
            $inrsss = $fck->where($fck_where)
                ->field('id,user_id,is_agent')
                ->find();
            $zz_content = '支付成功,' . $inrsss['user_id'] . '收款【' . C('ssb') . '】';
            // $zz_content = '收到' . $out_rsss['user_id'] . '的支付【' . C('agent_use') . '】';
            
            // 明细表
            // $data['uid'] = $rsss['id'];
            // $data['user_id'] = $rsss['user_id'];
            // $data['action_type'] = 20;
            // $data['pdt'] = time();
            // $data['epoints'] = $rs['real_epoint'];
            // $data['did'] = $rsss['id']; // 转入会员ID
            // $data['allp'] = 0;
            // $data['bz'] = $zz_content;
            // $data['type'] = 1;
            // $history->create();
            // $rs1 = $history->add($data);
            
            $fck = D('Fck');
            $rs1 = $fck->addencAdd($rsss['id'], $out_rsss['user_id'], ($rs['real_epoint']), 19, 0, 0, 0, $zz_content);
            file_put_contents("real_epoint.txt", $rs1);
            if ($rs1 > 0) {
                // 提交事务
                if ($stype == 6) {
                    
                    $fck->execute("update `xt_fck` Set `buy_point`=buy_point+" . $rs['real_epoint'] . " where `id`=" . $rs['out_uid']);
                } elseif ($stype == 2) {
                    $fck_where['id'] = $rs['in_uid'];
                    
                    $stype = $rs['type'];
                    $inrsss = $fck->where($fck_where)
                        ->field('id,user_id,is_agent')
                        ->find();
                    $zz_content = '支付成功,' . $inrsss['user_id'] . '收款';
                    $fck = D('Fck');
                    // $news_id = $fck->addencAdd($out_rsss['id'], $out_rsss['user_id'], (- $rs['real_epoint']), 19, 0, 0, 0, $zz_content.C('agent_use'));
                    
                    $fck->execute("update `xt_fck` Set `ssb`=ssb-" . $rs['real_epoint'] . " where `id`=" . $rs['out_uid']);
                    $fck->execute("update `xt_fck` Set `ssb`=ssb+" . $rs['real_epoint'] . " where `id`=" . $rs['in_uid']);
                } elseif ($stype == 1) {
                    $fck_where['id'] = $rs['in_uid'];
                    
                    $stype = $rs['type'];
                    $inrsss = $fck->where($fck_where)
                        ->field('id,user_id,is_agent')
                        ->find();
                    $zz_content = '支付成功,' . $inrsss['user_id'] . '收款';
                    $fck = D('Fck');
                    // $news_id = $fck->addencAdd($out_rsss['id'], $out_rsss['user_id'], (- $rs['real_epoint']), 19, 0, 0, 0, $zz_content.C('agent_cash'));
                    
                    $fck->execute("update `xt_fck` Set `agent_cash`=agent_cash-" . $rs['real_epoint'] . " where `id`=" . $rs['out_uid']);
                    $fck->execute("update `xt_fck` Set `agent_cash`=agent_cash+" . $rs['real_epoint'] . " where `id`=" . $rs['in_uid']);
                } elseif ($stype == 7) {
                    
                    $fck->execute("UPDATE __TABLE__ set `agent_kt`=agent_kt+" . $rs['real_epoint'] . " where `id`=" . $rs['out_uid']);
                } elseif ($stype == 8) {
                    $fck->execute("UPDATE __TABLE__ set `ssb`=ssb+" . $rs['real_epoint'] . "  where `id`=" . $rs['out_uid']);
                } elseif ($stype == 9) {
                    $fck->execute("UPDATE __TABLE__ set `buy_point`=buy_point+" . $rs['real_epoint'] . "  where `id`=" . $rs['out_uid']);
                } elseif ($stype == 10) {
                    $fck->execute("UPDATE __TABLE__ set `live_gupiao`=live_gupiao+" . $rs['real_epoint'] . " where `id`=" . $rs['out_uid']);
                } elseif ($stype == 11) {
                    $fck->execute("UPDATE __TABLE__ set `live_gupiao`=live_gupiao+" . $rs['real_epoint'] . " where `id`=" . $rs['out_uid']);
                } elseif ($stype == 13) {
                    $fck->execute("UPDATE __TABLE__ set `ssb`=ssb+" . $rs['real_epoint'] . " where `id`=" . $rs['out_uid']);
                } elseif ($stype == 14) {
                    $fck->execute("UPDATE __TABLE__ set `limit_money`=limit_money+" . $rs['real_epoint'] . " where `id`=" . $rs['out_uid']);
                } elseif ($stype == 15) {
                    $fck->execute("UPDATE __TABLE__ set `limit_money`=limit_money+" . $rs['real_epoint'] . " where `id`=" . $rs['out_uid']);
                }
                
                $chongzhi->execute("UPDATE __TABLE__ set `status`=0,`pdt`=" . time() . " where `id`=" . $rs['id']);
                $fck->commit();
                // unset($chongzhi, $fck, $where, $rs, $fck_where, $nowdate, $history, $data);
                $this->ajaxSuccess('完成支付！');
            } else {
                
                $fck->rollback();
                $this->ajaxError('确认失败！');
            }
        }
    }

    private function _adminCurrencyTransferMoneyDel($PTid)
    {
        $chongzhi = M('zhuanj');
        
        $fck = D('Fck'); //
        $where = array();
        $where['id'] = $PTid;
        $rs = $chongzhi->where($where)->find();
        $stype = $rs['type'];
        if ($stype == 6) {
            
            $fck->execute("update `xt_fck` Set `agent_use`=agent_use+" . $rs['epoint'] . "   where `id`=" . $rs['out_uid']);
        } elseif ($stype == 7) {
            $fck->execute("UPDATE __TABLE__ set `agent_use`=agent_use+" . $rs['epoint'] . "  where `id`=" . $rs['out_uid']);
        } elseif ($stype == 8) {
            $fck->execute("UPDATE __TABLE__ set `live_gupiao`=live_gupiao+" . $rs['epoint'] . "  where `id`=" . $rs['out_uid']);
        }
        
        $rs = $chongzhi->where($where)->setField('status', 2);
        if ($rs) {
            $this->ajaxSuccess('拒绝成功');
        } else {
            $this->ajaxError('系统繁忙！');
            exit();
        }
    }
}
?>
<?php

class RechargeAction extends CommonAction
{

    public function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->_inject_check(0); // 调用过滤函数
        $this->_Config_name(); // 调用参数
        $this->_checkUser();
    }
    
    // 会员货币充值
    public function currencyRecharge()
    {
        $chongzhi = M('chongzhi');
        $fck = M('fck');
        $map['uid'] = $_SESSION[C('USER_AUTH_KEY')];
        
        $time1 = $this->_post('time1') ? $this->_post('time1') : 0;
        $time2 = $this->_post('time2') ? $this->_post('time2') : "2037-12-1";
        
        $map['rdt'] = array(
            array(
                'egt',
                strtotime($time1)
            ),
            array(
                'elt',
                strtotime($time2)
            )
        );
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $chongzhi->where($map)->count(); // 总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $chongzhi->where($map)
            ->field($field)
            ->order('id desc')
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->select();
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        $where = array();
        $fwhere['id'] = $_SESSION[C('USER_AUTH_KEY')];
        
        $frs = $fck->where($fwhere)
            ->field($field)
            ->find();
        $this->assign('frs', $frs);
        
        if ($this->_post('time1')) {
            $this->assign('time1', $time1);
        }
        if ($this->_post('time2')) {
            $this->assign('time2', $time2);
        }
        
        $fee = M('fee')->where('id=1')->getField('str20');
        $str21 = M('fee')->where('id=1')->getField('str21');
        $str22 = M('fee')->where('id=1')->getField('str22');
        
        $this->assign('config', $fee);
        $this->assign('str21', $str21);
        $this->assign('str22', $str22);
        $str29 = M('fee')->where('id=1')->getField('str29');
        // 输出银行
        $bank_list = explode('|', $str29);
        $this->assign('bank_list', $bank_list);
        
        $bankcard = M('fee')->where('id=1')->getField('bankcard');
        // 输出银行
        $bankcard = explode('|', $bankcard);
        $this->assign('bankcard', $bankcard);
        
        $bankusername = M('fee')->where('id=1')->getField('bankusername');
        // 输出银行
        $bankusername = explode('|', $bankusername);
        $this->assign('bankusername', $bankusername);
        
        $this->display('currencyRecharge');
    }

    /**
     * 会员确认充值
     *
     * @return [type] 异步请求
     */
    public function currencyRechargeAC()
    {
        $fck = M('fck');
        $ID = $_SESSION[C('USER_AUTH_KEY')];
        $rs = $fck->field('is_pay,user_id')->find($ID);
        if ($rs['is_pay'] == 0) {
            $this->error('临时会员不能充值！');
            exit();
        }
        $inUserID = $rs['user_id'];
        
        $user_id = trim($this->_post('tousername'));
        $ePoints = trim($this->_post('price'));
        $time = trim($this->_post('time'));
        $type = trim($this->_post('type'));
        $chongzhi = M('chongzhi');
        if (! $chongzhi->autoCheckToken($_POST)) {
            $this->error('页面过期，请刷新页面！');
            exit();
        }
        if (empty($ePoints) || ! is_numeric($ePoints)) {
            $this->error('金额不能为空!');
            exit();
        }
        if (strlen($ePoints) > 9) {
            $this->error('金额太大!');
            exit();
        }
        if ($ePoints <= 0) {
            $this->error('金额格式不对!');
            exit();
        }
        if (! ($type == 0 || $type == 1)) {
            $this->ajaxError('系统繁忙！');
        }
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $where = array();
        $where['uid'] = $id;
        $where['is_pay'] = 0;
        $where['stype'] = $type;
        $field1 = 'id';
        $vo3 = $chongzhi->where($where)
            ->field($field1)
            ->find();
        if ($vo3) {
            $this->error('上次充值还没通过审核!');
            exit();
        }
        
        // 开始事务处理
        $chongzhi->startTrans();
        
        // $nowdate = strtotime(date('c'));
        $nowdate = strtotime($time);
        
        $data = array();
        $data['uid'] = $id;
        $data['user_id'] = $user_id;
        $data['huikuan'] = $ePoints;
        $data['zhuanghao'] = 0;
        $data['rdt'] = $nowdate;
        $data['epoint'] = $ePoints;
        $data['is_pay'] = 0;
        $data['stype'] = $type;
        
        $rs2 = $chongzhi->add($data);
        // echo $chongzhi->_sql();
        unset($data, $id);
        if ($rs2) {
            // 提交事务
            $chongzhi->commit();
            $this->ajaxSuccess('充值申请成功，请等待后台审核！');
            exit();
        } else {
            // 事务回滚：
            $chongzhi->rollback();
            $this->ajaxError('充值失败');
            exit();
        }
    }

    /**
     * 管理员充值管理
     *
     * @return [type] [description]
     */
    public function adminPingyi()
    {
        $this->_Admin_checkUser();
        $chongzhi = M('pingyi');
        $UserID = $_REQUEST['UserID'];
        if (! empty($UserID)) {
            $UserID = strtolower($UserID);
            // $map['user_id'] = array('like',"%".$UserID."%");
            $map['user_id'] = array(
                'eq',
                $UserID
            );
        }
        
        // =====================分页开始=================
        import("@.ORG.Page"); // 导入分页类
        $count = $chongzhi->where($map)->count(); // 总页数
        $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $chongzhi->where($map)
            ->field($field)
            ->order('id desc')
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->select();
        
        foreach ($list as $k => $item) {
            $list[$k]['add_time_str'] = date('Y-m-d H:i:s', $list[$k]['add_time']);
        }
        $this->assign('list', $list); // 数据输出到模板
        
        $this->display('adminPingyi');
    }

    public function adminPingyiAC()
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
                $this->_adminPingyiOpen($PTid);
                break;
            
            default:
                $this->ajaxError('系统繁忙！');
                break;
        }
    }

    private function _adminPingyiOpen($PTid)
    {
        $chongzhi = M('pingyi');
        $fck = D('Fck'); //
        $where = array();
        $where['id'] = $PTid;
        $rs = $chongzhi->where($where)->find();
        if ($rs['status'] == 1) {
            
            $chongzhi->where($where)->setField('status', 0);
            $chongzhi->where($where)->setField('check_time', time());
            
            $user = M('fck')->where('id=' . $rs['uid'])->find();
            
            $fck->where(array(
                'id' => $user['id']
            ))->setField('pdt', time());
            
            $money_a = $rs['out_money'] - $rs['extra_limit_money'] - $rs['extra_limit_money1'] / 3;
            if ($money_a <= 0) {
                $money_a = 0;
            }
            $fck->where(array(
                'id' => $user['id']
            ))->setInc('cpzj', $money_a);
            $fck->where(array(
                'id' => $user['id']
            ))->setInc('pingyi_money', $money_a);
            if ($money_a > 0) {
                // $kt_cont = '释放平移额度';
                // $fck->addencAdd($user['id'], $user['user_id'], - $money_a, 19, 0, 0, 0, $kt_cont . '-' . C('agent_kt'));
            }
            $money_b = $rs['out_money'] * 3;
            $limit_money = $money_b;
            
            $fck->where(array(
                'id' => $user['id']
            ))->setInc('limit_money', $limit_money);
            $kt_cont = '增加释放额度';
            $fck->addencAdd($user['id'], $user['user_id'], $limit_money, 19, 0, 0, 0, $kt_cont . '-' . C('limit_money'));
            
            $fck->where(array(
                'id' => $rs['id']
            ))->setField('is_pingyi', 1);
            
            $rs = $fck->where('id=' . $user['id'])->find();
            
            $fee = M('fee');
            $fee_rs = $fee->field('s1,s2,s9,s10,str6,cjbs')->find();
            $s1 = explode('|', $fee_rs['s1']);
            $s2 = explode('|', $fee_rs['s2']);
            $s9 = explode('|', $fee_rs['s9']);
            
            foreach ($s9 as $k => $v) {
                if ($v == ($limit_money / 3)) {
                    $fck->where(array(
                        'id' => $rs['id']
                    ))->setField('get_level', ($k + 1));
                    $fck->where(array(
                        'id' => $rs['id']
                    ))->setField('u_level', ($k + 1));
                }
            }
            $rs = $fck->field('id,user_id,register_type')
                ->where('id=' . $user['id'])
                ->find();
            user_award($rs, $money_a);
            $this->ajaxSuccess('确认平移！');
        } else {
            $this->ajaxError('系统繁忙！');
            exit();
        }
    }

    /**
     * 管理员充值管理
     *
     * @return [type] [description]
     */
    public function adminCurrencyRecharge()
    {
        $this->_Admin_checkUser();
        $chongzhi = M('chongzhi');
        $UserID = $_REQUEST['UserID'];
        if (! empty($UserID)) {
            // $map['user_id'] = array('like',"%".$UserID."%");
            
            $map = ' (user_id like "%' . $UserID . '%" OR user_name like "%' . $UserID . '%")    ';
        }
        
        $sdata = strtotime($_REQUEST['sNowDate']);
        $edata = strtotime($_REQUEST['endNowDate']);
        
        if (! empty($sdata) && empty($edata)) {
            $map['pdt'] = array(
                'gt',
                $sdata
            );
        }
        
        if (! empty($edata) && empty($sdata)) {
            $enddata = $edata + 24 * 3600 - 1;
            $map['pdt'] = array(
                'elt',
                $enddata
            );
        }
        
        if (! empty($sdata) && ! empty($edata)) {
            $enddatas = $edata + 24 * 3600 - 1;
            $map['_string'] = 'pdt >= ' . $sdata . ' and pdt <= ' . $enddatas;
        }
        
        // =====================分页开始=================
        import("@.ORG.Page"); // 导入分页类
        $count = $chongzhi->where($map)->count(); // 总页数
        $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $chongzhi->where($map)
            ->field($field)
            ->order('id desc')
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->select();
        
        foreach ($list as $k => $item) {
            $list[$k]['img_url'] = str_replace("8088/", "8088/fx70999/main/", $list[$k]['img_url']);
            $user = M('fck')->field('user_name')
                ->where('id=' . $item['uid'])
                ->find();
            $list[$k]['user_name'] = $user['user_name'];
        }
        $this->assign('list', $list); // 数据输出到模板
        
        $all_money = $chongzhi->

        order('id desc')->

        sum('epoint');
        $this->assign('all_money', $all_money);
        
        // 所有现金点值总计
        $agent_use_money = $chongzhi->

        where(' stype=0')
            ->order('id desc')
            ->sum('epoint');
        if ($agent_use_money == null) {
            $agent_use_money = 0;
        }
        $this->assign('agent_use_money', $agent_use_money);
        // 所有激活点值总计
        $agent_kt_money = $chongzhi->

        where(' stype=2')
            ->order('id desc')
            ->sum('epoint');
        if ($agent_kt_money == null) {
            $agent_kt_money = 0;
        }
        $this->assign('agent_kt_money', $agent_kt_money);
        
        // 所有双闪币总计
        $ssb_money = $chongzhi->

        where(' stype=7')
            ->order('id desc')
            ->sum('epoint');
        if ($ssb_money == null) {
            $ssb_money = 0;
        }
        $this->assign('ssb_money', $ssb_money);
        
        $limit_money_money = $chongzhi->

        where(' stype=8')
            ->order('id desc')
            ->sum('epoint');
        if ($limit_money_money == null) {
            $limit_money_money = 0;
        }
        $this->assign('limit_money_money', $limit_money_money);
        
        $this->display('adminCurrencyRecharge');
    }

    public function adminCurrencyRechargeAC()
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
                $this->_adminCurrencyRechargeOpen($PTid);
                break;
            case 'deny':
                $this->_adminCurrencyRechargeDel($PTid);
                break;
            default:
                $this->ajaxError('系统繁忙！');
                break;
        }
    }

    public function adminCurrencyRechargeSSBAdd()
    {
        $this->_Admin_checkUser();
        $fck = M('fck');
        if (! $fck->autoCheckToken($_POST)) {
            $this->error('页面过期，请刷新页面！');
            exit();
        }
        $UserID = $this->_post('username');
        // $UserID = strtolower($UserID);
        $percent = $this->_post('percent');
        $usertype = $this->_post('usertype');
        
        $list = M('fck')->where('out_money>0 and out_level=' . $usertype)
            ->field('id,user_id,get_level,out_money,out_level')
            ->select();
        
        $stype = $_POST['stype'];
        $ids = '0';
        foreach ($list as $k => $item) {
            
            $ids = $ids . ',' . $item['id'];
        }
        $epoint = $item['out_money'] * $percent;
        
        $fck->execute("UPDATE __TABLE__ set `ssb`=ssb+" . $epoint . ",`cz_epoint`=cz_epoint+" . $epoint . " where `id`    in   (" . $ids . ")");
        
        // foreach ($list as $k => $item) {
        // $frs = $item;
        // $ePoints = $percent * $frs['out_money'];
        
        // $chongzhi = M('chongzhi');
        // $data = array();
        // $data['uid'] = $frs['id'];
        // $data['user_id'] = $frs['user_id'];
        // $data['rdt'] = time();
        // $data['epoint'] = $ePoints;
        // $data['is_pay'] = 0;
        // $data['stype'] = $stype;
        // $result = $chongzhi->add($data);
        // $rearray[] = $result;
        // $chongzhi = M('chongzhi');
        // $where = array();
        // $where['is_pay'] = 0;
        // $where['id'] = $result;
        // $rs = $chongzhi->where($where)->find();
        // // 明细表
        // $data['uid'] = $rs['uid'];
        // $data['user_id'] = $rs['user_id'];
        // $data['action_type'] = 21;
        // $data['pdt'] = time();
        // $data['epoints'] = $rs['epoint'];
        // $data['did'] = 0;
        // $data['allp'] = 0;
        // $data['bz'] = '会员充值';
        // $data['type'] = $rs['stype'];
        // $history = M('history');
        // $history->create();
        // $rs1 = $history->add($data);
        // if ($rs1) {
        // // 提交事务
        // if ($stype == 7) {
        // $fck->execute("UPDATE __TABLE__ set `ssb`=ssb+" . $rs['epoint'] . ",`cz_epoint`=cz_epoint+" . $rs['epoint'] . " where `id`=" . $rs['uid']);
        // }
        
        // $chongzhi->execute("UPDATE __TABLE__ set `is_pay`=1,`pdt`=" . time() . " where `id`=" . $rs['id']);
        // $fck->commit();
        // }
        // }
        unset($chongzhi, $fck, $where, $rs, $fck_where, $nowdate, $history, $data);
        $this->ajaxSuccess('确认充值！');
    }

    public function adminCurrencyRechargeAdd()
    {
        $this->_Admin_checkUser();
        $fck = M('fck');
        if (! $fck->autoCheckToken($_POST)) {
            $this->error('页面过期，请刷新页面！');
            exit();
        }
        $UserID = $this->_post('username');
        // $UserID = strtolower($UserID);
        $ePoints = $this->_post('money');
        $content = $this->_post('memo');
        $stype = $this->_post('stype', true, '0');
        if (is_numeric($ePoints) == false) {
            $this->error('金额错误，请重新输入！');
            exit();
        }
        if (! empty($UserID) && ! empty($ePoints)) {
            $where = array();
            $where['user_id'] = $UserID;
//             $where['is_pay'] = array(
//                 'gt',
//                 0
//             );
            $frs = $fck->where($where)
                ->field('id,nickname,is_agent,user_id,user_name')
                ->find();
            if ($frs) {
                $chongzhi = M('chongzhi');
                $data = array();
                $data['uid'] = $frs['id'];
                $data['user_id'] = $frs['user_id'];
                $data['user_name'] = $frs['user_name'];
                $data['rdt'] = time();
                $data['epoint'] = $ePoints;
                $data['is_pay'] = 0;
                $data['stype'] = $stype;
                $data['content'] = $content;
                $data['percent'] = 0;
                $data['real_epoint'] = $ePoints;
                $add_user_id = $_SESSION[C('USER_AUTH_KEY')];
                $data['add_user_id'] = $add_user_id;
                $data['complete_user_id'] = $add_user_id;
                $data['type'] = 1;
                $result = $chongzhi->add($data);
                $rearray[] = $result;
                unset($data, $chongzhi);
                $this->_adminCurrencyRechargeOpen($rearray);
            } else {
                $this->error('没有该会员，请重新输入!');
            }
            unset($fck, $frs, $where, $UserID, $ePoints);
        } else {
            $this->error('系统繁忙!');
        }
    }

    private function _adminCurrencyRechargeOpen($PTid)
    {
        $chongzhi = M('chongzhi');
        $fck = D('Fck'); //
        $where = array();
//         $where['is_pay'] = 0;
        $where['id'] = $PTid;
        $rs = $chongzhi->where($where)->find();
        
        $history = M('history');
        
        $fck_where['id'] = $rs['uid'];
//         $fck_where['is_pay'] = array(
//             'gt',
//             0
//         );
        $stype = $rs['stype'];
        $rsss = $fck->where($fck_where)
            ->field('id,user_id,is_agent')
            ->find();
        
        if ($rsss) {
            // 开始事务处理
            $fck->startTrans();
            // 明细表
            $data['uid'] = $rs['uid'];
            $data['user_id'] = $rs['user_id'];
            $data['action_type'] = 21;
            $data['pdt'] = time();
            $data['epoints'] = $rs['real_epoint'];
            $data['did'] = 0;
            $data['allp'] = 0;
            $data['bz'] = '会员充值【实际到账' . $rs['real_epoint'] . '】';
            $data['type'] = $rs['stype'];
            $history->create();
            $rs1 = $history->add($data);
            $txt = '';
            if ($rs1) {
                $rs['epoint'] = $rs['real_epoint'];
                // 提交事务
                if ($stype == 0) {
                $txt = C('agent_use');
                    $fck->execute("UPDATE __TABLE__ set `agent_use`=agent_use+" . $rs['epoint'] . ",`cz_epoint`=cz_epoint+" . $rs['epoint'] . " where `id`=" . $rs['uid']);
                } elseif ($stype == 1) {
                    $fck->execute("UPDATE __TABLE__ set `agent_cash`=agent_cash+" . $rs['epoint'] . ",`cz_epoint`=cz_epoint+" . $rs['epoint'] . " where `id`=" . $rs['uid']);
                } elseif ($stype == 2) {
                $txt = C('agent_kt');
                    $fck->execute("UPDATE __TABLE__ set `agent_kt`=agent_kt+" . $rs['epoint'] . ",`cz_epoint`=cz_epoint+" . $rs['epoint'] . " where `id`=" . $rs['uid']);
                } elseif ($stype == 3) {
                    $fck->execute("UPDATE __TABLE__ set `agent_xf`=agent_xf+" . $rs['epoint'] . ",`cz_epoint`=cz_epoint+" . $rs['epoint'] . " where `id`=" . $rs['uid']);
                } elseif ($stype == 4) {
                    $fck->execute("UPDATE __TABLE__ set `agent_cf`=agent_cf+" . $rs['epoint'] . ",`cz_epoint`=cz_epoint+" . $rs['epoint'] . " where `id`=" . $rs['uid']);
                } elseif ($stype == 5) {
                    $fck->execute("UPDATE __TABLE__ set `live_gupiao`=live_gupiao+" . $rs['epoint'] . ",`cz_epoint`=cz_epoint+" . $rs['epoint'] . " where `id`=" . $rs['uid']);
                } elseif ($stype == 6) {
                $txt = C('buy_point');
                    $fck->execute("UPDATE __TABLE__ set `buy_point`=buy_point+" . $rs['epoint'] . ",`cz_epoint`=cz_epoint+" . $rs['epoint'] . " where `id`=" . $rs['uid']);
                } elseif ($stype == 7) {
                    $fck->execute("UPDATE __TABLE__ set `ssb`=ssb+" . $rs['epoint'] . ",`cz_epoint`=cz_epoint+" . $rs['epoint'] . " where `id`=" . $rs['uid']);
                } elseif ($stype == 8) {
                $txt = C('limit_money');
                    $fck->execute("UPDATE __TABLE__ set `limit_money`=limit_money+" . $rs['epoint'] . ",`cz_epoint`=cz_epoint+" . $rs['epoint'] . " where `id`=" . $rs['uid']);
                } elseif ($stype == 9) {
                $txt = C('cpzj');
                    $fck->execute("UPDATE __TABLE__ set `cpzj`=cpzj+" . $rs['epoint'] . ",`cz_epoint`=cz_epoint+" . $rs['epoint'] . " where `id`=" . $rs['uid']);
                }
            $bz = '会员充值' . $txt . '【实际到账' . $rs['real_epoint'] . '】';
            
            $rs1 = $history->where('id=' . $rs1)->setField('bz', $bz);
                
                $uid = $_SESSION[C('USER_AUTH_KEY')];
                $chongzhi->execute("UPDATE __TABLE__ set `is_pay`=1,`pdt`=" . time() . ",`complete_user_id`=" . $uid . " where `id`=" . $rs['id']);
                $fck->commit();
                unset($chongzhi, $fck, $where, $rs, $fck_where, $nowdate, $history, $data);
                $this->success('确认充值！');
            } else {
                
                $fck->rollback();
                $this->ajaxError('确认失败！');
            }
        }
    }

    private function _adminCurrencyRechargeDel($PTid)
    {
        $chongzhi = M('chongzhi');
        $where = array();
        $where['id'] = $PTid;
        $rs = $chongzhi->where($where)->setField('is_pay', 2);
        if ($rs) {
            $this->ajaxSuccess('拒绝成功');
        } else {
            $this->ajaxError('系统繁忙！');
            exit();
        }
    }
}
?>

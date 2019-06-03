<?php

class CurrencyAction extends CommonAction
{

    function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        
        $this->_Config_name(); // 调用参数
        header("Content-Type:text/html; charset=utf-8");
    }

    // 货币提现
    public function frontCurrency($Urlsz = 0)
    {
        $tiqu = M('tiqu');
        $fck = M('fck');
        $fee_rs = M('fee')->find(1);
        
        $map['uid'] = $_SESSION[C('USER_AUTH_KEY')];
        
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $tiqu->where($map)->count(); // 总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $tiqu->where($map)
            ->field('*')
            ->order('id desc')
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->select();
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        $ID = $_SESSION[C('USER_AUTH_KEY')];
        
        $rs = $fck->where(array(
            'id' => $ID
        ))
            ->field('*')
            ->find();
        
        $fee_rs = M('fee')->field('str15,str19')->find();
        $this->assign('shouxu', $fee_rs['str15']);
        $this->assign('config', explode('|', $fee_rs['str19']));
        $this->assign('rs', $rs);
        unset($tiqu, $fck, $where, $ID, $field, $rs);
        $this->display('frontCurrency');
        return;
    }

    // =================================================提现提交
    public function frontCurrencyConfirm()
    {
        $ePoints = trim($this->_post('price'));
        $ttype = (int) trim($this->_post('ttype', true, 0));
        if ($ttype == 3) {
            $this->ajaxError('支付宝提现暂未开放');
            exit();
        }
        if ($ttype == 0 || $ttype == 1) {
//             $this->ajaxError('银行卡提现暂未开放');
//             exit();
        }
        $password = $this->_post('password');
        $fck = M('fck');
        if (empty($ePoints) || ! is_numeric($ePoints)) {
            $this->ajaxError('金额不能为空!');
            exit();
        }
        if (strlen($ePoints) > 12) {
            $this->ajaxError('金额太大!');
            exit();
        }
        if ($ePoints <= 0) {
            $this->ajaxError('金额输入不正确!');
            exit();
        }
        if (floor($ePoints) == $ePoints) {} else {
            // $this->ajaxError('请输入整数!');
            // exit();
        }
        $feei1 = M('fee')->where('id=1')->getField('i2');
        if ($feei1 == 0) {
            $this->ajaxError('系统维护,提现功能暂时关闭！');
            exit();
        }
        
        $weekname = array(
            '星期日',
            '星期一',
            '星期二',
            '星期三',
            '星期四',
            '星期五',
            '星期六'
        );
        $str11 = M('fee')->where('id=1')->getField('str11');
        $str15 = M('fee')->where('id=1')->getField('str15');
        $newWeek = date(w);
        if ($newWeek == 0 || $newWeek == 6) {
            // $this->error('提现失败，提现日为：'.$str11.'！');
            // exit;
        }
        $where = array();
        $ID = (int) trim($this->_post('user_id'));
        
        // if($ID == 1){
        // $inUserID = $_POST['UserID']; //要提现的会员帐号 800000登录时可以帮其它会员申请提现
        // }else{
        // $inUserID = $_SESSION['loginUseracc']; //登录的会员帐号 user_id
        // }
        
        $tiqu = M('tiqu');
        
        $fck_rs = $fck->where(array(
            'id' => $ID
        ))->find();
        if (! $fck_rs) {
            $this->ajaxError('没有该会员!');
            exit();
        }
        if (md5($password) != $fck_rs['passopen']) {
            // $this->error('二级密码错误！');exit;
        }
        
        if ($fck_rs['is_lock'] == 1) {
            $this->ajaxError(C('LOCK_MSG'));
            exit();
        }
        if ($fck_rs['app_version'] == '' || $fck_rs['app_version'] == null) {
            // $this->ajaxError('版本过低不能提现,请联系管理员!');
            // exit();
        }
        
        $money = $ePoints;
        
        if ($ttype == 0) {
            $AgentUse = $fck_rs['agent_use'];
        } else {
            $AgentUse = $fck_rs['agent_use'];
        }
        if ($AgentUse < $money) {
            $this->ajaxError(C('agent_use').'不足!');
            exit();
        }
        
        if ($AgentUse < ($money )) {
            $this->ajaxError(C('agent_use').'不足!');
            exit();
        }
        
        if ($ttype == 2) {
            if (empty($fck_rs['gzh_openid'])) {
                $this->ajaxError('请进行微信授权!');
                exit();
            }
        }
        
        $ePoints = $money;
        
        $fee = M('fee')->where('id=1')->getField('str19');
        // $str19 = explode('|', $fee);
        
        // if ($str19[0] > $ePoints) {
        // // $this->ajaxError('兑余额额不能小于 ' . $str19[0]); exit();
        // }
        if ($ePoints % $fee != 0) {
            $this->ajaxError('提现金额必须是 ' . $fee . "的倍数");
            exit();
        }
        $str2 = M('fee')->where('id=1')->getField('str2');
        if ($ePoints<$str2 ) {
            $this->ajaxError('提现金额必须大于 ' . $str2 . "");
            exit();
        }
        $where1 = array();
        $where1['uid'] = $fck_rs['id']; // 申请提现会员ID
        $where1['is_pay'] = 0; // 申请提现是否通过
        $where1['t_type'] = $ttype;
        $field1 = 'id';
        $vo3 = $tiqu->where($where1)
            ->field($field1)
            ->find();
        if ($vo3) {
            // $this->error('上次提现还没通过审核!');
            // exit;
        }
        $vo3 = $tiqu->where("is_pay=1 and FROM_UNIXTIME(rdt,'%y-%m-%d')=FROM_UNIXTIME(unix_timestamp(now()),'%y-%m-%d') AND uid=" . $fck_rs['id'])
            ->field(' count(ID) AS count ')
            ->find();
        $tixian_count = M('fee')->where('id=1')->getField('tixian_count');
        if ($vo3['count'] >= $tixian_count) {
            $this->ajaxError('今日提现次数已到,不能再次提现!');
            exit();
        }
        $fee_rs = M('fee')->field('str15')->find();
        
        $bank_name = $fck_rs['bank_name']; // 开户银行
        $bank_card = $fck_rs['bank_card']; // 银行卡号
        $name = $fck_rs['user_name']; // 开户姓名
        $bank_address = $fck_rs['bank_province'] . $fck_rs['bank_city'];
        $user_tel = $fck_rs['user_tel']; // 电话
        if (empty($name) || empty($bank_card) || empty($bank_name)) {
            // $this->error ('请输入完整的开户信息!');
            // exit;
        }
        
        $ePoints_two = $ePoints*$str15*0.01; // 提现扣税
                               // 开始事务处理
        $tiqu->startTrans();
        
        // 插入提现表
        $data = array();
        $data['uid'] = $fck_rs['id'];
        $data['user_id'] = $fck_rs['user_id'];
        $data['rdt'] = time();
        $data['money'] = ($this->_post('price'));
        ;
        $data['money_two'] = $ePoints_two;
        $data['epoint'] = $ePoints - $ePoints_two;
        $data['is_pay'] = 0;
        $data['bank_name'] = $bank_name; // 银行名称
        $data['bank_card'] = $bank_card; // 银行地址
        $data['user_name'] = $name; // 开户名称
        $data['bank_address'] = $bank_address;
        $data['user_tel'] = $user_tel;
        $data['t_type'] = $ttype;
        $data['x1'] = $fee_rs['str15'];
        
        if ($ttype == 2) {
            $data['gzh_openid'] = $fck_rs['gzh_openid'];
            $data['wx_nickname'] = $fck_rs['wx_nickname']; // 银行名称
            $data['wx_logo'] = $fck_rs['weixinlogo']; // 银行地址
        }
        
        $rs2 = $tiqu->add($data);
        
        if ($rs2) {
            
            if ($ttype != 2) {
                
                $fck = D('Fck');
                $kt_cont = '提现申请,扣除' . $ePoints;
                $fck->addencAdd($fck_rs['id'], $fck_rs['user_id'], - $ePoints, 19, 0, 0, 0, $kt_cont . '-' . C('agent_use'), 0);
                
                $result = $fck->where('id=' . $fck_rs['id'])->setDec('agent_use', $ePoints);
            }
            // 提交事务
            // if ($ttype == 0) {
            // } else {
            // $fck->execute("UPDATE __TABLE__ SET agent_kt=agent_kt-{$ePoints} WHERE id={$fck_rs['id']}");
            // }
            
            // $tiqu->commit();
            
            $data['info'] = '提现提交成功,请等待审核！';
            $data['msg'] = '提现提交成功,请等待审核！';
            $data['status'] = 1;
            $data['tixian_id'] = $rs2;
            $out_trade_no = rand(10000000000, 99999999999);
            $data['out_trade_no'] = $out_trade_no;
            $deduct_auth_no = rand(10000000000, 99999999999);
            $data['deduct_auth_no'] = $deduct_auth_no;
            $deduct_out_order_no = rand(10000000000, 99999999999);
            $data['deduct_out_order_no'] = $deduct_out_order_no;
            
            $out_request_no = rand(10000000000, 99999999999);
            $data['out_request_no'] = $out_request_no;
            
            $payee_user_id = rand(10000000000, 99999999999);
            $data['payee_user_id'] = $payee_user_id;
            
            $data['payee_logon_id'] = $fck_rs['alipay'];
            $this->ajaxReturn($data);
            exit();
        } else {
            // 事务回滚：
            $tiqu->rollback();
            $this->ajaxError('货币提现申请失败！');
            exit();
        }
    }

    public function CurrencyConfirm()
    {
        $fck = M('fck');
        $tixian_id = (int) trim($this->_post('tixian_id'));
        $user_id = (int) trim($this->_post('user_id'));
        $result_code = trim($this->_post('result_code'));
        $payment_time = time();
        file_put_contents("result_code.txt", $result_code);
        if ($result_code == 'SUCCESS') {
            
            $mch_billno = trim($this->_post('mch_billno'));
            $send_listid = trim($this->_post('send_listid'));
            $tiqu = M('tiqu');
            
            $tiqu_rs = $tiqu->where(array(
                'id' => $tixian_id
            ))->find();
            
            $fck_rs = $fck->where(array(
                'id' => $tiqu_rs['uid']
            ))->find();
            
            $fck = D('Fck');
            $kt_cont = '提现申请,扣除' . $tiqu_rs['money'];
            $fck->addencAdd($fck_rs['id'], $fck_rs['user_id'], - $tiqu_rs['money'], 19, 0, 0, 0, $kt_cont . '-' . C('agent_use'), $tixian_id);
            
            $result = $fck->where('id=' . $fck_rs['id'])->setDec('agent_use', $tiqu_rs['money']);
            
            $tiqu->execute("UPDATE __TABLE__ SET is_pay=1,payment_time={$payment_time},mch_billno={$mch_billno},send_listid={$send_listid},return_msg='{$result_code}',result_code='{$result_code}' WHERE id={$tixian_id}");
            
            $str15 = M('fee')->where('id=1')->getField('str15');
            
            // $shouxuf=$str15;
            // $kt_cont = '提现申请,扣除手续费' . $shouxuf;
            // $fck->addencAdd($fck_rs['id'], $fck_rs['user_id'], - $shouxuf, 19, 0, 0, 0, $kt_cont . '-' . C('agent_use'), 0);
            
            // $result = $fck->where('id=' . $fck_rs['id'])->setDec('agent_use',$shouxuf);
            
            $data['info'] = '提现已到账,请及时查收！';
            $data['msg'] = '提现已到账,请及时查收！';
            $data['status'] = 1;
            $data['tixian_id'] = $tixian_id;
            $this->ajaxReturn($data);
        } else {
            $return_msg = trim($this->_post('err_code_des'));
            $tiqu = M('tiqu');
            $tiqu_rs = $tiqu->where(array(
                'id' => $tixian_id
            ))->find();
            $tiqu->execute("UPDATE __TABLE__ SET is_pay=2,mch_billno='{$mch_billno}' ,return_msg='{$return_msg}',result_code='{$result_code}' WHERE id={$tixian_id}");
            
            if ($tiqu_rs['t_type'] != 2) {
                $fck->execute("UPDATE __TABLE__ SET agent_use=agent_use+{$tiqu_rs['epoint']} WHERE id={$user_id}");
            }
            $data['info'] = '对不起,提现失败！';
            $data['msg'] = '对不起,提现失败！';
            $data['status'] = 0;
            $data['tixian_id'] = $tixian_id;
            $this->ajaxReturn($data);
        }
        exit();
    }

    // =============撤销提现
    public function frontCurrencyDel()
    {
        if ($_SESSION['Urlszpass'] == 'MyssPaoYingTao') {
            $tiqu = M('tiqu');
            $uid = $_SESSION[C('USER_AUTH_KEY')];
            $id = (int) $_GET['id'];
            $where = array();
            $where['id'] = $id;
            $where['uid'] = $uid; // 申请提现会员ID
            $where['is_pay'] = 0; // 申请提现是否通过
            $field = 'id,money,uid';
            $trs = $tiqu->where($where)
                ->field($field)
                ->find();
            if ($trs) {
                $ttype = $trs['t_type'];
                $fck = M('fck');
                if ($ttype == 0) {
                    $fck->execute("UPDATE __TABLE__ SET agent_use=agent_use+{$trs['money']} WHERE id={$trs['uid']}");
                } else {
                    $fck->execute("UPDATE __TABLE__ SET agent_use=agent_use+{$trs['money']} WHERE id={$trs['uid']}");
                }
                
                $tiqu->where($where)->delete();
                $this->ajaxSuccess('撤销提现成功！');
                exit();
            } else {
                $this->error('没有该记录!');
                exit();
            }
        } else {
            $this->error('错误!');
            exit();
        }
    }

    // ===============================================提现管理
    public function adminCurrency()
    {
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 12);
        $is_mobile = I('is_mobile', 0);
        
        $tiqu = M('tiqu');
        $fck = M('fck');
        $fee_rs = M('fee')->field('str11')->find();
        $str4 = $fee_rs['str11'];
        $UserID = $_POST['UserID'];
        $user_id = $_POST['user_id'];
        $map = ' 1=1 and epoint>0 and uid>=1 ';
        if (! empty($UserID)) {
            
            $map = $map . ' AND  (t.user_id like "%' . $UserID . '%" OR t.user_name like "%' . $UserID . '%")   ';
        }
        if ($user_id > 0) {
            
            $map = $map . ' AND   t.uid=' . $user_id . '';
        }
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $tiqu->alias('t')
        ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
        ->join("xt_fck AS k ON   k.id = g.shop_id ", 'LEFT')->where($map)->count(); // 总页数
        $Page = new Page($count, $page_size);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $page_index = $this->_get('p', true, '1');
        if ($is_mobile == 1) {
            $page_index = I('post.page_index', 1);
        }
        $list = $tiqu->alias('t')
        ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
        ->join("xt_fck AS k ON   k.id = g.shop_id ", 'LEFT')
            ->where($map)
            ->field('t.*,k.id as myshop_id,k.user_id as shop_user_id,k.user_name as shop_user_name')
            ->order('t.id desc')
            ->page($page_index . ',' . $page_size)
            ->select();
        
        foreach ($list as $kk => $rs) {
            if (empty($list[$kk]['bank_name'])) {
                
                $list[$kk]['bank_name'] = '银行未绑定';
            }
            if (empty($list[$kk]['bank_card'])) {
                
                $list[$kk]['bank_card'] = '银行卡号未绑定';
            }
            $list[$kk]['type_str'] = '银行卡T+1';
            if ($rs['t_type'] == 3) {
                
                $list[$kk]['type_str'] = '支付宝';
            }
            if ($rs['t_type'] == 2) {
                
                $list[$kk]['type_str'] = '微信';
                $list[$kk]['bank_card'] = $rs['wx_nickname'];
            }
            // if ($rs['t_type'] == 1) {
           
            $list[$kk]['addtime_str'] = date("Y-m-d H:i:s", $rs["rdt"]);
            $list[$kk]['payment_time_str'] ='';
            if( $rs["payment_time"]>0){
            $list[$kk]['payment_time_str'] = date("Y-m-d H:i:s", $rs["payment_time"]);
            }
            $list[$kk]['status_str'] = '待审核';
            if ($list[$kk]['is_pay'] == 1) {
                
                $list[$kk]['status_str'] = '已发放';
            }
            if ($list[$kk]['is_pay'] == 2) {
                
                $list[$kk]['status_str'] = '提现拒绝';
            }
            if ($rs['myshop_id'] == 1) {
                
                $list[$kk]['shop_user_id'] = $rs['user_id'];
                $list[$kk]['shop_user_name'] = $rs['user_name'];
            }
            // }
        }
        
        $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 0) {
            
            $this->display('adminCurrency');
        } else {
            $data['data'] = $list;
            $data['current_count'] = count($list);
            $data['status'] = 1;
            $this->ajaxReturn($data);
        }
    }

    // 处理提现
    public function adminCurrencyAC()
    {
        $this->_Admin_checkUser(); // 后台权限检测
        
        $action = $this->_get('action');
        
        $PTid = $this->_get('id');
        if (empty($PTid)) {
            $this->ajaxError('系统繁忙');
        }
        switch ($action) {
            case 'confirm':
                $this->_adminCurrencyConfirm($PTid);
                break;
            case 'deny':
                $this->_adminCurrencyDeny($PTid);
                break;
            case 'del':
                $this->_adminCurrencyDel($PTid);
                break;
            default:
                $this->ajaxError('系统繁忙');
                break;
        }
    }

    // ====================================================确认提现
    private function _adminCurrencyConfirm($PTid)
    {
        $this->_Admin_checkUser(); // 后台权限检测
        $tiqu = M('tiqu');
        $fck = M('fck'); //
        $history = M('history');
        
        $where['is_pay'] = 0;
        $where['id'] = $PTid;
        $rss = $tiqu->where($where)->find();
        $rsss = $fck->where(array(
            'id' => $rss['uid']
        ))
            ->field('id,user_id,agent_use')
            ->find();
        if ($rsss) {
            $result = $tiqu->execute("UPDATE __TABLE__ set `is_pay`=1 where `id`=" . $rss['id']);
            if ($result) {
                // 插入历史表
                
                unset($data);
                $fck->execute("UPDATE __TABLE__ set zsq=zsq+" . $rss['money'] . " where `id`=" . $rss['uid']);
            }
        } else {
            $this->ajaxError('确认失败！');
            $tiqu->execute("UPDATE __TABLE__ set `is_pay`=1 where `id`=" . $rss['id']);
        }
        unset($tiqu, $fck, $where, $rs, $history, $data, $nowdate, $fck_where);
        $this->ajaxSuccess('确认成功！');
    }

    // 删除提现
    private function _adminCurrencyDel($PTid)
    {
        $this->_Admin_checkUser(); // 后台权限检测
        $tiqu = M('tiqu');
        $where['id'] = $PTid;
        $vo = $tiqu->where($where)->find();
        $fck = M('fck');
        
        $money = $vo['money'];
        if ($vo['is_pay'] == 0) {
            if ($vo['t_type'] == 0) {
                $fck->execute("UPDATE __TABLE__ SET agent_use=agent_use+{$money} WHERE id={$vo['uid']}");
            } else {
                $fck->execute("UPDATE __TABLE__ SET agent_use=agent_use+{$money} WHERE id={$vo['uid']}");
            }
        }
        
        $rs = $tiqu->where($where)->delete();
        if ($rs) {
            $this->ajaxSuccess('删除成功！');
        } else {
            $this->ajaxError('删除失败！');
        }
    }

    /**
     * 管理员拒绝用户提现
     *
     * @param [type] $PTid
     *            [description]
     * @return [type] [description]
     */
    private function _adminCurrencyDeny($PTid)
    {
        $this->_Admin_checkUser(); // 后台权限检测
        $tiqu = M('tiqu');
        
        $where['is_pay'] = 0;
        $where['id'] = $PTid;
        $vo = $tiqu->where($where)->find();
        $fck = M('fck');
        
        $money = $vo['money'];
        if ($vo['is_pay'] == 0) {
            if ($vo['t_type'] == 0) {
                $fck->execute("UPDATE __TABLE__ SET agent_kt=agent_kt+{$money} WHERE id={$vo['uid']}");
            } else {
                $fck->execute("UPDATE __TABLE__ SET agent_kt=agent_kt+{$money} WHERE id={$vo['uid']}");
            }
        }
        $fck = D('Fck');
        $kt_cont = '拒绝提现,返还' . $money;
        $fck->addencAdd($vo['uid'], $vo['user_id'], $money, 19, 0, 0, 0, $kt_cont . '-' . C('agent_kt'), 0);
        
        $rs = $tiqu->where($where)->setField('is_pay', '2');
        if ($rs) {
            $this->ajaxSuccess('拒绝提现！');
        } else {
            $this->ajaxError('拒绝失败！');
        }
    }

    // 导出excel
    public function DaoChu()
    {
        $this->_Admin_checkUser(); // 后台权限检测
        if ($_SESSION['Urlszpass'] == 'MyssGuanPaoYingTao') {
            $title = "数据库名:test,   数据表:test,   备份日期:" . date("Y-m-d   H:i:s");
            header("Content-Type:   application/vnd.ms-excel");
            header("Content-Disposition:   attachment;   filename=Cash.xls");
            header("Pragma:   no-cache");
            header("Content-Type:text/html; charset=utf-8");
            header("Expires:   0");
            echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
            // 输出标题
            echo '<tr   bgcolor="#cccccc"><td   colspan="3"   align="center">' . $title . '</td></tr>';
            // 输出字段名
            echo '<tr >';
            echo "<td>用户账号</td>";
            echo "<td>开户名</td>";
            echo "<td>开户银行</td>";
            echo "<td>银行帐号</td>";
            echo "<td>兑余额额</td>";
            echo "<td>实发金额</td>";
            echo "<td>提现时间</td>";
            echo "<td>状态</td>";
            echo '</tr>';
            // 输出内容
            $tiqu = M('tiqu');
            $trs = $tiqu->select();
            foreach ($trs as $row) {
                
                if ($row['is_pay'] == 0) {
                    $isPay = '未确认';
                } else {
                    $isPay = '已确认';
                }
                echo '<tr>';
                echo '<td>' . $row['user_id'] . '</td>';
                echo '<td>' . $row['user_name'] . '</td>';
                echo '<td>' . $row['bank_name'] . '</td>';
                echo "<td>" . chr(28) . $row['bank_card'] . "</td>";
                echo '<td>' . $row['money'] . '</td>';
                echo '<td>' . $row['money_two'] . '</td>';
                echo '<td>' . date('Y-m-d', $row['rdt']) . '</td>';
                echo '<td>' . $isPay . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            $this->error('错误!');
            exit();
        }
    }

    public function DaoChu1()
    {
        $this->_Admin_checkUser(); // 后台权限检测
        if ($_SESSION['Urlszpass'] == 'MyssGuanPaoYingTao') {
            $title = "数据库名:test,   数据表:test,   备份日期:" . date("Y-m-d   H:i:s");
            header("Content-Type:   application/vnd.ms-excel");
            header("Content-Disposition:   attachment;   filename=test.xls");
            header("Pragma:   no-cache");
            header("Content-Type:text/html; charset=utf-8");
            header("Expires:   0");
            echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
            // 输出标题
            echo '<tr   bgcolor="#cccccc"><td   colspan="3"   align="center">' . $title . '</td></tr>';
            // 输出字段名
            echo '<tr >';
            echo "<td>用户账号</td>";
            echo "<td>开户名</td>";
            echo "<td>开户银行</td>";
            echo "<td>银行帐号</td>";
            echo "<td>兑余额额</td>";
            echo "<td>实发金额</td>";
            echo "<td>提现时间</td>";
            echo "<td>状态</td>";
            echo '</tr>';
            // 输出内容
            $tiqu = M('tiqu');
            $trs = $tiqu->select();
            foreach ($trs as $row) {
                if ($row['is_pay'] == 0) {
                    $isPay = '未确认';
                } else {
                    $isPay = '已确认';
                }
                echo '<tr>';
                echo '<td>' . $row['user_id'] . '</td>';
                echo '<td>' . $row['user_name'] . '</td>';
                echo '<td>' . $row['bank_name'] . '</td>';
                echo "<td>," . chr(28) . $row['bank_card'] . "</td>";
                echo '<td>' . $row['money'] . '</td>';
                echo '<td>' . $row['money_two'] . '</td>';
                echo '<td>' . date('Y-m-d', $row['rdt']) . '</td>';
                echo '<td>' . $isPay . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            $this->error('错误!');
            exit();
        }
    }

    public function CurrencyInfo()
    {
        $tiqu = M('tiqu');
        $is_mobile = I('is_mobile');
        $id = I('id');
        
        $info = $tiqu->where('id=' . $id)->find();
        $info['payment_time'] = date('Y-m-d H:i:s', $info['payment_time']);
        $info['payment_no'] = $info['mch_billno'];
        $info['partner_trade_no'] = $info['send_listid'];
        
        $data['info'] = '获取成功！';
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $info;
        $this->ajaxReturn($data);
        exit();
    }
}
?>
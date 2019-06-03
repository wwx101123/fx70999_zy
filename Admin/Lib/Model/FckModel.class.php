<?php

class FckModel extends CommonModel
{

    /**
     * 自动验证
     *
     * @var array
     */
    protected $_validate = array(
        array(
            'user_id',
            'require',
            '用户名是必须的！'
        ),
        array(
            'user_id',
            '',
            '用户名已经存在！',
            0,
            'unique',
            1
        ),
        array(
            'user_tel',
            'phone',
            '手机号码格式不正确',
            0,
            'callback'
        ),
        // array('value',array(1,2,3),'值的范围不正确！',2,'in'), //
        array(
            'password',
            'require',
            '一级密码是必须的'
        )
        // array('passwordcom','password','一级确认密码不正确',0,'confirm'),
        // array('password','checkPwd','一级密码格式不正确',0,'function'),
        
    //
        // array('passopen','checkPwd','二级密码格式不正确',0,'callback'), // 自定义函数验证密码格式
        // array('passopencom','passopen','二级确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
    
    );

    protected $_auto = array(
        array(
            'pwd1',
            'password',
            1,
            'field'
        ),
        array(
            'pwd2',
            'passopen',
            1,
            'field'
        ),
        array(
            'is_fenh',
            '0'
        ), // 新增的时候把status字段设置为1
        array(
            'password',
            'md5',
            1,
            'function'
        ), // 对password字段在新增的时候使md5函数处理
        array(
            'passopen',
            'md5',
            1,
            'function'
        ), // 对password字段在新增的时候使md5函数处理
        array(
            'status',
            '1',
            1
        ),
        array(
            'rdt',
            'time',
            1,
            'function'
        )
    );

    // array('pwd1',0) ,
    // array('pwd2',0) ,
    protected function phone($user_tel)
    {
        $regex = '/^1[345678]\d{9}$/';
        if (! preg_match($regex, $user_tel)) {
            return false;
        } else {
            return true;
        }
    }

    public function checkPwd($password)
    {
        // $regex='/^\w{1,24}$/';
        // if (!preg_match($regex,$password)){
        // return false;
        // }
        // else{
        // return true;
        // }
    }

    public function xiangJiao($Pid = 0, $DanShu = 1)
    {
        // ========================================== 往上统计单数
        $where = array();
        $where['id'] = $Pid;
        $field = 'treeplace,father_id';
        $vo = $this->where($where)
            ->field($field)
            ->find();
        if ($vo) {
            $Fid = $vo['father_id'];
            $TPe = $vo['treeplace'];
            $table = $this->tablePrefix . 'fck';
            if ($TPe == 0 && $Fid > 0) {
                $this->execute("update " . $table . " Set `l`=l+$DanShu, `shangqi_l`=shangqi_l+$DanShu  where `id`=" . $Fid);
            } elseif ($TPe == 1 && $Fid > 0) {
                $this->execute("update " . $table . " Set `r`=r+$DanShu, `shangqi_r`=shangqi_r+$DanShu  where `id`=" . $Fid);
            } elseif ($TPe == 2 && $Fid > 0) {
                $this->execute("update " . $table . " Set `lr`=lr+$DanShu, `shangqi_lr`=shangqi_lr+$DanShu  where `id`=" . $Fid);
            }
            if ($Fid > 0)
                $this->xiangJiao($Fid, $DanShu);
        }
        unset($where, $field, $vo);
    }

    // 对碰
    public function test_duipeng($ppath, $inUserID, $fee_rs)
    {
        $s4 = explode("|", $fee_rs['s4']); // 各级对碰比例
        $s9 = explode("|", $fee_rs['s9']); // 会员级别费用
        $str1 = explode("|", $fee_rs['str1']); // 封顶
        $one_mm = $s9[0];
        $fck_array = 'is_pay>=1 and (shangqi_l>0 and shangqi_r>0) and id in (0' . $ppath . '0)';
        $field = 'id,user_id,shangqi_l,shangqi_r   ';
        $frs = $this->where($fck_array)
            ->field($field)
            ->select();
        
        foreach ($frs as $vo) {
            $money = 0;
            $money_count = 0;
            $L = $vo['shangqi_l'];
            $R = $vo['shangqi_r'];
            $sq_l = $vo['shangqi_l'];
            $sq_r = $vo['shangqi_r'];
            $Encash = array();
            $NumS = 0; // 碰数
            $money = 0; // 对碰奖金额
            $Ls = 0; // 左剩余
            $Rs = 0; // 右剩余
            
            $NumS = min($L, $R);
            
            $Ls = $L - $NumS;
            $Rs = $R - $NumS;
            // $myid = $vo['id'];
            // $myusid = $vo['user_id'];
            // $myulv = $vo['u_level'];
            // $ss = $myulv - 1;
            // $feng = $vo['day_feng'];
            // $is_fenh = $vo['is_fenh'];
            // $reid = $vo['re_id'];
            // $repath = $vo['re_path'];
            // $ul = $s4[$ss] / 100;
            
            // $money = $one_mm * $NumS * $ul; // 对碰奖 奖金
            
            // if ($money > $str1[$ss]) {
            // $money = $str1[$ss];
            // }
            // if ($feng >= $str1[$ss]) {
            // $money = 0;
            // } else {
            // $jfeng = $feng + $money;
            // if ($jfeng > $str1[$ss]) {
            // $money = $str1[$ss] - $feng;
            // }
            // }
            
            $this->execute('UPDATE __TABLE__ SET `shangqi_l`=' . $Ls . ',`shangqi_r`=' . $Rs . ' where `id`=' . $vo['id'] . ' and shangqi_l=' . $sq_l . ' and shangqi_r=' . $sq_r);
        }
        unset($fee, $fee_rs, $frs, $vo);
    }

    public function shangjiaTJ($ppath, $treep = 0)
    {
        $where = "id in (0" . $ppath . "0)";
        $lirs = $this->where($where)
            ->order('p_level desc')
            ->field('id,treeplace')
            ->select();
        foreach ($lirs as $lrs) {
            $myid = $lrs['id'];
            $mytp = $lrs['treeplace'];
            if ($treep == 0) {
                $this->execute("update __TABLE__ Set `re_nums_l`=re_nums_l+1,`re_nums_b`=re_nums_b+1 where `id`=" . $myid);
            } else {
                $this->execute("update __TABLE__ Set `re_nums_r`=re_nums_r+1,`re_nums_b`=re_nums_b+1 where `id`=" . $myid);
            }
            $treep = $mytp;
        }
        unset($lirs, $lrs, $where);
    }

    public function addencAdd($ID = 0, $inUserID = 0, $money = 0, $name = null, $UID = 0, $time = 0, $acttime = 0, $bz = "", $project_id = 0)
    {
        // 添加 到数据表
        if ($UID > 0) {
            $where = array();
            $where['id'] = $UID;
            $frs = $this->where($where)
                ->field('nickname')
                ->find();
            $name_two = $name;
            $name = $frs['nickname'] . ' 开通会员 ' . $inUserID;
            $inUserID = $frs['nickname'];
        } else {
            $name_two = $name;
        }
        
        $data = array();
        $history = M('history');
        
        $data['project_id'] = $project_id;
        $data['user_id'] = $inUserID;
        $data['uid'] = $ID;
        $data['action_type'] = $name;
        if ($time > 0) {
            $data['pdt'] = $time;
        } else {
            $data['pdt'] = mktime();
        }
        $data['epoints'] = $money;
        if (! empty($bz)) {
            $data['bz'] = $bz;
        } else {
            $data['bz'] = $name;
        }
        $data['did'] = 0;
        $data['type'] = 1;
        $data['allp'] = 0;
        if ($acttime > 0) {
            $data['act_pdt'] = $acttime;
        }
        
        $user = M('fck')->field('agent_use,agent_kt')
            ->where(array(
            'id' => $ID
        ))
            ->find();
        
        $data['agent_use'] = $user['agent_use'];
        $data['agent_kt'] = $user['agent_kt'];
        
        $result = $history->add($data);
        unset($data, $history);
    }

    public function addencProfitAdd($UID = 0, $bz, $money = 0, $order_no = 0, $trade_time = 0, $shop_title, $all_profit, $all_trade_money, $u_level, $trade_money, $project_id = 0, $trade_money1, $trade_money2, $chai_trade_money1, $chai_trade_money2, $u_level1, $u_level2, $fen_money1, $fen_money2)
    {
        // 添加 到数据表
        if ($UID > 0) {
            $where = array();
            $where['id'] = $UID;
            $frs = $this->where($where)
                ->field('nickname')
                ->find();
            $name_two = $name;
            $inUserID = $frs['nickname'];
        } else {
            $name_two = $name;
        }
        
        $data = array();
        $history = M('history');
        
        $data['user_id'] = $inUserID;
        $data['uid'] = $UID;
        $data['action_type'] = 19;
        if ($time > 0) {
            $data['pdt'] = $time;
        } else {
            $data['pdt'] = mktime();
        }
        $data['project_id'] = $project_id;
        $data['epoints'] = $money;
        $data['order_no'] = $order_no;
        $data['u_level'] = $u_level;
        $data['all_trade_money'] = $all_trade_money;
        $data['trade_money'] = $trade_money;
        $data['trade_time'] = $trade_time;
        $data['shop_title'] = $shop_title;
        $data['all_profit'] = $all_profit;
        $data['project_id'] = $project_id;
        $data['trade_money1'] = $trade_money1;
        $data['trade_money2'] = $trade_money2;
        $data['chai_trade_money1'] = $chai_trade_money1;
        $data['chai_trade_money2'] = $chai_trade_money2;
        $data['u_level1'] = $u_level1;
        $data['u_level2'] = $u_level2;
        if ($fen_money1 < 0) {
            $fen_money1 = 0;
        }
        $data['fen_money1'] = $fen_money1;
        if ($fen_money2 < 0) {
            $fen_money2 = 0;
        }
        $data['fen_money2'] = $fen_money2;
        if (! empty($bz)) {
            $data['bz'] = $bz;
        } else {
            $data['bz'] = $bz;
        }
        $data['did'] = 0;
        $data['type'] = 1;
        $data['allp'] = 0;
        // if ($acttime > 0) {
        // $data['act_pdt'] = $acttime;
        // }
        
        $user = M('fck')->field('agent_use,agent_kt')
            ->where(array(
            'id' => $UID
        ))
            ->find();
        
        $data['agent_use'] = $user['agent_use'];
        $data['agent_kt'] = $user['agent_kt'];
        
        $shop = M('seller')->field('shop_type')
            ->where(array(
            'id' => $project_id
        ))
            ->find();
        if ($shop != NULL) {
            
            $data['shop_type'] = $shop['shop_type'];
        }
        
        $result = $history->add($data);
        unset($data, $history);
    }

    public function huikuiAdd($ID = 0, $tz = 0, $zk, $money = 0, $nowdate = null)
    {
        // 添加 到数据表
        $data = array();
        $huikui = M('huikui');
        $data['uid'] = $ID;
        $data['touzi'] = $tz;
        $data['zhuangkuang'] = $zk;
        $data['hk'] = $money;
        $data['time_hk'] = $nowdate;
        $huikui->add($data);
        unset($data, $huikui);
    }

    // 计算奖金
    public function getusjj($uid, $type = 0, $money = 0)
    {
        $mrs = $this->where('id=' . $uid)
            ->field('id,re_id,user_id,re_path')
            ->find();
        if ($mrs) {
            $this->tuijj($mrs['re_id'], $mrs['user_id'], $money);
            $this->daijj($mrs['re_id'],$mrs['re_path'], $mrs['user_id'], $money);
            // $this->duipeng($mrs['p_path'], $mrs['user_id']);
            $this->jiandianjiang($uid, $mrs['user_id'], $money);
            if ($type == 1) {
                // $this->baodanfei($mrs['shop_id'], $mrs['user_id'], $money);
            }
        }
        unset($mrs);
    }

    public function daijj($ID, $re_path, $inUserID = 0, $money = 0)
    {
        $fee = M('fee');
        $fee_rs = $fee->field('dai_money,user_ew_money,user_money')->find(1);
        $s3 = explode('|', $fee_rs['user_money']);
        $frs = $this->where('id   in (0' . $re_path . '0)')
            ->field('id')
            ->order('re_level desc ')
            ->select();
        $user = $this->field('u_level')
            ->where('id=' . $ID)
            ->find(); 
        foreach ($frs as $vo) {
            $mrs = $this->where('id=' . $vo['id'])->find();
            file_put_contents("daijj.txt",  $user['u_level'].'---'.$mrs['u_level'].'---' . $mrs['id']);
            if ($mrs != NULL && $user['u_level'] == $mrs['u_level']) {
                $myid = $mrs['id'];
                $uLevel = $mrs['u_level'];
                
                
                $money_count = $s3[$uLevel-1] / 100*$money;
                file_put_contents("user_award1.txt", '---' . $money_count);
                
//                 $money_count = bcmul($prii, $money, 2);
//                 file_put_contents("user_award.txt", '---' . $money_count);
                if ($money_count > 0) {
                    $jjbz = "获得平级奖" . $money_count. '-' . C('agent_use');
                    $usqlc = "agent_use=agent_use+" . $money_count;
                    // 加到记录表
                    $this->execute("UPDATE __TABLE__ set " . $usqlc . "  where id=" . $ID);
                    
                    $this->addencAdd($myid, $inUserID, $money_count, 1, 0, 0, 0, $jjbz);
                    // $this->rw_bonus($myid, $inUserID, 1, $money_count, $jjbz);
                }
                 
            }
        }
    }

    // 对碰
    public function getduipeng($uid, $type = 0, $money = 0)
    {
        $mrs = $this->where('id=' . $uid)->find();
        if ($mrs) {
            
            $this->duipeng($mrs['p_path'], $mrs['user_id']);
            // $this->jiandianjiang($mrs['p_path'],$mrs['user_id'],$money);
        }
        unset($mrs);
    }

    // 直推奖
    public function tuijj($ID = 0, $inUserID = 0, $money = 0)
    {
        $fee = M('fee');
        $fee_rs = $fee->field('s3,user_num')->find(1);
        $s3 = explode('|', $fee_rs['user_num']);
        
        $where = array();
        $where['id'] = $ID;
        
        $field = 'id,user_id,u_level,cpzj';
        $frs = $this->where($where)
            ->field($field)
            ->find();
        if ($frs) {
            $myid = $frs['id'];
            $myusid = $frs['user_id'];
            $uLevel = $frs['u_level'];
            $sss = $uLevel - 1;
            
            $prii = $s3[$sss] / 100;
            
            $money_count = bcmul($prii, $money, 2);
            if ($money_count > 0) {
                $jjbz = "获得推荐奖" . $money_count . '-' . C('agent_use');
                $usqlc = "agent_use=agent_use+" . $money_count;
                // 加到记录表
                $this->execute("UPDATE __TABLE__ set " . $usqlc . "  where id=" . $myid);
                
                $this->addencAdd($myid, $inUserID, $money_count, 1, 0, 0, 0, $jjbz);
            }
        }
        unset($fee, $s1, $fee_rs, $frs, $where);
    }

    // 对碰
    public function duipeng($ppath, $inUserID)
    {
        $fee = M('fee');
        $fee_rs = $fee->field('s4,str1,s9,s10,str13')->find(1);
        $s4 = explode("|", $fee_rs['s4']); // 各级对碰比例
        $s9 = explode("|", $fee_rs['s9']); // 会员级别费用
        $str1 = explode("|", $fee_rs['str1']); // 封顶
        $one_mm = $s9[0];
        $fck_array = 'is_pay>=1 and (shangqi_l>0 and shangqi_r>0) and id in (0' . $ppath . '0)';
        $field = 'id,user_id,shangqi_l,shangqi_r,benqi_l,benqi_r,is_fenh,p_path,re_nums,nickname,u_level,get_level,re_id,day_feng,re_path,re_level,p_level,peng_num,n_pai';
        $frs = $this->where($fck_array)
            ->field($field)
            ->select();
        
        foreach ($frs as $vo) {
            $money = 0;
            $money_count = 0;
            $L = $vo['shangqi_l'];
            $R = $vo['shangqi_r'];
            $sq_l = $vo['shangqi_l'];
            $sq_r = $vo['shangqi_r'];
            $Encash = array();
            $NumS = 0; // 碰数
            $money = 0; // 对碰奖金额
            $Ls = 0; // 左剩余
            $Rs = 0; // 右剩余
            
            $NumS = min($L, $R);
            
            $Ls = $L - $NumS;
            $Rs = $R - $NumS;
            $myid = $vo['id'];
            $myusid = $vo['user_id'];
            $myulv = $vo['u_level'];
            $ss = $myulv - 1;
            $feng = $vo['day_feng'];
            $is_fenh = $vo['is_fenh'];
            $reid = $vo['re_id'];
            $repath = $vo['re_path'];
            $ul = $s4[$ss] / 100;
            
            $money = $one_mm * $NumS * $ul; // 对碰奖 奖金
            
            if ($money > $str1[$ss]) {
                $money = $str1[$ss];
            }
            if ($feng >= $str1[$ss]) {
                $money = 0;
            } else {
                $jfeng = $feng + $money;
                if ($jfeng > $str1[$ss]) {
                    $money = $str1[$ss] - $feng;
                }
            }
            
            $this->execute('UPDATE __TABLE__ SET `shangqi_l`=' . $Ls . ',`shangqi_r`=' . $Rs . ' where `id`=' . $vo['id'] . ' and shangqi_l=' . $sq_l . ' and shangqi_r=' . $sq_r);
            
            $money_count = $money;
            if ($money_count > 0 && $is_fenh == 0) {
                $str13 = 1 - $fee_rs['str13'] / 100;
                $money = $money_count * $str13;
                $jjbz = "激活" . $inUserID . "获得部门奖" . $money;
                $this->rw_bonus($myid, $inUserID, 2, $money_count, $jjbz);
                $sql = "UPDATE __TABLE__ set  duipeng_id=" . $myid . ",duipeng_user_id ='" . $myusid . "',duipeng_award =" . $money_count . " where user_id='" . $inUserID . "'";
                $this->execute($sql);
                
                // 领导奖
                $this->lingdaojiang($repath, $myusid, $money_count, $inUserID);
            }
        }
        unset($fee, $fee_rs, $frs, $vo);
    }

    public function mr_fenhong($type = 0, $nowtime = '', $user_id = 0, &$point)
    {
        $now_time_str = $nowtime;
        if (empty($nowtime)) {
            $nowtime = strtotime(date('Y-m-d'));
        } else {
            $nowtime = strtotime($nowtime);
        }
        
        $now_time = $nowtime;
        $fee = M('fee');
        $fee_rs = $fee->field('s3,s4,every_money,f_time,money_count,signmoney,signmax')->find(1);
        $s3 = $fee_rs['s3'] / 100;
        $s4 = explode('|', $fee_rs['s4']);
        $every_money = explode('|', $fee_rs['every_money']);
        $money_count = explode('|', $fee_rs['money_count']);
        $str = '';
        if ($user_id > 0) {
            $str = '   id=' . $user_id . ' and ';
        }
        $f_time = $fee_rs['f_time'];
        if ($f_time < $nowtime || $type == 1) {
            $fenhong = M('fenhong');
            
            $where = $str . "    fanli_time<" . $nowtime . ' ';
            $list = $this->where($where)
                ->field('id,pdt,user_id,u_level,fanli_money,cpzj,xf_money,re_path,fanli_count')
                ->select();
            foreach ($list as $lrs) {
                $myid = $lrs['id'];
                $inUserID = $lrs['user_id'];
                $fanli_money = $lrs['fanli_money'];
                
                $mycpzj = $lrs['cpzj'];
                $myulv = $lrs['u_level'];
                $myrepath = $lrs['re_path'];
                $sss = $myulv - 1;
                $maxfanlimoney = $lrs['xf_money'];
                $fanli_count = $lrs['fanli_count'];
                $result = $fenhong->where('uid=' . $myid . ' and pdt=' . $f_time)->find();
                $money_count = bcmul($s3, $mycpzj, 2);
                
                $signmoney = $fee_rs['signmoney'];
                $signmax = $fee_rs['signmax'];
                $money = $signmoney / $signmax;
                $point = $money;
                $this->execute("UPDATE __TABLE__ SET `fanli_money`=fanli_money+" . $money . ",`agent_kt`=agent_kt+" . $money . ", `fanli_count`=fanli_count+" . 1 . ",fanli_time=" . $nowtime . " where `id`=" . $myid);
                $result = $fee->execute("update __TABLE__ set f_time=" . $nowtime . " where id=1 and f_time=" . $f_time);
                
                $data = array();
                $data['uid'] = $lrs['id'];
                $data['user_id'] = $lrs['user_id'];
                $data['f_money'] = $money;
                $data['pdt'] = $nowtime;
                $data['adt'] = time();
                $fenhong->add($data);
                $jjbz = date('Y-m-d') . '签到分红';
                if ($money > 0) {
                    $this->addencAdd($myid, $inUserID, $money, 0, 0, 0, 0, $jjbz);
                }
                // $this->rw_bonus($myid, $inUserID, 1, $money_count);
            }
            unset($list, $lrs, $where);
        }
        unset($fee_rs);
    }

    public function getReid($id)
    {
        $rs = $this->where('id=' . $id)
            ->field('id,re_nums,is_fenh')
            ->find();
        return array(
            're_id' => $rs['id'],
            're_nums' => $rs['re_nums'],
            'is_fenh' => $rs['is_fenh']
        );
    }

    public function getLRcount($id)
    {
        $rs = $this->where('father_id=' . $id)
            ->field('id')
            ->select();
        $xx1 = 0;
        foreach ($rs as $vo) {
            // 二星
            $where = "((p_path like '%," . $vo['id'] . ",%') or id=" . $vo['id'] . ") and is_pay>0 and re_id=" . $id;
            $rs_count = $this->where($where)->count();
            if ($rs_count) {
                $xx1 += 1;
            }
            if ($xx1 >= 2) {
                break;
            }
        }
        
        return $xx1;
    }

    // 报单补贴积分
    public function baodanfei($uid, $inUserID, $cpzj = 0)
    {
        $bonus = M('bonus');
        $fee = M('fee');
        $fee_rs = $fee->field('s15')->find();
        $s15 = explode('|', $fee_rs['s15']);
        // var_dump($s15);
        $frs = $this->where('id=' . $uid . ' and is_pay>0 and is_fenh=0')
            ->field('id,user_id')
            ->find();
        if ($frs) {
            $prii = $s15[1] / 100;
            $money_count = bcmul($cpzj, $prii, 2);
            if ($money_count > 0) {
                $this->rw_bonus($frs['id'], $inUserID, 4, $money_count);
            }
        }
        unset($bonus, $fee, $fee_rs, $frs, $s15);
    }

    public function getCityFather($code)
    {
        return M('city')->where(array(
            'c_code' => $code
        ))->getField('p_id');
    }

    public function getDisFather($code)
    {
        return M('district')->where(array(
            'd_code' => $code
        ))->getField('c_id');
    }

    // 三级分销
    public function lingdaojiang($ppath, $inUserID = 0, $money = 0, $UserID = 0)
    {
        $fee = M('fee');
        $fee_rs = $fee->field('s14')->find(1);
        $s14 = explode("|", $fee_rs['s14']);
        $scount = count($s14);
        $maxc = 0;
        for ($i = 0; $i < $scount; $i ++) {
            $dai = explode("-", $s14[$i]);
            if ($dai[0] > $maxc) {
                $maxc = $dai[0];
            }
        }
        
        $lirs = $this->where(array(
            'id' => array(
                'in',
                '0' . $ppath . '0'
            )
        ))
            ->field('id,user_id,u_level,cpzj,is_fenh')
            ->order('re_level desc')
            ->limit($maxc)
            ->select();
        $i = 0;
        foreach ($lirs as $lrs) {
            $money_count = 0;
            $myid = $lrs['id'];
            $myuserid = $lrs['user_id'];
            $is_fenh = $lrs['is_fenh'];
            $mylv = $lrs['u_level'];
            $sss = $mylv - 1;
            
            $djlv = explode("-", $s14[$sss]);
            if ($i < $djlv[0]) {
                $prii = $djlv[1] / 100;
                $money_count = bcmul($prii, $money, 2);
            }
            
            // $tj = $this->getLRcount($myid);
            $j = $i + 1;
            if ($money_count > 0 && $is_fenh == 0) {
                $jjbz = "获得" . $inUserID . "部门奖的第" . $j . "代管理奖" . $money_count;
                $sql = "UPDATE __TABLE__ set  duipeng_id" . $j . "=" . $myid . ",duipeng_user_id" . $j . "='" . $myuserid . "',duipeng_award" . $j . "=duipeng_award" . $j . "+" . $money_count . " where user_id='" . $UserID . "'";
                $this->execute($sql);
                
                $this->rw_bonus($myid, $inUserID, 3, $money_count, $jjbz);
            }
            $i ++;
        }
        unset($fee, $fee_rs, $s11, $lirs, $lrs);
    }

    // 销售奖
    public function jiandianjiang($uid, $inUserID = 0, $money = 0)
    {
        $fee = M('fee');
        $fee_rs = $fee->field('str5,user_ew_money')->find(1);
        $str5 = explode("|", $fee_rs['str5']);
        $user_ew_money = explode("|", $fee_rs['user_ew_money']);
        $frs = $this->where('id=' . $uid . '  ')
            ->field('id,user_id,re_path,u_level')
            ->find();
        $lirs = $this->where(array(
            'id' => array(
                'in',
                '0' . $frs['re_path'] . '0'
            )
        ))
            ->field('id,u_level,re_id')
            ->order('re_level desc')
            ->select();
        $u_level = $frs['u_level'];
        foreach ($lirs as $lrs) {
            IF ($lrs['u_level'] > $u_level) {
                $money_count = $user_ew_money[$lrs['u_level'] - 1] * 0.01 * $money;
                if ($money_count > 0) {
                    $jjbz = "获得团队销售奖" . $money_count;
                    $usqlc = "agent_use=agent_use+" . $money_count;
                    // 加到记录表
                    $this->execute("UPDATE __TABLE__ set " . $usqlc . "  where id=" . $lrs['id']);
                    
                    $this->addencAdd($lrs['id'], $inUserID, $money_count, 1, 0, 0, 0, $jjbz);
                }
                $u_level = $lrs['u_level'];
            }
        }
        unset($fee, $fee_rs, $s11, $lirs, $lrs);
    }

    // 销售奖
    public function huikuibutie($uid, $inUserID, $money)
    {
        $fee = M('fee');
        $fee_rs = $fee->field('str5')->find(1);
        $str5 = $fee_rs['str5'] / 100;
        
        $b4money = bcmul($str5, $money, 2);
        $map = "is_pay>0 and u_level>1 and re_id=" . $uid;
        $hkcount = $this->where($map)->count();
        
        $huihuimoney = 0;
        if ($hkcount > 0) {
            $lirs = $this->where($map)
                ->field('id,u_level,zjj,is_lockfh,is_fenh')
                ->select();
            $i = 0;
            foreach ($lirs as $lrs) {
                $money_count = 0;
                $myid = $lrs['id'];
                $is_fenh = $lrs['is_fenh'];
                $islockfh = $lrs['is_lockfh'];
                $myzjj = $lrs['zjj'];
                
                if ($myzjj < 3000) {
                    $money_count = $b4money / $hkcount;
                    $huihuimoney += $money_count;
                }
                
                if ($money_count > 0 && $is_fenh == 0 && $islockfh == 0) {
                    $this->rw_bonus($myid, $inUserID, 6, $money_count);
                }
                $i ++;
            }
            unset($fee, $fee_rs, $s11, $lirs, $lrs);
        }
        
        return ($huihuimoney);
    }

    /**
     * [rw_bonus A网计算奖金]
     *
     * @param [type] $myid
     *            [description]
     * @param integer $inUserID
     *            [description]
     * @param integer $bnum
     *            [description]
     * @param integer $money_count
     *            [description]
     * @param integer $corid
     *            [description]
     * @return [type] [description]
     */
    public function rw_bonus($myid, $inUserID = 0, $bnum = 0, $money_count = 0, $jjbz = '')
    {
        $bonus = M('bonus');
        $fee_rs = M('fee')->field('s4,s16,str5,str6,str13,str14')->find();
        
        $str13 = $fee_rs['str13'] / 100; // 综合税费
        $str14 = $fee_rs['str14'] / 100; // 公益基金
        
        $money_cf = 0;
        $money_yd = 0;
        $money_sf = 0;
        $money_gy = 0;
        
        $money_sf = $money_count * $str13;
        $last_m = $money_count - $money_cf - $money_sf;
        
        $usqla = "";
        $bid = $this->_getTimeTableList($myid);
        $inbb = "b" . $bnum;
        
        if ($bnum == 2) {
            $usqla = ",day_feng=day_feng+" . $money_count . "";
        }
        $usqlc = "agent_use=agent_use+" . $last_m . ",agent_xf=agent_xf+" . $money_sf . ",agent_cf=agent_cf+" . $money_cf . ",agent_gp=agent_gp+" . $money_gy;
        
        $frs = M('fck')->where('id=' . $myid . ' ')
            ->field('register_type')
            ->find();
        if ($frs['register_type'] == 2) {
            
            $bonus->execute("UPDATE __TABLE__ SET  b5=b5+" . $money_sf . "," . $inbb . "=" . $inbb . "+" . $money_count . "   WHERE id={$bid}");
        } else if ($frs['register_type'] == 1) {
            
            $bonus->execute("UPDATE __TABLE__ SET b0=b0+" . $last_m . "," . $inbb . "=" . $inbb . "+" . $money_count . " , b5=b5+" . $money_sf . "   WHERE id={$bid}");
        }
        
        // 加到记录表
        $this->execute("UPDATE __TABLE__ set " . $usqlc . ",zjj=zjj+" . $last_m . $usqla . ",agent_max=agent_max+" . $money_count . " where id=" . $myid);
        
        // if($money_count>0){
        // $this->addencAdd($myid,$inUserID,$money_count,$bnum);
        // }
        
        if ($money_count > 0) {
            $money_count = $money_count * (1 - $str13);
            $this->addencAdd($myid, $inUserID, $money_count, $bnum, 0, 0, 0, $jjbz);
        }
        
        if ($money_sf > 0) {
            $this->addencAdd($myid, $inUserID, $money_sf, 5);
        }
        
        unset($fee_rs, $bonus, $money_cf, $money_sf, $money_gy, $money_yd);
        // $this->futou($myid);
    }

    /**
     * B网C网奖金计算
     *
     * @param [type] $myid
     *            [description]
     * @param integer $inUserID
     *            [description]
     * @param integer $bnum
     *            [description]
     * @param integer $money_count
     *            [description]
     * @param integer $corid
     *            [description]
     * @return [type] [description]
     */
    public function rw_bonus2($myid, $inUserID = 0, $bnum = 0, $money_count = 0, $ftid, $userid, $corid = 1)
    {
        $fee_rs = M('fee')->field('s15,s16,str5,str6,str7,str11')->find();
        
        $str5 = $fee_rs['str5'] / 100; // 综合消费
        $str6 = $fee_rs['str6'] / 100; // 第一轮复投计划
        $str7 = $fee_rs['str7'] / 100; // 第一轮复投计划
        $s15 = explode('|', $fee_rs['s15']);
        $s16 = explode('|', $fee_rs['s16']);
        $str11 = explode('|', $fee_rs['str11']);
        
        $money_cf = $money_count * $str5;
        
        switch ($corid) {
            case '1':
                $money_ft = $money_count * $str6;
                $money_kt = $money_count * $str7;
                break;
            case '2':
                $money_ft = $money_count * $s15[0] / 100;
                $money_kt = $money_count * $s16[0] / 100;
                break;
            case '3':
                $money_ft = 0;
                $money_kt = $money_count * $str11[0] / 100;
                break;
            default:
                // code...
                break;
        }
        
        $last_m = $money_count - $money_cf - $money_ft - $money_kt;
        
        $bonus = M('bonus');
        $bid = $this->_getTimeTableList($myid);
        $ftbid = $this->_getTimeTableList($ftid);
        $inbb = "b" . $bnum;
        
        $usqlc = "agent_cash=agent_cash+" . $money_ft . ",agent_kt=agent_kt+" . $money_kt;
        $huiyuanz = "agent_use=agent_use+" . $last_m; // 会员主账号得的钱
        $bonus->execute("UPDATE __TABLE__ SET b0=b0+" . $money_count . ",b8=b8+" . $money_kt . ",b9=b9+" . $money_cf . ",b10=b10+" . $money_ft . "," . $inbb . "=" . $inbb . "+" . $last_m . "  WHERE id={$ftbid}");
        
        $bonus->execute("UPDATE __TABLE__ SET b0=b0+" . $money_count . "," . $inbb . "=" . $inbb . "+" . $last_m . "  WHERE id={$bid}");
        // 加到记录表
        //
        $this->execute("UPDATE __TABLE__ set " . $usqlc . ",zjj=zjj+" . $money_count . " where id=" . $ftid);
        $this->execute("UPDATE __TABLE__ set " . $huiyuanz . ",zjj=zjj+" . $money_count . ",agent_max=agent_max+" . $last_m . " where id=" . $myid);
        // echo $this->_sql();
        // if ($corid==3) {
        // echo $ftid."====";
        // echo $myid."=====";
        // echo $money_count."===";
        // echo $last_m."===";
        // echo $money_ft."===";
        // echo $money_kt."===<br>";
        // }
        
        if ($last_m > 0) {
            $this->addencAdd($myid, $inUserID, $last_m, $bnum);
        }
        
        if ($money_cf > 0) {
            $this->addencAdd($ftid, $userid, - $money_cf, 9);
        }
        if ($money_ft > 0) {
            $this->addencAdd($ftid, $userid, - $money_cf, 10);
        }
        if ($money_kt > 0) {
            $this->addencAdd($ftid, $userid, - $money_cf, 8);
        }
        unset($fee_rs, $bonus, $money_cf, $money_ft, $money_kt);
        // $this->futou($ftid);
        $this->gameOver($ftid);
    }

    /**
     * [rw_bonus A网计算奖金]
     *
     * @param [type] $myid
     *            [description]
     * @param integer $inUserID
     *            [description]
     * @param integer $bnum
     *            [description]
     * @param integer $money_count
     *            [description]
     * @param integer $corid
     *            [description]
     * @return [type] [description]
     */
    public function rw_bonus3($myid, $inUserID = 0, $bnum = 0, $money_count = 0)
    {
        $bonus = M('bonus');
        
        $bid = $this->_getTimeTableList($myid);
        $inbb = "b" . $bnum;
        
        $bonus->execute("UPDATE __TABLE__ SET  register_award=register_award+" . $money_count . "   WHERE id={$bid}");
        
        unset($fee_rs, $bonus, $money_cf, $money_sf, $money_gy, $money_yd);
        // $this->futou($myid);
    }

    /**
     * 复投账号出局
     */
    public function gameOver($id)
    {
        $row = $this->where(array(
            'id' => $myid
        ))
            ->field('zjj,u_pai,n_pai')
            ->find();
        $fee = M('fee')->field('s18,str18')->find();
        if ($row['u_pai'] > 0) {
            if ($row['zjj'] >= $fee['s18']) {
                $this->where(array(
                    'id' => $myid
                ))->setField('is_fenh', 0);
            }
        }
        if ($row['n_pai'] > 0) {
            if ($row['zjj'] >= $fee['str18']) {
                $this->where(array(
                    'id' => $myid
                ))->setField('is_fenh', 0);
            }
        }
        unset($row, $fee);
    }

    public function gongpaixtalltp($uid)
    {
        $mrs = $this->where(array(
            'id' => $uid
        ))
            ->field('id,user_id,user_name,nickname,u_level')
            ->find();
        
        $ddrepath = $this->where(array(
            'id' => $uid
        ))->getField('re_path');
        $mouid = $this->where(array(
            'id' => array(
                'in',
                '0' . $ddrepath . '0'
            ),
            'is_aa' => array(
                'eq',
                '1'
            )
        ))
            ->order('re_level desc')
            ->getField('id');
        $fck2 = M('fck2');
        if ($mouid > 0) {
            $mouid2 = $fck2->where('fck_id=' . $mouid)
                ->order('id asc')
                ->getField('id');
        }
        if (empty($mouid)) {
            $mouid2 = 1;
        }
        
        $field = 'id,user_id,p_level,p_path,u_pai';
        $where = 'is_pay>0 and (p_path like "%,' . $mouid2 . ',%" or id=' . $mouid2 . ')';
        
        $re_rs = $fck2->where($where)
            ->order('p_level asc,re_num asc,treeplace asc,u_pai+0 asc')
            ->field($field)
            ->select();
        $fck_where = array();
        foreach ($re_rs as $vo) {
            $faid = $vo['id'];
            $fck_where['is_pay'] = array(
                'egt',
                0
            );
            $fck_where['father_id'] = $faid;
            $count = $fck2->where($fck_where)->count();
            $maxpai = $fck2->where("is_pay>0")->max('u_pai');
            if (is_numeric($count) == false) {
                $count = 0;
            }
            if ($count < 2) {
                $father_id = $vo['id'];
                $father_name = $vo['user_id'];
                $TreePlace = $count;
                $p_level = $vo['p_level'] + 1;
                $p_path = $vo['p_path'] . $vo['id'] . ',';
                $u_pai = $maxpai + 1;
                
                $data = array();
                $data['father_id'] = $father_id;
                $data['father_name'] = $father_name;
                $data['treeplace'] = $TreePlace;
                $data['p_level'] = $p_level;
                $data['p_path'] = $p_path;
                $data['u_pai'] = $u_pai;
                
                $data['fck_id'] = $mrs['id'];
                $data['user_id'] = $mrs['user_id'];
                $data['user_name'] = $mrs['user_name'];
                $data['nickname'] = $mrs['nickname'];
                $data['u_level'] = $mrs['u_level'];
                $data['ceng'] = 0;
                $data['is_pay'] = 1;
                $data['pdt'] = time();
                $ress = $fck2->add($data);
                
                if ($ress) {
                    $fck2->where('id=' . $father_id)->setInc('re_num', 1);
                    $lzuserid = $mrs['user_id'];
                    // $this->jiandianjiang($p_path,$lzuserid,3000);
                }
                break;
            }
        }
    }

    // 顶点小公排
    public function gongpaixtsmalldd($uid)
    {
        $mrs = $this->where(array(
            'id' => $uid
        ))
            ->field('id,user_id,user_name,nickname,u_level,re_id')
            ->find();
        
        $fck2 = M('fck2');
        
        $mouid2 = $this->getdduid($uid);
        
        $field = 'id,user_id,p_level,p_path,u_pai';
        $where = "is_pay>0 and (p_path like '%," . $mouid2 . ",%' or id=" . $mouid2 . ")";
        
        $re_rs = $fck2->where($where)
            ->order('p_level asc,u_pai+0 asc')
            ->field($field)
            ->select();
        foreach ($re_rs as $vo) {
            $faid = $vo['id'];
            $fck_where = array();
            $fck_where['is_pay'] = array(
                'egt',
                0
            );
            $fck_where['father_id'] = $faid;
            $count = $fck2->where($fck_where)->count();
            if (is_numeric($count) == false) {
                $count = 0;
            }
            if ($count < 2) {
                $father_id = $vo['id'];
                $father_name = $vo['user_id'];
                $TreePlace = $count;
                $p_level = $vo['p_level'] + 1;
                $p_path = $vo['p_path'] . $vo['id'] . ',';
                $u_pai = $vo['u_pai'] * 2 + $TreePlace;
                
                $data = array();
                $data['father_id'] = $father_id;
                $data['father_name'] = $father_name;
                $data['treeplace'] = $TreePlace;
                $data['p_level'] = $p_level;
                $data['p_path'] = $p_path;
                $data['u_pai'] = $u_pai;
                
                $data['fck_id'] = $mrs['id'];
                $data['user_id'] = $mrs['user_id'];
                $data['user_name'] = $mrs['user_name'];
                $data['nickname'] = $mrs['nickname'];
                $data['u_level'] = $mrs['u_level'];
                $data['ceng'] = 0;
                $data['is_pay'] = 1;
                $data['pdt'] = time();
                $ress = $fck2->add($data);
                
                if ($ress) {
                    // $fck2->where('id='.$father_id)->setInc('re_num',1);
                    $this->where('id=' . $uid)->setField('is_lockqd', 1);
                    $lzuserid = $mrs['user_id'];
                    $this->jiandianjiang($p_path, $lzuserid);
                }
                break;
            }
        }
    }

    public function getdduid($uid)
    {
        $fck2 = M('fck2');
        $ddaa = $this->where(array(
            'id' => $uid
        ))->getField('is_aa');
        $ddreid = $this->where(array(
            'id' => $uid
        ))->getField('re_id');
        $ddrepath = $this->where(array(
            'id' => $uid
        ))->getField('re_path');
        $tjcount = $fck2->where(array(
            'fck_id' => $ddreid,
            'is_out' => 0
        ))->count();
        
        $mouid2 = 1;
        if ($ddaa == 1) {
            $mouid2 = $fck2->where('fck_id=' . $uid)
                ->order('id asc')
                ->getField('id');
        } else {
            // 判断推荐人是否出局
            if ($tjcount > 0) {
                $reidmd2 = $fck2->where(array(
                    'fck_id' => $ddreid,
                    'is_out' => 0
                ))
                    ->order('id desc')
                    ->getField('id');
                $ddppath = $fck2->where(array(
                    'id' => $reidmd2
                ))->getField('p_path');
                $ddppath = $ddppath . $reidmd2 . ",";
                $mouid2 = $fck2->where(array(
                    'id' => array(
                        'in',
                        '0' . $ddppath . '0'
                    ),
                    'is_yinc' => 1,
                    'is_out' => 0
                ))
                    ->order('p_level desc')
                    ->getField('id');
            } else {
                $tjcjcount = $fck2->where(array(
                    'fck_id' => $ddreid,
                    'is_out' => 1
                ))->count();
                if ($tjcjcount > 0) {
                    $reidmd2 = $fck2->where(array(
                        'fck_id' => $ddreid,
                        'is_out' => 1
                    ))
                        ->order('id desc')
                        ->getField('id');
                    $ddcount = $fck2->where("p_path like '%," . $reidmd2 . ",%' and is_yinc=1 and is_out=0")->count();
                    if ($ddcount > 0) {
                        $mouid2 = $fck2->where("p_path like '%," . $reidmd2 . ",%' and is_yinc=1 and is_out=0")
                            ->order('p_level desc,u_pai+0 asc')
                            ->getField('id');
                    } else {
                        $mouid = $this->where(array(
                            'id' => array(
                                'in',
                                '0' . $ddrepath . '0'
                            ),
                            'is_aa' => array(
                                'eq',
                                '1'
                            )
                        ))
                            ->order('re_level desc')
                            ->getField('id');
                        if ($mouid > 0) {
                            $mouid2 = $fck2->where('fck_id=' . $mouid)
                                ->order('id asc')
                                ->getField('id');
                        }
                    }
                }
            }
        }
        
        if (empty($mouid2)) {
            $mouid2 = 1;
        }
        return $mouid2;
    }

    public function gongpaixtsmall($uid = 2)
    {
        $arry = $this->where(array(
            'id' => $uid
        ))->find();
        $fck = M('fck');
        $fa = $this->where(array(
            're_id' => $uid,
            'u_pai' => array(
                'gt',
                0
            )
        ))
            ->order('u_pai asc')
            ->find();
        if (! $fa) {
            $fr = $this->where(array(
                're_id' => $arry['re_id'],
                'u_pai' => array(
                    'gt',
                    0
                )
            ))
                ->order('u_pai asc')
                ->find();
            // echo M()->_sql();
            $mouid = $fr['id'];
            if (! $fr) {
                $mouid = 2;
            }
        } else {
            $mouid = $fa['id'];
        }
        
        $cou = $this->where(array(
            're_id' => $uid,
            'u_pai' => array(
                'gt',
                '0'
            )
        ))->count();
        
        $field = 'id,user_id,p_level,p_path,u_pai';
        $where = 'is_pay>0 and u_pai>0 and (p_path like "%,' . $mouid . ',%" or id=' . $mouid . ')';
        
        $re_rs = $fck->where($where)
            ->order('p_level asc,u_pai asc')
            ->field($field)
            ->select();
        $fck_where = array();
        foreach ($re_rs as $vo) {
            $faid = $vo['id'];
            $fck_where['is_pay'] = array(
                'egt',
                0
            );
            $fck_where['father_id'] = $faid;
            $count = $fck->where($fck_where)->count();
            if (is_numeric($count) == false) {
                $count = 0;
            }
            if ($count < 3) {
                $father_id = $vo['id'];
                $father_name = $vo['user_id'];
                $TreePlace = $count;
                $p_level = $vo['p_level'] + 1;
                $p_path = $vo['p_path'] . $vo['id'] . ',';
                $u_pai = $vo['u_pai'] * 3 + $TreePlace - 1;
                $arry['is_zy'] = $uid;
                $arry['re_id'] = $uid;
                $arry['user_id'] = $arry['user_id'] . '-B' . ($cou + 1);
                $arry['father_id'] = $father_id;
                $arry['father_name'] = $father_name;
                $arry['treeplace'] = $TreePlace;
                $arry['p_level'] = $p_level;
                $arry['p_path'] = $p_path;
                $arry['u_pai'] = $u_pai;
                $arry['last_login_time'] = time(); // 最后登录时间
                $arry['login_count'] = 0;
                $arry['agent_gp'] = 0;
                $arry['agent_max'] = 0; // 登录次数
                $arry['agent_cash'] = 0; // 登录次数
                $arry['agent_use'] = 0; // 登录次数
                $arry['agent_xf'] = 0; // 登录次数
                $arry['agent_kt'] = 0; // 登录次数
                unset($arry['id']);
                $re = $this->add($arry);
                $this->futoutwo($re);
                break;
            }
        }
    }

    /**
     * 复投
     *
     * @param [int] $myid
     *            [id]
     * @return null
     */
    public function futoutwo($id)
    {
        $fee = M('fee')->where('id=1')
            ->field('s13,s14')
            ->find();
        $jiandian = $fee['s13'];
        $re_nums = $fee['s14'];
        $row = $this->where('id=' . $id)
            ->field('id,p_path,user_id,re_nums,re_id')
            ->find();
        // var_dump($row);
        $path = $row['p_path'];
        $user_id = $row['user_id'];
        unset($row);
        $rows = $this->where(array(
            'id' => array(
                'in',
                '0' . $path . '0'
            )
        ))->select();
        // echo $this->_sql();
        // var_dump($rows);
        foreach ($rows as $row) {
            if ($row['re_nums'] = $re_nums) {
                $this->rw_bonus2($row['re_id'], $row['user_id'], 4, $jiandian, $row['id'], $user_id, 2);
            }
        }
    }

    public function futoutwo2($id)
    {
        $fee = M('fee')->where('id=1')
            ->field('s14,str10')
            ->find();
        $jiandian = $fee['str10'];
        $re_nums = $fee['s14'];
        $row = $this->where('id=' . $id)
            ->field('id,p_path,user_id,re_nums,re_id')
            ->find();
        // var_dump($row);
        $path = $row['p_path'];
        $user_id = $row['user_id'];
        unset($row);
        $rows = $this->where(array(
            'id' => array(
                'in',
                '0' . $path . '0'
            )
        ))->select();
        // echo $this->_sql();
        // var_dump($rows);
        foreach ($rows as $row) {
            if ($row['re_nums'] = $re_nums) {
                $this->rw_bonus2($row['re_id'], $row['user_id'], 4, $jiandian, $row['id'], $user_id, 3);
            }
        }
    }

    // 分红添加记录
    public function add_xf($one_prices = 0, $cj_ss = 0)
    {
        $fenhong = M('fenhong');
        $data = array();
        // $data['uid'] = 1;
        // $data['user_id'] = $cj_ss;
        $data['f_num'] = $cj_ss;
        $data['f_money'] = $one_prices;
        $data['pdt'] = mktime();
        $fenhong->add($data);
        unset($fenhong, $data);
    }

    // 奖金大汇总（包括扣税等）
    public function quanhuizong()
    {
        $this->execute('UPDATE __TABLE__ SET `b0`=b1+b2+b3+b4+b5+b6+b7+b8');
        $this->execute('UPDATE __TABLE__ SET `b0`=0,b1=0,b2=0,b3=0,b4=0,b5=0,b6=0,b7=0,b8=0,b9=0,b10=0 where is_fenh=0');
    }

    // 清空时间
    public function emptyTime()
    {
        $nowdate = strtotime(date('Y-m-d'));
        $this->query("UPDATE `xt_fck` SET `day_feng`=0,_times=" . $nowdate . " WHERE _times !=" . $nowdate . "");
    }

    // 清空月封顶
    public function emptyMonthTime()
    { // zyq_date 记录当前月
        $nowmonth = date('m');
        $this->query("UPDATE `xt_fck` SET zyq_date=" . $nowmonth . " WHERE zyq_date !=" . $nowmonth . "");
    }

    public function _getTimeTableList($uid)
    {
        $times = M('times');
        $bonus = M('bonus');
        $boid = 0;
        $nowdate = strtotime(date('Y-m-d')) + 3600 * 24 - 1;
        $settime_two['benqi'] = $nowdate;
        $settime_two['type'] = 0;
        $trs = $times->where($settime_two)->find();
        if (! $trs) {
            $rs3 = $times->where('type=0')
                ->order('id desc')
                ->find();
            if ($rs3) {
                $data['shangqi'] = $rs3['benqi'];
                $data['benqi'] = $nowdate;
                $data['is_count'] = 0;
                $data['type'] = 0;
            } else {
                $data['shangqi'] = strtotime('2010-01-01');
                $data['benqi'] = $nowdate;
                $data['is_count'] = 0;
                $data['type'] = 0;
            }
            $shangqi = $data['shangqi'];
            $benqi = $data['benqi'];
            unset($rs3);
            $boid = $times->add($data);
            unset($data);
        } else {
            $shangqi = $trs['shangqi'];
            $benqi = $trs['benqi'];
            $boid = $trs['id'];
        }
        $_SESSION['BONUSDID'] = $boid;
        $brs = $bonus->where("uid={$uid} AND did={$boid}")->find();
        if ($brs) {
            $bid = $brs['id'];
        } else {
            $frs = $this->where("id={$uid}")
                ->field('id,user_id')
                ->find();
            $data = array();
            $data['did'] = $boid;
            $data['uid'] = $frs['id'];
            $data['user_id'] = $frs['user_id'];
            $data['e_date'] = $benqi;
            $data['s_date'] = $shangqi;
            $bid = $bonus->add($data);
        }
        return $bid;
    }

    public function gupiaoAdd($UID = 0, $buy_point)
    {
        // 添加 到数据表
        if ($UID > 0) {
            $where = array();
            $where['id'] = $UID;
            $frs = $this->where($where)->find();
            
            $this->execute('UPDATE __TABLE__ SET `live_gupiao`=`live_gupiao`+' . $buy_point . ' WHERE id=' . $UID);
            
            $data = array();
            $history = M('history');
            
            $data['user_id'] = $frs['user_id'];
            $data['uid'] = $UID;
            $data['action_type'] = 19;
            
            $data['pdt'] = time();
            
            $data['epoints'] = $buy_point;
            
            $data['bz'] = '注册赠送股票点值';
            
            $data['did'] = 0;
            $data['type'] = 1;
            $data['allp'] = 0;
            
            $result = $history->add($data);
            unset($data, $history);
        }
    }

    public function buy_pointAdd($UID = 0, $buy_point)
    {
        // 添加 到数据表
        if ($UID > 0) {
            $where = array();
            $where['id'] = $UID;
            $frs = $this->where($where)->find();
            
            $this->execute('UPDATE __TABLE__ SET `buy_point`=`buy_point`+' . $buy_point . ' WHERE id=' . $UID);
            
            $data = array();
            $history = M('history');
            
            $data['user_id'] = $frs['user_id'];
            $data['uid'] = $UID;
            $data['action_type'] = 19;
            
            $data['pdt'] = time();
            
            $data['epoints'] = $buy_point;
            
            $data['bz'] = '注册赠送购物点值';
            
            $data['did'] = 0;
            $data['type'] = 1;
            $data['allp'] = 0;
            
            $result = $history->add($data);
            unset($data, $history);
        }
    }

    public function get_tree($UID = 0, &$user_list)
    {
        if ($UID > 0) {
            $where = array();
            $where['id'] = $UID;
            $frs = $this->where($where)
                ->field('id,register_award,user_id,re_id ')
                ->find();
            $user_list[] = $frs;
            $this->get_tree($frs['re_id'], $user_list);
        }
    }

    public function get_register_award_tree(&$user_list, $tree_list, &$max_register_award)
    {
        $reward = 0;
        
        $real_reward = 0;
        $register_award = 0;
        foreach ($tree_list as $key => $vo) {
            if ($register_award < $vo['register_award']) {
                
                if ($key == 0) {
                    $real_reward = $vo['register_award'];
                } else {
                    
                    $real_reward = $vo['register_award'] - $register_award;
                }
                $max_register_award = $vo['register_award'];
                $vo['real_reward'] = $real_reward;
                
                // $reward = $real_reward;
                
                $register_award = $vo['register_award'];
                $user_list[] = $vo;
            }
        }
    }

    public function add_register_award($userInfo, $txt, $money)
    {
        $user_list = array();
        $this->get_tree($userInfo['re_id'], $user_list);
        $tree_list = array();
        $max_register_award = 0;
        $this->get_register_award_tree($tree_list, $user_list, $max_register_award);
        
        foreach ($tree_list as $key => $vo) {
            $where = array();
            $where['id'] = $vo['id'];
            $frs = $this->where($where)->find();
            $fee = M('fee')->find();
            $s9 = explode('|', $fee['s9']);
            $agent_cash = $money * $vo['real_reward'] * 0.01 * (100 - $fee['str13']) * 0.01;
            $this->execute('UPDATE __TABLE__ SET `agent_cash`=`agent_cash`+' . $agent_cash . ' WHERE id=' . $frs['id']);
            
            $data = array();
            $history = M('history');
            
            $data['user_id'] = $userInfo['user_id'];
            $data['uid'] = $frs['id'];
            $data['action_type'] = 19;
            
            $data['pdt'] = time();
            
            $data['epoints'] = $agent_cash;
            
            $data['bz'] = $txt . '注册积分【趴点奖励】';
            
            $data['did'] = 0;
            $data['type'] = 1;
            $data['allp'] = 0;
            
            $result = $history->add($data);
            $agent_cash = $money * $vo['real_reward'] * 0.01 * $fee['str13'] * 0.01;
            $this->execute('UPDATE __TABLE__ SET `agent_xf`=`agent_xf`+' . $agent_cash . ' WHERE id=' . $frs['id']);
            
            $data = array();
            $history = M('history');
            
            $data['user_id'] = $userInfo['user_id'];
            $data['uid'] = $frs['id'];
            $data['action_type'] = 19;
            
            $data['pdt'] = time();
            
            $data['epoints'] = $agent_cash;
            
            $data['bz'] = $txt . '复投积分【趴点奖励】';
            
            $data['did'] = 0;
            $data['type'] = 1;
            $data['allp'] = 0;
            
            $result = $history->add($data);
            $this->rw_bonus3($frs['id'], $frs['user_id'], 3, $money * $vo['real_reward'] * 0.01);
        }
    }

    public function xiangJiao_tree($Pid = 0, $DanShu = 1)
    {
        // ========================================== 往上統計單數
        $where = array();
        $where['id'] = $Pid;
        $field = 'treeplace,father_id';
        $vo = $this->where($where)
            ->field($field)
            ->find();
        if ($vo) {
            $Fid = $vo['father_id'];
            $TPe = $vo['treeplace'];
            $table = $this->tablePrefix . 'fck';
            if ($TPe == 0 && $Fid > 0) {
                $this->execute("update " . $table . " Set `l`=l-$DanShu   where `id`=" . $Fid);
            } elseif ($TPe == 1 && $Fid > 0) {
                $this->execute("update " . $table . " Set `r`=r-$DanShu  where `id`=" . $Fid);
            } elseif ($TPe == 2 && $Fid > 0) {
                $this->execute("update " . $table . " Set `lr`=lr-$DanShu  where `id`=" . $Fid);
            }
            
            if ($Fid > 0)
                $model = $this->find($Fid);
            $this->execute("update " . $table . " Set  `shangqi_l`=0,shangqi_r=0   where `id`=" . $Fid);
            if ($model['l'] > $model['r']) {
                $value = $model['l'] - $model['r'];
                $this->execute("update " . $table . " Set  `shangqi_l`=$value   where `id`=" . $Fid);
            } else {
                $value = $model['r'] - $model['l'];
                $this->execute("update " . $table . " Set `shangqi_r`=$value  where `id`=" . $Fid);
            }
            $this->xiangJiao_tree($Fid, $DanShu);
        }
        unset($where, $field, $vo);
    }

    // 數據庫名稱
    public function xiangJiao_tree2($Pid = 0, $DanShu = 1)
    {
        // ========================================== 往上統計單數
        $where = array();
        $where['id'] = $Pid;
        $field = 'treeplace,father_id';
        $vo = $this->where($where)
            ->field($field)
            ->find();
        if ($vo) {
            $Fid = $vo['father_id'];
            $TPe = $vo['treeplace'];
            $table = $this->tablePrefix . 'fck';
            if ($TPe == 0 && $Fid > 0) {
                $this->execute("update " . $table . " Set `l`=l+$DanShu, `shangqi_l`=shangqi_l+$DanShu  where `id`=" . $Fid);
            } elseif ($TPe == 1 && $Fid > 0) {
                $this->execute("update " . $table . " Set `r`=r+$DanShu, `shangqi_r`=shangqi_r+$DanShu  where `id`=" . $Fid);
            } elseif ($TPe == 2 && $Fid > 0) {
                $this->execute("update " . $table . " Set `lr`=lr+$DanShu, `shangqi_lr`=shangqi_lr+$DanShu  where `id`=" . $Fid);
            }
            if ($Fid > 0) {
                
                $model = $this->find($Pid);
                if ($model['is_pay'] == 2) {
                    $model['f4'] = 0;
                }
                if ($model['u_level'] == 1) {
                    $model['f4'] = 1;
                } else if ($model['u_level'] == 2) {
                    $model['f4'] = 3;
                } else if ($model['u_level'] == 3) {
                    $model['f4'] = 5;
                } else if ($model['u_level'] == 4) {
                    $model['f4'] = 10;
                } else if ($model['u_level'] == 5) {
                    $model['f4'] = 30;
                } else if ($model['u_level'] == 6) {
                    $model['f4'] = 50;
                } else if ($model['u_level'] == 7) {
                    $model['f4'] = 100;
                }
                $value = $model['r'] + $model['l'] + $model['u_level'];
                if ($TPe == 0 && $Fid > 0) {
                    $this->execute("update " . $table . " Set  `l`=$value   where `id`=" . $Fid);
                } elseif ($TPe == 1 && $Fid > 0) {
                    $this->execute("update " . $table . " Set  `r`=$value   where `id`=" . $Fid);
                }
                
                $model = $this->find($Fid);
                $this->execute("update " . $table . " Set  `shangqi_l`=0,shangqi_r=0   where `id`=" . $Fid);
                if ($model['l'] > $model['r']) {
                    $value = $model['l'] - $model['r'];
                    $this->execute("update " . $table . " Set  `shangqi_l`=$value   where `id`=" . $Fid);
                } else {
                    $value = $model['r'] - $model['l'];
                    $this->execute("update " . $table . " Set `shangqi_r`=$value  where `id`=" . $Fid);
                }
                
                $this->xiangJiao_tree2($Fid, $DanShu);
            }
        }
        unset($where, $field, $vo);
    }

    public function add_peisong($ID = 0, $agent_gp)
    {
        $fck = M('fck');
        $mrs = $fck->where('id=' . $ID)->find();
        // 添加 到数据表
        $data = array();
        $huikui = M('peisong');
        $data['uid'] = $mrs['id'];
        $data['user_id'] = $mrs['user_id'];
        $data['user_name'] = $mrs['user_name'];
        $data['addtime'] = time();
        $data['get_level'] = $mrs['get_level'];
        $data['live_gupiao'] = $mrs['live_gupiao'];
        $data['agent_gp'] = $agent_gp;
        
        $fee = M('fee');
        
        $fee_rs = $fee->find(1);
        
        $data['gp_one'] = $fee_rs['gp_one'];
        
        $huikui->add($data);
        unset($data, $huikui);
    }
}
?>
<?php

class GupiaoAction extends CommonAction
{

    function _initialize()
    {
        ob_clean();
        $this->_inject_check(0); // 调用过滤函数
        header("Content-Type:text/html; charset=utf-8");
        $this->_checkUser();
        // $this->gp_up_down_pd();
        // $this->auto_have_LBN();
        // $this->force_sell_gp();
        $this->stock_past_due(); // /查看是否過一天 股票(股)
    }
    
    // 二级验证
    public function Cody()
    {
        $this->_checkUser();
        $UrlID = (int) $_GET['c_id'];
        if (! empty($_SESSION['user_pwd2'])) {
            // unset($_SESSION['user_pwd3']);//清空二级输入的密码
            $url = __URL__ . "/codys/Urlsz/$UrlID";
            $this->_boxx($url);
            exit();
        }
        $thisa = $this->getActionName();
        $this->assign('thisa', $thisa);
        if (empty($UrlID)) {
            $this->error('二级密码错误!');
            exit();
        }
        $cody = M('cody');
        $list = $cody->where("c_id=$UrlID")->getField('c_id');
        if (! empty($list)) {
            $this->assign('vo', $list);
            $this->display('Public:cody');
            exit();
        } else {
            $this->error('二级密码错误!');
            exit();
        }
    }
    
    // 二级验证后调转页面
    public function Codys()
    {
        $this->_checkUser();
        $Urlsz = (int) $_POST['Urlsz'];
        if (empty($_SESSION['user_pwd2'])) {
            $pass = $_POST['oldpassword'];
            $fck = M('fck');
            if (! $fck->autoCheckToken($_POST)) {
                // $this->error('页面过期请刷新页面!');
                // exit();
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
                $_SESSION['UrlszUserpass'] = 'MyssQiCheng'; // 求购股票(股)
                $bUrl = __URL__ . '/buyGPform';
                $this->_boxx($bUrl);
                break;
            case 2:
                $_SESSION['UrlszUserpass'] = 'sellgupiao'; // 出卖股票(股)
                $bUrl = __URL__ . '/sellGPform';
                $this->_boxx($bUrl);
                break;
            case 3:
                $_SESSION['UrlszUserpass'] = 'gpHistory'; // 股票(股)买卖记录
                $bUrl = __URL__ . '/sellGPform_N';
                $this->_boxx($bUrl);
                break;
            case 4:
                $_SESSION['UrlszUserpass'] = 'gpHistory'; // 股票(股)买卖记录
                $bUrl = __URL__ . '/alllistGP';
                $this->_boxx($bUrl);
                break;
            case 5:
                $_SESSION['UrlPTPass'] = 'adminsetGP';
                $bUrl = __URL__ . '/adminsetGP';
                $this->_boxx($bUrl);
                break;
            case 7:
                $_SESSION['UrlszUserpass'] = 'gpHistory';
                $bUrl = __URL__ . '/buylist';
                $this->_boxx($bUrl);
                break;
            case 8:
                $_SESSION['UrlszUserpass'] = 'gpHistory';
                $bUrl = __URL__ . '/selllist';
                $this->_boxx($bUrl);
                break;
            default:
                $this->error('二级密码错误!');
                break;
        }
    }

    public function tradingfloor()
    { // 交易大厅
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $GPmj = M('gupiao');
        $fck = M('fck');
        $fee = M('fee');
        
        $ttrs = $GPmj->where('id>0')
            ->field('id,uid')
            ->select();
        foreach ($ttrs as $tors) {
            $thid = $tors['id'];
            $tuid = $tors['uid'];
            $cs = $fck->where('id=' . $tuid)
                ->field('id,user_id')
                ->find();
            if (! $cs) {
                $GPmj->where('id=' . $thid)->delete();
            }
        }
        
        $rs = $fee->find();
        $one_price = $rs['str1'];
        $dan = $rs['str2'];
        
        $ys_gp = $rs['str8'];
        $pt_gp = $rs['str24'];
        $gj_gp = $rs['str25'];
        
        $this->assign('ys_gp', $ys_gp);
        $this->assign('pt_gp', $pt_gp);
        $this->assign('gj_gp', $gj_gp);
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        import("@.ORG.ZQPage"); // 导入分页类
        
        $where = 'xt_gupiao.uid>0 and xt_gupiao.lnum>0 and xt_gupiao.type=1 and xt_gupiao.status=0 and xt_gupiao.ispay=0'; // 出售
        $map = 'xt_gupiao.uid>0 and xt_gupiao.type=0 and xt_gupiao.ispay=0'; // 求购
        
        $field = 'xt_gupiao.*';
        $field .= ',xt_fck.nickname,xt_fck.user_id';
        $join = 'left join xt_fck ON xt_fck.id=xt_gupiao.uid'; // 连表查询
        
        $count = $GPmj->where($where)
            ->field($field)
            ->join($join)
            ->Distinct(true)
            ->count(); // 出售总页数
        $count1 = $GPmj->where($map)
            ->field($field)
            ->join($join)
            ->Distinct(true)
            ->count(); // 求购总页数
        
        $listrows = 15; // 每页显示的记录数
        
        $Page = new ZQPage($count, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        
        $this->assign('page', $show); // 分页变量输出到模板
        $Page1 = new ZQPage($count1, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show1 = $Page1->show(); // 分页变量
        $this->assign('page1', $show1); // 分页变量输出到模板
        $list = $GPmj->where($where)
            ->field($field)
            ->join($join)
            ->Distinct(true)
            ->order('ispay asc,eDate asc,id asc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        $list1 = $GPmj->where($map)
            ->field($field)
            ->join($join)
            ->Distinct(true)
            ->order('ispay asc,eDate asc,id asc')
            ->page($Page1->getPage() . ',' . $listrows)
            ->select();
        foreach ($list1 as $vov) {
            
            // $can_b[$vov['id']] = floor($vov['buy_s']/$vov['one_price']);
            $can_b[$vov['id']] = floor($vov['buy_s'] / $one_price);
        }
        $this->assign('can_b', $can_b);
        
        $this->assign('one_price', $one_price);
        $this->assign('list', $list);
        $this->assign('list1', $list1);
        $this->display();
        exit();
    }

    public function alllistGP()
    { // 股票(股)买卖界面
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $GPmj = M('gupiao');
        $fck = M('fck');
        $fee = M('fee');
        
        $ttrs = $GPmj->where('id>0')
            ->field('id,uid')
            ->select();
        foreach ($ttrs as $tors) {
            $thid = $tors['id'];
            $tuid = $tors['uid'];
            $cs = $fck->where('id=' . $tuid)
                ->field('id,user_id')
                ->find();
            if (! $cs) {
                $GPmj->where('id=' . $thid)->delete();
            }
        }
        
        $rs = $fee->find();
        // 当前股价
        $one_price = $rs['gp_one'];
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        import("@.ORG.ZQPage"); // 导入分页类
        
        $where = 'xt_gupiao.uid>0 and xt_gupiao.lnum>0 and xt_gupiao.type=1 and xt_gupiao.status=0 and xt_gupiao.ispay=0'; // 出售
        $map = 'xt_gupiao.uid>0 and xt_gupiao.type=0 and xt_gupiao.ispay=0'; // 求购
        
        $field = 'xt_gupiao.*';
        $field .= ',xt_fck.nickname,xt_fck.user_id';
        $join = 'left join xt_fck ON xt_fck.id=xt_gupiao.uid'; // 连表查询
        
        $count = $GPmj->where($where)
            ->field($field)
            ->join($join)
            ->Distinct(true)
            ->count(); // 出售总页数
        $count1 = $GPmj->where($map)
            ->field($field)
            ->join($join)
            ->Distinct(true)
            ->count(); // 求购总页数
        
        $listrows = 15; // 每页显示的记录数
        
        $Page = new ZQPage($count, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        
        $this->assign('page', $show); // 分页变量输出到模板
        $Page1 = new ZQPage($count1, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show1 = $Page1->show(); // 分页变量
        $this->assign('page1', $show1); // 分页变量输出到模板
        $list = $GPmj->where($where)
            ->field($field)
            ->join($join)
            ->Distinct(true)
            ->order('ispay asc,one_price asc,eDate asc,id asc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        $list1 = $GPmj->where($map)
            ->field($field)
            ->join($join)
            ->Distinct(true)
            ->order('ispay asc,one_price asc,eDate asc,id asc')
            ->page($Page1->getPage() . ',' . $listrows)
            ->select();
        foreach ($list1 as $vov) {
            
            $can_b[$vov['id']] = floor($vov['buy_s'] / $one_price);
        }
        $this->assign('can_b', $can_b);
        
        $this->assign('one_price', $one_price);
        $this->assign('list', $list);
        $this->assign('list1', $list1);
        
        $gp = M('gp');
        $ts = $gp->where('id>0')->find();
        $all_num = $ts['turnover'];
        
        $all_num = number_format($all_num, 0, "", ",");
        $this->assign('all_num', $all_num); // 总成交量
        
        $this->display('alllistGP');
        exit();
    }

    public function allTradelist()
    { // 股票(股)买卖界面
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $t_orders = M('t_orders');
        $fck = M('fck');
        $fee = M('fee');
        
        $rs = $fee->find();
        // 当前股价
        $one_price = $rs['gp_one'];
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        import("@.ORG.ZQPage"); // 导入分页类
        $where['type'] = 0;
        $count = $t_orders->where($where)->

        count(); // 出售总页数
        $where['type'] = 1;
        $count1 = $t_orders->where($where)->count(); // 求购总页数
        
        $listrows = 15; // 每页显示的记录数
        
        $Page = new ZQPage($count, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        
        $this->assign('page', $show); // 分页变量输出到模板
        $Page1 = new ZQPage($count1, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show1 = $Page1->show(); // 分页变量
        $this->assign('page1', $show1); // 分页变量输出到模板
        $where['type'] = 0;
        $list = $t_orders->where($where)
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        foreach ($list as $k => $vov) {
            $user = M('fck')->find($vov['uid']);
            
            $list[$k]['member_id'] = $user['user_id'];
            $list[$k]['trade_total'] = $vov['price'] * $vov['num'];
            $list[$k]['trade_num'] = $vov['num'] - $vov['shengyu_num'];
            $list[$k]['num'] = (int) $vov['num'];
            $list[$k]['shengyu_num'] = (int) $vov['shengyu_num'];
        }
        
        $where['type'] = 1;
        $list1 = $t_orders->where($where)->

        select();
        foreach ($list1 as $k => $vov) {
            $user = M('fck')->find($vov['uid']);
            $list1[$k]['member_id'] = $user['user_id'];
            $list1[$k]['trade_total'] = $vov['price'] * $vov['num'];
            $list1[$k]['trade_num'] = $vov['num'] - $vov['shengyu_num'];
            $list1[$k]['num'] = (int) $vov['num'];
            $list1[$k]['shengyu_num'] = (int) $vov['shengyu_num'];
        }
        $this->assign('can_b', $can_b);
        
        $this->assign('one_price', $one_price);
        $this->assign('list', $list);
        $this->assign('list1', $list1);
        
        $gp = M('gp');
        $ts = $gp->where('id>0')->find();
        $all_num = $ts['turnover'];
        
        $all_num = number_format($all_num, 0, "", ",");
        $this->assign('all_num', $all_num); // 总成交量
        
        $this->display('allTradelist');
        exit();
    }
    
    // 购买原始股
    public function buyyuans()
    {
        if (! empty($_SESSION[C('USER_AUTH_KEY')])) {
            $GPmj = M('gupiao');
            $fck = M('fck');
            $fee = M('fee')->field('str1,str8')->find();
            $close_gp = $fee['str8']; // 原始股票(股)开关,1为关闭
            if ($close_gp == 1) {
                $this->error("原始股票(股)尚未开放交易！");
                exit();
            }
            
            $one_price = 0.1;
            $gp_info = $this->gpInfo(); // 股票(股)的信息
            $id = $_SESSION[C('USER_AUTH_KEY')];
            $user_rs = $fck->where("id=$id")
                ->field("agent_use")
                ->find();
            $game_m = $user_rs['agent_use']; // 剩余的购物券
            $this->assign('game_m', $game_m);
            $this->assign('live_gp', $gp_info[6]); // 剩余的股票(股)
            $this->assign('one_price', $one_price);
            $this->display();
        } else {
            $this->error("错误！");
        }
    }
    
    // 购买原始股处理
    public function buyyuansAC()
    {
        if (! empty($_SESSION[C('USER_AUTH_KEY')])) {
            $one_price = $_POST['one_price']; // 表单传来的股票(股)单价
            
            $fck = M('fck');
            $fee = M('fee');
            $frse = $fee->field('str1,str8,str9')->find();
            $close_gp = $frse['str8']; // 原始股票(股)开关,1为关闭
            $yuan_num = $frse['str9']; // 原始股票(股)剩余数量
            if ($close_gp == 1) {
                $this->error("原始股票(股)尚未开放交易！");
                exit();
            }
            
            $id = $_SESSION[C('USER_AUTH_KEY')];
            // 检查交易密码
            $user_info = $fck->where("id=$id")
                ->field("agent_use,user_id,passopentwo")
                ->find();
            $use = $user_info['agent_use']; // 可以的游戏币
            $gp_pwd = trim($_POST['gp_pwd']);
            if (md5($gp_pwd) != $user_info['passopentwo']) {
                $this->error("三级密码不正确！");
                exit();
            }
            
            $sNun = (int) $_POST['sNun']; // 购买股票(股)的数量
            
            if (empty($sNun)) {
                $this->error('购买原始股票(股)的数量不能为空或者小于等于0！');
                exit();
            }
            if ($sNun != floor($sNun)) {
                $this->error('温馨提示：您输入数量必须是整数。请检验后再试！');
                exit();
            }
            
            if ($sNun > $yuan_num) {
                $this->error('购买不成功，原始股剩余数量不足！');
                exit();
            }
            
            $buy = $sNun * $one_price; // 购买股票(股)所需的金额
            $may = (int) ($use / $one_price);
            
            if (bccomp($buy, $use, 2) > 0) {
                $this->error('温馨提示：你的购物券账户余额不足 ' . $buy . '。请检验后再试！');
                exit();
            }
            
            $fck->execute("UPDATE __TABLE__ SET yuan_gupiao=yuan_gupiao+$sNun,agent_use=agent_use-$buy WHERE `id`=$id");
            $fee->execute("UPDATE __TABLE__ SET str9=str9-$sNun");
            
            $bUrl = __URL__ . '/buyyuans';
            $this->_box(1, '恭喜您，原始股票(股)购买成功！', $bUrl, 3);
        } else {
            $this->error("错误！");
        }
    }
    
    // 检查股票(股)开关是否开发
    private function check_gpopen($type = 0)
    {
        $fee_rs = M('fee')->field('gp_kg')->find();
        $gp_kg = $fee_rs['gp_kg'];
        if ($type == 1) {
            if ($gp_kg == 1) {
                $this->error("股票(股)尚未开放交易！");
                exit();
            }
        } else {
            $this->assign('close_gp', $gp_kg);
        }
    }

    function buys()
    {
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $t_orders = M('t_orders');
        $fee = M('fee');
        $fee_s = $fee->field('*')->find();
        
        $per_num = 10;
        $uid = $id;
        
        $type = 1;
        
        if ($uid > 1) {
            $where['uid'] = $uid;
        }
        
        $where['type'] = $type;
        
        $begin = I('time1');
        $end = I('time2');
        if (! empty($begin)) {
            $begin = strtotime($begin);
        }
        if (! empty($end)) {
            $end = strtotime($end);
        }
        if ($begin && $end) {
            $where['ctime'] = array(
                'between',
                "$begin,$end"
            );
        }
        
        import("@.ORG.ZQPage"); // 導入分頁類
        $count = $t_orders->where($where)->count(); // 總頁數
        $listrows = 10; // 每頁顯示的記錄數
        
        $Page = new ZQPage($count, $listrows, 1, 0, 3, $where);
        // ===============(總頁數,每頁顯示記錄數,css樣式 0-9)
        $show = $Page->show(); // 分頁變量
        $list = $t_orders->where($where)
            ->page($Page->getPage() . ',' . $listrows)
            ->order('id desc')
            ->select();
        $trade = $fee_s['gp_perc'];
        foreach ($list as $k => $v) {
            $list[$k]['total'] = floatval($v['price'] * $v['num']);
            $list[$k]['shouxufei'] = 0;
            if ($v['type'] == 0) {
                $list[$k]['shouxufei'] = floatval($v['price'] * $v['num'] * $trade / 100);
            }
            $user = M('fck')->find($v['uid']);
            
            $list[$k]['user_id'] = $user['user_id'];
            
            $list[$k]['buy_s'] = floatval($v['shengyu_num'] * $v['price']);
            
            $trade_total_num = M('t_trans')->where('type=1 and buyid=' . $v['id'])->sum('num');
            if ($trade_total_num == null) {
                $trade_total_num = 0;
            }
            $list[$k]['trade_total_num'] = $trade_total_num;
            
            $trade_total_money = M('t_trans')->where('type=1 and buyid=' . $v['id'])->sum('price');
            if ($trade_total_money == null) {
                $trade_total_money = 0;
            }
            $list[$k]['trade_total_money'] = $trade_total_num * $trade_total_money;
        }
        
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('page_num', $listrows);
        
        $gupiao = M('gupiao');
        $fck = M('fck');
        $fee = M('fee');
        $this->check_gpopen();
        
        $fee_rs = $fee->field('gp_one,gp_fxnum,gp_senum')->find(1);
        // 当前股价
        $one_price = $fee_rs['gp_one'];
        $gp_fxnum = $fee_rs['gp_fxnum']; // 涨价数量
        $gp_senum = $fee_rs['gp_senum']; // 已售出
        $ca_gp_n = $gp_fxnum - $gp_senum; // 差多少涨价
        $ca_gp_p = ((int) ($one_price * $ca_gp_n * 1000)) / 1000; // 差多少钱涨价
        $this->assign('gp_upnum', $ca_gp_n);
        $this->assign('gp_uppri', $ca_gp_p);
        // 股票(股)的信息
        $gp_info = $this->gpInfo();
        // 正在求购的股票(股)
        $gping_num = $this->buy_and_ing(0);
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        $user_rs = $fck->where("id=$id")
            ->field("agent_use")
            ->find();
        $game_m = $user_rs['agent_use']; // 剩余的股票(股)交易账户余额
        $this->assign('game_m', $game_m);
        
        $this->assign('one_price', $one_price);
        $this->assign('live_gp', $gp_info[0]); // 剩余的股票(股)
        $this->assign('gping_num', $gping_num); // 正在求购的股票(股)
        
        if (! empty($begin)) {
            $begin = date("Y-m-d", $begin);
        }
        if (! empty($end)) {
            $end = date("Y-m-d", $end);
        }
        
        $this->assign('time1', $begin);
        $this->assign('time2', $end);
        
        $_SESSION['GP_Sesion_Buy'] = 'OK';
        
        $this->display();
    }
    // 求购股票(股)列表
    public function buyGPform()
    {
        if (! empty($_SESSION[C('USER_AUTH_KEY')])) {
            $gupiao = M('gupiao');
            $fck = M('fck');
            $fee = M('fee');
            $this->check_gpopen();
            
            $fee_rs = $fee->field('gp_one,gp_fxnum,gp_senum')->find(1);
            // 当前股价
            $one_price = $fee_rs['gp_one'];
            $gp_fxnum = $fee_rs['gp_fxnum']; // 涨价数量
            $gp_senum = $fee_rs['gp_senum']; // 已售出
            $ca_gp_n = $gp_fxnum - $gp_senum; // 差多少涨价
            $ca_gp_p = ((int) ($one_price * $ca_gp_n * 1000)) / 1000; // 差多少钱涨价
            $this->assign('gp_upnum', $ca_gp_n);
            $this->assign('gp_uppri', $ca_gp_p);
            // 股票(股)的信息
            $gp_info = $this->gpInfo();
            // 正在求购的股票(股)
            $gping_num = $this->buy_and_ing(0);
            
            $id = $_SESSION[C('USER_AUTH_KEY')];
            
            import("@.ORG.ZQPage"); // 导入分页类
            $where = 'type=0 and id>0 and uid=' . $id;
            $field = '*';
            
            $count = $gupiao->where($where)
                ->field($field)
                ->count(); // 总页数
            $listrows = 10; // 每页显示的记录数
            $Page = new ZQPage($count, $listrows, 1);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $list = $gupiao->where($where)
                ->field($field)
                ->order('eDate desc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            foreach ($list as $vvv) {
                $buy_s = $vvv['buy_s'];
                $is_pay = $vvv['ispay'];
                if ($is_pay == 1) {
                    $can_b = 0;
                } else {
                    $can_b = floor($buy_s / $one_price);
                }
                $tvo[$vvv['id']] = $can_b;
            }
            $this->assign('list', $list);
            $this->assign('tvo', $tvo);
            
            $user_rs = $fck->where("id=$id")
                ->field("agent_use")
                ->find();
            $game_m = $user_rs['agent_use']; // 剩余的股票(股)交易账户余额
            $this->assign('game_m', $game_m);
            
            $this->assign('one_price', $one_price);
            $this->assign('live_gp', $gp_info[0]); // 剩余的股票(股)
            $this->assign('gping_num', $gping_num); // 正在求购的股票(股)
            
            $_SESSION['GP_Sesion_Buy'] = 'OK';
            
            $this->display('buyGPform');
        } else {
            $this->error("错误！");
        }
    }

    public function sellGPform()
    { // 出售股票(股)
        if (empty($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error("错误");
            exit();
        }
        $GPmj = M('gupiao');
        $gp_sell = M('gp_sell');
        $fck = M('fck');
        
        $fee_rs = M('fee')->field('gp_one,gp_kg')->find();
        // 当前股价
        $one_price = $fee_rs['gp_one'];
        $close_gp = $fee_rs['gp_kg']; // 股票(股)交易开关,1为关
                                      // 股票(股)的信息
        $gp_info = $this->gpInfo();
        // 正在出售的股票(股)
        $gping_num = $this->buy_and_ing(1);
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        import("@.ORG.ZQPage"); // 导入分页类
        
        $where = 'type=1 and id>0 and uid=' . $id;
        $field = '*';
        
        $count = $GPmj->where($where)
            ->field($field)
            ->count(); // 总页数
        $listrows = 15; // 每页显示的记录数
        $Page = new ZQPage($count, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        
        $list = $GPmj->where($where)
            ->field($field)
            ->order('eDate desc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        $this->assign('list', $list);
        
        $user_rs = $fck->where("id=$id")
            ->field("agent_gp")
            ->find();
        $game_m = $user_rs['agent_gp']; // 剩余的股票(股)
        $this->assign('game_m', $game_m);
        
        $this->assign('one_price', $one_price);
        $this->assign('live_gp', $gp_info[0]); // 剩余的股票(股)
        $this->assign('gping_num', $gping_num); // 正在出售的股票(股)
        $this->assign('close_gp', $close_gp);
        
        $twhere = array();
        $twhere['uid'] = array(
            'eq',
            $id
        );
        $twhere['sNun'] = array(
            'gt',
            0
        );
        // $twhere['is_over'] = array('eq',0);
        $field1 = "*";
        
        $tcount = $gp_sell->where($twhere)
            ->field($field1)
            ->count(); // 总页数
        $Page2 = new ZQPage($tcount, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show2 = $Page2->show(); // 分页变量
        $this->assign('page_t', $show2); // 分页变量输出到模板
        
        $list2 = $gp_sell->where($twhere)
            ->field($field1)
            ->order('id desc')
            ->page($Page2->getPage() . ',' . $listrows)
            ->select();
        $this->assign('list_t', $list2);
        
        $_SESSION['GP_Sesion_Sell'] = 'OK';
        
        $this->display('sellGPform');
    }

    public function sellGPform_N()
    { // 出售股票(股)
        $this->_Admin_checkUser();
        if (empty($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error("错误");
            exit();
        }
        $GPmj = M('gupiao');
        $fck = M('fck');
        
        $this->check_gpopen();
        
        $fee_rs = M('fee')->field('gp_one')->find();
        // 当前股价
        $one_price = $fee_rs['gp_one'];
        // 股票(股)的信息
        $gp_info = $this->gpInfo();
        // 正在出售的股票(股)
        $gping_num = $this->buy_and_ing(1);
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        import("@.ORG.ZQPage"); // 导入分页类
        
        $where = 'type=1 and id>0 and uid=' . $id;
        $field = '*';
        
        $count = $GPmj->where($where)
            ->field($field)
            ->count(); // 总页数
        $listrows = 15; // 每页显示的记录数
        $Page = new ZQPage($count, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        
        $list = $GPmj->where($where)
            ->field($field)
            ->order('eDate desc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        $user_rs = $fck->where("id=$id")
            ->field("agent_gp")
            ->find();
        $game_m = $user_rs['agent_gp']; // 剩余的股票(股)
        $this->assign('game_m', $game_m);
        
        $this->assign('one_price', $one_price);
        $this->assign('live_gp', $gp_info[0]); // 剩余的股票(股)
        $this->assign('gping_num', $gping_num); // 正在出售的股票(股)
        $this->assign('list', $list);
        
        $aars = $fck->where('id>1')->sum('live_gupiao');
        if (empty($aars))
            $aars = 0;
        $this->assign('aars', $aars);
        
        $_SESSION['GP_Sesion_Sell'] = 'OK';
        
        $this->display();
    }
    
    // 股票(股)买卖历史
    public function gpHistory()
    {
        if (! empty($_SESSION[C('USER_AUTH_KEY')]) && ($_SESSION['UrlszUserpass'] == 'gpHistory')) {
            $id = $_SESSION[C('USER_AUTH_KEY')];
            $GPmj = M('hgupiao');
            $fck = M('fck');
            $fee = M('fee');
            
            $rs = $fee->find();
            $one_price = $rs['str1']; // 当前股票(股)价格
            $gp_info = $this->gpInfo(); // 股票(股)的信息
            $gping_num0 = $this->buy_and_ing(0); // 正在求购的股票(股)
            $gping_num1 = $this->buy_and_ing(1); // 正在出售的股票(股)
            
            import("@.ORG.ZQPage"); // 导入分页类
            
            $where = "uid=$id"; // 买卖记录
            $field = '*';
            
            $count = $GPmj->where($where)
                ->field($field)
                ->count(); // 出售总页数
            $listrows = 15; // 每页显示的记录数
            
            $Page = new ZQPage($count, $listrows, 1);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            
            $list = $GPmj->where($where)
                ->field($field)
                ->order('eDate desc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            $this->assign('one_price', $one_price);
            
            $this->assign('list', $list);
            $this->assign('live_gp', $gp_info[0]); // 剩余的股票(股)
            $this->assign('all_in_gp', $gp_info[1]); // 成功售出
            $this->assign('all_out_gp', $gp_info[2]); // 成功买入
            $this->assign('gping_num0', $gping_num0); // 正在求购的股票(股)
            $this->assign('gping_num1', $gping_num1); // 正在求购的股票(股)
            $this->display();
            exit();
        }
    }

    public function sellGP_Next()
    { // 出售股票(股)
        if (empty($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error("错误！");
            exit();
        }
        if (empty($_SESSION['GP_Sesion_Sell'])) {
            $this->error("刷新操作错误！");
            exit();
        }
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $hgp = M('hgupiao');
        $gupiao = M('gupiao'); //
        $gp_sell = M('gp_sell'); // 售股信息表
        $fck = D('Fck');
        $fee = M('fee');
        
        $this->gpxz_sell_a();
        
        $fee_rs = $fee->find();
        $one_price = $fee_rs['str1'];
        $this->assign('one_price', $one_price);
        $close_gp = $fee_rs['str3']; // 股票(股)交易开关,1为关闭
        $this->assign('close_gp', $close_gp);
        $jj_t11 = explode("|", $fee_rs['str13']); // 竞价出售开始时间
        $jj_t12 = explode("|", $fee_rs['str14']); // 竞价出售结束时间
        
        $start_t = $jj_t11[0] . ":" . $jj_t11[1] . ":" . $jj_t11[2]; // 组合分割的时间
        $end_t = $jj_t12[0] . ":" . $jj_t12[1] . ":" . $jj_t12[2]; // 组合分割的时间
        
        $time_ss = strtotime($start_t); // 运用时间戳对比，开始时间
        $time_se = strtotime($end_t); // 结束时间
        $now_time = strtotime(date("H:i:s", time())); // 现在时间
                                                      
        // 检查交易密码及其他
        $user_info = $fck->where("id=" . $id)
            ->field("id,live_gupiao,user_id,passopentwo,re_path,max_jy,u_level")
            ->find();
        
        $my_lv = $user_info['u_level']; // 级别
        
        $cur_one_price = $fee_rs['str1']; // 系统设置的股票(股)价格
        
        $day_week = $fee_rs['str20']; // 运行出售日期
        
        $use = $user_info['live_gupiao']; // 剩余的股票(股)
        
        if ($close_gp == 1) {
            $this->error("交易功能已经关闭！");
            exit();
        }
        
        if ($now_time < $time_ss || $now_time > $time_se) {
            $this->error("交易竞价出售时间已过！");
            exit();
        }
        
        $nowweek = date("w");
        if ($nowweek == 0) {
            if (strpos($day_week, ',7,') !== false) {
                // 允许运行
            } else {
                $this->error("今日不允许竞价出售GP！");
                exit();
            }
        } else {
            if (strpos($day_week, ',' . $nowweek . ',') !== false) {
                // 允许运行
            } else {
                $this->error("今日不允许竞价出售GP！");
                exit();
            }
        }
        
        $next_min_d = 20; // 20天才可再次交易
                          // $next_min_d = 0;//20天才可再次交易
        
        $tid = (int) $_GET['tid'];
        
        $where = array();
        $where['id'] = array(
            'eq',
            $tid
        );
        $where['is_over'] = array(
            'eq',
            0
        );
        $where['uid'] = array(
            'eq',
            $id
        );
        
        $trs = $gp_sell->where($where)->find();
        if ($trs) {
            $tgpid = $trs['id'];
            $gpuid = $trs['uid'];
            $last_d = $trs['sell_date'];
            $next_d = $last_d + 3600 * 24 * $next_min_d;
            $now_ddd = mktime();
            if ($next_d > $now_ddd) {
                $this->error("每笔GP出售后必须等待 " . $next_min_d . " 天才能再次出售！");
                exit();
            }
            $ln_num = $trs['sell_ln']; // 剩余
            $sell_m = $trs['sell_mon']; // 售出总数
            $sell_n = $trs['sell_num']; // 售出次数
            $mmsNun = $trs['sNun']; // 总量
            $chus = 3 - $sell_n; // 被除数
            if ($sell_n == 2) {
                $now_sell_num = $ln_num;
                $is_over = 1;
            } else {
                $now_sell_num = floor($ln_num / $chus);
                $is_over = 0;
            }
            $this->assign('tid', $tid);
        } else {
            $this->error("找不到此GP！");
            exit();
        }
        
        $sNun = $now_sell_num;
        if ($sNun > 0) {
            $this->assign('sNun', $sNun);
        }
        $this->display();
    }

    public function A_sellGP()
    { // 出售股票(股)
        if (empty($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error("错误！");
            exit();
        }
        if (empty($_SESSION['GP_Sesion_Sell'])) {
            $this->error("刷新操作错误！");
            exit();
        }
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $hgp = M('hgupiao');
        $gupiao = M('gupiao'); //
        $gp_sell = M('gp_sell'); // 售股信息表
        $fck = D('Fck');
        $fee = M('fee');
        
        $this->gpxz_sell_a();
        
        $fee_rs = $fee->find();
        $now_price = $fee_rs['str1'];
        // $one_price = $_POST['one_price'];//表单传来的股票(股)单价
        $one_price = $now_price;
        $close_gp = $fee_rs['str3']; // 股票(股)交易开关,1为关闭
        
        $max_price = $now_price + 0.01;
        $min_price = $now_price - 0.01;
        if ($one_price > $max_price || $one_price < $min_price) {
            $this->error("价格只能在当前价幅度1美分！");
            exit();
        }
        
        $jj_t11 = explode("|", $fee_rs['str13']); // 竞价出售开始时间
        $jj_t12 = explode("|", $fee_rs['str14']); // 竞价出售结束时间
        
        $start_t = $jj_t11[0] . ":" . $jj_t11[1] . ":" . $jj_t11[2]; // 组合分割的时间
        $end_t = $jj_t12[0] . ":" . $jj_t12[1] . ":" . $jj_t12[2]; // 组合分割的时间
        
        $time_ss = strtotime($start_t); // 运用时间戳对比，开始时间
        $time_se = strtotime($end_t); // 结束时间
        $now_time = strtotime(date("H:i:s", time())); // 现在时间
                                                      
        // 检查交易密码及其他
        $user_info = $fck->where("id=" . $id)
            ->field("id,live_gupiao,user_id,passopentwo,re_path,max_jy,u_level")
            ->find();
        
        $my_lv = $user_info['u_level']; // 级别
        
        $cur_one_price = $fee_rs['str1']; // 系统设置的股票(股)价格
        
        $day_week = $fee_rs['str20']; // 运行出售日期
        
        $use = $user_info['live_gupiao']; // 剩余的股票(股)
                                          // $gp_pwd = trim($_POST['gp_pwd']);
                                          // if(md5($gp_pwd) != $user_info['passopentwo']){
                                          // $this->error("三级密码不正确！");
                                          // exit;
                                          // }
        
        if ($close_gp == 1) {
            $this->error("交易功能已经关闭！");
            exit();
        }
        
        if ($now_time < $time_ss || $now_time > $time_se) {
            $this->error("交易竞价出售时间已过！");
            exit();
        }
        
        $nowweek = date("w");
        if ($nowweek == 0) {
            if (strpos($day_week, ',7,') !== false) {
                // 允许运行
            } else {
                $this->error("今日不允许竞价出售GP！");
                exit();
            }
        } else {
            if (strpos($day_week, ',' . $nowweek . ',') !== false) {
                // 允许运行
            } else {
                $this->error("今日不允许竞价出售GP！");
                exit();
            }
        }
        
        $next_min_d = 20; // 20天才可再次交易
                          // $next_min_d = 0;//20天才可再次交易
        
        $tid = (int) $_GET['tid'];
        
        $where = array();
        $where['id'] = array(
            'eq',
            $tid
        );
        $where['is_over'] = array(
            'eq',
            0
        );
        $where['uid'] = array(
            'eq',
            $id
        );
        
        $trs = $gp_sell->where($where)->find();
        if ($trs) {
            $tgpid = $trs['id'];
            $gpuid = $trs['uid'];
            $last_d = $trs['sell_date'];
            $next_d = $last_d + 3600 * 24 * $next_min_d;
            $now_ddd = mktime();
            if ($next_d > $now_ddd) {
                $this->error("每笔GP出售后必须等待 " . $next_min_d . " 天才能再次出售！");
                exit();
            }
            $ln_num = $trs['sell_ln']; // 剩余
            $sell_m = $trs['sell_mon']; // 售出总数
            $sell_n = $trs['sell_num']; // 售出次数
            $mmsNun = $trs['sNun']; // 总量
            $chus = 3 - $sell_n; // 被除数
            if ($sell_n == 2) {
                $now_sell_num = $ln_num;
                $is_over = 1;
            } else {
                $now_sell_num = floor($ln_num / $chus);
                $is_over = 0;
            }
            
            $last_sellmon = $sell_m + $now_sell_num;
            $last_sellnum = $sell_n + 1;
            $last_ln = $ln_num - $now_sell_num;
            
            $s_pid = $trs['id'];
            if ($last_sellnum < 3) {
                $s_last = 0;
            } else {
                $s_last = 1;
            }
        } else {
            $this->error("找不到此GP！");
            exit();
        }
        
        $swh = array();
        $swh['id'] = array(
            'eq',
            $tgpid
        );
        $swh['is_over'] = array(
            'eq',
            0
        );
        $swh['uid'] = array(
            'eq',
            $id
        );
        
        $valuearr = array(
            'sell_ln' => $last_ln,
            'sell_mon' => $last_sellmon,
            'sell_num' => $last_sellnum,
            'sell_date' => mktime(),
            'is_over' => $is_over
        );
        
        $gp_sell->where($swh)->setField($valuearr);
        
        $sNun = $now_sell_num;
        if ($sNun > 0) {
            $this->sell_GPAC($id, $user_info['user_id'], $sNun, $s_pid, $s_last, $one_price);
        }
        
        $_SESSION['GP_Sesion_Sell'] = "";
        
        $bUrl = __URL__ . '/sellGPform';
        $this->_box(1, '出售GP成功！', $bUrl, 3);
        exit();
    }

    public function force_sell_gp()
    { // 出售股票(股)
        if (! empty($_SESSION[C('USER_AUTH_KEY')])) {
            $fck = D('Fck');
            $fee = M('fee');
            $gupiao = M('gupiao');
            
            $this->check_gpopen(1);
            
            // 检查交易密码及其他
            $user_info = $fck->where("live_gupiao>0 and is_pay>0 and id>1")
                ->field("id,live_gupiao,user_id,passopen,cpzj,u_level")
                ->order('pdt asc,id asc')
                ->select();
            foreach ($user_info as $lrs) {
                
                $fee_rs = $fee->find();
                $cur_one_price = $fee_rs['gp_one']; // 系统设置的股票(股)价格
                $str12 = $fee_rs['str12'];
                $s7 = explode("|", $fee_rs['s7']);
                
                $max_num = $str12;
                $nowdate = strtotime(date('Y-m-d'));
                
                $id = $lrs['id'];
                $cpzj = $lrs['cpzj'];
                $ulevel = $lrs['u_level'];
                $prii = $s7[$ulevel - 1];
                $maxcyed = $cpzj * 10; // 最大可持有额度
                
                $use = $lrs['live_gupiao']; // 剩余的股票(股)
                $gpjiazhi = $use * $cur_one_price;
                
                if ($gpjiazhi >= $maxcyed) { // 当前持有股票(股)价值大于可持有额度时
                    $use_yes = (int) ($use * 0.2); // 剩余的股票(股)
                    
                    $sNun = $use_yes; // 出售股票(股)的数量
                    
                    if (empty($sNun) || $sNun <= 0) {
                        $sNun = 0;
                    }
                    
                    if ($sNun == floor($sNun) && $sNun > 0) {
                        
                        // 更新卖方的股票(股)信息
                        $fck->execute("UPDATE __TABLE__ SET live_gupiao=live_gupiao-" . $sNun . " WHERE `id`=" . $id . "");
                        $this->sell_GPAC($id, $lrs['user_id'], $sNun, 0, 1);
                        
                        $_SESSION['GP_Sesion_Buy'] = "OK";
                        // 有人售出股票(股)后排队收藏品自动购买股票(股)
                        $frs = $fck->where('agent_gp>0 and id=1')
                            ->order('pdt asc,id asc')
                            ->find();
                        if ($frs) {
                            $agent_gp = $frs['agent_gp'];
                            $fgpid = $frs['id'];
                            $this->auto_buyGP($fgpid, $agent_gp);
                        }
                    }
                }
            }
            unset($lrs);
        }
    }

    public function sells()
    {
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $t_orders = M('t_orders');
        $fee = M('fee');
        $fee_s = $fee->field('*')->find();
        
        $per_num = 10;
        $uid = $id;
        
        $type = 0;
        
        if ($uid > 1) {
            $where['uid'] = $uid;
        }
        
        $where['type'] = $type;
        
        $begin = I('time1');
        $end = I('time2');
        if (! empty($begin)) {
            $begin = strtotime($begin);
        }
        if (! empty($end)) {
            $end = strtotime($end);
        }
        if ($begin && $end) {
            $where['ctime'] = array(
                'between',
                "$begin,$end"
            );
        }
        
        import("@.ORG.ZQPage"); // 導入分頁類
        $count = $t_orders->where($where)->count(); // 總頁數
        $listrows = 10; // 每頁顯示的記錄數
        
        $Page = new ZQPage($count, $listrows, 1, 0, 3, $where);
        // ===============(總頁數,每頁顯示記錄數,css樣式 0-9)
        $show = $Page->show(); // 分頁變量
        $list = $t_orders->where($where)
            ->page($Page->getPage() . ',' . $listrows)
            ->order('id desc')
            ->select();
        $trade = $fee_s['gp_perc'];
        foreach ($list as $k => $v) {
            $list[$k]['total'] = floatval($v['price'] * $v['num']);
            $list[$k]['shouxufei'] = 0;
            if ($v['type'] == 0) {
                $list[$k]['shouxufei'] = floatval($v['price'] * $v['num'] * $trade / 100);
            }
            $user = M('fck')->find($v['uid']);
            
            $list[$k]['user_id'] = $user['user_id'];
            
            $list[$k]['buy_s'] = floatval($v['shengyu_num'] * $v['price']);
            
            $trade_total_num = M('t_trans')->where('type=0 and sellid=' . $v['id'])->sum('num');
            if ($trade_total_num == null) {
                $trade_total_num = 0;
            }
            $list[$k]['trade_total_num'] = $trade_total_num;
            
            $trade_total_money = M('t_trans')->where('type=0 and sellid=' . $v['id'])->sum('price');
            if ($trade_total_money == null) {
                $trade_total_money = 0;
            }
            $list[$k]['trade_total_money'] = $trade_total_num * $trade_total_money;
        }
        
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('page_num', $listrows);
        
        $one_price = $fee_s['gp_one'];
        $close_gp = $fee_s['gp_kg'];
        
        $fck = M('fck');
        
        $gping_num = $this->buy_and_ing(1);
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        import("@.ORG.ZQPage"); // 导入分页类
        
        $gp_info = $this->gpInfo();
        $user_rs = $fck->where("id=$id")
            ->field("agent_gp")
            ->find();
        $game_m = $user_rs['agent_gp']; // 剩余的股票(股)
        $this->assign('game_m', $game_m);
        
        $this->assign('one_price', $one_price);
        $this->assign('live_gp', $gp_info[0]); // 剩余的股票(股)
        $this->assign('gping_num', $gping_num); // 正在出售的股票(股)
        $this->assign('close_gp', $close_gp);
        
        if (! empty($begin)) {
            $begin = date("Y-m-d", $begin);
        }
        if (! empty($end)) {
            $end = date("Y-m-d", $end);
        }
        
        $this->assign('time1', $begin);
        $this->assign('time2', $end);
        
        $_SESSION['GP_Sesion_Sell'] = 'OK';
        
        $this->display('sells');
    }

    public function sell()
    {
        $fee = M('fee');
        $fee_s = $fee->field('*')->find();
        
        if ($fee_s['gp_kg'] == 1) {
            $this->ajaxReturn('', '暂未开市！', 0);
            exit();
        }
        
        $num = floatval($_POST['num']);
        $price = floatval($_POST['price']);
        $coin = $_POST['coin'];
        $paypwd = md5(trim($_POST['paypwd']));
        $fck = M('fck');
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $where['id'] = $id;
        $field = '*';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        
        if (! chkInt($num)) {
            $this->ajaxReturn('', '请输入数字！', 0);
            exit();
        }
        if ($num < 1) {
            $this->ajaxReturn('', '数量必须大于1！', 0);
            exit();
        }
        $gp_cnum = $rs['gp_cnum'];
        
        if ($gp_cnum < 2 && $id > 1) {
            $this->error("拆分两次后才能卖出！");
            exit();
        }
        // $max_chushou = $fee_s['max_chushou'];
        // if ($max_chushou < $num) {
        // $this->ajaxReturn('', '单笔手数不能大于' . $max_chushou, 0);
        // exit();
        // }
        
        if (! chkNum($price)) {
            $this->ajaxReturn('', '请输入数字！', 0);
            exit();
        }
        
        if ($paypwd != $rs['passopen']) {
            $this->error('交易密码不正确');
            
            exit();
        }
        
        $total = $num * $price;
        $auth = $fck->where('id=' . $id)->find();
        if ($auth['live_gupiao'] < $num) {
            $this->error('股票不足！');
            
            exit();
        }
        // $max_price = $fee_s['zhangting'];
        // $min_price = $fee_s['dieting'];
        // $max_price = ! empty($max_price) ? $max_price : '10';
        // $min_price = ! empty($min_price) ? $min_price : '0';
        // // 计算出售比例
        // $keyi_sell = $auth['agent_kt'] * $fee_s['max_chushou'];
        // 统计当天卖出数量
        $start = strtotime(date('Y-m-d', time()));
        $end = time();
        $map['ctime'] = array(
            'between',
            array(
                $start,
                $end
            )
        );
        $map['type'] = 0;
        $map['uid'] = $id;
        $maichu_shuliang = M('t_trans')->where($map)->sum('num');
        // 统计挂单数量
        $guadan = M('t_orders')->where('type=0 and uid=' . $id)->sum('num');
        // 可以卖出数量
        // if ($keyi_sell < $maichu_shuliang + $guadan + $num) {
        // $this->error('卖出数量超限！');
        // exit();
        // }
        
        // if ($price > $max_price || $price < $min_price) {
        // $this->error('超出涨跌停范围！');
        // exit();
        // }
        $rs[] = $fck->save(array(
            'id' => $auth['id'],
            'live_gupiao' => array(
                'exp',
                'live_gupiao-' . $num
            )
        ));
        $rs[] = M('t_orders')->add(array(
            'uid' => $auth['id'],
            'price' => $price,
            'num' => $num,
            'shengyu_num' => $num,
            'type' => 0,
            'coin' => $coin,
            'ctime' => time()
        ));
        
        if (! chkArr($rs)) {
            $this->ajaxReturn('', '提交失败！', 0);
            exit();
        }
        $this->runTrade($coin, $price, 'sell');
        $bUrl = __URL__ . '/trade';
        
        return $this->success('提交成功！', $bUrl);
        
        exit();
    }

    public function sellGP()
    { // 出售股票(股)
        if (empty($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error("错误！");
            exit();
        }
        if (empty($_SESSION['GP_Sesion_Sell'])) {
            $this->error("刷新操作错误！");
            exit();
        }
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $fck = D('Fck');
        $fee = M('fee');
        $pdgp = M('pdgp');
        $gupiao = M('gupiao');
        
        $this->check_gpopen(1);
        
        $one_price = $_POST['one_price1']; // 表单传来的股票(股)单价
        
        $fee_rs = $fee->find();
        $cur_one_price = $fee_rs['gp_one']; // 系统设置的股票(股)价格
        $gp_cnum = $fee_rs['gp_cnum'];
        $s12 = explode('|', $fee_rs['s12']);
        $str5 = explode('|', $fee_rs['str5']);
        if ($gp_cnum < 2 && $id > 1) {
            $this->error("拆分两次后才能卖出！");
            exit();
        }
        
        // 检查交易密码及其他
        $user_info = $fck->where("id=" . $id)
            ->field("id,live_gupiao,b1,cpzj,user_id,passopen,re_path,u_level")
            ->find();
        $use = $user_info['live_gupiao']; // 剩余的股票(股)
        $hycpzj = $user_info['cpzj'];
        $mylv = $user_info['u_level'];
        $fdbs = $str5[$mylv - 1];
        $fdmoney = $fdbs * $hycpzj;
        $use_yes = (int) ($use * 0.1); // 剩余的股票(股)
        
        $gp_pwd = trim($_POST['gp_pwd']);
        if (md5($gp_pwd) != $user_info['passopen']) {
            $this->error("二级密码不正确！");
            exit();
        }
        
        if ($user_info['b1'] >= $fdmoney) {
            $this->error("不能卖出！");
            exit();
        }
        
        $smap['uid'] = $id;
        $smap['type'] = 1;
        $tdgrs = $gupiao->where($smap)
            ->order('id desc')
            ->find();
        if ($tdgrs && $id > 1) {
            $last_price = $tdgrs['one_price'];
            if ($last_price == $cur_one_price) {
                $this->error('行情指数涨一次才能挂卖一次！');
                exit();
            }
        }
        
        $now_time = strtotime(date('Y-m-d'));
        // $smap['uid'] = $id;
        // $smap['type'] = 1;
        // $smap['eDate'] = array('egt',$now_time);
        // $tdgnum = $gupiao->where($smap)->order('id desc')->count();
        // if($tdgnum>=3 && $id>1){
        // $this->error('每日可以出售3次,请择日再出售！');
        // exit;
        // }
        
        $sNun = trim($_POST['sNun']); // 出售股票(股)的数量
        
        if (empty($sNun) || $sNun <= 0) {
            $this->error('出售的数量不能为空或者小于等于0！');
            exit();
        }
        if ($sNun != floor($sNun)) {
            $this->error('温馨提示：您输入数量必须是整数。请检验后再试！');
            exit();
        }
        // if($sNun > $s12[$mylv-1] && $id>1){
        // $this->error('温馨提示：卖出数量错误,单次卖出不能超过'.$s12[$mylv-1].'！');
        // exit;
        // }
        
        if ($sNun > $use_yes) {
            $this->error('温馨提示：您目前最多可以出售 ' . $use_yes . ' 个。请检验后再试！');
            exit();
        }
        
        // 更新卖方的股票(股)信息
        $fck->execute("UPDATE __TABLE__ SET live_gupiao=live_gupiao-" . $sNun . " WHERE `id`=" . $id . "");
        $this->sell_GPAC($id, $user_info['user_id'], $sNun);
        
        $_SESSION['GP_Sesion_Buy'] = "OK";
        // 有人售出股票(股)后排队收藏品自动购买股票(股)
        // $pdrs = $fck->where('agent_gp>0')->order('pdt asc,id asc')->select();
        // foreach ($pdrs as $vo){
        // $fgpid = $vo['id'];
        // $agent_gp = $vo['agent_gp'];
        //
        // $this->auto_buyGP($fgpid,$agent_gp);
        // }
        
        $_SESSION['GP_Sesion_Buy'] = "";
        $_SESSION['GP_Sesion_Sell'] = "";
        
        // $bUrl = __URL__ . '/sellGPform';
        // $this->_box(1, '出售股票(股)成功。', $bUrl, 3);
        $this->ajaxSuccess('出售股票(股)成功');
        exit();
    }

    public function sell_GPAC($uid, $user_id, $sNunb = 0, $spid = 0, $ssn = 0, $o_pri = 0)
    {
        $fee = M('fee');
        $fck = D('Fck');
        $game = D('Game');
        $gupiao = M('gupiao');
        
        $this->check_gpopen(1);
        
        $one_price = $o_pri;
        $fee_rs = $fee->find();
        $now_price = $fee_rs['gp_one']; // 系统设置的股票(股)价格
        if (empty($one_price)) {
            $one_price = $now_price;
        }
        
        $sNunb = (int) $sNunb; // 卖出数
        $ok_sell = 0; // 成功卖出数
        $ok_over = 0; // 结束卖操作
        while ($ok_over == 0) {
            $new_price = $fee->where(array(
                'id' => 1
            ))->getField('gp_one');
            
            $sNun = $sNunb - $ok_sell;
            if ($sNun > 0) {
                $map = array();
                $map['type'] = array(
                    'eq',
                    0
                ); // 求购股票(股)的标识
                $map['status'] = array(
                    'eq',
                    0
                ); // 没有作废的标识
                $map['ispay'] = array(
                    'eq',
                    0
                ); // 没有交易完成的标识
                $map['is_en'] = array(
                    'eq',
                    0
                ); // 标准股票(股)
                $map['uid'] = array(
                    'neq',
                    $uid
                ); // 不能交易给自己
                $map['one_price'] = array(
                    'eq',
                    $one_price
                ); // 价格
                $order = "eDate asc,id asc"; // 时间先后顺序
                $list_gp = $gupiao->where($map)
                    ->field("*")
                    ->order($order)
                    ->find();
                if ($list_gp) {
                    $gpid = $list_gp['id'];
                    $gpuid = $list_gp['uid'];
                    $buy_s = $list_gp['buy_s']; // 剩余总值
                    $scan_b = floor($buy_s / $new_price); // 当前购买力
                    $gpcan_b = $scan_b;
                    
                    $gp_fxnum = $fee->where(array(
                        'id' => 1
                    ))->getField('gp_fxnum');
                    $gp_senum = $fee->where(array(
                        'id' => 1
                    ))->getField('gp_senum');
                    $ca_gp_n = $gp_fxnum - $gp_senum; // 差多少涨价
                    
                    if ($scan_b > $ca_gp_n) {
                        $scan_b = $ca_gp_n;
                    }
                    
                    if ($scan_b == 0) { // 说明该交易完成了【再判断一次，以防程序出错】
                        $gupiao->query("update __TABLE__ set ispay=1 where id=" . $gpid);
                        sleep(1); // 休眠1秒以免程序运行过快数据未处理;
                    }
                    
                    $lnum = $scan_b; // 剩余
                    $i_ispay = $list_gp['ispay']; // 成功标签
                    $buy_a = $list_gp['buy_a']; // 已购买总额
                    $buy_nn = $list_gp['buy_nn']; // 已购买量
                    if ($lnum <= $sNun) {
                        
                        $us_money = $lnum * $new_price; // 使用额度
                        
                        $s_buy_s = $buy_s - $us_money; // 过后剩余总值
                        $s_buy_a = $buy_a + $us_money; // 过后已购买总额
                        $s_buy_nn = $buy_nn + $lnum; // 过后已购买量
                        
                        if ($gpcan_b > $lnum) {
                            $s_ispay = 0;
                        } else {
                            $s_ispay = 1;
                        }
                        $se_numb = $lnum;
                    } else {
                        
                        $us_money = $sNun * $new_price; // 使用额度
                        
                        $s_buy_s = $buy_s - $us_money; // 过后剩余总值
                        $s_buy_a = $buy_a + $us_money; // 过后已购买总额
                        $s_buy_nn = $buy_nn + $sNun; // 过后已购买量
                        
                        $s_ispay = 0;
                        $se_numb = $sNun;
                    }
                    $do_where = "id=" . $gpid . " and buy_a=" . $buy_a . " and buy_nn=" . $buy_nn . " and buy_s=" . $buy_s . " and ispay=" . $i_ispay . "";
                    $do_sql = "update __TABLE__ set buy_s=" . $s_buy_s . ",buy_a=" . $s_buy_a . ",buy_nn=" . $s_buy_nn . ",ispay=" . $s_ispay . " where " . $do_where;
                    $do_relute = $gupiao->execute($do_sql); // 返回影响的行数
                    
                    if ($do_relute != false) { // 上一个语句是否存在行数
                        
                        if ($s_ispay == 1) {
                            // 自动生成卖出信息
                            $this->addsell_gp($gpuid, $s_buy_nn, $new_price);
                            if ($s_buy_s > 0) {
                                $fck->execute("UPDATE __TABLE__ SET agent_use=agent_use+" . $s_buy_s . ",agent_lock=agent_lock+" . $s_buy_s . " WHERE `id`=" . $gpuid . "");
                            }
                        }
                        
                        $ok_sell = $ok_sell + $se_numb;
                        
                        // 更新对方的股票(股)信息
                        $fck->execute("UPDATE __TABLE__ SET live_gupiao=live_gupiao+" . $se_numb . ",all_in_gupiao=all_in_gupiao+" . $se_numb . " WHERE `id`=" . $gpuid . "");
                        
                        // 记录成功交易的股票(股)信息
                        $this->gpSuccessed($gpuid, $se_numb, 0, $fee_rs, $gpid, 0, 0, $uid);
                    }
                } else {
                    $ok_over = 1;
                }
                unset($list_gp);
            } else {
                $ok_over = 1;
            }
            
            $this->gp_up_down_pd();
        }
        
        $id = $uid;
        
        // 更新自己的售股信息
        $data['uid'] = $uid;
        $data['one_price'] = $new_price;
        $data['price'] = $sNunb * $new_price; // 总得股票(股)金额
        $data['sNun'] = $sNunb; // 总的股票(股)数
        $data['used_num'] = $ok_sell; // 成功买到的股票(股)
        $data['lnum'] = $sNunb - $ok_sell; // 还差没有售出的股票(股)
        $data['ispay'] = ($sNunb - $ok_sell <= 0) ? 1 : 0; // 交易是否完成
        $data['eDate'] = mktime(); // 售出时间
        $data['status'] = 0; // 这条记录有效
        $data['type'] = 1; // 标识为售出
        $data['is_en'] = 0; // 标准股
        $data['spid'] = $spid; // 原卖出记录ID
        $data['last_s'] = $ssn; // 是否最后一次卖出
        $data['sell_g'] = $ok_sell * $new_price; // 售出获得总额
        $resid = $gupiao->add($data); // 添加记录
                                      // 记录成功交易的股票(股)信息
        if ($ok_sell > 0) {
            $this->gpSuccessed($uid, $ok_sell, 1, $fee_rs, $resid, 0, 1);
        }
        
        $this->sellOutGp($id, $resid, $ok_sell, $fee_rs, $game, $sNunb);
        
        unset($fck, $fee, $gupiao, $game, $fee_rs);
    }
    
    // 购买处理
    public function buyGP()
    {
        if (empty($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error("错误！");
            exit();
        }
        if (empty($_SESSION['GP_Sesion_Buy'])) {
            $this->error("刷新操作错误！");
            exit();
        }
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $gp = M('gp');
        $fck = M('fck');
        $fee = M('fee');
        $gupiao = M('gupiao'); // 购股信息表
        $pdgp = M('pdgp');
        
        // $this->check_gpopen(1);
        
        $one_price = $_POST['one_price']; // 表单传来的股票(股)单价
        
        $where = 'one_price=' . $one_price . ' and type=0 and status=0 and ispay=0 and is_cancel=0';
        $gpnum = $gupiao->where($where)->sum('price');
        if (empty($gpnum)) {
            $gpnum = 0;
        }
        $cz_gp_n = (int) ($this->numb_duibi($gpnum, $one_price));
        
        $fee_rs = $fee->find();
        $cur_one_price = $fee_rs['gp_one']; // 系统设置的股票(股)价格
        $gp_fxnum = $fee_rs['gp_fxnum']; // 涨价数量
        $gp_senum = $fee_rs['gp_senum']; // 已售出
        $ca_gp_n = $gp_fxnum - $gp_senum; // 差多少涨价
        $ca_gp_p = ((int) ($cur_one_price * $ca_gp_n * 1000)) / 1000;
        
        // 检查交易密码
        $user_info = $fck->where("id=$id")
            ->field("id,user_id,agent_use,agent_lock,user_id,passopen")
            ->find();
        $myuser_id = $user_info['user_id'];
        $use = $user_info['agent_use']; // 可以的游戏币
        $gp_pwd = trim($_POST['gp_pwd']);
        if (md5($gp_pwd) != $user_info['passopen']) {
            $this->error("二级密码不正确！");
            exit();
        }
        // if($user_info['agent_lock']<=0){
        // $this->error("等待循环出局，不能再购买股票(股)！");
        // exit;
        // }
        
        $sNun = (int) trim($_POST['sNun']); // 购买股票(股)数量
        $buy_mm = $sNun * $cur_one_price;
        
        if (empty($sNun) || $sNun <= 0) {
            $this->error('购买股票(股)的数量不能为空或者小于等于0！');
            exit();
        }
        if ($sNun > $ca_gp_n) {
            $this->error('距离下次涨价只能购买' . $ca_gp_n . '股（折合币:' . $ca_gp_p . '），请检验后再试！');
            exit();
        }
        if (bccomp($buy_mm, $use, 2) > 0) {
            $this->error('你的配送积分账户余额不足 ' . $buy_mm . '。请检验后再试！');
            exit();
        }
        
        // 股票(股)交易
        $this->buy_GPAC($id, $myuser_id, $buy_mm);
        
        $_SESSION['GP_Sesion_Buy'] = "";
        // $bUrl = __URL__ . '/buyGPform';
        // $this->_box(1, '求购提交完成！', $bUrl, 3);
        $this->ajaxSuccess('求购提交完成');
    }
    
    // 购买处理
    public function auto_buyGP($uid, $money = 0)
    {
        if (empty($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error("错误！");
            exit();
        }
        // if(empty($_SESSION['GP_Sesion_Buy'])){
        // $this->error("刷新操作错误1！");
        // exit;
        // }
        
        $id = $uid;
        $gupiao = M('gupiao'); // 购股信息表
        $fck = M('fck');
        $fee = M('fee');
        $gp = M('gp');
        
        $where = 'uid<>' . $id . ' and lnum>0 and type=1 and status=0 and ispay=0 and is_cancel=0';
        $gpnum = $gupiao->where($where)->sum('lnum');
        if (empty($gpnum)) {
            $gpnum = 0;
        }
        
        $fee_rs = $fee->find();
        $cur_one_price = $fee_rs['gp_one']; // 系统设置的股票(股)价格
        $gp_fxnum = $fee_rs['gp_fxnum']; // 涨价数量
        $gp_senum = $fee_rs['gp_senum']; // 已售出
        $ca_gp_n = $gp_fxnum - $gp_senum; // 差多少涨价
                                          // echo "-";
        $ca_gp_p = ((int) ($cur_one_price * $ca_gp_n * 1000)) / 1000;
        
        // 检查交易密码
        $user_info = $fck->where("id=$id")
            ->field("agent_use,agent_lock,user_id,passopen")
            ->find();
        $myuser_id = $user_info['user_id'];
        $use = $user_info['agent_use']; // 可以的游戏币
        $gp_pwd = trim($_POST['gp_pwd']);
        
        $buy_mm = $money; // 购买股票(股)总金额
        $sNun = (int) ($this->numb_duibi($buy_mm, $cur_one_price));
        if ($sNun > $gpnum) {
            $sNun = $gpnum;
            $buy_mm = $sNun * $cur_one_price;
        }
        // echo "*";
        if ($sNun > $ca_gp_n) {
            $sNun = $ca_gp_n;
            $buy_mm = $sNun * $cur_one_price;
        }
        if (bccomp($buy_mm, $use, 2) > 0) {
            $buy_mm = $use;
        }
        if ($buy_mm > 0 && $sNun > 0) {
            // 股票(股)交易
            $this->buy_GPAC($id, $myuser_id, $buy_mm);
            // 交易后自动更新股价
            $this->gp_up_down_pd();
        }
        
        $dmoney = $money - $buy_mm;
        
        if ($gpnum > $ca_gp_n && $dmoney > 0) {
            $this->auto_buyGP($id, $dmoney);
        }
    }
    
    // 购买交易算法
    public function buy_GPAC($uid, $user_id, $bmoney = 0, $o_pri = 0)
    {
        $fck = D('Fck');
        $gupiao = M('gupiao');
        $fee = M('fee');
        $game = D('Game');
        
        $fee_rs = $fee->find();
        
        $one_price = $o_pri;
        $now_price = $fee_rs['gp_one']; // 系统设置的GP价格
        if ($ca_gp_n < 0) {
            $ca_gp_n = 0;
        }
        if (empty($one_price)) {
            $one_price = $now_price;
        }
        // echo "-";
        $bmoney = $bmoney; // 求购总值
        $can_buy = floor($this->numb_duibi($bmoney, $one_price));
        
        // echo "<br>";
        $sNunb = (int) $can_buy; // 买入数
        $ok_buy = 0; // 成功买入数
        $ok_over = 0; // 结束买操作
        while ($ok_over == 0) {
            $sNun = $sNunb - $ok_buy;
            if ($sNun > 0) {
                $map = array();
                $map['type'] = array(
                    'eq',
                    1
                ); // 售出股票(股)的标识
                $map['status'] = array(
                    'eq',
                    0
                ); // 没有作废的标识
                $map['ispay'] = array(
                    'eq',
                    0
                ); // 没有交易完成的标识
                $map['uid'] = array(
                    'neq',
                    $uid
                ); // 不能交易给自己
                $map['one_price'] = array(
                    'eq',
                    $one_price
                ); // 价格
                $order = "eDate asc,id asc"; // 时间先后顺序
                $list_gp = $gupiao->where($map)
                    ->field("*")
                    ->order($order)
                    ->find();
                if ($list_gp) {
                    $gpid = $list_gp['id'];
                    $gpuid = $list_gp['uid'];
                    if (($list_gp['sNun'] == $list_gp['used_num']) || ($list_gp['lnum'] == 0)) { // 说明该交易完成了【再判断一次，以防程序出错】
                        $gupiao->query("update __TABLE__ set ispay=1 where id=" . $gpid);
                        usleep(500000); // 休眠5000毫秒以免程序运行过快数据未处理;
                    }
                    
                    $ussNun = $list_gp['sNun']; // 全部
                    $used_num = $list_gp['used_num']; // 已使用
                    $lnum = $list_gp['lnum']; // 剩余
                    $sell_g = $list_gp['sell_g']; // 已售出总额
                    $i_ispay = $list_gp['ispay']; // 成功标签
                    if ($lnum <= $sNun) {
                        
                        $us_money = $lnum * $one_price; // 使用额度
                        
                        $s_sell_g = $sell_g + $us_money; // 过后已售出总额
                        $s_used_n = $ussNun; // 过后已使用
                        $s_lnum = 0; // 过后剩余
                        $s_ispay = 1;
                        $se_numb = $lnum;
                    } else {
                        
                        $us_money = $sNun * $one_price; // 使用额度
                        
                        $s_sell_g = $sell_g + $us_money; // 过后已售出总额
                        $s_used_n = $used_num + $sNun; // 过后已使用
                        $s_lnum = $lnum - $sNun; // 过后剩余
                        $s_ispay = 0;
                        $se_numb = $sNun;
                    }
                    $do_where = "id=" . $gpid . " and sNun=" . $ussNun . " and used_num=" . $used_num . " and lnum=" . $lnum . " and ispay=" . $i_ispay . "";
                    $do_sql = "update __TABLE__ set used_num=" . $s_used_n . ",lnum=" . $s_lnum . ",sell_g=" . $s_sell_g . ",ispay=" . $s_ispay . " where " . $do_where;
                    $do_relute = $gupiao->execute($do_sql); // 返回影响的行数
                    
                    if ($do_relute != false) { // 上一个语句是否存在行数
                        
                        $ok_buy = $ok_buy + $se_numb;
                        
                        // 更新对方成功出售的股票(股)信息
                        $this->sellOutGp($gpuid, $gpid, $se_numb, $fee_rs, $game);
                        // 记录成功交易的股票(股)信息
                        $this->gpSuccessed($gpuid, $se_numb, 1, $fee_rs, $gpid, 0, 0, $uid);
                    }
                } else {
                    $ok_over = 1;
                }
                unset($list_gp);
            } else {
                $ok_over = 1;
            }
            
            $this->gp_up_down_pd();
        }
        
        $id = $uid;
        
        $lv_nnm = $sNunb - $ok_buy;
        $all_bm = $ok_buy * $one_price; // 购买总金额
        $lv_money = $bmoney - $all_bm; // 差额
                                       
        // 更新自己的购股信息
        $data['uid'] = $id;
        $data['one_price'] = $one_price;
        $data['price'] = $bmoney; // 总金额
        $data['sNun'] = 0; // 总的股票(股)数
        $data['used_num'] = 0; // 成功买到的股票(股)
        $data['lnum'] = 0; // 还差没有买到的股票(股)
        $data['ispay'] = ($lv_nnm <= 0) ? 1 : 0; // 交易是否完成
        $data['eDate'] = mktime(); // 购买时间
        $data['status'] = 0; // 这条记录有效
        $data['type'] = 0; // 标识为求股
        $data['is_en'] = 0; // 标准股
        
        $data['buy_a'] = $all_bm;
        $data['buy_nn'] = $ok_buy;
        $data['buy_s'] = $lv_money;
        if ($lv_nnm == 0) {
            if ($lv_money > 0) {
                $fck->execute("UPDATE __TABLE__ SET agent_use=agent_use+" . $lv_money . ",agent_lock=agent_lock+" . $lv_money . " WHERE `id`=" . $id . "");
            }
        }
        $resid = $gupiao->add($data); // 添加记录
                                      // 记录成功交易的股票(股)信息
        if ($ok_buy > 0) {
            $this->gpSuccessed($id, $ok_buy, 0, $fee_rs, $resid, 0, 1);
        }
        // 小于零时，自动生成卖出信息
        $lv_n = $sNunb - $ok_buy;
        if ($sNunb > 0) {
            $this->addsell_gp($id, $sNunb, $one_price);
        }
        $gm = $sNunb * $one_price; // 购股所花费的金额
        $hm = $ok_buy * $one_price; // 已经用在买股票(股)上的钱
                                    // $game->updateGameCash($id,$hm);
                                    // 更新fck表中的股票(股)信息
        $fck->execute("UPDATE __TABLE__ SET live_gupiao=live_gupiao+" . $ok_buy . ",all_in_gupiao=all_in_gupiao+" . $ok_buy . ",agent_use=agent_use-" . $bmoney . ",agent_lock=agent_lock-" . $bmoney . " WHERE `id`=" . $id . "");
        $fck->execute("UPDATE __TABLE__ SET agent_lock=0 WHERE `id`=" . $id . " and agent_lock<0");
        unset($fck, $fee, $gupiao, $game, $fee_rs);
    }

    public function delbuyGP()
    {
        $del = M('gupiao');
        $fck = M('fck');
        $gp_sell = M('gp_sell');
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        if (empty($id)) {
            $this->error("您的登录状态过期！");
            exit();
        }
        
        $where['id'] = $_GET['id'];
        $where['uid'] = $id;
        // 选出该条记录的信息
        $del_info = $del->where($where)
            ->field("*")
            ->find();
        if (empty($del_info)) {
            $this->error("没有找到符合条件的记录");
            exit();
        }
        
        $buy_s = $del_info['buy_s']; // 剩余总价
        
        $fck->execute("UPDATE __TABLE__ SET agent_use=agent_use+" . $buy_s . " WHERE `id`=" . $del_info['uid']);
        $bUrl = __URL__ . '/buyGPform';
        
        // 撤销的话要更新股票(股)表
        $data['ispay'] = 1;
        $data['is_cancel'] = 1;
        
        $rs = $del->where($where)->save($data);
        if ($rs) {
            
            $sNunb = $del_info['buy_nn'];
            $wdata = array();
            $wdata['uid'] = $id;
            $wdata['sNun'] = $sNunb;
            $wdata['eDate'] = mktime();
            $wdata['sell_ln'] = $sNunb;
            $gp_sell->add($wdata);
            
            $this->Success('撤销成功');
            // $this->_box(1, '撤销成功！', $bUrl, 1);
        } else {
            $this->error('撤销失败');
        }
    }

    public function delsellGP()
    {
        $del = M('gupiao');
        $fck = M('fck');
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        if (empty($id)) {
            $this->error("您的登录状态过期！");
            exit();
        }
        
        $where['id'] = $_GET['id'];
        $where['uid'] = $id;
        // 选出该条记录的信息
        $del_info = $del->where($where)
            ->field("*")
            ->find();
        if (empty($del_info)) {
            $this->error("没有找到符合条件的记录");
            exit();
        }
        
        $sNun = $del_info['sNun']; // 总得交易数
        $used_num = $del_info['used_num']; // 成功成交得数量
        $lnum = $del_info['lnum']; // 余下的数量
        
        if ($lnum + $used_num != 0) {
            // 交易成功跟余下的数量不等于0
            if ($lnum + $used_num != $sNun) {
                $this->error("该条信息记录有误，请和管理员联系");
                exit();
            }
        }
        
        // 没有售出的那部分股票(股)还给他
        $fck->execute("UPDATE __TABLE__ SET live_gupiao=live_gupiao+" . $lnum . " WHERE `id`=" . $del_info['uid']);
        $bUrl = __URL__ . '/sellGPform_N';
        
        $cx_content = "撤销出售 " . $lnum . " 个";
        
        // 撤销的话要更新股票(股)表
        $data['ispay'] = 1;
        $data['is_cancel'] = 1;
        $data['sNun'] = $del_info['used_num'];
        $data['lnum'] = 0;
        $data['bz'] = $cx_content;
        $rs = $del->where($where)->save($data);
        if ($rs) {
            $this->_box(1, '撤销成功！', $bUrl, 1);
        } else {
            $this->error('撤销失败');
        }
    }

    public function us_delsellgpAC()
    {
        $del = M('gupiao');
        $fck = M('fck');
        $gp_sell = M('gp_sell');
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        if (empty($id)) {
            $this->error("您的登录状态过期！");
            exit();
        }
        
        $where['id'] = $_GET['id'];
        $where['uid'] = $id;
        // 选出该条记录的信息
        $del_info = $del->where($where)
            ->field("*")
            ->find();
        if (empty($del_info)) {
            $this->error("没有找到符合条件的记录");
            exit();
        }
        
        $sNun = $del_info['sNun']; // 总得交易数
        $used_num = $del_info['used_num']; // 成功成交得数量
        $lnum = $del_info['lnum']; // 余下的数量
        
        if ($lnum + $used_num != 0) {
            // 交易成功跟余下的数量不等于0
            if ($lnum + $used_num != $sNun) {
                $this->error("该条信息记录有误，请和管理员联系");
                exit();
            }
        }
        
        $last_s = $del_info['last_s'];
        if ($last_s == 1) {
            $this->error("该条信息为最后一次售出，不能进行撤销操作。");
            exit();
        }
        
        $spid = $del_info['spid'];
        $y_rd = 1; // 是否原数据读出
        if ($spid > 0) {
            $s_c = $gp_sell->where('id=' . $spid . ' and sell_num<3')->count();
            if ($s_c == 0) {
                $this->error("该条信息为最后一次售出，不能进行撤销操作。");
                exit();
            }
        } else {
            $s_rs = $gp_sell->where('uid=' . $id . ' and sell_num<3')
                ->field('id,uid')
                ->order('sell_date desc')
                ->find();
            if (! $s_rs) {
                $this->error("该条信息为最后一次售出，不能进行撤销操作。");
                exit();
            } else {
                $spid = $s_rs['id'];
                $y_rd = 0;
            }
        }
        
        $xz_hour = 1;
        $eDate = $del_info['eDate'];
        $n_edate = $eDate + 3600 * $xz_hour;
        if ($n_edate > mktime()) {
            $this->error("交易挂出 " . $xz_hour . " 小时内，不能撤销。");
            exit();
        }
        
        $where['sNun'] = array(
            'eq',
            $sNun
        );
        $where['used_num'] = array(
            'eq',
            $used_num
        );
        $where['lnum'] = array(
            'eq',
            $lnum
        );
        
        $cx_content = "撤销出售 " . $lnum . " 个";
        
        // 撤销的话要更新股票(股)表
        $data['ispay'] = 1;
        $data['is_cancel'] = 1;
        $data['sNun'] = $del_info['used_num'];
        $data['lnum'] = 0;
        $data['bz'] = $cx_content;
        $rs = $del->where($where)->save($data);
        if ($rs) {
            
            // 没有售出的那部分股票(股)还给他
            if ($y_rd == 1) {
                $gp_sell->execute("UPDATE __TABLE__ SET sell_ln=sell_ln+" . $lnum . ",sell_mon=sell_mon-" . $lnum . " WHERE `id`=" . $spid);
            } else {
                $gp_sell->execute("UPDATE __TABLE__ SET sNun=sNun+" . $lnum . ",sell_ln=sell_ln+" . $lnum . " WHERE `id`=" . $spid);
            }
            
            $fck->execute("UPDATE __TABLE__ SET live_gupiao=live_gupiao+" . $lnum . " where id=" . $id);
            
            $bUrl = __URL__ . '/sellGPform';
            $this->_box(1, '撤销成功！', $bUrl, 1);
        } else {
            $this->error('撤销失败');
        }
    }
    
    // 卖家交易出去后处理
    public function sellOutGp($uid = 0, $gpid = 0, $out_n = 0, $fee_rs = 0, $game = 0, $senum = 0)
    {
        $fck = D('Fck');
        $fenhong = M('fenhong');
        $gupiao = M('gupiao');
        
        $last_s = 0;
        $mrs = $fck->where('id=' . $uid)
            ->field('id,user_id')
            ->find();
        $last_s = $gupiao->where('id=' . $gpid)->getField('last_s');
        
        $one_price = $fee_rs['gp_one']; // 达人挂起的价格
        $gp_perc = $fee_rs['gp_perc'] / 100; // 交易手续费
        $gp_inm = $fee_rs['gp_inm'] / 100; // 进入奖金比例
        $gp_inn = $fee_rs['gp_inn'] / 100; // 进入回购比例
        $gp_ino = $fee_rs['gp_ino'] / 100; // 进入消费比例
        $in_usmoney = 0;
        $in_cfmoney = 0;
        $in_gpmoney = 0;
        $get_money = $out_n * $one_price; // 售出股票(股)金额
        if ($get_money > 0) {
            $shuis = $get_money * $gp_perc; // 税收
            $la_money = $get_money;
            
            $last_in = 1 - $gp_inm - $gp_inn - $gp_ino - $gp_perc;
            if ($last_in > 0) {
                $in_lastmoney = $la_money * $last_in;
            }
            
            $in_usmoney = $la_money * $gp_inm; // 进入奖金
            $in_gpmoney = $la_money * $gp_inn; // 进入回购
            $in_cfmoney = $la_money * $gp_ino; // 进入消费
            if ($in_gpmoney < 0)
                $in_gpmoney = 0;
            
            $fck->addencAdd($uid, $mrs['user_id'], $in_usmoney, 31); // 添加奖金和记录
            $fck->addencAdd($uid, $mrs['user_id'], $in_gpmoney, 32); // 添加回购和记录
            $fck->addencAdd($uid, $mrs['user_id'], $in_cfmoney, 33); // 添加消费和记录
        }
        // 更新账户
        if ($last_s == 1) {
            $fck->query("update __TABLE__ SET " . "all_out_gupiao=all_out_gupiao+" . $out_n . ",agent_cf=agent_cf+" . $in_cfmoney . ",agent_xf=agent_xf+" . $in_gpmoney . ",agent_use=agent_use+" . $in_usmoney . ",b1=b1+" . $in_usmoney . " WHERE `id`=$uid");
        } else {
            $fck->query("update __TABLE__ SET " . "all_out_gupiao=all_out_gupiao+" . $out_n . ",agent_cf=agent_cf+" . $in_cfmoney . ",agent_xf=agent_xf+" . $in_gpmoney . ",agent_use=agent_use+" . $in_usmoney . ",b1=b1+" . $in_usmoney . " WHERE `id`=$uid");
        }
        
        $data = array();
        $data['uid'] = $mrs['id'];
        $data['user_id'] = $mrs['user_id'];
        $data['f_money'] = $in_cfmoney;
        $data['pdt'] = time();
        $fenhong->add($data);
        unset($fenhong, $data);
        
        $gongsi_money = $shuis + $in_cfmoney;
        if ($uid > 1) {
            $fck->query("update __TABLE__ SET agent_use=agent_use+" . $gongsi_money . ",b1=b1+" . $gongsi_money . " WHERE `id`=1");
            $fck->addencAdd(1, $mrs['user_id'], $shuis, 30); // 平台收税
            $fck->addencAdd(1, $mrs['user_id'], $in_cfmoney, 34); // 平台收入
        }
        
        unset($fck, $game, $mrs, $fee_rs);
    }
    
    // 把交易成功的记录写入到一个表中【不能删除的】
    public function gpSuccessed($uid = 0, $out_n = 0, $type = 0, $fee_rs = 0, $gpid = 0, $en = 0, $ett = 0, $did = 0)
    {
        $hgp = M('hgupiao');
        $gp = M('gupiao');
        $grs = $gp->where('id=' . $gpid)->find();
        $cur_one_price = $grs['one_price']; // 达人挂起的价格
        
        $gm = $out_n * $cur_one_price; // 售出股票(股)金额
                                       // 添加记录到表
        $data['uid'] = $uid;
        $data['price'] = $gm;
        $data['one_price'] = $cur_one_price;
        $data['sNun'] = $out_n;
        $data['ispay'] = 1;
        $data['eDate'] = time();
        $data['type'] = $type;
        if ($type == 1) {
            $fee_money = $fee_rs['str2'] / 100; // 股票(股)的综合税费
            $shuis = $gm * $fee_money; // 税收
            $la_sh = $gm - $shuis; // 税后
            $sy_sh = $la_sh; // 剩余
                             // 扣税后的金额
            $data['gprice'] = $la_sh;
            // 更新多少进入购物券,多少进入交易币
            $stt6 = $fee_rs['str6'] / 100; // 购物券比例
            $stt5 = $fee_rs['str5'] / 100; // 交易币比例
            $data['gmp'] = $sy_sh * $stt6; // 进入购物券
            $data['pmp'] = $sy_sh * $stt5; // 进入交易币
        } else {
            // D('Game')->updateGameCash($uid,$gm);
        }
        
        $data['is_en'] = $en; // 股票(股)类型
                              // $data['did'] = $did;
        
        $hgp->add($data);
        // 添加到历史记录表
        
        $fck = D('Fck');
        $rs = $fck->where("id=$uid")
            ->field("user_id")
            ->find();
        
        $c_gp = M('gp');
        if ($ett == 1) {
            $jioayi_n = 0;
        } else {
            $jioayi_n = $out_n;
        }
        $c_gp->query("update __TABLE__ set gp_quantity=gp_quantity+" . $jioayi_n . ",turnover=turnover+" . $jioayi_n . " where id=1");
        
        $this->gp_jy_bs($jioayi_n, 0);
        
        $this->gp_jy_bs($jioayi_n, 1);
    }
    
    // 股票(股)买卖统计交易量
    public function gp_jy_bs($num, $type = 0)
    {
        if ($num > 0) {
            $gp = M('gp');
            if ($type == 0) {
                $gp->query("update __TABLE__ set buy_num=buy_num+" . $num . " where id=1");
                // 加卖出数量
                M('fee')->query("update __TABLE__ set gp_senum=gp_senum+" . $num . " where id=1");
            } else {
                $gp->query("update __TABLE__ set sell_num=sell_num+" . $num . " where id=1");
            }
            unset($gp);
        }
    }
    
    // 股票(股)升价降价
    public function gp_up_down_pd()
    {
        $gp = M('gp');
        $fee = M('fee');
        $fee_rs = $fee->field('gp_one,gp_fxnum,gp_senum,gp_cnum,str12')->find();
        $cf_pri = 0.4; // 拆分价格
        $up_pri = $fee_rs['str12']; // 上涨价格
        $one_price = $fee_rs['gp_one'];
        $gp_fxnum = $fee_rs['gp_fxnum']; // 升价标准
        $gp_senum = $fee_rs['gp_senum']; // 销售量
        $gp_cnum = $fee_rs['gp_cnum']; // 拆股次数
        $gp_c_pri = $one_price;
        if ($gp_fxnum <= $gp_senum && $gp_fxnum > 0) { // 升价
            $new_pri = $one_price + $up_pri;
            $gp_c_pri = $new_pri;
            $result = $fee->execute("update __TABLE__ set gp_one=" . $new_pri . ",gp_senum=gp_senum-" . $gp_fxnum . " where id=1 and gp_one=" . $one_price);
            if ($result) { // 涨价成功
                $gp->query("update __TABLE__ set opening=" . $new_pri . "");
                if ($gp_cnum > 1) {
                    // 自动抛出股票(股)
                    // $this->auto_sell_gp($new_pri);
                }
            }
        }
        if ($gp_c_pri == $cf_pri) { // 达到拆股
                                    // 自动拆股
            $this->splitGP(1);
            $gp_ch_pri = $gp_c_pri / 2;
            $gp->query("update __TABLE__ set opening=" . $gp_ch_pri . "");
            if ($gp_cnum >= 1) {
                // 自动抛出股票(股)
                // $this->auto_sell_gp($gp_ch_pri);
            } else {
                // $this->gx_auto_sell();
            }
        }
        unset($gp, $fee, $fee_rs);
    }
    
    // 自动判断会员持股量
    public function auto_have_LBN()
    {
        $fck = M('fck');
        $fee = M('fee');
        $fee_rs = $fee->field('s13')->find(1);
        $s12 = explode("|", $fee_rs['s13']); // 代数
        
        $fck->execute("update __TABLE__ set live_gupiao=" . $s12[0] . ",yuan_gupiao=yuan_gupiao+live_gupiao-" . $s12[0] . " where id>1 and u_level=1 and live_gupiao>" . $s12[0]);
        $fck->execute("update __TABLE__ set live_gupiao=" . $s12[1] . ",yuan_gupiao=yuan_gupiao+live_gupiao-" . $s12[1] . " where id>1 and u_level=2 and live_gupiao>" . $s12[1]);
        $fck->execute("update __TABLE__ set live_gupiao=" . $s12[2] . ",yuan_gupiao=yuan_gupiao+live_gupiao-" . $s12[2] . " where id>1 and u_level=3 and live_gupiao>" . $s12[2]);
        $fck->execute("update __TABLE__ set live_gupiao=" . $s12[3] . ",yuan_gupiao=yuan_gupiao+live_gupiao-" . $s12[3] . " where id>1 and u_level=4 and live_gupiao>" . $s12[3]);
        $fck->execute("update __TABLE__ set live_gupiao=" . $s12[4] . ",yuan_gupiao=yuan_gupiao+live_gupiao-" . $s12[4] . " where id>1 and u_level=5 and live_gupiao>" . $s12[4]);
        $fck->execute("update __TABLE__ set live_gupiao=" . $s12[5] . ",yuan_gupiao=yuan_gupiao+live_gupiao-" . $s12[5] . " where id>1 and u_level=6 and live_gupiao>" . $s12[5]);
        
        $sumyuangp = $fck->where('id>1')->sum('yuan_gupiao');
        if (empty($sumyuangp)) {
            $sumyuangp = 0;
        }
        
        $fck->execute("update __TABLE__ set live_gupiao=live_gupiao+" . sumyuangp . " where id=1");
        $fck->execute("update __TABLE__ set yuan_gupiao=0 where id>1");
    }
    
    // 自动抛售股票(股)
    public function auto_sell_gp($one_price)
    {
        if ($one_price > 0) {
            $gp_sell = M('gp_sell');
            $fck = M('fck');
            $gupiao = M('gupiao');
            $where = "is_over=0 and sell_mm=" . $one_price . " and sell_mon=2";
            $order = "id asc";
            $lirs = $gp_sell->where($where)
                ->order($order)
                ->select();
            foreach ($lirs as $lrs) {
                $id = $lrs['id'];
                $myid = $lrs['uid'];
                $sNunb = $lrs['sNun'];
                $bmoney = $one_price * $sNunb;
                $ok_sell = 0;
                $ok_money = $ok_sell * $one_price;
                
                // 更新自己的售股信息
                $data['uid'] = $myid;
                $data['one_price'] = $one_price;
                $data['price'] = $sNunb * $one_price; // 总得股票(股)金额
                $data['sNun'] = $sNunb; // 总的股票(股)数
                $data['used_num'] = $ok_sell; // 成功买到的股票(股)
                $data['lnum'] = $sNunb - $ok_sell; // 还差没有售出的股票(股)
                $data['ispay'] = 0; // 交易是否完成
                $data['eDate'] = time(); // 售出时间
                $data['status'] = 0; // 这条记录有效
                $data['type'] = 1; // 标识为售出
                $data['is_en'] = 0; // 标准股
                $data['spid'] = 0; // 原卖出记录ID
                $data['last_s'] = 0; // 是否最后一次卖出
                $data['sell_g'] = $ok_money; // 售出获得总额
                $resid = $gupiao->add($data); // 添加记录
                if ($resid) {
                    // 更新账户
                    $fck->query("update __TABLE__ SET " . "live_gupiao=live_gupiao-" . $sNunb . ",all_out_gupiao=all_out_gupiao+" . $ok_sell . " WHERE `id`=" . $myid);
                    $gp_sell->query("update __TABLE__ set is_over=1,ispay=1,sell_date=" . time() . " where id=" . $id);
                }
            }
            unset($gp_sell, $fck, $gupiao, $lirs, $lrs);
        }
    }
    
    // 更新所有会员股数
    public function gx_all_gp_tj()
    {
        
        // $fck = M('fck');
        // $gp_sell = M('gp_sell');
        
        // $lirs = $fck->where('is_pay>0 and id>1 and is_lock=0')->field('id,live_gupiao')->order('id asc')->select();
        // foreach($lirs as $lrs){
        // $myid = $lrs['id'];
        // $live_gupiao = $lrs['live_gupiao'];
        
        // $all_n = $gp_sell->where('uid='.$myid.' and is_over=0 and sell_ln>0')->sum('sell_ln');
        // $all_n = (int)$all_n;
        // if($live_gupiao!=$all_n){
        // $fck->query("update __TABLE__ set live_gupiao=".$all_n." where id=".$myid);
        // }
        // }
        // unset($fck,$gp_sell,$lirs,$lrs);
    }
    
    // 达人的股票(股)信息
    public function gpInfo()
    {
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $rs = M('fck')->where("id=$id")
            ->field("live_gupiao,all_in_gupiao,all_out_gupiao,yuan_gupiao")
            ->find();
        $arr = array();
        $arr[0] = $rs['live_gupiao']; // 剩余的股票(股)
        $arr[1] = $rs['all_in_gupiao']; // 全部买进的股票(股)
        $arr[2] = $rs['all_out_gupiao']; // 全部卖出的股票(股)
        $arr[3] = $rs['yuan_gupiao']; // 原始股票(股)
        return $arr;
    }
    
    // 达人正在求购或者购买的股票(股),0为求购,1为出售
    public function buy_and_ing($x = 0, $en = 0)
    {
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $gp = M('gupiao');
        $gping_num = $gp->where("uid=$id and type=$x and is_en=$en")->sum("lnum");
        return empty($gping_num) ? 0 : $gping_num;
    }

    /* 股票(股)走势图部分【开始】 */
    public function trade()
    { // /7/股權交易
        if (empty($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error("错误！");
            exit();
        }
        if (empty($_SESSION['first_in_trade'])) {
            // 第一次进来就刷新走势图
            $this->stock_past_due();
            $_SESSION['first_in_trade'] = 1;
        }
        $fck = M('fck');
        $gppp = M('gp');
        $fee = M('fee');
        $gupiao = M('gupiao');
        $xml = M('xml');
        $fee_rs = $fee->find();
        
        $fee_gp = $gppp->find();
        $laxl = $xml->order('id desc')->find();
        $now_p = $laxl['money'];
        $now_num = $laxl['amount'];
        $now_t = $laxl['x_date'];
        
        $bj_p = $fee_rs['gp_one'];
        $bj_n = $fee_gp['gp_quantity'];
        $bj_d = strtotime(date("Y-m-d"));
        if (bccomp($bj_p, $now_p, 2) != 0 || $now_num != $bj_n || $now_t != $bj_d) {
            $this->stock_past_due();
        }
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        import("@.ORG.ZQPage"); // 导入分页类
        $where = 'type=0 and id>0 and uid=' . $id;
        $field = '*';
        
        $count = $gupiao->where($where)
            ->field($field)
            ->count(); // 总页数
        $listrows = 10; // 每页显示的记录数
        $Page = new ZQPage($count, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $list = $gupiao->where($where)
            ->field($field)
            ->order('eDate desc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        foreach ($list as $vvv) {
            $buy_s = $vvv['buy_s'];
            $is_pay = $vvv['ispay'];
            if ($is_pay == 1) {
                $can_b = 0;
            } else {
                $can_b = floor($buy_s / $one_price);
            }
            $tvo[$vvv['id']] = $can_b;
        }
        $this->assign('list', $list);
        $this->assign('tvo', $tvo);
        
        import("@.ORG.ZQPage1"); // 导入分页类
        
        $where1 = 'type=1 and id>0 and uid=' . $id;
        $field1 = '*';
        
        $count1 = $gupiao->where($where1)
            ->field($field1)
            ->count(); // 总页数
        $listrows = 10; // 每页显示的记录数
        $Page1 = new ZQPage($count1, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show1 = $Page1->show(); // 分页变量
        $this->assign('page1', $show1); // 分页变量输出到模板
        
        $list1 = $gupiao->where($where1)
            ->field($field1)
            ->order('eDate desc')
            ->page($Page1->getPage() . ',' . $listrows)
            ->select();
        $this->assign('list1', $list1);
        
        $op_price = $fee_rs['gp_one']; // 现价和现在的购买价和销售价
        $day_price = $fee_rs['gp_open']; // 开盘价
        $close_pr = $fee_rs['gp_close']; // 昨日收盘价
        $close_gp = $fee_rs['gp_kg']; // 股票(股)交易开关,1为关
        
        $tall_sNun = $fee_gp['gp_quantity']; // 今日成交量
        $tall_sNun = number_format($tall_sNun, 0, "", ",");
        $all_sNun = $xml->sum('amount'); // XML总成交量
        $all_sNun = number_format($all_sNun, 0, "", ",");
        
        $all_num = $fee_gp['turnover']; // 总成交量
        $yt_sellnum = $fee_gp['yt_sellnum']; // 昨日交易量
        
        $all_num = number_format($all_num, 0, "", ",");
        
        $all_num = (int) M('t_trans')->where('type=1  ')->sum('num');
        
        $this->assign('all_num', $all_num); // 总成交量
        
        $yt_sellnum = (int) M('t_trans')->where('type=1 and  DATEDIFF(ctime_,NOW())=-1 ')->sum('num');
        
        $yt_sellnum = number_format($yt_sellnum, 0, "", ",");
        $this->assign('yt_sellnum', $yt_sellnum); //
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $gp_info = $this->gpInfo(); // 股票(股)的信息
        $fck_rs = $fck->where("id=$id")
            ->field("agent_gp")
            ->find();
        
        $this->assign('live_gp', $gp_info[0]); // 剩余的
        $this->assign('game_cash', $fck_rs['agent_gp']); // 当前的游戏币
        
        $this->assign('one_price', $op_price);
        $this->assign('op_price', $op_price);
        $this->assign('day_price', $day_price);
        $this->assign('cl_price', $close_pr);
        $this->assign('close_gp', $close_gp);
        
        $tall_sNun = (int) M('t_trans')->where('type=1 and  DATEDIFF(ctime_,NOW())=0 ')->sum('num');
        
        $this->assign('tall_sNun', $tall_sNun);
        $this->assign('all_sNun', $all_sNun);
        
        $last = $xml->order('id desc')->find();
        $start = $xml->order('id asc')->find();
        $res = $xml->where('x_date >=' . $start['x_date'] . ' and x_date<=' . $last['x_date'])
            ->order('id asc')
            ->select();
        foreach ($res as $key => $value) {
            $kds .= $value['money'];
            if ($value['money']) {
                
                $kds .= ',';
            }
            
            $data .= date("Ymd", $value['x_date']);
            if ($value['x_date']) {
                
                $data .= ',';
            }
        }
        $kdss = rtrim($kds, ',');
        $datas = rtrim($data, ',');
        
        $this->assign('res', $kdss);
        $this->assign('data', $datas);
        
        $_SESSION['GP_Sesion_Buy'] = 'OK';
        $_SESSION['GP_Sesion_Sell'] = 'OK';
        
        $this->display();
    }

    public function stock_past_due()
    {
        $gp = M('gp');
        $xml = M('xml');
        $rs = $gp->where("id=1")->find();
        $gp_quantity = $rs['gp_quantity'];
        $tt = $rs['f_date'];
        $newday = strtotime(date("Y-m-d"));
        $ddtt = strtotime(date("Y-m-d", $tt));
        
        if ($ddtt == $newday) {
            $mrs = $xml->where('x_date=' . $newday)->find();
            if ($mrs) {
                $data = array();
                $data['id'] = $mrs['id'];
                $data['money'] = $rs['opening'];
                $data['amount'] = $rs['gp_quantity'];
                $xml->save($data);
            } else {
                $data = array();
                $data['money'] = $rs['opening'];
                $data['amount'] = $rs['gp_quantity'];
                $data['x_date'] = $newday;
                $xml->add($data);
            }
        } else {
            $result = $gp->execute("update __TABLE__ set yt_sellnum=gp_quantity,gp_quantity=0,closing=opening where id=1 and gp_quantity=" . $gp_quantity);
            if ($result) {
                $mrs = $xml->where('x_date=' . $newday)->find();
                if ($mrs) {
                    $data = array();
                    $data['id'] = $mrs['id'];
                    $data['amount'] = 0;
                    $xml->save($data);
                } else {
                    $data = array();
                    $data['money'] = $rs['opening'];
                    $data['amount'] = 0;
                    $data['x_date'] = $newday;
                    $xml->add($data);
                }
            }
        }
        if ($tt < time()) { // 时时更新价格
            $f_date = time();
            $gp->query("update __TABLE__ set today=opening,most_g=opening,most_d=opening,f_date='$f_date' where id=1");
        }
        $this->ChartsPrice();
    }
    
    // 股票(股)升值判断是否还能再购买
    public function pd_buy_ok($pri = 0)
    {
        $fck = M('fck');
        $gupiao = M('gupiao');
        
        $rs = $gupiao->where('ispay=0 and status=0 and buy_s<' . $pri . ' and type=0')->select();
        foreach ($rs as $vo) {
            
            $buy_s = $vo['buy_s']; // 剩余购买总额
            $myuid = $vo['uid'];
            $tid = $vo['id'];
            
            $sy_pri = $buy_s; // 购买剩余多少没买到
            $gupiao->where('id=' . $tid)->setField('ispay', 1); // 完成
            $fck->query('update __TABLE__ set agent_use=agent_use+' . $sy_pri . ' where id=' . $myuid); // 补回余额
        }
    }

    public function ChartsPrice()
    {
        $xml = M('xml');
        $fengD = strtotime("2012-01-01");
        $rs = $xml->where('x_date>=' . $fengD)
            ->order("x_date desc")
            ->select();
        $xx = "";
        foreach ($rs as $vo) {
            $xx = $xx . date("Y-m-d", $vo['x_date']) . "," . $vo['amount'] . "," . $vo['money'] . "\r\n";
        }
        // $filename = "./Public/amstock/data2.csv";
        $filename = "./Public/U/data2.csv";
        if (file_exists($filename)) {
            unlink($filename); // 存在就先刪除
        }
        file_put_contents($filename, $xx);
    }

    public function ChartsVolume($date, $shu)
    {
        // //生成xml檔
        $yy = "<graph yAxisMaxValue='3500000' yAxisMinValue='100' numdivlines='19' lineThickness='1' showValues='0' numVDivLines='0' formatNumberScale='1' rotateNames='1' decimalPrecision='2' anchorRadius='2' anchorBgAlpha='0' divLineAlpha='30' showAlternateHGridColor='1' shadowAlpha='50'>";
        $yy = $yy . "<categories>";
        $yy = $yy . $date;
        $yy = $yy . "</categories>";
        $yy = $yy . "<dataset color='A66EDD' anchorBorderColor='A66EDD' anchorRadius='2'>";
        $yy = $yy . $shu;
        $yy = $yy . "</dataset>";
        $yy = $yy . "</graph>";
        $filename = "./Public/Images/ChartsVolume.xml";
        if (file_exists($filename)) {
            unlink($filename); // 存在就先刪除
        }
        $wContent = $yy;
        $handle = fopen($filename, "a");
        if (is_writable($filename)) {
            // fwrite($handle, $wContent);
            fwrite($handle, $wContent);
            if (is_readable($filename)) {
                $file = fopen($filename, "rb");
                $contents = "";
                while (! feof($file)) {
                    $contents = fread($file, 90000000);
                }
                fclose($file);
            }
            fclose($handle);
        }
    }
    
    // 检查代卖表
    public function addsell_gp($uid, $sNunb = 0, $pri = 0)
    {
        $gp_sell = M('gp_sell');
        $lrs = $gp_sell->where('uid=' . $uid . ' and sell_mm=' . $pri . ' and is_over=0 and sell_mon=0')->find();
        if ($lrs) {
            $gp_sell->query("update __TABLE__ set sNun=sNun+" . $sNunb . ",sell_ln=sell_ln+" . $sNunb . " where id=" . $lrs['id']);
        } else {
            $wdata = array();
            $wdata['uid'] = $uid;
            $wdata['sNun'] = $sNunb;
            $wdata['eDate'] = time();
            $wdata['sell_mm'] = $pri;
            $wdata['sell_ln'] = $sNunb;
            $gp_sell->add($wdata);
            unset($wdata);
        }
        unset($gp_sell, $lrs);
    }
    
    // 股票(股)参数设置
    public function adminsetGP()
    {
        $this->_Admin_checkUser();
        $fck = M('fck');
        $allagcash = $fck->where('is_pay>0')->sum('agent_gp');
        if (empty($allagcash)) {
            $allagcash = 0;
        }
        $this->assign('allagcash', $allagcash);
        
        $fee = M('fee');
        $fee_rs = $fee->find();
        
        $is_sp = $fee_rs['gp_cgbl'];
        $is_yes = explode(':', $is_sp);
        
        $btn = "<input name=\"bttn\" type=\"button\" id=\"bttn\" value=\"立刻按此设置进行拆股\" class=\"btn1\" onclick=\"if(confirm('您确定要按照 " . $fee_str10 . " 比例进行拆股吗？')){window.location='__URL__/set_gp_cg/f_b/" . $is_yes[0] . "/s_b/" . $is_yes[1] . "/';return true;}return false;\"/>";
        $this->assign('btn', $btn);
        $this->assign('fee_rs', $fee_rs);
        
        $this->display('adminsetGP');
    }

    public function setGPSave()
    {
        $this->_Admin_checkUser();
        $fee = M('fee');
        $gp = M('gp');
        $rs = $fee->find();
        
        $data1 = array();
        $data1['gp_open'] = trim($_POST['gp_open']);
        $data1['gp_close'] = trim($_POST['gp_close']);
        $data1['gp_perc'] = trim($_POST['gp_perc']);
        $data1['gp_inm'] = trim($_POST['gp_inm']);
        $data1['gp_inn'] = trim($_POST['gp_inn']);
        $data1['gp_ino'] = trim($_POST['gp_ino']);
        $data1['gp_kg'] = trim($_POST['gp_kg']);
        $data1['str12'] = trim($_POST['str12']);
        $data1['gp_fxnum'] = trim($_POST['gp_fxnum']);
        $data1['gp_zhidao'] = trim($_POST['gp_zhidao']);
        $is_sp = trim($_POST['gp_cgbl']);
        $is_yes = explode(':', $is_sp);
        if (! is_numeric($is_yes[0]) || ! is_numeric($is_yes[1])) {
            $this->error('拆股比例不是数值!');
            exit();
        }
        $data1['gp_cgbl'] = trim($_POST['gp_cgbl']);
        $data1['gp_cfmax'] = trim($_POST['gp_cfmax']);
        $now_ppp = trim($_POST['gp_one']);
        $gpprice = trim($_POST['gpprice']);
        $data1['gp_one'] = $now_ppp;
        $fee->where("id=1")->save($data1);
        
        if (bccomp($gpprice, $now_ppp, 2) != 0) {
            // 更新价格
            $gp->execute("update __TABLE__ set opening=$now_ppp,f_date=" . time());
            // 更新交易信息
            $this->pd_buy_ok($now_ppp);
        }
        
        $this->success('参数设置成功！');
        exit();
    }
    
    // 拆股操作
    public function set_gp_cg()
    {
        $is_yes = array();
        $is_yes[0] = $_GET['f_b'];
        $is_yes[1] = $_GET['s_b'];
        if (! is_numeric($is_yes[0]) || ! is_numeric($is_yes[1])) {
            $this->error('拆股比例不是数值!');
            exit();
        }
        if ($is_yes[0] != $is_yes[1]) {
            $this->splitGP();
            // $bUrl = __URL__.'/adminsetGP';
            // $this->_box(1,'拆股操作完成！',$bUrl,1);
            $this->success('拆股操作完成!');
        } else {
            $this->error('拆股比例未有改变!');
            exit();
        }
    }
    
    // 拆分股票(股)
    public function splitGP($c_type = 0)
    {
        if ($c_type == 0) {
            $this->_Admin_checkUser();
        }
        $fee = M('fee');
        $fck = M('fck');
        $gp = M('gp');
        
        $fee_rs = $fee->find();
        // 拆分之前股票(股)的相关设置
        $gp_cfmax = explode('|', $fee_rs['gp_cfmax']); // 系统设置的拆分上限
        $old_close_gp = $fee_rs['gp_kg']; // 股票(股)交易开关,1为关闭
        $old_one_price = $fee_rs['gp_one']; // 系统设置的股票(股)价格
                                            // 拆分比率
        $split_m = explode(':', $fee_rs['gp_cgbl']);
        $split_m1 = $split_m[0]; // 拆分前的股票(股)比率
        $split_m2 = $split_m[1]; // 拆分后的股票(股)比率
        if ($c_type == 1) { // 自动拆分固定比例1:2
            $split_m1 = 1;
            $split_m2 = 2;
        }
        // 计算拆分后的价格【根据拆分前后的总价值相等算出拆分后的价格】
        $cur_one_price = ((int) ((($old_one_price * $split_m[0]) / $split_m2) * 10000)) / 10000;
        // 拆分后达人的股票(股)变动比率【根据拆分前后的比率为$split_m1/$split_m2】
        $cur_gp = $split_m2 / $split_m1;
        
        // 拆分之前先把股票(股)的交易功能关闭掉,以免出错
        $fee->execute("update __TABLE__ set gp_kg=1 where id=1");
        
        // 撤消所有未完成交易
        $this->canel_jy();
        
        foreach ($gp_cfmax as $key => $vo) {
            // 更新达人的股票(股)信息
            // $result = $fck->execute('update __TABLE__ set live_gupiao=live_gupiao*' . $cur_gp . ',gp_cnum=gp_cnum+1 where id>0 AND get_level=' . ($key + 1) . ' and live_gupiao>0 AND live_gupiao*' . $cur_gp.'<' . $vo);
        }
        
        M('gp_sell')->query("update __TABLE__ set sNun=sNun*$cur_gp,sell_ln=sell_ln*$cur_gp,sell_mon=sell_mon+1 where is_over=0");
        
        // 更新会员股数
        $this->gx_all_gp_tj();
        
        // 更新股票(股)的当前价格,恢复1:1
        $fee->execute("update __TABLE__ set  gp_fxnum=gp_fxnum*$cur_gp,gp_cnum=gp_cnum+1,gp_cgbl='1:1' where id=1");
        // 更新股票(股)价格
        $gp->execute("update __TABLE__ set opening=" . $cur_one_price . ",f_date=" . time() . "");
        
        // 拆分完毕，重新恢复股票(股)的之前设置
        $fee->execute("update __TABLE__ set gp_kg=$old_close_gp where id=1");
        
        $list = $fck->where(' live_gupiao>1 ')
            ->order('rdt desc')
            ->select();
        $fck = D('Fck');
        $all_chai_money = 0;
        foreach ($list as $key => $vo) {
            
            $feng_ding = fdjuli($vo['id']);
            IF ($feng_ding > 0) {
                $feng = M('split_gp')->where('user_id=' . $vo['id'])->sum('cf_money');
                if ($feng == null) {
                    $feng = 0;
                }
                $myulv = $vo['get_level'];
                $ss = $myulv - 1;
                
                $gp_cnum = $vo['gp_cnum'];
                $live_gupiao = $vo['live_gupiao'];
                
                $t_order = M('t_orders');
                $trade_gupiao = $t_order->where('uid=' . $vo['id'] . ' AND type=0')->sum('num');
                if ($trade_gupiao == null) {
                    $trade_gupiao = 0;
                }
                
                $money = 0;
                $model = $fck->field('gp_cnum,live_gupiao')->find($vo['id']);
                
                $cf_money = 0;
                $gp_cnum = $model['gp_cnum'];
                $gp_inm = 0;
                $gp_inn = 0;
                $gp_ino = 0;
                $gp_shui = 0;
                if ($gp_cnum == 0) {
                    $cf_money = $model['live_gupiao'] * $cur_gp;
                    $result = $fck->execute('update __TABLE__ set live_gupiao=' . $cf_money . '  where id=' . $vo['id']);
                    $result = $fck->execute('update __TABLE__ set  gp_cnum=gp_cnum+1 where id=' . $vo['id']);
                    $model = $fck->field('gp_cnum,live_gupiao')->find($vo['id']);
                    $gp_cnum = $model['gp_cnum'];
                    $rs[] = M('split_gp')->add(array(
                        'user_id' => $vo['id'],
                        'price' => $fee_rs['gp_one'],
                        'money' => $money,
                        'cf_money' => $cf_money,
                        'gp_inm' => $gp_inm,
                        'gp_inn' => $gp_inn,
                        'gp_ino' => $gp_ino,
                        'gp_shui' => $gp_shui,
                        'ctime' => time(),
                        'cur_gp' => $cur_gp,
                        'remark' => '第' . $gp_cnum . '次拆分'
                    ));
                } else {
                    if ($gp_cnum >= 1) {
                        
                        $split_gp_gupiao = M('split_gp')->where('user_id=' . $vo['id'])->sum('money');
                        if ($split_gp_gupiao == null) {
                            $split_gp_gupiao = 0;
                        }
                        $live_gupiao = $live_gupiao + $split_gp_gupiao;
                        
                        if ($vo['id'] > 1) {
                            
                            if (($model['live_gupiao'] * $cur_gp) > ($gp_cfmax[$ss] - $split_gp_gupiao - $trade_gupiao)) {
                                $cf_money = ($gp_cfmax[$ss] - $split_gp_gupiao - $trade_gupiao) - (($gp_cfmax[$ss] - $split_gp_gupiao - $trade_gupiao) * $fee_rs['gp_zhidao'] * 0.01);
                                $money = (($gp_cfmax[$ss] - $split_gp_gupiao - $trade_gupiao) * $fee_rs['gp_zhidao'] * 0.01);
                            } else {
                                $cf_money = $model['live_gupiao'] * $cur_gp - $model['live_gupiao'] * $cur_gp * $fee_rs['gp_zhidao'] * 0.01 - $trade_gupiao;
                                $money = $model['live_gupiao'] * $fee_rs['gp_zhidao'] * 0.01;
                                $money = $money * $cur_gp;
                                $money = $money - $trade_gupiao;
                            }
                        } else {
                            
                            $cf_money = $model['live_gupiao'] * $cur_gp;
                            $money = $model['live_gupiao'] * $fee_rs['gp_zhidao'] * 0.01;
                            $money = $money * $cur_gp;
                            $money = $money - $trade_gupiao;
                        }
                    }
                    
                    // 更新达人的股票(股)信息
                    if ($cf_money > 0) {
                        $result = $fck->execute('update __TABLE__ set live_gupiao=' . $cf_money . '  where id=' . $vo['id']);
                        $result = $fck->execute('update __TABLE__ set  gp_cnum=gp_cnum+1 where id=' . $vo['id']);
                    }
                    
                    $model = $fck->field('gp_cnum,live_gupiao')->find($vo['id']);
                    
                    $gp_cnum = $model['gp_cnum'];
                    
                    if ($gp_cnum >= 2 && $money > 0) {
                        $peisong = M('peisong');
                        $peisong_rs = $peisong->where('uid=' . $vo['id'])
                            ->order(' addtime desc')
                            ->find();
                        
                        // 分账现金点值
                        $gp_inm = $money * $fee_rs['gp_inm'] * 0.01 * $peisong_rs['gp_one'];
                        // 分账复投积分
                        $gp_inn = $money * $fee_rs['gp_inn'] * 0.01 * $peisong_rs['gp_one'];
                        // 分账幸运积分
                        $gp_ino = $money * $fee_rs['gp_ino'] * 0.01 * $peisong_rs['gp_one'];
                        // 税收
                        $gp_shui = $money * $fee_rs['gp_perc'] * 0.01 * $peisong_rs['gp_one'];
                        $kt_cont = "指导销售";
                        $fck->where(array(
                            'id' => $vo['id']
                        ))->setInc('agent_use', $gp_inm);
                        $fck->addencAdd($vo['id'], $vo['user_id'], $gp_inm, 19, 0, 0, 0, $kt_cont . '-现金点值'); // 激活积分扣除历史记录
                        $fck->where(array(
                            'id' => $vo['id']
                        ))->setInc('agent_xf', $gp_inn);
                        $fck->addencAdd($vo['id'], $vo['user_id'], $gp_inn, 19, 0, 0, 0, $kt_cont . '-复投积分'); // 注册积分扣除历史记录
                        $fck->where(array(
                            'id' => $vo['id']
                        ))->setInc('agent_cf', $gp_ino);
                        $fck->addencAdd($vo['id'], $vo['user_id'], $gp_ino, 19, 0, 0, 0, $kt_cont . '-幸运积分'); // 注册积分扣除历史记录
                        
                        if ($vo['id'] == 1) {
                            
                            $fck->where(array(
                                'id' => $vo['id']
                            ))->setDec('live_gupiao', $money);
                        }
                        $fck->addencAdd($vo['id'], $vo['user_id'], - $money, 19, 0, 0, 0, $kt_cont . '-股票点值');
                        
                        $all_chai_money = $all_chai_money + $money;
                    }
                    $rs[] = M('split_gp')->add(array(
                        'user_id' => $vo['id'],
                        'price' => $fee_rs['gp_one'],
                        'money' => $money,
                        'cf_money' => $cf_money,
                        'gp_inm' => $gp_inm,
                        'gp_inn' => $gp_inn,
                        'gp_ino' => $gp_ino,
                        'gp_shui' => $gp_shui,
                        'ctime' => time(),
                        'cur_gp' => $cur_gp,
                        'remark' => '第' . $gp_cnum . '次拆分'
                    ));
                }
            }
        }
        if ($all_chai_money > 0) {
            $admin = $fck->field('gp_cnum,live_gupiao,user_id,id')->find(1);
            
            $fck->where(array(
                'id' => $admin['id']
            ))->setInc('live_gupiao', $all_chai_money);
            $fck->addencAdd($admin['id'], $admin['user_id'], $all_chai_money, 19, 0, 0, 0, '返回' . $kt_cont . '-股票点值');
        }
    }
    
    // 公司自动抛售
    private function gx_auto_sell()
    {
        $one_price = 0.1;
        $sNunb = 6000000;
        $ok_sell = 0;
        $ok_money = $ok_sell * $one_price;
        
        $data = array();
        $data['uid'] = 1;
        $data['one_price'] = $one_price;
        $data['price'] = $sNunb * $one_price; // 总得股票(股)金额
        $data['sNun'] = $sNunb; // 总的股票(股)数
        $data['used_num'] = $ok_sell; // 成功买到的股票(股)
        $data['lnum'] = $sNunb - $ok_sell; // 还差没有售出的股票(股)
        $data['ispay'] = 0; // 交易是否完成
        $data['eDate'] = time(); // 售出时间
        $data['status'] = 0; // 这条记录有效
        $data['type'] = 1; // 标识为售出
        $data['is_en'] = 0; // 标准股
        $data['spid'] = 0; // 原卖出记录ID
        $data['last_s'] = 0; // 是否最后一次卖出
        $data['sell_g'] = $ok_money; // 售出获得总额
        $resid = M('gupiao')->add($data); // 添加记录
        if ($resid) {
            M('fck')->query("update __TABLE__ SET all_out_gupiao=all_out_gupiao+" . $sNunb . " WHERE `id`=1");
        }
        unset($data);
    }
    
    // 两个数字相除
    private function numb_duibi($a, $b)
    {
        $numb = 3;
        $chub = pow(10, $numb);
        $c_a = (int) ($a * $chub);
        $c_b = (int) ($b * $chub);
        $c_c = $c_a / $c_b;
        return $c_c;
    }
    
    // 取消未完成交易
    public function canel_jy()
    {
        $fck = M('fck');
        $gupiao = M('gupiao');
        $where = array();
        $where = 'uid>0 and status=0 and lnum>0 and ispay=0 and type=1'; // 卖
        $mrs = $gupiao->where($where)->select();
        foreach ($mrs as $vo) {
            $sNun = $vo['sNun']; // 总得交易数
            $used_num = $vo['used_num']; // 成功成交得数量
            $lnum = $vo['lnum']; // 余下的数量
            $en = $vo['is_en'];
            
            if ($lnum + $used_num != 0) {
                // 交易成功跟余下的数量不等于0,退出此次循环
                if ($lnum + $used_num != $sNun) {
                    continue;
                }
            }
            $resulta = $fck->execute("UPDATE __TABLE__ SET live_gupiao=live_gupiao+" . $lnum . ",all_out_gupiao=all_out_gupiao-" . $lnum . " WHERE `id`=" . $vo['uid']);
            if ($resulta) {
                $cx_content = "撤销出售 " . $lnum . " 个";
                // 撤销的话要更新股票(股)表
                $data = array();
                $data['ispay'] = 1;
                $data['is_cancel'] = 1;
                $data['sNun'] = $vo['used_num'];
                $data['lnum'] = 0;
                $data['bz'] = $cx_content;
                $gupiao->where('id=' . $vo['id'])->save($data);
            }
        }
        unset($where, $mrs, $vo);
        
        $where = array();
        $where = 'uid>0 and status=0 and buy_s>0 and ispay=0 and type=0'; // 买
        $mrs = $gupiao->where($where)->select();
        foreach ($mrs as $vo) {
            $buy_s = $vo['buy_s']; // 剩余额度
            $resulta = $fck->execute("UPDATE __TABLE__ SET agent_use=agent_use+" . $buy_s . " WHERE `id`=" . $vo['uid']);
            if ($resulta) {
                $data = array();
                $data['ispay'] = 1;
                $data['is_cancel'] = 1;
                $gupiao->where('id=' . $vo['id'])->save($data);
            }
        }
        
        $t_orders = M('t_orders');
        
        $where = array();
        $where = 'uid>0 and status=0  and type=0  '; // 买
        $mrs = $t_orders->where($where)->select();
        foreach ($mrs as $vo) {
            $buy_s = $vo['shengyu_num'] * $vo['price']; // 剩余额度
            $resulta = $fck->execute("UPDATE __TABLE__ SET live_gupiao=live_gupiao+" . $buy_s . " WHERE `id`=" . $vo['uid']);
            if ($resulta) {
                $rs[] = $t_orders->where('id=' . $vo['id'])->delete();
            }
        }
        
        unset($where, $mrs, $vo);
        unset($fck, $gupiao);
    }
    
    // 求购股票(股)列表
    public function buylist()
    {
        $this->_Admin_checkUser();
        $gupiao = M('t_orders');
        import("@.ORG.ZQPage"); // 导入分页类
        $where = 'type=1 and id>0';
        $field = '*';
        $count = $gupiao->where($where)
            ->field($field)
            ->count(); // 总页数
        $listrows = 10; // 每页显示的记录数
        $Page = new ZQPage($count, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $list = $gupiao->where($where)
            ->field($field)
            ->order(' id desc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        foreach ($list as $k => $v) {
            $list[$k]['trade_total'] = floatval($v['price'] * $v['num']);
            $list[$k]['shengyu_trade_total'] = floatval($v['price'] * $v['shengyu_num']);
            $list[$k]['trade_total_num'] = ($v['num'] - $v['shengyu_num']);
            $list[$k]['trade_total_money'] = floatval($v['price'] * $list[$k]['trade_total_num']);
            
            $user = M('fck')->find($v['uid']);
            
            $list[$k]['member_id'] = $user['user_id'];
            $list[$k]['shengyu_num'] = (int) $v['shengyu_num'];
        }
        
        $this->assign('list', $list);
        $this->display('buylist');
    }
    
    // 出售股票(股)列表
    public function selllist()
    {
        $this->_Admin_checkUser();
        $gupiao = M('t_orders');
        import("@.ORG.ZQPage"); // 导入分页类
        $where = 'type=0 and id>0';
        $field = '*';
        $count = $gupiao->where($where)
            ->field($field)
            ->count(); // 总页数
        $listrows = 10; // 每页显示的记录数
        $Page = new ZQPage($count, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $list = $gupiao->where($where)
            ->field($field)
            ->order('id desc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        foreach ($list as $k => $v) {
            $list[$k]['trade_total'] = floatval($v['price'] * $v['num']);
            $list[$k]['shengyu_trade_total'] = floatval($v['price'] * $v['shengyu_num']);
            $list[$k]['trade_total_num'] = ($v['num'] - $v['shengyu_num']);
            $list[$k]['trade_total_money'] = floatval($v['price'] * $list[$k]['trade_total_num']);
            
            $user = M('fck')->find($v['uid']);
            
            $list[$k]['member_id'] = $user['user_id'];
            $list[$k]['shengyu_num'] = (int) $v['shengyu_num'];
        }
        
        $this->assign('list', $list);
        $this->display('selllist');
    }
    // 出售股票(股)列表
    public function allselllist()
    {
        $this->_Admin_checkUser();
        $gupiao = M('t_orders');
        import("@.ORG.ZQPage"); // 导入分页类
        $where = 'type=0 and id>0 and status=0 ';
        $field = '*';
        $count = $gupiao->where($where)
            ->field($field)
            ->count(); // 总页数
        $listrows = 10; // 每页显示的记录数
        $Page = new ZQPage($count, $listrows, 1);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $list = $gupiao->where($where)
            ->field($field)
            ->order('id desc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        foreach ($list as $k => $v) {
            $list[$k]['trade_total'] = floatval($v['price'] * $v['num']);
            $list[$k]['shengyu_trade_total'] = floatval($v['price'] * $v['shengyu_num']);
            $list[$k]['trade_total_num'] = ($v['num'] - $v['shengyu_num']);
            $list[$k]['trade_total_money'] = floatval($v['price'] * $list[$k]['trade_total_num']);
            
            $user = M('fck')->find($v['uid']);
            
            $list[$k]['member_id'] = $user['user_id'];
            $list[$k]['shengyu_num'] = (int) $v['shengyu_num'];
        }
        
        $this->assign('list', $list);
        $this->display('allselllist');
    }

    public function buy()
    {
        $fee = M('fee');
        $fee_s = $fee->field('*')->find();
        
        if ($fee_s['gp_kg'] == 1) {
            $this->ajaxReturn('', '暂未开市！', 0);
            exit();
        }
        
        $num = floatval($_POST['sNun']);
        $price = floatval($_POST['one_price']);
        $coin = $_POST['coin'];
        $paypwd = md5(trim($_POST['paypwd']));
        $fck = M('fck');
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $where['id'] = $id;
        $field = '*';
        $rs = $fck->where($where)
            ->field($field)
            ->find();
        
        if (! chkInt($num)) {
            $this->ajaxReturn('', '请输入数字！', 0);
            exit();
        }
        if ($num < 1) {
            $this->ajaxReturn('', '数量必须大于1！', 0);
            exit();
        }
        
        // $max_chushou=$fee_s['max_chushou'];
        // if ($max_chushou < $num) {
        // $this->ajaxReturn('', '单笔手数不能大于'.$max_chushou, 0);
        // exit();
        // }
        
        if (! chkNum($price)) {
            $this->ajaxReturn('', '请输入数字！', 0);
            exit();
        }
        
        if ($paypwd != $rs['passopen']) {
            $this->ajaxReturn('', '交易密码不正确！', 0);
            exit();
        }
        
        $total = $num * $price;
        // $max_price = $fee_s['zhangting'];
        // $min_price = $fee_s['dieting'];
        // $max_price = ! empty($max_price) ? $max_price : '10';
        // $min_price = ! empty($min_price) ? $min_price : '0';
        // // 计算出售比例
        // $keyi_sell = $rs['agent_use'] * $fee_s['max_chushou'];
        // if ($price > $max_price || $price < $min_price) {
        // $this->error('超出涨跌停范围！');
        
        // exit();
        // }
        
        if ($rs['agent_use'] < $total) {
            
            $this->error('现金点值不足！');
            
            exit();
        }
        
        $rs[] = $fck->save(array(
            'id' => $rs['id'],
            'agent_use' => array(
                'exp',
                'agent_use-' . $total
            )
        ));
        
        $nowdate = time();
        // 轉出
        $history = M('history'); // 明細表
        $data = array();
        $data['uid'] = $rs['id']; // 轉出會員ID
        $data['user_id'] = $rs['user_id'];
        $data['did'] = ''; // 轉入會員ID
        $data['user_did'] = $rs['user_id'];
        $data['action_type'] = 21; // 轉入還是轉出
        $data['pdt'] = $nowdate; // 轉帳時間
        $data['epoints'] = $total; // 進出金額
        $data['allp'] = 0;
        $data['bz'] = '买入交易,消费现金点值 '; // 備註
        $data['type'] = 1; // 1轉帳
        
        $rs2 = $history->add($data);
        
        // $data = array();
        // $data['uid'] = $rs['id']; // 轉出會員ID
        // $data['user_id'] = $rs['user_id'];
        // $data['did'] = ''; // 轉入會員ID
        // $data['user_did'] = $rs['user_id'];
        // $data['action_type'] = 21; // 轉入還是轉出
        // $data['pdt'] = $nowdate; // 轉帳時間
        // $data['epoints'] = $total; // 進出金額
        // $data['allp'] = 0;
        // $data['bz'] = '买入交易,冻结米拉'; // 備註
        // $data['type'] = 1; // 1轉帳
        
        // $rs2 = $history->add($data);
        
        $orders = M('t_orders');
        $rs[] = $orders->add(array(
            'uid' => $rs['id'],
            'price' => $price,
            'num' => $num,
            'shengyu_num' => $num,
            'type' => 1,
            'coin' => $coin,
            'ctime' => time()
        ));
        
        if (! chkArr($rs)) {
            
            $this->error('提交失败！');
            
            exit();
        }
        $this->runTrade($coin, $price, 'buy');
        
        $bUrl = __URL__ . '/trade';
        
        return $this->success('提交成功！', $bUrl);
        
        exit();
    }

    public function runTrade($coin, $price, $type, $trade)
    {
        $history = M('history'); // 明細表
        $fee = M('fee');
        $fee_s = $fee->field('*')->find();
        $mo = M('t_orders');
        $fck = M('fck');
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $where['id'] = $id;
        $field = '*';
        $rs_user = $fck->where($where)
            ->field($field)
            ->find();
        $t_sxf = M('t_sxf');
        $t_trans = M('t_trans');
        $buy_str = '';
        $sell_str = '';
        if ($type == 'buy') {
            // $buy_str = 'uid!=' . $id . ' and';
        }
        if ($type == 'sell') {
            // $sell_str = 'uid!=' . $id . ' and';
        }
        for (;;) {
            $rs = array();
            $buy = $mo->where($sell_str . '    status=0 and type=1 and coin="' . $coin . '" ')
                ->order('price desc')
                ->find();
            $sell = $mo->where($buy_str . '   status=0 and type=0 and coin="' . $coin . '" AND ID=' . $trade['id'])
                ->order('price asc')
                ->find();
            
            $price_buy = $buy['price'];
            $price_sell = $sell['price'];
            if ($buy && $sell && floatval($price_buy) - floatval($price_sell) >= 0) {
                $amount = min($buy['num'] - $buy['deal'], $sell['num'] - $sell['deal']);
                // 价格按最小额计算
                $price = min($price_buy, $price_sell);
                $rs[] = $mo->save(array(
                    'id' => $buy['id'],
                    'deal' => array(
                        'exp',
                        'deal+' . $amount
                    )
                ));
                $rs[] = $mo->save(array(
                    'id' => $sell['id'],
                    'deal' => array(
                        'exp',
                        'deal+' . $amount
                    )
                ));
                
                $rs[] = $mo->save(array(
                    'id' => $buy['id'],
                    'shengyu_num' => array(
                        'exp',
                        'shengyu_num-' . $amount
                    )
                ));
                $rs[] = $mo->save(array(
                    'id' => $sell['id'],
                    'shengyu_num' => array(
                        'exp',
                        'shengyu_num-' . $amount
                    )
                ));
                
                $realnum = $amount; // - $amount*$this->sys['tradefee']/100;
                $total = $amount * $price;
                // 需要修正价格差造成的数据差,把少花的钱加回来,多扣除冻结，增加不冻结
                $xiuzheng = ($price_buy - $price) * $amount;
                
                $buy_user = $fck->where('id=' . $buy['uid'])
                    ->field($field)
                    ->find();
                $sell_user = $fck->where('id=' . $sell['uid'])
                    ->field($field)
                    ->find();
                $rs[] = $fck->save(array(
                    'id' => $buy['uid'],
                    
                    'agent_use' => array(
                        'exp',
                        'agent_use-' . $xiuzheng
                    ),
                    'live_gupiao' => array(
                        'exp',
                        'live_gupiao+' . $xiuzheng
                    )
                ));
                
                $nowdate = time();
                
                $fee_rs = $fee->find();
                
                $money = $sell['num'] * $price;
                
                $fck->save(array(
                    'id' => 1,
                    'live_gupiao' => array(
                        'exp',
                        'live_gupiao+' . $amount
                    )
                ));
                
                // 分账现金点值
                $gp_inm = $money * $fee_rs['gp_inm'] * 0.01;
                // 分账复投积分
                $gp_inn = $money * $fee_rs['gp_inn'] * 0.01;
                // 分账幸运积分
                $gp_ino = $money * $fee_rs['gp_ino'] * 0.01;
                $fck = D('Fck');
                $kt_cont = "交易匹配";
                $fck->where(array(
                    'id' => $sell_user['id']
                ))->setInc('agent_use', $gp_inm);
                $fck->addencAdd($sell_user['id'], $sell_user['user_id'], $gp_inm, 19, 0, 0, 0, $kt_cont . '-现金点值'); // 激活积分扣除历史记录
                $fck->where(array(
                    'id' => $sell_user['id']
                ))->setInc('agent_xf', $gp_inn);
                $fck->addencAdd($sell_user['id'], $sell_user['user_id'], $gp_inn, 19, 0, 0, 0, $kt_cont . '-复投积分'); // 注册积分扣除历史记录
                $fck->where(array(
                    'id' => $sell_user['id']
                ))->setInc('agent_cf', $gp_ino);
                $fck->addencAdd($sell_user['id'], $sell_user['user_id'], $gp_ino, 19, 0, 0, 0, $kt_cont . '-幸运积分'); // 注册积分扣除历史记录
                
                $data = array();
                
                $data['userid'] = $sell['uid'];
                $data['money'] = $total * $fee_s['tradefee'] / 100;
                $data['time'] = time();
                $rs[] = $t_sxf->add($data);
                
                $rs[] = $t_trans->add(array(
                    'buyid' => $buy['id'],
                    'sellid' => $sell['id'],
                    'shouxufei' => 0,
                    'ctime' => time(),
                    'ctime_' => date("Y/m/d H:i:sa"),
                    'price' => $price,
                    'type' => 1,
                    'uid' => $buy['uid'],
                    'num' => $amount,
                    'coin' => $coin
                ));
                $rs[] = $t_trans->add(array(
                    'buyid' => $buy['id'],
                    'sellid' => $sell['id'],
                    'shouxufei' => $total * $fee_s['tradefee'] / 100,
                    'ctime' => time(),
                    'ctime_' => date("Y/m/d H:i:sa"),
                    'price' => $price,
                    'type' => 0,
                    'uid' => $sell['uid'],
                    'num' => $amount,
                    'coin' => $coin
                ));
                
                $rs[] = $mo->where('num=deal')->setField('status', 1);
            } else {
                break;
            }
            unset($rs);
        }
    }

    public function cancel()
    {
        $history = M('history'); // 明細表
        if (! chkNum($_POST['id']) || ! chkStr($_POST['coin'])) {
            $this->ajaxReturn('', '请选择要撤销的委托！', 1);
            exit();
        }
        ;
        
        $rs = array();
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $t_orders = M('t_orders');
        $fee = M('fee');
        $fee_s = $fee->field('*')->find();
        $Orders = $t_orders->where('id=' . $_POST['id'] . ' and status=0')->find();
        if (! $Orders) {
            
            $this->ajaxReturn('', '无撤销项！', 1);
            exit();
        }
        $t_user = M('fck');
        $user = $t_user->find($Orders['uid']);
        if ($Orders['type'] == 1) {
            if ($_POST['coin'] == 'kg') {
                $total = $Orders['shengyu_num'] * $Orders['price'];
                $rs[] = $t_user->save(array(
                    'id' => $Orders['uid'],
                    'agent_use' => array(
                        'exp',
                        'agent_use+' . $total
                    ),
                    'agent_xf' => array(
                        'exp',
                        'agent_xf-' . $total
                    )
                ));
                
                $nowdate = time();
                $data = array();
                $data['uid'] = $user['id']; // 轉出會員ID
                $data['user_id'] = $user['user_id'];
                $data['did'] = ''; // 轉入會員ID
                $data['user_did'] = $user['user_id'];
                $data['action_type'] = 21; // 轉入還是轉出
                $data['pdt'] = $nowdate; // 轉帳時間
                $data['epoints'] = ($total); // 進出金額
                $data['allp'] = 0;
                $data['bz'] = '撤销交易,返还现金点值'; // 備註
                $data['type'] = 1; // 1轉帳
                
                if (($total) > 0) {
                    $rs2 = $history->add($data);
                }
                
                $nowdate = time();
                $data = array();
                $data['uid'] = $user['id']; // 轉出會員ID
                $data['user_id'] = $user['user_id'];
                $data['did'] = ''; // 轉入會員ID
                $data['user_did'] = $user['user_id'];
                $data['action_type'] = 21; // 轉入還是轉出
                $data['pdt'] = $nowdate; // 轉帳時間
                $data['epoints'] = ($total); // 進出金額
                $data['allp'] = 0;
                $data['bz'] = '撤销交易,扣除冻结米拉'; // 備註
                $data['type'] = 1; // 1轉帳
                
                if (($total) > 0) {
                    $rs2 = $history->add($data);
                }
            } else {
                $rs[] = $t_user->save(array(
                    'id' => $Orders['uid'],
                    'ks' => array(
                        'exp',
                        'ks+' . ($Orders['shengyu_num'])
                    ),
                    'ks_frozen' => array(
                        'exp',
                        'ks_frozen-' . ($Orders['shengyu_num'])
                    )
                ));
            }
        } else {
            $total = (($Orders['shengyu_num'])) * $Orders['price'];
            $rs[] = $t_user->save(array(
                'id' => $Orders['uid'],
                'live_gupiao' => array(
                    'exp',
                    'live_gupiao+' . $total
                ),
                'agent_kt_dj' => array(
                    'exp',
                    'agent_kt_dj-' . $total
                )
            ));
            
            $nowdate = time();
            $data = array();
            $data['uid'] = $user['id']; // 轉出會員ID
            $data['user_id'] = $user['user_id'];
            $data['did'] = ''; // 轉入會員ID
            $data['user_did'] = $user['user_id'];
            $data['action_type'] = 21; // 轉入還是轉出
            $data['pdt'] = $nowdate; // 轉帳時間
            $data['epoints'] = ($total); // 進出金額
            $data['allp'] = 0;
            $data['bz'] = '撤销交易,返还股票'; // 備註
            $data['type'] = 1; // 1轉帳
            
            if (($total) > 0) {
                $rs2 = $history->add($data);
            }
            $nowdate = time();
            $data = array();
            $data['uid'] = $user['id']; // 轉出會員ID
            $data['user_id'] = $user['user_id'];
            $data['did'] = ''; // 轉入會員ID
            $data['user_did'] = $user['user_id'];
            $data['action_type'] = 21; // 轉入還是轉出
            $data['pdt'] = $nowdate; // 轉帳時間
            $data['epoints'] = ($total); // 進出金額
            $data['allp'] = 0;
            $data['bz'] = '撤销交易,扣除冻结种子'; // 備註
            $data['type'] = 1; // 1轉帳
            
            if (($total) > 0) {
                $rs2 = $history->add($data);
            }
        }
        
        $rs[] = $t_orders->where('id=' . $_POST['id'])->delete();
        
        if (chkArr($rs)) {
            
            $this->ajaxReturn('', '撤销成功！', 0);
        } else {
            $this->ajaxReturn('', '撤销失败！', 1);
        }
    }

    function auto_buy()
    {
        $fee = M('fee');
        $fee_s = $fee->field('*')->find();
        
        if ($fee_s['gp_kg'] == 1) {
            $this->ajaxReturn('', '暂未开市！', 0);
            exit();
        }
        
        $ids = $_POST['ids'];
        if (! isset($ids) || empty($ids)) {
            $this->error('请选择记录！');
        }
        foreach ($ids as $vo) {
            $trade = M('t_orders')->find($vo);
            
            $num = floatval($trade['num']);
            $price = floatval($trade['price']);
            $coin = $trade['coin'];
            $fck = M('fck');
            $id = $_SESSION[C('USER_AUTH_KEY')];
            $where['id'] = $id;
            $field = '*';
            $rs = $fck->where($where)
                ->field($field)
                ->find();
            
            if (! chkInt($num)) {
                $this->ajaxReturn('', '请输入数字！', 0);
                exit();
            }
            if ($num < 1) {
                $this->ajaxReturn('', '数量必须大于1！', 0);
                exit();
            }
            
            // $max_chushou=$fee_s['max_chushou'];
            // if ($max_chushou < $num) {
            // $this->ajaxReturn('', '单笔手数不能大于'.$max_chushou, 0);
            // exit();
            // }
            
            if (! chkNum($price)) {
                $this->ajaxReturn('', '请输入数字！', 0);
                exit();
            }
            
            $total = $num * $price;
            
            // if ($rs['agent_use'] < $total) {
            
            // $this->error('现金点值不足！');
            
            // exit();
            // }
            
            // $rs[] = $fck->save(array(
            // 'id' => $rs['id'],
            // 'agent_use' => array(
            // 'exp',
            // 'agent_use-' . $total
            // )
            // ));
            
            // $nowdate = time();
            // // 轉出
            // $history = M('history'); // 明細表
            // $data = array();
            // $data['uid'] = $rs['id']; // 轉出會員ID
            // $data['user_id'] = $rs['user_id'];
            // $data['did'] = ''; // 轉入會員ID
            // $data['user_did'] = $rs['user_id'];
            // $data['action_type'] = 19; // 轉入還是轉出
            // $data['pdt'] = $nowdate; // 轉帳時間
            // $data['epoints'] = $total; // 進出金額
            // $data['allp'] = 0;
            // $data['bz'] = '买入交易,消费现金点值 '; // 備註
            // $data['type'] = 1; // 1轉帳
            
            // $rs2 = $history->add($data);
            
            $orders = M('t_orders');
            $rs[] = $orders->add(array(
                'uid' => $rs['id'],
                'price' => $price,
                'num' => $num,
                'shengyu_num' => $num,
                'type' => 1,
                'coin' => $coin,
                'ctime' => time()
            ));
            
            if (! chkArr($rs)) {
                
                $this->error('提交失败！');
                
                exit();
            }
            $this->runTrade($coin, $price, 'buy', $trade);
        }
        
        $bUrl = __URL__ . '/trade';
        
        return $this->success('提交成功！', $bUrl);
        
        exit();
    }
}
?>

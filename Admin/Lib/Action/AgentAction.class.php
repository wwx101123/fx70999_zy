<?php
// 注册模块
class AgentAction extends CommonAction
{

    public function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        header("Content-Type:text/html; charset=utf-8");
        $this->_inject_check(0); // 调用过滤函数
        $this->_Config_name(); // 调用参数
        $this->_checkUser();
        if ($_POST['is_mobile'] == 1) {
            $_SESSION[C('USER_AUTH_KEY')] = $this->_post('user_id');
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
                if ($list['is_agent'] >= 2) {
                    $this->error('您已经是服务中心!');
                    exit();
                }
                $_SESSION['Urlszpass'] = 'MyssXiGua';
                $bUrl = __URL__ . '/agents'; // 申请代理
                $this->_boxx($bUrl);
                break;
            case 2:
                $_SESSION['Urlszpass'] = 'MyssShuiPuTao';
                $bUrl = __URL__ . '/menber'; // 未开通会员
                $this->_boxx($bUrl);
                break;
            
            case 3:
                $_SESSION['Urlszpass'] = 'Myssmenberok';
                $bUrl = __URL__ . '/menberok'; // 已开通会员
                $this->_boxx($bUrl);
                break;
            
            case 4:
                $_SESSION['UrlPTPass'] = 'MyssGuanXiGua';
                $bUrl = __URL__ . '/adminAgents'; // 后台确认服务中心
                $this->_boxx($bUrl);
                break;
            default:
                $this->error('二级密码错误!');
                exit();
        }
    }

    public function agents($Urlsz = 0)
    {
        $fee_rs = M('fee')->find();
        
        $fck = M('fck');
        $where = array();
        // 查询条件
        $where['id'] = $_SESSION[C('USER_AUTH_KEY')];
        $field = '*';
        $fck_rs = $fck->where($where)
            ->field($field)
            ->find();
        
        $provincelist = M('province')->select();
        $this->assign('provincelist', $provincelist);
        if ($fck_rs) {
            // 会员级别
            switch ($fck_rs['is_agent']) {
                case 0:
                    $agent_status = '未申请服务中心!';
                    break;
                case 1:
                    $agent_status = '申请正在审核中!';
                    break;
                case 2:
                    $agent_status = '服务中心已开通!';
                    break;
            }
            $this->assign('agent_status', $agent_status);
            $this->assign('fck_rs', $fck_rs);
            
            $Aname = explode("|", C('Agent_Us_Name'));
            $this->assign('Aname', $Aname);
            
            $promo = M('promo')->where(array(
                'uid' => $_SESSION[C('USER_AUTH_KEY')],
                'type' => 1
            ))->select();
            $this->assign('list', $promo);
            $this->display('agents');
        } else {
            $this->error('操作失败!');
            exit();
        }
    }
    
    // 申请会员中心中转函数
    public function agentsAC()
    {
        $content = $_POST['content'];
        $ranktype = $this->_post('ranktype');
        
        $fee = M('fee');
        $fee_rs = $fee->where('s15')->find(1);
        $s15 = explode('|', $fee_rs['s15']);
        
        $fck = M('fck');
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $where = array();
        $where['id'] = $id;
        
        $fck_rs = $fck->where($where)
            ->field('*')
            ->find();
        if ($fck_rs) {
            if ($fck_rs['is_pay'] == 0) {
                $this->error('临时会员不能申请!');
                exit();
            }
            
            if ($fck_rs['is_agent'] > 0) {
                $this->error('已申请,不能重复申请!');
                exit();
            }
            
            if ($fck_rs['l'] < $s15[0] || $fck_rs['r'] < $s15[0]) {
                $this->error('未达到申请条件!');
                exit();
            }
            
            $data = array();
            // $bqycount=0;
            // if($ranktype==3){
            // $shop_a = $this->_post('province');
            // if ($shop_a=="请选择") {
            // $this->ajaxError('请选择省！');
            // }
            // $data['shop_a']=$shop_a;
            // // echo $shop_a;
            // $bqycount = $fck->where(array('is_agent'=>array('gt',0),'shoplx'=>'3','shop_a'=>$shop_a))->count();
            // // echo $fck->_sql();
            // }elseif($ranktype==2){
            // $shop_b = $this->_post('city');
            // if ($shop_b=="请选择") {
            // $this->ajaxError('请选择市！');
            // }
            // $data['shop_b']=$shop_b;
            // $bqycount = $fck->where(array('is_agent'=>array('gt',0),'shoplx'=>'2','shop_b'=>$shop_b))->count();
            // }elseif($ranktype==1){
            // $shop_c = $this->_post('districe');
            // if ($shop_c=="请选择") {
            // $this->ajaxError('请选择区！');
            // }
            // $data['shop_c']=$shop_c;
            // $bqycount = $fck->where(array('is_agent'=>array('gt',0),'shoplx'=>'1','shop_c'=>$shop_c))->count();
            // }
            // if($bqycount>0){
            // $this->error('本区域的服务中心已经存在!');
            // exit;
            // }
            $promo = M('promo');
            
            $nowdate = time();
            $data['shoplx'] = $ranktype;
            $data['idt'] = time();
            $fck->where('id=' . $id)->save($data);
            // echo $fck->_sql();
            
            $pro['uid'] = $fck_rs['id'];
            $pro['user_id'] = $fck_rs['user_id'];
            $pro['user_name'] = $fck_rs['user_name'];
            $pro['u_lvel'] = $fck_rs['shoplx'];
            $pro['up_level'] = 1;
            $pro['type'] = 1;
            $pro['moeny'] = 0;
            $pro['moeny_two'] = 0;
            $pro['create_time'] = time();
            $promo->add($pro);
            $this->ajaxSuccess('申请成功，请耐心等待审核！');
        } else {
            $this->error('非法操作');
            exit();
        }
    }
    
    // 未开通会员
    public function menber($Urlsz = 0)
    {
        $is_mobile = I('is_mobile',0);
        $page_index = I('page_index', 1);
        $size = I('page_num', C('ONE_PAGE_RE'));
        // 列表过滤器，生成查询Map对象
        $fck = M('fck');
        $map = array();
        $id = $_SESSION[C('USER_AUTH_KEY')];
        
        $map['is_pay'] = array(
            'eq',
            0
        );
        $map['_string'] = "register_id=" . $id . " ";
        
        // 查询字段
        $field = '*';
        
        $p = $this->_get('p', true, '1');
        IF($is_mobile==1){
            $p =$page_index;
        }
        // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
        $list = $fck->where($map)
            ->order('rdt')
            ->page($p . ',' . $size)
            ->select();
        $this->assign('list', $list); // 赋值数据集
        import("@.ORG.Page"); // 导入分页类
        $count = $fck->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, $size); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $this->assign('page', $show); // 赋值分页输出
        
        $this->getLevelNamebyLevel($HYDJ, 1);
        $this->assign('HYDJ', $HYDJ); // 会员级别
        
        $agent_kt = $fck->where('id=' . $id)->getField('agent_kt');
        $agent_cash = $fck->where('id=' . $id)->getField('agent_cash');
        $this->assign('agent_kt', $agent_kt); // 购物券
        $this->assign('agent_cash', $agent_cash); // 购物券
        foreach ($list as $key => $vo) {
            if ($vo['treeplace'] == 0) {
                $list[$key]['treeplace_str'] = '左区';
            } else {
                
                $list[$key]['treeplace_str'] = '右区';
            }
            $list[$key]['rdt_str'] = date("Y-m-d H:i:s", $vo['rdt']);
            $list[$key]['level_str'] = getUserLevel($vo['u_level']);
        } 
        
        IF($is_mobile==1){
            
            
            $data = array();
            $data['data'] = $list;
            $data['current_count'] = count($list);
            $data['status'] = 1;
            $this->ajaxReturn($data);
            
        }else{
        
        
        
        
        $this->display();
    }}
    
    // 未开通会员
    public function menberok($Urlsz = 0)
    {
        // 列表过滤器，生成查询Map对象
        $fck = M('fck');
        $map = array();
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $gid = (int) $_GET['bj_id'];
        $map['shop_id'] = $id;
        $map['is_pay'] = array(
            'gt',
            0
        );
        $UserID = $_POST['UserID'];
        if (! empty($UserID)) {
            import("@.ORG.KuoZhan"); // 导入扩展类
            $KuoZhan = new KuoZhan();
            if ($KuoZhan->is_utf8($UserID) == false) {
                $UserID = iconv('GB2312', 'UTF-8', $UserID);
            }
            unset($KuoZhan);
            $where['nickname'] = array(
                'like',
                "%" . $UserID . "%"
            );
            $where['user_id'] = array(
                'like',
                "%" . $UserID . "%"
            );
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            $UserID = urlencode($UserID);
        }
        
        // 查询字段
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.ZQPage"); // 导入分页类
        $count = $fck->where($map)->count(); // 总页数
        $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
        $page_where = 'UserID=' . $UserID; // 分页条件
        $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $list = $fck->where($map)
            ->field($field)
            ->order('is_pay asc,pdt desc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        $HYJJ = '';
        $this->_levelConfirm($HYJJ, 1);
        $this->assign('voo', $HYJJ); // 会员级别
        $where = array();
        $where['id'] = $id;
        $fck_rs = $fck->where($where)
            ->field('*')
            ->find();
        $this->assign('frs', $fck_rs); // 购物券
        $this->display('menberok');
        exit();
    }

    /**
     * 提交的会员的操作
     *
     * @return null
     */
    public function menberAC()
    {
        // 处理提交按钮
        $action = $this->_get('action','confirm');
        // 获取复选框的值
        $id = $this->_get('id',11221);
        if ($_POST['is_mobile'] == 1) {
            
            $id = $_POST['id'];
            $action = $_POST['action'];
            session(C('USER_AUTH_KEY'), $_POST['user_id']);
        }
        if (! isset($id) || empty($id)) {
            $this->error('没有该会员');
            exit();
        }
        switch ($action) {
            case 'confirm':
                $this->_menberOpenUse($id, 0);
                break;
            case 'del':
                $this->_menberDelUse($id);
                break;
            default:
                $this->error('没有该会员');
                break;
        }
    }

    private function _menberOpenUse($OpID = 0, $reg_money = 0)
    {
        $ajax = false;
        $is_mobile = $_POST['is_mobile'];
        if ($is_mobile == 1) {
            $ajax = true;
        }
        
        $fck = D('Fck');
        $fee = M('fee');
        $gouwu = M('gouwu');
        $shouru = M('shouru');
        $fenhong = M('fenhong');
        
        if (! $fck->autoCheckToken($_POST)) {
            $this->error('页面过期，请刷新页面！');
            exit();
        }
        
        // 被开通会员参数
        $where = array();
        $where['id'] = $OpID;
        $where['is_pay'] = 0; // 未开通的
        $field = '*';
        
        $fee_rs = $fee->field('s3,s1,str6,s9,buy_point,agent_use,cjbs,give_ssb,ssb_money,s11,user_shui')->find();
        $s1 = explode("|", $fee_rs['s1']);
        $s3 = explode("|", $fee_rs['s3']);
        $s11= explode("|", $fee_rs['s11']);
        $str6 = $fee_rs['str6'] / 100;
        
        // 服务中心参数
        $where_two = array();
        $field_two = '*';
        $where_two['id'] = $_SESSION[C('USER_AUTH_KEY')];
        // $where_two['is_agent'] = array('gt',1);
        
        $fck->emptyTime();
        
        $rs = $fck->where($where_two)
            ->field($field_two)
            ->find(); // 找出登录会员(必须为服务中心并且已经登录)
        if (! $rs) {
            $this->ajaxError('会员错误！');
            exit();
        }
        $fck = M('fck');
        $vo = $fck->where($where)
            ->field('*')
            ->find();
        if (! $vo) {
            $this->ajaxError('系统繁忙！');
        }
        $ppath = $vo['p_path'];
        // 上级未开通不能开通下级员工
        $frs_where['is_pay'] = array(
            'eq',
            0
        );
        $frs_where['id'] = $vo['father_id'];
        $frs = $fck->where($frs_where)->find();
        if ($frs) {
            $this->ajaxError('开通失败，上级未开通');
            exit();
        }
        
        if ($reg_money == 0) {
            $us_money = $rs['agent_cash'];
            $us_monkt = $rs['agent_kt'];
            $money_a = $vo['cpzj'] * $str6;
            $money_b = $vo['cpzj'] * (1 - $str6);
            if ($us_monkt < $money_a&&$is_mobile == 1) {
                $this->ajaxError(C('agent_kt').'不足');
                exit();
            }
            if ($us_money < $money_b) {
                // $this->error('注册积分余额不足');
                // exit;
            }
        }
        if ($reg_money == 0) {
            // $fck->where(array('id'=>$rs['id']))->setDec('agent_kt',$money_a);
            // $result = $fck->where(array('id'=>$rs['id']))->setDec('agent_cash',$money_b);
        }
        $result = true;
        if ($result) {
            if ($reg_money == 0) {
                $kt_cont='开通会员赠送';
            }
            $fck = D('Fck');
            $fck->where(array(
                'id' => $rs['id']
            ))->setDec('agent_kt', $money_a);
            $kt_cont='开通会员扣除';
            $fck->addencAdd($rs['id'], $rs['user_id'], - $money_a, 19, 0, 0, 0, $kt_cont . '-'.C('agent_kt')); // 激活积分扣除历史记录
                                                                                                     // $fck->addencAdd($rs['id'], $vo['user_id'], -$money_b, 19,0,0,0,$kt_cont.'-注册积分');//注册积分扣除历史记录
                                                                                                     
            // 奖金额度增加
            $cjbs = explode('|', $fee_rs['cjbs']);
//             $limit_money = $cjbs[$vo['u_level'] - 1] * $money_a;
            $money_b=$s11[ $vo['u_level']-1];
            $kt_cont='开通会员赠送';
            $fck->query("update __TABLE__ set  buy_point=buy_point+" . $money_b . "  where `id`=" . $vo['id']);
            $fck->addencAdd($vo['id'], $vo['user_id'], $money_b, 19, 0, 0, 0, $kt_cont . '-'.C('buy_point'));
            
            $money_c= $fee_rs['user_shui'];
            $money_c = explode('|', $money_c);
            $money_c=$money_c[ $vo['u_level']-1];
            $kt_cont='开通会员赠送';
            $fck->query("update __TABLE__ set  agent_use=agent_use+" . $money_c . "  where `id`=" . $vo['id']);
            $fck->addencAdd($vo['id'], $vo['user_id'], $money_c, 19, 0, 0, 0, $kt_cont . '-'.C('agent_use'));
            
            // 给分享人添加分享人数或单数
            $fck->where('id=' . $vo['re_id'])->setInc('re_nums', 1);
            
            $nnrs = $fck->where('is_pay>0')
                ->field('n_pai')
                ->order('n_pai desc')
                ->find();
            
            // //接点人信息
            // $arry = array();
            // $arry = $this->gongpaixtsmall($voo['re_id']);
            // $father_id = $arry['father_id'];
            // $father_name = $arry['father_name'];
            // $TreePlace = $arry['treeplace'];
            // $p_level = $arry['p_level'];
            // $p_path = $arry['p_path'];
            // $u_pai = $arry['u_pai'];
            
            $in_gp = $s1[$vo['get_level'] - 1] * $vo['cpzj'] / 100;
            $data = array();
            $data['is_pay'] = 1;
            $data['is_fenh'] = 0;
            $data['pdt'] = time();
            // $data['fanli_time'] = time(); // 记录分红时间
            $data['agent_gp'] = $in_gp;
            $data['open'] = 0;
            
            // $data['father_id'] = $father_id;
            // $data['father_name'] = $father_name;
            // $data['treeplace'] = $TreePlace;
            // $data['p_level'] = $p_level;
            // $data['p_path'] = $p_path;
            // $data['u_pai'] = $u_pai;
            
            // 开通会员
            $result = $fck->where('id=' . $vo['id'])->save($data);
            
            unset($data);
            
            $data = array();
            $data['uid'] = $vo['id'];
            $data['user_id'] = $vo['user_id'];
            $data['in_money'] = $vo['cpzj'];
            $data['in_time'] = time();
            $data['in_bz'] = "新会员加入";
            $shouru->add($data);
            unset($data);
            
            // $data = array();
            // $data['uid'] = $vo['id'];
            // $data['user_id'] = $vo['user_id'];
            // $data['f_money'] = $in_gp;
            // $data['pdt'] = time();
            // $fenhong->add($data);
            $fck = D('Fck');
            unset($fenhong, $data);
            $s9 = explode("|", $fee_rs['s9']);
            // 统计单数
            $dan = 0;
//             if ($vo['is_money'] == 1) {
                $dan = $s9[$vo['u_level'] - 1];
                
                user_award($vo, $dan);
//             }
            $kt_cont = '激活赠送';
//             give_ssb($vo,$dan,$kt_cont);
//             if ($vo['register_type'] == 0) {
// //                 $give_ssb = explode('|', $fee['give_ssb']);
//                 $dan = $s9[$vo['get_level'] - 1];
//                 $fck = D('Fck');
//                 $give_ssb = $dan/$fee_rs['ssb_money'] ;
                
// //                 $give_ssb=round($give_ssb,2);
//                 $fck->where(array(
//                     'id' => $vo['id']
//                 ))->setInc('ssb', $give_ssb);
            
//                 $kt_cont = '激活赠送';
//                 $fck->addencAdd($vo['id'], $vo['user_id'], $give_ssb, 19, 0, 0, 0, $kt_cont . '-' . C('ssb'));
//             }
            
            
            
            
            if ($_POST['is_mobile'] == 1) {
                $data['info'] = '开通会员成功';
                $data['msg'] = '开通会员成功';
                $data['status'] = 1;
                $data['url'] = 'agent.html';
                $this->ajaxReturn($data);
                exit();
            } else {
                $this->success('开通会员成功');
            }
        } else {
            $this->ajaxError('开通会员失败');
        }
        unset($fck, $where, $where_two, $rs);
    }

    private function _menberDelUse($OpID = 0)
    {
        $ID = $_SESSION[C('USER_AUTH_KEY')];
        $fck = M('fck');
        $rs = $fck->where(array(
            'id' => $OpID,
            'is_pay' => 0
        ))
            ->order('id asc')
            ->find();
        if ($rs) {
            $whe['re_id'] = $OpID;
            $rss = $fck->where($whe)
                ->field('id')
                ->count();
            if ($rss) {
                $this->error('会员 ' . $rs['user_id'] . ' 有下级会员，不能删除！');
                exit();
            } else {
                $fck->where(array(
                    'id' => $OpID
                ))->delete();
                
                // $fee = M('fee')->find();
                // $str6 = $fee['str6'] / 100;
                // $money_a = $rs['cpzj'] * $str6;
                // $money_b = $rs['cpzj'] * (1 - $str6);
                
                // $vo = $fck->where(array(
                // 'id' => $ID
                // ))->find();
                // $kt_cont = "删除会员";
                // $fck->where(array(
                // 'id' => $vo['id']
                // ))->setInc('agent_kt', $money_a);
                // $result = $fck->where(array(
                // 'id' => $vo['id']
                // ))->setInc('agent_cash', $money_b);
                $fck = D('Fck');
                // $fck->addencAdd($vo['id'], $vo['user_id'], $money_a, 19, 0, 0, 0, $kt_cont . '-返还激活积分'); // 激活积分扣除历史记录
                // $fck->addencAdd($vo['id'], $vo['user_id'], $money_b, 19, 0, 0, 0, $kt_cont . '-返还注册积分'); // 注册积分扣除历史记录
                $data['info'] = '删除成功';
                $data['msg'] = '删除成功';
                $data['status'] = 1;
                $data['url'] = 'agent.html';
                $this->ajaxReturn($data);
                exit();
                // $this->success('删除成功！');
            }
        } else {
            $this->error('系统繁忙！');
        }
    }
    
    // 已开通会员
    public function frontMenber($Urlsz = 0)
    {
        // 列表过滤器，生成查询Map对象
        if ($_SESSION['Urlszpass'] == 'MyssDaShuiPuTao') {
            $fck = M('fck');
            $id = $_SESSION[C('USER_AUTH_KEY')];
            $map = array();
            $map['open'] = $id;
            $map['is_pay'] = array(
                'gt',
                0
            );
            $UserID = $_POST['UserID'];
            if (! empty($UserID)) {
                import("@.ORG.KuoZhan"); // 导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false) {
                    $UserID = iconv('GB2312', 'UTF-8', $UserID);
                }
                unset($KuoZhan);
                $where['nickname'] = array(
                    'like',
                    "%" . $UserID . "%"
                );
                $where['user_id'] = array(
                    'like',
                    "%" . $UserID . "%"
                );
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
                $UserID = urlencode($UserID);
            }
            
            // 查询字段
            $field = "*";
            // =====================分页开始==============================================
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $fck->where($map)->count(); // 总页数
            $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
            $page_where = 'UserID=' . $UserID; // 分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $list = $fck->where($map)
                ->field($field)
                ->order('pdt desc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            
            $HYJJ = '';
            $this->_levelConfirm($HYJJ, 1);
            $this->assign('voo', $HYJJ); // 会员级别
            $this->assign('list', $list); // 数据输出到模板
                                          // =================================================
            
            $this->display('frontMenber');
            exit();
        } else {
            $this->error('数据错误2!');
            exit();
        }
    }

    public function adminAgents()
    {
        // =====================================后台服务中心管理
        $this->_Admin_checkUser();
        $fck = M('fck');
        $map['type'] = array(
            'eq',
            1
        );
        // $map['uid'] =$_SESSION[C('USER_AUTH_KEY')];
        
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = M('promo')->where($map)->count(); // 总页数
        
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show);
        $p = $this->_get('p', true, '1');
        $list = M('promo')->where($map)
            ->field($field)
            ->order('id desc')
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->select();
        $this->assign('list', $list); // 数据输出到模板
        
        $Aname = explode("|", C('Agent_Us_Name'));
        $this->assign('Aname', $Aname);
        
        $this->display('adminAgents');
        return;
    }

    public function adminAgentsShow()
    {
        // 查看详细信息
        if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGua') {
            $fck = M('fck');
            $ID = (int) $_GET['Sid'];
            $where = array();
            $where['id'] = $ID;
            $srs = $fck->where($where)
                ->field('user_id,verify')
                ->find();
            $this->assign('srs', $srs);
            unset($fck, $where, $srs);
            $this->display('adminAgentsShow');
            return;
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function adminAgentsAC()
    { // 审核服务中心(服务中心)申请
        $this->_Admin_checkUser();
        // 处理提交按钮
        $action = $this->_get('action');
        // 获取复选框的值
        $XGid = $this->_get('id');
        
        if (! isset($XGid) || empty($XGid)) {
            $bUrl = __URL__ . '/adminAgents';
            $this->_box(0, '请选择会员！', $bUrl, 1);
            exit();
        }
        switch ($action) {
            case 'confirm':
                $this->_adminAgentsConfirm($XGid);
                break;
            case 'deny':
                $this->_adminAgentsDel($XGid);
                break;
            default:
                $bUrl = __URL__ . '/adminAgents';
                $this->_box(0, '没有该会员！', $bUrl, 1);
                break;
        }
    }

    private function _adminAgentsConfirm($XGid = 0)
    {
        // ==========================================确认申请服务中心
        $where['id'] = $XGid;
        $where['is_pay'] = 0;
        $where['type'] = 1;
        $rss = M('promo')->where($where)
            ->field('*')
            ->find();
        
        if ($rss) {
            $history = M('history');
            
            $data['user_id'] = $rss['user_id'];
            $data['uid'] = $rss['uid'];
            $data['action_type'] = '申请成为服务中心';
            $data['pdt'] = time();
            $data['epoints'] = $rss['money'];
            $data['bz'] = '申请成为服务中心';
            $data['did'] = 0;
            $data['allp'] = 0;
            $history->add($data);
            
            $rs = M('fck')->where(array(
                'id' => $rss['uid']
            ))->save(array(
                'is_agent' => 2,
                'shoplx' => $rss['up_level']
            ));
            M('promo')->where($where)->save(array(
                'is_pay' => 1,
                'pdt' => time()
            ));
            if ($rs) {
                unset($fck, $where, $rs, $history, $data, $rewhere);
                $this->ajaxSuccess('确认成功！');
            } else {
                $this->ajaxError('确认失败！');
            }
        } else {
            $this->ajaxError('确认失败！');
        }
    }

    public function adminAgentsCoirmAC()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGua') {
            // $this->_checkUser();
            $fck = M('fck');
            $content = $_POST['content'];
            $userid = trim($_POST['userid']);
            $where['user_id'] = $userid;
            // $rs=$fck->where($where)->find();
            $fck_rs = $fck->where($where)
                ->field('id,is_agent,is_pay,user_id,user_name,agent_max,is_agent')
                ->find();
            if ($fck_rs) {
                if ($fck_rs['is_pay'] == 0) {
                    $this->error('临时代理商不能授权服务中心!');
                    exit();
                }
                if ($fck_rs['is_agent'] == 1) {
                    $this->error('上次申请还没通过审核!');
                    exit();
                }
                if ($fck_rs['is_agent'] == 2) {
                    $this->error('该代理商已是服务中心!');
                    exit();
                }
                if (empty($content)) {
                    $this->error('请输入备注!');
                    exit();
                }
                
                if ($fck_rs['is_agent'] == 0) {
                    $nowdate = time();
                    $result = $fck->query("update __TABLE__ set verify='" . $content . "',is_agent=2,idt=$nowdate,adt={$nowdate} where id=" . $fck_rs['id']);
                }
                
                $bUrl = __URL__ . '/adminAgents';
                $this->_box(1, '授权成功！', $bUrl, 2);
            } else {
                $this->error('会员不存在！');
                exit();
            }
        } else {
            $this->error('错误！');
            exit();
        }
    }

    private function _adminAgentsDel($XGid = 0)
    {
        $where['is_pay'] = array(
            'eq',
            0
        );
        $where['type'] = array(
            'eq',
            1
        );
        $where['id'] = $XGid;
        $rs = M('promo')->where($where)->select();
        if ($rs) {
            M('promo')->where($where)->save(array(
                'is_pay' => 2,
                'pdt' => time()
            ));
            $this->ajaxSuccess('删除成功！');
        } else {
            $this->ajaxError('系统繁忙！');
        }
    }
    // 服务中心表
    public function financeDaoChu_BD()
    {
        $this->_Admin_checkUser();
        // 导出excel
        set_time_limit(0);
        
        header("Content-Type:   application/vnd.ms-excel");
        header("Content-Disposition:   attachment;   filename=Member-Agent.xls");
        header("Pragma:   no-cache");
        header("Content-Type:text/html; charset=utf-8");
        header("Expires:   0");
        
        $fck = M('fck'); // 奖金表
        
        $map = array();
        $map['id'] = array(
            'gt',
            0
        );
        $map['is_agent'] = array(
            'gt',
            0
        );
        $field = '*';
        $list = $fck->where($map)
            ->field($field)
            ->order('idt asc,adt asc')
            ->select();
        
        $title = "服务中心表 导出时间:" . date("Y-m-d   H:i:s");
        
        echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
        // 输出标题
        echo '<tr   bgcolor="#cccccc"><td   colspan="9"   align="center">' . $title . '</td></tr>';
        // 输出字段名
        echo '<tr  align=center>';
        echo "<td>序号</td>";
        echo "<td>用户账号</td>";
        echo "<td>姓名</td>";
        echo "<td>联系电话</td>";
        echo "<td>申请时间</td>";
        echo "<td>确认时间</td>";
        echo "<td>类型</td>";
        echo "<td>服务中心区域</td>";
        echo "<td>剩余购物券</td>";
        echo '</tr>';
        // 输出内容
        
        // dump($list);exit;
        
        $i = 0;
        foreach ($list as $row) {
            $i ++;
            $num = strlen($i);
            if ($num == 1) {
                $num = '000' . $i;
            } elseif ($num == 2) {
                $num = '00' . $i;
            } elseif ($num == 3) {
                $num = '0' . $i;
            } else {
                $num = $i;
            }
            if ($row['shoplx'] == 1) {
                $nnn = '服务中心';
            } elseif ($row['shoplx'] == 2) {
                $nnn = '县/区代理商';
            } else {
                $nnn = '市级代理商';
            }
            
            echo '<tr align=center>';
            echo '<td>' . chr(28) . $num . '</td>';
            echo "<td>" . $row['user_id'] . "</td>";
            echo "<td>" . $row['user_name'] . "</td>";
            echo "<td>" . $row['user_tel'] . "</td>";
            echo "<td>" . date("Y-m-d H:i:s", $row['idt']) . "</td>";
            echo "<td>" . date("Y-m-d H:i:s", $row['adt']) . "</td>";
            echo "<td>" . $nnn . "</td>";
            echo "<td>" . $row['shop_a'] . " / " . $row['shop_b'] . "</td>";
            echo "<td>" . $row['agent_cash'] . "</td>";
            echo '</tr>';
        }
        echo '</table>';
    }
}
?>
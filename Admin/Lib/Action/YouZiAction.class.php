<?php

class YouZiAction extends CommonAction
{

    function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        // $this->_inject_check(1);//调用过滤函数
        // $this->_inject_check(0); // 调用过滤函数
        $this->_checkUser();
        $this->_Admin_checkUser(); // 后台权限检测
        $this->_Config_name(); // 调用参数
        header("Content-Type:text/html; charset=utf-8");
    }

    // ================================================二级验证
    public function cody()
    {
        $UrlID = (int) $_GET['c_id'];
        if (empty($UrlID)) {
            $this->error('二级密码错误1!');
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

    // ====================================二级验证后调转页面
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
                $_SESSION['UrlPTPass'] = 'MyssShenShuiPuTao';
                $bUrl = __URL__ . '/auditMenber'; // 审核会员
                $this->_boxx($bUrl);
                break;
            case 2:
                $_SESSION['UrlPTPass'] = 'MyssGuanShuiPuTao';
                $bUrl = __URL__ . '/adminMenber'; // 会员管理
                $this->_boxx($bUrl);
                break;
            case 3:
                $_SESSION['UrlPTPass'] = 'MyssPingGuoCP';
                $bUrl = __URL__ . '/setParameter'; // 参数设置
                $this->_boxx($bUrl);
                break;
            case 4:
                $_SESSION['UrlPTPass'] = 'MyssPingGuo';
                $bUrl = __URL__ . '/adminParameter'; // 比例设置
                $this->_boxx($bUrl);
                break;
            case 5:
                $_SESSION['UrlPTPass'] = 'MyssMiHouTao';
                $bUrl = __URL__ . '/adminFinance'; // 拨出比例
                $this->_boxx($bUrl);
                break;
            case 6:
                $_SESSION['UrlPTPass'] = 'MyssGuanPaoYingTao';
                $bUrl = __URL__ . '/adminCurrency'; // 提现管理
                $this->_boxx($bUrl);
                break;
            case 7:
                $_SESSION['UrlPTPass'] = 'MyssHaMiGua';
                $bUrl = __APP__ . '/Backup/'; // 数据库管理
                $this->_boxx($bUrl);
                break;
            case 8:
                $_SESSION['UrlPTPass'] = 'MyssPiPa';
                $bUrl = __URL__ . '/adminFinanceTable'; // 奖金查询
                $this->_boxx($bUrl);
                break;
            case 9:
                $_SESSION['UrlPTPass'] = 'MyssQingKong';
                $bUrl = __URL__ . '/delTable'; // 清空数据
                $this->_boxx($bUrl);
                break;
            case 10:
                $_SESSION['UrlPTPass'] = 'MyssGuanXiGua';
                $bUrl = __URL__ . '/adminAgents'; // 代理商管理
                $this->_boxx($bUrl);
                break;
            case 11:
                $_SESSION['UrlPTPass'] = 'MyssBaiGuoJS';
                $bUrl = __URL__ . '/adminClearing'; // 奖金结算
                $this->_boxx($bUrl);
                break;
            case 12:
                $_SESSION['UrlPTPass'] = 'MyssGuanMangGuo';
                $bUrl = __URL__ . '/adminCurrencyRecharge'; // 充值管理
                $this->_boxx($bUrl);
                break;
            case 13:
                $_SESSION['UrlPTPass'] = 'MyssGuansingle';
                $bUrl = __URL__ . '/adminsingle'; // 加单管理
                $this->_boxx($bUrl);
                break;
            case 17:
                $_SESSION['UrlPTPass'] = 'MyssGuancash';
                $bUrl = __URL__ . '/adminCash'; // 加单管理
                $this->_boxx($bUrl);
                break;
            case 18:
                $_SESSION['UrlPTPass'] = 'MyssMoneyFlows';
                $bUrl = __URL__ . '/adminmoneyflows'; // 财务流向管理
                $this->_boxx($bUrl);
                break;
            case 19:
                $_SESSION['UrlPTPass'] = 'MyssadminMenberJL';
                $bUrl = __URL__ . '/adminMenberJL';
                $this->_boxx($bUrl);
                break;
            case 21:
                $_SESSION['UrlPTPass'] = 'MyssGuanXiGuaUp';
                $bUrl = __URL__ . '/adminUserUp'; // 升级管理
                $this->_boxx($bUrl);
                break;
            case 22:
                $_SESSION['UrlPTPass'] = 'MyssPingGuoCPB';
                $bUrl = __URL__ . '/setParameter_B';
                $this->_boxx($bUrl);
                break;
            case 23:
                $_SESSION['UrlPTPass'] = 'MyssOrdersList';
                $bUrl = __URL__ . '/OrdersList'; // 加单管理
                $this->_boxx($bUrl);
                break;
            case 24:
                $_SESSION['UrlPTPass'] = 'MyssWuliuList';
                $bUrl = __URL__ . '/adminLogistics'; // 物流管理
                $this->_boxx($bUrl);
                break;
            case 25:
                $_SESSION['UrlPTPass'] = 'MyssGuanXiGuaJB';
                $bUrl = __URL__ . '/adminJB'; // 金币中心管理
                $this->_boxx($bUrl);
                break;
            case 26:
                $_SESSION['UrlPTPass'] = 'MyssGuanChanPin';
                $bUrl = __URL__ . '/pro_index'; // 产品管理
                $this->_boxx($bUrl);
                break;
            case 27:
                $_SESSION['UrlPTPass'] = 'MyssGuanzy';
                $bUrl = __URL__ . '/admin_zy'; // 专营店管理
                $this->_boxx($bUrl);
                break;
            case 28:
                $_SESSION['UrlPTPass'] = 'MyssShenqixf';
                $bUrl = __URL__ . '/adminXiaofei'; // 消费申请
                $this->_boxx($bUrl);
                break;
            case 29:
                $_SESSION['UrlPTPass'] = 'MyssJinji';
                $bUrl = __URL__ . '/adminmemberJJ'; // 晋级
                $this->_boxx($bUrl);
                break;
            case 30:
                $_SESSION['UrlPTPass'] = 'Myssadminlookfhall';
                $bUrl = __URL__ . '/adminlookfhall';
                $this->_boxx($bUrl);
                break;
            default:
                $this->error('二级密码错误!');
                break;
        }
    }

    // ============================================会员升级页面显示
    public function admin_level($GPid = 0)
    {
        // 列表过滤器，生成查询Map对象
        if ($_SESSION['UrlPTPass'] == 'MyssGuanUplevel') {
            $fck = M('fck');
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
            $map['sel_level'] = array(
                'lt',
                90
            );
            
            // 查询字段
            $field = 'id,user_id,nickname,bank_name,bank_card,user_name,user_address,user_tel,rdt,f4,cpzj,pdt,u_level,sel_level';
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
                ->order('rdt desc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            
            $HYJJ = '';
            $this->_levelConfirm($HYJJ, 1);
            
            $this->assign('list', $list); // 数据输出到模板
                                          // =================================================
            
            $this->display('admin_level');
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    // ========================================数据库管理
    public function adminManageTables()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssHaMiGua') {
            $Url = __ROOT__ . '/HaMiGua/';
            $_SESSION['shujukuguanli!12312g@#$%^@#$!@#$~!@#$'] = md5("^&%#hdgfhfg$@#$@gdfsg13123123!@#!@#");
            $this->_boxx($Url);
        }
    }

    // ============================================审核会员
    public function auditMenber($GPid = 0)
    {
        // 列表过滤器，生成查询Map对象
        $fck = M('fck');
        $UserID = $_POST['UserID'];
        $type = $_GET['type'];
        $str = '';
        if ($type == 1) {
            
            $str = '已审核';
            $map['shop_type'] = array(
                'gt',
                0
            );
            $map['auth_status'] = array(
                'eq',
                0
            );
            
            $map['auth_check_time'] = array(
                'gt',
                0
            );
        }
        if ($type == 2) {
            
            $str = '未审核';
            
            $map['_string'] = ' auth_status!=2 AND auth_status>0 AND shop_type>0  ';
        }
        if ($type == 3) {
            
            $str = '未完善';
            
            $map['_string'] = ' is_pay=0  ';
        }
        
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
        // $map['shop_type'] = array(
        // 'gt',
        // 0
        // );
        // $map['u_level'] = array(
        // 'neq',
        // 3
        // );
        $map['id'] = array(
            'gt',
            1
        );
        $map['is_boss'] = array(
            'eq',
            0
        );
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
            ->order('rdt desc,is_pay,id ')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        foreach ($list as $k => $item) {
            $list[$k] = get_user_info($list[$k], $item['id']);
        }
        
        $HYJJ = '';
        $this->_levelConfirm($HYJJ, 1);
        
        $this->assign('str', $str);
        $this->assign('type', $type);
        $this->assign('voo', $HYJJ); // 会员级别
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        $this->display('auditMenber');
    }

    public function auditMenberData()
    {
        // 查看会员详细信息
        $fck = M('fck');
        $ID = (int) $_GET['PT_id'];
        // 判断获取数据的真实性 是否为数字 长度
        if (strlen($ID) > 11) {
            $this->error('数据错误!');
            exit();
        }
        $where = array();
        $where['id'] = $ID;
        $field = '*';
        $vo = $fck->where($where)
            ->field($field)
            ->find();
        if ($vo) {
            $user = $vo;
            $vo = get_user_info($user, $vo['id']);
            $this->assign('user', $vo);
            $this->display();
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function auditMenberData2()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao') {
            // 查看会员详细信息
            $fck = M('fck');
            $ID = (int) $_GET['PT_id'];
            // 判断获取数据的真实性 是否为数字 长度
            if (strlen($ID) > 11) {
                $this->error('数据错误!');
                exit();
            }
            $where = array();
            $where['id'] = $ID;
            $field = '*';
            $vo = $fck->where($where)
                ->field($field)
                ->find();
            if ($vo) {
                $this->assign('vo', $vo);
                $this->display();
            } else {
                $this->error('数据错误!');
                exit();
            }
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function auditMenberData2AC()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao') {
            
            $fck = M('fck');
            $data = array();
            
            $where['id'] = (int) $_POST['id'];
            $rs = $fck->where('is_pay = 0')->find($where['id']);
            if (! $rs) {
                $this->error('非法操作!');
                exit();
            }
            
            $data['nickname'] = $_POST['NickName'];
            $rs = $fck->where($data)->find();
            if ($rs) {
                if ($rs['id'] != $where['id']) {
                    $this->error('该会员名已经存在!');
                    exit();
                }
            }
            
            $data['bank_name'] = $_POST['BankName'];
            $data['bank_card'] = $_POST['BankCard'];
            $data['user_name'] = $_POST['UserName'];
            $data['bank_province'] = $_POST['BankProvince'];
            $data['bank_city'] = $_POST['BankCity'];
            $data['user_code'] = $_POST['UserCode'];
            $data['bank_address'] = $_POST['BankAddress'];
            $data['user_address'] = $_POST['UserAddress'];
            $data['user_post'] = $_POST['UserPost'];
            $data['user_tel'] = $_POST['UserTel'];
            $data['bank_province'] = $_POST['BankProvince'];
            $data['is_lock'] = $_POST['isLock'];
            
            $fck->where($where)
                ->data($data)
                ->save();
            $bUrl = __URL__ . '/auditMenberData2/PT_id/' . $where['id'];
            $this->_box(1, '修改会员信息！', $bUrl, 1);
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function auditMenberAC()
    {
        // 处理提交按钮
        $action = $this->_get('action');
        // 获取复选框的值
        $id = $this->_get('id');
        $name = $this->_get('name');
        if (! isset($id) || empty($id)) {
            $this->error('没有该会员');
            exit();
        }
        switch ($action) {
            case 'confirm':
                $this->_auditMenberOpenUser($id);
                break;
            case 'del':
                $this->_auditMenberDelUser($id);
                break;
            default:
                $this->error('没有该会员');
                break;
        }
    }

    // 审核会员升级-通过
    private function _AdminLevelAllow($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanUplevel') {
            $fck = M('fck');
            $where = array();
            $where['id'] = array(
                'in',
                $PTid
            );
            $where['sel_level'] = array(
                'lt',
                90
            );
            $vo = $fck->where($where)
                ->field('id,sel_level')
                ->select();
            foreach ($vo as $voo) {
                $where = array();
                $data = array();
                $where['id'] = $voo['id'];
                $data['u_level'] = $voo['sel_level'];
                $data['sel_level'] = 98;
                $fck->where($where)
                    ->data($data)
                    ->save();
            }
            
            $bUrl = __URL__ . '/admin_level';
            $this->_box(1, '会员升级通过！', $bUrl, 1);
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    // 审核会员升级-拒绝
    private function _AdminLevelNo($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanUplevel') {
            $fck = M('fck');
            $where = array();
            $where['id'] = array(
                'in',
                $PTid
            );
            $where['sel_level'] = array(
                'lt',
                90
            );
            $vo = $fck->where($where)
                ->field('id')
                ->select();
            foreach ($vo as $voo) {
                $where = array();
                $data = array();
                $where['id'] = $voo['id'];
                $data['sel_level'] = 97;
                $fck->where($where)
                    ->data($data)
                    ->save();
            }
            
            $bUrl = __URL__ . '/admin_level';
            $this->_box(1, '拒绝会员升级！', $bUrl, 1);
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    // ===============================================设为空单
    private function _auditMenberOpenNull($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao') {
            $fck = D('Fck');
            $where = array();
            if (! $fck->autoCheckToken($_POST)) {
                $this->error('页面过期，请刷新页面！');
                exit();
            }
            $ID = $_SESSION[C('USER_AUTH_KEY')];
            $where['id'] = array(
                'in',
                $PTid
            );
            $where['is_pay'] = 0;
            $field = "id,u_level,re_id,cpzj,re_path,user_id,p_path,p_level,shop_id,f4";
            $vo = $fck->where($where)
                ->order('rdt asc')
                ->field($field)
                ->select();
            $nowdate = strtotime(date('c'));
            $nowday = strtotime(date('Y-m-d'));
            $nowmonth = date('m');
            
            foreach ($vo as $voo) {
                $ppath = $voo['p_path'];
                // 上级未开通不能开通下级员工
                $frs_where['is_pay'] = array(
                    'eq',
                    0
                );
                $frs_where['id'] = $voo['father_id'];
                $frs = $fck->where($frs_where)->find();
                if ($frs) {
                    $this->error('开通失败，上级未开通');
                    exit();
                }
                
                $nnrs = $fck->where('is_pay>0')
                    ->field('n_pai')
                    ->order('n_pai desc')
                    ->find();
                $mynpai = ((int) $nnrs['n_pai']) + 1;
                
                $data = array();
                $data['is_pay'] = 2;
                $data['pdt'] = $nowdate;
                $data['open'] = 1;
                $data['get_date'] = $nowday;
                $data['fanli_time'] = $nowday;
                $data['n_pai'] = $mynpai;
                
                // $data['n_pai'] = $max_p;
                // $data['x_pai'] = $myppp;
                // 开通会员
                $result = $fck->where('id=' . $voo['id'])->save($data);
                unset($data, $varray);
            }
            unset($fck, $where, $field, $vo, $nowday);
            $bUrl = __URL__ . '/auditMenber';
            $this->_box(1, '设为空单！', $bUrl, 1);
            exit();
        } else {
            $this->error('错误！');
            exit();
        }
    }

    // ===============================================开通会员
    private function _auditMenberOpenUser($PTid = 0)
    {
        $fck = D('Fck');
        $fee = M('fee');
        $gouwu = M('gouwu');
        $shouru = M('shouru');
        $fenhong = M('fenhong');
        
        $fee_rs = $fee->field('s1,s9')->find();
        $s1 = explode("|", $fee_rs['s1']);
        $where = array();
        $where['id'] = array(
            'in',
            $PTid
        );
        $where['is_pay'] = 0;
        $field = "*";
        $voo = $fck->where($where)
            ->field($field)
            ->order('id asc')
            ->find();
        // var_dump($voo);
        
        $nowdate = strtotime(date('c'));
        $nowday = strtotime(date('Y-m-d'));
        $nowmonth = date('m');
        $fck->emptyTime();
        
        if ($voo) {
            $ppath = $voo['p_path'];
            // 上级未开通不能开通下级员工
            $frs_where['is_pay'] = array(
                'eq',
                0
            );
            $frs_where['id'] = $voo['father_id'];
            $frs = $fck->where($frs_where)->find();
            if ($frs) {
                $this->error('开通失败，上级未开通');
                exit();
            }
            // 给分享人添加分享人数或单数
            $fck->query("update __TABLE__ set `re_nums`=re_nums+1,re_f4=re_f4+" . $voo['f4'] . " where `id`=" . $voo['re_id']);
            
            $nnrs = $fck->where('is_pay>0')
                ->field('n_pai')
                ->order('n_pai desc')
                ->find();
            $mynpai = ((int) $nnrs['n_pai']) + 1;
            
            $in_gp = $s1[$voo['u_level'] - 1] * $voo['cpzj'] / 100;
            $data = array();
            $data['is_pay'] = 1;
            $data['pdt'] = $nowdate;
            $data['open'] = 1;
            $data['fanli_time'] = $nowday - 1; // 当天没有分红奖
            $data['agent_gp'] = $in_gp; //
            $data['n_pai'] = $mynpai;
            
            // 开通会员
            $result = $fck->where('id=' . $voo['id'])->save($data);
            unset($data, $varray);
            
            $data = array();
            $data['uid'] = $voo['id'];
            $data['user_id'] = $voo['user_id'];
            $data['in_money'] = $voo['cpzj'];
            $data['in_time'] = time();
            $data['in_bz'] = "新会员加入";
            $shouru->add($data);
            unset($data);
            
            $data = array();
            $data['uid'] = $voo['id'];
            $data['user_id'] = $voo['user_id'];
            $data['f_money'] = $in_gp;
            $data['pdt'] = time();
            $fenhong->add($data);
            unset($fenhong, $data);
            // 统计单数
            $fck->xiangJiao($voo['id'], $voo['f4']);
            
            if ($voo['register_type'] == 1) {
                
                // 算出奖金
                $fck->getusjj($voo['id'], 1, $voo['cpzj']);
                // 算出注册积分奖励
                
                $s9 = explode('|', $fee_rs['s9']);
                
                $fck->add_register_award($voo, '注册赠送', $s9[$voo['u_level'] - 1]);
            }
            
            $fck->getduipeng($voo['id'], 1, $voo['cpzj']);
            $fck->add_peisong($voo['id'], $in_gp);
            
            $where = array();
            $where = 'id in (0' . $voo['re_path'] . '0)';
            $list = $fck->where($where)->select();
            // 增加推荐人的盟友数据
            foreach ($list as $k => $item) {
                set_user_count($item);
            }
            
            $this->success('开通会员成功');
        } else {
            $this->error('开通会员失败');
        }
        unset($fck, $field, $where, $voo);
    }

    private function _auditMenberDelUser($PTid = 0)
    {
        // 删除会员
        $fck = M('fck');
        $rs = $fck->find($PTid);
        if ($rs) {
            $whe['father_name'] = $rs['user_id'];
            $rss = $fck->where($whe)->find();
            if ($rss) {
                $bUrl = __URL__ . '/auditMenber';
                $this->error('该 ' . $rs['user_id'] . ' 会员有下级会员，不能删除！');
                exit();
            } else {
                $where['id'] = $PTid;
                $a = $fck->where($where)->delete();
                // $bUrl = __URL__.'/auditMenber';
                // $this->_box(1,'删除会员！',$bUrl,1);
                $this->success('删除会员!');
            }
        }
    }

    public function adminMenber($GPid = 0)
    {
        // 列表过滤器，生成查询Map对象
        $fck = M('fck');
        $user_id = $this->_get('username');
        $re_id = $this->_get('tjrname');
        $this->assign('re_id', $re_id);
        $s_time = $this->_get('time1', true, 0);
        $e_time = $this->_get('time2');
        $type = $_GET['type'];
        $this->assign('type', $type);
        $e_time = $e_time ? $e_time : date("Y-m-d H:i:s", time());
        if (! empty($user_id)) {
            $where['user_name'] = array(
                'like',
                "%" . $user_id . "%"
            );
            $where['user_id'] = array(
                'like',
                "%" . $user_id . "%"
            );
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            
            $map['_string'] = '   user_name  like "%' . $user_id . '%" OR  user_id  like "%' . $user_id . '%"';
        }
        if (! empty($re_id)) {
            $map['re_name'] = array(
                'eq',
                $re_id
            );
        }
        if (! empty($e_time)) {
            $map['pdt'] = array(
                array(
                    'egt',
                    strtotime($s_time)
                ),
                array(
                    'elt',
                    strtotime($e_time)
                )
            );
        }
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        if ($type == 0) {
            
            $map['_string'] = '   ( is_pay=1 ) OR  id=' . $id;
        }
        if ($type == 1) {
            
            $map['_string'] = '   is_boss=2 ';
        }
        if ($type == 2) {
            
            $map['_string'] = ' re_id=1 and  is_boss=1';
        }
        // $map['is_pay'] = array('egt',1);
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $fck->where($map)->count(); // 总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $fck->where($map)
            ->field($field)
            ->order('pdt desc,id desc')
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->select();
        // $json_string = file_get_contents( 'http://localhost:8088/fx70999_pos/Main/mobile_info.json');
        
        // // 用参数true把JSON字符串强制转成PHP数组
        // $mobile_info = json_decode($json_string, true);
        
        foreach ($list as $key => $vo) {
            $list[$key]['user_number'] = $_SESSION['number'] . $vo['user_number'];
            // $recommend_count = M('fck')->where('re_id=' . $vo['id'])->count();
            
            // $list[$key]['recommend_count'] = $recommend_count;
            
            $user_tel = mb_substr($vo['user_tel'], 0, 7, 'utf-8');
            
            if (empty($vo['user_address'])) {
                
                $mobile_info = M('mobile')->where("phone  LIKE '%{$user_tel}%'")->find();
                IF ($mobile_info != NULL) {
                    M('fck')->where('id=' . $vo['id'])->setField('user_address', $mobile_info['province'] . '' . $mobile_info['city']);
                    $list[$key]['user_address'] = $mobile_info['province'] . '' . $mobile_info['city'];
                }
            }
            
            // $gudong = M('fck')->field('user_id')->where(" re_level=0 and id in (0".$vo['re_path']."0)")->find();
            
            if (empty($vo['wx_nickname'])) {
                
                $list[$key]['weixinlogo'] = '__PUBLIC__/Images/gbRes_2.png';
                $list[$key]['wx_nickname'] = '未绑定';
            }
            
            // $list[$key]['user_address'] = $area['province'];
            // }
            $client_ip = $vo['new_login_ip'];
            
            $list[$key]['level_str'] = getUserLevel($vo['u_level']);
            $list[$key]['reg_url'] = U('Reg/userReg', array(
                'FID' => $vo['id'],
                'RID' => $vo['id']
            ));
        }
        
        $this->assign('list', $list);
        
        $this->assign('user_id', $user_id);
        $this->assign('re_id', $re_id);
        $this->assign('s_time', $s_time);
        if ($this->_post('time2') != "") {
            $this->assign('e_time', $e_time);
        }
        $all_agent_use = M('fck')->sum('agent_use');
        $this->assign('all_agent_use', $all_agent_use);
        
        $this->display('adminMenber');
        return;
    }

    public function get_mobile_area1($mobile)
    {
        $sms = array(
            'province' => '',
            'supplier' => ''
        ); // 初始化变量
           // 根据淘宝的数据库调用返回值
        $url = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=" . $mobile . "&t=" . time();
        
        $content = file_get_contents($url);
        $content = iconv("gb2312", "utf-8//IGNORE", $content);
        $content = str_replace("__GetZoneResult_", "", $content);
        $content = str_replace("=", "", $content);
        $content = json_decode($content);
        
        return $content;
    }

    public function setUserParameter($GPid = 0)
    {
        // 列表过滤器，生成查询Map对象
        $fck = M('fck');
        $user_id = $this->_post('username');
        $re_id = $this->_post('tjrname');
        $s_time = $this->_post('time1', true, 0);
        $e_time = $this->_post('time2');
        $e_time = $e_time ? $e_time : date("Y-m-d H:i:s", time());
        if (! empty($user_id)) {
            $where['user_name'] = array(
                'like',
                "%" . $user_id . "%"
            );
            $where['user_id'] = array(
                'like',
                "%" . $user_id . "%"
            );
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            $map['user_id'] = array(
                'eq',
                $user_id
            );
        }
        if (! empty($re_id)) {
            $map['re_id'] = array(
                'eq',
                $re_id
            );
        }
        $map['pdt'] = array(
            array(
                'egt',
                strtotime($s_time)
            ),
            array(
                'elt',
                strtotime($e_time)
            )
        );
        // $map['is_pay'] = array('egt',1);
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $fck->where($map)->count(); // 总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $fck->where($map)
            ->field($field)
            ->order('pdt desc,id desc')
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->select();
        $this->assign('list', $list);
        
        $this->assign('user_id', $user_id);
        $this->assign('re_id', $re_id);
        $this->assign('s_time', $s_time);
        if ($this->_post('time2') != "") {
            $this->assign('e_time', $e_time);
        }
        $this->display('setUserParameter');
        return;
    }

    public function adminlookfh()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            
            $uid = (int) $_GET['uid'];
            if (empty($uid)) {
                $this->error('数据错误!');
                exit();
            }
            $fenhong = M('fenhong');
            $where = array();
            $where['uid'] = array(
                'eq',
                $uid
            );
            
            // 查询字段
            $field = '*';
            // =====================分页开始==============================================
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $fenhong->where($where)->count(); // 总页数
            $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
            $page_where = ''; // 分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $list = $fenhong->where($where)
                ->field($field)
                ->order('f_num asc,id asc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            $this->assign('list', $list); // 数据输出到模板
                                          // =================================================
            $this->display();
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function adminlookfhall()
    {
        if ($_SESSION['UrlPTPass'] == 'Myssadminlookfhall') {
            
            $fenhong = M('fenhong');
            $where = array();
            // 查询字段
            $field = '*';
            // =====================分页开始==============================================
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $fenhong->where($where)->count(); // 总页数
            $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
            $page_where = ''; // 分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $list = $fenhong->where($where)
                ->field($field)
                ->order('f_num asc,id asc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            $this->assign('list', $list); // 数据输出到模板
                                          // =================================================
            $this->display();
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function premAdd()
    {
        $id = (int) $_GET['id'];
        $table = M('fck');
        $rs = $table->field('id,is_boss,prem')->find($id);
        if ($rs) {
            $ars = array();
            $arr = explode(',', $rs['prem']);
            for ($i = 1; $i <= 30; $i ++) {
                if (in_array($i, $arr)) {
                    $ars[$i] = "checked";
                } else {
                    $ars[$i] = "";
                }
            }
            $this->assign('ars', $ars);
            $this->assign('rs', $rs);
            $title = '修改权限';
        } else {
            $title = '添加权限';
        }
        
        $this->assign('title', $title);
        $this->display('premAdd');
    }

    public function premAddSave()
    {
        $id = (int) $_POST['id'];
        if ($id == 1 && $_SESSION[C('USER_AUTH_KEY')] != 1) {
            $this->error('不能修改该会员的权限!');
            exit();
        }
        $table = M('fck');
        $is_boss = $_POST['is_boss'];
        $boss = $_POST['isBoss'];
        $arr = ',';
        if (is_array($is_boss)) {
            foreach ($is_boss as $vo) {
                $arr .= $vo . ',';
            }
        }
        $data = array();
        // $data['is_boss'] = $boss;
        $data['prem'] = $arr;
        $data['id'] = $id;
        $table->save($data);
        
        $this->success('权限设置成功！');
    }

    // 显示劳资详细
    public function BonusShow($GPid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $hi = M('history');
            
            $where = array();
            $where['Uid'] = $_REQUEST['PT_id'];
            $where['type'] = 19;
            
            $list = $hi->where($where)->select();
            $this->assign('list', $list);
            $this->display('BonusShow');
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function adminTerminalData()
    {
        // 查看会员详细信息
        $ID = $this->_get('id');
        $userInfo = M('user_terminal')->where('id=' . $ID)
            ->field('*')
            ->find();
        
        $user = M('fck')->where(ARRAY(
            'id' => $userInfo['uid']
        ))->find();
        
        $userInfo['user_name'] = $user['user_id'];
        
        $this->assign('user', $userInfo);
        
        $this->display();
    }

    public function adminTerminalDel()
    {
        // 查看会员详细信息
        $ID = $this->_get('id');
        $userInfo = M('user_terminal')->where('id=' . $ID)
            ->field('*')
            ->find();
        if ($userInfo['seller_id'] > 0) {
            $this->error('已绑定商户,不能删除!');
        }
        M('user_terminal')->where('id=' . $ID)->delete();
        
        set_terminal_sns($userInfo['uid']);
        set_out_terminal_ids($userInfo['uid']);
        set_seller_sns($userInfo['uid']);
        
        $this->success('删除成功!');
    }

    public function adminTerminalDataSave()
    {
        $ID = $this->_post('id');
        $fck = M('user_terminal');
        $res = $fck->where('id=' . $ID)
            ->field('id')
            ->find();
        if (! $res) {
            $this->error('警告,非法提交!');
        }
        $data['id'] = $ID;
        
        $data['shop_expire_day'] = $this->_post('shop_expire_day');
        $data['expire_day'] = $this->_post('expire_day');
        $data['fan_money'] = $this->_post('fan_money');
        $data['min_fan_money'] = $this->_post('min_fan_money');
        $data['award_money'] = $this->_post('award_money');
        
        $data['user_name'] = $this->_post('user_name');
        $data['sn_type'] = $this->_post('sn_type');
        
        $user = M('fck')->where(ARRAY(
            'user_id' => $data['user_name']
        ))->find();
        
        if ($user == NULL) {
            $this->error('对不起,此用户不存在!');
        }
        $expire_day = $data['expire_day'];
        
        $fck->where('id=' . $ID)->setField('award_money', $data['award_money']);
        
        $fck->where('id=' . $ID)->setField('shop_expire_day', $data['shop_expire_day']);
        $fck->where('id=' . $ID)->setField('fan_money', $data['fan_money']);
        $fck->where('id=' . $ID)->setField('sn_type', $data['sn_type']);
        $fck->where('id=' . $ID)->setField('min_fan_money', $data['min_fan_money']);
        $fck->where('id=' . $ID)->setField('expire_day', $expire_day);
        $fck->where('id=' . $ID)->setField('uid', $user['id']);
        // $fck->where('id=' . $ID)->setField('order_uid', $user['id']);
        $fck->where('id=' . $ID)->setField('update_time', time());
        if ($expire_day > 0) {
            $expire_time = strtotime('+' . $expire_day . ' day');
            
            $expire_time_str = date('Y-m-d H:i:s', strtotime('+' . $expire_day . ' day'));
        }
        $fck->where('id=' . $ID)->setField('expire_time', $expire_time);
        $fck->where('id=' . $ID)->setField('expire_time_str', $expire_time_str);
        
        $data['sn'] = $this->_post('sn');
        $seller = M('seller')->where(ARRAY(
            'sn' => $data['sn']
        ))->find();
        
        if ($seller != NULL) {
            
            M('seller')->where('id=' . $seller['id'])->setField('user_id', $user['id']);
        }
        
        unset($data);
        $this->success('修改机器成功！');
    }

    public function adminuserData()
    {
        // 查看会员详细信息
        $ID = $this->_get('id');
        $userInfo = M('fck')->where('id=' . $ID)
            ->field('*')
            ->find();
        $this->assign('user', $userInfo);
        
        $voo = 0;
        $this->_levelConfirm($voo);
        $level = array();
        for ($i = 1; $i <= count($voo); $i ++) {
            $level[$i] = $voo[$i];
        }
        $this->assign('level', $level);
        
        $TP = $userInfo['treeplace'];
        if (empty($TP))
            $TP = 0;
        $TPL = array();
        for ($i = 0; $i < 2; $i ++) {
            $TPL[$i] = '';
        }
        $TPL[$TP] = 'selected="selected"';
        $this->assign('TPL', $TPL);
        
        // var_dump($userInfo);
        $wenti = M('fee')->where(array(
            'id' => 1
        ))->getField('str24');
        $wentilist = explode('|', $wenti);
        $this->assign('wentilist', $wentilist);
        $role_list = M('manager_role')->select();
        $this->assign('role_list', $role_list);
        
        unset($userInfo);
        $this->display();
    }

    public function adminuserDataSave()
    {
        $ID = $this->_post('id');
        $fck = D('Fck');
        $res = $fck->where('id=' . $ID)
            ->field('id,is_boss')
            ->find();
        if (! $res) {
            $this->error('警告,非法提交!');
        }
        $data['id'] = $ID;
        $data['role_id'] = $this->_post('role_id');
        
        $role = $fck->where('id=' . $ID)
            ->field('id,is_boss')
            ->find();
        
        $data['is_boss'] = 0;
        if ($data['role_id'] > 0) {
            $manager_role = M('manager_role');
            $role = $manager_role->where('id=' . $data['role_id'])->find();
            
            $data['role_type'] = $role['role_type'];
            $data['is_boss'] = 1;
        }
        $data['pwd1'] = $this->_post('pwd1');
        $data['pwd2'] = $this->_post('pwd2');
        $data['password'] = md5($this->_post('pwd1'));
        $data['passopen'] = md5($this->_post('pwd2'));
        $data['user_name'] = $this->_post('username');
        $data['user_code'] = $this->_post('idcard');
        $data['card_no'] = $this->_post('idcard');
        $data['user_tel'] = $this->_post('mobile');
        $data['u_level'] = $this->_post('newulevel');
        // $data['u_level'] = $this->_post('newu_level');
        $data['bank_name'] = $this->_post('bankname');
        $data['bank_card'] = $this->_post('bankcard');
        $data['name'] = $this->_post('huzhu');
        $data['bank_address'] = $this->_post('bankaddress');
        $data['alipay'] = $this->_post('alipay');
        $data['nickname'] = $this->_post('nickname');
        $data['user_address'] = $this->_post('user_address');
        $data['email'] = $this->_post('email');
        $data['wenti'] = $this->_post('wenti');
        $data['wenti_dan'] = $this->_post('daan');
        $data['left_yeji'] = $this->_post('left_yeji');
        $data['right_yeji'] = $this->_post('right_yeji');
        $data['is_gd'] = $this->_post('is_gd');
        
        $ReName = $this->_post('ReName');
        $oldRename = $this->_post('oldReName');
        if (! empty($ReName)) {
            if ($ReName != $oldRename) {
                $re_where = array();
                $re_where['user_id'] = $ReName;
                $re_fck_rs = $fck->where($re_where)
                    ->field('id,nickname,user_id,re_level,re_path')
                    ->find();
                if ($re_fck_rs) {
                    if ($ID == 1) {
                        $data['re_id'] = 0;
                        $data['re_name'] = 0;
                    } else {
                        $recount = $fck->where('re_path like "%,' . $ID . ',%" and id=' . $re_fck_rs['id'])->count();
                        if ($recount == 0) {
                            $data['re_id'] = $re_fck_rs['id'];
                            $data['re_name'] = $re_fck_rs['user_id'];
                            $data['re_level'] = $re_fck_rs['re_level'] + 1;
                            $data['re_path'] = $re_fck_rs['re_path'] . $re_fck_rs['id'] . ",";
                        } else {
                            $this->error('逻辑不正确，不能改当前团队下的会员为推荐人！');
                        }
                    }
                } else {
                    if ($ID != 1) {
                        $this->error('推荐人不存在，请重新输入！');
                        exit();
                    }
                }
            }
            
            $father = $this->_post('FatherName');
            $oldufather = $this->_post('oldFatherName');
            
            if ($father != $oldufather) {
                $re_where = array();
                $where = array();
                $where['user_id'] = $father;
                $fa_fck_rs = $fck->where($where)
                    ->field('id,nickname,user_id,p_level,p_path')
                    ->find();
                if ($fa_fck_rs) {
                    if ($ID == 1) {
                        $data['father_id'] = 0;
                        $data['father_name'] = 0;
                    } else {
                        
                        $TPL = $this->_post('TPL');
                        // 判断位置是否存在
                        $facount = $fck->where('father_id=' . $fa_fck_rs['id'] . ' and treeplace=' . $TPL)->count();
                        if ($facount > 0) {
                            $this->error('该位置已存在！');
                            exit();
                        }
                        
                        $recount = $fck->where('p_path like "%,' . $ID . ',%" and id=' . $fa_fck_rs['id'])->count();
                        if ($recount == 0) {
                            $data['father_id'] = $fa_fck_rs['id'];
                            $data['father_name'] = $fa_fck_rs['user_id'];
                            $data['treeplace'] = $TPL;
                            $data['p_level'] = $fa_fck_rs['p_level'] + 1;
                            $data['p_path'] = $fa_fck_rs['p_path'] . $fa_fck_rs['id'] . ",";
                        } else {
                            $this->error('逻辑不正确，不能改当前团队下的会员为推荐人！');
                        }
                    }
                } else {
                    if ($ID != 1) {
                        $this->error('接点人不存在，请重新输入！');
                        exit();
                    }
                }
            }
        }
        
        $smap = ' re_level=1 and id in (0' . $data['re_path'] . '0)';
        
        $shop_rs = $fck->where($smap)
            ->field('id,user_id')
            ->find();
        if ($shop_rs != null) {
            $data['shop_id'] = $shop_rs['id']; // 隶属会员中心编号
            $data['shop_name'] = $shop_rs['user_id']; // 隶属会员中心帐号
        } else {
            $data['shop_id'] = $data['id']; // 隶属会员中心编号
            $data['shop_name'] = $data['user_id']; // 隶属会员中心帐号
        }
        
        if ($fck->create($data)) {
            $fck->save();
            
            if ($ReName != $oldRename) {
                $ts_fck = $fck->where('re_path like "%,' . $ID . ',%"')
                    ->order('re_level asc')
                    ->select();
                foreach ($ts_fck as $tsvo) {
                    $tdate = array();
                    $tres_fck = $fck->where('id=' . $tsvo['re_id'])->find();
                    $mrelevel = $tres_fck['re_level'] + 1;
                    $mrepath = $tres_fck['re_path'] . $tsvo['re_id'] . ",";
                    
                    $condition['id'] = $tsvo['id'];
                    $tdate['re_level'] = $mrelevel;
                    $tdate['re_path'] = $mrepath;
                    $result1 = $fck->where($condition)
                        ->data($tdate)
                        ->save();
                }
            }
            
            if ($father != $oldufather) {
                $ts_fck = $fck->where('p_path like "%,' . $ID . ',%"')
                    ->order('p_level asc')
                    ->select();
                foreach ($ts_fck as $tsvo) {
                    $tdate = array();
                    $tres_fck = $fck->where('id=' . $tsvo['father_id'])->find();
                    $mrelevel = $tres_fck['p_level'] + 1;
                    $mrepath = $tres_fck['p_path'] . $tsvo['father_id'] . ",";
                    
                    $condition['id'] = $tsvo['id'];
                    $tdate['p_level'] = $mrelevel;
                    $tdate['p_path'] = $mrepath;
                    $result1 = $fck->where($condition)
                        ->data($tdate)
                        ->save();
                }
            }
            if ($data['is_gd'] == 1) {
                $oldulevel = I('oldulevel');
                $newulevel = I('newulevel');
                $add_time = time();
                $tdate['uid'] = $ID;
                $tdate['old_level'] = $oldulevel;
                $tdate['new_level'] = $newulevel;
                $tdate['add_time'] = $add_time;
                $result1 = M('fck_level')->add($tdate);
            }
            unset($data);
            $this->success('修改资料成功！');
        } else {
            $this->error($fck->getError());
        }
    }

    public function adminuserJLData()
    {
        // 查看会员详细信息
        $ID = $this->_get('id');
        $userInfo = M('fck')->where('id=' . $ID)
            ->field('*')
            ->find();
        $this->assign('user', $userInfo);
        
        $voo = 0;
        $this->_levelConfirm($voo);
        $level = array();
        for ($i = 1; $i <= count($voo); $i ++) {
            $level[$i] = $voo[$i];
        }
        $this->assign('level', $level);
        
        $TP = $userInfo['treeplace'];
        if (empty($TP))
            $TP = 0;
        $TPL = array();
        for ($i = 0; $i < 2; $i ++) {
            $TPL[$i] = '';
        }
        $TPL[$TP] = 'selected="selected"';
        $this->assign('TPL', $TPL);
        
        // var_dump($userInfo);
        $wenti = M('fee')->where(array(
            'id' => 1
        ))->getField('str24');
        $wentilist = explode('|', $wenti);
        $this->assign('wentilist', $wentilist);
        unset($userInfo);
        $this->display();
    }

    public function adminuserJLDataSave()
    {
        $ID = $this->_post('id');
        $fck = D('Fck');
        $res = $fck->where('id=' . $ID)
            ->field('id')
            ->find();
        if (! $res) {
            $this->error('警告,非法提交!');
        }
        $data['id'] = $ID;
        $data['user_name'] = $this->_post('username');
        $data['agent_gp'] = $this->_post('agentgp');
        
        if ($fck->create($data)) {
            $fck->save();
            
            unset($data);
            $this->success('修改成功！', U('YouZi/adminMenberJL'));
        } else {
            $this->error($fck->getError());
        }
    }

    public function slevel()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao' || $_SESSION['UrlPTPass'] == 'MyssGuanXiGua' || $_SESSION['UrlPTPass'] == 'MyssGuansingle') {
            // 查看会员详细信息
            $fck = M('fck');
            $ID = (int) $_GET['PT_id'];
            // 判断获取数据的真实性 是否为数字 长度
            if (strlen($ID) > 15) {
                $this->error('数据错误!');
                exit();
            }
            $where = array();
            // 查询条件
            // $where['ReID'] = $_SESSION[C('USER_AUTH_KEY')];
            $where['id'] = $ID;
            $field = '*';
            $vo = $fck->where($where)
                ->field($field)
                ->find();
            if ($vo) {
                $this->assign('vo', $vo);
                $this->display();
            } else {
                $this->error('数据错误!');
                exit();
            }
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function slevelsave()
    { // 升级保存数据
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao' || $_SESSION['UrlPTPass'] == 'MyssGuanXiGua' || $_SESSION['UrlPTPass'] == 'MyssGuansingle') {
            // 查看会员详细信息
            $fck = D('Fck');
            $fee = M('fee');
            $ID = (int) $_POST['ID'];
            $slevel = (int) $_POST['slevel']; // 升级等级
                                              
            // 判断获取数据的真实性 是否为数字 长度
            if (strlen($ID) > 15 or $ID <= 0) {
                $this->error('数据错误!');
                exit();
            }
            
            $fee_rs = $fee->find(1);
            if ($slevel <= 0 or $slevel >= 7) {
                $this->error('升级等级错误！');
                exit();
            }
            
            $where = array();
            // 查询条件
            // $where['ReID'] = $_SESSION[C('USER_AUTH_KEY')];
            $where['id'] = $ID;
            $field = '*';
            $vo = $fck->where($where)
                ->field($field)
                ->find();
            if ($vo) {
                switch ($slevel) { // 通过注册等级从数据库中找出注册金额及认购单数
                    case 1:
                        $cpzj = $fee_rs['uf1']; // 注册金额
                        $F4 = $fee_rs['jf1']; // 自身认购单数
                        break;
                    case 2:
                        $cpzj = $fee_rs['uf2'];
                        $F4 = $fee_rs['jf2'];
                        break;
                    case 3:
                        $cpzj = $fee_rs['uf3'];
                        $F4 = $fee_rs['jf3'];
                        break;
                    case 4:
                        $cpzj = $fee_rs['uf4'];
                        $F4 = $fee_rs['jf4'];
                        break;
                    case 5:
                        $cpzj = $fee_rs['uf5'];
                        $F4 = $fee_rs['jf5'];
                        break;
                    case 6:
                        $cpzj = $fee_rs['uf6'];
                        $F4 = $fee_rs['jf6'];
                        break;
                }
                
                $number = $F4 - $vo['f4']; // 升级所需单数差
                $data = array();
                $data['u_level'] = $slevel; // 升级等级
                $data['cpzj'] = $cpzj; // 注册金额
                $data['f4'] = $F4; // 自身认购单数
                $fck->where($where)
                    ->data($data)
                    ->save();
                
                $fck->xiangJiao_lr($ID, $number); // 住上统计单数
                
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(1, '会员升级！', $bUrl, 1);
                exit();
            } else {
                $this->error('数据错误!');
                exit();
            }
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function adminMenberAC()
    {
        // 处理提交按钮
        $action = $this->_get('action');
        // 获取复选框的值
        $PTid = $this->_get('id');
        if (! isset($PTid) || empty($PTid)) {
            echo $action = $_POST['action'];
            echo $PTid = $_POST['id'];
        }
        switch ($action) {
            case 'open':
                $this->_adminMenberOpen($PTid);
                break;
            case 'lock':
                $this->_adminMenberLock($PTid);
                break;
            case 'hb':
                $this->_adminMenberHb($PTid);
                break;
            case 'gpps':
                $this->_admingppeisong($PTid);
                break;
            case '配股':
                $this->admingppeisong($PTid);
                break;
            case '积分提现':
                $this->adminMenberCurrency($PTid);
                break;
            case '开启奖金':
                $this->adminMenberFenhong($PTid);
                break;
            case 'del':
                $this->adminMenberDel($PTid);
                break;
            case '关闭奖金':
                $this->_Lockfenh($PTid);
                break;
            case '开启期限':
                $this->_OpenQd($PTid);
                break;
            case '关闭期限':
                $this->_LockQd($PTid);
                break;
            case '开启分红奖':
                $this->_OpenFh($PTid);
                break;
            case '关闭分红奖':
                $this->_LockFh($PTid);
                break;
            case '奖金转购物券':
                $this->adminMenberZhuan($PTid);
                break;
            case '设为服务中心':
                $this->_adminMenberAgent($PTid);
                break;
            case '设为代理商':
                $this->_adminMenberJB($PTid);
            case '取消代理商':
                $this->adminMenberJBcancel($PTid);
                break;
            case '设为物流管理':
                $this->_adminMenberWL($PTid);
            case '设为财务管理':
                $this->_adminMenberCw($PTid);
            case '取消管理员':
                $this->adminMenberWLcancel($PTid);
                break;
            default:
                // $bUrl = __URL__.'/adminMenber';
                // $this->_box(0,'没有该会员！',$bUrl,1);
                $this->Success('没有该会员！');
                break;
        }
    }

    public function _admingppeisong($PTid = 0)
    {
        $peisong = M('peisong');
        $cur_one_price = M('fee')->where(array(
            'id' => 1
        ))->getField('gp_one');
        $where['id'] = array(
            'in',
            $PTid
        );
        $rs = $peisong->where($where)
            ->field('id,agent_gp,uid')
            ->order('id asc')
            ->select();
        $psflag = 0;
        $fck = M('fck');
        foreach ($rs as $vo) {
            $id = $vo['id'];
            $myid = $vo['uid'];
            $mygp = $vo['agent_gp'];
            $cz_gp_n = (int) ($this->numb_duibi($mygp, $cur_one_price));
            $psmoney = $cz_gp_n * $cur_one_price;
            
            $gsgp = $fck->where(array(
                'id' => 1
            ))->getField('live_gupiao');
            
            if ($gsgp >= $cz_gp_n) {
                $fck->execute('update __TABLE__ set agent_gp=agent_gp-' . $psmoney . ',live_gupiao=live_gupiao+' . $cz_gp_n . ' where is_pay>0 and id=' . $myid . '');
                $fck->execute('update __TABLE__ set live_gupiao=live_gupiao-' . $cz_gp_n . ' where is_pay>0 and id=1');
                $peisong->execute('update __TABLE__ set agent_gp=agent_gp-' . $psmoney . ',gp_one=' . $cur_one_price . '  where   id=' . $id . '');
                $psflag = 1;
            }
        }
        // exit;
        if ($psflag == 1) {
            $bUrl = __URL__ . '/adminMenberJL';
            $this->success('配送成功！', $bUrl);
        } else {
            $this->error('配送失败');
        }
        
        unset($fck, $where, $rs, $myid, $result);
    }

    public function admingppeisong($PTid = 0)
    {
        print_r($PTid);
        $fck = M('fck');
        $cur_one_price = M('fee')->where(array(
            'id' => 1
        ))->getField('gp_one');
        $where['id'] = array(
            'in',
            $PTid
        );
        $rs = $fck->where($where)
            ->field('id,agent_gp')
            ->order('pdt asc')
            ->select();
        $psflag = 0;
        foreach ($rs as $vo) {
            $myid = $vo['id'];
            $mygp = $vo['agent_gp'];
            $cz_gp_n = (int) ($this->numb_duibi($mygp, $cur_one_price));
            $psmoney = $cz_gp_n * $cur_one_price;
            
            $gsgp = $fck->where(array(
                'id' => 1
            ))->getField('live_gupiao');
            if ($gsgp >= $cz_gp_n) {
                $fck->execute('update __TABLE__ set agent_gp=agent_gp-' . $psmoney . ',live_gupiao=live_gupiao+' . $cz_gp_n . ' where is_pay>0 and id=' . $myid . '');
                $fck->execute('update __TABLE__ set live_gupiao=live_gupiao-' . $cz_gp_n . ' where is_pay>0 and id=1');
                $psflag = 1;
            }
        }
        // exit;
        if ($psflag == 1) {
            $this->Success('配送成功！');
        } else {
            $this->error('配送失败');
        }
        
        unset($fck, $where, $rs, $myid, $result);
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

    public function adminMenberDL()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $result = $fck->execute('update __TABLE__ set agent_cash=agent_cash+agent_use,agent_use=0 where is_pay>0');
            
            $bUrl = __URL__ . '/adminMenber';
            $this->_box(1, '转换会员奖金为购物券！', $bUrl, 1);
        } else {
            $this->error('错误2!');
        }
    }

    public function adminMenberZhuan($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $where['id'] = array(
                'in',
                $PTid
            );
            $rs = $fck->where($where)
                ->field('id')
                ->select();
            foreach ($rs as $vo) {
                $myid = $vo['id'];
                $fck->execute('update __TABLE__ set agent_cash=agent_cash+agent_use,agent_use=0 where is_pay>0 and id=' . $myid . '');
            }
            unset($fck, $where, $rs, $myid, $result);
            $bUrl = __URL__ . '/adminMenber';
            $this->_box(1, '转换会员奖金为购物券！', $bUrl, 1);
        } else {
            $this->error('错误2!');
        }
    }

    private function _adminMenberJB($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $where['id'] = array(
                'in',
                $PTid
            );
            $where['is_pay'] = array(
                'gt',
                0
            );
            $where['is_jb'] = array(
                'eq',
                0
            );
            $rs = $fck->where($where)->setField('is_jb', '1');
            if ($rs) {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(1, '设为代理商成功！', $bUrl, 1);
                exit();
            } else {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(0, '设为代理商失败！', $bUrl, 1);
                exit();
            }
        } else {
            $this->error('错误！');
            exit();
        }
    }

    public function adminMenberJBcancel($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $where['id'] = array(
                'in',
                $PTid
            );
            $rs = $fck->where($where)
                ->field('id')
                ->select();
            foreach ($rs as $vo) {
                $myid = $vo['id'];
                $fck->execute('update __TABLE__ set is_jb=0 where is_pay>0 and is_jb>0 and id=' . $myid . '');
            }
            unset($fck, $where, $rs, $myid, $result);
            $bUrl = __URL__ . '/adminMenber';
            $this->_box(1, '取消代理商成功！', $bUrl, 1);
        } else {
            $this->error('错误2!');
        }
    }

    private function _adminMenberWL($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $where['id'] = array(
                'in',
                $PTid
            );
            $where['is_pay'] = array(
                'gt',
                0
            );
            $where['is_aa'] = array(
                'eq',
                0
            );
            $rs = $fck->where($where)->setField('is_aa', '2');
            if ($rs) {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(1, '设为物流管理成功！', $bUrl, 1);
                exit();
            } else {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(0, '设为物流管理失败！', $bUrl, 1);
                exit();
            }
        } else {
            $this->error('错误！');
            exit();
        }
    }

    private function adminMenberDel($PTid = 0)
    {
        $fck = M('fck');
        $times = M('times');
        $bonus = M('bonus');
        $history = M('history');
        $chongzhi = M('chongzhi');
        $gouwu = M('gouwu');
        $tiqu = M('tiqu');
        $zhuanj = M('zhuanj');
        
        $rs = $fck->where('id=' . $PTid)->find();
        if ($rs) {
            $id = $rs['id'];
            $whe['id'] = $rs['father_id'];
            $con = $fck->where($whe)->count();
            if ($id == 1) {
                $bUrl = __URL__ . '/adminMenber';
                $this->error('该 ' . $rs['user_id'] . ' 不能删除！');
                exit();
            }
            if ($con == 2) {
                $bUrl = __URL__ . '/adminMenber';
                $this->error('该 ' . $rs['user_id'] . ' 会员有下级会员，不能删除！');
                exit();
            }
            if ($con == 1) {
                $this->set_Re_Path($id);
                $this->set_P_Path($id);
            }
            $where = array();
            $where['id'] = $PTid;
            $map['uid'] = $PTid;
            $bonus->where($map)->delete();
            $history->where($map)->delete();
            $chongzhi->where($map)->delete();
            $times->where($map)->delete();
            $tiqu->where($map)->delete();
            $zhuanj->where($map)->delete();
            $gouwu->where($map)->delete();
            $fck->where($where)->delete();
            $bUrl = __URL__ . '/adminMenber';
            $this->success('删除会员！');
        }
    }

    public function set_Re_Path($id)
    {
        $fck = M("fck");
        $frs = $fck->find($id);
        
        $r_rs = $fck->find($frs['re_id']);
        $xr_rs = $fck->where("re_id=" . $id)->select();
        foreach ($xr_rs as $xr_vo) {
            $re_Level = $r_rs['re_level'] + 1;
            $re_path = $r_rs['re_path'] . $r_rs['id'] . ',';
            $fck->execute("UPDATE __TABLE__ SET re_id=" . $r_rs['id'] . ",re_name='" . $r_rs['user_id'] . "',re_path='" . $re_path . "',re_level=" . $re_Level . " where `id`= " . $xr_vo['id']);
            // 修改分享路径
            $f_where = array();
            $f_where['re_path'] = array(
                'like',
                '%,' . $xr_vo['id'] . ',%'
            );
            $ff_rs = $fck->where($f_where)
                ->order('re_level asc')
                ->select();
            $r_where = array();
            foreach ($ff_rs as $fvo) {
                $r_where['id'] = $fvo['re_id'];
                $sr_rs = $fck->where($r_where)->find();
                $r_pLevel = $sr_rs['re_level'] + 1;
                $r_re_path = $sr_rs['re_path'] . $sr_rs['id'] . ',';
                $fck->execute("UPDATE __TABLE__ SET re_path='" . $r_re_path . "',re_level=" . $r_pLevel . " where `id`= " . $fvo['id']);
            }
        }
    }

    public function set_P_Path($id)
    {
        $fck = M("fck");
        $frs = $fck->find($id);
        
        $r_rs = $fck->find($frs['father_id']);
        $xr_rs = $fck->where("father_id=" . $id)->find();
        if ($xr_rs) {
            $p_level = $r_rs['p_level'] + 1;
            $p_path = $r_rs['p_path'] . $r_rs['id'] . ',';
            $fck->execute("UPDATE __TABLE__ SET treeplace=" . $frs['treeplace'] . ",father_id=" . $r_rs['id'] . ",father_name='" . $r_rs['user_id'] . "',p_path='" . $p_path . "',p_level=" . $p_level . " where `id`= " . $xr_rs['id']);
            // 修改分享路径
            $f_where = array();
            $f_where['p_path'] = array(
                'like',
                '%,' . $xr_rs['id'] . ',%'
            );
            $ff_rs = $fck->where($f_where)
                ->order('p_level asc')
                ->select();
            $r_where = array();
            foreach ($ff_rs as $fvo) {
                $r_where['id'] = $fvo['father_id'];
                $sr_rs = $fck->where($r_where)->find();
                $p_level = $sr_rs['p_level'] + 1;
                $p_path = $sr_rs['p_path'] . $sr_rs['id'] . ',';
                $fck->execute("UPDATE __TABLE__ SET p_path='" . $p_path . "',p_level=" . $p_level . " where `id`= " . $fvo['id']);
            }
        }
    }

    public function jiandan($Pid = 0, $DanShu = 1, $pdt, $t_rs)
    {
        // ========================================== 往上统计单数
        $fck = M('fck');
        $where = array();
        $where['id'] = $Pid;
        $field = 'treeplace,father_id,pdt';
        $vo = $fck->where($where)
            ->field($field)
            ->find();
        if ($vo) {
            $Fid = $vo['father_id'];
            $TPe = $vo['treeplace'];
            if ($pdt > $t_rs) {
                if ($TPe == 0 && $Fid > 0) {
                    $fck->execute("update __TABLE__ Set `l`=l-$DanShu, `benqi_l`=benqi_l-$DanShu where `id`=" . $Fid);
                } elseif ($TPe == 1 && $Fid > 0) {
                    $fck->execute("update __TABLE__ Set `r`=r-$DanShu, `benqi_r`=benqi_r-$DanShu  where `id`=" . $Fid);
                }
            } else {
                if ($TPe == 0 && $Fid > 0) {
                    $fck->execute("update __TABLE__ Set `l`=l-$DanShu where `id`=" . $Fid);
                } elseif ($TPe == 1 && $Fid > 0) {
                    $fck->execute("update __TABLE__ Set `r`=r-$DanShu  where `id`=" . $Fid);
                }
            }
            
            if ($Fid > 0)
                $this->jiandan($Fid, $DanShu, $pdt, $t_rs);
        }
        unset($where, $field, $vo, $pdt, $t_rs);
    }

    private function adminMenberFenhong($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $where['id'] = array(
                'in',
                $PTid
            );
            $where['is_pay'] = array(
                'gt',
                0
            );
            $rs = $fck->where($where)->setField('is_fenh', '0');
            if ($rs) {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(1, '开启奖金成功！', $bUrl, 1);
                exit();
            } else {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(0, '开启奖金失败！', $bUrl, 1);
                exit();
            }
        } else {
            $this->error('错误！');
            exit();
        }
    }

    private function _Lockfenh($PTid = 0)
    {
        // 锁定会员
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $where['is_pay'] = array(
                'egt',
                1
            );
            $where['_string'] = 'id>1';
            $where['id'] = array(
                'in',
                $PTid
            );
            $rs = $fck->where($where)->setField('is_fenh', '1');
            
            if ($rs) {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(1, '关闭奖金成功！', $bUrl, 1);
                exit();
            } else {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(0, '关闭奖金失败！', $bUrl, 1);
                exit();
            }
        } else {
            $this->error('错误!');
        }
    }

    // 开启会员
    private function _adminMenberOpen($PTid = 0)
    {
        $fck = M('fck');
        $where['id'] = array(
            'in',
            $PTid
        );
        $data['is_pay'] = 1;
        $rs = $fck->where($where)->setField('is_lock', '0');
        if ($rs) {
            $this->ajaxSuccess('开启成功');
        } else {
            $this->ajaxError('开启失败');
        }
    }

    // 锁定会员
    private function _adminMenberHb($PTid = 0)
    {
        $fck = M('fck');
        
        $where['id'] = $PTid;
        $rs = $fck->where($where)->find();
        if (empty($rs['openid'])) {
            $this->ajaxError('此用户没有openid');
        }
        
        if ($rs) {
            $this->ajaxSuccess('锁定成功');
        } else {
            $this->ajaxError('锁定失败');
        }
    }

    // 锁定会员
    private function _adminMenberLock($PTid = 0)
    {
        $fck = M('fck');
        $where['is_pay'] = array(
            'egt',
            1
        );
        $where['is_boss'] = 0;
        $where['id'] = $PTid;
        $rs = $fck->where($where)->setField('is_lock', '1');
        if ($rs) {
            
            $rs = $fck->where($where)->setInc('lock_count', 1);
            
            $this->ajaxSuccess('锁定成功');
        } else {
            $this->ajaxError('锁定失败');
        }
    }

    // 设为服务中心
    private function _adminMenberAgent($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $where['id'] = array(
                'in',
                $PTid
            );
            $where['is_agent'] = array(
                'lt',
                2
            );
            $rs2 = $fck->where($where)->setField('adt', mktime());
            $rs1 = $fck->where($where)->setField('is_agent', '2');
            if ($rs1) {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(1, '设置服务中心成功！', $bUrl, 1);
                exit();
            } else {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(0, '设置服务中心失败！', $bUrl, 1);
                exit();
            }
        } else {
            $this->error('错误！');
            exit();
        }
    }

    // 开启分红奖
    private function _OpenFh($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $nowday = strtotime(date('Y-m-d'));
            $where['is_lockfh'] = array(
                'egt',
                1
            );
            $where['_string'] = 'id>1';
            $where['id'] = array(
                'in',
                $PTid
            );
            $varray = array(
                'is_lockfh' => '0',
                'fanli_time' => $nowday
            );
            $rs = $fck->where($where)->setField($varray);
            if ($rs) {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(1, '开启分红奖成功！', $bUrl, 1);
                exit();
            } else {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(0, '开启分红奖失败！', $bUrl, 1);
                exit();
            }
        } else {
            $this->error('错误!');
        }
    }

    // 关闭分红奖
    private function _LockFh($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $where['is_lockfh'] = array(
                'egt',
                0
            );
            $where['_string'] = 'id>1';
            $where['id'] = array(
                'in',
                $PTid
            );
            $rs = $fck->where($where)->setField('is_lockfh', '1');
            
            if ($rs) {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(1, '关闭分红奖成功！', $bUrl, 1);
                exit();
            } else {
                $bUrl = __URL__ . '/adminMenber';
                $this->_box(0, '关闭分红奖失败！', $bUrl, 1);
                exit();
            }
        } else {
            $this->error('错误!');
        }
    }

    public function adminMenberUP()
    {
        // 会员晋级
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $PTid = (int) $_GET['UP_ID'];
            $rs = $fck->find($PTid);
            
            if (! $rs) {
                $this->error('非法操作！');
                exit();
            }
            
            switch ($rs['u_level']) {
                case 1:
                    $fck->query("UPDATE `xt_fck` SET u_level=2,b12=2000 where id=" . $PTid);
                    break;
                case 2:
                    $fck->query("UPDATE `xt_fck` SET u_level=3,b12=4000 where id=" . $PTid);
                    break;
            }
            
            unset($fck, $PTid);
            $bUrl = __URL__ . '/adminMenber';
            $this->_box(1, '晋升！', $bUrl, 1);
        } else {
            $this->error('错误!');
        }
    }

    // =================================================管理员帮会员提现处理
    public function adminMenberCurrency($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao') {
            $fck = M('fck');
            $where = array(); //
            $tiqu = M('tiqu');
            // 查询条件
            $where['id'] = array(
                'in',
                $PTid
            );
            $where['agent_use'] = array(
                'egt',
                100
            );
            $field = 'id,user_id,agent_use,bank_name,bank_card,user_name';
            $fck_rs = $fck->where($where)
                ->field($field)
                ->select();
            
            $data = array();
            $tiqu_where = array();
            $eB = 0.02; // 提现税收
            $nowdate = strtotime(date('c'));
            foreach ($fck_rs as $vo) {
                $is_qf = 0; // 区分上次有没有提现
                $ePoints = 0;
                $ePoints = $vo['agent_use'];
                
                $tiqu_where['uid'] = $vo['id'];
                $tiqu_where['is_pay'] = 0;
                $trs = $tiqu->where($tiqu_where)
                    ->field('id')
                    ->find();
                if ($trs) {
                    $is_qf = 1;
                }
                // 提现税收
                // if ($ePoints >= 10 && $ePoints <= 100){
                // $ePoints1 = $ePoints - 2;
                // }else{
                // $ePoints1 = $ePoints - $ePoints * $eB;//(/100);
                // }
                
                if ($is_qf == 0) {
                    $fck->query("UPDATE `xt_fck` SET `zsq`=zsq+agent_use,`agent_use`=0 where `id`=" . $vo['id']);
                    // 开始事务处理
                    $data['uid'] = $vo['id'];
                    $data['user_id'] = $vo['user_id'];
                    $data['rdt'] = $nowdate;
                    $data['money'] = $ePoints;
                    $data['money_two'] = $ePoints;
                    $data['is_pay'] = 1;
                    $data['user_name'] = $vo['user_name'];
                    $data['bank_name'] = $vo['bank_name'];
                    $data['bank_card'] = $vo['bank_card'];
                    $tiqu->add($data);
                }
            }
            unset($fck, $where, $tiqu, $field, $fck_rs, $data, $tiqu_where, $eB, $nowdate);
            $bUrl = __URL__ . '/adminMenber';
            $this->_box(1, '积分提现！', $bUrl, 1);
            exit();
        } else {
            $this->error('错误!');
            exit();
        }
    }

    // ===============================================消费管理
    public function adminXiaofei()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssShenqixf') {
            $xiaof = M('xiaof');
            $UserID = $_POST['UserID'];
            if (! empty($UserID)) {
                $map['user_id'] = array(
                    'like',
                    "%" . $UserID . "%"
                );
            }
            
            $field = '*';
            // =====================分页开始==============================================
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $xiaof->where($map)->count(); // 总页数
            $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
            $page_where = 'UserID=' . $UserID; // 分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $list = $xiaof->where($map)
                ->field($field)
                ->order('id desc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            $this->assign('list', $list); // 数据输出到模板
                                          // =================================================
            
            $this->display('adminXiaofei');
        } else {
            $this->error('错误!');
            exit();
        }
    }

    // 处理消费
    public function adminXiaofeiAC()
    {
        // 处理提交按钮
        $action = $_POST['action'];
        // 获取复选框的值
        $PTid = $_POST['tabledb'];
        $fck = M('fck');
        // if (!$fck->autoCheckToken($_POST)){
        // $this->error('页面过期，请刷新页面！');
        // exit;
        // }
        if (empty($PTid)) {
            $bUrl = __URL__ . '/adminXiaofei';
            $this->_box(0, '请选择！', $bUrl, 1);
            exit();
        }
        switch ($action) {
            case '确认':
                $this->_adminXiaofeiConfirm($PTid);
                break;
            case '删除':
                $this->_adminXiaofeiDel($PTid);
                break;
            default:
                $bUrl = __URL__ . '/adminXiaofei';
                $this->_box(0, '没有该记录！', $bUrl, 1);
                break;
        }
    }

    // ====================================================确认消费
    private function _adminXiaofeiConfirm($PTid)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssShenqixf') {
            $xiaof = M('xiaof');
            $fck = M('fck'); //
            $where = array();
            $where['is_pay'] = 0; // 未审核的申请
            $where['id'] = array(
                'in',
                $PTid
            ); // 所有选中的会员ID
            $rs = $xiaof->where($where)->select(); // tiqu表要通过的申请记录数组
            $history = M('history');
            $data = array();
            $fck_where = array();
            $nowdate = strtotime(date('c'));
            foreach ($rs as $rss) {
                // 开始事务处理
                $fck->startTrans();
                // 明细表
                $data['uid'] = $rss['uid'];
                $data['user_id'] = $rss['user_id'];
                $data['action_type'] = '重复消费';
                $data['pdt'] = $nowdate;
                $data['epoints'] = - $rss['money'];
                $data['bz'] = '重复消费';
                $data['did'] = 0;
                $data['allp'] = 0;
                $history->create();
                $rs1 = $history->add($data);
                if ($rs1) {
                    // 提交事务
                    $xiaof->execute("UPDATE __TABLE__ set `is_pay`=1 where `id`=" . $rss['id']);
                    $fck->commit();
                } else {
                    // 事务回滚：
                    $fck->rollback();
                }
            }
            unset($xiaof, $fck, $where, $rs, $history, $data, $nowdate, $fck_where);
            $bUrl = __URL__ . '/adminXiaofei';
            $this->_box(1, '确认消费成功！', $bUrl, 1);
        } else {
            $this->error('错误!');
            exit();
        }
    }

    // 删除消费
    private function _adminXiaofeiDel($PTid)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssShenqixf') {
            $xiaof = M('xiaof');
            $where = array();
            $where['is_pay'] = 0;
            $where['id'] = array(
                'in',
                $PTid
            );
            $trs = $xiaof->where($where)->select();
            $fck = M('fck');
            foreach ($trs as $vo) {
                $fck->execute("UPDATE __TABLE__ SET agent_cash=agent_cash+{$vo['money']} WHERE id={$vo['uid']}");
            }
            $rs = $xiaof->where($where)->delete();
            if ($rs) {
                $bUrl = __URL__ . '/adminXiaofei';
                $this->_box(1, '删除成功！', $bUrl, 1);
                exit();
            } else {
                $bUrl = __URL__ . '/adminXiaofei';
                $this->_box(1, '删除成功！', $bUrl, 1);
                exit();
            }
        } else {
            $this->error('错误!');
            exit();
        }
    }

    public function financeDaoChu_ChuN()
    {
        // 导出excel
        set_time_limit(0);
        
        header("Content-Type:   application/vnd.ms-excel");
        header("Content-Disposition:   attachment;   filename=Cashier.xls");
        header("Pragma:   no-cache");
        header("Content-Type:text/html; charset=utf-8");
        header("Expires:   0");
        
        $m_page = (int) $_GET['p'];
        if (empty($m_page)) {
            $m_page = 1;
        }
        
        $times = M('times');
        $Numso = array();
        $Numss = array();
        $map = 'is_count=0';
        // 查询字段
        $field = '*';
        import("@.ORG.ZQPage"); // 导入分页类
        $count = $times->where($map)->count(); // 总页数
        $listrows = C('PAGE_LISTROWS'); // 每页显示的记录数
        $s_p = $listrows * ($m_page - 1) + 1;
        $e_p = $listrows * ($m_page);
        
        $title = "当期出纳 第" . $s_p . "-" . $e_p . "条 导出时间:" . date("Y-m-d   H:i:s");
        
        echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
        // 输出标题
        echo '<tr   bgcolor="#cccccc"><td   colspan="6"   align="center">' . $title . '</td></tr>';
        // 输出字段名
        echo '<tr  align=center>';
        echo "<td>期数</td>";
        echo "<td>结算时间</td>";
        echo "<td>当期收入</td>";
        echo "<td>当期支出</td>";
        echo "<td>当期盈利</td>";
        echo "<td>拨出比例</td>";
        echo '</tr>';
        // 输出内容
        
        $rs = $times->where($map)
            ->order(' id desc')
            ->find();
        $Numso['0'] = 0;
        $Numso['1'] = 0;
        $Numso['2'] = 0;
        if ($rs) {
            $eDate = strtotime(date('c')); // time()
            $sDate = $rs['benqi']; // 时间
            
            $this->MiHouTaoBenQi($eDate, $sDate, $Numso, 0);
        }
        
        $page_where = ''; // 分页条件
        $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $list = $times->where($map)
            ->field($field)
            ->order('id desc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        
        // dump($list);exit;
        
        $occ = 1;
        $Numso['1'] = $Numso['1'] + $Numso['0'];
        $Numso['3'] = $Numso['3'] + $Numso['0'];
        $maxnn = 0;
        foreach ($list as $Roo) {
            
            $eDate = $Roo['benqi']; // 本期时间
            $sDate = $Roo['shangqi']; // 上期时间
            $Numsd = array();
            $Numsd[$occ][0] = $eDate;
            $Numsd[$occ][1] = $sDate;
            
            $this->MiHouTaoBenQi($eDate, $sDate, $Numss, 1);
            // $Numoo = $Numss['0']; //当期收入
            $Numss[$occ]['0'] = $Numss['0'];
            $Dopp = M('bonus');
            $field = '*';
            $where = " s_date>= '" . $sDate . "' And e_date<= '" . $eDate . "' ";
            $rsc = $Dopp->where($where)
                ->field($field)
                ->select();
            $Numss[$occ]['1'] = 0;
            $nnn = 0;
            foreach ($rsc as $Roc) {
                $nnn ++;
                $Numss[$occ]['1'] += $Roc['b0']; // 当期支出
                $Numb2[$occ]['1'] += $Roc['b1'];
                $Numb3[$occ]['1'] += $Roc['b2'];
                $Numb4[$occ]['1'] += $Roc['b3'];
                // $Numoo += $Roc['b9'];//当期收入
            }
            $maxnn += $nnn;
            $Numoo = $Numss['0']; // 当期收入
            $Numss[$occ]['2'] = $Numoo - $Numss[$occ]['1']; // 本期赢利
            $Numss[$occ]['3'] = substr(floor(($Numss[$occ]['1'] / $Numoo) * 100), 0, 3); // 本期拔比
            $Numso['1'] += $Numoo; // 收入合计
            $Numso['2'] += $Numss[$occ]['1']; // 支出合计
            $Numso['3'] += $Numss[$occ]['2']; // 赢利合计
            $Numso['4'] = substr(floor(($Numso['2'] / $Numso['1']) * 100), 0, 3); // 总拔比
            $Numss[$occ]['4'] = substr(($Numb2[$occ]['1'] / $Numoo) * 100, 0, 4); // 小区奖金拔比
            $Numss[$occ]['5'] = substr(($Numb3[$occ]['1'] / $Numoo) * 100, 0, 4); // 互助基金拔比
            $Numss[$occ]['6'] = substr(($Numb4[$occ]['1'] / $Numoo) * 100, 0, 4); // 管理基金拔比
            $Numss[$occ]['7'] = $Numb2[$occ]['1']; // 小区奖金
            $Numss[$occ]['8'] = $Numb3[$occ]['1']; // 互助基金
            $Numss[$occ]['9'] = $Numb4[$occ]['1']; // 管理基金
            $Numso['5'] += $Numb2[$occ]['1']; // 小区奖金合计
            $Numso['6'] += $Numb3[$occ]['1']; // 互助基金合计
            $Numso['7'] += $Numb4[$occ]['1']; // 管理基金合计
            $Numso['8'] = substr(($Numso['5'] / $Numso['1']) * 100, 0, 4); // 小区奖金总拔比
            $Numso['9'] = substr(($Numso['6'] / $Numso['1']) * 100, 0, 4); // 互助基金总拔比
            $Numso['10'] = substr(($Numso['7'] / $Numso['1']) * 100, 0, 4); // 管理基金总拔比
            $occ ++;
        }
        
        $i = 0;
        foreach ($list as $row) {
            $i ++;
            echo '<tr align=center>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . date("Y-m-d H:i:s", $row['benqi']) . '</td>';
            echo '<td>' . $Numss[$i][0] . '</td>';
            echo '<td>' . $Numss[$i][1] . '</td>';
            echo '<td>' . $Numss[$i][2] . '</td>';
            echo '<td>' . $Numss[$i][3] . ' % </td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function financeDaoChu_JJCX()
    {
        // 导出excel
        set_time_limit(0);
        
        header("Content-Type:   application/vnd.ms-excel");
        header("Content-Disposition:   attachment;   filename=Bonus-query.xls");
        header("Pragma:   no-cache");
        header("Content-Type:text/html; charset=utf-8");
        header("Expires:   0");
        
        $m_page = (int) $_REQUEST['p'];
        if (empty($m_page)) {
            $m_page = 1;
        }
        $fee = M('fee'); // 参数表
        $times = M('times');
        $bonus = M('bonus'); // 奖金表
        $fee_rs = $fee->field('s18')->find();
        $fee_s7 = explode('|', $fee_rs['s18']);
        
        $where = array();
        $sql = '';
        if (isset($_REQUEST['FanNowDate'])) { // 日期查询
            if (! empty($_REQUEST['FanNowDate'])) {
                $time1 = strtotime($_REQUEST['FanNowDate']); // 这天 00:00:00
                $time2 = strtotime($_REQUEST['FanNowDate']) + 3600 * 24 - 1; // 这天 23:59:59
                $sql = "where e_date >= $time1 and e_date <= $time2";
            }
        }
        
        $field = '*';
        import("@.ORG.ZQPage"); // 导入分页类
        $count = count($bonus->query("select id from __TABLE__ " . $sql . " group by did")); // 总记录数
        $listrows = C('PAGE_LISTROWS'); // 每页显示的记录数
        $page_where = 'FanNowDate=' . $_REQUEST['FanNowDate']; // 分页条件
        if (! empty($page_where)) {
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
        } else {
            $Page = new ZQPage($count, $listrows, 1, 0, 3);
        }
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $status_rs = ($Page->getPage() - 1) * $listrows;
        $list = $bonus->query("select e_date,did,sum(b0) as b0,sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,max(type) as type from __TABLE__ " . $sql . " group by did  order by did desc limit " . $status_rs . "," . $listrows);
        // =================================================
        
        $s_p = $listrows * ($m_page - 1) + 1;
        $e_p = $listrows * ($m_page);
        
        $title = "奖金查询 第" . $s_p . "-" . $e_p . "条 导出时间:" . date("Y-m-d   H:i:s");
        
        echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
        // 输出标题
        echo '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">' . $title . '</td></tr>';
        // 输出字段名
        echo '<tr  align=center>';
        echo "<td>结算时间</td>";
        echo "<td>" . $fee_s7[0] . "</td>";
        echo "<td>" . $fee_s7[1] . "</td>";
        echo "<td>" . $fee_s7[2] . "</td>";
        echo "<td>" . $fee_s7[3] . "</td>";
        echo "<td>" . $fee_s7[4] . "</td>";
        echo "<td>" . $fee_s7[5] . "</td>";
        echo "<td>" . $fee_s7[6] . "</td>";
        echo "<td>合计</td>";
        echo "<td>实发</td>";
        echo '</tr>';
        // 输出内容
        
        // dump($list);exit;
        
        $i = 0;
        foreach ($list as $row) {
            $i ++;
            $mmm = $row['b1'] + $row['b2'] + $row['b3'] + $row['b4'] + $row['b5'] + $row['b6'] + $row['b7'];
            echo '<tr align=center>';
            echo '<td>' . date("Y-m-d H:i:s", $row['e_date']) . '</td>';
            echo "<td>" . $row['b1'] . "</td>";
            echo "<td>" . $row['b2'] . "</td>";
            echo "<td>" . $row['b3'] . "</td>";
            echo "<td>" . $row['b4'] . "</td>";
            echo "<td>" . $row['b5'] . "</td>";
            echo "<td>" . $row['b6'] . "</td>";
            echo "<td>" . $row['b7'] . "</td>";
            echo "<td>" . $mmm . "</td>";
            echo "<td>" . $row['b0'] . "</td>";
            echo '</tr>';
        }
        echo '</table>';
    }

    // 会员表
    public function financeDaoChu_MM()
    {
        // 导出excel
        set_time_limit(0);
        $user_id = I('user_id', 0);
        header("Content-Type:   application/vnd.ms-excel");
        header("Content-Disposition:   attachment;   filename=Member.xls");
        header("Pragma:   no-cache");
        header("Content-Type:text/html; charset=utf-8");
        header("Expires:   0");
        
        $fck = M('fck'); // 奖金表
        
        $map = array();
        $map['id'] = array(
            'gt',
            0
        );
        IF ($user_id > 0) {
            $map['re_path'] = array(
                'like',
                '%,' . $user_id . ',%'
            );
        }
        $field = '*';
        $list = $fck->where($map)
            ->field($field)
            ->order('pdt asc')
            ->select();
        
        $title = "会员表 导出时间:" . date("Y-m-d   H:i:s");
        
        echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
        // 输出标题
        echo '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">' . $title . '</td></tr>';
        // 输出字段名
        echo '<tr  align=center>';
        echo "<td>序号</td>";
        echo "<td>用户账号</td>";
        echo "<td>姓名</td>";
        echo "<td>银行卡号</td>";
        echo "<td>开户行地址</td>";
        echo "<td>联系电话</td>";
        echo "<td>身份证号</td>";
        echo "<td>注册时间</td>";
        echo "<td>余额</td>";
        echo "<td>总交易量</td>";
        echo "<td>交易量</td>";
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
            
            $row['all_trade_money'] = get_all_trade_money($row['id']);
            
            // 获取交易量
            
            $row['trade_money'] = get_trade_money($row['id']);
            
            echo '<tr align=center>';
            echo '<td>' . chr(28) . $num . '</td>';
            echo "<td>" . $row['user_id'] . "</td>";
            echo "<td>" . $row['user_name'] . "</td>";
            echo "<td>" . sprintf('%s', (string) chr(28) . $row['bank_card'] . chr(28)) . "</td>";
            echo "<td>" . $row['bank_province'] . $row['bank_city'] . $row['bank_address'] . "</td>";
            echo "<td>" . $row['user_tel'] . "&nbsp;</td>";
            echo "<td>" . $row['card_no'] . "</td>";
            echo "<td>" . date("Y-m-d H:i:s", $row['rdt']) . "</td>";
            echo "<td>" . $row['agent_use'] . "</td>";
            echo "<td>" . $row['trade_money'] . "</td>";
            echo "<td>" . $row['all_trade_money'] . "</td>";
            echo '</tr>';
        }
        echo '</table>';
    }

    // 服务中心表
    public function financeDaoChu_BD()
    {
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

    public function financeDaoChu()
    {
        // 导出excel
        // if ($_SESSION['UrlPTPass'] =='MyssPiPa' || $_SESSION['UrlPTPass'] == 'MyssMiHouTao'){
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
        echo '<tr  align=center>';
        echo "<td>银行卡号</td>";
        echo "<td>姓名</td>";
        echo "<td>银行名称</td>";
        echo "<td>省份</td>";
        echo "<td>城市</td>";
        echo "<td>金额</td>";
        echo "<td>所有人的排序</td>";
        echo '</tr>';
        // 输出内容
        $did = (int) $_GET['did'];
        $bonus = M('bonus');
        $map = 'xt_bonus.b0>0 and xt_bonus.did=' . $did;
        // 查询字段
        $field = 'xt_bonus.id,xt_bonus.uid,xt_bonus.did,s_date,e_date,xt_bonus.b0,xt_bonus.b1,xt_bonus.b2,xt_bonus.b3';
        $field .= ',xt_bonus.b4,xt_bonus.b5,xt_bonus.b6,xt_bonus.b7,xt_bonus.b8,xt_bonus.b9,xt_bonus.b10';
        $field .= ',xt_fck.user_id,xt_fck.user_tel,xt_fck.bank_card';
        $field .= ',xt_fck.user_name,xt_fck.user_address,xt_fck.nickname,xt_fck.user_phone,xt_fck.bank_province,xt_fck.user_tel';
        $field .= ',xt_fck.user_code,xt_fck.bank_city,xt_fck.bank_name,xt_fck.bank_address';
        import("@.ORG.ZQPage"); // 导入分页类
        $count = $bonus->where($map)->count(); // 总页数
        $listrows = 1000000; // 每页显示的记录数
        $page_where = ''; // 分页条件
        $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $join = 'left join xt_fck ON xt_bonus.uid=xt_fck.id'; // 连表查询
        $list = $bonus->where($map)
            ->field($field)
            ->join($join)
            ->Distinct(true)
            ->order('id asc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
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
            }
            echo '<tr align=center>';
            echo '<td>' . sprintf('%s', (string) chr(28) . $row['bank_card'] . chr(28)) . '</td>';
            echo '<td>' . $row['user_name'] . '</td>';
            echo "<td>" . $row['bank_name'] . "</td>";
            echo '<td>' . $row['bank_province'] . '</td>';
            echo '<td>' . $row['bank_city'] . '</td>';
            echo '<td>' . $row['b0'] . '</td>';
            echo '<td>' . chr(28) . $num . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        // }else{
        // $this->error('错误!');
        // exit;
        // }
    }

    public function financeDaoChuTwo1()
    {
        // 导出WPS
        if ($_SESSION['UrlPTPass'] == 'MyssGuanPaoYingTao' || $_SESSION['UrlPTPass'] == 'MyssMiHouTao') {
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
            echo '<tr  align=center>';
            echo "<td>用户账号</td>";
            echo "<td>开会名</td>";
            echo "<td>开户银行</td>";
            echo "<td>银行账户</td>";
            echo "<td>兑余额额</td>";
            echo "<td>提现时间</td>";
            echo "<td>所有人的排序</td>";
            echo '</tr>';
            // 输出内容
            $did = (int) $_GET['did'];
            $bonus = M('bonus');
            $map = 'xt_bonus.b0>0 and xt_bonus.did=' . $did;
            // 查询字段
            $field = 'xt_bonus.id,xt_bonus.uid,xt_bonus.did,s_date,e_date,xt_bonus.b0,xt_bonus.b1,xt_bonus.b2,xt_bonus.b3';
            $field .= ',xt_bonus.b4,xt_bonus.b5,xt_bonus.b6,xt_bonus.b7,xt_bonus.b8,xt_bonus.b9,xt_bonus.b10';
            $field .= ',xt_fck.user_id,xt_fck.user_tel,xt_fck.bank_card';
            $field .= ',xt_fck.user_name,xt_fck.user_address,xt_fck.nickname,xt_fck.user_phone,xt_fck.bank_province,xt_fck.user_tel';
            $field .= ',xt_fck.user_code,xt_fck.bank_city,xt_fck.bank_name,xt_fck.bank_address';
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $bonus->where($map)->count(); // 总页数
            $listrows = 1000000; // 每页显示的记录数
            $page_where = ''; // 分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $join = 'left join xt_fck ON xt_bonus.uid=xt_fck.id'; // 连表查询
            $list = $bonus->where($map)
                ->field($field)
                ->join($join)
                ->Distinct(true)
                ->order('id asc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
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
                }
                $date = date('Y-m-d H:i:s', $row['rdt']);
                
                echo '<tr align=center>';
                echo "<td>'" . $row['user_id'] . '</td>';
                echo '<td>' . $row['user_name'] . '</td>';
                echo "<td>" . $row['bank_name'] . "</td>";
                echo '<td>' . $row['bank_card'] . '</td>';
                echo '<td>' . $row['money'] . '</td>';
                echo '<td>' . $date . '</td>';
                echo "<td>'" . $num . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            $this->error('错误!');
            exit();
        }
    }

    public function financeDaoChuTwo()
    {
        // 导出WPS
        // if ($_SESSION['UrlPTPass'] =='MyssGuanPaoYingTao' || $_SESSION['UrlPTPass'] == 'MyssMiHouTao'){
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
        echo '<tr  align=center>';
        echo "<td>银行卡号</td>";
        echo "<td>姓名</td>";
        echo "<td>银行名称</td>";
        echo "<td>省份</td>";
        echo "<td>城市</td>";
        echo "<td>金额</td>";
        echo "<td>所有人的排序</td>";
        echo '</tr>';
        // 输出内容
        $did = (int) $_GET['did'];
        $bonus = M('bonus');
        $map = 'xt_bonus.b0>0 and xt_bonus.did=' . $did;
        // 查询字段
        $field = 'xt_bonus.id,xt_bonus.uid,xt_bonus.did,s_date,e_date,xt_bonus.b0,xt_bonus.b1,xt_bonus.b2,xt_bonus.b3';
        $field .= ',xt_bonus.b4,xt_bonus.b5,xt_bonus.b6,xt_bonus.b7,xt_bonus.b8,xt_bonus.b9,xt_bonus.b10';
        $field .= ',xt_fck.user_id,xt_fck.user_tel,xt_fck.bank_card';
        $field .= ',xt_fck.user_name,xt_fck.user_address,xt_fck.nickname,xt_fck.user_phone,xt_fck.bank_province,xt_fck.user_tel';
        $field .= ',xt_fck.user_code,xt_fck.bank_city,xt_fck.bank_name,xt_fck.bank_address';
        import("@.ORG.ZQPage"); // 导入分页类
        $count = $bonus->where($map)->count(); // 总页数
        $listrows = 1000000; // 每页显示的记录数
        $page_where = ''; // 分页条件
        $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $join = 'left join xt_fck ON xt_bonus.uid=xt_fck.id'; // 连表查询
        $list = $bonus->where($map)
            ->field($field)
            ->join($join)
            ->Distinct(true)
            ->order('id asc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
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
            }
            echo '<tr align=center>';
            echo "<td>'" . sprintf('%s', (string) chr(28) . $row['bank_card'] . chr(28)) . '</td>';
            echo '<td>' . $row['user_name'] . '</td>';
            echo "<td>" . $row['bank_name'] . "</td>";
            echo '<td>' . $row['bank_province'] . '</td>';
            echo '<td>' . $row['bank_city'] . '</td>';
            echo '<td>' . $row['b0'] . '</td>';
            echo "<td>'" . $num . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        // }else{
        // $this->error('错误!');
        // exit;
        // }
    }

    public function financeDaoChuTXT()
    {
        // 导出TXT
        if ($_SESSION['UrlPTPass'] == 'MyssPiPa' || $_SESSION['UrlPTPass'] == 'MyssMiHouTao') {
            // 输出内容
            $did = (int) $_GET['did'];
            $bonus = M('bonus');
            $map = 'xt_bonus.b0>0 and xt_bonus.did=' . $did;
            // 查询字段
            $field = 'xt_bonus.id,xt_bonus.uid,xt_bonus.did,s_date,e_date,xt_bonus.b0,xt_bonus.b1,xt_bonus.b2,xt_bonus.b3';
            $field .= ',xt_bonus.b4,xt_bonus.b5,xt_bonus.b6,xt_bonus.b7,xt_bonus.b8,xt_bonus.b9,xt_bonus.b10';
            $field .= ',xt_fck.user_id,xt_fck.user_tel,xt_fck.bank_card';
            $field .= ',xt_fck.user_name,xt_fck.user_address,xt_fck.nickname,xt_fck.user_phone,xt_fck.bank_province,xt_fck.user_tel';
            $field .= ',xt_fck.user_code,xt_fck.bank_city,xt_fck.bank_name,xt_fck.bank_address';
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $bonus->where($map)->count(); // 总页数
            $listrows = 1000000; // 每页显示的记录数
            $page_where = ''; // 分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $join = 'left join xt_fck ON xt_bonus.uid=xt_fck.id'; // 连表查询
            $list = $bonus->where($map)
                ->field($field)
                ->join($join)
                ->Distinct(true)
                ->order('id asc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            $i = 0;
            $ko = "";
            $m_ko = 0;
            foreach ($list as $row) {
                $i ++;
                $num = strlen($i);
                if ($num == 1) {
                    $num = '000' . $i;
                } elseif ($num == 2) {
                    $num = '00' . $i;
                } elseif ($num == 3) {
                    $num = '0' . $i;
                }
                $ko .= $row['bank_card'] . "|" . $row['user_name'] . "|" . $row['bank_name'] . "|" . $row['bank_province'] . "|" . $row['bank_city'] . "|" . $row['b0'] . "|" . $num . "\r\n";
                $m_ko += $row['b0'];
                $e_da = $row['e_date'];
            }
            $m_ko = $this->_2Mal($m_ko, 2);
            $content = $num . "|" . $m_ko . "\r\n" . $ko;
            
            header('Content-Type: text/x-delimtext;');
            header("Content-Disposition: attachment; filename=xt_" . date('Y-m-d H:i:s', $e_da) . ".txt");
            header("Pragma: no-cache");
            header("Content-Type:text/html; charset=utf-8");
            header("Expires: 0");
            echo $content;
            exit();
        } else {
            $this->error('错误!');
            exit();
        }
    }

    public function setParameterManage()
    {
        $this->assign('url', U('YouZi/setParameter'));
        $this->display('Public/admincontainer');
    }

    // 参数设置
    public function setParameter()
    {
        // if ($_SESSION['UrlPTPass'] == 'MyssPingGuoCP'){
        $fee = M('fee');
        $fee_rs = $fee->find();
        $fee_s1 = $fee_rs['s1'];
        $fee_s2 = $fee_rs['s2'];
        $fee_s3 = $fee_rs['s3'];
        $fee_s4 = $fee_rs['s4'];
        $fee_s5 = $fee_rs['s5'];
        $fee_s6 = $fee_rs['s6'];
        $fee_s7 = $fee_rs['s7'];
        $fee_s8 = $fee_rs['s8'];
        $fee_s9 = $fee_rs['s9'];
        $fee_s10 = $fee_rs['s10'];
        $fee_s11 = $fee_rs['s11'];
        $fee_s12 = $fee_rs['s12'];
        $fee_s13 = $fee_rs['s13'];
        $fee_s14 = $fee_rs['s14'];
        $fee_s15 = $fee_rs['s15'];
        $fee_s16 = $fee_rs['s16'];
        $fee_s17 = $fee_rs['s17'];
        $fee_s18 = $fee_rs['s18'];
        
        $fee_str1 = $fee_rs['str1'];
        $fee_str2 = $fee_rs['str2'];
        $fee_str3 = $fee_rs['str3'];
        $fee_str4 = $fee_rs['str4'];
        $fee_str5 = $fee_rs['str5'];
        $fee_str6 = $fee_rs['str6'];
        $fee_str7 = $fee_rs['str7'];
        $fee_str9 = $fee_rs['str9'];
        
        $fee_str10 = $fee_rs['str10'];
        $fee_str11 = $fee_rs['str11'];
        $fee_str12 = $fee_rs['str12'];
        $fee_str13 = $fee_rs['str13'];
        $fee_str14 = $fee_rs['str14'];
        $fee_str15 = $fee_rs['str15'];
        $fee_str16 = $fee_rs['str16'];
        $fee_str17 = $fee_rs['str17'];
        $fee_str18 = $fee_rs['str18'];
        $fee_str19 = $fee_rs['str19'];
        $fee_str20 = $fee_rs['str20'];
        $fee_str21 = $fee_rs['str21'];
        $fee_str22 = $fee_rs['str22'];
        $fee_str23 = $fee_rs['str23'];
        $fee_str24 = $fee_rs['str24'];
        $fee_str25 = $fee_rs['str25'];
        $fee_str26 = $fee_rs['str26'];
        
        $fee_str27 = $fee_rs['str27'];
        $fee_str28 = $fee_rs['str28'];
        $fee_str29 = $fee_rs['str29'];
        
        $fee_str99 = $fee_rs['str99'];
        
        $a_money = $fee_rs['a_money'];
        $b_money = $fee_rs['b_money'];
        $user_add = $fee_rs['user_add'];
        $active_level = $fee_rs['active_level'];
        $every_money = $fee_rs['every_money'];
        $live_gupiao = $fee_rs['live_gupiao'];
        $award_num = $fee_rs['award_num'];
        $day_num = $fee_rs['day_num'];
        $month_num = $fee_rs['month_num'];
        $week_num = $fee_rs['week_num'];
        $money_count = $fee_rs['money_count'];
        $jiaquan1 = $fee_rs['jiaquan1'];
        $jiaquan2 = $fee_rs['jiaquan2'];
        $jiaquan3 = $fee_rs['jiaquan3'];
        $jicha = $fee_rs['jicha'];
        $kong_money = $fee_rs['kong_money'];
        $ssb = $fee_rs['ssb'];
        $close_tx_str = $fee_rs['close_tx_str'];
        $give_ssb = $fee_rs['give_ssb'];
        $every_money_ssb = $fee_rs['every_money_ssb'];
        $ssb_money = $fee_rs['ssb_money'];
        $all_ssb_money = $fee_rs['all_ssb_money'];
        $every_money_limit = $fee_rs['every_money_limit'];
        $zz_shouxu = $fee_rs['zz_shouxu'];
        $cz_shouxu = $fee_rs['cz_shouxu'];
        $dai_num = $fee_rs['dai_num'];
        $android_url = $fee_rs['android_url'];
        $ios_url = $fee_rs['ios_url'];
        $recommend_jingpai = $fee_rs['recommend_jingpai'];
        $recommend_num = $fee_rs['recommend_num'];
        $team_num = $fee_rs['team_num'];
        $recommend_pingji = $fee_rs['recommend_pingji'];
        $trade_bs = $fee_rs['trade_bs'];
        $limit_money = $fee_rs['limit_money'];
        $money_time1 = $fee_rs['money_time1'];
        $money_time2 = $fee_rs['money_time2'];
        $money_time3 = $fee_rs['money_time3'];
        $ssb_shouxu = $fee_rs['ssb_shouxu'];
        $dai_money = $fee_rs['dai_money'];
        $buy_point = $fee_rs['buy_point'];
        $video_url = $fee_rs['video_url'];
        $video_url2 = $fee_rs['video_url2'];
        $RegisterProtocol = $fee_rs['RegisterProtocol'];
        $price_str = $fee_rs['price_str'];
        $reg_futou_str = $fee_rs['reg_futou'];
        $jifen_time1_str = $fee_rs['jifen_time1'];
        $jifen_time2_str = $fee_rs['jifen_time2'];
        $open_level_str = $fee_rs['open_level'];
        $recommend_gupiao = $fee_rs['recommend_gupiao'];
        $recommend_order = $fee_rs['recommend_order'];
        $agent_use = $fee_rs['agent_use'];
        $extra_fenhong = $fee_rs['extra_fenhong'];
        $fenhong_max_day = $fee_rs['fenhong_max_day'];
        $is_goods = $fee_rs['is_goods'];
        $cjbs = $fee_rs['cjbs'];
        $jjbb = $fee_rs['jjbb'];
        $recharge_bs = $fee_rs['recharge_bs'];
        $this->assign('recharge_bs', $recharge_bs);
        $this->assign('cjbs', $cjbs);
        $this->assign('jjbb', $jjbb);
        $this->assign('fenhong_max_day', $fenhong_max_day);
        $this->assign('is_goods', $is_goods);
        $this->assign('extra_fenhong', $extra_fenhong);
        $this->assign('agent_use', $agent_use);
        $this->assign('recommend_order', $recommend_order);
        $this->assign('recommend_gupiao', $recommend_gupiao);
        $this->assign('open_level', $open_level_str);
        $this->assign('jifen_time1', $jifen_time1_str);
        $this->assign('jifen_time2', $jifen_time2_str);
        $this->assign('reg_futou', $reg_futou_str);
        $this->assign('price_str', $price_str);
        $this->assign('buy_point', $buy_point);
        $this->assign('video_url', $video_url);
        $this->assign('video_url2', $video_url2);
        $this->assign('RegisterProtocol', $RegisterProtocol);
        // $fee_s20 = explode('|',$fee_rs['s20']);
        $this->assign('fee_s1', $fee_s1);
        $this->assign('fee_s2', $fee_s2);
        $this->assign('fee_s3', $fee_s3);
        $this->assign('fee_s4', $fee_s4);
        $this->assign('fee_s5', $fee_s5);
        $this->assign('fee_s6', $fee_s6);
        $this->assign('fee_s7', $fee_s7);
        $this->assign('fee_s8', $fee_s8);
        $this->assign('fee_s9', $fee_s9);
        $this->assign('fee_s10', $fee_s10);
        $this->assign('fee_s11', $fee_s11);
        $this->assign('fee_s12', $fee_s12);
        $this->assign('fee_s13', $fee_s13);
        $this->assign('fee_s14', $fee_s14);
        $this->assign('fee_s15', $fee_s15);
        $this->assign('fee_s16', $fee_s16);
        $this->assign('fee_s17', $fee_s17);
        $this->assign('fee_s18', $fee_s18);
        // $this -> assign('fee_s20',$fee_s20);
        $this->assign('fee_i1', $fee_rs['i1']);
        $this->assign('fee_i2', $fee_rs['i2']);
        $this->assign('fee_i3', $fee_rs['i3']);
        $this->assign('fee_i4', $fee_rs['i4']);
        $this->assign('fee_i5', $fee_rs['i5']);
        $this->assign('fee_id', $fee_rs['id']); // 记录ID
        
        $this->assign('b_money', $fee_rs['b_money']);
        
        $this->assign('fee_str1', $fee_str1);
        $this->assign('fee_str2', $fee_str2);
        $this->assign('fee_str3', $fee_str3);
        $this->assign('fee_str4', $fee_str4);
        $this->assign('fee_str5', $fee_str5);
        $this->assign('fee_str6', $fee_str6);
        $this->assign('fee_str7', $fee_str7);
        $this->assign('fee_str9', $fee_str9);
        
        $this->assign('fee_str10', $fee_str10);
        $this->assign('fee_str11', $fee_str11);
        $this->assign('fee_str12', $fee_str12);
        $this->assign('fee_str13', $fee_str13);
        $this->assign('fee_str14', $fee_str14);
        $this->assign('fee_str15', $fee_str15);
        $this->assign('fee_str16', $fee_str16);
        
        $this->assign('fee_str17', $fee_str17);
        $this->assign('fee_str18', $fee_str18);
        $this->assign('fee_str19', $fee_str19);
        
        $this->assign('fee_str20', $fee_str20);
        $this->assign('fee_str21', $fee_str21);
        
        $this->assign('fee_str22', $fee_str22);
        $this->assign('fee_str23', $fee_str23);
        $this->assign('fee_str24', $fee_str24);
        $this->assign('fee_str25', $fee_str25);
        $this->assign('fee_str26', $fee_str26);
        
        $this->assign('fee_str27', $fee_str27);
        $this->assign('fee_str28', $fee_str28);
        $this->assign('fee_str29', $fee_str29);
        $this->assign('fee_str99', $fee_str99);
        
        $this->assign('a_money', $a_money);
        $this->assign('b_money', $b_money);
        $this->assign('user_add', $user_add);
        $this->assign('active_level', $active_level);
        $this->assign('every_money', $every_money);
        $this->assign('dai_money', $dai_money);
        $this->assign('live_gupiao', $live_gupiao);
        $this->assign('award_num', $award_num);
        $this->assign('day_num', $day_num);
        $this->assign('month_num', $month_num);
        $this->assign('week_num', $week_num);
        $this->assign('money_count', $money_count);
        $this->assign('jiaquan1', $jiaquan1);
        $this->assign('jiaquan2', $jiaquan2);
        $this->assign('jiaquan3', $jiaquan3);
        $this->assign('jicha', $jicha);
        $this->assign('kong_money', $kong_money);
        $this->assign('ssb', $ssb);
        $this->assign('close_tx_str', $close_tx_str);
        $this->assign('give_ssb', $give_ssb);
        $this->assign('every_money_ssb', $every_money_ssb);
        $this->assign('ssb_money', $ssb_money);
        $this->assign('all_ssb_money', $all_ssb_money);
        $this->assign('ssb_shouxu', $ssb_shouxu);
        $this->assign('every_money_limit', $every_money_limit);
        $this->assign('zz_shouxu', $zz_shouxu);
        $this->assign('cz_shouxu', $cz_shouxu);
        $this->assign('money_time3', $money_time3);
        $this->assign('money_time2', $money_time2);
        $this->assign('money_time1', $money_time1);
        $this->assign('dai_num', $dai_num);
        $this->assign('android_url', $android_url);
        $this->assign('ios_url', $ios_url);
        $this->assign('recommend_jingpai', $recommend_jingpai);
        $this->assign('recommend_num', $recommend_num);
        $this->assign('team_num', $team_num);
        $this->assign('recommend_pingji', $recommend_pingji);
        $this->assign('trade_bs', $trade_bs);
        $this->assign('limit_money', $limit_money);
        
        $str11 = array(
            '星期日',
            '星期一',
            '星期二',
            '星期三',
            '星期四',
            '星期五',
            '星期六'
        );
        $this->assign('weekname', $str11);
        
        if ($_POST['is_mobile'] == 1) {
            
            $userbank = explode('|', $fee_rs['userbank']);
            foreach ($userbank as $k => $item2) {
                $item1 = array();
                $item1['label'] = $item2;
                $item1['value'] = $k + 1;
                $item1['text'] = $item2;
                $item1['id'] = $item2;
                $userbank[$k] = $item1;
            }
            
            $fee_rs['RegisterProtocol'] = htmlspecialchars_decode($fee_rs['RegisterProtocol']);
            $_SESSION['number'] = 'No.8000';
            $user_number = $_SESSION['number'] . get_new_user_number();
            $fee_rs['user_number'] = $user_number;
            
            $wenti_array = array();
            $wenti = explode('|', $fee_rs['str24']);
            foreach ($wenti as $k => $item) {
                $item1 = array();
                $item1['label'] = $item;
                $item1['value'] = $item;
                $wenti_array[] = $item1;
            }
            $fee_rs['wenti'] = $wenti_array;
            
            $payment_array = array();
            $payment = explode('|', C('payment'));
            foreach ($payment as $k => $item) {
                $item1 = array();
                $item1['label'] = $item;
                $item1['value'] = $k;
                $item1['id'] = $k;
                $payment_array[] = $item1;
            }
            $fee_rs['payment'] = $payment_array;
            
            $open_level = explode('|', $fee_rs['open_level']);
            
            $s9 = explode('|', $fee_rs['s9']);
            $s10 = explode('|', $fee_rs['s10']);
            $Level_name = array();
            $Level_money = array();
            $Level = array();
            foreach ($s9 as $key => $item) {
                
                $item = array();
                $txt = '';
                if ($key < 4) {
                    $txt = '【套餐】';
                }
                
                $item['label'] = $s9[$key] . $txt;
                $item['value'] = $s9[$key] . $txt;
                $item['id'] = $key + 1;
                
                $Level_name[] = $item;
                $item1 = array();
                $item1['label'] = $s10[$key] . '【' . $s9[$key] . '】';
                // $item1['value'] = $s10[$key];
                $item1['value'] = $key + 1;
                $item1['id'] = $key + 1;
                $item1['money'] = $s9[$key];
                $Level_money[] = $item1;
                $Level[] = $s10[$key];
            }
            
            $this->assign('level', $Level_money);
            $this->assign('s9', $Level_name);
            $this->assign('s10', $Level_money);
            
            $fee_rs['s9'] = $Level_money;
            $fee_rs['s10'] = $Level_money;
            $fee_rs['level'] = $Level;
            
            $bank = explode('|', $fee_rs['str29']);
            $fee_rs['bank'] = $bank;
            foreach ($bank as $k => $item) {
                $item1 = array();
                $item1['label'] = $item;
                $item1['value'] = $item;
                $item1['id'] = $item;
                $bank[$k] = $item1;
            }
            $fee_rs['bank_list'] = $bank;
            
            // 生成随机号码
            $arr = array(
                130,
                131,
                132,
                133,
                134,
                135,
                136,
                137,
                138,
                139,
                144,
                147,
                150,
                151,
                152,
                153,
                155,
                156,
                157,
                158,
                159,
                176,
                177,
                178,
                180,
                181,
                182,
                183,
                184,
                185,
                186,
                187,
                188,
                189
            );
            
            $fee_rs['username'] = $arr[array_rand($arr)] . '' . mt_rand(1000, 9999) . '' . mt_rand(1000, 9999);
            $fee_rs['mobile'] = $arr[array_rand($arr)] . '' . mt_rand(1000, 9999) . '' . mt_rand(1000, 9999);
            $fee_rs['email'] = $arr[array_rand($arr)] . '' . mt_rand(1000, 9999) . '' . mt_rand(1000, 9999) . '@qq.com';
            
            $ssb = M('fck')->sum('ssb');
            
            // $fee_rs['all_ssb_money'] = num_str1($fee_rs['all_ssb_money']);
            // $fee_rs['all_ssb'] = num_str($ssb);
            $fee_rs['market_max_price'] = ($fee_rs['ssb_money']);
            $fee_rs['market_min_price'] = 0.35;
            
            $fee_rs['re_share_sub_title'] = C('re_share_sub_title');
            $fee_rs['re_share_title'] = C('re_share_title');
            
            $slider = ARRAY();
            $slider[0]['img'] = __ROOT__ . '/Public/Images/slides/1.jpg';
            $slider[1]['img'] = __ROOT__ . '/Public/Images/slides/2.jpg';
            
            $data = array();
            $data['slider'] = $slider;
            $data['data'] = $fee_rs;
            $data['userbank'] = $userbank;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display('setParameter');
            // }else{
            // $this->error('错误!');
            // exit;
            // }
        }
    }

    public function setParameterSave()
    {
        $fee = M('fee');
        $fck = M('fck');
        // var_dump($this->_post());
        $rs = $fee->find();
        
        $i1 = $_POST['i1'];
        $i2 = $_POST['i2'];
        $i3 = $_POST['i3'];
        $i4 = $_POST['i4'];
        $a_money = $_POST['a_money'];
        $b_money = $_POST['b_money'];
        
        $where = array();
        $where['id'] = 1;
        $data = array();
        if (empty($a_money) == false || strlen($a_money) > 0) {
            $data['a_money'] = trim($a_money);
        }
        if (empty($b_money) == false || strlen($b_money) > 0) {
            $data['b_money'] = trim($b_money);
        }
        
        for ($j = 1; $j <= 10; $j ++) {
            $arr_rs[$j] = $_POST['i' . $j];
        }
        
        $s_sql2 = "";
        for ($j = 1; $j <= 10; $j ++) {
            if ($arr_rs[$j] != '') {
                if (empty($s_sql2)) {
                    $s_sql2 = 'i' . $j . "='{$arr_rs[$j]}'";
                } else {
                    $s_sql2 .= ',i' . $j . "='{$arr_rs[$j]}'";
                }
            }
        }
        
        for ($i = 1; $i <= 35; $i ++) {
            $arr_s[$i] = $_POST['s' . $i];
        }
        
        $s_sql = "";
        for ($i = 1; $i <= 35; $i ++) {
            if (empty($arr_s[$i]) == false || strlen($arr_s[$i]) > 0) {
                if (empty($s_sql)) {
                    $s_sql = 's' . $i . "='{$arr_s[$i]}'";
                } else {
                    $s_sql .= ',s' . $i . "='{$arr_s[$i]}'";
                }
            }
        }
        
        for ($i = 1; $i <= 40; $i ++) {
            $arr_sts[$i] = $_POST['str' . $i];
        }
        $str_sql = "";
        for ($i = 1; $i <= 40; $i ++) {
            if (strlen(trim($arr_sts[$i])) > 0) {
                if (empty($s_sql2) && empty($s_sql)) {
                    $str_sql = 'str' . $i . "='{$arr_sts[$i]}'";
                } else {
                    $str_sql .= ',str' . $i . "='{$arr_sts[$i]}'";
                }
            }
        }
        
        $str99 = trim($_POST['str99']);
        $ttst_sql .= ',str99="' . $str99 . '"';
        
        $user_add = trim($_POST['user_add']);
        $ttst_sql .= ',user_add="' . $user_add . '"';
        $active_level = trim($_POST['active_level']);
        $ttst_sql .= ',active_level="' . $active_level . '"';
        $buy_point = trim($_POST['buy_point']);
        $buy_point_sql .= ',buy_point="' . $buy_point . '"';
        $video_url = trim($_POST['video_url']);
        $video_url2 = trim($_POST['video_url2']);
        $video_url_sql .= ',video_url="' . $video_url . '",video_url2="' . $video_url2 . '"';
        
        $RegisterProtocol = trim($_POST['RegisterProtocol']);
        // $RegisterProtocol_sql .= ',RegisterProtocol="' . $RegisterProtocol . '"';
        $price_str = trim($_POST['price_str']);
        $price_str_sql .= ',price_str="' . $price_str . '"';
        $reg_futou = trim($_POST['reg_futou']);
        $reg_futou_sql .= ',reg_futou="' . $reg_futou . '"';
        $jifen_time1 = trim($_POST['jifen_time1']);
        $jifen_time1_sql .= ',jifen_time1="' . $jifen_time1 . '"';
        $jifen_time2 = trim($_POST['jifen_time2']);
        $jifen_time2_sql .= ',jifen_time2="' . $jifen_time2 . '"';
        $open_level = trim($_POST['open_level']);
        $every_money = trim($_POST['every_money']);
        $dai_money = trim($_POST['dai_money']);
        $live_gupiao = trim($_POST['live_gupiao']);
        $week_num = trim($_POST['week_num']);
        $month_num = trim($_POST['month_num']);
        $day_num = trim($_POST['day_num']);
        $award_num = trim($_POST['award_num']);
        $money_count = trim($_POST['money_count']);
        $jiaquan1 = trim($_POST['jiaquan1']);
        $jiaquan2 = trim($_POST['jiaquan2']);
        $jiaquan3 = trim($_POST['jiaquan3']);
        $jicha = trim($_POST['jicha']);
        $recommend_gupiao = trim($_POST['recommend_gupiao']);
        $recommend_order = trim($_POST['recommend_order']);
        $agent_use = trim($_POST['agent_use']);
        $extra_fenhong = trim($_POST['extra_fenhong']);
        $kong_money = trim($_POST['kong_money']);
        $fenhong_max_day = trim($_POST['fenhong_max_day']);
        $is_goods = trim($_POST['is_goods']);
        $ssb = trim($_POST['ssb']);
        $cjbs = trim($_POST['cjbs']);
        $jjbb = trim($_POST['jjbb']);
        $recharge_bs = trim($_POST['recharge_bs']);
        $close_tx_str = trim($_POST['close_tx_str']);
        $give_ssb = trim($_POST['give_ssb']);
        $every_money_ssb = trim($_POST['every_money_ssb']);
        $ssb_money = trim($_POST['ssb_money']);
        $all_ssb_money = trim($_POST['all_ssb_money']);
        $ssb_shouxu = trim($_POST['ssb_shouxu']);
        $every_money_limit = trim($_POST['every_money_limit']);
        $zz_shouxu = trim($_POST['zz_shouxu']);
        $cz_shouxu = trim($_POST['cz_shouxu']);
        $money_time1 = trim($_POST['money_time1']);
        $money_time2 = trim($_POST['money_time2']);
        $dai_num = trim($_POST['dai_num']);
        $android_url = trim($_POST['android_url']);
        $ios_url = trim($_POST['ios_url']);
        $recommend_jingpai = trim($_POST['recommend_jingpai']);
        $s14 = trim($_POST['s14']);
        $recommend_num = trim($_POST['recommend_num']);
        $team_num = trim($_POST['team_num']);
        $recommend_pingji = trim($_POST['recommend_pingji']);
        $trade_bs = trim($_POST['trade_bs']);
        $money_time3 = trim($_POST['money_time3']);
        $limit_money = trim($_POST['limit_money']);
        $open_level_sql .= ',limit_money="' . $limit_money . '",money_time3="' . $money_time3 . '",trade_bs="' . $trade_bs . '",recommend_pingji="' . $recommend_pingji . '",team_num="' . $team_num . '",recommend_num="' . $recommend_num . '",recommend_jingpai="' . $recommend_jingpai . '",s14="' . $s14 . '",android_url="' . $android_url . '",ios_url="' . $ios_url . '",open_level="' . $open_level . '",every_money="' . $every_money . '",dai_money="' . $dai_money . '",live_gupiao="' . $live_gupiao . '"';
        $open_level_sql .= ',dai_num="' . $dai_num . '",money_time1="' . $money_time1 . '",money_time2="' . $money_time2 . '",cz_shouxu="' . $cz_shouxu . '",zz_shouxu="' . $zz_shouxu . '",every_money_limit="' . $every_money_limit . '",ssb_shouxu="' . $ssb_shouxu . '",all_ssb_money="' . $all_ssb_money . '",ssb_money="' . $ssb_money . '",every_money_ssb="' . $every_money_ssb . '",give_ssb="' . $give_ssb . '",close_tx_str="' . $close_tx_str . '",recharge_bs="' . $recharge_bs . '",jjbb="' . $jjbb . '",cjbs="' . $cjbs . '",is_goods="' . $is_goods . '",ssb="' . $ssb . '",fenhong_max_day="' . $fenhong_max_day . '",kong_money="' . $kong_money . '",extra_fenhong="' . $extra_fenhong . '",agent_use="' . $agent_use . '",recommend_order="' . $recommend_order . '",recommend_gupiao="' . $recommend_gupiao . '",jicha="' . $jicha . '",jiaquan1="' . $jiaquan1 . '",jiaquan2="' . $jiaquan2 . '",jiaquan3="' . $jiaquan3 . '",money_count="' . $money_count . '",award_num="' . $award_num . '",day_num="' . $day_num . '",month_num="' . $month_num . '",week_num="' . $week_num . '"';
        
        $data['RegisterProtocol'] = $RegisterProtocol;
        $fee->execute("update __TABLE__ SET " . $s_sql2 . "," . $s_sql . $str_sql . $ttst_sql . $buy_point_sql . $video_url_sql . $price_str_sql . $reg_futou_sql . $jifen_time1_sql . $jifen_time2_sql . $open_level_sql . $bank_sql . "  where `id`=1");
        $fee->where($where)
            ->data($data)
            ->save();
        
        // echo $fee->_sql();die();
        $this->success('修改成功！');
        exit();
    }

    // 参数设置
    public function setParameter_B()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssPingGuoCPB') {
            $fee = M('fee');
            $fee_rs = $fee->find();
            
            $fee_str21 = $fee_rs['str21'];
            $fee_str22 = $fee_rs['str22'];
            $fee_str23 = $fee_rs['str23'];
            
            $this->assign('fee_str21', $fee_str21);
            $this->assign('fee_str22', $fee_str22);
            $this->assign('fee_str23', $fee_str23);
            
            $this->display();
        } else {
            $this->error('错误!');
            exit();
        }
    }

    public function setParameterSave_B()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssPingGuoCPB') {
            $fee = M('fee');
            $fck = M('fck');
            $rs = $fee->find();
            
            $where = array();
            $where['id'] = (int) $_POST['id'];
            for ($i = 1; $i <= 40; $i ++) {
                $arr_sts[$i] = $_POST['str' . $i];
            }
            $str_sql = "";
            for ($i = 1; $i <= 40; $i ++) {
                if (strlen(trim($arr_sts[$i])) > 0) {
                    if (empty($str_sql)) {
                        $str_sql = 'str' . $i . "='{$arr_sts[$i]}'";
                    } else {
                        $str_sql .= ',str' . $i . "='{$arr_sts[$i]}'";
                    }
                }
            }
            
            $fee->execute("update __TABLE__ SET " . $str_sql . "  where `id`=1");
            $this->success('首页图片设置！');
            exit();
        } else {
            $this->error('错误!');
            exit();
        }
    }

    public function MenberBonus()
    {
        // 列表过滤器，生成查询Map对象
        if ($_SESSION['UrlPTPass'] == 'MyssPingGuoCP') {
            $fck = M('fck');
            $UserID = trim($_REQUEST['UserID']);
            $ss_type = (int) $_REQUEST['type'];
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
            $map['is_pay'] = 1;
            // 查询字段
            $field = 'id,user_id,nickname,bank_name,bank_card,user_name,user_address,user_tel,rdt,f4,cpzj,pdt,u_level,zjj,agent_use,is_lock,f3,b3';
            // =====================分页开始==============================================
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $fck->where($map)->count(); // 总页数
            $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
            $page_where = 'UserID=' . $UserID . '&type=' . $ss_type; // 分页条件
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
            foreach ($list as $vo) {
                $voo[$vo['id']] = $HYJJ[$vo['u_level']];
            }
            $this->assign('voo', $voo); // 会员级别
            $this->assign('list', $list); // 数据输出到模板
                                          // =================================================
            
            $title = '会员管理';
            $this->assign('title', $title);
            $this->display('MenberBonus');
            return;
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function MenberBonusSave()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssPingGuoCP') {
            $fck = M('fck');
            $fee_rs = M('fee')->find();
            $fee_s7 = explode('|', $fee_rs['s7']);
            
            $date = strtotime($_POST['date']);
            $lz = (int) $_POST['lz'];
            $lzbz = $_POST['lzbz'];
            
            $userautoid = (int) $_POST['userautoid'];
            
            if ($lz <= 0) {
                $this->error('请录入正确的劳资金额!');
                exit();
            }
            
            $rs = $fck->field('user_id,id')->find($userautoid);
            if ($rs) {
                $fck->query("update __TABLE__ set b3=b3+$lz where id=" . $userautoid);
                $this->input_bonus_2($rs['user_id'], $rs['id'], $fee_s7[2], $lz, $lzbz, $date); // 写进明细
                
                $bUrl = __URL__ . '/MenberBonus';
                $this->_box(1, '劳资录入！', $bUrl, 1);
            } else {
                $this->error('数据错误!');
                exit();
            }
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function delTable()
    {
        // 清空数据库===========================
        $this->display();
    }

    public function delTableExe()
    {
        
        
        $fck = M('fck');
        if (! $fck->autoCheckToken($_POST)) {
            $this->error('页面过期，请刷新页面！');
            exit();
        }
        unset($fck);
        $this->_delTable();
        exit();
    }

    public function udate_tree()
    {
        $fee = M('fee');
        $fee_rs = $fee->field('s4,str1,s9,s10')->find(1);
        $fck = D('Fck');
        $list = $fck->where('id>0')
            ->field('id,f4,p_path,user_id')
            ->select();
        foreach ($list as $key => $vo) {
            $fck->xiangJiao($vo['id'], $vo['f4']);
            $fck->test_duipeng($vo['p_path'], $vo['user_id'], $fee_rs);
            $fck->where('id=' . $vo['id'])->setField('is_update', 1);
        }
        $this->success("更新成功！");
        exit();
    }

    function checkstr($str, $needle)
    {
        $tmparray = explode($needle, $str);
        if (count($tmparray) > 1) {
            return true;
        } else {
            return false;
        }
    }

    public function change_tree_edit()
    {
        
        // 获取账号
        $fck = M('fck');
        $member_id = $_POST['member_id'];
        $recommend_member_id = $_POST['recommend_member_id'];
        $TPL = $_POST['TPL'];
        
        $fck_rs = $fck->where('user_id="' . $member_id . '"')->find();
        
        $recommend_fck_rs = $fck->where('user_id="' . $recommend_member_id . '"')->find();
        if ($fck_rs == null) {
            
            $this->error('账号不存在！');
            exit();
        }
        
        if ($recommend_fck_rs == null) {
            
            $this->error('服务编号不存在！');
            exit();
        }
        if ($recommend_fck_rs == $fck_rs) {
            
            $this->error('不能填写本人！');
            exit();
        }
        if ($this->checkstr($recommend_fck_rs['p_path'], $fck_rs['id'])) {
            
            $this->error('非法操作！');
            exit();
        }
        $where = array();
        $where['father_id'] = $recommend_fck_rs['id'];
        $where['treeplace'] = $TPL;
        $rs = $fck->where($where)
            ->field('id')
            ->find();
        if ($rs) {
            // $this->error('該位置已經註冊！');
            // exit();
        }
        
        // 統計單數
        $fck = D('Fck');
        
        if ($fck_rs['u_level'] == 1) {
            $fck_rs['f4'] = 1;
        } else if ($fck_rs['u_level'] == 2) {
            $fck_rs['f4'] = 3;
        } else if ($fck_rs['u_level'] == 3) {
            $fck_rs['f4'] = 5;
        } else if ($fck_rs['u_level'] == 4) {
            $fck_rs['f4'] = 10;
        } else if ($fck_rs['u_level'] == 5) {
            $fck_rs['f4'] = 30;
        } else if ($fck_rs['u_level'] == 6) {
            $fck_rs['f4'] = 50;
        } else if ($fck_rs['u_level'] == 7) {
            $fck_rs['f4'] = 100;
        }
        if ($fck_rs['is_pay'] == 2) {
            $fck_rs['f4'] = 0;
        }
        
        $fck->xiangJiao_tree($fck_rs['id'], $fck_rs['f4'] + ($fck_rs['l'] + $fck_rs['r']));
        
        $recommend_id1 = $fck_rs['father_id'];
        $this->usersSave($recommend_fck_rs, $fck_rs, 1, $TPL);
        
        $fee = M('fee')->find();
        $re_sql = $fee['tree_sql'];
        $recommend_sql = $re_sql;
        $recommend_sql = str_replace("inPid", $fck_rs['id'], $recommend_sql);
        $M = M();
        
        // 获取下线
        $z_tree = $M->query($recommend_sql);
        
        for ($i = 0; $i < count($z_tree); $i ++) {
            if ($z_tree[$i]['id'] != $fck_rs['id']) {
                $fck_rs = $fck->where('id=' . $z_tree[$i]['id'])->find();
                $recommend_fck_rs = $fck->where('id=' . $z_tree[$i]['pid'])->find();
                if ($recommend_fck_rs != null) {
                    $this->usersSave($recommend_fck_rs, $fck_rs, 1, $fck_rs['treeplace']);
                }
            }
        }
        
        $fck = M('fck');
        $fck_rs = $fck->where('user_id="' . $member_id . '"')->find();
        $fck = D('Fck');
        // 統計單數
        if ($fck_rs['is_pay'] == 2) {
            $fck_rs['f4'] = 0;
        }
        if ($fck_rs['u_level'] == 1) {
            $fck_rs['f4'] = 1;
        } else if ($fck_rs['u_level'] == 2) {
            $fck_rs['f4'] = 3;
        } else if ($fck_rs['u_level'] == 3) {
            $fck_rs['f4'] = 5;
        } else if ($fck_rs['u_level'] == 4) {
            $fck_rs['f4'] = 10;
        } else if ($fck_rs['u_level'] == 5) {
            $fck_rs['f4'] = 30;
        } else if ($fck_rs['u_level'] == 6) {
            $fck_rs['f4'] = 50;
        } else if ($fck_rs['u_level'] == 7) {
            $fck_rs['f4'] = 100;
        }
        
        $fck->xiangJiao_tree2($fck_rs['id'], $fck_rs['f4'] + ($fck_rs['l'] + $fck_rs['r']));
        $this->success("正在修改！");
        exit();
    }

    /**
     * 註冊處理
     * *
     */
    public function usersSave($recommend, $fck_rs, $type, $TPL)
    {
        $fck = M('fck'); // 註冊表
        $rs = $recommend;
        $m = $rs['agent_cash'];
        if ($rs['is_pay'] == 0) {
            // $this->error('臨時會員不能註冊會員！');
            // exit();
        }
        if (strlen($fck_rs['user_id']) < 1) {
            $this->error('會員編號不能少！');
            exit();
        }
        $data = $fck_rs; // 創建數據對像
                         // 檢測體驗中心
        if ($type == 0) {
            // 檢測推薦人
            $RID = trim($rs['user_id']); // 獲取推薦會員帳號
            $mapp = array();
            $mapp['user_id'] = $RID;
            $mapp['is_pay'] = array(
                'gt',
                0
            );
            
            $authInfoo = $recommend;
            if ($authInfoo) {
                $data['re_path'] = $authInfoo['re_path'] . $authInfoo['id'] . ','; // 推薦路徑
                $data['re_id'] = $authInfoo['id']; // 推薦人ID
                $data['re_name'] = $authInfoo['user_id']; // 推薦人帳號
                $data['re_level'] = $authInfoo['re_level'] + 1; // 代數(絕對層數)
            } else {
                $this->error('推薦人不存在！');
                exit();
            }
            unset($authInfoo, $mapp);
        } else {
            // 檢測上節點人
            $FID = trim($rs['user_id']); // 上節點帳號
            $mappp = array();
            $mappp['user_id'] = $FID;
            // $mappp['is_pay'] = array('gt',0);
            $authInfoo = $recommend;
            if ($authInfoo) {
                
                $fatherispay = $authInfoo['is_pay'];
                $data['p_path'] = $authInfoo['p_path'] . $authInfoo['id'] . ','; // 絕對路徑
                
                $data['father_id'] = $authInfoo['id']; // 上節點ID
                $data['father_name'] = $authInfoo['user_id']; // 上節點帳號
                $data['p_level'] = $authInfoo['p_level'] + 1; // 上節點ID
                $tp_path = $authInfoo['tp_path'];
            } else {
                $this->error('上級會員不存在！');
                exit();
            }
            unset($authInfoo, $mappp);
            
            $where = array();
            $where['father_id'] = $data['father_id'];
            $where['treeplace'] = $TPL;
            $rs = $fck->where($where)
                ->field('id')
                ->find();
            
            $data['treeplace'] = $TPL;
            if (strlen($tp_path) == 0) {
                $data['tp_path'] = $TPL;
            } else {
                $data['tp_path'] = $tp_path . "," . $TPL;
            }
            
            if ($fatherispay == 0 && $TPL > 0) {
                // $this->error('接點人開通後才能在此位置註冊！');
                // exit();
            }
        }
        $result = $fck->save($data); // 統計單數
        
        unset($data, $fck);
        if ($result) {} else {}
    }

    public function adminClearing()
    {
        $times = M('times');
        $trs = $times->where('type=0')
            ->order('id desc')
            ->find();
        if (! $trs) {
            $trs['benqi'] = strtotime('2010-01-01');
        }
        if ($trs['benqi'] == strtotime(date("Y-m-d"))) {
            $isPay = 1;
        } else {
            $isPay = 0;
        }
        $this->assign('is_pay', $isPay);
        $this->assign('trs', $trs);
        
        $fee = M('fee');
        $fee_rs = $fee->field('a_money,b_money')->find();
        $a_money = $fee_rs['a_money'];
        $this->assign('a_money', $a_money);
        $b_money = $fee_rs['b_money'];
        $this->assign('b_money', $b_money);
        
        $this->display();
    }

    public function adminzengdian()
    { // 新增顶点
        set_time_limit(0);
        $pwid = $this->_get('pwid');
        
        $fck = M('fck');
        $where['id'] = array(
            'eq',
            $pwid
        );
        $where['is_aa'] = array(
            'eq',
            0
        );
        $where['is_lockqd'] = array(
            'eq',
            0
        );
        $rs = $fck->where($where)->setField('is_aa', '1');
        if ($rs) {
            $rs = $this->pd_into_websiteb($pwid);
            $this->ajaxSuccess('新增顶点完成！');
            exit();
        } else {
            $this->ajaxError('新增顶点失败！');
            exit();
        }
    }

    public function adminpaiwang()
    { // 资金结算
        set_time_limit(0);
        $fck = D('Fck');
        $pwid = $this->_get('pwid');
        // 结算分红
        $fck->gongpaixtsmalldd($pwid);
        
        sleep(1);
        $this->ajaxSuccess('排网完成！');
        exit();
    }

    public function adminClearingSave()
    { // 资金结算
        set_time_limit(0); // 是页面不过期
        $times = M('times');
        $fck = D('Fck');
        $ydate = mktime();
        $user_id = $_POST['user_id'];
        $is_mobile = $_POST['is_mobile'];
        
        if ($is_mobile == 1) {
            $user = M('fck')->where('id=' . $user_id)->find();
            $fee = M('fee');
            $fee_rs = $fee->find(1);
            
            $timediff = timediff($user['fanli_time'], time());
            $count = $timediff['day'];
            if ($count == 0) {
                $this->error('您已签到,不能签到！');
                exit();
            }
            
            $str29 = M('fee')->where('id=1')->getField('fenhong_max_day');
            
            $fenhong_max_day = explode('|', $str29);
            if ($user['u_level'] == 0) {
                $fenhong_max_day = $fenhong_max_day[0];
            } else {
                $fenhong_max_day = $fenhong_max_day[$user['u_level'] - 1];
            }
            $fanli_time = $user['fanli_time'];
            $begin_time = $fanli_time;
            if ($fanli_time < $user['pdt']) {
                $pdt = date('Y-m-d', $user['pdt']);
                
                $fanli_time = strtotime($pdt);
                $begin_time = $fanli_time;
            }
            
            $begin_time_str = date('Y-m-d H:i:s', $fanli_time);
            
            $end_time = time();
            $end_time_str = date('Y-m-d H:i:s');
            
            $signtime = M('fee')->where('id=1')->getField('signtime');
            $sign_time_str = date('Y-m-d') . ' ' . $signtime;
            $sign_time = strtotime($sign_time_str);
            
            if ($sign_time > $end_time) {
                $this->error('签到时间未到,不能签到！');
                exit();
            }
            $point = 0;
            FOR ($i = 0; $i <= 1; $i ++) {
                $fck->mr_fenhong(1, date('Y-m-d', strtotime($end_time_str . "+" . 0 . " day")), $user_id, $point);
            }
            
            $user = M('fck')->where('id=' . $user_id)->find();
            
            $authInfo = get_user_info($user, $user['id']);
            
            $data = array();
            $data['msg'] = '签到成功！';
            $data['info'] = '签到成功,获得' . $point . '佣金金币';
            $data['point'] = $point;
            $data['user'] = $authInfo;
            $data['status'] = 1;
            $this->ajaxReturn($data);
            // $this->success('签到成功！', '', true);
        } else {
            // 结算分红
            $fck->mr_fenhong(1);
        }
        sleep(1);
        if ($_POST['is_mobile'] == 1) {} else {
            $this->success('积分结算完成！');
        }
        exit();
    }

    public function adminsingle($GPid = 0)
    {
        // ============================================审核会员加单
        if ($_SESSION['UrlPTPass'] == 'MyssGuansingle') {
            $jiadan = M('jiadan');
            $UserID = $_POST['UserID'];
            if (! empty($UserID)) {
                $map['user_id'] = array(
                    'like',
                    "%" . $UserID . "%"
                );
            }
            
            $field = '*';
            // =====================分页开始==============================================
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $jiadan->where($map)->count(); // 总页数
            $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
            $page_where = 'UserID=' . $UserID; // 分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $list = $jiadan->where($map)
                ->field($field)
                ->order('id desc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            $this->assign('list', $list); // 数据输出到模板
                                          // =================================================
            
            $this->display('adminsingle');
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function adminsingleAC()
    {
        // 处理提交按钮
        $fck = M('fck');
        $action = $_POST['action'];
        // 获取复选框的值
        $PTid = $_POST['tabledb'];
        if (! $fck->autoCheckToken($_POST)) {
            $this->error('页面过期，请刷新页面！');
            exit();
        }
        if (! isset($PTid) || empty($PTid)) {
            $bUrl = __URL__ . '/adminsingle';
            $this->_box(0, '请选择！', $bUrl, 1);
            exit();
        }
        unset($fck);
        switch ($action) {
            case '确认':
                $this->_adminsingleConfirm($PTid);
                break;
            case '删除':
                $this->_adminsingleDel($PTid);
                break;
            default:
                $bUrl = __URL__ . '/adminsingle';
                $this->_box(0, '没有该注册！', $bUrl, 1);
                break;
        }
    }

    private function _adminsingleConfirm($PTid = 0)
    {
        // ===============================================确认加单
        if ($_SESSION['UrlPTPass'] == 'MyssGuansingle') {
            $fck = D('Fck');
            $jiadan = M('jiadan');
            $fee = M('fee');
            $fee_rs = $fee->find(1);
            $where = array();
            $where['id'] = array(
                'in',
                $PTid
            );
            $where['is_pay'] = 0;
            $field = '*';
            $vo = $jiadan->where($where)
                ->field($field)
                ->select();
            $fck_where = array();
            $nowdate = strtotime(date('c'));
            foreach ($vo as $voo) {
                $fck->xiangJiao($voo['uid'], $voo['danshu']); // 统计单数
                $fck_where['id'] = $voo['uid'];
                $fck_rs = $fck->where($fck_where)
                    ->field('user_id,re_id,f5')
                    ->find();
                if ($fck_rs) {
                    // 给分享人添加分享人数
                    $fck->query("update `xt_fck` set `re_nums`=re_nums+" . $voo['danshu'] . " where `id`=" . $fck_rs['re_id']);
                    $fck->upLevel($fck_rs['re_id']); // 晋级
                }
                $fck->userLevel($voo['uid'], $voo['danshu']); // 自己晋级
                                                              
                // 加上单数到自身认购字段
                $money = 0;
                $money = $fee_rs['uf1'] * $voo['danshu']; // 金额
                $fck->xsjOne($fck_rs['re_id'], $fck_rs['user_id'], $money, $fck_rs['f5']); // 销售奖第一部分中的第二部分
                $fck->query("update `xt_fck` set `f4`=f4+" . $voo['danshu'] . ",`cpzj`=cpzj+" . $money . " where `id`=" . $voo['uid']);
                // 改变状态
                $jiadan->query("UPDATE `xt_jiadan` SET `pdt`=$nowdate,`is_pay`=1 where `id`=" . $voo['id']);
            }
            unset($jiadan, $where, $field, $vo, $fck, $fck_where);
            $bUrl = __URL__ . '/adminsingle';
            $this->_box(1, '确认！', $bUrl, 1);
        } else {
            $this->error('错误！');
            exit();
        }
    }

    private function _adminsingleDel($PTid = 0)
    {
        // ====================================删除加单
        if ($_SESSION['UrlPTPass'] == 'MyssGuansingle') {
            $jdan = M('jiadan');
            // $fck->query("UPDATE `xt_fck` SET `single_ispay`=0,`single_money`=0 where `ID` in (".$PTid.")");
            $jwhere['id'] = array(
                'in',
                $PTid
            );
            $jwhere['is_pay'] = 0;
            $jdan->where($jwhere)->delete();
            $bUrl = __URL__ . '/adminsingle';
            $this->_box(1, '删除！', $bUrl, 1);
            exit();
        } else {
            $this->error('错误!');
        }
    }

    private function _delTableBonus()
    {
        if ($_SESSION['UrlPTPass'] == 'MyssQingKong') {
            // 删除指定记录
            // $name=$this->getActionName();
            $model = M('fck');
            $model2 = M('bonus');
            $model3 = M('history');
            $model4 = M('bonushistory');
            $model5 = M('times');
            $model6 = M('cash');
            
            $sql = "`agent_cash`=0,`zjj`=0";
            $model->execute("UPDATE __TABLE__ SET " . $sql);
            $model6->execute("UPDATE __TABLE__ SET x1=0");
            
            $model2->where('id > 0')->delete();
            $model3->where('id > 0')->delete();
            $model4->where('id > 0')->delete();
            $model5->where('id > 0')->delete();
            
            $bUrl = __URL__ . '/delTable';
            $this->_box(1, '部分清空数据完成！', $bUrl, 1);
            exit();
        } else {
            $bUrl = __URL__ . '/delTable';
            $this->_box(0, '清空数据失败！', $bUrl, 1);
            exit();
        }
    }

    private function _delTable()
    {
        // 删除指定记录
        // $name=$this->getActionName();
        $model = M('fck');
        $model2 = M('bonus');
        $model3 = M('history');
        $model4 = M('msg');
        $model5 = M('times');
        $model6 = M('tiqu');
        $model7 = M('zhuanj');
        $model8 = M('shop');
        $model9 = M('jiadan');
        $model10 = M('chongzhi');
        $model11 = M('region');
        $model12 = M('orders');
        $model13 = M('huikui');
        // $model14 = M ('product');
        $model15 = M('gouwu');
        $model16 = M('xiaof');
        $model17 = M('promo');
        $model18 = M('fenhong');
        $model19 = M('peng');
        $model20 = M('ulevel');
        $model21 = M('address');
        $model22 = M('shouru');
        $model23 = M('remit');
        $model24 = M('cash');
        $model25 = M('xfhistory');
        $model26 = M('game');
        $model27 = M('gupiao');
        $model28 = M('hgupiao');
        $model29 = M('gp_sell');
        
        $model30 = M('gp');
        $model31 = M('blist');
        $model32 = M('cashhistory');
        $model33 = M('bonushistory');
        $model34 = M('cashpp');
        $model35 = M('t_trans');
        $model36 = M('t_sxf');
        $model37 = M('t_orders');
        $model38 = M('xml');
        $model39 = M('split_gp');
        $model40 = M('peisong');
        $model41 = M('order');
        $model42 = M('duipeng_num');
        $model43 = M('orders');
        $model44 = M('order_goods');
        $model45 = M('project');
        $model46 = M('project_users');
        $model47 = M('project_user_albums');
        $model48 = M('project_albums');
        $model49 = M('user_code');
        
        $model49->where('id > 0')->delete();
        $model48->where('id > 0')->delete();
        $model47->where('id > 0')->delete();
        $model46->where('id > 0')->delete();
        $model45->where('id > 0')->delete();
        $model44->where('id > 0')->delete();
        $model43->where('id > 0')->delete();
//           $model->where('id > 1')->delete();
        $model2->where('id > 0')->delete();
        $model3->where('id > 0')->delete();
        $model4->where('id > 0')->delete();
        $model5->where('id > 0')->delete();
        $model6->where('id > 0')->delete();
        $model7->where('id > 0')->delete();
        $model8->where('id > 0')->delete();
        $model9->where('id > 0')->delete();
        $model10->where('id > 0')->delete();
        $model11->where('id > 0')->delete();
        $model12->where('id > 0')->delete();
        $model13->where('id > 0')->delete();
        // $model14->where('id > 0')->delete();
        $model15->where('ID > 0')->delete();
        $model16->where('ID > 0')->delete();
        $model17->where('ID > 0')->delete();
        $model18->where('id > 0')->delete();
        $model19->where('id > 0')->delete();
        $model20->where('id > 0')->delete();
        $model21->where('id > 1')->delete();
        $model22->where('id > 0')->delete();
        $model23->where('id > 0')->delete();
        $model24->where('id > 0')->delete();
        $model25->where('id > 0')->delete();
        $model26->where('id > 0')->delete();
        $model27->where('id > 0')->delete();
        $model28->where('id > 0')->delete();
        $model29->where('id > 0')->delete();
        $model31->where('id > 0')->delete();
        $model32->where('id > 0')->delete();
        $model33->where('id > 0')->delete();
        $model34->where('id > 0')->delete();
        $model35->where('id > 0')->delete();
        $model36->where('id > 0')->delete();
        $model37->where('id > 0')->delete();
        $model38->where('id > 0')->delete();
        $model39->where('id > 0')->delete();
        $model40->where('id > 0')->delete();
        $model41->where('id > 0')->delete();
        $model42->where('id > 0')->delete();
        // M('seller')->where('id > 0')->delete();
        // M('user_terminal')->where('id > 0')->delete();
        // M('user_terminal_out')->where('id > 0')->delete();
        // M('user_terminal_back')->where('id > 0')->delete();
        M('trade_orders')->where('id > 0')->delete();
        M('te_trade_orders')->where('id > 0')->delete();
        M('user_detail')->where('id > 0')->delete();
        M('form')->where('user_id > 0')->delete();
//         M('seller')->where('id > 0')->delete();
        // M('user_terminal')->where('id > 0')->setField('is_fan', 0);
//         M('seller')->where('id > 0')->setField('trade_money', 0);
        M('fck')->where('id > 0')->setField('out_terminal_sn', '');
        M('fck')->where('id > 0')->setField('terminal_sn', '');
        M('fck')->where('id > 0')->setField('agent_use', 0);
        M('fck')->where('id > 0')->setField('agent_kt', 0);
        // M('fck')->where('id > 0')->setField('u_level', 1);
        // M('fck')->where('id > 0')->setField('get_level', 1);
        
        $nowdate = time();
        // 数据清0
        
        $nowday = strtotime(date('Y-m-d'));
        $nowday1 = strtotime(date('Y-m-d H:i:s')); // 测试 使用
        $have_gp = 1000000;
        $fh_gp = 250;
        $fx_numb = $fh_gp;
        $open_pri = 0.2;
        $money = 100000;
        $model30->execute("UPDATE __TABLE__ SET opening=" . $open_pri . ",buy_num=0,sell_num=0,turnover=0,yt_sellnum=0,gp_quantity=0");
        
        $sql .= "`l`=0,`r`=0,`shangqi_l`=0,`shangqi_r`=0,`idt`=0,";
        $sql .= "`benqi_l`=0,`benqi_r`=0,`lr`=0,`shangqi_lr`=0,`benqi_lr`=0,";
        $sql .= "`agent_max`=0,`lssq`=0,`agent_use`=$money,`is_agent`=2,`shop_a`=0,`shop_b`=0,`shop_c`=0,`agent_cash`=$money,";
        $sql .= "`u_level`=1,`zjj`=0,`wlf`=0,`zsq`=0,`re_money`=0,is_fenh=0,";
        $sql .= "`cz_epoint`=0,b0=0,b1=0,b2=0,b3=0,b4=0,fanli_count=0,";
        $sql .= "`b5`=0,b6=0,b7=0,b8=0,b9=0,b10=0,b11=0,b12=0,re_nums=0,man_ceng=0,";
        $sql .= "re_peat_money=0,cpzj=100,duipeng=0,_times=0,fanli=0,rdt=$nowday1,pdt=$nowday1,fanli_time=$nowday,fanli_num=0,day_feng=0,get_date=$nowday,get_numb=0,";
        $sql .= "u_level=1,is_xf=0,xf_money=0,zyi_date=0,zyq_date=0,down_num=0,agent_xf=$money,agent_kt=$money,agent_gp=$money,agent_cf=$money,live_gupiao=$money,buy_point=$money,gp_num=0,xy_money=0,";
        $sql .= "peng_num=0,re_f4=0,agent_bi=$money,agent_cf=$money,is_aa=1,is_bb=0,tx_num=0,xx_money=0,x_num=0,fanli_money=0,wlf_money=0,";
        $sql .= "re_nums_b=0,re_nums_l=0,re_nums_r=0,";
        $sql .= "live_gupiao=" . $have_gp . ",all_in_gupiao=0,all_out_gupiao=0,p_out_gupiao=0,no_out_gupiao=0,ok_out_gupiao=0,";
        $sql .= "yuan_gupiao=0 ,day_num=0 ,month_num=0 ,week_num=0 ,all_gupiao=0,down_num=0,get_ceng =0,left_yeji =0,right_yeji =0,user_number=1,gp_cnum=0,seller_rate=3,register_type=1 ";
        $model->execute("UPDATE __TABLE__ SET " . $sql . " where id=1 or id=2 or id=3 or id=4 or id=5 or id=6");
        // unset($sql);
        // $sql .= "`l`=0,`r`=0,`shangqi_l`=0,`shangqi_r`=0,`idt`=0,";
        // $sql .= "`benqi_l`=0,`benqi_r`=0,`lr`=0,`shangqi_lr`=0,`benqi_lr`=0,";
        // $sql .= "`agent_max`=0,`lssq`=0,`agent_use`=0,`is_agent`=2,`agent_cash`=0,";
        // $sql .= "`u_level`=1,`zjj`=0,`wlf`=0,`zsq`=0,`re_money`=0,";
        // $sql .= "`cz_epoint`=0,b0=0,b1=0,b2=0,b3=0,b4=0,";
        // $sql .= "`b5`=0,b6=0,b7=0,b8=0,b9=0,b10=0,b11=0,b12=0,re_nums=0,man_ceng=0,";
        // $sql .= "re_peat_money=0,cpzj=770,duipeng=0,_times=0,fanli=0,fanli_time=$nowday,fanli_num=0,day_feng=0,get_date=$nowday,get_numb=0,";
        // $sql .= "u_level=0,is_xf=0,xf_money=0,is_zy=0,zyi_date=0,zyq_date=0,down_num=0,agent_xf=0,agent_kt=0,agent_gp=0,gp_num=0,xy_money=0,";
        // $sql .= "peng_num=0,re_f4=0,agent_cf=0,is_aa=0,is_bb=0,tx_num=0,xx_money=0,u_pai=1,x_pai=0,x_out=1,x_num=0,fanli_money=0,wlf_money=0,";
        // $sql .= "re_nums_b=0,re_nums_l=0,re_nums_r=0,";
        // $sql .= "live_gupiao=".$have_gp.",all_in_gupiao=0,all_out_gupiao=0,p_out_gupiao=0,no_out_gupiao=0,ok_out_gupiao=0,";
        // $sql .= "yuan_gupiao=0,all_gupiao=0,down_num=0,get_ceng =0";
        // $model->execute("UPDATE __TABLE__ SET " . $sql ." where id=2");
        $userInfo = M('fck')->where('id =1')->find();
        
        $list = M('fck')->select();
        
        foreach ($list as $i => $userInfo) {
            set_user_shop($userInfo);
            set_user_trade_money($userInfo);
            set_user_count($userInfo);
        }
        
        for ($i = 1; $i <= 2; $i ++) { // fck1 ~ fck5 表 (清空只留800000)
            $fck_other = M('fck' . $i);
            $fck_other->where('id > 1')->delete();
        }
        $nowweek = date("w");
        if ($nowweek == 0) {
            $nowweek = 7;
        }
        $kou_w = $nowweek - 1;
        $weekday = $nowday - $kou_w * 24 * 3600;
        
        // fee表,记载清空操作的时间(时间截)
        $fee = M('fee');
        $fee_rs = $fee->field('id')->find();
        $where = array();
        $data = array();
        $data['id'] = $fee_rs['id'];
        $data['create_time'] = time();
        $data['f_time'] = $weekday;
        $data['us_num'] = 1;
        // $data['a_money'] = 0;
        // $data['b_money'] = 0;
        $data['ff_num'] = 1;
        $data['gp_one'] = $open_pri;
        $data['gp_fxnum'] = $fx_numb;
        $data['gp_senum'] = 0;
        $data['gp_cnum'] = 0;
        $rs = $fee->save($data);
        
        $card = M('card');
        $card->query("update __TABLE__ set is_sell=0,bid=0,buser_id='',b_time=0");
        $this->success('清除成功！');
        exit();
    }

    public function menber()
    {
        
        // 列表过滤器，生成查询Map对象
        $fck = M('fck');
        $map = array();
        $id = $PT_id;
        $map['re_id'] = (int) $_GET['PT_id'];
        // $map['is_pay'] = 0;
        $UserID = $_POST['UserID'];
        if (! empty($UserID)) {
            $map['user_id'] = array(
                'like',
                "%" . $UserID . "%"
            );
        }
        
        // 查询字段
        $field = 'id,user_id,nickname,bank_name,bank_card,user_name,user_address,user_tel,rdt,f4,cpzj,is_pay';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $fck->where($map)->count(); // 总页数
        
        $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
        $page_where = 'UserID=' . $UserID; // 分页条件
        $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $list = $fck->where($map)
            ->field($field)
            ->order('rdt desc')
            ->page($Page->getPage() . ',' . $listrows)
            ->select();
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        $where = array();
        $where['id'] = $id;
        $fck_rs = $fck->where($where)
            ->field('agent_cash')
            ->find();
        $this->assign('frs', $fck_rs); // 购物券
        $this->display('menber');
        exit();
    }

    public function BonusSettlement()
    {
        $this->display();
    }

    public function adminmoneyflows()
    {
        $is_mobile = $_POST['is_mobile'];
        // 货币流向
        $fck = M('fck');
        $history = M('history');
        $sDate = $_REQUEST['time1'];
        $eDate = $_REQUEST['time2'];
        $UserID = $_REQUEST['username'];
        $user_id = $_POST['user_id'];
        $ss_type = (int) $_REQUEST['memo'];
        $map['_string'] = "1=1";
        $s_Date = 0;
        $e_Date = 0;
        if (! empty($sDate)) {
            $s_Date = strtotime($sDate);
        } else {
            $sDate = "2000-01-01";
        }
        if (! empty($eDate)) {
            $e_Date = strtotime($eDate);
        } else {
            $eDate = date("Y-m-d");
        }
        if ($s_Date > $e_Date && $e_Date > 0) {
            $temp_d = $s_Date;
            $s_Date = $e_Date;
            $e_Date = $temp_d;
        }
        if ($s_Date > 0) {
            $map['_string'] .= " and t.pdt>=" . $s_Date;
        }
        if ($e_Date > 0) {
            $e_Date = $e_Date + 3600 * 24 - 1;
            $map['_string'] .= " and t.pdt<=" . $e_Date;
        }
        if ($ss_type > 0) {
            if ($ss_type == 15) {
                $map['action_type'] = array(
                    'lt',
                    12
                );
            } else {
                $map['action_type'] = array(
                    'eq',
                    $ss_type
                );
            }
        }
        if (! empty($UserID)) {
            
            $map['_string'] .= " and (g.user_id like '%" . $UserID . "%' or  g.user_name like '%" . $UserID . "%') ";
        }
        if ($this->_post('time1')) {
            $this->assign('S_Date', $sDate);
        }
        if ($this->_post('time2')) {
            $this->assign('E_Date', $eDate);
        }
        $this->assign('ry', $ss_type);
        $this->assign('username', $UserID);
        // 查询字段
        $field = 't.*,g.user_name';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        
        if ($is_mobile == 1) {
            
            $usid = $_POST['user_id'];
            $map['_string'] .= " and (t.uid=" . $usid . " or t.user_id='" . $usuid . "')";
        }
        
        $count = $history->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid", 'LEFT')
            ->where($map)
            ->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页变量
        
        $this->assign('page', $show);
        
        $p = $this->_param('p', true, 1);
        $list = $history->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid", 'LEFT')
            ->where($map)
            ->field($field)
            ->order('t.pdt desc,t.id desc')
            ->page($p . ',20')
            ->select();
        
        foreach ($list as $key => $value) {
            if ($value['type'] == 0) {
                $list[$key]['type_str'] = '现金点值';
            } else if ($value['type'] == 1) {
                $list[$key]['type_str'] = '注册积分';
            } else if ($value['type'] == 2) {
                $list[$key]['type_str'] = '激活积分';
            } else if ($value['type'] == 3) {
                $list[$key]['type_str'] = '复投积分';
            } else if ($value['type'] == 4) {
                $list[$key]['type_str'] = '幸运积分';
            } else if ($value['type'] == 5) {
                $list[$key]['type_str'] = '股票';
            }
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["pdt"]);
            $list[$key]['epoints'] = ($value['epoints']);
            // $list[$key]['user_id'] = $value['uid'];
        }
        
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
                                      // dump($history);
        
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('s18')->find();
        $fee_s7 = explode('|', $fee_rs['s18']);
        $this->assign('fee_s7', $fee_s7); // 输出奖项名称数组
        
        $is_mobile = $_POST['is_mobile'];
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function profitflows()
    {
        $is_mobile = $_POST['is_mobile'];
        // 货币流向
        $fck = M('fck');
        $history = M('history');
        $sDate = $_REQUEST['time1'];
        $eDate = $_REQUEST['time2'];
        $UserID = $_REQUEST['username'];
        $shop_id = $_REQUEST['shop_id'];
        $user_id = $_POST['user_id'];
        $ss_type = (int) $_REQUEST['memo'];
        $map['_string'] = "1=1";
        $s_Date = 0;
        $e_Date = 0;
        if (! empty($sDate)) {
            $s_Date = strtotime($sDate);
        } else {
            // $sDate = "2000-01-01";
        }
        if (! empty($eDate)) {
            $e_Date = strtotime($eDate);
        } else {
            // $eDate = date("Y-m-d");
        }
        if ($s_Date > $e_Date && $e_Date > 0) {
            $temp_d = $s_Date;
            $s_Date = $e_Date;
            $e_Date = $temp_d;
        }
        if ($s_Date > 0) {
            $map['_string'] .= " and g.trade_time>=" . $s_Date;
        }
        if ($e_Date > 0) {
            $e_Date = $e_Date + 3600 * 24 - 1;
            $map['_string'] .= " and g.trade_time<=" . $e_Date;
        }
        if ($ss_type > 0) {
            if ($ss_type == 15) {
                $map['action_type'] = array(
                    'lt',
                    12
                );
            } else {
                $map['action_type'] = array(
                    'eq',
                    $ss_type
                );
            }
        }
        if (! empty($UserID)) {
            import("@.ORG.KuoZhan"); // 导入扩展类
            $KuoZhan = new KuoZhan();
            if ($KuoZhan->is_utf8($UserID) == false) {
                $UserID = iconv('GB2312', 'UTF-8', $UserID);
            }
            
            unset($KuoZhan);
            $where = array();
            $where['user_id'] = array(
                'eq',
                $UserID
            );
            $usrs = $fck->where($where)
                ->field('id,user_id')
                ->find();
            if ($usrs) {
                $usid = $usrs['id'];
                $usuid = $usrs['user_id'];
                $map['_string'] .= " and (t.uid=" . $usid . " or t.user_id='" . $usuid . "')";
            } else {
                $map['_string'] .= " and t.id=0";
            }
            unset($where, $usrs);
            $UserID = urlencode($UserID);
        }
        $this->assign('ShopID', $shop_id);
        if (! empty($shop_id)) {
            
            $map['_string'] .= " and (g.shop_id=" . $shop_id . " )";
        }
        if ($sDate) {
            $this->assign('S_Date', $sDate);
        }
        if ($eDate) {
            $this->assign('E_Date', $eDate);
        }
        $this->assign('ry', $ss_type);
        $this->assign('UserID', $UserID);
        // 查询字段
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        
        if ($is_mobile == 1) {
            
            $usid = $_POST['user_id'];
            $map['_string'] .= " and (t.uid=" . $usid . " or t.user_id='" . $usuid . "')";
        }
        $map['_string'] .= " and t.bz like '%分润-%'  AND t.shop_type =0 ";
        
        $count = $history->alias('t')
            ->join("xt_trade_orders AS g ON g.order_no = t.order_no ", 'LEFT')
            ->join("xt_seller AS H ON g.SHOP_ID = H.seller_no ", 'LEFT')
            ->where($map)
            ->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页变量
        
        $this->assign('page', $show);
        
        $p = $this->_param('p', true, 1);
        $list = $history->alias('t')
            ->join("xt_trade_orders AS g ON g.order_no = t.order_no ", 'LEFT')
            ->join("xt_seller AS H ON g.SHOP_ID = H.seller_no ", 'LEFT')
            ->where($map)
            ->field($field)
            ->order('t.pdt desc,t.id desc')
            ->page($p . ',20')
            ->select();
        
        $fee = M('fee');
        $fee_rs = $fee->field('user_money')->find(1);
        $fee_user_money = explode('|', $fee_rs['user_money']);
        
        $all_profit = $history->alias('t')
            ->join("xt_trade_orders AS g ON g.order_no = t.order_no ", 'LEFT')
            ->join("xt_seller AS H ON g.SHOP_ID = H.seller_no ", 'LEFT')
            ->where($map)
            ->field($field)
            ->sum('epoints');
        if ($all_profit == NULL) {
            $all_profit = 0;
        }
        $this->assign('all_profit', $all_profit);
        
        foreach ($list as $key => $value) {
            if ($value['type'] == 0) {
                $list[$key]['type_str'] = '现金点值';
            } else if ($value['type'] == 1) {
                $list[$key]['type_str'] = '注册积分';
            } else if ($value['type'] == 2) {
                $list[$key]['type_str'] = '激活积分';
            } else if ($value['type'] == 3) {
                $list[$key]['type_str'] = '复投积分';
            } else if ($value['type'] == 4) {
                $list[$key]['type_str'] = '幸运积分';
            } else if ($value['type'] == 5) {
                $list[$key]['type_str'] = '股票';
            }
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["pdt"]);
            $list[$key]['epoints'] = ($value['epoints']);
            $list[$key]['user_id'] = $value['uid'];
            $list[$key]['user_money'] = $fee_user_money[$value['u_level'] - 1];
            
            $usrs = $fck->where('id=' . $value['uid'])
                ->field('id,user_id,user_name')
                ->find();
            $list[$key]['user_name'] = $usrs['user_id'] . '(' . $usrs['user_name'] . ')';
            
            $trade_orders = M('trade_orders')->where('order_no="' . $value['order_no'] . '"')
                ->field('id,shop_id')
                ->find();
            $list[$key]['shop_id'] = $trade_orders['shop_id'];
        }
        
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
                                      // dump($history);
        
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('s18')->find();
        $fee_s7 = explode('|', $fee_rs['s18']);
        $this->assign('fee_s7', $fee_s7); // 输出奖项名称数组
        
        $is_mobile = $_POST['is_mobile'];
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function te_profitflows()
    {
        $is_mobile = $_POST['is_mobile'];
        // 货币流向
        $fck = M('fck');
        $history = M('history');
        $sDate = $_REQUEST['time1'];
        $eDate = $_REQUEST['time2'];
        $UserID = $_REQUEST['username'];
        $shop_id = $_REQUEST['shop_id'];
        $user_id = $_POST['user_id'];
        $ss_type = (int) $_REQUEST['memo'];
        $map['_string'] = "1=1";
        $s_Date = 0;
        $e_Date = 0;
        if (! empty($sDate)) {
            $s_Date = strtotime($sDate);
        } else {
            // $sDate = "2000-01-01";
        }
        if (! empty($eDate)) {
            $e_Date = strtotime($eDate);
        } else {
            // $eDate = date("Y-m-d");
        }
        if ($s_Date > $e_Date && $e_Date > 0) {
            $temp_d = $s_Date;
            $s_Date = $e_Date;
            $e_Date = $temp_d;
        }
        if ($s_Date > 0) {
            $map['_string'] .= " and g.trade_time>=" . $s_Date;
        }
        if ($e_Date > 0) {
            $e_Date = $e_Date + 3600 * 24 - 1;
            $map['_string'] .= " and g.trade_time<=" . $e_Date;
        }
        if ($ss_type > 0) {
            if ($ss_type == 15) {
                $map['action_type'] = array(
                    'lt',
                    12
                );
            } else {
                $map['action_type'] = array(
                    'eq',
                    $ss_type
                );
            }
        }
        if (! empty($UserID)) {
            import("@.ORG.KuoZhan"); // 导入扩展类
            $KuoZhan = new KuoZhan();
            if ($KuoZhan->is_utf8($UserID) == false) {
                $UserID = iconv('GB2312', 'UTF-8', $UserID);
            }
            
            unset($KuoZhan);
            $where = array();
            $where['user_id'] = array(
                'eq',
                $UserID
            );
            $usrs = $fck->where($where)
                ->field('id,user_id')
                ->find();
            if ($usrs) {
                $usid = $usrs['id'];
                $usuid = $usrs['user_id'];
                $map['_string'] .= " and (t.uid=" . $usid . " or t.user_id='" . $usuid . "')";
            } else {
                $map['_string'] .= " and t.id=0";
            }
            unset($where, $usrs);
            $UserID = urlencode($UserID);
        }
        $this->assign('ShopID', $shop_id);
        if (! empty($shop_id)) {
            
            $map['_string'] .= " and (g.shop_id=" . $shop_id . " )";
        }
        if ($sDate) {
            $this->assign('S_Date', $sDate);
        }
        if ($eDate) {
            $this->assign('E_Date', $eDate);
        }
        $this->assign('ry', $ss_type);
        $this->assign('UserID', $UserID);
        // 查询字段
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        
        if ($is_mobile == 1) {
            
            $usid = $_POST['user_id'];
            $map['_string'] .= " and (t.uid=" . $usid . " or t.user_id='" . $usuid . "')";
        }
        $map['_string'] .= " and t.bz like '%分润-%'     AND H.shop_type =1 ";
        
        $count = $history->alias('t')
            ->join("xt_te_trade_orders AS g ON g.order_no = t.order_no ", 'LEFT')
            ->join("xt_seller AS H ON g.SHOP_ID = H.seller_no ", 'LEFT')
            ->where($map)
            ->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页变量
        
        $this->assign('page', $show);
        
        $p = $this->_param('p', true, 1);
        $list = $history->alias('t')
            ->join("xt_te_trade_orders AS g ON g.order_no = t.order_no ", 'LEFT')
            ->join("xt_seller AS H ON g.SHOP_ID = H.seller_no ", 'LEFT')
            ->where($map)
            ->field($field)
            ->order('t.pdt desc,t.id desc')
            ->page($p . ',20')
            ->select();
        
        $fee = M('fee');
        $fee_rs = $fee->field('user_money')->find(1);
        $fee_user_money = explode('|', $fee_rs['user_money']);
        
        $all_profit = $history->alias('t')
            ->join("xt_te_trade_orders AS g ON g.order_no = t.order_no ", 'LEFT')
            ->join("xt_seller AS H ON g.SHOP_ID = H.seller_no ", 'LEFT')
            ->where($map)
            ->field($field)
            ->sum('epoints');
        if ($all_profit == NULL) {
            $all_profit = 0;
        }
        $this->assign('all_profit', $all_profit);
        
        foreach ($list as $key => $value) {
            if ($value['type'] == 0) {
                $list[$key]['type_str'] = '现金点值';
            } else if ($value['type'] == 1) {
                $list[$key]['type_str'] = '注册积分';
            } else if ($value['type'] == 2) {
                $list[$key]['type_str'] = '激活积分';
            } else if ($value['type'] == 3) {
                $list[$key]['type_str'] = '复投积分';
            } else if ($value['type'] == 4) {
                $list[$key]['type_str'] = '幸运积分';
            } else if ($value['type'] == 5) {
                $list[$key]['type_str'] = '股票';
            }
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["pdt"]);
            $list[$key]['epoints'] = ($value['epoints']);
            $list[$key]['user_id'] = $value['uid'];
            $list[$key]['user_money'] = $value['trade_money'] / $value['real_fen_money'] / 10000;
            
            $usrs = $fck->where('id=' . $value['uid'])
                ->field('id,user_id,user_name')
                ->find();
            $list[$key]['user_name'] = $usrs['user_id'] . '(' . $usrs['user_name'] . ')';
            
            $trade_orders = M('te_trade_orders')->where('order_no="' . $value['order_no'] . '"')
                ->field('id,shop_id')
                ->find();
            $list[$key]['shop_id'] = $trade_orders['shop_id'];
        }
        
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
                                      // dump($history);
        
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('s18')->find();
        $fee_s7 = explode('|', $fee_rs['s18']);
        $this->assign('fee_s7', $fee_s7); // 输出奖项名称数组
        
        $is_mobile = $_POST['is_mobile'];
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function tradeflows()
    {
        $is_excel = I('get.is_excel', 0);
        $is_mobile = I('is_mobile', 0);
        // 货币流向
        $fck = M('fck');
        $history = M('trade_orders');
        $seller_type = $_REQUEST['seller_type'];
        $order_no = $_REQUEST['order_no'];
        $sDate = $_REQUEST['time1'];
        $eDate = $_REQUEST['time2'];
        $UserID = $_REQUEST['username'];
        $shop_id = $_REQUEST['shop_id'];
        $user_id = I('user_id', 10941);
        $ss_type = (int) $_REQUEST['memo'];
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 10000);
        $map['_string'] = "1=1 and t.user_id>0 ";
        // $s_Date = 0;
        // $e_Date = 0;
        if (! empty($sDate)) {
            $s_Date = strtotime($sDate);
        } else {
            $sDate = "2000-01-01";
        }
        if (! empty($eDate)) {
            $e_Date = strtotime($eDate);
        } else {
            $eDate = date("Y-m-d");
        }
        if ($s_Date > $e_Date && $e_Date > 0) {
            $temp_d = $s_Date;
            $s_Date = $e_Date;
            $e_Date = $temp_d;
        }
        if ($s_Date > 0) {
            $map['_string'] .= " and t.trade_time>=" . $s_Date;
        }
        if ($e_Date > 0) {
            $e_Date = $e_Date + 3600 * 24 - 1;
            $map['_string'] .= " and  t.trade_time<=" . $e_Date;
        }
        if ($ss_type > 0) {
            if ($ss_type == 15) {
                $map['action_type'] = array(
                    'lt',
                    12
                );
            } else {
                $map['action_type'] = array(
                    'eq',
                    $ss_type
                );
            }
        }
        if (! empty($UserID)) {
            import("@.ORG.KuoZhan"); // 导入扩展类
            $KuoZhan = new KuoZhan();
            if ($KuoZhan->is_utf8($UserID) == false) {
                $UserID = iconv('GB2312', 'UTF-8', $UserID);
            }
            
            unset($KuoZhan);
            $where = array();
            $where['_string'] = "   user_id='" . $UserID . "' OR  user_name='" . $UserID . "' ";
            $usrs = $fck->where($where)
                ->field('id,user_id')
                ->find();
            if ($usrs) {
                $usid = $usrs['id'];
                $usuid = $usrs['user_id'];
                $map['_string'] .= " and (  t.user_id='" . $usid . "')";
            } else {
                $map['_string'] .= " and id=0";
            }
            unset($where, $usrs);
            // $UserID = urlencode($UserID);
        }
        if (! empty($shop_id)) {
            
            $map['t.shop_id'] = array(
                'like',
                '%' . $shop_id . '%'
            );
        }
        if (! empty($order_no)) {
            
            $map['t.order_no'] = array(
                'like',
                '%' . $order_no . '%'
            );
        }
        $seller_type_str = '';
        if ($seller_type == 0) {
            
            $seller_type_str = '传统';
        }
        if ($seller_type == 1) {
            
            $seller_type_str = '二维码';
        }
        $this->assign('seller_type_str', $seller_type_str);
        $this->assign('seller_type', $seller_type);
        $this->assign('S_Date', $sDate);
        
        $this->assign('E_Date', $eDate);
        
        $this->assign('ry', $ss_type);
        $this->assign('UserID', $UserID);
        $this->assign('shop_id', $shop_id);
        $this->assign('order_no', $order_no);
        
        // 查询字段
        $field = 'h.img,t.shop_id,t.trade_time,t.shop_id,t.money_time,t.shop_title,t.order_no,t.id,t.trade_money,t.upload_type,t.add_time,g.user_name';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        
        $year = I('year', 2018);
        $month = I('month', 12);
        $day = I('day');
        $usuid = I('user_id', 11);
        
        if ($is_mobile == 1) {
            
            $usid = $_POST['user_id'];
            $map['_string'] .= " and ( t.user_id='" . $usuid . "' )    ";
        }
        // if ($is_mobile == 0) {
        
        if ($seller_type == 0) {
            
            // $map['_string'] .= " and h.seller_type=0 ";
        }
        if ($seller_type == 1) {
            
            // $map['_string'] .= " and (h.seller_type>0) ";
        }
        if ($is_excel == 0) {
            $count = $history->alias('t')
                ->join("xt_fck AS g ON   g.id = t.user_id ", 'LEFT')
                ->join("xt_seller AS h ON   h.seller_no = t.shop_id ", 'LEFT')
                ->where($map)
                ->count(); // 查询满足要求的总记录数
        }
        $size = 20;
        IF ($is_excel == 1) {
            $size = 1000000;
        }
        IF ($is_mobile == 1) {
            $size = $page_size;
        }
        
        if ($is_excel == 0) {
            $Page = new Page($count, $size); // 实例化分页类 传入总记录数和每页显示的记录数
            $show = $Page->show(); // 分页变量
        }
        $this->assign('page', $show);
        
        $p = $this->_param('p', true, 1);
        $list = $history->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id ", 'LEFT')
            ->join("xt_seller AS h ON   h.seller_no = t.shop_id ", 'LEFT')
            ->where($map)
            ->field($field)
            ->page($p . ',' . $size)
            ->order(' t.trade_time desc ')
            ->select();
        if ($is_excel == 0) {
            $all_trade_money = $history->alias('t')
                ->join("xt_fck AS g ON   g.id = t.user_id ", 'LEFT')
                ->join("xt_seller AS h ON   h.seller_no = t.shop_id ", 'LEFT')
                ->where($map)
                ->sum('t.trade_money');
            if ($all_trade_money == NULL) {
                $all_trade_money = 0;
            }
            $this->assign('all_trade_money', $all_trade_money);
            $map['upload_type'] = 'web';
            $all_web_trade_money = $history->alias('t')
                ->join("xt_fck AS g ON   g.id = t.user_id ", 'LEFT')
                ->join("xt_seller AS h ON   h.seller_no = t.shop_id ", 'LEFT')
                ->where($map)
                ->sum('t.trade_money');
            if ($all_web_trade_money == NULL) {
                $all_web_trade_money = 0;
            }
            
            $this->assign('all_web_trade_money', $all_web_trade_money);
        }
        foreach ($list as $key => $value) {
            
            $list[$key]['tradetime_str'] = date("Y-m-d H:i:s", $value["trade_time"]);
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["money_time"]);
            $list[$key]['epoints'] = ($value['trade_money']);
            $list[$key]['status_str'] = '已完成';
            
            // $usrs = $fck->where('id=' . $value['user_id'])
            // ->field('id,user_id,user_name')
            // ->find();
            // $list[$key]['user_name'] = $usrs['user_name'];
        }
        
        $this->assign('list', $list); // 数据输出到模板
                                      // }
                                      // =================================================
                                      // dump($history);
                                      
        // $fee = M('fee'); // 参数表
                                      // $fee_rs = $fee->field('s18')->find();
                                      // $fee_s7 = explode('|', $fee_rs['s18']);
                                      // $this->assign('fee_s7', $fee_s7); // 输出奖项名称数组
        IF ($is_excel == 1) {
            // 导出excel
            set_time_limit(0);
            
            $time = $sDate . "到" . $eDate;
            $title = "交易量表 导出时间:" . date("Y-m-d   H:i:s");
            header("Content-Type:   application/vnd.ms-excel");
            header("Content-Disposition:   attachment;   filename=" . $time . "分润补贴表.xls");
            header("Pragma:   no-cache");
            header("Content-Type:text/html; charset=utf-8");
            header("Expires:   0");
            
            echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
            // 输出标题
            echo '<tr  align=center>';
            echo "<td>序号</td>";
            echo "<td>订单号</td>";
            echo "<td>商户号</td>";
            echo "<td>所属商户</td>";
            echo "<td>所属盟友</td>";
            echo "<td>交易量</td>";
            echo "<td>上传时间</td>";
            echo "<td>交易时间</td>";
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
                
                $order_no = chr(28) . "\t" . $row['order_no'] . "\t";
                
                echo '<tr align=center>';
                echo '<td>' . chr(28) . $num . '</td>';
                echo "<td>" . $order_no . "</td>";
                echo "<td>" . $row['shop_id'] . "</td>";
                echo "<td>" . $row['shop_title'] . "</td>";
                echo "<td>" . $row['user_name'] . "&nbsp;</td>";
                echo "<td>" . $row['trade_money'] . "</td>";
                echo "<td>" . date("Y-m-d H:i:s", $row['add_time']) . "</td>";
                echo "<td>" . date("Y-m-d H:i:s", $row['trade_time']) . "</td>";
                echo '</tr>';
            }
            echo '</table>';
        }
        // $is_mobile = $_POST['is_mobile'];
        if ($is_mobile == 1) {
            
            $str = $month;
            
            // $item = M('user_detail')->where(" uid=" . $user_id . " and begintime_str like '%" . $str . "%'")->find();
            // if ($item == NULL) {
            
            // $item['month_shop_trade_money'] = 0;
            // $item['month_team_trade_money'] = 0;
            // }
            // $sum = M('trade_orders')->where($str)->sum('trade_money');
            // if ($sum == null) {
            // $sum = 0;
            // }
            // // 本月直营商户交易额
            // $str = 'FROM_UNIXTIME(trade_time,"%Y-%m")="' . $year . "-" . $month . '" AND money_user_id=' . $user_id;
            
            // $sum1 = M('trade_orders')->where($str)->sum('trade_money');
            // if ($sum1 == null) {
            // $sum1 = 0;
            // }
            
            // // 本月团队商户交易额
            // $str = 'FROM_UNIXTIME(t.trade_time,"%Y-%m")="' . $year . "-" . $month . '" and g.re_path like "%,' . $user_id . ',%"';
            
            // $sum2 = M('trade_orders')->alias('t')
            // ->join("xt_fck AS g ON g.id = t.user_id ", 'LEFT')
            // ->where($str)
            // ->sum('t.trade_money');
            // if ($sum2 == null) {
            // $sum2 = 0;
            // }
            // $list = array();
            // if (! empty($day)) {
            // $list = M('history')->alias('t')
            // ->join("xt_trade_orders AS g ON g.id = t.project_id ", 'LEFT')
            // ->join("xt_fck AS h ON h.id = t.uid ", 'LEFT')
            // ->field(" FROM_UNIXTIME(g.trade_time, '%Y-%m-%d') as time ,FROM_UNIXTIME(g.trade_time, '%m') as month ,FROM_UNIXTIME(g.trade_time, '%d') as day_time ,sum(t.epoints) AS all_trade_money ,sum(g.trade_money) AS all_trade_money1 ")
            // ->where(' FROM_UNIXTIME(g.trade_time,"%Y-%m-%d")="' . $day . '" AND (h.id=' . $user_id . ' ) and t.bz like "%分润%"')
            // ->group("FROM_UNIXTIME(g.trade_time, '%Y-%m-%d') ")
            // ->order('g.trade_time desc ')
            // ->select();
            // } else {
            // $list = M('history')->alias('t')
            // ->join("xt_trade_orders AS g ON g.id = t.project_id ", 'LEFT')
            // ->join("xt_fck AS h ON h.id = t.uid ", 'LEFT')
            // ->field(" FROM_UNIXTIME(g.trade_time, '%Y-%m-%d') as time ,FROM_UNIXTIME(g.trade_time, '%m') as month ,FROM_UNIXTIME(g.trade_time, '%d') as day_time ,sum(t.epoints) AS all_trade_money ,sum(g.trade_money) AS all_trade_money1 ")
            // ->where(' FROM_UNIXTIME(g.trade_time,"%Y-%m")="' . $month . '" AND (h.id=' . $user_id . ' ) and t.bz like "%分润%"')
            // ->group("FROM_UNIXTIME(g.trade_time, '%Y-%m-%d') ")
            // ->order('g.trade_time desc ')
            // ->select();
            // }
            
            if (! empty($day)) {
                // foreach ($list as $key => $value) {
                
                // $field = 'g.*,sum(t.epoints) as real_fen_money,t.fen_money1,t.fen_money2,t.chai_trade_money1,t.chai_trade_money2';
                // $map['_string'] = " t.uid=" . $user_id . " and t.bz like '%分润-%' and FROM_UNIXTIME(g.trade_time, '%Y-%m-%d') = '" . $value['time'] . "'";
                
                // $list1 = M('history')->alias('t')
                // ->join("xt_trade_orders AS g ON g.id = t.project_id ", 'LEFT')
                // ->join("xt_fck AS h ON h.id = t.uid ", 'LEFT')
                // ->where($map)
                // ->field($field)
                // ->group(' g.id ,t.project_id ')
                // ->order(' g.trade_time desc ')
                // ->page($page_index . ',' . 1000000)
                // ->select();
                // foreach ($list1 as $key1 => $value1) {
                // if ($value1['user_id'] != $user_id) {
                // $usrs = $fck->where('id=' . $value1['user_id'])
                // ->field('id,user_id,user_name')
                // ->find();
                // $list1[$key1]['shop_name'] = $usrs['user_name'];
                // }
                // $list1[$key1]['real_fen_money'] = floor($list1[$key1]['real_fen_money'] * 100) / 100;
                // }
                
                // $list[$key]['list'] = $list1;
                // }
            }
            // foreach ($list as $key => $value) {
            
            // $list[$key]['all_trade_money'] = floor($list[$key]['all_trade_money'] * 100) / 100;
            // $list[$key]['all_trade_money2'] = ($value['all_trade_money1'] / 10000);
            // }
            
            $data = array();
            // $data['all_trade_money'] = $item['month_team_trade_money'];
            
            // $data['all_trade_money1'] = $item['month_shop_trade_money'];
            // $data['all_trade_money2'] = $item['month_team_trade_money'] - $item['month_shop_trade_money'];
            $data['data'] = $list;
            $data['current_count'] = count($list);
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            if ($is_excel == 0) {
                $this->display();
            }
        }
    }

    // 会员升级
    public function adminUserUp($GPid = 0)
    {
        // 列表过滤器，生成查询Map对象
        if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGuaUp') {
            $ulevel = M('ulevel');
            $UserID = $_POST['UserID'];
            if (! empty($UserID)) {
                $map['user_id'] = array(
                    'like',
                    "%" . $UserID . "%"
                );
            }
            
            $field = '*';
            // =====================分页开始==============================================
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $ulevel->where($map)->count(); // 总页数
            $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
            $page_where = 'UserID=' . $UserID; // 分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $list = $ulevel->where($map)
                ->field($field)
                ->order('id desc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            
            $HYJJ = '';
            $this->_levelConfirm($HYJJ, 1);
            $this->assign('voo', $HYJJ); // 会员级别
            
            $this->assign('list', $list); // 数据输出到模板
                                          // =================================================
            
            $title = '会员升级管理';
            $this->display('adminuserUp');
            return;
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function adminUserUpAC($GPid = 0)
    {
        // 列表过滤器，生成查询Map对象
        if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGuaUp') {
            // 处理提交按钮
            $action = $_POST['action'];
            // 获取复选框的值
            $PTid = $_POST['tabledb'];
            if (! isset($PTid) || empty($PTid)) {
                $bUrl = __URL__ . '/adminUserUp';
                $this->_box(0, '请选择会员！', $bUrl, 1);
                exit();
            }
            switch ($action) {
                case '确认升级':
                    $this->_adminUserUpOK($PTid);
                    break;
                case '删除':
                    $this->_adminUserUpDel($PTid);
                    break;
                default:
                    $bUrl = __URL__ . '/adminUserUp';
                    $this->_box(0, '没有该会员！', $bUrl, 1);
                    break;
            }
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    private function _adminUserUpOK($PTid = 0)
    {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGuaUp') {
            $fck = D('Fck');
            $ulevel = M('ulevel');
            $where = array();
            $where['id'] = array(
                'in',
                $PTid
            );
            $where['is_pay'] = 0;
            $field = '*';
            $vo = $ulevel->where($where)
                ->field($field)
                ->select();
            $fck_where = array();
            $nowdate = strtotime(date('c'));
            foreach ($vo as $voo) {
                $ulevel->query("UPDATE `xt_ulevel` SET `pdt`=$nowdate,`is_pay`=1 where `id`=" . $voo['id']);
                $money = 0;
                $money = $voo['money']; // 金额
                $fck->query("update `xt_fck` set `cpzj`=cpzj+" . $money . ",u_level=" . $voo['up_level'] . "  where `id`=" . $voo['uid']);
            }
            unset($fck, $where, $field, $vo);
            $bUrl = __URL__ . '/adminUserUp';
            $this->_box(1, '升级会员成功！', $bUrl, 1);
            exit();
        } else {
            $this->error('错误！');
            exit();
        }
    }

    private function _adminUserUpDel($PTid = 0)
    {
        // 删除会员
        if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGuaUp') {
            $fck = M('fck');
            $ispay = M('ispay');
            $ulevle = M('ulevel');
            $where['id'] = array(
                'in',
                $PTid
            );
            $where['is_pay'] = array(
                'eq',
                0
            );
            $rss1 = $ulevle->where($where)->delete();
            
            if ($rss1) {
                $bUrl = __URL__ . '/adminUserUp';
                $this->_box(1, '删除升级申请成功！', $bUrl, 1);
                exit();
            } else {
                $bUrl = __URL__ . '/adminUserUp';
                $this->_box(0, '删除升级申请失败！', $bUrl, 1);
                exit();
            }
        } else {
            $this->error('错误!');
        }
    }

    public function adminMenberJL()
    {
        $peisong = M('peisong');
        $user_id = $this->_post('username');
        
        $s_time = $this->_post('time1', true, 0);
        $e_time = $this->_post('time2');
        $e_time = $e_time ? $e_time : date("Y-m-d H:i:s", time());
        if (! empty($user_id)) {
            $where['user_name'] = array(
                'like',
                "%" . $user_id . "%"
            );
            $where['user_id'] = array(
                'like',
                "%" . $user_id . "%"
            );
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            // $map['user_id'] = array('eq',$user_id);
        }
        
        $map['addtime'] = array(
            array(
                'egt',
                strtotime($s_time)
            ),
            array(
                'elt',
                strtotime($e_time)
            )
        );
        // $map['is_pay'] = array(
        // 'egt',
        // 1
        // );
        $map['agent_gp'] = array(
            'egt',
            1
        );
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $peisong->where($map)->count(); // 总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $peisong->where($map)
            ->field($field)
            ->order('addtime asc,id asc')
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->select();
        $this->assign('list', $list);
        
        $this->assign('user_id', $user_id);
        $this->assign('s_time', $s_time);
        if ($this->_post('time2') != "") {
            $this->assign('e_time', $e_time);
        }
        $this->display('adminMenberJL');
        return;
    }

    public function adminMenberJLAC()
    {
        // 处理提交按钮
        $action = $_POST['action'];
        // 获取复选框的值
        $PTid = $_POST['id'];
        if (! isset($PTid) || empty($PTid)) {
            $this->error('请选择会员！');
        }
        switch ($action) {
            case '配股':
                $this->_admingppeisong($PTid);
                break;
            default:
                // $bUrl = __URL__.'/adminMenber';
                // $this->_box(0,'没有该会员！',$bUrl,1);
                $this->error('没有该会员！');
                break;
        }
    }

    public function upload_fengcai_aa()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_aa();
        }
    }

    protected function _upload_fengcai_aa()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数
        
        // 载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        
        // 设置上传文件大小
        $upload->maxSize = 1048576 * 20; // TODO 50M 3M 3292200 1M 1048576
                                         
        // 设置上传文件类型
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        
        // 设置附件上传目录
        $upload->savePath = './Public/Uploads/';
        
        // 设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = false;
        
        // 设置需要生成缩略图的文件前缀
        $upload->thumbPrefix = 'm_'; // 生产2张缩略图
                                     
        // 设置缩略图最大宽度
        $upload->thumbMaxWidth = '800';
        
        // 设置缩略图最大高度
        $upload->thumbMaxHeight = '600';
        
        // 设置上传文件规则
        $upload->saveRule = date("Y") . date("m") . date("d") . date("H") . date("i") . date("s") . rand(1, 100);
        
        // 删除原图
        $upload->thumbRemoveOrigin = true;
        
        if (! $upload->upload()) {
            // 捕获上传异常
            $error_p = $upload->getErrorMsg();
            echo "<script>alert('" . $error_p . "');history.back();</script>";
        } else {
            // 取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path = $uploadList[0]['savepath'];
            $U_nname = $uploadList[0]['savename'];
            $U_inpath = (str_replace('./Public/', '__PUBLIC__/', $U_path)) . $U_nname;
            
            echo "<script>window.parent.myform.str21.value='" . $U_inpath . "';</script>";
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function upload_fengcai_bb()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_bb();
        }
    }

    protected function _upload_fengcai_bb()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数
        
        // 载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        
        // 设置上传文件大小
        $upload->maxSize = 1048576 * 2; // TODO 50M 3M 3292200 1M 1048576
                                        
        // 设置上传文件类型
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg,mp4');
        
        // 设置附件上传目录
        $upload->savePath = './Public/Uploads/';
        
        // 设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = false;
        
        // 设置需要生成缩略图的文件前缀
        $upload->thumbPrefix = 'm_'; // 生产2张缩略图
                                     
        // 设置缩略图最大宽度
        $upload->thumbMaxWidth = '800';
        
        // 设置缩略图最大高度
        $upload->thumbMaxHeight = '600';
        
        // 设置上传文件规则
        $upload->saveRule = date("Y") . date("m") . date("d") . date("H") . date("i") . date("s") . rand(1, 100);
        
        // 删除原图
        $upload->thumbRemoveOrigin = true;
        
        if (! $upload->upload()) {
            // 捕获上传异常
            $error_p = $upload->getErrorMsg();
            echo "<script>alert('" . $error_p . "');history.back();</script>";
        } else {
            // 取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path = $uploadList[0]['savepath'];
            $U_nname = $uploadList[0]['savename'];
            $U_inpath = (str_replace('./Public/', '__PUBLIC__/', $U_path)) . $U_nname;
            
            echo "<script>window.parent.myform.str22.value='" . $U_inpath . "';</script>";
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function upload_fengcai_video()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_video();
        }
    }

    protected function _upload_fengcai_video()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数
        
        // 载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        
        // 设置上传文件大小
        $upload->maxSize = 104857600 * 2; // TODO 50M 3M 3292200 1M 1048576
                                          
        // 设置上传文件类型
        $upload->allowExts = explode(',', 'mp4,avi,wmv,rmvb,mkv');
        
        // 设置附件上传目录
        $upload->savePath = './Public/Uploads/';
        
        // 设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = false;
        
        // 设置需要生成缩略图的文件前缀
        $upload->thumbPrefix = 'm_'; // 生产2张缩略图
                                     
        // 设置缩略图最大宽度
        $upload->thumbMaxWidth = '800';
        
        // 设置缩略图最大高度
        $upload->thumbMaxHeight = '600';
        
        // 设置上传文件规则
        $upload->saveRule = date("Y") . date("m") . date("d") . date("H") . date("i") . date("s") . rand(1, 100);
        
        // 删除原图
        $upload->thumbRemoveOrigin = true;
        
        if (! $upload->upload()) {
            // 捕获上传异常
            $error_p = $upload->getErrorMsg();
            echo "<script>alert('" . $error_p . "');history.back();</script>";
        } else {
            // 取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path = $uploadList[0]['savepath'];
            $U_nname = $uploadList[0]['savename'];
            $U_inpath = (str_replace('./Public/', '__PUBLIC__/', $U_path)) . $U_nname;
            
            echo "<script>window.parent.myform.video_url.value='" . $U_inpath . "';</script>";
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function upload_fengcai_video2()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_video2();
        }
    }

    protected function _upload_fengcai_video2()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数
        
        // 载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        
        // 设置上传文件大小
        $upload->maxSize = 104857600 * 2; // TODO 50M 3M 3292200 1M 1048576
                                          
        // 设置上传文件类型
        $upload->allowExts = explode(',', 'mp4,avi,wmv,rmvb,mkv');
        
        // 设置附件上传目录
        $upload->savePath = './Public/Uploads/';
        
        // 设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = false;
        
        // 设置需要生成缩略图的文件前缀
        $upload->thumbPrefix = 'm_'; // 生产2张缩略图
                                     
        // 设置缩略图最大宽度
        $upload->thumbMaxWidth = '800';
        
        // 设置缩略图最大高度
        $upload->thumbMaxHeight = '600';
        
        // 设置上传文件规则
        $upload->saveRule = date("Y") . date("m") . date("d") . date("H") . date("i") . date("s") . rand(1, 100);
        
        // 删除原图
        $upload->thumbRemoveOrigin = true;
        
        if (! $upload->upload()) {
            // 捕获上传异常
            $error_p = $upload->getErrorMsg();
            echo "<script>alert('" . $error_p . "');history.back();</script>";
        } else {
            // 取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path = $uploadList[0]['savepath'];
            $U_nname = $uploadList[0]['savename'];
            $U_inpath = (str_replace('./Public/', '__PUBLIC__/', $U_path)) . $U_nname;
            
            echo "<script>window.parent.myform.video_url2.value='" . $U_inpath . "';</script>";
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function upload_fengcai_cc()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_cc();
        }
    }

    protected function _upload_fengcai_cc()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数
        
        // 载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        
        // 设置上传文件大小
        $upload->maxSize = 1048576 * 2; // TODO 50M 3M 3292200 1M 1048576
                                        
        // 设置上传文件类型
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        
        // 设置附件上传目录
        $upload->savePath = './Public/Uploads/';
        
        // 设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = false;
        
        // 设置需要生成缩略图的文件前缀
        $upload->thumbPrefix = 'm_'; // 生产2张缩略图
                                     
        // 设置缩略图最大宽度
        $upload->thumbMaxWidth = '800';
        
        // 设置缩略图最大高度
        $upload->thumbMaxHeight = '600';
        
        // 设置上传文件规则
        $upload->saveRule = date("Y") . date("m") . date("d") . date("H") . date("i") . date("s") . rand(1, 100);
        
        // 删除原图
        $upload->thumbRemoveOrigin = true;
        
        if (! $upload->upload()) {
            // 捕获上传异常
            $error_p = $upload->getErrorMsg();
            echo "<script>alert('" . $error_p . "');history.back();</script>";
        } else {
            // 取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path = $uploadList[0]['savepath'];
            $U_nname = $uploadList[0]['savename'];
            $U_inpath = (str_replace('./Public/', '__PUBLIC__/', $U_path)) . $U_nname;
            
            echo "<script>window.parent.myform.str23.value='" . $U_inpath . "';</script>";
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function LoginMenber()
    {
        if ($_SESSION[C('USER_AUTH_KEY')] != 1) {
            $this->error('系统繁忙！1');
        } else {
            import('@.ORG.RBAC');
            $id = $this->_get('id');
            $authInfo = M('fck')->where(array(
                'id' => $id
            ))->find();
            if (! $authInfo) {
                $this->error('系统繁忙！');
            } else {
                $_SESSION = array();
                $_SESSION[C('USER_AUTH_KEY')] = $authInfo['id'];
                $_SESSION['loginUseracc'] = $authInfo['user_id']; // 用户名
                $_SESSION['loginNickName'] = $authInfo['nickname']; // 会员名
                $_SESSION['loginUserName'] = $authInfo['user_name']; // 开户名
                $_SESSION['lastLoginTime'] = $authInfo['last_login_time'];
                // $_SESSION['login_count'] = $authInfo['login_count'];
                $_SESSION['login_isAgent'] = $authInfo['is_agent']; // 是否服务中心
                $_SESSION['UserMktimes'] = mktime();
                $_SESSION['user_pwd2'] = 1;
                $_SESSION['login_sf_list_u'] = md5($authInfo['user_id'] . 'wodetp_new_1012!@#' . $authInfo['password'] . $_SERVER['HTTP_USER_AGENT']);
                $user_type = md5($_SERVER['HTTP_USER_AGENT'] . 'wtp' . rand(0, 999999));
                $_SESSION['login_user_type'] = $user_type;
                $where['id'] = $authInfo['id'];
                M('M')->where($where)->setField('user_type', $user_type);
                
                $parmd = $this->_cheakPrem();
                if ($authInfo['id'] == 1 || $parmd[11] == 1) {
                    $_SESSION['administrator'] = 1;
                } else {
                    $_SESSION['administrator'] = 2;
                }
                // 管理员
                if ($authInfo['is_boss'] == 2) {
                    $_SESSION['administrator'] = 1;
                }
                M('fck')->execute("update __TABLE__ set last_login_time=new_login_time,last_login_ip=new_login_ip,new_login_time=" . time() . ",new_login_ip='" . $_SERVER['REMOTE_ADDR'] . "' where id=" . $authInfo['id']);
                RBAC::saveAccessList();
                $this->redirect('Index/index', '', 2, '页面跳转中...');
            }
        }
    }

    public function all_static()
    {
        $static_count1 = M('fck')->where('u_level=1')->count();
        $static_count2 = M('fck')->where('u_level=2')->count();
        $static_count3 = M('fck')->where('u_level=3')->count();
        $static_count4 = M('fck')->where('u_level=4')->count();
        $static_count5 = M('fck')->where('u_level=5')->count();
        $static_count6 = M('fck')->where('u_level=6')->count();
        $static_count7 = M('fck')->where('u_level=7')->count();
        
        $this->assign('static_count1', $static_count1);
        $this->assign('static_count2', $static_count2);
        $this->assign('static_count3', $static_count3);
        $this->assign('static_count4', $static_count4);
        $this->assign('static_count5', $static_count5);
        $this->assign('static_count6', $static_count6);
        $this->assign('static_count7', $static_count7);
        
        $active_count1 = M('fck')->where('u_level=1')->count();
        $active_count2 = M('fck')->where('u_level=2')->count();
        $active_count3 = M('fck')->where('u_level=3')->count();
        $active_count4 = M('fck')->where('u_level=4')->count();
        $active_count5 = M('fck')->where('u_level=5')->count();
        $active_count6 = M('fck')->where('u_level=6')->count();
        $active_count7 = M('fck')->where('u_level=7')->count();
        
        $this->assign('active_count1', $active_count1);
        $this->assign('active_count2', $active_count2);
        $this->assign('active_count3', $active_count3);
        $this->assign('active_count4', $active_count4);
        $this->assign('active_count5', $active_count5);
        $this->assign('active_count6', $active_count6);
        $this->assign('active_count7', $active_count7);
        $fee = M('fee');
        $fee_rs = $fee->field('s9')->find();
        $s9 = explode("|", $fee_rs['s9']);
        $all_money = 0;
        $all_user = M('fck')->field('u_level')->select();
        foreach ($all_user as $voo) {
            $all_money = $all_money + $s9[$voo['u_level'] - 1];
        }
        $this->assign('all_money', $all_money);
        
        $all_agent_use = M('fck')->sum('agent_use');
        
        $this->assign('all_agent_use', $all_agent_use);
        
        $all_agent_cash = M('fck')->sum('agent_cash');
        
        $this->assign('all_agent_cash', $all_agent_cash);
        
        $all_agent_xf = M('fck')->sum('agent_xf');
        
        $this->assign('all_agent_xf', $all_agent_xf);
        
        $all_agent_kt = M('fck')->sum('agent_kt');
        
        $this->assign('all_agent_kt', $all_agent_kt);
        $all_agent_cf = M('fck')->sum('agent_cf');
        
        $this->assign('all_agent_cf', $all_agent_cf);
        $all_buy_point = M('fck')->sum('buy_point');
        
        $this->assign('all_buy_point', $all_buy_point);
        
        $all_live_gupiao = M('fck')->sum('live_gupiao');
        
        $this->assign('all_live_gupiao', $all_live_gupiao);
        
        $map['is_pay'] = array(
            'egt',
            1
        );
        $map['agent_gp'] = array(
            'egt',
            1
        );
        
        $count1 = M('peisong')->where($map)->count(); // 总页数
        $this->assign('count1', $count1);
        
        $all_money = 0;
        $all_user = M('peisong')->field('agent_gp')->select();
        foreach ($all_user as $voo) {
            $all_money = $all_money + $voo['agent_gp'];
        }
        $this->assign('all_agent_gp', $all_money);
        
        $this->display('all_static');
        return;
    }

    function adminChange()
    {
        $fck = D('Fck');
        $where = ' 1=1 ';
        if ($_POST) {
            $live_gupiao = I('post.live_gupiao');
            $user_number = I('post.user_number');
            $percent = I('post.percent');
            if (EMPTY($live_gupiao) && EMPTY($user_number)) {
                $this->error('请输入用户编号或者股票拥有！');
            }
            
            if (! EMPTY($user_number)) {
                $user_number = explode("|", $user_number);
                if (! EMPTY($user_number[0])) {
                    $where = $where . ' and user_number>=' . $user_number[0];
                }
                if (! EMPTY($user_number[1])) {
                    $where = $where . ' and user_number<=' . $user_number[1];
                }
            }
            if (! EMPTY($live_gupiao)) {
                $live_gupiao = explode("|", $live_gupiao);
                if (! EMPTY($live_gupiao[0])) {
                    $where = $where . ' and live_gupiao>=' . $live_gupiao[0];
                }
                if (! EMPTY($live_gupiao[1])) {
                    $where = $where . ' and live_gupiao<=' . $live_gupiao[1];
                }
            }
            $user_list = $fck->where($where)->select();
            $history = M('history');
            $fee = M('fee');
            $fee_rs = $fee->find();
            // 开始事务处理
            $fck->startTrans();
            if ($live_gupiao == 0) {
                $this->error(' 股票拥有必须大于0！');
            }
            if (EMPTY($user_list)) {
                $this->error('没有对应的用户！');
            }
            
            foreach ($user_list as $vo) {
                $change_money = $vo['live_gupiao'] * $percent * 0.01;
                // 现金点值比例
                $gp_inm = $fee_rs['gp_inm'] * 0.01 * $change_money * $fee_rs['gp_one'];
                
                $data['uid'] = $vo['id'];
                $data['user_id'] = $vo['user_id'];
                $data['action_type'] = 100;
                $data['pdt'] = time();
                $data['epoints'] = $gp_inm;
                $data['did'] = 0;
                $data['allp'] = 0;
                $data['bz'] = '转换股票-现金点值';
                $data['type'] = 1;
                $history->create();
                $rs1 = $history->add($data);
                if ($rs1) {
                    // 提交事务
                    
                    $fck->execute("UPDATE __TABLE__ set `agent_use`=agent_use+" . $gp_inm . "   where `id`=" . $vo['id']);
                    
                    $fck->commit();
                }
                
                // 复投积分比例
                $gp_inn = $fee_rs['gp_inn'] * 0.01 * $change_money * $fee_rs['gp_one'];
                
                $data['uid'] = $vo['id'];
                $data['user_id'] = $vo['user_id'];
                $data['action_type'] = 100;
                $data['pdt'] = time();
                $data['epoints'] = $gp_inn;
                $data['did'] = 0;
                $data['allp'] = 0;
                $data['bz'] = '转换股票-复投积分';
                $data['type'] = 1;
                $history->create();
                $rs1 = $history->add($data);
                if ($rs1) {
                    // 提交事务
                    
                    $fck->execute("UPDATE __TABLE__ set `agent_xf`=agent_xf+" . $gp_inn . "  where `id`=" . $vo['id']);
                    
                    $fck->commit();
                }
                
                // 幸运积分比例
                $gp_inm = $fee_rs['gp_ino'] * 0.01 * $change_money * $fee_rs['gp_one'];
                
                $data['uid'] = $vo['id'];
                $data['user_id'] = $vo['user_id'];
                $data['action_type'] = 100;
                $data['pdt'] = time();
                $data['epoints'] = $gp_inm;
                $data['did'] = 0;
                $data['allp'] = 0;
                $data['bz'] = '转换股票-幸运积分';
                $data['type'] = 1;
                $history->create();
                $rs1 = $history->add($data);
                if ($rs1) {
                    // 提交事务
                    
                    $fck->execute("UPDATE __TABLE__ set `agent_cf`=agent_cf+" . $gp_inm . "  where `id`=" . $vo['id']);
                    
                    $fck->commit();
                }
                // 扣掉对应股票
                
                $data['uid'] = $vo['id'];
                $data['user_id'] = $vo['user_id'];
                $data['action_type'] = 100;
                $data['pdt'] = time();
                $data['epoints'] = - $change_money;
                $data['did'] = 0;
                $data['allp'] = 0;
                $data['bz'] = '转换股票-扣除股票';
                $data['type'] = 1;
                $history->create();
                $rs1 = $history->add($data);
                if ($rs1) {
                    // 提交事务
                    $fck->execute("UPDATE __TABLE__ set `live_gupiao`=live_gupiao-" . $change_money . "  where `id`=" . $vo['id']);
                    
                    $fck->commit();
                }
                
                $admin = $fck->field('gp_cnum,live_gupiao,user_id,id')->find(1);
                $result = $fck->execute('update __TABLE__ set live_gupiao=live_gupiao+' . $change_money . '  where id=' . $admin['id']);
                $fck->addencAdd($admin['id'], $admin['user_id'], $change_money, 19, 0, 0, 0, '返回转换股票-股票点值');
            }
            
            $this->ajaxSuccess('转换成功！');
        }
        // 货币流向
        $fck = M('fck');
        $history = M('history');
        $sDate = $_REQUEST['time1'];
        $eDate = $_REQUEST['time2'];
        $UserID = $_REQUEST['username'];
        $ss_type = (int) $_REQUEST['memo'];
        $map['_string'] = "1=1";
        $s_Date = 0;
        $e_Date = 0;
        if (! empty($sDate)) {
            $s_Date = strtotime($sDate);
        } else {
            $sDate = "2000-01-01";
        }
        if (! empty($eDate)) {
            $e_Date = strtotime($eDate);
        } else {
            $eDate = date("Y-m-d");
        }
        if ($s_Date > $e_Date && $e_Date > 0) {
            $temp_d = $s_Date;
            $s_Date = $e_Date;
            $e_Date = $temp_d;
        }
        if ($s_Date > 0) {
            $map['_string'] .= " and pdt>=" . $s_Date;
        }
        if ($e_Date > 0) {
            $e_Date = $e_Date + 3600 * 24 - 1;
            $map['_string'] .= " and pdt<=" . $e_Date;
        }
        $map['action_type'] = array(
            'eq',
            100
        );
        
        if (! empty($UserID)) {
            import("@.ORG.KuoZhan"); // 导入扩展类
            $KuoZhan = new KuoZhan();
            if ($KuoZhan->is_utf8($UserID) == false) {
                $UserID = iconv('GB2312', 'UTF-8', $UserID);
            }
            
            unset($KuoZhan);
            $where = array();
            $where['user_id'] = array(
                'eq',
                $UserID
            );
            $usrs = $fck->where($where)
                ->field('id,user_id')
                ->find();
            if ($usrs) {
                $usid = $usrs['id'];
                $usuid = $usrs['user_id'];
                $map['_string'] .= " and (uid=" . $usid . " or user_id='" . $usuid . "')";
            } else {
                $map['_string'] .= " and id=0";
            }
            unset($where, $usrs);
            $UserID = urlencode($UserID);
        }
        if ($this->_post('time1')) {
            $this->assign('S_Date', $sDate);
        }
        if ($this->_post('time2')) {
            $this->assign('E_Date', $eDate);
        }
        $this->assign('ry', $ss_type);
        $this->assign('UserID', $UserID);
        // 查询字段
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $history->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页变量
        
        $this->assign('page', $show);
        
        $p = $this->_param('p', true, 1);
        $list = $history->where($map)
            ->field($field)
            ->order('pdt desc,id desc')
            ->page($p . ',20')
            ->select();
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
                                      // dump($history);
        
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('s18')->find();
        $fee_s7 = explode('|', $fee_rs['s18']);
        $this->assign('fee_s7', $fee_s7); // 输出奖项名称数组
        $this->display('adminChange');
    }

    function adminChangeLevel()
    {
        $fck = D('Fck');
        $where = ' 1=1 ';
        if ($_POST) {
            $u_level = I('post.u_level');
            $user_number = I('post.user_number');
            $u_level = I('post.u_level');
            if (EMPTY($u_level) && EMPTY($user_number)) {
                $this->error('请输入用户编号或者股票拥有！');
            }
            if (! EMPTY($user_number)) {
                $user_number = explode("|", $user_number);
                if (! EMPTY($user_number[0])) {
                    $where = $where . ' and user_number>=' . $user_number[0];
                }
                if (! EMPTY($user_number[1])) {
                    $where = $where . ' and user_number<=' . $user_number[1];
                }
            }
            if (! EMPTY($u_level)) {
                
                if (! EMPTY($u_level)) {
                    $where = $where . ' and u_level=' . $u_level . ' AND u_level!=' . $u_level;
                }
            }
            $user_list = $fck->where($where)->select();
            $history = M('change_level');
            $fee = M('fee');
            $fee_rs = $fee->find();
            // 开始事务处理
            $fck->startTrans();
            if (EMPTY($user_list)) {
                $this->error('没有对应的用户！');
            }
            foreach ($user_list as $vo) {
                $change_level = $u_level;
                
                $data['uid'] = $vo['id'];
                $data['user_id'] = $vo['user_id'];
                $data['change_level'] = $u_level;
                $data['pdt'] = time();
                $data['u_level'] = $vo['u_level'];
                $data['u_level'] = $vo['u_level'];
                $history->create();
                
                if ($vo['u_level'] != $u_level) {
                    $rs1 = $history->add($data);
                    if ($rs1) {
                        // 提交事务
                        
                        $fck->execute("UPDATE __TABLE__ set `u_level`=" . $u_level . "   where `id`=" . $vo['id']);
                        
                        $fck->commit();
                    }
                }
            }
            
            $this->ajaxSuccess('转换成功！');
        }
        // 货币流向
        $fck = M('fck');
        $history = M('change_level');
        $sDate = $_REQUEST['time1'];
        $eDate = $_REQUEST['time2'];
        $UserID = $_REQUEST['username'];
        $ss_type = (int) $_REQUEST['memo'];
        $map['_string'] = "1=1";
        $s_Date = 0;
        $e_Date = 0;
        if (! empty($sDate)) {
            $s_Date = strtotime($sDate);
        } else {
            $sDate = "2000-01-01";
        }
        if (! empty($eDate)) {
            $e_Date = strtotime($eDate);
        } else {
            $eDate = date("Y-m-d");
        }
        if ($s_Date > $e_Date && $e_Date > 0) {
            $temp_d = $s_Date;
            $s_Date = $e_Date;
            $e_Date = $temp_d;
        }
        if ($s_Date > 0) {
            $map['_string'] .= " and pdt>=" . $s_Date;
        }
        if ($e_Date > 0) {
            $e_Date = $e_Date + 3600 * 24 - 1;
            $map['_string'] .= " and pdt<=" . $e_Date;
        }
        
        if (! empty($UserID)) {
            import("@.ORG.KuoZhan"); // 导入扩展类
            $KuoZhan = new KuoZhan();
            if ($KuoZhan->is_utf8($UserID) == false) {
                $UserID = iconv('GB2312', 'UTF-8', $UserID);
            }
            
            unset($KuoZhan);
            $where = array();
            $where['user_id'] = array(
                'eq',
                $UserID
            );
            $usrs = $fck->where($where)
                ->field('id,user_id')
                ->find();
            if ($usrs) {
                $usid = $usrs['id'];
                $usuid = $usrs['user_id'];
                $map['_string'] .= " and (uid=" . $usid . " or user_id='" . $usuid . "')";
            } else {
                $map['_string'] .= " and id=0";
            }
            unset($where, $usrs);
            $UserID = urlencode($UserID);
        }
        if ($this->_post('time1')) {
            $this->assign('S_Date', $sDate);
        }
        if ($this->_post('time2')) {
            $this->assign('E_Date', $eDate);
        }
        $this->assign('ry', $ss_type);
        $this->assign('UserID', $UserID);
        // 查询字段
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $history->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页变量
        
        $this->assign('page', $show);
        
        $p = $this->_param('p', true, 1);
        $list = $history->where($map)
            ->field($field)
            ->order('pdt desc,id desc')
            ->page($p . ',20')
            ->select();
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
                                      // dump($history);
        
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('s10')->find();
        $fee_s7 = explode('|', $fee_rs['s10']);
        $this->assign('s10', $fee_s7); // 输出奖项名称数组
        $this->display('adminChangeLevel');
    }

    public function setUserParameterSave()
    {
        $fck = M('fck');
        $user_ids = I('post.user_ids');
        $register_awards = I('post.register_awards');
        foreach ($user_ids as $key => $vo) {
            $where = array();
            $where['id'] = array(
                'eq',
                $vo
            );
            $usrs = $fck->where($where)
                ->field('id,user_id')
                ->find();
            if ($usrs != null) {
                $usrs['register_award'] = $register_awards[$key];
                $fck->where('id=' . $vo)->setField('register_award', $register_awards[$key]);
            }
        }
        $this->success('设置成功！');
    }

    public function setBankParameter($GPid = 0)
    {
        // 列表过滤器，生成查询Map对象
        $fee = M('fee');
        
        // $map['is_pay'] = array('egt',1);
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $bank = $fee->find(1);
        $s1 = explode('|', $bank['s1']);
        $this->assign('s1', $s1);
        $s2 = explode('|', $bank['s2']);
        $this->assign('s2', $s2);
        $s3 = explode('|', $bank['s3']);
        $this->assign('s3', $s3);
        $s4 = explode('|', $bank['s4']);
        $this->assign('s4', $s4);
        $s5 = explode('|', $bank['s5']);
        $this->assign('s5', $s5);
        $s6 = explode('|', $bank['s6']);
        $this->assign('s6', $s6);
        $s7 = explode('|', $bank['s7']);
        $this->assign('s7', $s7);
        $s8 = explode('|', $bank['s8']);
        $this->assign('s8', $s8);
        $s11 = explode('|', $bank['s11']);
        $this->assign('s11', $s11);
        $list = explode('|', $bank['str29']);
        $this->assign('list', $list);
        $bankcard = explode('|', $bank['bankcard']);
        $this->assign('bankcard', $bankcard);
        $bankusername = explode('|', $bank['bankusername']);
        $this->assign('bankusername', $bankusername);
        $userbank = explode('|', $bank['userbank']);
        $this->assign('userbank', $userbank);
        $s10 = explode('|', $bank['s10']);
        $this->assign('userLevel', $s10);
        $s9 = explode('|', $bank['s9']);
        $this->assign('trade_money', $s9);
        $user_money = explode('|', $bank['user_money']);
        $user_ew_money = explode('|', $bank['user_ew_money']);
        $this->assign('user_money', $user_money);
        $this->assign('user_ew_money', $user_ew_money);
        $user_shui = explode('|', $bank['user_shui']);
        $this->assign('user_shui', $user_shui);
        $user_num = explode('|', $bank['user_num']);
        $this->assign('user_num', $user_num);
        $jq_shui = explode('|', $bank['jq_shui']);
        $this->assign('jq_shui', $jq_shui);
        $this->assign('money_count', $bank['money_count']);
        $this->assign('str15', $bank['str15']);
        $this->assign('i1', $bank['i1']);
        $this->assign('i2', $bank['i2']);
        $this->assign('str19', $bank['str19']);
        $this->assign('str2', $bank['str2']);
        $this->assign('str13', $bank['str13']);
        
        $payment = M('payment')->select();
        
        $this->assign('payment', $payment);
        
        $this->display('setBankParameter');
        return;
    }

    public function setBankParameterSave()
    {
        $fck = M('fee');
        $bank = I('post.bank');
        $bankcard = I('post.bankcard');
        $bankusername = I('post.bankusername');
        $userbank = I('post.userbank');
        $u_level = I('post.u_level');
        $trade_money = I('post.trade_money');
        $user_shui = I('post.user_shui');
        $user_money = I('post.user_money');
        $user_ew_money = I('post.user_ew_money');
        $user_num = I('post.user_num');
        $jq_shui = I('post.jq_shui');
        $money_count = I('post.money_count');
        $i1 = I('post.i1');
        $i2 = I('post.i2');
        $s1 = I('post.s1');
        $s2 = I('post.s2');
        $s3 = I('post.s3');
        $s4 = I('post.s4');
        $s5 = I('post.s5');
        $s6 = I('post.s6');
        $s7 = I('post.s7');
        $s8 = I('post.s8');
        $s11 = I('post.s11');
        $str15 = I('post.str15');
        $str19 = I('post.str19');
        $str2 = I('post.str2');
        $str13 = I('post.str13');
        $payment_id = I('post.payment_id');
        $title = I('post.title');
        $poundage_type = I('post.poundage_type');
        $remark = I('post.remark');
        $poundage_amount = I('post.poundage_amount');
        $is_lock = I('post.is_lock');
        
        $s1 = implode("|", $s1);
        $s2 = implode("|", $s2);
        $s3 = implode("|", $s3);
        $s4 = implode("|", $s4);
        $s5 = implode("|", $s5);
        $s6 = implode("|", $s6);
        $s7 = implode("|", $s7);
        $s8 = implode("|", $s8);
        $s11 = implode("|", $s11);
        $jq_shui = implode("|", $jq_shui);
        $bank = implode("|", $bank);
        $bankcard = implode("|", $bankcard);
        $bankusername = implode("|", $bankusername);
        $userbank = implode("|", $userbank);
        $u_level = implode("|", $u_level);
        $trade_money = implode("|", $trade_money);
        $user_money = implode("|", $user_money);
        $user_ew_money = implode("|", $user_ew_money);
        $user_num = implode("|", $user_num);
        $user_shui = implode("|", $user_shui);
        
        $fck->where('id=1')->setField('str2', $str2);
        $fck->where('id=1')->setField('str15', $str15);
        $fck->where('id=1')->setField('str19', $str19);
        $fck->where('id=1')->setField('s11', $s11);
        $fck->where('id=1')->setField('s8', $s8);
        $fck->where('id=1')->setField('s7', $s7);
        $fck->where('id=1')->setField('s6', $s6);
        $fck->where('id=1')->setField('s5', $s5);
        $fck->where('id=1')->setField('s4', $s4);
        $fck->where('id=1')->setField('s3', $s3);
        $fck->where('id=1')->setField('s2', $s2);
        $fck->where('id=1')->setField('s1', $s1);
        $fck->where('id=1')->setField('jq_shui', $jq_shui);
        $fck->where('id=1')->setField('user_num', $user_num);
        $fck->where('id=1')->setField('s9', $trade_money);
        $fck->where('id=1')->setField('user_money', $user_money);
        $fck->where('id=1')->setField('user_ew_money', $user_ew_money);
        $fck->where('id=1')->setField('s10', $u_level);
        // $fck->where('id=1')->setField('str29', $bank);
        $fck->where('id=1')->setField('bankcard', $bankcard);
        $fck->where('id=1')->setField('bankusername', $bankusername);
        $fck->where('id=1')->setField('userbank', $userbank);
        $fck->where('id=1')->setField('user_shui', $user_shui);
        $fck->where('id=1')->setField('money_count', $money_count);
        $fck->where('id=1')->setField('i1', $i1);
        $fck->where('id=1')->setField('i2', $i2);
        $fck->where('id=1')->setField('str13', $str13);
        
        for ($i = 0; $i < count($payment_id); $i ++) {
            $payment = ARRAY();
            $payment['title'] = $title[$i];
            $payment['poundage_type'] = $poundage_type[$i];
            $payment['poundage_amount'] = $poundage_amount[$i];
            $payment['remark'] = $remark[$i];
            $payment['is_lock'] = $is_lock[$i];
            $ret = M('payment')->where('id=' . $payment_id[$i])->save($payment);
        }
        $data = array();
        $data['msg'] = '设置成功';
        $data['url'] = U('YouZi/setBankParameter');
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    public function adminTerminal()
    {
        $user_terminal_type = I('get.user_terminal_type');
        $terminal_type = I('get.terminal_type');
        $this->assign('terminal_type', $terminal_type);
        $status = I('get.status', 0);
        $this->assign('status', $status);
        $page_index = I('post.page_index', 1);
        $page_num = I('post.page_num', 10);
        $keywords = I('post.keywords');
        $user_id = I('post.user_id', 11145);
        $is_mobile = I('post.is_mobile', 0);
        if ($is_mobile == 1) {
            $user_terminal_type = I('post.user_terminal_type', 1);
            $terminal_type = I('post.terminal_type', 4);
        }
        $sDate = $_REQUEST['time1'];
        $eDate = $_REQUEST['time2'];
        $UserID = $_REQUEST['username'];
        $sn = $_REQUEST['sn'];
        $this->assign('sn', $sn);
        $type = $this->_get('type');
        $this->assign('type', $type);
        $map = array();
        if ($status == 1) {
            $map['seller_id'] = ARRAY(
                'gt',
                0
            );
        }
        if ($status == 2) {
            $map['seller_id'] = ARRAY(
                'eq',
                0
            );
        }
        if ($is_mobile == 0) {
            $map['terminal_type'] = $terminal_type;
        }
        $map['_string'] = "1=1  ";
        if ($type == 2) {
            $map['payment_status'] = 0;
        }
        if ($type == 3) {
            $map['payment_status'] = 2;
            $map['express_status'] = 1;
        }
        
        if (! empty($keywords)) {
            
            $map['sn'] = array(
                'like',
                '%' . $keywords . '%'
            );
        }
        
        if (! empty($sDate)) {
            // $s_Date = strtotime($sDate);
        } else {
            // $sDate = "2000-01-01";
        }
        if (! empty($eDate)) {
            $e_Date = strtotime($eDate);
        } else {
            $eDate = date("Y-m-d");
        }
        if ($s_Date > $e_Date && $e_Date > 0) {
            $temp_d = $s_Date;
            $s_Date = $e_Date;
            $e_Date = $temp_d;
        }
        if ($sDate) {
            $map['_string'] .= " and FROM_UNIXTIME(add_time, '%Y-%m-%d')=" . $s_Date;
        }
        // if ($e_Date > 0) {
        // $e_Date = $e_Date + 3600 * 24 - 1;
        // $map['_string'] .= " and add_time<=" . $e_Date;
        // }
        if ($sDate) {
            $this->assign('S_Date', $sDate);
        }
        if ($eDate) {
            $this->assign('E_Date', $eDate);
        }
        
        if (! empty($UserID)) {
            
            $map['_string'] .= " and (g.user_id LIKE '%" . $UserID . "%'  OR g.user_name LIKE  '%" . $UserID . "%')";
        }
        if (! empty($sn)) {
            
            $map['_string'] .= " and (t.sn LIKE '%" . $sn . "%' )";
        }
        $p = $this->_get('p', true, '1');
        if ($is_mobile == 1) {
            $p = $page_index;
        }
        
        $terminal_sn = M('fck')->where('id=' . $user_id)->getField('terminal_sn');
        if ($terminal_sn == NULL) {
            $terminal_sn = '"0"';
        }
        $out_terminal_sn = M('fck')->where('id=' . $user_id)->getField('out_terminal_sn');
        if ($out_terminal_sn == NULL) {
            $out_terminal_sn = '"0"';
        }
        if ($is_mobile == 1) {
            // 全部
            if ($terminal_type == 1) {
                $map['_string'] .= " and t.uid=" . $user_id . '  AND t.sn   in (' . $terminal_sn . ')    and t.sn not in(' . $out_terminal_sn . ')     ';
                // $map['_string'] .= " and t.uid=" . $user_id.' AND EXISTS(SELECT A.ID FROM xt_user_terminal_out A WHERE A.UID=t.uid and A.status=1 and t.sn in(A.sn) and A.type=0 ) ';
            }
            // 已登记
            
            if ($terminal_type == 2) {
                $map['_string'] .= " and t.uid=" . $user_id . '  AND t.sn   in (' . $terminal_sn . ')    and t.sn not in(' . $out_terminal_sn . ')';
                // $map['_string'] .= " and t.uid=" . $user_id.' AND EXISTS(SELECT A.ID FROM xt_user_terminal_out A WHERE A.UID=t.uid and A.status=1 and t.sn in(A.sn) and A.type=0 ) ';
                $map['seller_id'] = array(
                    'gt',
                    0
                );
            }
            // 未登记
            if ($terminal_type == 3) {
                $map['_string'] .= " and t.uid=" . $user_id . ' AND t.sn   in (' . $terminal_sn . ')   and t.sn not in(' . $out_terminal_sn . ') ';
                $map['seller_id'] = array(
                    'eq',
                    0
                );
            }
            // 已下发
            if ($terminal_type == 4) {}
            $map['t.terminal_type'] = $user_terminal_type;
        }
        
        $form = M('user_terminal');
        $field = 't.*,g.user_id';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid", 'LEFT')
            ->where($map)
            ->count(); // 总页数
        
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        
        $list = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
            ->where($map)
            ->field($field)
            ->order('t.add_time desc,t.status asc,t.id desc')
            ->page($p . ',' . $page_num)
            ->select();
        
        if ($is_mobile == 1) {
            $map = array();
            // 已下发
            if ($terminal_type == 4) {
                $map['t.uid'] = array(
                    'eq',
                    $user_id
                );
                $map['h.terminal_type'] = $user_terminal_type;
                $map['_string'] = '   not exists (select A.id from xt_user_terminal_back A WHERE A.out_id=t.id and A.in_uid=' . $user_id . ' ) ';
                
                $field = 't.*,g.user_id,h.trade_money,h.id AS terminal_id';
                $list = M('user_terminal_out')->alias('t')
                    ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
                    ->join("xt_user_terminal AS h ON     FIND_IN_SET(t.sn,h.sn) ", 'LEFT')
                    ->where($map)
                    ->field($field)
                    ->order('t.add_time desc,t.status asc,t.id desc')
                    ->page($p . ',' . $page_num)
                    ->select();
            }
        }
        
        $seller_type = C('seller_type');
        
        $seller_type = explode('|', $seller_type);
        
        foreach ($list as $key => $vo) {
            // $list[$key]['status_str'] = get_terminal_status($vo['id']);
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $vo["add_time"]);
            $use_user_id = M('fck')->where('id=' . $vo['uid'])->getField('user_id');
            if ($terminal_type == 4) {
                $use_user_name = M('fck')->where('id=' . $vo['in_uid'])->getField('user_name');
            } else {
                
                $use_user_name = M('fck')->where('id=' . $vo['uid'])->getField('user_name');
            }
            $list[$key]['use_user_id'] = $use_user_id;
            $list[$key]['user_name'] = $use_user_id . '(' . $use_user_name . ')';
            
            $buy_user_id = M('fck')->where('id=' . $vo['order_uid'])->getField('user_id');
            $buy_user_name = M('fck')->where('id=' . $vo['order_uid'])->getField('user_name');
            $list[$key]['buy_user_name'] = $buy_user_id . '(' . $buy_user_name . ')';
            
            $expire_status_str = get_expire_status_str($vo);
            $list[$key]['expire_status_str'] = $expire_status_str;
            $list[$key]['active_status_str'] = get_terminal_active_status_str($vo);
            ;
            
            $list[$key]['status_str'] = get_terminal_status_str($vo);
            // $list[$key]['trade_money'] = 0;
            $where = ARRAY();
            $where['sn'] = array(
                'like',
                '%' . $vo['sn'] . '%'
            );
            // $model = M('user_terminal_out')->where($where)
            // ->order('id desc ')
            // ->find();
            
            $list[$key]['out_id'] = $vo['id'];
            // if ($model != NULL) {
            
            // $list[$key]['out_id'] = $model['id'];
            // }
            $list[$key]['shop_name'] = '无';
            $where = ARRAY();
            $where['sn'] = array(
                'eq',
                $vo['sn']
            );
            $where['status'] = 0;
            $model = M('seller')->where($where)
                ->order('id desc ')
                ->field('title,seller_no')
                ->find();
            if ($model != NULL) {
                
                $list[$key]['shop_name'] = $model['title'];
                $list[$key]['seller_no'] = $model['seller_no'];
            }
            $list[$key]['is_fan_status_str'] = get_terminal_fan_status_str($vo);
            $list[$key]['is_user_terminal_back_check'] = 0;
            $where['sn'] = array(
                'like',
                '%' . $vo['sn'] . '%'
            );
            $where['status'] = array(
                'eq',
                1
            );
            $check_count = M('user_terminal_back')->where($where)->count();
            IF ($check_count > 0) {
                
                $list[$key]['is_user_terminal_back_check'] = 1;
            }
            $txt = '';
            
            if ($vo['uid'] != $vo['order_uid']) {
                $txt = '【下发机器】';
                
                $list[$key]['use_user_id'] = $use_user_name;
            } else {
                $txt = '【' . $vo['sn_type'] . '】';
            }
            $list[$key]['extra_txt'] = $txt;
            
            $list[$key]['terminal_type'] = $seller_type[$vo['terminal_type']];
            
            $list[$key]['img'] = '/Public/Images/pos.png';
            if ($vo['terminal_type'] == 1) {
                
                $list[$key]['img'] = '/Public/Images/erweima.png';
            }
            
            $back_status = '';
            
            $check_count = M('user_terminal_out')->alias('t')
                ->where('   FIND_IN_SET(t.sn,"' . $vo['sn'] . '")   AND not exists(select A.ID FROM XT_user_terminal_back A WHERE A.out_id=t.id)    and t.id>' . $vo['id'] . '  ')
                ->count();
            IF ($check_count > 0) {
                $list[$key]['back_status'] = '已销售';
            }
            $check_count = M('seller')->alias('t')
                ->where('   t.sn="' . $vo['sn'] . '" ')
                ->find();
            IF ($check_count > 0) {
                $list[$key]['back_status'] = '已绑定';
            }
        }
        
        $this->assign('username', $UserID);
        $this->assign('list', $list); // 数据输出到模板
        if ($is_mobile == 1) {
            $map = ARRAY();
            $map['_string'] = "    uid=" . $user_id . '    ';
            $map['terminal_type'] = array(
                'eq',
                $user_terminal_type
            );
            $all_count = M('user_terminal')->where($map)
                ->order('id desc ')
                ->count();
            $map = ARRAY();
            $map['_string'] = "    uid=" . $user_id . '   and trade_status=1  AND  sn   in (' . $terminal_sn . ')   ';
            $map['terminal_type'] = array(
                'eq',
                $user_terminal_type
            );
            $active_count = M('user_terminal')->where($map)
                ->order('id desc ')
                ->count();
            
            $map = ARRAY();
            $map['_string'] = "   uid=" . $user_id . ' and expire_time>UNIX_TIMESTAMP() and trade_status=0   AND  sn   in (' . $terminal_sn . ')   ';
            $map['terminal_type'] = array(
                'eq',
                $user_terminal_type
            );
            $no_active_count = M('user_terminal')->where($map)
                ->order('id desc ')
                ->count();
            
            $map = ARRAY();
            $map['_string'] = "   uid=" . $user_id . ' and expire_time<UNIX_TIMESTAMP() and trade_status=0  AND  sn   in (' . $terminal_sn . ')   ';
            $map['terminal_type'] = array(
                'eq',
                $user_terminal_type
            );
            $expire_count = M('user_terminal')->where($map)
                ->order('id desc ')
                ->count();
            
            $data = array();
            $data['all_count'] = $all_count;
            $data['expire_count'] = $expire_count;
            $data['no_active_count'] = $no_active_count;
            $data['active_count'] = $active_count;
            $data['data'] = $list;
            $data['status'] = 1;
            $data['current_count'] = count($list);
            $data['count'] = $count;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function adminCheckTerminal()
    {
        $page_index = I('post.page_index', 1);
        $page_num = I('post.page_num', 10);
        $keywords = I('post.keywords');
        $user_id = I('post.user_id');
        $is_mobile = I('post.is_mobile');
        $terminal_type = I('post.terminal_type');
        $sDate = $_REQUEST['time1'];
        $eDate = $_REQUEST['time2'];
        $UserID = $_REQUEST['username'];
        $sn = $_REQUEST['sn'];
        $this->assign('sn', $sn);
        $type = $this->_get('type');
        $this->assign('type', $type);
        $map = array();
        
        $map['_string'] = "1=1  ";
        if ($type == 1) {
            $map['t.status'] = 0;
            $map['t.type'] = 0;
        }
        if ($type == 2) {
            $map['t.status'] = 1;
            $map['t.type'] = 0;
        }
        if ($type == 3) {
            $map['t.status'] = 0;
            $map['t.type'] = 1;
        }
        if ($type == 4) {
            $map['t.status'] = 1;
            $map['t.type'] = 1;
        }
        if (! empty($keywords)) {
            
            $map['sn'] = array(
                'like',
                '%' . $keywords . '%'
            );
        }
        
        if (! empty($sDate)) {
            // $s_Date = strtotime($sDate);
        } else {
            // $sDate = "2000-01-01";
        }
        if (! empty($eDate)) {
            $e_Date = strtotime($eDate);
        } else {
            $eDate = date("Y-m-d");
        }
        if ($s_Date > $e_Date && $e_Date > 0) {
            $temp_d = $s_Date;
            $s_Date = $e_Date;
            $e_Date = $temp_d;
        }
        if ($sDate) {
            $map['_string'] .= " and FROM_UNIXTIME(add_time, '%Y-%m-%d')=" . $s_Date;
        }
        // if ($e_Date > 0) {
        // $e_Date = $e_Date + 3600 * 24 - 1;
        // $map['_string'] .= " and add_time<=" . $e_Date;
        // }
        if ($sDate) {
            $this->assign('S_Date', $sDate);
        }
        if ($eDate) {
            $this->assign('E_Date', $eDate);
        }
        
        if (! empty($UserID)) {
            
            $map['_string'] .= " and (g.user_id LIKE '%" . $UserID . "%' )";
        }
        if (! empty($sn)) {
            
            $map['_string'] .= " and (t.sn LIKE '%" . $sn . "%' )";
        }
        $p = $this->_get('p', true, '1');
        if ($is_mobile == 1) {
            $p = $page_index;
            
            $map['_string'] .= " and t.uid=" . $user_id;
        }
        $form = M('user_terminal_out');
        $field = 't.*,g.user_id,g.user_name';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid", 'LEFT')
            ->where($map)
            ->count(); // 总页数
        
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        
        $list = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
            ->where($map)
            ->field($field)
            ->order('t.add_time desc,t.status asc,t.id desc')
            ->page($p . ',' . $page_num)
            ->select();
        
        foreach ($list as $key => $vo) {
            $list[$key]['status_str'] = get_terminal_status($vo['id']);
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $vo["add_time"]);
            $use_user_id = M('fck')->where('id=' . $vo['in_uid'])->getField('user_id');
            $list[$key]['use_user_id'] = $use_user_id;
            $use_user_name = M('fck')->where('id=' . $vo['in_uid'])->getField('user_name');
            $list[$key]['use_user_name'] = $use_user_name;
            
            $status_str = '已审核';
            if ($vo['status'] == 1) {
                $status_str = '待审核';
            }
            if ($vo['status'] == 2) {
                $status_str = '审核不通过';
            }
            $list[$key]['status_str'] = $status_str;
        }
        
        $this->assign('username', $UserID);
        $this->assign('list', $list); // 数据输出到模板
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['status'] = 1;
            $data['current_count'] = count($list);
            $data['count'] = $count;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function adminCheckBackTerminal()
    {
        $page_index = I('post.page_index', 1);
        $page_num = I('post.page_num', 10);
        $keywords = I('post.keywords');
        $user_id = I('post.user_id');
        $is_mobile = I('post.is_mobile');
        $terminal_type = I('post.terminal_type');
        $sDate = $_REQUEST['time1'];
        $eDate = $_REQUEST['time2'];
        $UserID = $_REQUEST['username'];
        
        $sn = $_REQUEST['sn'];
        $this->assign('sn', $sn);
        $type = $this->_get('type');
        $this->assign('type', $type);
        $map = array();
        
        $map['_string'] = "1=1  ";
        if ($type == 1) {
            $map['t.status'] = 0;
            $map['t.type'] = 0;
        }
        if ($type == 2) {
            $map['t.status'] = 1;
            $map['t.type'] = 0;
        }
        if ($type == 3) {
            $map['t.status'] = 0;
            $map['t.type'] = 1;
        }
        if ($type == 4) {
            $map['t.status'] = 1;
            $map['t.type'] = 1;
        }
        if (! empty($keywords)) {
            
            $map['sn'] = array(
                'like',
                '%' . $keywords . '%'
            );
        }
        
        if (! empty($sDate)) {
            // $s_Date = strtotime($sDate);
        } else {
            // $sDate = "2000-01-01";
        }
        if (! empty($eDate)) {
            $e_Date = strtotime($eDate);
        } else {
            $eDate = date("Y-m-d");
        }
        if ($s_Date > $e_Date && $e_Date > 0) {
            $temp_d = $s_Date;
            $s_Date = $e_Date;
            $e_Date = $temp_d;
        }
        if ($sDate) {
            $map['_string'] .= " and FROM_UNIXTIME(add_time, '%Y-%m-%d')=" . $s_Date;
        }
        // if ($e_Date > 0) {
        // $e_Date = $e_Date + 3600 * 24 - 1;
        // $map['_string'] .= " and add_time<=" . $e_Date;
        // }
        if ($sDate) {
            $this->assign('S_Date', $sDate);
        }
        if ($eDate) {
            $this->assign('E_Date', $eDate);
        }
        
        if (! empty($UserID)) {
            
            $map['_string'] .= " and (g.user_id LIKE '%" . $UserID . "%' )";
        }
        if (! empty($sn)) {
            
            $map['_string'] .= " and (t.sn LIKE '%" . $sn . "%' )";
        }
        $p = $this->_get('p', true, '1');
        if ($is_mobile == 1) {
            $p = $page_index;
            
            $map['_string'] .= " and t.uid=" . $user_id;
        }
        $form = M('user_terminal_back');
        $field = 't.*,g.user_id,g.user_name';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid", 'LEFT')
            ->where($map)
            ->count(); // 总页数
        
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        
        $list = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
            ->where($map)
            ->field($field)
            ->order('t.add_time desc,t.status asc,t.id desc')
            ->page($p . ',' . $page_num)
            ->select();
        
        foreach ($list as $key => $vo) {
            $list[$key]['status_str'] = get_terminal_status($vo['id']);
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $vo["add_time"]);
            $use_user_id = M('fck')->where('id=' . $vo['in_uid'])->getField('user_id');
            $list[$key]['use_user_id'] = $use_user_id;
            $use_user_name = M('fck')->where('id=' . $vo['in_uid'])->getField('user_name');
            $list[$key]['use_user_name'] = $use_user_name;
            
            $status_str = '已审核';
            if ($vo['status'] == 1) {
                $status_str = '待审核';
            }
            if ($vo['status'] == 2) {
                $status_str = '审核不通过';
            }
            $list[$key]['status_str'] = $status_str;
        }
        
        $this->assign('username', $UserID);
        $this->assign('list', $list); // 数据输出到模板
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['status'] = 1;
            $data['current_count'] = count($list);
            $data['count'] = $count;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function checkBackTerminal()
    {
        // 处理提交按钮
        $action = $this->_get('action');
        // 获取复选框的值
        $id = $this->_get('id');
        $name = $this->_get('name');
        if (! isset($id) || empty($id)) {
            $this->error('没有该申请');
            exit();
        }
        switch ($action) {
            case 'check':
                $this->_checkBackTerminal($id);
                break;
            case 'rejuse':
                $this->_rejuseBackTerminal($id);
                break;
            default:
                $this->error('没有该会员');
                break;
        }
    }

    private function _checkBackTerminal($PTid = 0)
    {
        // 删除会员
        $fck = M('user_terminal_back');
        $user_terminal = M('user_terminal');
        $rs = $fck->find($PTid);
        if ($rs) {
            
            $where['id'] = $PTid;
            $fck->where($where)->setField('status', 0);
            ;
            $fck->where($where)->setField('check_time', time());
            ;
            
            $sns = explode(',', $rs['sn']);
            
            foreach ($sns as $i => $value) {
                $user_terminal->where(array(
                    'sn' => $value
                ))->setField('uid', $rs['in_uid']);
                ;
            }
            
            set_out_terminal_ids($rs['in_uid']);
            
            set_terminal_sns($rs['in_uid']);
            
            set_out_terminal_ids($rs['uid']);
            
            set_terminal_sns($rs['uid']);
            
            $this->success('审核成功!');
        }
    }

    private function _rejuseBackTerminal($PTid = 0)
    {
        // 删除会员
        $fck = M('user_terminal_back');
        $rs = $fck->find($PTid);
        if ($rs) {
            
            $where['id'] = $PTid;
            $fck->where($where)->setField('status', 2);
            ;
            $fck->where($where)->setField('check_time', time());
            ;
            // $bUrl = __URL__.'/auditMenber';
            // $this->_box(1,'删除会员！',$bUrl,1);
            $this->success('操作成功!');
        }
    }

    public function init_userdata($PTid = 0)
    {
        $id = $this->_get('user_id');
        // 删除会员
        $fck = M('fck');
        $rs = $fck->where('id=' . $id)->find();
        if ($rs) {
            set_user_count($rs);
            set_user_shop($rs);
            set_user_trade_money($rs);
            
            $this->success('操作成功!');
        }
    }

    public function init_all_userdata()
    {
        // 删除会员
        $fck = M('fck');
        $array = $fck->where('user_trade_money>0')
            ->field('id')
            ->select();
        foreach ($array as $k => $v) {
            
            // set_user_count($v);
            // set_user_shop($v);
            set_user_trade_money($v);
        }
        $array = $fck->field('count(id),re_id as id ,FROM_UNIXTIME(pdt,"%Y-%m-%d") as time')
            ->group('re_id,FROM_UNIXTIME(pdt,"%Y-%m")')
            ->select();
        foreach ($array as $k => $v) {
            
            set_user_count($v, $v['time']);
        }
        
        $array = M('user_terminal')->field('uid as id,FROM_UNIXTIME(add_time,"%Y-%m-%d") as time ')
            ->group('uid,FROM_UNIXTIME(add_time,"%Y-%m")')
            ->select();
        foreach ($array as $k => $v) {
            
            set_user_terminal($v, $v['time']);
        }
        
        $this->success('操作成功!');
    }

    public function checkTerminal()
    {
        // 处理提交按钮
        $action = $this->_get('action');
        // 获取复选框的值
        $id = $this->_get('id');
        $name = $this->_get('name');
        if (! isset($id) || empty($id)) {
            $this->error('没有该申请');
            exit();
        }
        switch ($action) {
            case 'check':
                $this->_checkTerminal($id);
                break;
            case 'rejuse':
                $this->_rejuseTerminal($id);
                break;
            default:
                $this->error('没有该会员');
                break;
        }
    }

    private function _checkTerminal($PTid = 0)
    {
        // 删除会员
        $fck = M('user_terminal_out');
        $user_terminal = M('user_terminal');
        $rs = $fck->find($PTid);
        if ($rs) {
            
            $where['id'] = $PTid;
            $fck->where($where)->setField('status', 0);
            ;
            $fck->where($where)->setField('check_time', time());
            ;
            
            $sns = explode(',', $rs['sn']);
            
            foreach ($sns as $i => $value) {
                $user_terminal->where(array(
                    'sn' => $value
                ))->setField('uid', $rs['in_uid']);
                ;
            }
            
            set_out_terminal_ids($rs['in_uid']);
            
            set_terminal_sns($rs['in_uid']);
            
            set_out_terminal_ids($rs['uid']);
            
            set_terminal_sns($rs['uid']);
            $this->success('审核成功!');
        }
    }

    private function _rejuseTerminal($PTid = 0)
    {
        // 删除会员
        $fck = M('user_terminal_out');
        $rs = $fck->find($PTid);
        if ($rs) {
            
            $where['id'] = $PTid;
            $fck->where($where)->setField('status', 2);
            ;
            $fck->where($where)->setField('check_time', time());
            ;
            // $bUrl = __URL__.'/auditMenber';
            // $this->_box(1,'删除会员！',$bUrl,1);
            $this->success('操作成功!');
        }
    }

    // 压缩文件
    function zip()
    {
        $id = (int) $_GET['id'];
        $shop = M('fck')->where('id=' . $id)->find();
        $txt_url = "info.txt";
        $myfile = fopen($txt_url, "w+") or die("Unable to open file!");
        $txt = "账号" . $shop['user_id'] . "\r\n" . "";
        fwrite($myfile, $txt);
        $txt = "姓名" . $shop['user_name'] . "\r\n" . "";
        fwrite($myfile, $txt);
        
        fclose($myfile);
        
        $files = array(
            $shop['card_img1'],
            $shop['card_img2'],
            $shop['card_img3'],
            $shop['card_img4'],
            $txt_url
        );
        // $files = array('upload/qrcode/1/1.jpg');
        $zipName = $shop['user_name'] . 'zip.zip';
        $zip = new \ZipArchive(); // 使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
        /*
         * 通过ZipArchive的对象处理zip文件
         * $zip->open这个方法如果对zip文件对象操作成功，$zip->open这个方法会返回TRUE
         * $zip->open这个方法第一个参数表示处理的zip文件名。
         * 这里重点说下第二个参数，它表示处理模式
         * ZipArchive::OVERWRITE 总是以一个新的压缩包开始，此模式下如果已经存在则会被覆盖。
         * ZIPARCHIVE::CREATE 如果不存在则创建一个zip压缩包，若存在系统就会往原来的zip文件里添加内容。
         *
         * 这里不得不说一个大坑。
         * 我的应用场景是需要每次都是创建一个新的压缩包，如果之前存在，则直接覆盖，不要追加
         * so，根据官方文档和参考其他代码，$zip->open的第二个参数我应该用 ZipArchive::OVERWRITE
         * 问题来了，当这个压缩包不存在的时候，会报错：ZipArchive::addFile(): Invalid or uninitialized Zip object
         * 也就是说，通过我的测试发现，ZipArchive::OVERWRITE 不会新建，只有当前存在这个压缩包的时候，它才有效
         * 所以我的解决方案是 $zip->open($zipName, \ZIPARCHIVE::OVERWRITE | \ZIPARCHIVE::CREATE)
         *
         * 以上总结基于我当前的运行环境来说
         */
        if ($zip->open($zipName, \ZIPARCHIVE::OVERWRITE | \ZIPARCHIVE::CREATE) !== TRUE) {
            exit('无法打开文件，或者文件创建失败');
        }
        foreach ($files as $val) {
            // $attachfile = $attachmentDir . $val['filepath']; //获取原始文件路径
            if (file_exists($val)) {
                // addFile函数首个参数如果带有路径，则压缩的文件里包含的是带有路径的文件压缩
                // 若不希望带有路径，则需要该函数的第二个参数
                $zip->addFile($val, basename($val)); // 第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
            }
        }
        $zip->close(); // 关闭
        
        if (! file_exists($zipName)) {
            exit("无法找到文件"); // 即使创建，仍有可能失败
        }
        
        // 如果不要下载，下面这段删掉即可，如需返回压缩包下载链接，只需 return $zipName;
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . basename($zipName)); // 文件名
        header("Content-Type: application/zip"); // zip格式的
        header("Content-Transfer-Encoding: binary"); // 告诉浏览器，这是二进制文件
        header('Content-Length: ' . filesize($zipName)); // 告诉浏览器，文件大小
        @readfile($zipName);
    }

    function profitflows_manager()
    {
        $is_excel = I('get.is_excel', 0);
        
        $is_mobile = I('is_mobile', 0);
        // 货币流向
        $fck = M('fck');
        $history = M('user_trade_detail');
        $sDate = $_REQUEST['time1'];
        $eDate = $_REQUEST['time2'];
        $UserID = $_REQUEST['username'];
        $shop_id = $_REQUEST['shop_id'];
        $user_id = I('user_id', 10891);
        $ss_type = (int) $_REQUEST['memo'];
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 10000);
        $map['_string'] = "1=1 and re_level>0  ";
        // $s_Date = 0;
        // $e_Date = 0;
        if (! empty($sDate)) {
            $s_Date = strtotime($sDate);
        } else {
            $s_Date = strtotime(date('Y-m-01'));
            $sDate = date('Y-m-01');
        }
        if (! empty($eDate)) {
            $e_Date = strtotime($eDate);
        } else {
            $e_Date = time();
            $eDate = date('Y-m-d');
        }
        if ($s_Date > $e_Date && $e_Date > 0) {
            $temp_d = $s_Date;
            $s_Date = $e_Date;
            $e_Date = $temp_d;
        }
        $data = " AND 1=1  ";
        if ($s_Date > 0) {
            $data .= " and  (time)>=" . $s_Date;
        }
        if ($e_Date > 0) {
            $e_Date = $e_Date + 3600 * 24 - 1;
            $data .= " and   (time)<=" . $e_Date;
        }
        
        if (! empty($UserID)) {
            
            $map['_string'] .= " and (  t.user_id='" . $UserID . "' or  t.user_name like '%" . $UserID . "%')";
        }
        
        $this->assign('S_Date', $sDate);
        
        $this->assign('E_Date', $eDate);
        
        $this->assign('ry', $ss_type);
        $this->assign('UserID', $UserID);
        $this->assign('shop_id', $shop_id);
        
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        
        $year = I('year', 2018);
        $month = I('month', 11);
        $usuid = I('user_id', 11);
        
        if ($is_mobile == 1) {
            $usid = $_POST['user_id'];
            $map['_string'] .= " and ( t.uid='" . $usuid . "' )  and   FROM_UNIXTIME(t.pdt, '%Y-%m')  = '" . $year . "-" . $month . "'    ";
        }
        $size = 1000000;
        $count = $fck->alias('t')
            ->join("xt_user_trade_detail AS g ON   t.id = g.user_id " . $data, 'LEFT')
            ->where($map)
            ->group('t.id')
            ->count(); // 查询满足要求的总记录数
        $Page = new Page($count, $size); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页变量
        
        $this->assign('page', $show);
        
        $fee = M('fee');
        $fee_rs = $fee->field('s1,s9,buy_point,agent_use,user_money')->find();
        $fee_user_money = explode('|', $fee_rs['user_money']);
        $run = 0.0001;
        // 查询字段
        $field = 't.id,
	t.user_id,
	t.is_boss,
	t.user_name,
	ifnull(sum(g.user_trade_money), 0) AS 本人直营交易量,
	ifnull(sum(g.user_trade_money), 0) * 0.0005 AS 本人直营交易量万5 ,
	(
		SELECT
			IFNULL(SUM(f.user_trade_money), 0)
		FROM
			xt_user_trade_detail f 
		WHERE
			(
				f.re_path like CONCAT(CONCAT("%,",t.ID),",%")
				OR f.user_id = t.id
			)
		AND 1 = 1 ' . $data . '

	) AS 本人团队交易量 ,re_name,t.shop_name,(SELECT user_name from xt_fck A where A.user_id=t.shop_name) as shop_user_name';
        $p = $this->_param('p', true, 1);
        $list = $fck->alias('t')
            ->join('xt_user_trade_detail AS g ON   t.id = g.user_id ' . $data, 'LEFT')
            ->where($map)
            ->field($field)
            ->page($p . ',' . $size)
            ->order(' t.id asc ')
            ->group('t.id')
            ->select();
        $all_trade_money = $history->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id ", 'LEFT')
            ->where($map)
            ->sum('trade_money');
        if ($all_trade_money == NULL) {
            $all_trade_money = 0;
        }
        $this->assign('all_trade_money', $all_trade_money);
        foreach ($list as $key => $value) {
            if ($value['is_boss'] == 2) {
                $list[$key]['本人直营交易量万5'] = $value['本人直营交易量'] * $fee_user_money[count($fee_user_money) - 2] * $run;
            }
            
            $list[$key]['本人直营交易量万5'] = floor($list[$key]['本人直营交易量万5'] * 100) / 100;
            $list[$key]['tradetime_str'] = date("Y-m-d H:i:s", $value["trade_time"]);
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["money_time"]);
            $list[$key]['epoints'] = ($value['trade_money']);
            
            $level = trade_money_ulevel($value['本人团队交易量'], $value);
            $list[$key]['u_level_str'] = ($level['u_level_str']);
            $list[$key]['本人团队交易量分润'] = $value["本人团队交易量"] * ($level['user_money'] * $run);
            
            $relist = M('fck')->alias('t')
                ->field(' is_boss,(
								SELECT
									IFNULL(SUM(f.user_trade_money), 0)
								FROM
									xt_user_trade_detail f 
								WHERE
									( 
				f.re_path like CONCAT(CONCAT("%,",t.ID),",%") or
										 f.user_id = t.id
									) ' . $data . '
							) as 团队交易量')
                ->where('t.re_id=' . $value['id'])
                ->select();
            $list[$key]['V1团队人数'] = 0;
            $list[$key]['V2团队人数'] = 0;
            $list[$key]['V3团队人数'] = 0;
            $list[$key]['V4团队人数'] = 0;
            $list[$key]['V5团队人数'] = 0;
            $list[$key]['V6团队人数'] = 0;
            $list[$key]['V7团队人数'] = 0;
            $list[$key]['V8团队人数'] = 0;
            $list[$key]['V1交易量'] = 0;
            $list[$key]['V2交易量'] = 0;
            $list[$key]['V3交易量'] = 0;
            $list[$key]['V4交易量'] = 0;
            $list[$key]['V5交易量'] = 0;
            $list[$key]['V6交易量'] = 0;
            $list[$key]['V7交易量'] = 0;
            $list[$key]['V8交易量'] = 0;
            $list[$key]['盟友团队交易量'] = 0;
            $list[$key]['盟友团队交易量分润'] = 0;
            foreach ($relist as $key1 => $value1) {
                $level = trade_money_ulevel($value1["团队交易量"], $value1);
                $list[$key]['盟友团队交易量'] = $list[$key]['盟友团队交易量'] + $value1['团队交易量'];
                $list[$key]['盟友团队交易量分润'] = $list[$key]['盟友团队交易量分润'] + $value1['团队交易量'] * ($level['user_money'] * $run);
                if ($value1["团队交易量"] > 0) {
                    
                    $list[$key]['V' . $level['u_level'] . '团队人数'] = $list[$key]['V' . $level['u_level'] . '团队人数'] + 1;
                }
                $list[$key]['V' . $level['u_level'] . '交易量'] = $list[$key]['V' . $level['u_level'] . '交易量'] + $level['trade_money' . $level['u_level']];
            }
            
            $list[$key]['盟友团队交易量分润'] = floor($list[$key]['盟友团队交易量分润'] * 100) / 100;
            $list[$key]['本人团队交易量分润'] = floor($list[$key]['本人团队交易量分润'] * 100) / 100;
            
            $list[$key]['所得分润'] = round($list[$key]['本人团队交易量分润'], 2) - round($list[$key]['本人直营交易量万5'], 2) - round($list[$key]['盟友团队交易量分润'], 2);
            $list[$key]['所得分润'] = round($list[$key]['所得分润'], 2);
        }
        
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
                                      // dump($history);
        
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('s18')->find();
        $fee_s7 = explode('|', $fee_rs['s18']);
        $this->assign('fee_s7', $fee_s7); // 输出奖项名称数组
        IF ($is_excel == 1) {
            // 导出excel
            set_time_limit(0);
            
            $time = $sDate . "到" . $eDate;
            $sub_title = "查询时间:" . $time;
            header("Content-Type:   application/vnd.ms-excel");
            header("Content-Disposition:   attachment;   filename=" . $time . "分润补贴表.xls");
            header("Pragma:   no-cache");
            header("Content-Type:text/html; charset=utf-8");
            header("Expires:   0");
            
            $title = "分润补贴表 导出时间:" . date("Y-m-d   H:i:s");
            
            echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
            // 输出标题
            echo '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">' . $title . '</td></tr>';
            echo '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">' . $sub_title . '</td></tr>';
            // 输出字段名
            echo '<tr  align=center>';
            echo "<td>用户名</td>";
            echo "<td>姓名</td>";
            echo "<td>实时等级</td>";
            echo "<td>推荐人</td>";
            echo "<td>所属股东</td>";
            echo "<td>直营交易量</td>";
            echo "<td>直营固定万5</td>";
            echo "<td>盟友交易量</td>";
            echo "<td>盟友交易量分润</td>";
            echo "<td>团队交易量</td>";
            echo "<td>团队交易量分润</td>";
            echo "<td>所得分润</td>";
            echo "<td>V1交易量/人数</td>";
            echo "<td>V2交易量/人数</td>";
            echo "<td>V3交易量/人数</td>";
            echo "<td>V4交易量/人数</td>";
            echo "<td>V5交易量/人数</td>";
            echo "<td>V6交易量/人数</td>";
            echo "<td>V7交易量/人数</td>";
            echo "<td>V8交易量/人数</td>";
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
                
                echo '<tr align=center>';
                echo '<td>' . $row['user_id'] . '</td>';
                echo "<td>" . $row['user_name'] . "</td>";
                echo "<td>" . $row['u_level_str'] . "</td>";
                echo "<td>" . $row['re_name'] . "</td>";
                echo "<td>" . $row['shop_name'] . "(" . $row['shop_user_name'] . ")</td>";
                echo "<td>" . $row['本人直营交易量'] . "</td>";
                echo "<td>" . $row['本人直营交易量万5'] . "&nbsp;</td>";
                echo "<td>" . $row['盟友团队交易量'] . "</td>";
                echo "<td>" . $row['盟友团队交易量分润'] . "</td>";
                echo "<td>" . $row['本人团队交易量'] . "</td>";
                echo "<td>" . $row['本人团队交易量分润'] . "</td>";
                echo "<td>" . $row['所得分润'] . "</td>";
                echo "<td>" . $row['V1交易量'] . "/" . $row['V1团队人数'] . "</td>";
                echo "<td>" . $row['V2交易量'] . "/" . $row['V2团队人数'] . "</td>";
                echo "<td>" . $row['V3交易量'] . "/" . $row['V3团队人数'] . "</td>";
                echo "<td>" . $row['V4交易量'] . "/" . $row['V4团队人数'] . "</td>";
                echo "<td>" . $row['V5交易量'] . "/" . $row['V5团队人数'] . "</td>";
                echo "<td>" . $row['V6交易量'] . "/" . $row['V6团队人数'] . "</td>";
                echo "<td>" . $row['V7交易量'] . "/" . $row['V7团队人数'] . "</td>";
                echo "<td>" . $row['V8交易量'] . "/" . $row['V8团队人数'] . "</td>";
                echo '</tr>';
            }
            echo '</table>';
        }
        // $is_mobile = $_POST['is_mobile'];
        if ($is_mobile == 1) {} else {
            if ($is_excel == 0) {
                $this->display();
            }
        }
    }

    public function error_data_func()
    {
        set_time_limit(3600);
        $uid = $_GET['uid'];
        
        $tiqu = M('history');
        
        $map['uid'] = array(
            'eq',
            $uid
        );
        $map['bz'] = ARRAY(
            'like',
            '%异常%'
        );
        
        $content = $tiqu->where($map)
            ->group('uid')
            ->field('uid,user_id,sum(epoints) as epoints,pdt')
            ->select();
        
        init_error($content, 2, $uid);
        
        $url = (U('YouZi/error_data'));
        $this->success('处理成功！', $url);
    }

    public function error_data()
    {
        $user_id = $this->_get('username');
        $s_time = $this->_get('time1', true, 0);
        $e_time = $this->_get('time2');
        
        $this->assign('UserID', $user_id);
        $this->assign('S_Date', $s_time);
        if ($this->_post('time2') != "") {
            $this->assign('E_Date', $e_time);
        }
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 100);
        $is_mobile = I('is_mobile', 0);
        
        $tiqu = M('history');
        $fck = M('fck');
        $fee_rs = M('fee')->field('str11')->find();
        $str4 = $fee_rs['str11'];
        // $UserID = $_POST['UserID'];
        $map['t.bz'] = ARRAY(
            'like',
            '%异常%'
        );
        
        if (! empty($user_id)) {
            
            $map['_string'] = '    (t.user_id like "%' . $user_id . '%" OR t.user_name like "%' . $user_id . '%")   ';
        }
        
        if (! empty($e_time)) {
            $map['pdt'] = array(
                array(
                    'egt',
                    strtotime($s_time)
                ),
                array(
                    'elt',
                    strtotime($e_time)
                )
            );
        }
        
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $tiqu->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
            ->where($map)
            ->field('t.*,sum(t.epoints) as money,g.user_name')
            ->group('t.uid')
            ->count(); // 总页数
        $Page = new Page($count, $page_size);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $page_index = $this->_get('p', true, '1');
        
        $list = $tiqu->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
            ->where($map)
            ->field('t.*,sum(t.epoints) as money,g.user_name')
            ->order('t.id desc')
            ->page($page_index . ',' . $page_size)
            ->group('t.uid')
            ->select();
        
        $this->assign('list', $list); // 数据输出到模板
        
        $this->display();
    }

    public function error_manager()
    {
        $is_mobile = $_POST['is_mobile'];
        // 货币流向
        // $M = new Model();
        $sql = C('get_detail_error_list');
        $list = M()->query($sql);
        
        $this->assign('list', $list); // 数据输出到模板
        
        $this->display();
    }

    public function init_error()
    {
        
        // 货币流向
        init_user_error();
        
        $this->success('处理成功！');
    }

    // 商品保存
    public function setImagesParameter()
    {
        if ($_POST) {
            
            $data = I('post.goods_images');
            $str = implode(",", $data);
            $newstr = substr($str, 0, strlen($str) - 1);
            $fee = M('fee');
            $fee->where('id=1')->setField('str12', $newstr);
            
            $data = I('post.goods_images2');
            $str = implode(",", $data);
            $newstr = substr($str, 0, strlen($str) - 1);
            
            $fee->where('id=1')->setField('str29', $newstr);
            
            $str30 = I('post.str30');
            
            $fee->where('id=1')->setField('str30', $str30);
            $str99 = I('post.str99');
            
            $fee->where('id=1')->setField('str99', $str99);
            $video_url2 = I('post.video_url2');
            
            $fee->where('id=1')->setField('video_url2', $video_url2);
            $data = I('post.goods_images3');
            $str = implode(",", $data);
            $newstr = substr($str, 0, strlen($str) - 1);
            
            $fee->where('id=1')->setField('s18', $newstr);
            $this->success('操作成功');
            return;
        }
        
        $fee = M('fee');
        
        $goods_images = $fee->where('id=1')->getField('str12');
        $str29 = $fee->where('id=1')->getField('str29');
        if (! empty($str29)) {
            $str29 = explode(",", $str29);
        }
        if (! empty($goods_images)) {
            $goods_images = explode(",", $goods_images);
        }
        $list = array();
        
        foreach ($goods_images as $key => $rs) {
            $item = array();
            $item['image_url'] = $rs;
            $list[] = $item;
        }
        $slide_list = array();
        
        foreach ($str29 as $key => $rs) {
            $item = array();
            $item['image_url'] = $rs;
            $slide_list[] = $item;
        }
        $s18 = $fee->where('id=1')->getField('s18');
        if (! empty($s18)) {
            $s18 = explode(",", $s18);
        }
        $recommend_list = array();
        
        foreach ($s18 as $key => $rs) {
            $item = array();
            $item['image_url'] = $rs;
            // $item['txt'] = $s18[$key];
            $recommend_list[] = $item;
        }
        $video_url2 = $fee->where('id=1')->getField('video_url2');
        $str99 = $fee->where('id=1')->getField('str99');
        $str30 = $fee->where('id=1')->getField('str30');
        
        $this->assign('str99', $str99);
        $this->assign('str30', $str30);
        $this->assign('list', $list);
        $this->assign('slide_list', $slide_list);
        $this->assign('recommend_list', $recommend_list);
        $this->assign('video_url2', $video_url2);
        
        $this->display();
    }

    function detail()
    {
        $is_mobile = I('is_mobile', 0);
        $user_id = I('user_id', 0);
        
        $str = '';
        $str1 = '';
        if ($is_mobile == 0) {
            $user_id = I('get.user_id', $_SESSION[C('USER_AUTH_KEY')]);
            IF ($user_id > 0) {
                $str = '  AND ( re_path  like "%,' . $user_id . ',%" or id=' . $user_id . ') ';
                $str1 = '  AND  ( re_path  like "%,' . $user_id . ',%" or id=' . $user_id . ') ';
            }
        }
        $array = array();
        
        $where = ' 1=1   ' . $str;
        $where1 = ' 1=1   and is_pay=1  ' . $str1;
        if ($is_mobile == 1) {
            $where = ' 1=1   AND  re_path  like "%,' . $user_id . ',%"';
            $where1 = ' 1=1   and is_pay=1  AND  re_path  like "%,' . $user_id . ',%"';
        }
        // 总会员数
        $all_user_count = M('fck')->where($where)->count();
        $array['all_user_count'] = $all_user_count;
        $this->assign('all_user_count', $all_user_count);
        // 总激活会员数
        $all_active_user_count = M('fck')->where($where1)->count();
        $array['all_active_user_count'] = $all_active_user_count;
        $this->assign('all_active_user_count', $all_active_user_count);
        // 普通会员数
        // $all_check_user_count1 = M('fck')->where($where . ' AND u_level=2  ')->count();
        // $this->assign('all_check_user_count1', $all_check_user_count1);
        // // 合伙人会员数
        // $all_check_user_count2 = M('fck')->where($where . ' AND u_level=3  ')->count();
        // $this->assign('all_check_user_count2', $all_check_user_count2);
        
        // 今日注册会员数
        $today_user_count = M('fck')->where($where . '   AND  FROM_UNIXTIME(rdt, "%Y-%m-%d")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m-%d")')->count();
        $array['today_user_count'] = $today_user_count;
        $this->assign('today_user_count', $today_user_count);
        // 今日激活人数
        $today_active_user_count = M('fck')->where($where1 . '   AND  FROM_UNIXTIME(pdt, "%Y-%m-%d")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m-%d")')->count();
        $array['today_active_user_count'] = $today_active_user_count;
        $this->assign('today_active_user_count', $today_active_user_count);
        // // 今日认证普通会员数
        // $today_check_user_count1 = M('fck')->where($where . ' AND u_level=2 AND FROM_UNIXTIME(pdt, "%Y-%m-%d") =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m-%d") ')->count();
        // $this->assign('today_check_user_count1', $today_check_user_count1);
        // // 今日认证合伙人数
        // $today_check_user_count2 = M('fck')->where($where . ' AND u_level=3 AND FROM_UNIXTIME(pdt, "%Y-%m-%d") =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m-%d") ')->count();
        // $this->assign('today_check_user_count2', $today_check_user_count2);
        // 本周注册会员数
        $week_user_count = M('fck')->where($where . ' AND  DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(FROM_UNIXTIME(rdt, "%Y-%m-%d"))')->count();
        $array['week_user_count'] = $week_user_count;
        $this->assign('week_user_count', $week_user_count);
        // 本周激活人数
        $week_active_user_count = M('fck')->where($where1 . ' AND  DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(FROM_UNIXTIME(pdt, "%Y-%m-%d"))')->count();
        $array['week_active_user_count'] = $week_active_user_count;
        $this->assign('week_active_user_count', $week_active_user_count);
        // // 本周认证普通会员数
        // $week_check_user_count1 = M('fck')->where($where . ' AND u_level=2 AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(FROM_UNIXTIME(pdt, "%Y-%m-%d"))')->count();
        // $this->assign('week_check_user_count1', $week_check_user_count1);
        // // 本周认证合伙人数
        // $week_check_user_count2 = M('fck')->where($where . ' AND u_level=3 AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(FROM_UNIXTIME(pdt, "%Y-%m-%d"))')->count();
        // $this->assign('week_check_user_count2', $week_check_user_count2);
        // 本月注册会员数
        $month_user_count = M('fck')->where($where . ' AND  DATE_FORMAT(FROM_UNIXTIME(rdt, "%Y-%m-%d"), "%Y%m" ) = DATE_FORMAT( CURDATE( ) , "%Y%m" )')->count();
        $array['month_user_count'] = $month_user_count;
        $this->assign('month_user_count', $month_user_count);
        // 本月注册机器人数
        $month_active_user_count = M('fck')->where($where1 . ' AND  DATE_FORMAT(FROM_UNIXTIME(pdt, "%Y-%m-%d"), "%Y%m" ) = DATE_FORMAT( CURDATE( ) , "%Y%m" )')->count();
        $array['month_active_user_count'] = $month_active_user_count;
        $this->assign('month_active_user_count', $month_active_user_count);
        
        // 会员总可用积分
        $all_agent_use = M('fck')->where($where)->sum('agent_use');
        if ($all_agent_use == null) {
            $all_agent_use = 0;
        }
        $this->assign('all_agent_use', $all_agent_use);
        // 会员总可用积分
        $all_agent_cash = M('fck')->where($where)->sum('agent_cash');
        if ($all_agent_cash == null) {
            $all_agent_cash = 0;
        }
        $this->assign('all_agent_cash', $all_agent_cash);
        // 会员总冻结积分
        $all_buy_point = M('fck')->where($where)->sum('buy_point');
        if ($all_buy_point== null) {
            $all_buy_point = 0;
        }
        $this->assign('all_buy_point', $all_buy_point);
        // 会员总冻结积分
        $all_tiqu = M('tiqu')->sum('money');
        if ($all_tiqu== null) {
            $all_tiqu = 0;
        }
        $this->assign('all_tiqu', $all_tiqu);
        IF ($user_id > 0) {
            $str = '    AND  (g.id=' . $user_id . ' or   g.re_path  like "%,' . $user_id . ',%")';
        }
         
        
        
        //充值
        
        $where = ' 1=1 and is_pay=1  '  ;
        
        // 本月充值agent_use
        $month_cz_agent_use = M('chongzhi')->where($where . ' and stype=0 AND  FROM_UNIXTIME(rdt, "%Y-%m")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m")')->sum('epoint');
        if($month_cz_agent_use==NULL)
        {
            $month_cz_agent_use=0;
        }
        $array['month_cz_agent_use'] = $month_cz_agent_use;
        $this->assign('month_cz_agent_use', $month_cz_agent_use);
        
        // 本月充值buy_point
        $month_cz_buy_point = M('chongzhi')->where($where . ' and stype=6 AND  FROM_UNIXTIME(rdt, "%Y-%m")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m")')->sum('epoint');
        if($month_cz_buy_point==NULL)
        {
            $month_cz_buy_point=0;
        }
        $array['month_cz_buy_point'] = $month_cz_buy_point;
        $this->assign('month_cz_buy_point', $month_cz_buy_point);
        
        
        // 今日充值agent_use
        $today_cz_agent_use = M('chongzhi')->where($where . ' and stype=0 AND  FROM_UNIXTIME(rdt, "%Y-%m-%d")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m-%d")')->sum('epoint');
        if($today_cz_agent_use==NULL)
        {
            $today_cz_agent_use=0;
        }
        $array['today_cz_agent_use'] = $today_cz_agent_use;
        $this->assign('today_cz_agent_use', $today_cz_agent_use);
        
        // 今日充值buy_point
        $today_cz_buy_point = M('chongzhi')->where($where . ' and stype=6 AND  FROM_UNIXTIME(rdt, "%Y-%m-%d")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m-%d")')->sum('epoint');
        if($today_cz_buy_point==NULL)
        {
            $today_cz_buy_point=0;
        }
        $array['today_cz_buy_point'] = $today_cz_buy_point;
        $this->assign('today_cz_buy_point', $today_cz_buy_point);
        
        
        
        // 本周充值agent_use
        $week_cz_agent_use  = M('chongzhi')->where($where . ' and stype=0 AND  DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(FROM_UNIXTIME(rdt, "%Y-%m-%d"))')->count();
        if($week_cz_agent_use==NULL)
        {
            $week_cz_agent_use=0;
        }
        $array['week_cz_agent_use'] = $week_cz_agent_use;
        $this->assign('week_cz_agent_use', $week_cz_agent_use);
        // 本周充值buy_point
        $week_cz_buy_point = M('chongzhi')->where($where . ' and stype=6 AND  DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(FROM_UNIXTIME(rdt, "%Y-%m-%d"))')->count();
        if($week_cz_buy_point==NULL)
        {
            $week_cz_buy_point=0;
        }
        $array['week_cz_buy_point'] = $week_cz_buy_point;
        $this->assign('week_cz_buy_point', $week_cz_buy_point);
        
        
        
        
        
        
        
        // 总计充值agent_use
        $all_cz_agent_use  = M('chongzhi')->where($where . ' and stype=0  ')->count();
        if($all_cz_agent_use==NULL)
        {
            $all_cz_agent_use=0;
        }
        $array['all_cz_agent_use'] = $all_cz_agent_use;
        $this->assign('all_cz_agent_use', $all_cz_agent_use);
        // 总计充值buy_point
        $all_cz_buy_point = M('chongzhi')->where($where . ' and stype=6  ')->count();
        if($all_cz_buy_point==NULL)
        {
            $all_cz_buy_point=0;
        }
        $array['all_cz_buy_point'] = $all_cz_buy_point;
        $this->assign('all_cz_buy_point', $all_cz_buy_point);
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        IF ($is_mobile == 1) {
            $data = array();
            $data['data'] = $array;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }
    function order_detail()
    {
        $is_mobile = I('is_mobile', 0);
        $user_id = I('user_id', 0);
    
        $str = '';
        $str1 = '';
    
        $array = array();
    
        $cate_id=M('article_category')->where('title="KN购物区"')->getField('id');
        IF($cate_id==NULL){
            $cate_id=0;
        }
    
        $where = ' 1=1 and g.type=0 ' . $str;
    
        // 总销售额
        $all_order_money = M('order_goods')->alias('t')
        ->join("xt_orders AS f ON   f.id = t.order_id ", 'LEFT')
        ->join("xt_goods AS g ON   g.id = t.article_id ", 'LEFT')
        ->join("xt_article_category AS h ON   h.id = g.category_id ", 'LEFT')->where($where)->sum('t.goods_price*t.quantity');
        if($all_order_money==NULL)
        {
            $all_order_money=0;
        }
    
    
        $array['all_order_money'] = $all_order_money;
        $this->assign('all_order_money', $all_order_money);
    
    
    
    
        // 月销售额
        $month_order_money = M('order_goods')->alias('t')
        ->join("xt_orders AS f ON   f.id = t.order_id ", 'LEFT')
        ->join("xt_goods AS g ON   g.id = t.article_id ", 'LEFT')
        ->join("xt_article_category AS h ON   h.id = g.category_id ", 'LEFT')->where($where. ' and   FROM_UNIXTIME(f.add_time, "%Y-%m")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m")')->sum('t.goods_price*t.quantity');
    
        if($month_order_money==NULL)
        {
            $month_order_money=0;
        }
    
        $array['month_order_money'] = $month_order_money;
        $this->assign('month_order_money', $month_order_money);
    
    
        // 本周销售额
        $week_order_money = M('order_goods')->alias('t')
        ->join("xt_orders AS f ON   f.id = t.order_id ", 'LEFT')
        ->join("xt_goods AS g ON   g.id = t.article_id ", 'LEFT')
        ->join("xt_article_category AS h ON   h.id = g.category_id ", 'LEFT')->where($where. ' and   DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(FROM_UNIXTIME(f.add_time, "%Y-%m-%d"))')->sum('t.goods_price*t.quantity');
    
        if($week_order_money==NULL)
        {
            $week_order_money=0;
        }
    
    
        $array['week_order_money'] = $week_order_money;
        $this->assign('week_order_money', $week_order_money);
    
        // 今日销售额
        $today_order_money = M('order_goods')->alias('t')
        ->join("xt_orders AS f ON   f.id = t.order_id ", 'LEFT')
        ->join("xt_goods AS g ON   g.id = t.article_id ", 'LEFT')
        ->join("xt_article_category AS h ON   h.id = g.category_id ", 'LEFT')->where($where. ' and   FROM_UNIXTIME(f.add_time, "%Y-%m-%d")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m-%d")')->sum('t.goods_price*t.quantity');
    
        if($today_order_money==NULL)
        {
            $today_order_money=0;
        }
    
    
        $array['today_order_money'] = $today_order_money;
        $this->assign('today_order_money', $today_order_money);
    
    
    
    
    
        $cate_id=M('article_category')->where('title="KN零润区"')->getField('id');
        IF($cate_id==NULL){
            $cate_id=0;
        }
    
    
    
        $where = ' 1=1  and g.type=1  ' . $str;
    
        // 总销售额
        $all_order_money2 = M('order_goods')->alias('t')
        ->join("xt_orders AS f ON   f.id = t.order_id ", 'LEFT')
        ->join("xt_goods AS g ON   g.id = t.article_id ", 'LEFT')
        ->join("xt_article_category AS h ON   h.id = g.category_id ", 'LEFT')->where($where)->sum('t.goods_price*t.quantity');
    
        if($all_order_money2==NULL)
        {
            $all_order_money2=0;
        }
    
    
        $array['all_order_money2'] = $all_order_money2;
        $this->assign('all_order_money2', $all_order_money2);
    
    
    
    
        // 月销售额
        $month_order_money2 = M('order_goods')->alias('t')
        ->join("xt_orders AS f ON   f.id = t.order_id ", 'LEFT')
        ->join("xt_goods AS g ON   g.id = t.article_id ", 'LEFT')
        ->join("xt_article_category AS h ON   h.id = g.category_id ", 'LEFT')->where($where. ' and   FROM_UNIXTIME(f.add_time, "%Y-%m")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m")')->sum('t.goods_price*t.quantity');
    
        if($month_order_money2==NULL)
        {
            $month_order_money2=0;
        }
    
    
    
        $array['month_order_money2'] = $month_order_money2;
        $this->assign('month_order_money2', $month_order_money2);
    
    
        // 本周销售额
        $week_order_money2 = M('order_goods')->alias('t')
        ->join("xt_orders AS f ON   f.id = t.order_id ", 'LEFT')
        ->join("xt_goods AS g ON   g.id = t.article_id ", 'LEFT')
        ->join("xt_article_category AS h ON   h.id = g.category_id ", 'LEFT')->where($where. ' and   DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(FROM_UNIXTIME(f.add_time, "%Y-%m-%d"))')->sum('t.goods_price*t.quantity');
        if($week_order_money2==NULL)
        {
            $week_order_money2=0;
        }
    
    
    
        $array['week_order_money2'] = $week_order_money2;
        $this->assign('week_order_money2', $week_order_money2);
    
        // 今日销售额
        $today_order_money2 = M('order_goods')->alias('t')
        ->join("xt_orders AS f ON   f.id = t.order_id ", 'LEFT')
        ->join("xt_goods AS g ON   g.id = t.article_id ", 'LEFT')
        ->join("xt_article_category AS h ON   h.id = g.category_id ", 'LEFT')->where($where. ' and   FROM_UNIXTIME(f.add_time, "%Y-%m-%d")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m-%d")')->sum('t.goods_price*t.quantity');
        if($today_order_money2==NULL)
        {
            $today_order_money2=0;
        }
    
    
        $array['today_order_money2'] = $today_order_money2;
        $this->assign('today_order_money2', $today_order_money2);
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
        $where = ' 1=1  ' . $str;
    
        // 总销售额
        $all_order_money3 = M('trade_orders')->alias('t')
        ->join("xt_seller AS g ON   g.seller_no = t.shop_id ", 'LEFT')->where($where)->sum('t.trade_money');
    
        if($all_order_money3==NULL)
        {
            $all_order_money3=0;
        }
    
    
        $array['all_order_money3'] = $all_order_money3;
        $this->assign('all_order_money3', $all_order_money3);
    
    
    
    
        // 月销售额
        $month_order_money3 = M('trade_orders')->alias('t')
        ->join("xt_seller AS g ON   g.seller_no = t.shop_id ", 'LEFT')->where($where. ' and   FROM_UNIXTIME(t.trade_time, "%Y-%m")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m")')->sum('t.trade_money');
    
        if($month_order_money3==NULL)
        {
            $month_order_money3=0;
        }
    
    
    
        $array['month_order_money3'] = $month_order_money3;
        $this->assign('month_order_money3', $month_order_money3);
    
    
        // 本周销售额
        $week_order_money3 = M('trade_orders')->alias('t')
        ->join("xt_seller AS g ON   g.seller_no = t.shop_id ", 'LEFT')->where($where. ' and   DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(FROM_UNIXTIME(t.trade_time, "%Y-%m-%d"))')->sum('t.trade_money');
        if($week_order_money3==NULL)
        {
            $week_order_money3=0;
        }
    
    
    
        $array['week_order_money3'] = $week_order_money3;
        $this->assign('week_order_money3', $week_order_money3);
    
        // 今日销售额
        $today_order_money3 = M('trade_orders')->alias('t')
        ->join("xt_seller AS g ON   g.seller_no = t.shop_id ", 'LEFT')->where($where. ' and   FROM_UNIXTIME(t.trade_time, "%Y-%m-%d")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m-%d")')->sum('t.trade_money');
        if($today_order_money3==NULL)
        {
            $today_order_money3=0;
        }
    
    
        $array['today_order_money3'] = $today_order_money3;
        $this->assign('today_order_money3', $today_order_money3);
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
        IF ($is_mobile == 1) {
            $data = array();
            $data['data'] = $array;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }
    function init_user_trade_detail()
    {
        $sql = C('select_month_trade_list');
        $list = M()->query($sql);
        foreach ($list as $key => $rs) {
            create_user_month_trade_detail($rs['user_id'], $rs['time']);
            create_user_month_trade_detail($rs['shop_id'], $rs['time']);
        }
        
        $sql = C('select_day_trade_list');
        $list = M()->query($sql);
        foreach ($list as $key => $rs) {
            create_user_trade_detail($rs['user_id'], $rs['time']);
            create_user_trade_detail($rs['shop_id'], $rs['time']);
        }
        $this->success('操作成功!');
    }

    public function pointflows()
    {
        $page_index = I('page_index', 1);
        $size = I('page_num', 10);
        $is_mobile = I('is_mobile');
        $user_id = I('user_id');
        $type = I('type');
        
        $str = "";
        IF ($type == 'agent_cash') {
            $str = ' AND  bz like "%' . C('agent_cash') . '%" and epoints!=0  ';
        }
        IF ($type == 'agent_use') {
            $str = ' AND  bz like "%' . C('agent_use') . '%" and epoints!=0   ';
        }
        IF ($type == 'agent_kt') {
            $str = ' AND  bz like "%' . C('agent_kt') . '%" and epoints!=0   ';
        }
        IF ($type == 'limit_money') {
            $str = ' AND  bz like "%' . C('limit_money') . '%" and epoints!=0   ';
        }
        IF ($type == 'live_gupiao') {
            $str = ' AND  bz like "%' . C('live_gupiao') . '%" and epoints!=0   ';
        }
        IF ($type == 'buy_point') {
            $str = ' AND  bz like "%' . C('buy_point') . '%" and epoints!=0   ';
        }
        $list = M('history')->field(" FROM_UNIXTIME(pdt, '%Y-%m-%d') as time ,FROM_UNIXTIME(pdt, '%Y-%m') as month ,FROM_UNIXTIME(pdt, '%Y年%m月') as month_str ,FROM_UNIXTIME(pdt, '%d') as day_time ,sum(epoints) AS all_epoints ")
            ->where('  uid=' . $user_id . $str)
            ->group("FROM_UNIXTIME(pdt, '%Y-%m') ")
            ->order(' pdt desc ')
            ->page($page_index . ',' . $size)
            ->select();
        
        foreach ($list as $key => $value) {
            
            $map['_string'] = "  ( uid='" . $user_id . "')  and   FROM_UNIXTIME(pdt, '%Y-%m')  = '" . $value['month'] . "'" . $str;
            
            $list1 = M('history')->where($map)
                ->field(' *,FROM_UNIXTIME(pdt,"%m月%d日 %H:%i:%S") AS time_str')
                ->select();
            
            foreach ($list1 as $key1 => $value1) {
                $array1 = explode(',', $value1['bz']);
                $list1[$key1]['bz'] = $array1[0];
            }
            
            $list[$key]['list'] = $list1;
            $map['_string'] = "  ( uid='" . $user_id . "')  and   FROM_UNIXTIME(pdt, '%Y-%m')  = '" . $value['month'] . "' and epoints>0 " . $str;
            // 统计收入
            $sum1 = M('history')->where($map)->sum('epoints');
            if ($sum1 == NULL) {
                $sum1 = 0;
            }
            $map['_string'] = "  ( uid='" . $user_id . "')  and   FROM_UNIXTIME(pdt, '%Y-%m')  = '" . $value['month'] . "' and epoints<0 " . $str;
            // 统计支出
            $sum2 = M('history')->where($map)->sum('epoints');
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
?>
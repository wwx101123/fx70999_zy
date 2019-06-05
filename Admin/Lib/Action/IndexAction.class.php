<?php

class IndexAction extends CommonAction
{

    public function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
    }
    
    public function uni_app_version()
    {
        $is_need = 1;
        
        if ($is_need == 1) {
            $data = $_POST;
            $android_version = '5.4';
            $ios_version = '1.0';
            $user_id = $data['user_id'];
            file_put_contents('user_id.txt', $user_id . '----2222');
            $device_version = $data['device_version'];
            $device_vendor = $data['device_vendor'];
            $device_connection_type = $data['device_connection_type'];
            $device_model = $data['device_model'];
            $apk_version = $data['apk_version'];
            
            
            $update_tip=ARRAY();
            $update_tip[]='1、修改已知BUG';
            $update_tip[]='2、修改已知BUG';
            $update_tip[]='3、修改已知BUG';
            $update_tip[]='4、修改已知BUG';
            $update_tip[]='5、修改已知BUG';
            $update_tip[]='6、修改已知BUG';
            
            file_put_contents('version.txt', $apk_version . '----2222');
            if ($user_id > 0) {
                M('fck')->where('id=' . $user_id)->setField('app_version', $data['apk_version']);
                if (! EMPTY($device_version)) {
                    
                    M('fck')->where('id=' . $user_id)->setField('os', $data['client']);
                    M('fck')->where('id=' . $user_id)->setField('device_model', $device_model);
                    M('fck')->where('id=' . $user_id)->setField('device_vendor', $device_vendor);
                    M('fck')->where('id=' . $user_id)->setField('device_connection_type', $device_connection_type);
                    M('fck')->where('id=' . $user_id)->setField('device_version', $device_version);
                }
            }
            if ($data['client'] == 'android') {
                if ($android_version == $apk_version) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'msg' => '当前版本已经是最新版！'
                    ));
                } else {
                    $ret_data['status'] = 1;
                    $ret_data['version'] = $android_version;
                    $ret_data['update_tip'] = $update_tip;
                    if ($data['client'] == 'android') {
                        $ret_data['url'] = $data['IP'] . '/APP/uniapp/android.apk';
                    }
                    $this->ajaxReturn($ret_data);
                }
            }
            if ($data['client'] == 'ios') {
                if ($ios_version == $apk_version) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'msg' => '当前版本已经是最新版！'
                    ));
                } else {
                    $ret_data['status'] = 1;
                    $ret_data['version'] = $ios_version;
                    $ret_data['update_tip'] = $update_tip;
                    if ($data['client'] == 'ios') {
                        $fee = M('fee');
                        $fee_rs = $fee->find();
                        $ret_data['url'] = 'itms-services://?action=download-manifest&url=https://zyadmin.super-nba.com/app/IOS/x5.plist';
                    }
                    $this->ajaxReturn($ret_data);
                }
            }
        } else {
            $this->ajaxReturn(array(
                'status' => 0,
                'msg' => '不需要更新！'
            ));
        }
    }
    public function version()
    {
        $is_need = 1;
        
        if ($is_need == 1) {
            $data = $_POST;
            $android_version = '3.8';
            $ios_version = '1.0';
            $user_id = $data['user_id'];
            file_put_contents('user_id.txt', $user_id . '----2222');
            $device_version = $data['device_version'];
            $device_vendor = $data['device_vendor'];
            $device_connection_type = $data['device_connection_type'];
            $device_model = $data['device_model'];
            $apk_version = $data['apk_version'];


            $update_tip=ARRAY();
            $update_tip[]='1、修改已知BUG';
            $update_tip[]='2、修改已知BUG';
            $update_tip[]='3、修改已知BUG';
            $update_tip[]='4、修改已知BUG';
            $update_tip[]='5、修改已知BUG';
            $update_tip[]='6、修改已知BUG';
            
            file_put_contents('version.txt', $apk_version . '----2222');
            if ($user_id > 0) {
                M('fck')->where('id=' . $user_id)->setField('app_version', $data['apk_version']);
                if (! EMPTY($device_version)) {
                    
                    M('fck')->where('id=' . $user_id)->setField('os', $data['client']);
                    M('fck')->where('id=' . $user_id)->setField('device_model', $device_model);
                    M('fck')->where('id=' . $user_id)->setField('device_vendor', $device_vendor);
                    M('fck')->where('id=' . $user_id)->setField('device_connection_type', $device_connection_type);
                    M('fck')->where('id=' . $user_id)->setField('device_version', $device_version);
                }
            }
            if ($data['client'] == 'android') {
                if ($android_version == $apk_version) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'msg' => '当前版本已经是最新版！'
                    ));
                } else {
                    $ret_data['status'] = 1;
                    $ret_data['version'] = $android_version;
                    $ret_data['update_tip'] = $update_tip;
                    if ($data['client'] == 'android') {
                        $ret_data['url'] = $data['IP'] . '/APP/android/android.apk';
                    }
                    $this->ajaxReturn($ret_data);
                }
            }
            if ($data['client'] == 'ios') {
                if ($ios_version == $apk_version) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'msg' => '当前版本已经是最新版！'
                    ));
                } else {
                    $ret_data['status'] = 1;
                    $ret_data['version'] = $ios_version;
                    $ret_data['update_tip'] = $update_tip;
                    if ($data['client'] == 'ios') {
                        $fee = M('fee');
                        $fee_rs = $fee->find();
                        $ret_data['url'] = 'itms-services://?action=download-manifest&url=https://posadmin.super-nba.com/app/IOS/x5.plist';
                    }
                    $this->ajaxReturn($ret_data);
                }
            }
        } else {
            $this->ajaxReturn(array(
                'status' => 0,
                'msg' => '不需要更新！'
            ));
        }
    }
    // 框架首页
    /**
     * 系统首页
     *
     * @return 无返回值
     */
    public function updateVersion()
    {
        $is_need = 1;
        
        if ($is_need == 1) {
            // { "appid":"H5291D269", "iOS":{ "version":"1.2.0", "title":"Hello MUI版本更新", "note":"修复offcanvas偶发性出现菜单不能自动关闭的问题\n修复offcanvas中输入类控件无法focus的问题\n修复当list数据过多，swipeout菜单引起crash的bug\nhello mui支持双击顶部、列表滚到顶部功能", "url":"itms-services://?action=download-manifest&url=https://d.dcloud.net.cn/hellomui/HelloMUI.plist" }, "Android":{ "version":"2.4.0", "title":"Hello MUI版本更新", "note": "修复轮播组件和原生滚动冲突的bug；\n修复列表控件不显示上边框的bug；", "url":"http://www.dcloud.io/hellomui/HelloMUI.apk" } }
            $appid = I('post.appid');
            $version = I('post.version');
            file_put_contents('updateVersion.txt', $version . '----' . $appid);
            $data = array(
                'status' => 0,
                'appid' => $appid,
                'note' => '修复轮播组件和原生滚动冲突的bug；\n修复列表控件不显示上边框的bug；',
                'url' => __ROOT__ . '/APP/android/android.apk',
                'wgt' => __ROOT__ . '/app/com.lv.pos.wgt',
                'responseText' => '2.2'
            );
            $this->ajaxReturn($data);
        }
    }

    public function index()
    {
        ob_clean();
        $this->_checkUser();
        $this->_Config_name(); // 调用参数
        
        $fck = M('fck');
        $field = 'id,user_id,user_name,u_level,re_nums,agent_max,agent_gp,agent_use,agent_cf,xf_money,agent_xf,agent_cash,agent_kt,zjj,get_level,live_gupiao';
        $userInfo = $fck->field($field)->find($_SESSION[C('USER_AUTH_KEY')]);
        $this->assign('user', $userInfo);
        // 会员总数
        $memberCount = $fck->where('id>0')->count();
        $this->assign('memberCount', $memberCount);
        $this->getLevelNamebyLevel($YHDJ, $userInfo['u_level']);
        $this->assign('uLevel', $YHDJ); // 会员级别
        
        $dengji = '';
        for ($d = 1; $d <= $userInfo['u_level']; $d ++) {
            $dengji .= 'A';
        }
        $this->assign('dengji', $dengji);
        
        $team = $fck->where(array(
            're_path' => array(
                'like',
                '%,' . $_SESSION[C('USER_AUTH_KEY')] . ',%'
            ),
            'is_pay' => array(
                'gt',
                0
            )
        ))->count();
        $this->assign('team', $team); //
        /**
         * 登陆时，需要运行的其他流程
         */
        $this->cleargouwu();
        $this->endgouwu();
        $this->endfenhong();
        // 自动结算每周积分
        $loginid = $_SESSION[C('USER_AUTH_KEY')];
        
        $tiqu = M('tiqu')->where(array(
            'id' => $_SESSION[C('USER_AUTH_KEY')]
        ))->sum('money');
        $this->assign('tiqu', $tiqu);
        
        $allb1 = $fck->where(array(
            'id' => 1
        ))->getField('b1');
        $this->assign('allb1', $allb1);
        
        $allcf = $fck->where(array(
            'is_pay' => array(
                'gt',
                0
            )
        ))->sum('agent_cf');
        if (empty($allcf)) {
            $allcf = 0;
        }
        $this->assign('allcf', $allcf);
        
        $one_price = M('fee')->where(array(
            'id' => 1
        ))->getField('gp_one');
        $this->assign('one_price', $one_price);
        
        // 网站状态检测,是否关闭了登陆
        $fee = M('fee')->field('i3,i4')->find();
        $_SESSION['net'] = $fee['i4'];
        
        $todayb = M('bonus')->where(array(
            'uid' => $_SESSION[C('USER_AUTH_KEY')],
            'e_date' => strtotime(date('Y-m-d 23:59:59'))
        ))->find();
        $this->assign('todayb', $todayb);
        $yesd = M('bonus')->where(array(
            'uid' => $_SESSION[C('USER_AUTH_KEY')],
            'e_date' => strtotime(date('Y-m-d')) - 1
        ))->find();
        $this->assign('yesd', $yesd);
        $all = M('bonus')->where(array(
            'uid' => $_SESSION[C('USER_AUTH_KEY')]
        ))
            ->field('sum(b0) as b0,sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8')
            ->find();
        
        $this->assign('all', $all);
        unset($userInfo, $memberCount, $YHDJ);
        
        $newlist = M('form')->where("status=1")
            ->order('baile desc,id desc')
            ->limit(5)
            ->select();
        $this->assign('newslist', $newlist); // 数据输出到模板
                                             
        // 奖项名称与显示
        $b_b = array();
        $c_b = array();
        $b_b[1] = C('Bonus_B1');
        $c_b[1] = C('Bonus_B1c');
        $b_b[2] = C('Bonus_B2');
        $c_b[2] = C('Bonus_B2c');
        $b_b[3] = C('Bonus_B3');
        $c_b[3] = C('Bonus_B3c');
        $b_b[4] = C('Bonus_B4');
        $c_b[4] = C('Bonus_B4c');
        $b_b[5] = C('Bonus_B5');
        $c_b[5] = C('Bonus_B5c');
        $b_b[6] = C('Bonus_B6');
        $c_b[6] = C('Bonus_B6c');
        $b_b[7] = C('Bonus_B7');
        $c_b[7] = C('Bonus_B7c');
        $b_b[8] = C('Bonus_B8');
        $c_b[8] = C('Bonus_B8c');
        $b_b[9] = C('Bonus_B9');
        $c_b[9] = C('Bonus_B9c');
        $b_b[10] = C('Bonus_B10');
        $c_b[10] = C('Bonus_B10c');
        $b_b[11] = C('Bonus_HJ'); // 合计
        $c_b[11] = C('Bonus_HJc');
        $b_b[13] = C('Bonus_Bb0'); // 合计
        $c_b[13] = C('Bonus_Bb0c');
        $b_b[0] = C('Bonus_B0'); // 实发
        $c_b[0] = C('Bonus_B0c');
        $b_b[12] = C('Bonus_XX'); // 详细
        $c_b[12] = C('Bonus_XXc');
        
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('s18')->find();
        $fee_s7 = explode('|', $fee_rs['s18']);
        $this->assign('fee_s7', $fee_s7); // 输出奖项名称数组
        
        $this->assign('b_b', $b_b);
        $this->assign('c_b', $c_b);
        
        $erimg = $this->cserweima($loginid);
        $this->assign('erimg', $erimg);
        
        $see = $_SERVER['HTTP_HOST'] . __APP__;
        $see = str_replace("//", "/", $see);
        $this->assign('see', $see);
        $dizhi = 'http://' . $see . '/Reg/us_reg/rid/' . $loginid;
        $this->assign('dizhi', $dizhi);
        
        $imglist = M('indeximg')->where('is_index=1')
            ->order('id asc')
            ->select();
        $this->assign('imglist', $imglist); // 数据输出到模板
        
        $this->display('index');
    }
    
    // 每日自动结算
    public function aotu_clearings()
    {
        $fck = D('Fck');
        $fee = M('fee');
        $nowday = strtotime(date('Y-m-d'));
        $nowweek = date("w");
        if ($nowweek == 0) {
            $nowweek = 7;
        }
        $kou_w = $nowweek - 1;
        $weekday = $nowday - $kou_w * 24 * 3600;
        
        $now_dtime = strtotime(date("Y-m-d"));
        if (empty($_SESSION['auto_cl_ok']) || $_SESSION['auto_cl_ok'] != $now_dtime) {
            $js_c = $fee->where('id=1 and f_time<' . $weekday)->count();
            if ($js_c > 0) {
                // 经理分红
                $fck->jl_fenghong();
            }
            $_SESSION['auto_cl_ok'] = $now_dtime;
        }
        unset($fck, $fee);
    }

    /**
     * 每月清零
     *
     * @return [type] [description]
     */
    public function cleargouwu()
    {
        $cleartime = M('fee')->getField('cleartime');
        $now = time();
        if ($now >= $cleartime) {
            M('fck')->where('id>0')->setField('gp_num', 0);
            $nexttime = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') + 1, 3, date('Y'))));
            M('fee')->where('id=1')->setField('cleartime', $nexttime);
        }
        unset($cleartime, $now, $nexttime);
    }

    public function endgouwu()
    {
        $fee = M('fee')->field('endtime,a_money')->find();
        $now = time();
        if ($now > $fee['endtime']) {
            $nexttime = strtotime(date('Y-m-d H:i:s', mktime(23, 59, 59, date('m') + 1, 10, date('Y'))));
            M('fee')->where('id=1')->setField('endtime', $nexttime);
            // M('fck')->where('id>6')->setField('is_fenh',0);
            // $manzhu=M('fck')->where(array('gp_num'=>array('egt',$fee['a_money'])))->setField('is_fenh',1);
        }
        unset($fee, $now, $nexttime, $manzhu);
    }

    public function endfenhong()
    {
        $fck = M('fck');
        $now = time();
        $lasttime = $now - 29030400;
        $fck_rs = M('fck')->where(array(
            'pdt' => array(
                'elt',
                $lasttime
            )
        ))
            ->field('id,pdt,xf_money')
            ->select();
        foreach ($fck_rs as $frs) {
            $fck->where(array(
                'id' => $frs['id']
            ))->setInc('agent_use', $frs['xf_money']);
            $fck->where(array(
                'id' => $frs['id']
            ))->setField('xf_money', 0);
        }
        unset($fck, $now, $lasttime, $fck_rs, $frs);
    }
}
?>
<?php

class UserAction extends CommonAction
{

    public function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        // $this->_inject_check(0); // 调用过滤函数
        $this->_Config_name(); // 调用参数
                               // $this->_checkUser();
        $this->check_us_gq();
        if ($_POST['is_mobile'] == 1) {
            $_SESSION[C('USER_AUTH_KEY')] = $_POST['user_id'];
        }
    }

    public function delete_shop()
    {
        $seller = M('seller');
        $seller_id = I('post.seller_id');
        $user = $seller->where('id=' . $seller_id)->find();
        if ($user == NULL) {
            $this->ajaxError('此商户不存在！');
            exit();
        }
        if ($user != NULL) {
            IF ($user['status'] == 0) {
                
                $this->ajaxError('商户已存在,不能删除！');
                exit();
            }
        }
        $seller->where('id=' . $seller_id)->delete();
        $data['info'] = '删除成功！';
        $data['msg'] = '删除成功！';
        $data['status'] = 1;
        
        $this->ajaxReturn($data);
        exit();
    }

    public function open_seller_step1()
    {
        $fck = M('fck');
        $seller = M('seller');
        $is_mobile = I('is_mobile');
        $seller_id = I('post.seller_id');
        $history_url = I('post.history_url');
        $id = I('post.user_id');
        $seller_type = I('post.seller_type', 0);
        
        $huikui_id = I('huikui_id', 0);
        $licence_no = I('post.licence_no');
        $licence_title = I('post.licence_title');
        $shop_title = I('post.shop_title');
        $avatar = I('post.avatar');
        $category_ids = I('category_ids');
        if (Empty($shop_title)) {
            
            $this->ajaxError('对不起，请输入商户名！');
            exit();
        }
        
        IF ($seller_type == 1) {
            if (Empty($licence_no)) {
                
                // $this->ajaxError('对不起，请输入营业执照号！');
                // exit();
            }
        }
        if (Empty($category_ids)) {
            
            // $this->error('对不起，请选择类别！');
            // exit();
        }
        
        $user = $seller->where('id=' . $seller_id)->find();
        if ($user != NULL) {
            $seller_type = $user['seller_type'];
            IF ($seller_type == 0) {
                if ($user['auth_status'] == 1 && $user['status'] == 1 && ! empty($user['card_img8'])) {
                    $this->ajaxError('实名认证正在审核中,不可编辑！');
                    exit();
                }
            }
            IF ($seller_type == 1) {
                if ($user['auth_status'] == 1 && $user['status'] == 1 && ! empty($user['card_img12'])) {
                    $this->ajaxError('实名认证正在审核中,不可编辑！');
                    exit();
                }
            }
            IF ($seller_type == 2) {
                if ($user['auth_status'] == 1 && $user['status'] == 1 && ! empty($user['card_img8'])) {
                    $this->ajaxError('实名认证正在审核中,不可编辑！');
                    exit();
                }
            }
            if ($user['auth_status'] == 0) {
                $this->ajaxError('商户已实名认证,不可编辑！');
                exit();
            }
        }
        // $user['category_ids'] = $category_ids;
        $user['huikui_id'] = $huikui_id;
        $user['title'] = $shop_title;
        $user['licence_no'] = $licence_no;
        $user['licence_title'] = $licence_title;
        $user['img'] = $avatar;
        $user['user_id'] = $id;
        $user['auth_status'] = 1;
        $user['seller_type'] = $seller_type;
        IF ($seller_type == 0) {
            IF (! EMPTY($user['title']) && ! EMPTY($user['sn']) && ! EMPTY($user['card_img8']) && ! EMPTY($user['zhihang'])) {
                if (preg_match("/1/", $user['auth_check_status'])) {} else {
                    $user['auth_status'] = 1;
                    $user['status'] = 1;
                }
            }
        }
        IF ($seller_type == 1) {
            IF (! EMPTY($user['title']) && ! EMPTY($user['sn']) && ! EMPTY($user['card_img12']) && ! EMPTY($user['zhihang'])) {
                if (preg_match("/1/", $user['auth_check_status'])) {} else {
                    $user['auth_status'] = 1;
                    $user['status'] = 1;
                }
            }
        }
        IF ($seller_type == 2) {
            IF (! EMPTY($user['title']) && ! EMPTY($user['sn']) && ! EMPTY($user['card_img8']) && ! EMPTY($user['zhihang'])) {
                if (preg_match("/1/", $user['auth_check_status'])) {} else {
                    $user['auth_status'] = 1;
                    $user['status'] = 1;
                }
            }
        }
        
        if ($seller_id == 0) {
            // $user['seller_no'] = 'BD' . rand(1000000000, 2000000000);
            $user['status'] = 4;
            $user['add_time'] = time();
            $id = $seller->add($user);
            $seller_id = $id;
        } else {
            $seller_model = M('seller')->where('sn="' . $user['sn'] . '" and (status=0 or status=1)')->find();
            
            if ($seller_model != NULL) {
                
                IF (($seller_model['status'] == 0 || $seller_model['status'] == 1) && $seller_model['id'] != $user['id']) {
                    $this->ajaxError('对不起，SN编号已被选！');
                    exit();
                }
            }
            $user['update_time'] = time();
            $seller->where('id=' . $seller_id)->save($user);
            
            $auth_error = '';
            $auth_check_status = $user['auth_check_status'];
            $auth_check_label = $user['auth_check_label'];
            $auth_check_label = explode("|", $auth_check_label);
            $is_auth_error = 0;
            if (! EMPTY($auth_check_status)) {
                $auth_check_status = explode("|", $auth_check_status);
                foreach ($auth_check_status as $k => $item) {
                    if ($item == 1 && ($auth_check_label[$k] == '商户名称')) {
                        $auth_check_status[$k] = 0;
                    }
                }
                $auth_check_status = implode("|", $auth_check_status);
                $seller->where('id=' . $seller_id)->setField('auth_check_status', $auth_check_status);
                $is_auth_error = 1;
            }
        }
        update_seller_auth_status($seller_id);
        $user = $seller->where('id=' . $seller_id)->find();
        $user = get_seller_info($user);
        
        $data['info'] = '保存成功！';
        $data['msg'] = '保存成功！';
        $data['status'] = 1;
        $user = $seller->where('id=' . $seller_id)->find();
        $data['seller'] = $user;
        $data['title'] = '2/4 身份信息';
        // if (empty($history_url)) {
        $history_url = 'shop_base';
        // }
        
        $data['url'] = '' . $history_url . '.html';
        IF ($is_auth_error == 1) {
            
            $data['url'] = '';
        }
        $this->ajaxReturn($data);
        exit();
    }

    public function open_seller()
    {
        $fck = M('fck');
        $is_mobile = I('is_mobile');
        $id = I('post.user_id');
        $seller_id = I('post.seller_id');
        $user = $fck->where('id=' . $seller_id)->find();
        
        $shop_type = I('shop_type');
        if ($user['u_level'] == 3) {
            $this->ajaxError('您已认证,无需认证！');
            exit();
        }
        
        if ($user['shop_type'] == 1 && $user['u_level'] != 3) {
            // $this->error('个人合伙人认证正在审核中！');
            // exit();
        }
        if ($user['shop_type'] == 2 && $user['u_level'] != 3) {
            // $this->error('企业合伙人认证正在审核中！');
            // exit();
        }
        
        if ($shop_type == 1 && $user['shop_type'] == 2) {
            
            $this->ajaxError('您是企业合伙人,不可认证个人类型！');
            exit();
        }
        if ($user['auth_status'] == 1) {
            
            $this->ajaxError('正在审核,请等待！');
            exit();
        }
        $user = $fck->where('id=' . $id)->find();
        
        $user['shop_type'] = $shop_type;
        
        $fck->where('id=' . $id)->save($user);
        $user = $fck->where('id=' . $id)->find();
        $user = get_seller_info($user);
        
        $data['info'] = '开始填写认证资料！';
        $data['msg'] = '开始填写认证资料！';
        $data['status'] = 1;
        $data['seller'] = $user;
        if (EMPTY($user['shop_title'])) {
            $data['url'] = 'introduction.html';
            $data['title'] = '1/4 自我介绍';
        } else {
            
            $data['url'] = 'details.html';
            $data['title'] = '认证详情';
        }
        
        $this->ajaxReturn($data);
        exit();
    }

    public function open_seller_step2()
    {
        $fck = M('fck');
        $seller = M('seller');
        $is_mobile = I('is_mobile', 1);
        $history_url = I('history_url');
        $user_id = I('post.user_id');
        $id = I('post.seller_id');
        $seller_type = I('post.seller_type');
        $huikui_id = I('huikui_id');
        
        $province = I("txtProvince");
        $city = I("txtCity");
        $area = I("txtArea");
        $address = I("address");
        $card_begin_time = I("card_begin_time");
        $card_end_time = I("card_end_time");
        $user_name = I("user_name");
        $mobile = I("mobile");
        $sn = I("sn");
        $sn = explode('(', $sn);
        $sn = $sn[0];
        
        $card_no = I("card_no");
        $credit_mobile = I('credit_mobile');
        $credit_card_no = I('credit_card_no');
        $fan_type = I('fan_type', 1);
        
        $user = $seller->where('id=' . $id)->find();
        if (Empty($province)) {
            
            $this->ajaxError('对不起，请选择地址！');
            exit();
        }
        if (Empty($address)) {
            
            $this->ajaxError('对不起，请输入详细地址！');
            exit();
        }
        if (Empty($user_name)) {
            
            $this->ajaxError('对不起，请输入联系人！');
            exit();
        }
        if (Empty($mobile)) {
            
            $this->ajaxError('对不起，请输入联系电话！');
            exit();
        }
        if (Empty($sn)) {
            
            $this->ajaxError('对不起，请输入SN编号！');
            exit();
        }
        
        if (Empty($sn)) {
            
            $this->ajaxError('对不起，请输入SN编号！');
            exit();
        }
        if (strpos($sn, '机器') !== false) {
            $this->ajaxError('对不起，请选择SN编号！');
            exit();
        }
        // $sn_count = M('seller')->where(ARRAY(
        // 'sn' => $sn
        // ))->count();
        
        // if ($sn_count > 0 && $user['card_img8'] != null && $user['status'] == 0) {
        
        // $this->ajaxError('对不起，SN编号已被选！');
        // exit();
        // }
        
        $seller_model = M('seller')->where('sn="' . $sn . '"')->find();
        
        if ($seller_model != NULL) {
            
            IF (($seller_model['status'] == 0 || $seller_model['status'] == 1) && $seller_model['sn'] != $sn) {
                $this->ajaxError('对不起，SN编号已被选！');
                exit();
            }
        }
        
        if (Empty($card_no)) {
            
            $this->ajaxError('对不起，请输入身份证号码！');
            exit();
        }
        if (! validation_filter_id_card($card_no)) {
            
            $this->ajaxError('对不起，身份证号码不正确！');
            exit();
        }
        
        $sn_model = M('user_terminal')->where(array(
            'sn' => $sn
        ))->find();
        
        $user = $seller->where('id=' . $id)->find();
        if ($user != NULL) {
            $seller_type = $user['seller_type'];
            IF ($seller_type == 0) {
                if ($user['auth_status'] == 1 && $user['status'] == 1 && ! empty($user['card_img8'])) {
                    $this->ajaxError('实名认证正在审核中,不可编辑！');
                    exit();
                }
            }
            IF ($seller_type == 1) {
                if ($user['auth_status'] == 1 && $user['status'] == 1 && ! empty($user['card_img12'])) {
                    $this->ajaxError('实名认证正在审核中,不可编辑！');
                    exit();
                }
            }
            IF ($seller_type == 2) {
                if ($user['auth_status'] == 1 && $user['status'] == 1 && ! empty($user['card_img8'])) {
                    $this->ajaxError('实名认证正在审核中,不可编辑！');
                    exit();
                }
            }
            if ($user['auth_status'] == 0) {
                $this->ajaxError('商户已实名认证,不可编辑！');
                exit();
            }
        }
        $user['card_begin_time'] = $card_begin_time;
        $user['card_end_time'] = $card_end_time;
        $user['huikui_id'] = $huikui_id;
        $user['seller_type'] = $seller_type;
        $user['fan_type'] = $fan_type;
        $user['province'] = $province;
        $user['city'] = $city;
        $user['area'] = $area;
        $user['address'] = $address;
        $user['user_name'] = $user_name;
        $user['mobile'] = $mobile;
        $user['sn'] = $sn;
        
        if ($sn_model['terminal_type'] == 0 && ($seller_type == 1 || $seller_type == 2)) {
            $this->ajaxError('传统POS不允许录入二维码商户！');
            exit();
        }
        
        if ($sn_model['terminal_type'] == 1 && ($seller_type == 0)) {
            $this->ajaxError('二维码POS不允许录入传统商户！');
            exit();
        }
        
        $user['card_no'] = $card_no;
        $user['user_id'] = $user_id;
        $user['credit_card_no'] = $credit_card_no;
        $user['credit_mobile'] = $credit_mobile;
        $user['auth_status'] = 1;
        if ($sn_model != NULL) {
            $user['user_terminal_id'] = $sn_model['id'];
        }
        IF ($seller_type == 0) {
            IF (! EMPTY($user['title']) && ! EMPTY($user['sn']) && ! EMPTY($user['card_img8']) && ! EMPTY($user['zhihang'])) {
                if (preg_match("/1/", $user['auth_check_status'])) {} else {
                    $user['auth_status'] = 1;
                    $user['status'] = 1;
                }
            }
        }
        
        IF ($seller_type == 1) {
            IF (! EMPTY($user['title']) && ! EMPTY($user['sn']) && ! EMPTY($user['card_img12']) && ! EMPTY($user['zhihang'])) {
                if (preg_match("/1/", $user['auth_check_status'])) {} else {
                    $user['auth_status'] = 1;
                    $user['status'] = 1;
                }
            }
        }
        IF ($seller_type == 2) {
            IF (! EMPTY($user['title']) && ! EMPTY($user['sn']) && ! EMPTY($user['card_img8']) && ! EMPTY($user['zhihang'])) {
                if (preg_match("/1/", $user['auth_check_status'])) {} else {
                    $user['auth_status'] = 1;
                    $user['status'] = 1;
                }
            }
        }
        if ($id == 0) {
            
            // $user['seller_no'] = 'BD' . rand(1000000000, 2000000000);
            $user['status'] = 4;
            $user['add_time'] = time();
            
            $sn_model = M('user_terminal')->where(array(
                'sn' => $sn
            ))->find();
            if ($sn_model != null) {
                // $user['min_day'] = $sn_model['shop_expire_day'];
                // $user['fan_money']= $sn_model['shop_fan_money'];
                // $user['min_fan_money'] = $sn_model['shop_min_fan_money'];
            }
            
            $id = $seller->add($user);
            
            M('user_terminal')->where(array(
                'sn' => $sn
            ))->setField('seller_id', $id);
        } else {
            
            $seller_model = M('seller')->where('sn="' . $user['sn'] . '" and (status=0 or status=1)')->find();
            
            if ($seller_model != NULL) {
                
                IF (($seller_model['status'] == 0 || $seller_model['status'] == 1) && $seller_model['id'] != $user['id']) {
                    $this->ajaxError('对不起，SN编号已被选！');
                    exit();
                }
            }
            
            $user['update_time'] = time();
            $seller->where('id=' . $id)->save($user);
        }
        $auth_error = '';
        $auth_check_status = $user['auth_check_status'];
        $auth_check_label = $user['auth_check_label'];
        $auth_check_label = explode("|", $auth_check_label);
        
        $is_auth_error = 0;
        
        if (! EMPTY($auth_check_status)) {
            
            $auth_check_status = explode("|", $auth_check_status);
            foreach ($auth_check_status as $k => $item) {
                if ($item == 1 && ($auth_check_label[$k] == '身份证号' || $auth_check_label[$k] == 'sn' || $auth_check_label[$k] == '所在省市区' || $auth_check_label[$k] == '真实姓名' || $auth_check_label[$k] == '手机号码')) {
                    $auth_check_status[$k] = 0;
                }
            }
            $auth_check_status = implode("|", $auth_check_status);
            $seller->where('id=' . $id)->setField('auth_check_status', $auth_check_status);
            $is_auth_error = 1;
        }
        
        update_seller_auth_status($id);
        $user = $seller->where('id=' . $id)->find();
        $user = get_seller_info($user);
        
        set_seller_sns($user['user_id']);
        $fck = M('fck')->field('seller_sn,id')
            ->where('id=' . $user['user_id'])
            ->find();
        
        $str = '';
        IF (! EMPTY($fck['seller_sn'])) {
            $str = ' AND sn not in (' . $fck['seller_sn'] . ') ';
        }
        
        $form = M('user_terminal')->field('id,sn,sn_type')
            ->where("   uid=" . $fck['id'] . "  " . $str)
            ->select();
        
        $fck['user_terminal'] = $form;
        
        $data['info'] = '保存成功！';
        $data['msg'] = '保存成功！';
        $data['status'] = 1;
        $data['seller'] = $user;
        $data['user_terminal'] = $fck['user_terminal'];
        if (empty($history_url)) {
            $history_url = 'shop_account';
        }
        $data['url'] = '' . $history_url . '.html';
        IF ($is_auth_error == 1) {
            
            $data['url'] = '';
        }
        $data['title'] = '3/4 收款信息';
        $this->ajaxReturn($data);
        exit();
    }

    public function open_seller_step3()
    {
        $fck = M('seller');
        $is_mobile = I('is_mobile');
        $history_url = I('history_url');
        $id = I('post.seller_id', 11459);
        $user_id = I('post.user_id', 10956);
        $seller_type = I('post.seller_type', 0);
        $huikui_id = I('huikui_id', 0);
        
        $card_no = I('card_no');
        $card_img1 = I('card_img1', 1);
        $card_img2 = I('card_img2', 1);
        $card_img3 = I('card_img3', 1);
        $card_img4 = I('card_img4', 1);
        $card_img5 = I('card_img5', 1);
        $card_img6 = I('card_img6', 1);
        $card_img7 = I('card_img7', 1);
        $card_img8 = I('card_img8', 1);
        $card_img9 = I('card_img9', 1);
        $card_img10 = I('card_img10');
        $card_img11 = I('card_img11');
        $card_img12 = I('card_img12');
        $card_img13 = I('card_img13');
        $card_img14 = I('card_img14');
        $user_name = I('user_name');
        $title = I('title');
        $phone = I('phone');
        $credit_mobile = I('credit_mobile');
        $credit_card_no = I('credit_card_no');
        
        if (Empty($card_no)) {
            
            // $this->error('对不起，请输入您的身份证号！');
            // exit();
        }
        if (Empty($credit_mobile)) {
            
            // $this->error('对不起，请输入您的信用卡预留电话！');
            // exit();
        }
        if (Empty($user_name)) {
            
            // $this->error('对不起，请输入您的姓名！');
            // exit();
        }
        if (Empty($card_img1)) {
            
            $this->ajaxError('对不起，请上传身份证正面照！');
            exit();
        }
        if (Empty($card_img2)) {
            
            $this->ajaxError('对不起，请上传身份证反面照！');
            exit();
        }
        IF ($seller_type != 2) {
            if (Empty($card_img3)) {
                
                $this->ajaxError('对不起，请上传手持身份证正面照！');
                exit();
            }
        }
        if (Empty($card_img4)) {
            
            // $this->error('对不起，请上传手持身份证反面照！');
            // exit();
        }
        if (Empty($card_img5)) {
            
            // $this->error('对不起，请上传手持银行卡正面照！');
            // exit();
        }
        if (Empty($card_img6)) {
            
            // $this->error('对不起，请上传手持银行卡反面照！');
            // exit();
        }
        
        if (Empty($card_img7)) {
            
            $this->ajaxError('对不起，请上传银行卡正面照！');
            exit();
        }
        if (Empty($card_img8)) {
            
            $this->ajaxError('对不起，请上传银行卡反面照！');
            exit();
        }
        if (Empty($card_img9)) {
            
            // $this->error('对不起，请上传信用卡照片！');
            // exit();
        }
        
        IF ($seller_type == 1) {
            if (Empty($card_img10)) {
                
                $this->ajaxError('对不起，请上传经营场所照！');
                exit();
            }
            if (Empty($card_img11)) {
                
                $this->ajaxError('对不起，请上传门头照！');
                exit();
            }
            if (Empty($card_img12)) {
                
                $this->ajaxError('对不起，请上传收银台照！');
                exit();
            }
            
            // if (Empty($card_img13)) {
            
            // $this->ajaxError('对不起，请上传特约商户支付服务协议照！');
            // exit();
            // }
            // if (Empty($card_img5)) {
            
            // $this->ajaxError('对不起，请上传特约商户支付服务协议照！');
            // exit();
            // }
            // if (Empty($card_img6)) {
            
            // $this->ajaxError('对不起，请上传商户信息表照！');
            // exit();
            // }
        }
        
        if (Empty($phone)) {
            
            // $this->error('对不起，请输入现用电话！');
            // exit();
        }
        $user = $fck->where('id=' . $id)->find();
        if ($user != NULL) {
            
            $seller_type = $user['seller_type'];
            if (empty($user['title'])) {
                $this->ajaxError('请先完成第一步！');
                exit();
            }
            
            if (empty($user['sn'])) {
                $this->ajaxError('请先完成第二步！');
                exit();
            }
            if (empty($user['zhihang'])) {
                $this->ajaxError('请先完成第三步！');
                exit();
            }
            
            IF ($seller_type == 0) {
                if ($user['auth_status'] == 1 && $user['status'] == 1 && ! empty($user['card_img8'])) {
                    $this->ajaxError('实名认证正在审核中,不可编辑！');
                    exit();
                }
            }
            IF ($seller_type == 1) {
                if ($user['auth_status'] == 1 && $user['status'] == 1 && ! empty($user['card_img12'])) {
                    $this->ajaxError('实名认证正在审核中,不可编辑！');
                    exit();
                }
            }
            IF ($seller_type == 2) {
                if ($user['auth_status'] == 1 && $user['status'] == 1 && ! empty($user['card_img8'])) {
                    $this->ajaxError('实名认证正在审核中,不可编辑！');
                    exit();
                }
            }
            if ($user['auth_status'] == 0) {
                $this->ajaxError('商户已实名认证,不可编辑！');
                exit();
            }
        }
        $user['huikui_id'] = $huikui_id;
        $user['seller_type'] = $seller_type;
        $user['user_id'] = $user_id;
        $user['card_img1'] = $card_img1;
        $user['card_img2'] = $card_img2;
        $user['card_img3'] = $card_img3;
        $user['card_img4'] = $card_img4;
        $user['card_img5'] = $card_img5;
        $user['card_img6'] = $card_img6;
        $user['card_img7'] = $card_img7;
        $user['card_img8'] = $card_img8;
        $user['card_img9'] = $card_img9;
        $user['card_img10'] = $card_img10;
        $user['card_img11'] = $card_img11;
        $user['card_img12'] = $card_img12;
        $user['card_img13'] = $card_img13;
        $user['card_img14'] = $card_img14;
        $user['auth_status'] = 1;
        
        IF ($seller_type == 0) {
            IF (! EMPTY($user['title']) && ! EMPTY($user['sn']) && ! EMPTY($user['card_img8']) && ! EMPTY($user['zhihang'])) {
                if (preg_match("/1/", $user['auth_check_status'])) {} else {
                    $user['auth_status'] = 1;
                    $user['status'] = 1;
                }
            }
        }
        IF ($seller_type == 1) {
            IF (! EMPTY($user['title']) && ! EMPTY($user['sn']) && ! EMPTY($user['card_img12']) && ! EMPTY($user['zhihang'])) {
                if (preg_match("/1/", $user['auth_check_status'])) {} else {
                    $user['auth_status'] = 1;
                    $user['status'] = 1;
                }
            }
        }
        IF ($seller_type == 2) {
            IF (! EMPTY($user['title']) && ! EMPTY($user['sn']) && ! EMPTY($user['card_img8']) && ! EMPTY($user['zhihang'])) {
                if (preg_match("/1/", $user['auth_check_status'])) {} else {
                    $user['auth_status'] = 1;
                    $user['status'] = 1;
                }
            }
        }
        if ($id == 0) {
            
            // $user['seller_no'] = 'BD' . rand(1000000000, 2000000000);
            $user['status'] = 4;
            $user['add_time'] = time();
            $id = $fck->where('id=' . $id)->add($user);
        } else {
            $seller_model = M('seller')->where('sn="' . $user['sn'] . '" and (status=0 or status=1)')->find();
            
            if ($seller_model != NULL) {
                
                IF (($seller_model['status'] == 0 || $seller_model['status'] == 1) && $seller_model['id'] != $user['id']) {
                    $this->ajaxError('对不起，SN编号已被选！');
                    exit();
                }
            }
            
            $user['update_time'] = time();
            $fck->where('id=' . $id)->save($user);
        }
        $auth_error = '';
        $auth_check_status = $user['auth_check_status'];
        $auth_check_label = $user['auth_check_label'];
        $auth_check_label = explode("|", $auth_check_label);
        
        $is_auth_error = 0;
        if (! EMPTY($auth_check_status)) {
            
            $auth_check_status = explode("|", $auth_check_status);
            foreach ($auth_check_status as $k => $item) {
                IF ($seller_type == 0) {
                    if ($item == 1 && ($auth_check_label[$k] == '现用电话' || $auth_check_label[$k] == '信用卡图片' || $auth_check_label[$k] == '手持银行卡正面照片' || $auth_check_label[$k] == '手持银行卡反面图片' || $auth_check_label[$k] == '身份证正面图片' || $auth_check_label[$k] == '身份证背面面图片' || $auth_check_label[$k] == '手持身份证正面图片' || $auth_check_label[$k] == '手持身份证反面图片' || $auth_check_label[$k] == '银行卡正面图片' || $auth_check_label[$k] == '银行卡反面图片')) {
                        $auth_check_status[$k] = 0;
                    }
                }
                IF ($seller_type == 1) {
                    if ($item == 1 && ($auth_check_label[$k] == '收银台照片' || $auth_check_label[$k] == '经营场所照片' || $auth_check_label[$k] == '门头照' || $auth_check_label[$k] == '现用电话' || $auth_check_label[$k] == '信用卡图片' || $auth_check_label[$k] == '手持银行卡正面照片' || $auth_check_label[$k] == '手持银行卡反面图片' || $auth_check_label[$k] == '身份证正面图片' || $auth_check_label[$k] == '身份证背面面图片' || $auth_check_label[$k] == '手持身份证正面图片' || $auth_check_label[$k] == '手持身份证反面图片' || $auth_check_label[$k] == '银行卡正面图片' || $auth_check_label[$k] == '银行卡反面图片')) {
                        $auth_check_status[$k] = 0;
                    }
                }
                IF ($seller_type == 2) {
                    if ($item == 1 && ($auth_check_label[$k] == '收银台照片' || $auth_check_label[$k] == '经营场所照片' || $auth_check_label[$k] == '门头照' || $auth_check_label[$k] == '现用电话' || $auth_check_label[$k] == '信用卡图片' || $auth_check_label[$k] == '手持银行卡正面照片' || $auth_check_label[$k] == '手持银行卡反面图片' || $auth_check_label[$k] == '身份证正面图片' || $auth_check_label[$k] == '身份证背面面图片' || $auth_check_label[$k] == '手持身份证正面图片' || $auth_check_label[$k] == '手持身份证反面图片' || $auth_check_label[$k] == '银行卡正面图片' || $auth_check_label[$k] == '银行卡反面图片')) {
                        $auth_check_status[$k] = 0;
                    }
                }
            }
            $auth_check_status = implode("|", $auth_check_status);
            $fck->where('id=' . $id)->setField('auth_check_status', $auth_check_status);
            $is_auth_error = 1;
        }
        
        update_seller_auth_status($id);
        $user = $fck->where('id=' . $id)->find();
        $user = get_seller_info($user);
        $data['info'] = '提交成功,请等待审核！';
        $data['msg'] = '提交成功,请等待审核！';
        $data['status'] = 1;
        $data['seller'] = $user;
        $data['title'] = '待审核';
        if (empty($history_url)) {
            $history_url = 'check_shop_list';
        }
        
        $data['url'] = '' . $history_url . '.html';
        IF ($is_auth_error == 1) {
            
            $data['url'] = '';
        }
        $this->ajaxReturn($data);
        exit();
    }

    public function open_seller_step4()
    {
        $fck = M('seller');
        $is_mobile = I('is_mobile');
        $history_url = I('history_url');
        $id = I('post.seller_id');
        $seller_type = I('post.seller_type', 0);
        $user_id = I('post.user_id');
        $title= I('post.title');
        $province = $this->_post("txtProvince");
        $city = $this->_post("txtCity");
        $area = $this->_post("txtArea");
        $address = $this->_post("txtAddress");
        $txtMobile = I('txtMobile');
        $img_url = I('img_url');
        
//         $this->ajaxError('对不起，'.$img_url);
//         exit();
        
        if (Empty($title)) {
            
            $this->ajaxError('对不起，请输入店铺名称！');
            exit();
        }
        
        if (Empty($txtMobile)) {
            
            $this->ajaxError('对不起，请输入您的联系方式！');
            exit();
        }
         
        if (Empty($area)) {
            
            $this->ajaxError('对不起，请选择省市区！');
            exit();
        }
        if (Empty($address)) {
            
            $this->ajaxError('对不起，请输入详细地址！');
            exit();
        }
        // if (Empty($card_img2)) {
        
        // $this->error('对不起，请上传身份证反面照！');
        // exit();
        // }
        $user = $fck->where('id=' . $id)->find();
        
        if ($user != NULL) {
            $seller_type = $user['seller_type'];
            IF ($seller_type == 0) {
                if ($user['auth_status'] == 1 && $user['status'] == 1 && ! empty($user['phone'])) {
                    $this->ajaxError('店铺认证正在审核中,不可编辑！');
                    exit();
                }
            }
            
            if ($user['auth_status'] == 0) {
//                 $this->ajaxError('店铺已实名认证,不可编辑！');
//                 exit();
            }
        } 
        $user['seller_type'] = $seller_type;
        $user['title'] = $title;
        $user['phone'] = $txtMobile; 
        
        $user['address'] = $address;
        $user['province'] = $province;
        $user['city'] = $city;
        $user['area'] = $area;
        $user['user_id'] = $user_id;
        $user['img'] = $img_url;
//         $user['auth_status'] = 1;
        
        
        
        if ($id == 0) {
            
            // $user['seller_no'] = 'BD' . rand(1000000000, 2000000000);
            $user['status'] = 1;
            $user['add_time'] = time();
            $seller_id = $fck->where('id=' . $id)->add($user);
            $id = $seller_id;
        } else { 
            
            
            
            $user['update_time'] = time();
//             $user['status'] = 1;
            $fck->where('id=' . $id)->save($user);
        }
        
        
        
        $user = $fck->where('id=' . $id)->find();
        $data['info'] = '提交成功,请等待审核！';
        $data['msg'] = '提交成功,请等待审核！';
        
        if ($user['auth_status'] == 0) {
            $data['info'] = '修改成功！';
            $data['msg'] = '修改成功！';
            
        }
        
        $data['status'] = 1;
        $data['seller'] = $user;
         
        $this->ajaxReturn($data);
        exit();
    }

    public function open_shop()
    {
        $fck = M('fck');
        $is_mobile = I('is_mobile');
        $id = I('post.user_id');
        $user = $fck->where('id=' . $id)->find();
        
        $shop_type = I('shop_type');
        if ($user['u_level'] == 3) {
            $this->ajaxError('您已是合伙人无需认证！');
            exit();
        }
        
        if ($user['shop_type'] == 1 && $user['u_level'] != 3) {
            // $this->error('个人合伙人认证正在审核中！');
            // exit();
        }
        if ($user['shop_type'] == 2 && $user['u_level'] != 3) {
            // $this->error('企业合伙人认证正在审核中！');
            // exit();
        }
        
        if ($shop_type == 1 && $user['shop_type'] == 2) {
            
            $this->ajaxError('您是企业合伙人,不可认证个人类型！');
            exit();
        }
        if ($user['auth_status'] == 1) {
            
            $this->ajaxError('正在审核,请等待！');
            exit();
        }
        $user = $fck->where('id=' . $id)->find();
        
        $user['shop_type'] = $shop_type;
        
        $fck->where('id=' . $id)->save($user);
        $user = $fck->where('id=' . $id)->find();
        $user = get_user_info($user, $id);
        
        $data['info'] = '开始填写认证资料！';
        $data['msg'] = '开始填写认证资料！';
        $data['status'] = 1;
        $data['user'] = $user;
        if (EMPTY($user['shop_title'])) {
            $data['url'] = 'introduction.html';
            $data['title'] = '1/4 自我介绍';
        } else {
            
            $data['url'] = 'details.html';
            $data['title'] = '认证详情';
        }
        
        $this->ajaxReturn($data);
        exit();
    }

    public function open_shop_step1()
    {
        $fck = M('fck');
        $is_mobile = I('is_mobile');
        $history_url = I('history_url');
        $id = I('post.user_id');
        
        $shop_title = I('shop_title');
        $avatar = I('avatar');
        $category_ids = I('category_ids');
        
        if (Empty($shop_title)) {
            
            $this->ajaxError('对不起，请输入您的名字！');
            exit();
        }
        if (Empty($category_ids)) {
            
            // $this->error('对不起，请选择类别！');
            // exit();
        }
        
        $user = $fck->where('id=' . $id)->find();
        if ($user['auth_status'] == 1) {
            $this->ajaxError('实名认证正在审核中,不可编辑！');
            exit();
        }
        if ($user['auth_status'] == 0 && $user['u_level'] == 3) {
            $this->ajaxError('您已实名认证,不可编辑！');
            exit();
        }
        // $user['category_ids'] = $category_ids;
        $user['shop_title'] = $shop_title;
        $user['avatar'] = $avatar;
        
        $fck->where('id=' . $id)->save($user);
        
        $auth_error = '';
        $auth_check_status = $user['auth_check_status'];
        $auth_check_label = $user['auth_check_label'];
        $auth_check_label = explode("|", $auth_check_label);
        $is_auth_error = 0;
        if (! EMPTY($auth_check_status)) {
            
            $auth_check_status = explode("|", $auth_check_status);
            foreach ($auth_check_status as $k => $item) {
                if ($item == 1 && ($auth_check_label[$k] == '昵称')) {
                    $auth_check_status[$k] = 0;
                }
            }
            $auth_check_status = implode("|", $auth_check_status);
            $fck->where('id=' . $id)->setField('auth_check_status', $auth_check_status);
            $is_auth_error = 1;
        }
        
        update_auth_status($id);
        $user = $fck->where('id=' . $id)->find();
        $user = get_user_info($user, $id);
        
        $data['info'] = '保存成功！';
        $data['msg'] = '保存成功！';
        $data['status'] = 1;
        // $data['url'] = 'base.html';
        $data['title'] = '2/4 身份信息';
        // if (empty($history_url)) {
        $history_url = 'base';
        // }
        
        $data['url'] = '' . $history_url . '.html';
        IF ($is_auth_error == 1) {
            
            $data['url'] = '';
        }
        $this->ajaxReturn($data);
        exit();
    }

    public function open_shop_step2()
    {
        $fck = M('fck');
        $is_mobile = I('is_mobile');
        $history_url = I('history_url');
        $id = I('post.user_id');
        
        $province = $this->_post("txtProvince");
        $city = $this->_post("txtCity");
        $area = $this->_post("txtArea");
        $address = $this->_post("txtAddress");
        $name = $this->_post("name");
        $mobile = $this->_post("mobile");
        
        $user = $fck->where('id=' . $id)->find();
        if (Empty($province)) {
            
            $this->ajaxError('对不起，请选择地址！');
            exit();
        }
        if (Empty($name)) {
            
            $this->ajaxError('对不起，请输入联系人！');
            exit();
        }
        if (Empty($mobile)) {
            
            $this->ajaxError('对不起，请输入联系电话！');
            exit();
        }
        
        if ($user['auth_status'] == 1) {
            $this->ajaxError('实名认证正在审核中,不可编辑！');
            exit();
        }
        if ($user['auth_status'] == 0 && $user['u_level'] == 3) {
            $this->ajaxError('您已实名认证,不可编辑！');
            exit();
        }
        $user['province'] = $province;
        $user['city'] = $city;
        $user['area'] = $area;
        $user['address'] = $address;
        $user['name'] = $name;
        $user['user_tel'] = $mobile;
        // $user['license_img1'] = $license_img1;
        // $user['license_img2'] = $license_img2;
        // $user['card_img1'] = $card_img1;
        // $user['card_img2'] = $card_img2;
        // $user['card_img3'] = $card_img3;
        // $user['card_img4'] = $card_img4;
        
        $fck->where('id=' . $id)->save($user);
        
        $auth_error = '';
        $auth_check_status = $user['auth_check_status'];
        $auth_check_label = $user['auth_check_label'];
        $auth_check_label = explode("|", $auth_check_label);
        
        $is_auth_error = 0;
        
        if (! EMPTY($auth_check_status)) {
            
            $auth_check_status = explode("|", $auth_check_status);
            foreach ($auth_check_status as $k => $item) {
                if ($item == 1 && ($auth_check_label[$k] == '所在省市区' || $auth_check_label[$k] == '真实姓名' || $auth_check_label[$k] == '手机号码')) {
                    $auth_check_status[$k] = 0;
                }
            }
            $auth_check_status = implode("|", $auth_check_status);
            $fck->where('id=' . $id)->setField('auth_check_status', $auth_check_status);
            $is_auth_error = 1;
        }
        
        update_auth_status($id);
        $user = $fck->where('id=' . $id)->find();
        $user = get_user_info($user, $id);
        $data['info'] = '保存成功！';
        $data['msg'] = '保存成功！';
        $data['status'] = 1;
        $data['user'] = $user;
        if (empty($history_url)) {
            $history_url = 'card';
        }
        $data['url'] = '' . $history_url . '.html';
        IF ($is_auth_error == 1) {
            
            $data['url'] = '';
        }
        $data['title'] = '3/4 资质信息';
        $this->ajaxReturn($data);
        exit();
    }

    public function open_shop_step3()
    {
        $fck = M('fck');
        $is_mobile = I('is_mobile');
        $history_url = I('history_url');
        $id = I('post.user_id');
        
        $card_no = I('card_no');
        $card_img1 = I('card_img1');
        $card_img2 = I('card_img2');
        $card_img3 = I('card_img3');
        $card_img4 = I('card_img4');
        $user_name = I('user_name');
        $bank_name = I('bank_name');
        
        $province = $this->_post("txtProvince");
        $city = $this->_post("txtCity");
        $area = $this->_post("txtArea");
        $address = $this->_post("txtAddress");
        $bank = I('bank_name');
        $bank_card = I('bank_card');
        $alipay = I('alipay');
        if (Empty($card_no)) {
            
            $this->ajaxError('对不起，请输入您的身份证号！');
            exit();
        }
        if (Empty($user_name)) {
            
            $this->ajaxError('对不起，请输入您的姓名！');
            exit();
        }
        if (Empty($area)) {
            
            $this->ajaxError('对不起，请选择省市！');
            exit();
        }
        if (Empty($bank_name)) {
            
            $this->ajaxError('对不起，请选择所属银行！');
            exit();
        }
        if (Empty($bank_card)) {
            
            $this->ajaxError('对不起，请输入结算卡号！');
            exit();
        }
        if (Empty($card_img1)) {
            
            $this->ajaxError('对不起，请上传身份证正面照！' . $card_img1);
            exit();
        }
        if (Empty($card_img2)) {
            
            $this->ajaxError('对不起，请上传身份证反面照！' . $card_img2);
            exit();
        }
        if (Empty($card_img3)) {
            
            $this->ajaxError('对不起，请上传手持身份证照！' . $card_img3);
            exit();
        }
        if (Empty($card_img4)) {
            
            $this->ajaxError('对不起，请上传银行卡照！' . $card_img4);
            exit();
        }
        $user = $fck->where('id=' . $id)->find();
        if ($user['auth_status'] == 1) {
            $this->ajaxError('实名认证正在审核中,不可编辑！');
            exit();
        }
        if ($user['auth_status'] == 0 && $user['u_level'] == 3) {
            $this->ajaxError('您已实名认证,不可编辑！');
            exit();
        }
        $user['user_name'] = $user_name;
        $user['province'] = $province;
        $user['city'] = $city;
        $user['area'] = $area;
        $user['bank_name'] = $bank_name;
        $user['bank_province'] = $province;
        $user['bank_city'] = $city;
        $user['bank_area'] = $area;
        $user['bank_card'] = $bank_card;
        $user['card_no'] = $card_no;
        $user['card_img1'] = $card_img1;
        $user['card_img2'] = $card_img2;
        $user['card_img3'] = $card_img3;
        $user['card_img4'] = $card_img4;
        $user['alipay'] = $alipay;
        $user['shop_type'] = 2;
        $user['auth_status'] = 1;
        // $user['user_name'] = $user_name;
        
        $fck->where('id=' . $id)->save($user);
        
        $auth_error = '';
        $auth_check_status = $user['auth_check_status'];
        $auth_check_label = $user['auth_check_label'];
        $auth_check_label = explode("|", $auth_check_label);
        
        $is_auth_error = 0;
        if (! EMPTY($auth_check_status)) {
            
            $auth_check_status = explode("|", $auth_check_status);
            foreach ($auth_check_status as $k => $item) {
                if ($item == 1 && ($auth_check_label[$k] == '真实姓名' || $auth_check_label[$k] == '银行卡号' || $auth_check_label[$k] == '所在省市区' || $auth_check_label[$k] == '身份证号' || $auth_check_label[$k] == '身份证正面照' || $auth_check_label[$k] == '身份证反面照' || $auth_check_label[$k] == '手持身份证照片' || $auth_check_label[$k] == '银行卡正面照')) {
                    $auth_check_status[$k] = 0;
                }
            }
            $auth_check_status = implode("|", $auth_check_status);
            $fck->where('id=' . $id)->setField('auth_check_status', $auth_check_status);
            $is_auth_error = 1;
        }
        // $fck->where('id=' . $id)->setField('auth_status', 1);
        
        update_auth_status($id);
        $user = $fck->where('id=' . $id)->find();
        $user = get_user_info($user, $id);
        $data['info'] = '提交成功,请等待审核！';
        $data['msg'] = '提交成功,请等待审核！';
        $data['status'] = 1;
        $data['user'] = $user;
        $data['title'] = '4/4 收款信息';
        
        if (empty($history_url)) {
            $history_url = 'account';
        }
        
        $data['url'] = '' . $history_url . '.html';
        IF ($is_auth_error == 1) {
            
            $data['url'] = '';
        }
        $data['url'] = '';
        $this->ajaxReturn($data);
        exit();
    }

    public function open_shop_step4()
    {
        $fck = M('fck');
        $is_mobile = I('is_mobile');
        $history_url = I('history_url');
        $id = I('post.user_id');
        $province = $this->_post("txtProvince");
        $city = $this->_post("txtCity");
        $area = $this->_post("txtArea");
        $address = $this->_post("txtAddress");
        $bank = I('bank_name');
        $bank_card = I('bank_card');
        $img_url = I('img_url');
        
        $bank_user_name = I('bank_user_name');
        
        if (Empty($bank_card)) {
            
            $this->ajaxError('对不起，请输入您的银行卡号！');
            exit();
        }
        if (Empty($bank_user_name)) {
            
            $this->ajaxError('对不起，请输入您的开户人姓名！');
            exit();
        }
        if (Empty($bank)) {
            
            $this->ajaxError('对不起，请选择银行！');
            exit();
        }
        if (Empty($province)) {
            
            $this->ajaxError('对不起，请选择开户地址！');
            exit();
        }
        // if (Empty($card_img2)) {
        
        // $this->error('对不起，请上传身份证反面照！');
        // exit();
        // }
        $user = $fck->where('id=' . $id)->find();
        if ($user['auth_status'] == 1) {
            $this->ajaxError('实名认证正在审核中,不可编辑！');
            exit();
        }
        if ($user['auth_status'] == 0 && $user['u_level'] == 3) {
            $this->ajaxError('您已实名认证,不可编辑！');
            exit();
        }
        $user['bank_name'] = $bank;
        $user['bank_card'] = $bank_card;
        $user['bank_user_name'] = $bank_user_name;
        $user['auth_status'] = 1;
        $user['bank_province'] = $province;
        $user['bank_city'] = $city;
        $user['bank_area'] = $area;
        $user['img'] = $img_url;
        $fck->where('id=' . $id)->save($user);
        
        $auth_error = '';
        $auth_check_status = $user['auth_check_status'];
        $auth_check_label = $user['auth_check_label'];
        $auth_check_label = explode("|", $auth_check_label);
        $is_auth_error = 0;
        if (! EMPTY($auth_check_status)) {
            
            $auth_check_status = explode("|", $auth_check_status);
            foreach ($auth_check_status as $k => $item) {
                if ($item == 1 && ($auth_check_label[$k] == '银行卡号' || $auth_check_label[$k] == '开户姓名' || $auth_check_label[$k] == '开户银行' || $auth_check_label[$k] == '开户地址')) {
                    $auth_check_status[$k] = 0;
                }
            }
            $auth_check_status = implode("|", $auth_check_status);
            $fck->where('id=' . $id)->setField('auth_check_status', $auth_check_status);
            $is_auth_error = 1;
        }
        
        update_auth_status($id);
        
        $user = $fck->where('id=' . $id)->find();
        $data['info'] = '保存成功！';
        $data['msg'] = '保存成功！';
        $data['status'] = 1;
        $data['user'] = $user;
        if (empty($history_url)) {
            $history_url = 'details';
        }
        
        $data['url'] = '' . $history_url . '.html';
        IF ($is_auth_error == 1) {
            
            $data['url'] = '';
        }
        $this->ajaxReturn($data);
        exit();
    }

    public function test_getui()
    {
        $list = get_tiqu_list();
        getui_update_tixian_list();
        
        getui_update_project_count();
    }

    public function ok_profits()
    {
        $map['money_user_id'] = array(
            'gt',
            0
        );
        $map['is_money'] = array(
            'eq',
            1
        );
        import("@.ORG.Page"); // 导入分页类
        $count = M('trade_orders')->where($map)->count(); // 总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $content = M('trade_orders')->where($map)
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->order(' money_time desc ')
            ->select();
        foreach ($content as $key => $rs) {
            $array = array();
            $array['seller_no'] = trim($rs['shop_id']);
            $shop = M('seller')->field('title,user_id')
                ->where($array)
                ->find();
            $user_name = '无';
            $real_user_name = '无';
            $money = '0';
            $fck = M('fck')->field('id, user_id,u_level,re_id')
                ->where('id=' . $rs['money_user_id'])
                ->find();
            IF ($fck != NULL) {
                $user_name = $fck['user_id'];
            }
            $content[$key]['user_name'] = $user_name;
            $fck = M('fck')->field('id, user_id,u_level,re_id')
                ->where('id=' . $rs['user_id'])
                ->find();
            IF ($fck != NULL) {
                $real_user_name = $fck['user_id'];
            }
            $content[$key]['real_user_name'] = $real_user_name;
            $content[$key]['money_time_str'] = date("Y-m-d H:i:s", $rs['money_time']);
        }
        $this->assign('list', $content);
        
        $this->display();
    }

    public function ok_te_profits()
    {
        $map['money_user_id'] = array(
            'gt',
            0
        );
        $map['is_money'] = array(
            'eq',
            1
        );
        import("@.ORG.Page"); // 导入分页类
        $count = M('te_trade_orders')->where($map)->count(); // 总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $content = M('te_trade_orders')->where($map)
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->order(' money_time desc ')
            ->select();
        foreach ($content as $key => $rs) {
            $array = array();
            $array['seller_no'] = trim($rs['shop_id']);
            $shop = M('seller')->field('title,user_id')
                ->where($array)
                ->find();
            $user_name = '无';
            $real_user_name = '无';
            $money = '0';
            $fck = M('fck')->field('id, user_id,u_level,re_id')
                ->where('id=' . $rs['money_user_id'])
                ->find();
            IF ($fck != NULL) {
                $user_name = $fck['user_id'];
            }
            $content[$key]['user_name'] = $user_name;
            $fck = M('fck')->field('id, user_id,u_level,re_id')
                ->where('id=' . $rs['user_id'])
                ->find();
            IF ($fck != NULL) {
                $real_user_name = $fck['user_id'];
            }
            $content[$key]['real_user_name'] = $real_user_name;
            $content[$key]['money_time_str'] = date("Y-m-d H:i:s", $rs['money_time']);
        }
        $this->assign('list', $content);
        
        $this->display();
    }

    public function test_excel()
    {
        $type = (int) $_GET['type'];
        $order_nos = '';
        IF ($type != 1) {
            $file = $_FILES[filexls][tmp_name];
            if ($file != NULL) {
                set_time_limit(3600);
                $fileMessage = explode('.', $_FILES[excel][name]);
                
                // $file ='./321.csv';
                $type = pathinfo($file);
                $type = strtolower($type["extension"]);
                $type = $type === 'csv' ? $type : 'Excel5';
                ini_set('max_execution_time', '0');
                // $filename = $fileMessage[0];
                // 获得文件扩展名
                $filetype = $fileMessage[1];
                // 使用函数，获得excel数据
                $re = readFromExcel($file);
                $content = $re;
                $fee = M('fee');
                $fee_rs = $fee->find(1);
                $trade_money_fee = explode('|', $fee_rs['s9']);
                $user_num_fee = explode('|', $fee_rs['user_num']);
                $user_money = explode('|', $fee_rs['user_money']);
                
                foreach ($content as $key => $rs) {
                    if ($key > 1) {
                        $array = array();
                        $array['seller_no'] = trim($rs[2]);
                        $trade_money = trim($rs[4]);
                        $shop = M('seller')->field('title,user_id')
                            ->where($array)
                            ->find();
                        $user_id = 0;
                        $re_id = 0;
                        $user_name = '无';
                        $money = 0;
                        $gap_profit = 0;
                        $recommend_fen_money = 0;
                        $level = 0;
                        IF ($shop != NULL) {
                            
                            $fck = M('fck')->field('id, user_id,u_level,re_id,re_level,re_path')
                                ->where('id=' . $shop['user_id'])
                                ->find();
                            if ($fck != null) {
                                $user_name = $fck['user_id'];
                                $u_level = $fck['u_level'];
                                $money = get_lirun($fck, $trade_money);
                                $user_id = $fck['id'];
                                $re_id = $fck['re_id'];
                                
                                $profit = $trade_money * $user_money[$fck['u_level'] - 1] * 0.0001;
                                
                                $money = $profit;
                                $userlist = recommend_tree($fck['id']);
                                foreach ($userlist as $k => $r) {
                                    
                                    $r = get_user_info($r, $r['id']);
                                    // 获取总的下线交易额
                                    
                                    if ($r['u_level'] > $fck['u_level']) {
                                        
                                        $recommend_fen_money = $recommend_fen_money + $trade_money * ($user_money[$r['u_level'] - 1] - $user_money[$fck['u_level'] - 1]) * 0.0001;
                                    }
                                }
                            }
                            // $recommend_fen_money = $rs[17] * $gap_profit * 0.0001;
                        }
                        
                        $content[$key]['user_name'] = $user_name;
                        $content[$key]['money'] = $money;
                        
                        $item = array();
                        $item['order_no'] = trim($rs[0]);
                        $count = M('trade_orders')->where($item)->count();
                        $item['shop_id'] = $array['seller_no'];
                        $item['shop_name'] = $rs[3];
                        $item['shop_title'] = $shop['title'];
                        // $trade_money = $rs[17];
                        $item['trade_money'] = $trade_money;
                        $item['trade_time'] = strtotime($rs[5]);
                        $item['trade_time_str'] = $rs[5];
                        $item['real_fen_money'] = $money;
                        
                        $item['real_fen_money2'] = 0;
                        
                        // $item['fen_money'] = $rs[22];
                        $item['card_type'] = $rs[6];
                        $item['user_id'] = $user_id;
                        $item['money_user_id'] = $user_id;
                        $item['recommend_fen_money'] = $recommend_fen_money;
                        $fck = M('fck')->field('id, user_id,u_level,re_id,re_level,re_path')
                            ->where('id=' . $user_id)
                            ->find();
                        if ($fck != null) {
                            $item['re_path'] = $fck['re_path'] . $user_id . ',';
                        }
                        
                        $recommend_fen_money = 0;
                        $item['gap_profit'] = $gap_profit;
                        
                        if ($item['order_no'] > 0) {
                            
                            $item1 = array();
                            $item1['order_no'] = $item['order_no'];
                            $count = M('trade_orders')->where($item1)->count();
                            IF ($count == 0) {
                                // 更新交易金额
                                
                                $item['add_time'] = time();
                                $item['is_upload'] = 1;
                                
                                $id = M('trade_orders')->add($item);
                                set_shop_trade_status($trade_money, $array['seller_no']);
                                
                                $item1 = array();
                                $item1['id'] = $id;
                            } else {
                                
                                $item1 = array();
                                $item1['order_no'] = $item['order_no'];
                                $item1['is_upload'] = 1;
                                M('trade_orders')->where($item1)->save($item);
                            }
                        }
                        // 获取总交易量
                        $all_trade_money = get_all_trade_money($user_id);
                        $fck = M('fck')->field('id, user_id,u_level,re_id,re_level,re_path')
                            ->where('id=' . $user_id)
                            ->find();
                        
                        $level_count = M('fck')->where('re_id=' . $user_id . ' and u_level=5 ')->count();
                        
                        $trade_money_par = $trade_money_fee[$fck['u_level']] * 10000;
                        $item['trade_money2'] = 0;
                        
                        if (($all_trade_money) > $trade_money_par && $level_count >= $user_num_fee[$fck['u_level']]) {
                            $parent_trade_money = $trade_money_fee[$fck['u_level']] * 10000;
                            
                            // $item['trade_money'] = $parent_trade_money - $all_trade_money;
                            // $trade_money_par = $trade_money_fee[$fck['u_level']];
                            $item['trade_money2'] = $all_trade_money - $parent_trade_money;
                        }
                        
                        M('trade_orders')->where($item1)->setField('trade_money2', $item['trade_money2']);
                        
                        // user_level_check($user_id);
                        $fck = M('fck')->field('id, user_id,u_level,re_id,re_level,re_path')
                            ->where('id=' . $user_id)
                            ->find();
                        $item['u_level'] = $fck['u_level'];
                        M('trade_orders')->where($item1)->setField('u_level', $item['u_level']);
                        
                        $order_nos = $order_nos . '' . $item['order_no'] . ',';
                    }
                }
                // $content=M('trade_orders')->select();
                
                // $this->assign('list', $content);
                
                $order_nos = $order_nos . '0';
            }
        }
        $map['is_upload'] = array(
            'eq',
            1
        );
        // $map['money_user_id'] = array(
        // 'gt',
        // 0
        // );
        // $map['order_no'] = array(
        // 'in',
        // $order_nos
        // );
        
        import("@.ORG.Page"); // 导入分页类
        $count = M('trade_orders')->where($map)->count(); // 总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $content = M('trade_orders')->where($map)
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->order(' trade_time asc ')
            ->select();
        
        $userids = '';
        
        foreach ($content as $key => $rs) {
            $array = array();
            $array['seller_no'] = trim($rs['shop_id']);
            $shop = M('seller')->field('title,user_id')
                ->where($array)
                ->find();
            $user_name = '无';
            $real_user_name = '无';
            $money = '0';
            $fck = M('fck')->field('id, user_id,u_level,re_id')
                ->where('id=' . $rs['money_user_id'])
                ->find();
            IF ($fck != NULL) {
                $user_name = $fck['user_id'];
            }
            $content[$key]['user_name'] = $user_name;
            $fck = M('fck')->field('id, user_id,u_level,re_id')
                ->where('id=' . $rs['user_id'])
                ->find();
            IF ($fck != NULL) {
                $real_user_name = $fck['user_id'];
            }
            $content[$key]['real_user_name'] = $real_user_name;
            $content[$key]['level_str'] = getUserLevel($fck['u_level']);
            
            $userids = $userids . $rs['user_id'] . ',';
            $content[$key]['color'] = 'color:black';
            if ($rs['is_money'] == 1) {
                
                $content[$key]['color'] = 'color:red';
            }
            $content[$key]['url'] = urlencode(U('User/test_excel', array(
                'type' => 1
            )));
        }
        $userids = $userids . '0';
        
        $user = M('fck')->where('id  in (' . $userids . ')')->select();
        foreach ($user as $key1 => $rs1) {}
        
        $this->assign('list', $content);
        $_FILES = null;
        $this->display();
    }

    public function user_profit()
    {
        $upload_type = $_GET['upload_type'];
        $this->assign('upload_type', $upload_type);
        $type = I('get.type', 1);
        $order_nos = '';
        IF ($type != 1) {
            $file = $_FILES[filexls][tmp_name];
            if ($file != NULL) {
                $fileMessage = explode('.', $_FILES[excel][name]);
                
                // $file ='./321.csv';
                $type = pathinfo($file);
                $type = strtolower($type["extension"]);
                $type = $type === 'csv' ? $type : 'Excel5';
                ini_set('max_execution_time', '0');
                // $filename = $fileMessage[0];
                // 获得文件扩展名
                $filetype = $fileMessage[1];
                // 使用函数，获得excel数据
                $handle = fopen($file, 'r');
                $re = input_csv($handle);
                $content = $re;
                $fee = M('fee');
                $fee_rs = $fee->find(1);
                
                $fee->where('id=1')->setField('i7', count($content));
                
                $trade_money_fee = explode('|', $fee_rs['s9']);
                $user_num_fee = explode('|', $fee_rs['user_num']);
                $user_money = explode('|', $fee_rs['user_money']);
                $all_trade_money = 0;
                $order_nos = '';
                foreach ($content as $key => $rs) {
                    if ($key > 0) {
                        
                        $seller_no = trim($rs[2]);
                        $trade_money = trim($rs[4]);
                        $all_trade_money = $all_trade_money + $trade_money;
                        $order_no = trim($rs[0]);
                        if ($order_no == '1902152147353977') {
                            $wi = 0;
                        }
                        $trade_time = trim($rs[5]);
                        $card_type = trim($rs[6]);
                        $card_type = trim($rs[6]);
                        $shop_name = trim($rs[3]);
                        $upload_type = 'excel';
                        
                        create_trade_order($seller_no, $trade_money, $order_no, $trade_time, $card_type, $user_money, $shop_name, $upload_type);
                        // // 获取总交易量
                        // $all_trade_money = get_all_trade_money($user_id);
                        // $fck = M('fck')->field('id, user_id,u_level,re_id,re_level,re_path')
                        // ->where('id=' . $user_id)
                        // ->find();
                        
                        // $level_count = M('fck')->where('re_id=' . $user_id . ' and u_level=5 ')->count();
                        
                        // $trade_money_par = $trade_money_fee[$fck['u_level']] * 10000;
                        // $item['trade_money2'] = 0;
                        
                        // if (($all_trade_money) > $trade_money_par && $level_count >= $user_num_fee[$fck['u_level']]) {
                        // $parent_trade_money = $trade_money_fee[$fck['u_level']] * 10000;
                        
                        // // $item['trade_money'] = $parent_trade_money - $all_trade_money;
                        // // $trade_money_par = $trade_money_fee[$fck['u_level']];
                        // $item['trade_money2'] = $all_trade_money - $parent_trade_money;
                        // }
                        
                        // M('trade_orders')->where($item1)->setField('trade_money2', $item['trade_money2']);
                        
                        // // user_level_check($user_id);
                        // $fck = M('fck')->field('id, user_id,u_level,re_id,re_level,re_path')
                        // ->where('id=' . $user_id)
                        // ->find();
                        // $item['u_level'] = $fck['u_level'];
                        // M('trade_orders')->where($item1)->setField('u_level', $item['u_level']);
                        
                        $order_nos = $order_nos . '' . $order_no . ',';
                    }
                }
                $fee->where('id=1')->setField('i8', $all_trade_money);
                // $content=M('trade_orders')->select();
                
                // $this->assign('list', $content);
                
                $order_nos = $order_nos . '0';
                M('fee')->where('id=1')->setField('i9', $order_nos);
            }
        }
        $map['is_upload'] = array(
            'eq',
            1
        );
        
        $map['upload_type'] = array(
            'eq',
            $upload_type
        );
        // $map['real_fen_money'] = array(
        // 'gt',
        // 0
        // );
        $trade_orders = M('trade_orders');
        if ($upload_type == 'excel') {
            $trade_orders = M('trade_orders_record');
        }
        
        import("@.ORG.Page"); // 导入分页类
        $count = $trade_orders->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id", 'LEFT')
            ->join("xt_seller AS h ON   t.shop_id = h.seller_no", 'LEFT')
            ->where($map)
            ->count(); // 总页数
        $Page = new Page($count, 2000000);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $content = $trade_orders->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id", 'LEFT')
            ->join("xt_seller AS h ON   t.shop_id = h.seller_no", 'LEFT')
            ->where($map)
            ->page($p . ',' . 2000000)
            ->field(' t.*,G.user_id,G.user_name,h.title as shop_title')
            ->order(' T.trade_time asc ')
            ->select();
        
        $userids = '';
        $all_trade_money = $trade_orders->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id", 'LEFT')
            ->join("xt_seller AS h ON   t.shop_id = h.seller_no", 'LEFT')
            ->where($map)
            ->sum('t.trade_money');
        $order_nos = '';
        foreach ($content as $key => $rs) {
            
            $order_nos = $order_nos . '' . $rs['order_no'] . ',';
            // $userids = $userids . $rs['user_id'] . ',';
            $content[$key]['color'] = 'color:black';
            if ($rs['is_money'] == 1) {
                
                $content[$key]['color'] = 'color:red';
            }
            $content[$key]['url'] = urlencode(U('User/user_profit', array(
                'type' => 1,
                'upload_type' => $upload_type
            )));
            $content[$key]['order_no_color'] = 'color:black';
            $content[$key]['trade_money_color'] = 'color:black';
            if ($rs['upload_type'] == 'excel') {
                $trade_orders = M('trade_orders')->field('order_no,trade_money')
                    ->where(array(
                    'order_no' => $rs['order_no']
                ))
                    ->find();
                IF ($trade_orders != NULL) {
                    $content[$key]['order_no_color'] = 'color:red';
                    
                    IF ($rs['trade_money'] != $trade_orders['trade_money']) {
                        
                        $content[$key]['trade_money_color'] = 'color:red';
                    }
                }
            }
            // $all_trade_money=$all_trade_money+$rs['trade_money'];
        }
        $order_nos = $order_nos . '0';
        
        $no_order_nos = '';
        $csv_order_nos = M('fee')->where('id=1')->getField('i9');
        
        $csv_order_nos = explode(',', $csv_order_nos);
        
        foreach ($csv_order_nos as $key => $rs) {
            if (strpos($order_nos, $rs) !== false) {} else {
                $no_order_nos = $no_order_nos . '' . $rs . ',';
            }
        }
        
        $this->assign('no_order_nos', $no_order_nos);
        
        $no_order_nos_list = explode(',', $no_order_nos);
        
        $userids = $userids . '0';
        
        $user = M('fck')->where('id  in (' . $userids . ')')->select();
        foreach ($user as $key1 => $rs1) {}
        
        $fee = M('fee');
        $fee_rs = $fee->field('i7,i8')->find(1);
        $excel_count = $fee_rs['i7'];
        $excel_money = $fee_rs['i8'];
        $this->assign('no_excel_count', (count($no_order_nos_list) - 1));
        $this->assign('all_trade_money', round($all_trade_money, 2));
        
        $no_trade_money = ($excel_money - $all_trade_money);
        IF ($no_trade_money < 0) {
            $no_trade_money = 0;
        }
        $this->assign('no_trade_money', $no_trade_money);
        $this->assign('excel_count', $excel_count);
        $this->assign('excel_money', $excel_money);
        $this->assign('list', $content);
        $_FILES = null;
        $this->display();
    }

    public function user_te_profit()
    {
        $type = (int) $_GET['type'];
        $order_nos = '';
        IF ($type != 1) {
            $file = $_FILES[filexls][tmp_name];
            if ($file != NULL) {
                $fileMessage = explode('.', $_FILES[excel][name]);
                
                // $file ='./321.csv';
                $type = pathinfo($file);
                $type = strtolower($type["extension"]);
                $type = $type === 'csv' ? $type : 'Excel5';
                ini_set('max_execution_time', '0');
                // $filename = $fileMessage[0];
                // 获得文件扩展名
                $filetype = $fileMessage[1];
                // 使用函数，获得excel数据
                $re = readFromExcel($file);
                $content = $re;
                $fee = M('fee');
                $fee_rs = $fee->find(1);
                $trade_money_fee = explode('|', $fee_rs['s9']);
                $user_num_fee = explode('|', $fee_rs['user_num']);
                $user_money = explode('|', $fee_rs['user_money']);
                
                foreach ($content as $key => $rs) {
                    if ($key > 0) {
                        $array = array();
                        $array['seller_no'] = trim($rs[2]);
                        $array['shop_type'] = 1;
                        $trade_money = trim($rs[4]);
                        $shop = M('seller')->field('title,user_id,shop_profit')
                            ->where($array)
                            ->find();
                        $user_id = 0;
                        $re_id = 0;
                        $user_name = '无';
                        $money = 0;
                        $gap_profit = 0;
                        $recommend_fen_money = 0;
                        $level = 0;
                        IF ($shop != NULL) {
                            
                            $fck = M('fck')->field('id, user_id,u_level,re_id,re_level,re_path')
                                ->where('id=' . $shop['user_id'])
                                ->find();
                            if ($fck != null) {
                                $user_name = $fck['user_id'];
                                $u_level = $fck['u_level'];
                                $money = get_lirun($fck, $trade_money);
                                $user_id = $fck['id'];
                                $re_id = $fck['re_id'];
                                
                                $profit = $trade_money * $shop['shop_profit'] * 0.0001;
                                
                                $money = $profit;
                                $userlist = recommend_tree($fck['id']);
                                foreach ($userlist as $k => $r) {
                                    
                                    $r = get_user_info($r, $r['id']);
                                    // 获取总的下线交易额
                                    
                                    if ($r['u_level'] > $fck['u_level']) {
                                        
                                        $recommend_fen_money = $recommend_fen_money + $trade_money * ($user_money[$r['u_level'] - 1] - $user_money[$fck['u_level'] - 1]) * 0.0001;
                                    }
                                }
                            }
                            // $recommend_fen_money = $rs[17] * $gap_profit * 0.0001;
                        }
                        
                        $content[$key]['user_name'] = $user_name;
                        $content[$key]['money'] = $money;
                        
                        $item = array();
                        $item['order_no'] = trim($rs[0]);
                        $count = M('te_trade_orders')->where($item)->count();
                        $item['shop_id'] = $array['seller_no'];
                        $item['shop_name'] = $rs[3];
                        $item['shop_title'] = $shop['title'];
                        // $trade_money = $rs[17];
                        $item['trade_money'] = $trade_money;
                        $item['trade_time'] = strtotime($rs[5]);
                        $item['trade_time_str'] = $rs[5];
                        $item['real_fen_money'] = $money;
                        
                        $item['real_fen_money2'] = 0;
                        
                        // $item['fen_money'] = $rs[22];
                        $item['card_type'] = $rs[6];
                        $item['user_id'] = $user_id;
                        $item['money_user_id'] = $user_id;
                        $item['recommend_fen_money'] = $recommend_fen_money;
                        $fck = M('fck')->field('id, user_id,u_level,re_id,re_level,re_path')
                            ->where('id=' . $user_id)
                            ->find();
                        if ($fck != null) {
                            $item['re_path'] = $fck['re_path'] . $user_id . ',';
                        }
                        
                        $recommend_fen_money = 0;
                        $item['gap_profit'] = $gap_profit;
                        
                        if ($item['order_no'] > 0) {
                            
                            $item1 = array();
                            $item1['order_no'] = $item['order_no'];
                            $count = M('te_trade_orders')->where($item1)->count();
                            IF ($count == 0) {
                                
                                $item['add_time'] = time();
                                $item['is_upload'] = 1;
                                
                                $id = M('te_trade_orders')->add($item);
                                
                                $item1 = array();
                                $item1['id'] = $id;
                                
                                // 更新交易金额
                                set_shop_trade_status($trade_money, $array['seller_no']);
                            } else {
                                
                                $item1 = array();
                                $item1['order_no'] = $item['order_no'];
                                $item1['is_upload'] = 1;
                                M('te_trade_orders')->where($item1)->save($item);
                            }
                        }
                        // 获取总交易量
                        $all_trade_money = get_all_trade_money($user_id);
                        $fck = M('fck')->field('id, user_id,u_level,re_id,re_level,re_path')
                            ->where('id=' . $user_id)
                            ->find();
                        
                        $level_count = M('fck')->where('re_id=' . $user_id . ' and u_level=5 ')->count();
                        
                        $trade_money_par = $trade_money_fee[$fck['u_level']] * 10000;
                        $item['trade_money2'] = 0;
                        
                        if (($all_trade_money) > $trade_money_par && $level_count >= $user_num_fee[$fck['u_level']]) {
                            $parent_trade_money = $trade_money_fee[$fck['u_level']] * 10000;
                            
                            // $item['trade_money'] = $parent_trade_money - $all_trade_money;
                            // $trade_money_par = $trade_money_fee[$fck['u_level']];
                            $item['trade_money2'] = $all_trade_money - $parent_trade_money;
                        }
                        
                        M('te_trade_orders')->where($item1)->setField('trade_money2', $item['trade_money2']);
                        
                        // user_level_check($user_id);
                        $fck = M('fck')->field('id, user_id,u_level,re_id,re_level,re_path')
                            ->where('id=' . $user_id)
                            ->find();
                        $item['u_level'] = $fck['u_level'];
                        M('te_trade_orders')->where($item1)->setField('u_level', $item['u_level']);
                        
                        $order_nos = $order_nos . '' . $item['order_no'] . ',';
                    }
                }
                // $content=M('trade_orders')->select();
                
                // $this->assign('list', $content);
                
                $order_nos = $order_nos . '0';
            }
        }
        $map['is_upload'] = array(
            'eq',
            1
        );
        // $map['money_user_id'] = array(
        // 'gt',
        // 0
        // );
        // $map['order_no'] = array(
        // 'in',
        // $order_nos
        // );
        
        import("@.ORG.Page"); // 导入分页类
        $count = M('te_trade_orders')->where($map)->count(); // 总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $content = M('te_trade_orders')->where($map)
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->order(' trade_time asc ')
            ->select();
        
        $userids = '';
        
        foreach ($content as $key => $rs) {
            $array = array();
            $array['seller_no'] = trim($rs['shop_id']);
            $shop = M('seller')->field('title,user_id')
                ->where($array)
                ->find();
            $user_name = '无';
            $real_user_name = '无';
            $money = '0';
            $fck = M('fck')->field('id, user_id,u_level,re_id')
                ->where('id=' . $rs['money_user_id'])
                ->find();
            IF ($fck != NULL) {
                $user_name = $fck['user_id'];
            }
            $content[$key]['user_name'] = $user_name;
            $fck = M('fck')->field('id, user_id,u_level,re_id')
                ->where('id=' . $rs['user_id'])
                ->find();
            IF ($fck != NULL) {
                $real_user_name = $fck['user_id'];
            }
            $content[$key]['real_user_name'] = $real_user_name;
            $content[$key]['level_str'] = getUserLevel($fck['u_level']);
            
            $userids = $userids . $rs['user_id'] . ',';
            $content[$key]['color'] = 'color:black';
            if ($rs['is_money'] == 1) {
                
                $content[$key]['color'] = 'color:red';
            }
            $content[$key]['url'] = urlencode(U('User/user_te_profit', array(
                'type' => 1
            )));
        }
        $userids = $userids . '0';
        
        $user = M('fck')->where('id  in (' . $userids . ')')->select();
        foreach ($user as $key1 => $rs1) {}
        
        $this->assign('list', $content);
        $_FILES = null;
        $this->display();
    }

    public function all_te_profit()
    {
        $bei = 10000;
        $map['real_fen_money'] = array(
            'gt',
            0
        );
        $map['is_money'] = array(
            'eq',
            0
        );
        $fck = D('Fck');
        $fee = M('fee');
        $fee_rs = $fee->field('user_money,user_shui')->find(1);
        $user_shui = explode('|', $fee_rs['user_shui']);
        $content = M('te_trade_orders')->where($map)
            ->order(' trade_time asc ')
            ->select();
        
        // $fck = D('Fck');
        // $fee = M('fee')->field('s9,user_money')->find();
        
        $fee_user_money = explode('|', $fee_rs['user_money']);
        foreach ($content as $key => $rs) {
            $project_id = $rs['id'];
            $array = array();
            $array['seller_no'] = trim($rs['shop_id']);
            $seller = M('seller')->field('id, shop_profit')
                ->where($array)
                ->find();
            
            M('te_trade_orders')->where('id=' . $rs['id'])->setField('is_upload', 0);
            
            $user = M('fck')->field('id, user_id,u_level,re_id,u_level,re_path')
                ->where('id  =' . $rs['user_id'])
                ->find();
            $u_level = $user['u_level'];
            $user_money = $seller['shop_profit'];
            
            // $user = M('fck')->field('id, user_id,u_level,re_id,u_level,re_path')
            // ->where('id =' . $rs['user_id'])
            // ->find();// 分润值
            $all_trade_money = $rs['trade_money'];
            $fen_money = $user_money * $rs['trade_money'] / $bei;
            IF ($fen_money > 0) {
                $money = $fen_money;
                $result = $fck->where('id=' . $user['id'])->setInc('agent_use', $money);
                $kt_cont = "" . C('pos_txt') . "";
                $fck->addencProfitAdd($user['id'], $kt_cont . '-' . C('agent_use'), $money, $rs['order_no'], $rs['trade_time'], $rs['shop_title'], $fen_money, $all_trade_money, $user['u_level'], 0, $project_id); // 激活积分扣除历史记录
                                                                                                                                                                                                                    
                // 计算个人所得税
                $tax = $money * ($user_shui[$u_level - 1] / 100);
                
                $result = $fck->where('id=' . $user['id'])->setDec('agent_use', $tax);
                $kt_cont = "" . C('pos_txt') . "扣" . C('tax_txt');
                $fck->addencProfitAdd($user['id'], $kt_cont . '-' . C('agent_use'), - $tax, $rs['order_no'], $rs['trade_time'], $rs['shop_title'], 0); // 激活积分扣除历史记录
            }
            
            // 进行升级检测
            $this_month = strtotime(date('Y-m-01', $rs['trade_time']));
            
            user_level_check($user['id'], ' and trade_time>=' . $this_month . ' AND trade_time<=' . $rs['trade_time'], $this_month);
            M('te_trade_orders')->where('id=' . $rs['id'])->setField('tax', $tax);
            M('te_trade_orders')->where('id=' . $rs['id'])->setField('is_money', 1);
            M('te_trade_orders')->where('id=' . $rs['id'])->setField('money_time', time());
        }
        
        if (count($content) == 0) {
            
            $this->success('没有需要分润的数据！');
        } else {
            
            $this->success('分润成功！');
        }
    }

    public function all_profit()
    {
        set_time_limit(3600);
        $upload_type = $_GET['upload_type'];
        
        $trade_orders = M('trade_orders');
        if ($upload_type == 'excel') {
            $trade_orders = M('trade_orders_record');
        }
        
        $bei = 10000;
        $map['real_fen_money'] = array(
            'gt',
            0
        );
        $map['is_money'] = array(
            'eq',
            0
        );
        $fck = D('Fck');
        $fee = M('fee');
        $fee_rs = $fee->field('user_money,user_shui')->find(1);
        $user_shui = explode('|', $fee_rs['user_shui']);
        $content = $trade_orders->where($map)
            ->order(' trade_time asc ')
            ->select();
        
        $fee_user_money = explode('|', $fee_rs['user_money']);
        foreach ($content as $key => $rs) {
            
            trade_profit($rs, $user_shui, $fee_user_money);
        }
        foreach ($content as $key => $rs1) {
            M('trade_orders_record')->where(array(
                'order_no' => $rs1['order_no']
            ))->delete();
        }
        if (count($content) == 0) {
            
            $this->success('没有需要分润的数据！');
        } else {
            $url = (U('User/user_profit', array(
                'type' => 1,
                'upload_type' => $upload_type
            )));
            $fee->where('id=1')->setField('i7', 0);
            $fee->where('id=1')->setField('i8', 0);
            $fee->where('id=1')->setField('i9', '');
            $this->success('分润成功！', $url);
        }
    }

    public function all_profit_test()
    {
        // $map['real_fen_money'] = array(
        // 'gt',
        // 0
        // );
        $map['is_upload'] = array(
            'eq',
            1
        );
        $fee = M('fee');
        $fee_rs = $fee->find(1);
        $user_shui = explode('|', $fee_rs['user_shui']);
        $content = M('trade_orders')->where($map)
            ->order(' trade_time asc ')
            ->select();
        
        $fck = D('Fck');
        $fee = M('fee')->field('s9,user_money')->find();
        
        foreach ($content as $key => $rs) {
            M('trade_orders')->where('id=' . $rs['id'])->setField('is_upload', 0);
            if ($rs['is_money'] == 0) {
                if ($rs['trade_money'] == 900000) {
                    $o = 0;
                }
                if ($rs['order_no'] == '18084') {
                    $o = 0;
                }
                $user = M('fck')->field('id, user_id,u_level,re_id,u_level,re_path')
                    ->where('id  =' . $rs['user_id'])
                    ->find();
                $parent = M('fck')->field('id, user_id,u_level,re_id,u_level')
                    ->where('id in (0' . $user['re_path'] . '0) and re_level=0')
                    ->find();
                
                $user_list = M('fck')->field('id, user_id,u_level,re_id')
                    ->where('id  in (0' . $user['re_path'] . '' . $user['id'] . ') ')
                    ->order(' re_level desc ')
                    ->select();
                
                $fee_user_money = explode('|', $fee['user_money']);
                $fee_trade_money = explode('|', $fee['s9']);
                $bei = 10000;
                $all_fen_money = ($fee_user_money[$parent['u_level'] - 1]) * 0.01 * $rs['trade_money'];
                $u_level = 0;
                $user_money = $fee_user_money[$rs['u_level']];
                $parent_fee_user_money = 0;
                // 拆分前的交易量
                $trade_money = $rs['trade_money'];
                $parent_u_level1 = 0;
                $parent_u_level2 = 0;
                $chai_trade_money1 = 0;
                $chai_trade_money2 = 0;
                $chai_trade_money3 = 0;
                $trade_money1 = 0;
                $trade_money2 = 0;
                $parent_user_id = 0;
                $trade_money1 = $rs['trade_money'];
                $trade_money2 = 0;
                $project_id = $rs['id'];
                foreach ($user_list as $key1 => $rs1) {
                    $chai_trade_money12 = 0;
                    $user = M('fck')->field('id, user_id,u_level,re_id,u_level')
                        ->where('id  =' . $rs1['id'])
                        ->find();
                    IF ($rs['order_no'] == '180826241' && $user['user_id'] == '002') {
                        $TT = 0;
                    }
                    
                    // if ($user['u_level'] == $parent_u_level) {
                    // $all_trade_money = get_all_trade_money($user['id'], ' AND trade_time<' . $rs['trade_time']);
                    // $trade_money = $rs['trade_money'];
                    // if (($trade_money + $all_trade_money) >= $fee_trade_money[$user['u_level']] * $bei) {
                    
                    // $trade_money =$trade_money-($fee_trade_money[$user['u_level'] ]* $bei -$all_trade_money );
                    // // 分润值
                    // $fen_money2 = $user_money * $trade_money / $bei;
                    
                    // }
                    // }
                    $all_trade_money = get_all_trade_money($user['id'], ' AND trade_time<' . $rs['trade_time']);
                    $chai_trade_money = $fee_trade_money[$user['u_level']] * $bei - $all_trade_money;
                    
                    if ($user['u_level'] > $parent_u_level1 || ($trade_money + $all_trade_money) > $fee_trade_money[$user['u_level']] * $bei) {
                        $trade_money = $rs['trade_money'];
                        
                        if ($chai_trade_money < $trade_money) {
                            // 如果需要升级
                            if ($trade_money1 > $chai_trade_money) {
                                $chai_trade_money1 = $chai_trade_money;
                                // $parent_u_level1=$user['u_level'];
                                $user_money = $fee_user_money[$user['u_level'] - 1] - $fee_user_money[$parent_u_level1 - 1];
                                // 分润值
                                $fen_money = $user_money * $chai_trade_money1 / $bei;
                                
                                $chai_trade_money2 = $trade_money1 - $chai_trade_money;
                                
                                $user_money = $fee_user_money[$user['u_level']] - $fee_user_money[$parent_u_level2 - 1];
                                $parent_u_level2 = $user['u_level'] + 1;
                                $fen_money2 = $user_money * $chai_trade_money2 / $bei;
                                $chai_trade_money3 = 0;
                                $parent_u_level1 = $user['u_level'];
                                
                                $trade_money1 = $chai_trade_money1;
                                $trade_money2 = $chai_trade_money2 + $chai_trade_money3;
                            } else {
                                
                                // $chai_trade_money1 = $chai_trade_money;
                                // $parent_u_level1=$user['u_level'];
                                
                                $user_money = $fee_user_money[$user['u_level'] - 1];
                                if ($parent_u_level1 > 0) {
                                    $user_money = $fee_user_money[$user['u_level'] - 1] - $fee_user_money[$parent_u_level1 - 1];
                                }
                                
                                // 分润值
                                $fen_money = $user_money * $chai_trade_money1 / $bei;
                                
                                $chai_trade_money3 = $chai_trade_money2;
                                
                                if ($parent_u_level1 > 0) {
                                    $user_money = $fee_user_money[$user['u_level']] - $fee_user_money[$parent_u_level2 - 1];
                                }
                                $fen_money3 = $user_money * $chai_trade_money3 / $bei;
                                
                                $chai_trade_money2 = $chai_trade_money - $chai_trade_money1;
                                $user_money = $fee_user_money[$user['u_level'] - 1] - $fee_user_money[$parent_u_level1 - 1];
                                // 分润值
                                $fen_money2 = $user_money * $chai_trade_money2 / $bei;
                                
                                if ($parent_u_level2 > $parent_u_level1) {
                                    $chai_trade_money2 = $chai_trade_money - $chai_trade_money1;
                                    $user_money = $fee_user_money[$user['u_level']] - $fee_user_money[$parent_u_level1 + 1];
                                    // 分润值
                                    $fen_money2 = $user_money * $chai_trade_money2 / $bei;
                                    
                                    $chai_trade_money3 = $trade_money2 - $chai_trade_money2;
                                    
                                    if ($parent_u_level1 > 0) {
                                        $user_money = $fee_user_money[$user['u_level'] + 1] - $fee_user_money[$parent_u_level2];
                                    }
                                    $fen_money3 = $user_money * $chai_trade_money3 / $bei;
                                }
                                
                                $chai_trade_money2 = $chai_trade_money2 + $chai_trade_money3;
                                $parent_u_level2 = $user['u_level'] + 1;
                                
                                $parent_u_level1 = $user['u_level'];
                            }
                        } else {
                            if ($chai_trade_money2 == 0) {
                                $chai_trade_money1 = $trade_money;
                            }
                            // $parent_u_level1=$user['u_level'];
                            $user_money = $fee_user_money[$user['u_level'] - 1];
                            if ($parent_u_level1 > 0) {
                                $user_money = $fee_user_money[$user['u_level'] - 1] - $fee_user_money[$parent_u_level1 - 1];
                            }
                            // 分润值
                            $fen_money = $user_money * $chai_trade_money1 / $bei;
                            $user_money = $fee_user_money[$user['u_level'] - 1];
                            if ($parent_u_level2 > 0) {
                                $user_money = $fee_user_money[$user['u_level'] - 1] - $fee_user_money[$parent_u_level2 - 1];
                            }
                            // 分润值
                            $fen_money2 = $user_money * $chai_trade_money2 / $bei;
                            $parent_u_level1 = $user['u_level'];
                            $parent_u_level2 = $user['u_level'];
                            $chai_trade_money1 = $chai_trade_money1 + $chai_trade_money2 + $chai_trade_money3;
                            $chai_trade_money2 = 0;
                            $chai_trade_money3 = 0;
                        }
                        
                        IF ($fen_money > 0) {
                            $money = $fen_money;
                            $result = $fck->where('id=' . $rs1['id'])->setInc('agent_use', $money);
                            $kt_cont = "" . C('pos_txt') . "";
                            $fck->addencProfitAdd($user['id'], $kt_cont . '-' . C('agent_use'), $money, $rs['order_no'], $rs['trade_time'], $rs['shop_title'], $all_fen_money, $all_trade_money, $user['u_level'], $trade_money1, $project_id, $trade_money1, $trade_money2, $chai_trade_money1, $chai_trade_money2, $parent_u_level1, $parent_u_level2, $fen_money, $fen_money2 + $fen_money3); // 激活积分扣除历史记录
                                                                                                                                                                                                                                                                                                                                                                                                 
                            // 计算个人所得税
                            $tax = $money * ($user_shui[$u_level] / 100);
                            
                            $result = $fck->where('id=' . $user['id'])->setDec('agent_use', $tax);
                            $kt_cont = "" . C('pos_txt') . "扣" . C('tax_txt');
                            $fck->addencProfitAdd($user['id'], $kt_cont . '-' . C('agent_use'), - $tax, $rs['order_no'], $rs['trade_time'], $rs['shop_title'], 0); // 激活积分扣除历史记录
                        }
                        
                        IF ($fen_money2 > 0) {
                            $money = $fen_money2;
                            $result = $fck->where('id=' . $rs1['id'])->setInc('agent_use', $money);
                            $kt_cont = "" . C('pos_txt') . "";
                            $fck->addencProfitAdd($user['id'], $kt_cont . '-' . C('agent_use'), $money, $rs['order_no'], $rs['trade_time'], $rs['shop_title'], $all_fen_money, $all_trade_money, $user['u_level'], $trade_money2, $project_id, $trade_money1, $trade_money2, $chai_trade_money1, $chai_trade_money2, $parent_u_level1, $parent_u_level2, $fen_money, $fen_money2 + $fen_money3); // 激活积分扣除历史记录
                                                                                                                                                                                                                                                                                                                                                                                                 
                            // 计算个人所得税
                            $tax = $money * ($user_shui[$u_level] / 100);
                            
                            $result = $fck->where('id=' . $user['id'])->setDec('agent_use', $tax);
                            $kt_cont = "" . C('pos_txt') . "扣" . C('tax_txt');
                            $fck->addencProfitAdd($user['id'], $kt_cont . '-' . C('agent_use'), - $tax, $rs['order_no'], $rs['trade_time'], $rs['shop_title'], 0); // 激活积分扣除历史记录
                        }
                        
                        IF ($fen_money3 > 0) {
                            $money = $fen_money3;
                            $result = $fck->where('id=' . $rs1['id'])->setInc('agent_use', $money);
                            $kt_cont = "" . C('pos_txt') . "";
                            $fck->addencProfitAdd($user['id'], $kt_cont . '-' . C('agent_use'), $money, $rs['order_no'], $rs['trade_time'], $rs['shop_title'], $all_fen_money, $all_trade_money, $user['u_level'], $chai_trade_money12, $project_id, $trade_money1, $trade_money2, $chai_trade_money1, $chai_trade_money2, $parent_u_level1, $parent_u_level2, $fen_money, $fen_money2 + $fen_money3); // 激活积分扣除历史记录
                                                                                                                                                                                                                                                                                                                                                                                                       
                            // 计算个人所得税
                            $tax = $money * ($user_shui[$u_level] / 100);
                            
                            $result = $fck->where('id=' . $user['id'])->setDec('agent_use', $tax);
                            $kt_cont = "" . C('pos_txt') . "扣" . C('tax_txt');
                            $fck->addencProfitAdd($user['id'], $kt_cont . '-' . C('agent_use'), - $tax, $rs['order_no'], $rs['trade_time'], $rs['shop_title']); // 激活积分扣除历史记录
                        }
                        
                        // $parent_u_level = $user['u_level'];
                        // 最大可以分润的金额 进行扣除,给下一个人分润
                        $all_fen_money = $all_fen_money - $fen_money - $fen_money2;
                        
                        $user = M('fck')->field('id, user_id,u_level,re_id')
                            ->where('id  =' . $user['id'])
                            ->find();
                        // 保存上一个人的等级
                        $parent_u_level = $user['u_level'];
                        $is_profit = 1;
                        
                        // $fen_money=0;
                        // $fen_money2 = 0;
                        // $fen_money3 = 0;
                    } else {
                        $is_profit = 0;
                    }
                    $parent_user_id = $user['id'];
                }
                $user = M('fck')->field('id, user_id,u_level,re_id,u_level,re_path')
                    ->where('id  =' . $rs['user_id'])
                    ->find();
                // 进行升级检测
                
                $this_month = strtotime(date('Y-m-01', $rs['trade_time']));
                
                user_level_check($user['id'], '  and trade_time>=' . $this_month . ' AND trade_time<=' . $rs['trade_time'], $this_month);
                M('trade_orders')->where('id=' . $rs['id'])->setField('tax', $tax);
                M('trade_orders')->where('id=' . $rs['id'])->setField('is_money', 1);
                M('trade_orders')->where('id=' . $rs['id'])->setField('money_time', time());
                
                // 返现
                back_pos_money($rs['trade_money'], $rs['shop_id'], $rs['id']);
            }
        }
        
        if (count($content) == 0) {
            
            $this->success('没有需要分润的数据！');
        } else {
            
            $this->success('分润成功！');
        }
    }

    public function importExcel()
    {
        // 表单提交文件过来
        // 获得文件路径
        $this->display();
    }

    public function upload()
    {
        $img = I('imgOne');
        $user_id = I('user_id');
        $url = I('url');
        
        // 文件夹日期
        $ymd = date("Ymd");
        
        // 图片路径地址
        $basedir = 'Public/Public/upload/' . $ymd . '';
        $fullpath = $basedir;
        if (! is_dir($fullpath)) {
            mkdir($fullpath, 0777, true);
        }
        $types = empty($types) ? array(
            'jpg',
            'gif',
            'png',
            'jpeg'
        ) : $types;
        
        $img = str_replace(array(
            '_',
            '-'
        ), array(
            '/',
            '+'
        ), $img);
        
        // $b64img = substr($img, 0, 100);
        
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $matches)) {
            
            $type = $matches[2];
            if (! in_array($type, $types)) {
                return array(
                    'status' => 1,
                    'info' => '图片格式不正确，只支持 jpg、gif、png、jpeg哦！',
                    'url' => ''
                );
            }
            $img = str_replace($matches[1], '', $img);
            $img = base64_decode($img);
            $photo = '/' . md5(date('YmdHis') . rand(1000, 9999)) . '.' . $type;
            file_put_contents($fullpath . $photo, $img);
            if ($user_id == 10016) {
                import("@.ORG.WaterMask");
                $file = $img; // 需要加水印的图片
                $file_ext = '.jpeg'; // 扩展名
                $imgFileName = $basedir . $photo; // 需要加水印图片路径
                $obj = new WaterMask($imgFileName); // 实例化对象
                
                $obj->waterTypeStr = true; // 开启文字水印
                $obj->waterTypeImage = true; // 开启图片水印
                $obj->pos = 9; // 定义水印图片位置
                $obj->waterImg = $url . '/Public/Images/water300.png'; // 水印图片
                
                $obj->transparent = 100; // 水印透明度
                $obj->waterStr = '保险经纪人：刘测试  电话：02052552'; // 水印文字
                $obj->fontSize = 9; // 文字字体大小
                $obj->fontColor = array(
                    0,
                    0,
                    0
                ); // 水印文字颜色（RGB）
                $obj->is_draw_rectangle = TRUE; // 开启绘制矩形区域
                $obj->output_img = $imgFileName; // 输出的图片路径
                
                $ret = $obj->output();
                // $ary['status'] = 0;
                // $ary['info'] =$obj->waterImg;
                
                // $this->ajaxReturn($ary);
            }
            
            $ary['status'] = 1;
            $ary['info'] = '上传成功';
            $ary['url'] = $basedir . $photo;
            $ary['path'] = $basedir . $photo;
            $this->ajaxReturn($ary);
            return;
        }
        
        $ary['status'] = 0;
        $ary['info'] = '请选择要上传的图片';
        
        $this->ajaxReturn($ary);
        return;
    }

    /**
     * 显示会员信息
     */
    public function toplist()
    {
        $is_mobile = $_POST['is_mobile'];
        
        $user_id = $_POST['user_id'];
        
        $map = array();
        
        $form = M('fck');
        
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->where($map)->count(); // 总页数
        
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        
        $list = $form->where($map)
            ->field($field)
            ->order('  agent_kt desc')
            ->page($p . ',10')
            ->select();
        
        foreach ($list as $i => $value) {
            $list[$i]['index'] = $i + 1;
            $list[$i]['agent_kt'] = round($value['agent_kt']);
        }
        
        // 推广数量列表
        $user_recommend_list = $form->where($map)
            ->field($field)
            ->order('  agent_kt desc')
            ->page($p . ',10')
            ->select();
        
        foreach ($user_recommend_list as $i => $item) {
            $user_recommend_list[$i]['index'] = $i + 1;
            
            $count = M('fck')->where('re_id=' . $item['id'])->count();
            $user_recommend_list[$i]['user_count'] = $count;
        }
        $this->assign('list', $list); // 数据输出到模板
        
        $project = M('project');
        // 最佳投手列表
        $project_users_list = $project->query('SELECT T.* FROM (

SELECT
	A.id,
	A.user_id,
	A.nickname,
	A.headimgurl, 
            IFNULL(count(B.id), 0) AS project_count
FROM
	xt_fck A
LEFT JOIN xt_project_users B ON A.ID = B.user_id AND B.status=0
GROUP BY
	A.ID) T ORDER BY T.project_count DESC  limit 10 ');
        
        foreach ($project_users_list as $i => $item) {
            $project_users_list[$i]['index'] = $i + 1;
        }
        
        // 发布者列表
        $publish_list = $project->query('SELECT T.* FROM (

SELECT
	A.id,
	A.user_id,
	A.nickname,
	A.headimgurl,
	IFNULL(SUM(B.all_money), 0) AS all_money,
            IFNULL(count(B.id), 0) AS project_count
FROM
	xt_fck A
LEFT JOIN xt_project B ON A.ID = B.user_id
GROUP BY
	A.ID) T ORDER BY T.all_money DESC  limit 10 ');
        
        foreach ($publish_list as $i => $item) {
            $publish_list[$i]['index'] = $i + 1;
        }
        $slide = array();
        $slide[0]['path'] = 'Public/Images/slides/s1.png';
        $slide[1]['path'] = 'Public/Images/slides/s2.png';
        $slide[2]['path'] = 'Public/Images/slides/s3.png';
        
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['user_recommend_list'] = $user_recommend_list;
            $data['publish_list'] = $publish_list;
            $data['project_users_list'] = $project_users_list;
            $data['slide'] = $slide;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            
            $this->display();
        }
    }

    public function kefu()
    {
        $id = $this->_get('id');
        
        $fck = M('fck');
        $user = $fck->where('id=' . $id)->find();
        
        $easemob_username = C('easemob_username');
        
        $easemob_password = C('easemob_password');
        
        $configId = C('configId');
        
        $this->assign('url', $_SERVER['SERVER_NAME']);
        $this->assign('easemob_password', $easemob_password);
        $this->assign('easemob_username', $easemob_username);
        $this->assign('configId', $configId);
        $this->assign('user', $user);
        
        $this->display();
    }

    public function init_data($PTid = 0)
    {
        // 删除会员
        $fck = M('fck');
        $list = $fck->field('id,is_boss')->select();
        foreach ($list as $k => $userInfo) {
            init_month_data($userInfo);
        }
        $this->success('交易量清零成功!');
    }

    /**
     * 显示会员信息
     */
    public function userInfo()
    {
        $id = I('get.id', 1);
        $os = $_POST['app_os'];
        $is_mobile = I('is_mobile', 1);
        if ($is_mobile == 1) {
            $id = I('post.id', 1);
            
            $_SESSION[C('USER_AUTH_KEY')] = $id;
        }
        if ($id == "" && $is_mobile != 1) {
            $fee_rs = M('fee')->field('str29,bankcard,bankusername,userbank,s9')->find();
            
            $trade_money = explode('|', $fee_rs['s9']);
            $id = $_SESSION[C('USER_AUTH_KEY')];
            $userInfo = M('fck')->where('id=' . $id)
                ->field('*')
                ->find();
            $userInfo['level_remain'] = $trade_money[$userInfo['u_level']] * 10000 - ($userInfo['team_trade_money']);
            
            $userInfo['level_percent'] = round(($userInfo['team_trade_money'] / ($trade_money[$userInfo['u_level']] * 10000)), 2);
            $w = "re_id= $id";
            $list = M('fck')->where($w)
                ->field('user_id,is_fenh,zjj,fanli')
                ->limit('10')
                ->select();
            $this->assign('list', $list);
        } else {
            if ($_SESSION[C('USER_AUTH_KEY')] == '1') {
                $condition = array(
                    'id' => $id
                );
            } else {
                $condition = array(
                    'id' => $id
                );
            }
            
            $userInfo = M('fck')->where($condition)
                ->field('*')
                ->find();
            
            if (! $userInfo) {
                $this->error('系统繁忙');
                exit();
            }
            
            $is_money_bao = I('is_money_bao', 0);
            if ($is_mobile == 1) {
                $sum_num = M('cart')->where('uid=' . $userInfo['id'])->sum('quantity');
                if ($sum_num == NULL) {
                    $sum_num = 0;
                }
                
                $order_num = M('orders')->where('user_id=' . $userInfo['id'])->count();
                $YHDJ = 0;
                $this->getLevelNamebyLevel($YHDJ, $userInfo['u_level']);
                $userInfo['uLevel'] = $YHDJ;
                $userInfo = get_user_info($userInfo, $userInfo['id']);
                
                M('fck')->where('id=' . $userInfo['id'])->setField('os', $os);
                
                $fee_rs = M('fee')->field('str29,bankcard,bankusername,userbank,s9,i1,str15,str13')->find();
                
                $userInfo['sxf'] = '微信需收取单笔提现' . $fee_rs['str15'] . '元通道使用费。';
                $userInfo['str13'] = $fee_rs['str13'];
                $trade_money = explode('|', $fee_rs['s9']);
                $bank = explode('|', $fee_rs['str29']);
                $bankcard = explode('|', $fee_rs['bankcard']);
                $bankusername = explode('|', $fee_rs['bankusername']);
                $userbank = explode('|', $fee_rs['userbank']);
                $userInfo['user_bank'] = $userbank;
                foreach ($userbank as $k => $item2) {
                    $item1 = array();
                    $item1['label'] = $item2;
                    $item1['value'] = $k + 1;
                    $item1['text'] = $item2;
                    $item1['id'] = $item2;
                    $userbank[$k] = $item1;
                }
                $userInfo['userbank'] = $userbank;
                foreach ($bank as $k => $item) {
                    $item1 = array();
                    $item1['label'] = $item;
                    $item1['value'] = $item;
                    $item1['bankcard'] = $bankcard[$k];
                    $item1['bankusername'] = $bankusername[$k];
                    $item1['id'] = $item;
                    $bank[$k] = $item1;
                }
                $list1 = ARRAY();
                $list2 = ARRAY();
                IF ($is_money_bao == 0) {}
                $slider = ARRAY();
                
                $fee = M('fee');
                
                $goods_images = $fee->where('id=1')->getField('str29');
                
                if (! empty($goods_images)) {
                    $goods_images = explode(",", $goods_images);
                    
                    $list = array();
                    
                    foreach ($goods_images as $key => $rs) {
                        $item = array();
                        $item['img'] = $rs;
                        $slider[] = $item;
                    }
                }
                
                // $slider[0]['img'] = __ROOT__ . '/Public/Images/slides/3.jpg';
                // $slider[1]['img'] = __ROOT__ . '/Public/Images/slides/2.jpg';
                $userInfo['new_level_trade_money'] = $trade_money[$userInfo['u_level']] * 10000;
                $userInfo['agent_use'] = number_format($userInfo['agent_use'], 2);
                
                $userInfo['level_remain'] = $userInfo['new_level_trade_money'] - ($userInfo['team_trade_money']);
                
                $userInfo['level_percent'] = round($userInfo['team_trade_money'] / ($userInfo['new_level_trade_money']), 2);
                
                $userInfo['team_trade_money'] = round($userInfo['team_trade_money']);
                
                $userInfo['level_remain'] = bqwhits($userInfo['level_remain']);
                $userInfo['kefu_url'] = 'https://kefu.easemob.com/webim/im.html?configId=256624ed-f1a7-4404-9efe-0693f9dde739';
                // $userInfo['kefu_url'] = 'http://' . $_SERVER['SERVER_NAME'] . '/adm.php/User/kefu/id/' . $userInfo['id'];
                
                $configId = C('configId');
                $userInfo['configId'] = $configId;
                $app_icon = ARRAY();
                
                $fee = M('fee');
                
                $goods_images = $fee->where('id=1')->getField('str12');
                
                if (! empty($goods_images)) {
                    $goods_images = explode(",", $goods_images);
                    
                    $list = array();
                    
                    foreach ($goods_images as $key => $rs) {
                        $item = array();
                        $item['img'] = $rs;
                        $app_icon[] = $item;
                    }
                }
                
                // $app_icon[0]['img'] = __ROOT__ . '/Public/Images/mmexport1542552933236.jpg';
                // $app_icon[1]['img'] = __ROOT__ . '/Public/Images/mmexport1542552931968.jpg';
                // $app_icon[2]['img'] = __ROOT__ . '/Public/Images/mmexport1542552930674.jpg';
                // $app_icon[3]['img'] = __ROOT__ . '/Public/Images/mmexport1542552929327.jpg';
                // $app_icon[4]['img'] = __ROOT__ . '/Public/Images/mmexport1542552927934.jpg';
                // $app_icon[5]['img'] = __ROOT__ . '/Public/Images/mmexport1542552926394.jpg';
                // $app_icon[6]['img'] = __ROOT__ . '/Public/Images/mmexport1542552922821.jpg';
                // $app_icon[7]['img'] = __ROOT__ . '/Public/Images/mmexport1542552920914.jpg';
                
                $userInfo['app_icon'] = $app_icon;
                $userInfo['eweima_time'] = strtotime('+' . $fee_rs['i1'] . ' minute', time());
                IF ($is_money_bao == 1) {} else {
                    // order_num1
                    // 待付款
                }
                
                $array = ARRAY();
                for ($i = 1; $i < 5; $i ++) {
                    $item['money'] = $i * 100;
                    $item['logo'] = $i * 1;
                    
                    $array[] = $item;
                }
                $userInfo['money_list'] = $array;
                
                $goods_show_list= M('goods_show')->alias('t')
                ->join("xt_goods AS g ON   g.id = t.goods_id ", 'LEFT')
                ->join("xt_fck AS h ON   h.id = t.uid ", 'LEFT') 
                ->where('t.uid='.$userInfo['id'].' AND  g.id = t.goods_id ' )
                ->field('t.*,g.title,g.img') 
                ->order(' t.add_time desc ')
                ->group(' t.goods_id ')
                ->limit('1,10')
                ->select();
                foreach ($goods_show_list as $i => $goods) {
                    $goods_show_list[$i]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $goods_show_list[$i]['img'];
                    $goods_show_list[$i]['url'] = '/pages/product/product?id='.$goods['goods_id'];
                }
                $userInfo['goods_show_list'] = $goods_show_list;
                $userInfo['goods_show_list_count'] = count($goods_show_list);
                
                
                
                // IF (EMPTY($userInfo['easemob_uuid'])) {
                // $url = 'https://a1.easemob.com/easemob-demo/chatdemoui/users';
                // $post_data = array();
                // $post_data['username'] = $userInfo['user_id'];
                // $post_data['password'] = $userInfo['pwd1'];
                // $info = request_get($url, $post_data);
                // }
                
                
                $userInfo['portrait']= 'http://'.   $_SERVER['SERVER_NAME'].'/'.    $userInfo['avatar'];
                $userInfo['re_tip'] = '我为' . C('System_namex') . '代言';
                $userInfo['re_share_sub_title'] = C('re_share_sub_title');
                $userInfo['re_share_title'] = C('re_share_title');
                $userInfo['re_pay_sub_title'] = C('re_pay_sub_title');
                $userInfo['re_pay_title'] = C('re_pay_title');
//                 $userInfo['userbank'] =$userbank;
                $userInfo['tip'] = '未来已来,邀请你来';
                $userInfo['user_count1'] = 0;
                $userInfo['user_count2'] = 0;
                $userInfo['user_count3'] = 0;
                $userInfo['user_count4'] =0;
                $bi = $this->_Config_name();
                $userInfo['bi'] = $bi;
                $data = array();
                $data['msg'] = '获取成功';
                $data['data'] = $userInfo;
                $data['cart_num'] = $sum_num;
                $data['order_num'] = $order_num;
                $data['slider'] = $slider;
                $data['userbank'] = $userbank;
                $data['bank_list'] = $bank_list;
                $data['bi'] = $bi;
                $data['re_share_sub_title'] = C('re_share_sub_title');
                $data['re_share_title'] = C('re_share_title');
                ;
                $data['status'] = 1;
                $this->ajaxReturn($data);
            }
        }
        $userInfo['live_gupiao'] = (int) $userInfo['live_gupiao'];
        $this->assign('user', $userInfo);
        $this->getLevelNamebyLevel($YHDJ, $userInfo['u_level']);
        $this->assign('uLevel', $YHDJ); // 会员级别
        
        $this->display();
    }

    /**
     * 判断余额
     */
    public function check_user_money()
    {
        $money = $this->_post('money');
        $id = $this->_post('id');
        $userInfo = M('fck')->where('id=' . $id)
            ->field('*')
            ->find();
        if ($money > $userInfo['agent_use']) {
            $this->ajaxError('对不起,余额不足！');
            exit();
        }
        
        $data = array();
        $data['msg'] = '获取成功';
        $data['data'] = $userInfo;
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    /**
     * 显示子账户信息
     */
    public function subuserList()
    {
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $row = M('fck')->where(array(
            'id' => $id
        ))->find();
        $this->assign('rs', $row);
        
        $fckw['z_id'] = array(
            'eq',
            $id
        );
        $fckw['is_pay'] = array(
            'gt',
            0
        );
        
        import("@.ORG.Page"); // 导入分页类
        $count = M('fck')->where($fckw)->count(); // 总页数
        
        $Page = new Page($count, C('ONE_PAGE_RE'));
        $this->assign('page', $Page->show());
        $p = $this->_get('p', true, '1');
        $list = M('fck')->where($fckw)
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->select();
        $this->assign('list', $list);
        
        $fee = M('fee');
        $fee_s = $fee->field('*')->find();
        $s9 = explode('|', $fee_s['s9']);
        $ss10 = explode('|', $fee_s['s10']);
        $s10 = array_slice($ss10, 2);
        
        $this->assign('s9', $s9);
        $this->assign('s10', $s10);
        
        $this->display();
    }

    /**
     * 编辑会员信息
     */
    public function userEdit()
    {
        if (! IS_POST) {
            $ID = $_SESSION[C('USER_AUTH_KEY')];
            $userInfo = M('fck')->where('id=' . $ID)
                ->field('*')
                ->find();
            $this->assign('user', $userInfo);
            // $this->getLevelNamebyLevel($YHDJ,$userInfo['u_level']);
            // $this->assign('uLevel',$YHDJ);//会员级别
            $wenti = M('fee')->where(array(
                'id' => 1
            ))->getField('str24');
            $wentilist = explode('|', $wenti);
            $this->assign('wentilist', $wentilist);
            // 输出银行
            $fee_s = M('fee')->where(array(
                'id' => 1
            ))->getField('str29');
            $banklist = explode('|', $fee_s);
            $this->assign('bank', $banklist);
            unset($userInfo);
            $this->display();
        } else {
            $ID = $_SESSION[C('USER_AUTH_KEY')];
            $fck = D('Fck');
            $res = $fck->where('id=' . $ID)
                ->field('id')
                ->find();
            if (! $res) {
                $this->ajaxError('警告,非法提交!');
            }
            $data['id'] = $ID;
            $data['user_name'] = $this->_post('username');
            // $data['user_code'] = $this->_post('idcard');
            // $data['user_tel'] = $this->_post('mobile');
            // $data['bank_name'] = $this->_post('bankname');
            // $data['bank_card'] = $this->_post('bankcard');
            // $data['name'] = $this->_post('huzhu');
            // $data['bank_address'] = $this->_post('bankaddress');
            // $data['alipay'] = $this->_post('alipay');
            // $data['nickname'] = $this->_post('nickname');
            // $data['user_address'] = $this->_post('user_address');
            // $data['email'] = $this->_post('email');
            // $data['wenti'] = $this->_post('wenti');
            // $data['wenti_dan'] = $this->_post('daan');
            if ($fck->create($data)) {
                $fck->save();
                unset($data);
                $data = array();
                $data['info'] = '修改资料成功';
                $data['msg'] = '修改资料成功';
                $data['status'] = 1;
                $this->ajaxReturn($data);
            } else {
                $this->error($fck->getError());
            }
        }
    }

    public function pwdEdit()
    {
        if (! IS_POST) {
            $this->display();
        } else {
            $is_mobile = $this->_post('is_mobile');
            $data['id'] = $_SESSION[C('USER_AUTH_KEY')];
            if ($is_mobile == 1) {
                
                $data['id'] = $this->_post('user_id');
            }
            $fck = M('fck')->field('*')
                ->where('id=' . $data['id'])
                ->find();
            
            $old_pwd = $this->_post('old_pwd');
            
            if ($old_pwd != $fck['pwd1']) {
                $this->ajaxError('一级密码不正确');
            }
            
            $data['password'] = md5($this->_post('pwd'));
            $data['pwd1'] = $this->_post('pwd');
            $data['passwordcom'] = $this->_post('repwd');
            
            if (empty($data['pwd1'])) {
                $this->ajaxError('请输入新密码');
            }
            if (empty($data['passwordcom'])) {
                $this->ajaxError('请再次输入新密码');
            }
            
            if ($this->_post('pwd') != $data['passwordcom']) {
                $this->ajaxError('一级确认密码不正确');
            }
            
            // var_dump($data);
            $fck = D('Fck');
            if ($fck->create($data)) {
                $fck->save();
                unset($data);
                $this->ajaxSuccess('修改密码成功,请重新登录！');
            } else {
                $this->error($fck->getError());
            }
        }
    }

    public function pwd2Edit()
    {
        if (! IS_POST) {
            $this->display();
        } else {
            $is_mobile = $this->_post('is_mobile');
            $data['id'] = $_SESSION[C('USER_AUTH_KEY')];
            if ($is_mobile == 1) {
                
                $data['id'] = $this->_post('user_id');
            }
            $fck = M('fck')->field('*')
                ->where('id=' . $data['id'])
                ->find();
            
            $old_pwd = $this->_post('old_pwd');
            
            if ($old_pwd != $fck['pwd2']) {
                $this->ajaxError('支付密码不正确');
            }
            
            $data['passopen'] = md5($this->_post('pwd'));
            $data['pwd2'] = $this->_post('pwd');
            $data['passwordcom'] = $this->_post('repwd');
            
            if ($this->_post('pwd') != $data['passwordcom']) {
                $this->ajaxError('支付确认密码不正确');
            }
            if ($old_pwd != $fck['pwd2']) {
                $this->ajaxError('支付密码不正确');
                return;
            }
            if (! is_numeric($data['pwd2'])) {
                
                $this->ajaxError('支付密码必须是数字');
                return;
            }
            if (strlen($data['pwd2']) != 6) {
                
                $this->ajaxError('支付密码必须是6位');
                return;
            }
            
            // var_dump($data);
            $fck = D('Fck');
            if ($fck->create($data)) {
                $fck->save();
                unset($data);
                $this->ajaxSuccess('修改支付密码成功！');
            } else {
                $this->ajaxError($fck->getError());
            }
        }
    }

    public function check_password2()
    {
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];
        // $id = $_POST['id'];
        $User = M('fck');
        $where['id'] = $user_id;
        $rs = $User->where($where)->find();
        if ($rs['pwd2'] != $password) {
            $this->ajaxError('交易密码不正确');
            exit();
        }
        
        $data['info'] = '验证成功！';
        $data['msg'] = '验证成功！';
        $data['status'] = 1;
        $data['old_pwd'] = $rs['pwd2'];
        $this->ajaxReturn($data);
        exit();
    }

    public function forget_password()
    {
        $data['id'] = $this->_post('user_id');
        $is_mobile = $this->_post('is_mobile');
        $data['user_name'] = $this->_post('user_name');
        
        $data['password'] = md5($this->_post('pwd'));
        $data['pwd1'] = $this->_post('pwd');
        
        if ($_POST['user_name'] == '' && $is_mobile == 1) {
            $this->ajaxError('请输入用户名！');
            exit();
        }
        
        $user = M('fck')->where(array(
            'user_id' => $data['user_name']
        ))->find();
        if ($user == null) {
            $this->ajaxError('对不起,用户名不存在！');
            exit();
        }
        if ($_POST['sjyzm'] == '' && $is_mobile == 1) {
            $this->ajaxError('请输入手机验证码！');
            exit();
        }
        
        $user_code = M('user_code');
        $sms_template = M('sms_template')->where(array(
            'id' => array(
                'eq',
                '1'
            )
        ))->find();
        
        $usercode = $user_code->where("user_name='" . $data['user_name'] . "' AND type='" . $sms_template['call_index'] . "' and status=1 ")
            ->order('id desc')
            ->find();
        
        if ($usercode != null) {
            $now = time();
            if ($usercode['eff_time'] < $now && $is_mobile == 1) {
                $this->ajaxError('验证码已过期,请重新获取！');
                exit();
            }
            
            if ($usercode['str_code'] != $_POST['sjyzm'] && $is_mobile == 1) {
                $this->ajaxError('手机验证码错误！');
                exit();
            }
            $user_code->where("user_name='" . $data['user_name'] . "'  ")->setField('status', 0);
        } else {
            $this->ajaxError('请获取手机验证码！');
            exit();
        }
        
        M('fck')->where(array(
            'id' => $user['id']
        ))->setField('password', $data['password']);
        M('fck')->where(array(
            'id' => $user['id']
        ))->setField('pwd1', $data['pwd1']);
        
        $data = array();
        $data['msg'] = '重置密码成功！';
        $data['info'] = '重置密码成功！';
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    public function phoneEdit()
    {
        $is_mobile = $this->_post('is_mobile');
        $phone = $this->_post('phone');
        $new_phone = $this->_post('new_phone');
        $user_id = $this->_post('user_id');
        $sjyzm = $this->_post('sjyzm');
        $data['id'] = $user_id;
        $data['user_tel'] = $new_phone;
        
        $fck = M('fck');
        $rs = $fck->where("user_id='" . $new_phone . "'")
            ->field('id,email,user_id,user_name,pwd1,pwd2')
            ->find();
        if ($rs != NULL) {
            $data1 = array(
                'status' => 0,
                'info' => '此号码注册过，不能修改',
                'msg' => '此号码注册过，不能修改'
            );
            $this->ajaxReturn($data1);
        }
        
        if ($sjyzm == '' && $is_mobile == 1) {
            $this->error('请输入手机验证码！');
            exit();
        }
        $user_code = M('user_code');
        $sms_template = M('sms_template')->where(array(
            'id' => array(
                'eq',
                '1'
            )
        ))->find();
        
        $data['user_id'] = $new_phone;
        $usercode = $user_code->where("user_name='" . $new_phone . "' AND type='" . $sms_template['call_index'] . "' and status=1 ")
            ->order('id desc')
            ->find();
        
        if ($usercode != null) {
            $now = time();
            if ($usercode['eff_time'] < $now && $is_mobile == 1) {
                $this->error('验证码已过期,请重新获取！');
                exit();
            }
            
            if ($usercode['str_code'] != $_POST['sjyzm'] && $is_mobile == 1) {
                $this->error('手机验证码错误！');
                exit();
            }
            $user_code->where("user_name='" . $new_phone . "'  ")->setField('status', 0);
        }
        
        // var_dump($data);
        $fck = D('Fck');
        if ($fck->create($data)) {
            $fck->save();
            unset($data);
            
            $userInfo = M('fck')->where('id=' . $user_id)->find();
            $data = array();
            $data['data'] = $userInfo;
            $data['user_id'] = $userInfo;
            $data['msg'] = '修改手机号成功！';
            $data['info'] = '修改手机号成功！';
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->error($fck->getError());
        }
    }

    // 银行卡信息修改
    public function bankEdit()
    {
        $is_mobile = $this->_post('is_mobile');
        $bank = $this->_post('bank_name');
        $bank_card = $this->_post('bank_card');
        $user_tel = $this->_post('user_tel');
        $user_id = $this->_post('user_id');
        $sjyzm = $this->_post('yzm');
        $data['id'] = $user_id;
        $data['bank_card'] = $bank_card;
        $data['bank_name'] = $bank;
        
        $fck = M('fck');
        $rs = $fck->where("id='" . $user_id . "'")
            ->field('id,email,user_id,user_name,pwd1,pwd2')
            ->find();
        
        if ($sjyzm == '' && $is_mobile == 1) {
            $this->ajaxError('请输入手机验证码！');
            exit();
        }
        $user_code = M('user_code');
        $TemplateCode = C('ali_sms_tid_change_bank');
        $sms_template = M('sms_template')->where(array(
            'template_id' => array(
                'eq',
                $TemplateCode
            )
        ))->find();
        
        $data['user_id'] = $user_tel;
        $usercode = $user_code->where("user_name='" . $user_tel . "' AND type='" . $sms_template['call_index'] . "' and status=1 ")
            ->order('id desc')
            ->find();
        
        if ($usercode != null) {
            $now = time();
            if ($usercode['eff_time'] < $now && $is_mobile == 1) {
                $this->ajaxError('验证码已过期,请重新获取！');
                exit();
            }
            
            if ($usercode['str_code'] != $sjyzm && $is_mobile == 1) {
                $this->ajaxError('手机验证码错误！');
                exit();
            }
            $user_code->where("user_name='" . $user_tel . "'  ")->setField('status', 0);
        } else {
            $this->ajaxError('请先获取手机验证码！');
            exit();
        }
        
        // var_dump($data);
        $fck = D('Fck');
        if ($fck->create($data)) {
            $fck->save();
            unset($data);
            
            $userInfo = M('fck')->where('id=' . $user_id)->find();
            $data = array();
            $data['data'] = $userInfo;
            $data['msg'] = '修改成功！';
            $data['info'] = '修改成功！';
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->ajaxError($fck->getError());
        }
    }

    public function weixinauthEdit()
    {
        $username = $this->_post('username');
        $openid = $this->_post('openid');
        $unionid = $this->_post('unionid');
        $headimgurl = $this->_post('headimgurl');
        $nickname = $this->_post('nickname');
        $data['openid'] = $openid;
        
        $fck = M('fck');
        $userInfo = $fck->where('user_id="' . $username . '"')
            ->order('id desc')
            ->find();
        
        if ($userInfo == null && empty($username)) {
            $this->ajaxError('请输入手机号进行绑定');
            exit();
        }
        if ($userInfo == null) {
            $userInfo = $fck->where('user_id="' . $username . '"')
                ->order('id desc')
                ->find();
            if ($userInfo == null) {
                $this->ajaxError('手机号不存在');
                exit();
            }
        }
        if ($userInfo['is_lock'] == 1) {
            $this->ajaxError(C('LOCK_MSG'));
            exit();
        }
        $fck->where('id=' . $userInfo['id'])->setField('openid', $openid);
        $fck->where('id=' . $userInfo['id'])->setField('unionid', $unionid);
        $fck->where('id=' . $userInfo['id'])->setField('wx_nickname', $nickname);
        $fck->where('id=' . $userInfo['id'])->setField('nickname', $nickname);
        
        $fck->where('id=' . $userInfo['id'])->setField('headimgurl', $headimgurl);
        $fee = M('fee');
        $fee_rs = $fee->field('recommend_award')->find(1);
        $money = $fee_rs['recommend_award'];
        login_recommend_award($userInfo, $money);
        ;
        $data = array();
        $data['is_login'] = 1;
        $data['data'] = $userInfo;
        $data['user_id'] = $userInfo;
        $data['msg'] = '登录成功！';
        $data['info'] = '登录成功！';
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    // 微信信息修改
    public function wxYzmCheck()
    {
        $is_mobile = $this->_post('is_mobile');
        $user_id = $this->_post('user_id');
        $user_tel = $this->_post('user_tel');
        $sjyzm = $this->_post('yzm');
        $data['id'] = $user_id;
        
        $fck = M('fck');
        $rs = $fck->where("id='" . $user_id . "'")
            ->field('id,email,user_id,user_name,pwd1,pwd2')
            ->find();
        
        if ($sjyzm == '' && $is_mobile == 1) {
            $this->ajaxError('请输入手机验证码！');
            exit();
        }
        $user_code = M('user_code');
        $TemplateCode = C('ali_sms_tid_change_wx');
        $sms_template = M('sms_template')->where(array(
            'template_id' => array(
                'eq',
                $TemplateCode
            )
        ))->find();
        
        $data['user_id'] = $user_tel;
        $usercode = $user_code->where("user_name='" . $user_tel . "' AND type='" . $sms_template['call_index'] . "' and status=1 ")
            ->order('id desc')
            ->find();
        
        if ($usercode != null) {
            $now = time();
            if ($usercode['eff_time'] < $now && $is_mobile == 1) {
                $this->ajaxError('验证码已过期,请重新获取！');
                exit();
            }
            
            if ($usercode['str_code'] != $sjyzm && $is_mobile == 1) {
                $this->ajaxError('手机验证码错误！');
                exit();
            }
            $user_code->where("user_name='" . $user_tel . "'  ")->setField('status', 0);
        } else {
            $this->ajaxError('请先获取手机验证码！');
            exit();
        }
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('i1')->find();
        $eweima_time = strtotime('+' . $fee_rs['i1'] . ' minute', time());
        $fck = D('Fck');
        
        $data['msg'] = '验证成功！';
        $data['info'] = '验证成功！';
        $data['status'] = 1;
        $data['eweima_time'] = $eweima_time;
        $this->ajaxReturn($data);
    }

    public function authEdit()
    {
        $user_id = $this->_post('user_id');
        $data['id'] = $user_id;
        $jsonStr = $this->_post('jsonStr');
        $openid = $this->_post('openid');
        $unionid = $this->_post('unionid');
        $headimgurl = $this->_post('headimgurl');
        $nickname = $this->_post('nickname');
        $userInfo = json_encode($jsonStr);
        $data['openid'] = $openid;
        
        $fck = M('fck');
        $userInfo = $fck->where('id=' . $user_id)->find();
        $fck->where('id=' . $user_id)->setField('openid', $openid);
        $fck->where('id=' . $user_id)->setField('unionid', $unionid);
        $fck->where('id=' . $user_id)->setField('wx_nickname', $nickname);
        $fck->where('id=' . $user_id)->setField('nickname', $nickname);
        
        if (EMPTY($userInfo['headimgurl'])) {
            $fck->where('id=' . $user_id)->setField('headimgurl', $headimgurl);
        }
        
        $data = array();
        $data['is_login'] = 1;
        $data['data'] = $userInfo;
        $data['user_id'] = $userInfo;
        $data['msg'] = '更新成功！';
        $data['info'] = '更新成功！';
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    public function avatarEdit()
    {
        $user_id = $this->_post('user_id');
        $data['id'] = $user_id;
        $avatar = $this->_post('avatar');
        
        $fck = M('fck');
        $fck->where('id=' . $user_id)->setField('avatar', $avatar);
        
        $userInfo = $fck->where('id=' . $user_id)->find();
        $data = array();
        $data['data'] = $userInfo;
        $data['msg'] = '更新成功！';
        $data['info'] = '更新成功！';
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    // 分享表
    public function relations($Urlsz = 0)
    {
        // 分享关系
        if ($_SESSION['Urlszpass'] == 'MyssHuoLongGuo') {
            $fck = M('fck');
            $UserID = $_REQUEST['UserID'];
            if (! empty($UserID)) {
                $map['user_id'] = array(
                    'like',
                    "%" . $UserID . "%"
                );
            }
            $map['re_id'] = $_SESSION[C('USER_AUTH_KEY')];
            $map['is_pay'] = 1;
            
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
                ->order('pdt desc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            $HYJJ = '';
            $this->_levelConfirm($HYJJ, 1);
            $this->assign('voo', $HYJJ); // 会员级别
            $this->assign('list', $list); // 数据输出到模板
                                          // =================================================
            
            $this->display('relations');
            return;
        } else {
            $this->error('数据错误2!');
            exit();
        }
    }

    // 本轮位置
    public function showPlace()
    {
        // 列表过滤器，生成查询Map对象
        $fck = M('fck2');
        
        $id = $_SESSION[C('USER_AUTH_KEY')];
        // echo '=============id===='.$id.'<br>';
        $field = 'id,p_path,p_level,is_yinc';
        $fck_rs = $fck->where(array(
            'fck_id' => $id,
            'is_pay' => 1
        ))
            ->field($field)
            ->order('id desc,pdt desc')
            ->find(); // 找出最后一个
        $fck3_id = 0;
        if ($fck_rs) {
            $fck3_id = $fck_rs['id']; // fck3的id
            $p_path = $fck_rs['p_path'];
            
            // 自己是组长
            $fck_sons = array();
            if ($fck_rs['is_yinc'] == 1) {
                $where = 'is_pay>0 and (p_path like "%,' . $fck3_id . ',%" or id=' . $fck3_id . ')';
                $fck_sons = $fck->where($where)
                    ->order('pdt asc,p_level asc')
                    ->limit(7)
                    ->select();
            } else {
                $father_up = $fck->where(array(
                    'id' => array(
                        'in',
                        '0' . $p_path . '0'
                    ),
                    array(
                        'is_yinc' => array(
                            'eq',
                            1
                        )
                    )
                ))
                    ->order('p_level desc')
                    ->find();
                if ($father_up) {
                    $pid = $father_up['id'];
                    $condition = 'is_pay>0 and (p_path like "%,' . $pid . ',%" or id=' . $pid . ')';
                    $fck_sons = $fck->where($condition)
                        ->order('pdt asc,p_level asc')
                        ->limit(7)
                        ->select();
                }
            }
            
            // var_dump($fck_sons);
            $this->assign('list', $fck_sons);
            $this->assign('uid', $id);
        }
        
        $this->display();
    }

    // 前后5人
    public function member_x()
    {
        if ($_SESSION['Urlszpass'] == 'Myssmemberx') {
            $fck = M('fck');
            $id = $_SESSION[C('USER_AUTH_KEY')];
            $myrs = $fck->where('id=' . $id)
                ->field('id,user_id,n_pai')
                ->find();
            $n_pai = $myrs['n_pai'];
            
            $field = 'id,user_id,n_pai,pdt,user_tel,qq';
            // 前面5个
            $wherea = "is_pay>0 and n_pai<" . $n_pai;
            $alist = $fck->where($wherea)
                ->field($field)
                ->order('n_pai desc')
                ->limit(5)
                ->select();
            $this->assign('alist', $alist);
            
            // 后5个
            $whereb = "is_pay>0 and n_pai>" . $n_pai;
            $blist = $fck->where($whereb)
                ->field($field)
                ->order('n_pai asc')
                ->limit(5)
                ->select();
            $this->assign('blist', $blist);
            // dump($blist);exit;
            
            $this->display('member_x');
            return;
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    // 一线排网
    public function member_z()
    {
        if ($_SESSION['Urlszpass'] == 'Myssmemberz') {
            $fck = M('fck');
            $id = $_SESSION[C('USER_AUTH_KEY')];
            $myrs = $fck->where('id=' . $id)
                ->field('id,user_id,x_pai')
                ->find();
            $x_pai = $myrs['x_pai'];
            
            $field = 'id,user_id,x_pai,pdt,user_tel,qq,x_num,x_out';
            
            $wherea = "is_pay>0 and x_pai>=" . $x_pai;
            // =====================分页开始==============================================
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $fck->where($wherea)->count(); // 总页数
            $listrows = 20; // 每页显示的记录数
            $page_where = ''; // 分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $list = $fck->where($wherea)
                ->field($field)
                ->order('x_pai asc,id asc')
                ->page($Page->getPage() . ',' . $listrows)
                ->select();
            $this->assign('list', $list); // 数据输出到模板
                                          // =================================================
            
            $nn = $fck->where("is_pay>0 and x_pai<" . $x_pai . " and x_out=0")->count();
            $this->assign('nn', $nn);
            
            $this->display('member_z');
            return;
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    // 统计
    public function adminalltongji()
    {
        $this->_Admin_checkUser();
        $fck = M('fck');
        $msg = M('msg');
        $chongzhi = M('chongzhi');
        $tiqu = M('tiqu');
        $tiqu = M('tiqu');
        
        $now_day = strtotime(date("Y-m-d"));
        $end_day = $now_day + 3600 * 24;
        
        $yes_day = $now_day - 3600 * 24;
        
        $yes_c = $fck->where('is_pay>0 and pdt>=' . $yes_day . ' and pdt<' . $now_day)->count(); // 昨日新进
        $day_c = $fck->where('is_pay>0 and pdt>=' . $now_day . ' and pdt<' . $end_day)->count(); // 今日新进
        $not_c = $fck->where('is_pay=0')->count(); // 未开通
        $msg_c = $msg->where("s_uid=1 and s_read=0")->count(); // 总记录数
        $chz_c = $chongzhi->where("is_pay=0")->count(); // 充值
        $tix_c = $tiqu->where("is_pay=0")->count(); // 提现
        $bad_c = $fck->where("is_agent=1 and is_pay>0")->count(); // 服务中心
        
        $this->assign('yes_c', $yes_c);
        $this->assign('day_c', $day_c);
        $this->assign('not_c', $not_c);
        $this->assign('msg_c', $msg_c);
        $this->assign('chz_c', $chz_c);
        $this->assign('tix_c', $tix_c);
        $this->assign('upl_c', 0);
        $this->assign('bad_c', $bad_c);
        $this->assign('did_c', 0);
        
        $this->display();
    }

    // 未开通会员
    public function menber($Urlsz = 0)
    {
        // 列表过滤器，生成查询Map对象
        if ($_SESSION['UrlPTPass'] == 'MyssShuiPuTao') {
            $fck = M('fck');
            $map = array();
            $id = $_SESSION[C('USER_AUTH_KEY')];
            
            $rsss = $fck->where('id=' . $id)
                ->field('is_zy')
                ->find();
            // $gid = (int) $_GET['bj_id'];
            // $map['shop_id'] = $id;
            // $UserID = $_POST['UserID'];
            // if (!empty($UserID)){
            // import ( "@.ORG.KuoZhan" ); //导入扩展类
            // $KuoZhan = new KuoZhan();
            // if ($KuoZhan->is_utf8($UserID) == false){
            // $UserID = iconv('GB2312','UTF-8',$UserID);
            // }
            // unset($KuoZhan);
            // $where['nickname'] = array('like',"%".$UserID."%");
            // $where['user_id'] = array('like',"%".$UserID."%");
            // $where['_logic'] = 'or';
            // $map['_complex'] = $where;
            // $UserID = urlencode($UserID);
            // }
            $map['is_pay'] = array(
                'eq',
                1
            );
            $map['_string'] = "is_zy=" . $id . " or is_zy=" . $rsss['is_zy'];
            
            // 查询字段
            $field = '*';
            // =====================分页开始==============================================
            import("@.ORG.ZQPage"); // 导入分页类
            $count = $fck->where($map)->count(); // 总页数
            $listrows = C('ONE_PAGE_RE'); // 每页显示的记录数
            $page_where = ''; // 分页条件
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
            $this->display('menber');
            exit();
        } else {
            $this->error('数据错误!');
            exit();
        }
    }

    public function myRecommendlist()
    {
        $id = I('post.user_id', 0);
        $is_mobile = $_POST['is_mobile'];
        $keywords = $_POST['keywords'];
        $type = I('post.type', 1);
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 10000);
        $row = M('fck')->where(array(
            'id' => $id
        ))->find();
        
        if (! empty($keywords)) {
            
            $fckw['user_name'] = array(
                'like',
                '%' . $keywords . '%'
            );
        }
        
        $fckw['re_id'] = array(
            'eq',
            $id
        );
        if ($type == 0) {
            $fckw['_string'] = ' card_img4 is not null or is_boss=2 ';
        }
        if ($type == 1) {
            
            $fckw['card_img4'] = array(
                'EXP',
                ' is null '
            );
            $fckw['is_boss'] = array(
                'neq',
                2
            );
            $fckw['auth_status'] = array(
                'eq',
                0
            );
        }
        $page_ = C('ONE_PAGE_RE');
        import("@.ORG.Page"); // 导入分页类
        $count = M('fck')->where($fckw)->count(); // 总页数
        $p = $this->_get('p', true, '1');
        if ($is_mobile == 1) {
            $p = $_POST['p'];
            $page_ = 100000;
        }
        $Page = new Page($count, $page_);
        $this->assign('page', $Page->show());
        
        $list = M('fck')->where($fckw)
            ->page($p . ',' . $page_)
            ->order('py asc ')
            ->page($p . ',' . $page_size)
            ->field(' ifnull(firstPinyin(LTRIM(  ifnull(user_name,"未编辑"))),"Z") as py,id,user_id,user_tel,ifnull(user_name,"未编辑") as user_name,nickname,avatar')
            ->select();
        $pinyin = '';
        foreach ($list as $key => $vo) {
            
            $list[$key]['key'] = $key;
            if ($vo['treeplace'] == 0) {
                $list[$key]['treeplace_str'] = '左区';
            } else {
                
                $list[$key]['treeplace_str'] = '右区';
            }
            $list[$key]['rdt_str'] = date("Y-m-d H:i", $vo['rdt']);
            $list[$key]['pdt_str'] = date("Y-m-d H:i", $vo['pdt']);
            $list[$key]['level_str'] = '普通会员';
            $list[$key]['status_str'] = '已激活';
            
            // $shop_num = M('seller')->where(ARRAY(
            // 'user_id' => $vo['id']
            // ))->count();
            
            // $list[$key]['shop_num'] = $shop_num;
            // $trade_money = M('trade_orders')->where(ARRAY(
            // 'user_id' => $vo['id']
            // ))->sum('trade_money');
            // if ($trade_money == NULL) {
            // $trade_money = 0;
            // }
            // $list[$key]['trade_money'] = $trade_money;
            // $shop_num = M('fck')->where('re_id =' . $vo['id'])->count();
            // $list[$key]['user_num'] = 0;
        }
        $this->assign('list', $list);
        if ($_POST['is_mobile'] == 1) {
            $fckw = array();
            $fckw['re_id'] = array(
                'eq',
                $id
            );
            $fckw['card_img4'] = array(
                'EXP',
                'is not null '
            );
            $fckw['auth_status'] = array(
                'eq',
                0
            );
            // 已实名
            $count1 = M('fck')->where($fckw)->count();
            $fckw = array();
            $fckw['re_id'] = array(
                'eq',
                $id
            );
            $fckw['auth_status'] = array(
                'eq',
                0
            );
            $fckw['is_boss'] = array(
                'neq',
                2
            );
            $fckw['card_img4'] = array(
                'EXP',
                ' is null '
            );
            // 未实名
            $count2 = M('fck')->where($fckw)->count();
            
            $data['no_active_count'] = $count2;
            $data['active_count'] = $count1;
            
            $data['current_count'] = count($list);
            $data['count'] = count($list);
            $data['data'] = $list;
            $data['user'] = $row;
            $data['msg'] = '获取成功';
            $data['status'] = 1;
            $data['url'] = 'agent.html';
            $this->ajaxReturn($data);
            exit();
        } else {
            $this->display();
        }
    }

    public function myTeam()
    {
        $is_mobile = I('post.is_mobile', 1);
        $keywords = I('post.keywords', '');
        
        $id = I('post.user_id', 1);
        IF ($is_mobile != 1) {
            $row = M('fck')->where(array(
                'id' => $id
            ))->find();
            $rank = $this->_post('rank');
            if (! empty($rank)) {
                $fckw['re_level'] = array(
                    'eq',
                    $row['re_level'] + $rank
                );
                $this->assign('rank', $rank);
            }
            // $fckw['re_path']=array('like','%,'.$id.',%');
            // $fckw['re_level']=array('lt',$row['re_level']+4);
            $fckw['re_id'] = array(
                'eq',
                $id
            );
            $fckw['is_pay'] = array(
                'gt',
                0
            );
            
            import("@.ORG.Page"); // 导入分页类
            $count = M('fck')->where($fckw)->count(); // 总页数
            
            $Page = new Page($count, C('ONE_PAGE_RE'));
            $this->assign('page', $Page->show());
            $p = $this->_get('p', true, '1');
            $list = M('fck')->where($fckw)
                ->page($p . ',' . C('ONE_PAGE_RE'))
                ->order('pdt asc')
                ->select();
            $this->assign('list', $list);
            $this->display();
        }
        
        IF ($is_mobile == 1) {
            $page_index = I('post.page_index', 1);
            $page_size = I('post.page_size', 1000);
            $is_pay = I('post.is_pay', 0);
            $id = I('post.user_id', 1);
            $row = M('fck')->where(array(
                'id' => $id
            ))->find();
            $str = '';
            IF (! EMPTY($keywords)) {
                $str = ' AND t.user_id like "%' . $keywords . '%"';
            }
            $str1 = ' AND is_pay=' . $is_pay;
            // if ($row['u_level'] == 2) {
            // $str1 = ' AND t.re_level>=' . $row['re_level'];
            // }
            // if ($row['u_level'] == 1) {
            // $str1 = ' AND t.re_level=' . ($row['re_level'] + 1);
            // }
            
            $list1 = M('fck')->alias('t')
                ->where('     t.re_path  like "%,' . $id . ',%"     ' . $str . $str1)
                ->field('   t.id,t.user_id,t.is_pay,t.u_level,t.nickname,t.user_name,FROM_UNIXTIME(t.pdt, "%Y-%m-%d") AS pdt,t.agent_use,t.agent_kt,t.avatar')
                ->order(' t.re_level asc  ')
                ->group(' t.id   ')
                ->page($page_index . ',' . $page_size)
                ->select();
            $profit_list = ARRAY();
            for ($x = 1; $x < 11; $x ++) {
                $profit_list[] = $x * 10;
            }
            if ($row['u_level'] == 2) {
                
                $profit_list = ARRAY();
                $profit_list[] = 100;
            }
            
            $fee = M('fee');
            $fee_rs = $fee->field('s3,recommend_gupiao,recommend_order,jjbb,s16,ssb_money,ssb_shouxu,s12,s9')->find(1);
            
            $trade_money = explode('|', $fee_rs['s9']);
            foreach ($list1 as $k => $v) {
                // $list1[$k]['percent'] = (($v['collect_money'] / $v['price']) * 100) . '%';
                // $list1[$k]['profit_list'] = $profit_list;
                // $list1[$k]['real_user_id'] = ($v['user_id']);
                // // $list1[$k]['user_id'] = mytelsubstr($v['user_id'], 4);
                // if (EMPTY($v['avatar'])) {
                // $list1[$k]['avatar'] = 'app/img/logo.png';
                // }
                // $shouquan_time_str = date('Y-m-d H:i:s', $v['confirm_time']);
                
                $money = $trade_money[$v['u_level'] - 1];
                
                $list1[$k]['money'] = $money;
                $list1[$k]['btn_status_str'] = '点击开通';
                if ($v['is_pay'] == 1) {
                    $list1[$k]['btn_status_str'] = '已开通';
                }
                // get_squan_status($v['status'], $list1[$k]);
            }
            // $shouquan = M('shouquan')->where('uid=' . $id)
            // ->order('id desc')
            // ->find();
            // if ($shouquan != null) {
            // get_squan_status($shouquan['status'], $shouquan);
            // }
            
            $data = array(
                'status' => 1,
                'list1' => $list1,
                'current_count' => count($list1),
                'list2' => $list2,
                'list3' => $list3,
                'sq_count' => count($shouquan),
                'shouquan' => $shouquan,
                'profit_list' => $profit_list,
                'info' => '获取成功'
            );
            $this->ajaxReturn($data);
        }
    }

    public function feedback_list()
    {
        
        // 列表过滤器，生成查询Map对象
        $fck = M('feedback');
        
        $is_mobile = I('is_mobile');
        $user_id = I('user_id');
        $map['id'] = array(
            'gt',
            0
        );
        
        if ($_POST['is_mobile'] == 1) {
            $map['user_id'] = array(
                'eq',
                $user_id
            );
        }
        
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
            ->order('reply_time asc,is_lock DESC,add_time desc,id desc')
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->select();
        
        foreach ($list as $key => $vo) {
            $user = M('fck')->where('id=' . $vo['user_id'])->find();
            
            $list[$key]['user_name'] = $user['user_id'];
            $list[$key]['add_time_str'] = date("Y-m-d H:i", $vo['add_time']);
            $list[$key]['reply_time_str'] = date("Y-m-d H:i", $vo['reply_time']);
            
            $list[$key]['content'] = trim($vo['content']);
        }
        
        $this->assign('list', $list);
        if ($_POST['is_mobile'] == 1) {
            $data['info'] = '获取成功！';
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $data['current_count'] = count($list);
            $this->ajaxReturn($data);
            exit();
        } else {
            $this->display();
        }
        return;
    }

    public function reply()
    {
        $feed = M('feedback');
        $is_mobile = I('is_mobile');
        $id = I('id');
        
        $reply_content = I('reply_content');
        
        if (Empty($reply_content)) {
            
            $this->ajaxError('对不起，请输入回复的内容！');
            exit();
        }
        $feedback = M('feedback')->where('id=' . $id)->find();
        
        $feedback['reply_content'] = $reply_content;
        $feedback['is_lock'] = 0;
        $feedback['reply_time'] = time();
        $feed->where('id=' . $id)->save($feedback);
        $data['info'] = '留言回复成功！';
        $data['msg'] = '留言回复成功！';
        $data['status'] = 1;
        $data['url'] = 'feedback.html';
        $this->ajaxReturn($data);
        exit();
    }

    public function feedback()
    {
        $is_mobile = I('is_mobile');
        $title = I('txtTitle');
        $content = I('txtContent');
        $user_id = I('user_id');
        if (Empty($title)) {
            
            $this->ajaxError('对不起，请输入留言的标题！');
            exit();
        }
        if (Empty($content)) {
            
            $this->ajaxError('对不起，请输入留言的内容！');
            exit();
        }
        $model = ARRAY();
        $model['title'] = $title;
        $model['content'] = $content;
        $model['user_id'] = $user_id;
        $model['add_time'] = time();
        $model['is_lock'] = 1;
        $feedback = M('feedback');
        $feedback->add($model);
        $data['info'] = '恭喜您，留言提交成功！';
        $data['msg'] = '恭喜您，留言提交成功！';
        $data['status'] = 1;
        $data['url'] = 'feedback.html';
        $this->ajaxReturn($data);
        exit();
    }

    public function init_out_data()
    {
        $fee = M('fee');
        $fee_rs = $fee->field('jicha,jjbb,s16,money_count,s4')->find(1);
        $out_list = M('user_terminal_out')->where(' sn like "%,%" ')->select();
        
        $user_terminal = M('user_terminal');
        $pid = 0;
        foreach ($out_list as $k => $v) {
            $sn_list = M('user_terminal')->where('sn in (' . $v['sn'] . ')')
                ->field('sn')
                ->select();
            
            foreach ($sn_list as $i => $item) {
                
                $model = ARRAY();
                $model['sn'] = $item['sn'];
                $model['in_uid'] = $v['in_uid'];
                $model['uid'] = $v['uid'];
                $model['add_time'] = $v['add_time'];
                $model['check_time'] = $v['check_time'];
                $model['status'] = 0;
                
                $model['pid'] = $pid;
                $feedback = M('user_terminal_out');
                $PTid = $feedback->add($model);
                if ($pid == 0) {
                    $pid = $PTid;
                }
                
                $user_terminal_back = M('user_terminal_back')->where('sn ="' . $item['sn'] . '"')->find();
                if ($user_terminal_back != NULL) {
                    M('user_terminal_back')->where('id=' . $user_terminal_back['id'] . '')->setField('out_id', $PTid);
                }
                
                // $user_terminal->where(array(
                // 'sn' => $v['sn']
                // ))->setField('uid', $in_uid);
            }
            M('user_terminal_out')->where('id=' . $v['id'])->delete();
        }
        
        // set_out_terminal_ids($in_uid);
        // set_out_terminal_ids($user_id);
        
        $fck = M('user_terminal_out');
        // $rs = $fck->find($PTid);
        // if ($rs) {
        
        // $where['id'] = $PTid;
        // $fck->where($where)->setField('status', 0);
        // ;
        // $fck->where($where)->setField('check_time', time());
        // ;
        
        // $sns = explode(',', $rs['sn']);
        
        // foreach ($sns as $i => $value) {
        
        // ;
        // }
        
        // set_out_terminal_ids($in_uid);
        
        // set_terminal_sns($in_uid);
        
        // set_out_terminal_ids($user_id);
        
        // set_terminal_sns($user_id);
        // $this->success('审核成功!');
        // }
        
        $data['info'] = '初始化成功';
        $data['msg'] = '初始化成功';
        $data['status'] = 1;
        $this->success($data['info']);
        exit();
    }

    public function add_user_terminal_out()
    {
        $is_mobile = I('is_mobile');
        $sn = I('sn');
        
        // $var=explode(",",$sn);
        
        $sn = "'" . implode("','", explode(",", str_replace("\r", '', $sn))) . "'";
        
        // $sn=implode(",", $var);
        
        $in_uid = I('in_uid');
        $user_id = I('user_id');
        
        if (Empty($sn)) {
            
            $this->ajaxError('对不起，请选择机器！');
            exit();
        }
        if ($in_uid == 0) {
            
            $this->ajaxError('对不起，请选择盟友！');
            exit();
        }
        $fee = M('fee');
        $fee_rs = $fee->field('jicha,jjbb,s16,money_count,s4')->find(1);
        $sn_list = M('user_terminal')->field('sn')
            ->where('sn in (' . $sn . ')')
            ->select();
        // $this->ajaxError($sn);
        // exit();
        $user_terminal = M('user_terminal');
        $pid = 0;
        foreach ($sn_list as $k => $v) {
            $count = M('user_terminal_out')->where('sn in (' . $v['sn'] . ')')->count();
            if ($count >= $fee_rs['money_count']) {
                $this->ajaxError('对不起，' . $v['sn'] . '下发次数超过限额,请直接购买！');
                exit();
            }
            
            $shop_count = M('seller')->where(array(
                'sn' => $v['sn']
            ))->count();
            IF ($shop_count > 0) {
                
                $this->ajaxError('对不起，' . $v['sn'] . '已绑定商户,不能下发');
                exit();
            }
            $model = ARRAY();
            $model['sn'] = $v['sn'];
            $model['in_uid'] = $in_uid;
            $model['uid'] = $user_id;
            $model['add_time'] = time();
            $model['check_time'] = time();
            $model['status'] = 0;
            
            $model['pid'] = $pid;
            $feedback = M('user_terminal_out');
            $PTid = $feedback->add($model);
            if ($pid == 0) {
                $pid = $PTid;
            }
            $user_terminal->where(array(
                'sn' => $v['sn']
            ))->setField('uid', $in_uid);
        }
        
        // set_out_terminal_ids($in_uid);
        // set_out_terminal_ids($user_id);
        
        $fck = M('user_terminal_out');
        // $rs = $fck->find($PTid);
        // if ($rs) {
        
        // $where['id'] = $PTid;
        // $fck->where($where)->setField('status', 0);
        // ;
        // $fck->where($where)->setField('check_time', time());
        // ;
        
        // $sns = explode(',', $rs['sn']);
        
        // foreach ($sns as $i => $value) {
        
        // ;
        // }
        
        set_out_terminal_ids($in_uid);
        
        set_terminal_sns($in_uid);
        
        set_out_terminal_ids($user_id);
        
        set_terminal_sns($user_id);
        // $this->success('审核成功!');
        // }
        
        $data['info'] = '下发成功';
        $data['msg'] = '下发成功';
        $data['status'] = 1;
        $this->ajaxReturn($data);
        exit();
    }

    public function back_user_terminal()
    {
        $is_mobile = I('is_mobile');
        $sn = I('sn');
        $id = I('id');
        $out_id = I('out_id');
        $user_id = I('user_id');
        $user_terminal = M('user_terminal');
        
        $terminal = $user_terminal->where('id=' . $id)->find();
        $terminal_out = M('user_terminal_out')->where('id=' . $id)->find();
        
        $user_id = I('user_id');
        if ($terminal == NULL) {
            
            $this->ajaxError('对不起，机器有误！');
            exit();
        }
        
        if ($terminal['seller_id'] > 0) {
            
            $this->ajaxError('对不起，此机器已绑定商户,不能回收！');
            exit();
        }
        
        $count = M('user_terminal_out')->alias('t')
            ->where('   FIND_IN_SET(t.sn,' . $terminal['sn'] . ')  AND not exists(select A.ID FROM XT_user_terminal_back A WHERE A.out_id=T.id)   and t.id>' . $out_id . '  ')
            ->count();
        
        if ($count > 0) {
            $this->ajaxError('对不起，此机器已被下发,不能回收！');
            exit();
        }
        
        $count = M('user_terminal_back')->where(array(
            'sn' => $terminal['sn'],
            'status' => 1,
            'type' => 1
        ))->count();
        if ($count > 0) {
            
            $this->ajaxError('对不起，正在审核！');
            exit();
        }
        
        set_out_terminal_ids($user_id);
        
        set_terminal_sns($user_id);
        
        $model = ARRAY();
        $model['sn'] = $sn;
        $model['in_uid'] = $user_id;
        $model['out_id'] = $out_id;
        $model['uid'] = $terminal['uid'];
        $model['type'] = 1;
        $model['add_time'] = time();
        $model['status'] = 1;
        $feedback = M('user_terminal_back');
        $PTid = $feedback->add($model);
        
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
        }
        
        $data['info'] = '回收成功！';
        $data['msg'] = '回收成功！';
        $data['status'] = 1;
        $this->ajaxReturn($data);
        exit();
    }

    // 用户账单
    public function user_day_money_list()
    {
        $is_mobile = I('is_mobile', 1);
        $user_id = I('user_id', 10943);
        $time = I('time', '2018-12');
        $type = I('type');
        $time_str = strtotime($time);
        $j = date("t"); // 获取当前月份天数
        $start_time = strtotime(date('Y-m-01', $time_str));
        $list = array();
        $month_margin = 0;
        $month_num = 6;
        $min_trade_time = M('trade_orders')->field(' trade_time ')
            ->where(" user_id=" . $user_id)
            ->order(' trade_time asc ')
            ->find();
        $timediff = timediff($start_time, $time_str);
        
        $margin = $timediff['day'];
        
        $array = array();
        $all_money = 0;
        for ($i = 0; $i <= $margin; $i ++) {
            
            $time_str = date("Y-m-d", strtotime($time . " -" . $i . " day"));
            $day_str = date("d", strtotime($time . " -" . $i . " day"));
            $item = array();
            $day = array();
            // $time_str = date('Y-m-d', $t);
            
            // $month = ($i + 1);
            // IF ($month < 10) {
            // $month = '0' . $month;
            // }
            
            $day['time'] = $time_str;
            
            $array1 = init_user_money($user_id, $time_str);
            // $str = 'FROM_UNIXTIME(pdt,"%Y-%m-%d")="' . $day['time'] . '" AND uid=' . $user_id;
            // // $list = M('history')->field('*,FROM_UNIXTIME(pdt,"%Y-%m-%d %H:%i:%S") AS add_time_str')
            // // ->where($str)
            // // ->select();
            // $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m-%d")="' . $day['time'] . '" AND t.uid=' . $user_id . ' AND t.bz LIKE "%分润-%" ';
            
            // $fenrun_sum = M('history')->alias('t')
            // ->join("xt_trade_orders AS g ON g.id = t.project_id", 'LEFT')
            // ->where($str)
            // ->sum('T.epoints');
            // if ($fenrun_sum == NULL) {
            // $fenrun_sum = 0;
            // }
            // $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m-%d")="' . $day['time'] . '" AND t.uid=' . $user_id . ' AND t.bz LIKE "%分润扣%" ';
            
            // $shui_sum = M('history')->alias('t')
            // ->join("xt_trade_orders AS g ON g.order_no = t.order_no", 'LEFT')
            // ->where($str)
            // ->sum('T.epoints');
            // if ($shui_sum == NULL) {
            // $shui_sum = 0;
            // }
            // // 返现总计
            // $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m-%d")="' . $day['time'] . '" AND t.uid=' . $user_id . ' and t.epoints>0 AND t.bz LIKE "%机器返现%" ';
            
            // $fanxian_sum = M('history')->alias('t')
            // ->join("xt_trade_orders AS g ON g.id = t.project_id", 'LEFT')
            // ->where($str)
            // ->sum('t.epoints');
            // if ($fanxian_sum == NULL) {
            // $fanxian_sum = 0;
            // }
            // // 所得税总计
            // $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m-%d")="' . $day['time'] . '" AND t.uid=' . $user_id . ' and t.epoints<0 AND t.bz LIKE "%机器返现%" ';
            
            // $tax_sum = M('history')->alias('t')
            // ->join("xt_trade_orders AS g ON g.id = t.project_id", 'LEFT')
            // ->where($str)
            // ->sum('t.epoints');
            // if ($tax_sum == NULL) {
            // $tax_sum = 0;
            // }
            
            // // 提现总计
            // $str = ' FROM_UNIXTIME(rdt,"%Y-%m-%d")="' . $day['time'] . '" AND uid=' . $user_id . ' and is_pay!=2 ';
            
            // $tx_sum = M('tiqu')->where($str)->sum('money');
            // if ($tx_sum == NULL) {
            // $tx_sum = 0;
            // }
            
            // // 充值总计
            // $str = 'FROM_UNIXTIME(pdt,"%Y-%m-%d")="' . $day['time'] . '" AND uid=' . $user_id . ' AND bz LIKE "%' . C('cz_txt') . '%" ';
            
            // $cz_sum = M('history')->where($str)->sum('epoints');
            // if ($cz_sum == NULL) {
            // $cz_sum = 0;
            // }
            $tax_sum = $array1['tax_sum'];
            $fanxian_sum = $array1['fanxian_sum'];
            $fenrun_sum = $array1['fenrun_sum'];
            $tx_sum = $array1['tx_sum'];
            $cz_sum = $array1['cz_sum'];
            $shui_sum = $array1['shui_sum'];
            $all_money = $all_money + $tax_sum + $fanxian_sum + $fenrun_sum + $tx_sum + $cz_sum;
            // $time_str = date('Y年m', $t);
            $day['time_str'] = date('m', $t) . '月';
            $day['year'] = date('Y', $t);
            $day['day'] = $day_str;
            $day['time'] = $time_str;
            
            // $day['sum'] = $sum;
            $day['tx_sum'] = $tx_sum;
            $day['tax_sum'] = $tax_sum;
            $day['fanxian_sum'] = $fanxian_sum;
            $day['fenrun_sum'] = $fenrun_sum;
            $day['shui_sum'] = $shui_sum;
            $day['cz_sum'] = $cz_sum;
            $money = 0;
            $day['money'] = $money;
            $array[] = $day;
        }
        
        $user = M('fck')->field('round(agent_use,2) as agent_use')
            ->where(array(
            'id' => $user_id
        ))
            ->find();
        
        $time = I('time', '2018-12');
        
        $time_str = strtotime($time);
        $time_str = date("Y-m", $time_str);
        $array1 = init_user_month_money($user_id, $time_str);
        $tax_sum = $array1['tax_sum'];
        $fanxian_sum = $array1['fanxian_sum'];
        $fenrun_sum = $array1['fenrun_sum'];
        $shui_sum = $array1['shui_sum'];
        $pos_fx_sum = $array1['pos_fx_sum'];
        $pos_fx_shui_sum = $array1['pos_fx_shui_sum'];
        $data['info'] = '获取成功！';
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = ($array);
        $data['pos_fx_shui_sum'] = $pos_fx_shui_sum;
        $data['pos_fx_sum'] = $pos_fx_sum;
        $data['shui_sum'] = $shui_sum;
        $data['money1'] = $fenrun_sum;
        $data['money2'] = $tx_sum;
        $data['all_money'] = $all_money;
        $data['user'] = $user;
        $data['year'] = date('Y年');
        $this->ajaxReturn($data);
        exit();
    }

    // 收支明细
    public function user_money_list()
    {
        $is_mobile = I('is_mobile', 1);
        $user_id = I('user_id', 10956);
        $type = I('type');
        
        $j = date("t"); // 获取当前月份天数
        $start_time = strtotime(date('Y-m-01'));
        $list = array();
        $month_margin = 0;
        $month_num = 6;
        $min_trade_time = M('trade_orders')->field(' trade_time ')
            ->where(" user_id=" . $user_id)
            ->order(' trade_time asc ')
            ->find();
        IF ($min_trade_time == NULL) {
            $month_margin = $month_num + $month_margin;
        } else {
            $month_margin = get_month_diff($min_trade_time['trade_time'], $start_time);
            $month_margin = $month_num + $month_margin + $month_num;
        }
        
        $array = array();
        $all_money = 0;
        for ($i = 0; $i < $month_margin; $i ++) {
            
            $t = yzy_date(date('Y-m-d'), - $i);
            $t = strtotime($t);
            
            $item = array();
            $time_str = date('Y-m', $t);
            
            // $month = ($i + 1);
            // IF ($month < 10) {
            // $month = '0' . $month;
            // }
            
            $day['time'] = $time_str;
            $str = 'FROM_UNIXTIME(pdt,"%Y-%m")="' . $day['time'] . '" AND uid=' . $user_id;
            // $list = M('history')->field('*,FROM_UNIXTIME(pdt,"%Y-%m-%d %H:%i:%S") AS add_time_str')
            // ->where($str)
            // ->select();
            $str = 'FROM_UNIXTIME(pdt,"%Y-%m")="' . $day['time'] . '" AND uid=' . $user_id . '   AND bz LIKE "%分润-%"  ';
            $list = M('history')->field('*,sum(epoints) as all_money,FROM_UNIXTIME(pdt,"%Y-%m-%d %H:%i:%S") AS add_time_str')
                ->where($str)
                ->order('id desc ')
                ->group(' FROM_UNIXTIME(pdt,"%Y-%m-%d")')
                ->select();
            
            foreach ($list as $ii => $goods) {
                $list[$ii]['bz'] = '分润';
            }
            
            $fenrun_sum = M('history')->where($str)
                ->group(' FROM_UNIXTIME(pdt,"%Y-%m-%d") ')
                ->sum('epoints');
            if ($fenrun_sum == NULL) {
                $fenrun_sum = 0;
            }
            
            // 返现总计
            $str = 'FROM_UNIXTIME(pdt,"%Y-%m")="' . $day['time'] . '" AND uid=' . $user_id . '   AND bz LIKE "%返现%"  ';
            $list1 = M('history')->field('*,sum(epoints) as all_money,FROM_UNIXTIME(pdt,"%Y-%m-%d %H:%i:%S") AS add_time_str')
                ->where($str)
                ->order('id desc ')
                ->group(' FROM_UNIXTIME(pdt,"%Y-%m-%d") ')
                ->select();
            foreach ($list1 as $ii => $goods) {
                $list1[$ii]['bz'] = '返现';
            }
            $fanxian_sum = M('history')->where($str)
                ->group(' FROM_UNIXTIME(pdt,"%Y-%m-%d") ')
                ->sum('epoints');
            if ($fanxian_sum == NULL) {
                $fanxian_sum = 0;
            }
            // 所得税总计
            $str = 'FROM_UNIXTIME(pdt,"%Y-%m")="' . $day['time'] . '" AND uid=' . $user_id . '   AND bz LIKE "%' . C('tax_txt') . '%"  ';
            $list2 = M('history')->field('*,sum(epoints) as all_money,FROM_UNIXTIME(pdt,"%Y-%m-%d %H:%i:%S") AS add_time_str')
                ->where($str)
                ->order('id desc ')
                ->group('  FROM_UNIXTIME(pdt,"%Y-%m-%d") ')
                ->select();
            foreach ($list2 as $ii => $goods) {
                $list2[$ii]['bz'] = C('tax_txt');
            }
            $tax_sum = M('history')->where($str)
                ->group(' FROM_UNIXTIME(pdt,"%Y-%m-%d") ')
                ->sum('epoints');
            if ($tax_sum == NULL) {
                $tax_sum = 0;
            }
            
            // 提现总计
            $str = 'FROM_UNIXTIME(pdt,"%Y-%m")="' . $day['time'] . '" AND uid=' . $user_id . '   AND bz LIKE "%' . C('tx_txt') . '%"  ';
            $list3 = M('history')->field('*,sum(epoints) as all_money,FROM_UNIXTIME(pdt,"%Y-%m-%d %H:%i:%S") AS add_time_str')
                ->where($str)
                ->order('id desc ')
                ->group('  FROM_UNIXTIME(pdt,"%Y-%m-%d") ')
                ->select();
            foreach ($list3 as $ii => $goods) {
                $list3[$ii]['bz'] = C('tx_txt');
            }
            $tx_sum = M('history')->where($str)
                ->group(' FROM_UNIXTIME(pdt,"%Y-%m-%d") ')
                ->sum('epoints');
            if ($tx_sum == NULL) {
                $tx_sum = 0;
            }
            
            // 充值总计
            $str = 'FROM_UNIXTIME(pdt,"%Y-%m")="' . $day['time'] . '" AND uid=' . $user_id . '   AND bz LIKE "%' . C('cz_txt') . '%"  ';
            $list4 = M('history')->field('*,sum(epoints) as all_money,FROM_UNIXTIME(pdt,"%Y-%m-%d %H:%i:%S") AS add_time_str')
                ->where($str)
                ->order('id desc ')
                ->group('  FROM_UNIXTIME(pdt,"%Y-%m-%d") ')
                ->select();
            foreach ($list4 as $ii => $goods) {
                $list4[$ii]['bz'] = C('cz_txt');
            }
            $cz_sum = M('history')->where($str)
                ->group(' FROM_UNIXTIME(pdt,"%Y-%m-%d") ')
                ->sum('epoints');
            if ($cz_sum == NULL) {
                $cz_sum = 0;
            }
            
            if (count($list) == 0) {
                $list = array();
            }
            
            if (count($list1) == 0) {
                $list1 = array();
            }
            $list1 = array_merge($list, $list1);
            
            if (count($list2) == 0) {
                $list2 = array();
            }
            $list2 = array_merge($list1, $list2);
            // foreach ($list2 as $list4) {
            // $sort[] = $list4["id"];
            // }
            
            if (count($list3) == 0) {
                $list3 = array();
            }
            $list3 = array_merge($list2, $list3);
            
            if (count($list4) == 0) {
                $list4 = array();
            }
            $list4 = array_merge($list3, $list4);
            
            foreach ($list4 as $vo) {
                $sort[] = $vo["id"];
            }
            array_multisort($sort, SORT_DESC, $list4);
            
            if (count($list4) == 0) {
                $list4 = null;
            }
            
            $all_money = $all_money + $tax_sum + $fanxian_sum + $fenrun_sum + $tx_sum + $cz_sum;
            $time_str = date('Y年m', $t);
            $day['time_str'] = date('m', $t) . '月';
            $day['year'] = date('Y', $t);
            $day['time'] = $time_str;
            $day['list'] = $list4;
            $day['sum'] = $sum;
            $array[] = $day;
        }
        
        $user = M('fck')->field('round(agent_use,2) as agent_use')
            ->where(array(
            'id' => $user_id
        ))
            ->find();
        
        $data['info'] = '获取成功！';
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = ($array);
        $data['all_money'] = $all_money;
        $data['user'] = $user;
        $data['year'] = date('Y年');
        $this->ajaxReturn($data);
        exit();
    }

    // 收益明细
    public function user_income()
    {
        $is_mobile = I('is_mobile');
        $user_id = I('user_id', 10942);
        $type = I('type', 2);
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 10000);
        
        $j = date("t"); // 获取当前月份天数
        $start_time = strtotime(date('Y-m-01'));
        $list = array();
        if ($type == 1) {
            $array = array();
            $all_money = 0;
            for ($i = 0; $i < $j; $i ++) {
                $day['time'] = date('Y-m-d', $start_time + $i * 86400);
                $str = 'FROM_UNIXTIME(trade_time,"%Y-%m-%d")="' . $day['time'] . '" and epoints>0 ';
                $list = M('trade_orders')->alias('t')
                    ->join("xt_history AS H ON   H.project_id = t.id ", 'LEFT')
                    ->where($str)
                    ->select();
                $sum = 0;
                foreach ($list as $key => $value) {
                    
                    $sum = $sum + $value['epoints'];
                }
                
                // $sum = M('trade_orders') ->alias('t')
                // ->join("xt_fck AS g ON g.id = t.user_id ", 'LEFT')->where($str)->sum('real_fen_money');
                // if ($sum == NULL) {
                // $sum = 0;
                // }
                
                $all_money = $all_money + $sum;
                // $day['time'] = date('m月', $start_time + $i * 86400);
                
                $day['list'] = $list;
                $day['sum'] = $sum;
                $array[] = $day;
            }
            $data['info'] = '获取成功！';
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $array;
            $data['all_money'] = $all_money;
            $data['month'] = date('Y年m月');
            $this->ajaxReturn($data);
            exit();
        }
        if ($type == 2) {
            $array = array();
            $all_money = 0;
            $map['_string'] = "  t.uid=" . $user_id . "    and  t.bz like '%分润-%'    ";
            
            $list = M('history')->alias('t')
                ->join("xt_trade_orders AS g ON   g.id = t.project_id  ", 'LEFT')
                ->join("xt_fck AS h ON   h.id = t.uid ", 'LEFT')
                ->where($map)
                ->field(' t.uid as user_id,sum(t.epoints) as sum,FROM_UNIXTIME(g.trade_time,"%Y-%m") AS month_str ')
                ->group('t.uid,FROM_UNIXTIME(g.trade_time,"%Y-%m")')
                ->select();
            for ($i = 0; $i < 20; $i ++) {
                // $month = ($i + 1);
                $t = yzy_date(date('Y-m-d'), - $i);
                $t = strtotime($t);
                // IF ($month < 10) {
                // $month = '0' . $month;
                // }
                $time_str = date('Y-m', $t);
                $month = date('m', $t);
                $year = date('Y', $t);
                $day['time'] = $time_str;
                
                // $str = ' FROM_UNIXTIME(trade_time,"%Y-%m")="' . $day['time'] . '" AND (money_user_id=' . $user_id . ' OR G.re_id=' . $user_id . ')';
                // $list = M('trade_orders')->alias('t')
                // ->field(' t.* ')
                // ->join("xt_fck AS g ON g.id = t.user_id ", 'LEFT')
                // ->where($map)
                // ->select();
                // $sum = M('trade_orders')->where($str)->sum('real_fen_money');
                // if ($sum == NULL) {
                // $sum = 0;
                // }
                $sum = 0;
                
                $array1 = array();
                $array1['sum'] = 0;
                foreach ($list as $key => $value) {
                    // IF ($value['user_id'] == $user_id) {
                    // $sum = $sum + $value['all_epoints'];
                    // } else {
                    // $sum = $sum + $value['all_epoints'];
                    // }
                    // $array['user_id']=0;
                    // $array['all_epoints']=0;
                    IF ($value['month_str'] == $time_str) {
                        $array1 = $value;
                    }
                }
                
                $all_money = $all_money + $array1['sum'];
                $day['time_str'] = $month . '月';
                $day['year'] = $year;
                $day['month'] = $month;
                $day['list'] = $array1;
                $day['sum'] = $array1['sum'];
                $array[] = $day;
            }
            $data['info'] = '获取成功！';
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = ($array);
            $data['current_count'] = count($array);
            $data['all_money'] = $all_money;
            $data['year'] = date('Y年');
            $this->ajaxReturn($data);
            exit();
        }
    }

    public function user_achievement()
    {
        $is_mobile = I('is_mobile');
        $user_id = I('user_id');
        
        // file_put_contents('user_achievement.txt', $user_id, FILE_APPEND);
        
        $type = I('type');
        
        $j = date("t"); // 获取当前月份天数
        $start_time = strtotime(date('Y-m-01'));
        $list = array();
        
        $array = array();
        $all_money = 0;
        $arr = array();
        
        $month_num = 6;
        $month_margin = 0;
        
        $min_trade_time = M('trade_orders')->field(' trade_time ')
            ->where(" user_id=" . $user_id)
            ->order(' trade_time asc ')
            ->find();
        IF ($min_trade_time == NULL) {
            $month_margin = $month_num + $month_margin;
        } else {
            $month_margin = get_month_diff($min_trade_time['trade_time'], $start_time);
            $month_margin = $month_num + $month_margin + $month_num;
        }
        
        for ($i = 0; $i <= $month_margin; ++ $i) {
            
            $t = yzy_date(date('Y-m-d'), - $i);
            $t = strtotime($t);
            $item = array();
            $begintime_str = date('Y-m-01', $t);
            $begintime = strtotime($begintime_str);
            $endtime_str = date('Y-m-d', strtotime("$begintime_str +1 month -1 day"));
            $endtime = strtotime($endtime_str);
            
            $item = M('user_detail')->where(" uid=" . $user_id . " and  begintime=" . $begintime . " and  endtime=" . $endtime)->find();
            if ($item == NULL) {
                $item['month_user_count'] = 0;
                $item['month_team_user_count'] = 0;
                $item['month_shop_count'] = 0;
                $item['month_team_shop_count'] = 0;
                $item['month_shop_trade_money'] = 0;
                $item['month_team_trade_money'] = 0;
            }
            $item['time'] = explode('/', date('Y-m-01', $t) . '/' . date('Y-m-', $t) . date('t', $t));
            $first_day = date('Y-m-01', $t);
            $first_day_str = strtotime($first_day);
            $month_str = date("Y-m", strtotime($first_day));
            $month = date("m", strtotime($first_day));
            $item['month'] = $month;
            
            $year = date("Y", strtotime($first_day));
            $item['year'] = $year;
            $arr[] = $item;
        }
        
        $user = M('fck')->where('id=' . $user_id)->find();
        $team_trade_money = get_all_trade_money($user['id']);
        // 获取直营交易总金额
        $user_trade_money = get_trade_money($user['id']);
        $user['team_trade_money'] = $team_trade_money;
        $user['user_trade_money'] = $user_trade_money;
        
        $data['info'] = '获取成功！';
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $arr;
        $data['user'] = $user;
        $data['all_money'] = $all_money;
        $data['month'] = date('Y年m月');
        $this->ajaxReturn($data);
        exit();
    }

    public function get_zhihang()
    {
        $is_mobile = $_POST['is_mobile'];
        $bank = $_POST['bank'];
        $province = $_POST['province'];
        $city = $_POST['city'];
        $area = $_POST['area'];
        $keywords = $_POST['keywords'];
        $map = array();
        $map['bank_name'] = ARRAY(
            'like',
            '%' . $bank . '%'
        );
        if (strpos($bank, '信用社') !== false) {
            
            $map['bank_name'] = ARRAY(
                'like',
                '%信用社%'
            );
        }
        if (strpos($province, '上海') !== false || strpos($province, '北京') !== false || strpos($province, '天津') !== false || strpos($province, '重庆') !== false) {
            
            $map['province'] = ARRAY(
                'like',
                '%' . $province . '%'
            );
            // $map['fh'] = ARRAY(
            // 'like',
            // '%' . $city . '%'
            // );
        } else {
            $map['province'] = ARRAY(
                'like',
                '%' . $province . '%'
            );
            $map['city'] = ARRAY(
                'like',
                '%' . $city . '%'
            );
        }
        file_put_contents("sub_branch_name.txt", $keywords);
        IF (! EMPTY($keywords) && $keywords != 'null') {
            
            $map['sub_branch_name'] = ARRAY(
                'like',
                '%' . $keywords . '%'
            );
        }
        
        $list = M('banks')->field('sub_branch_name as fh')
            ->where($map)
            ->select();
        $data['info'] = '获取成功！';
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $list;
        $this->ajaxReturn($data);
        exit();
    }

    function get_trade_rank()
    {
        $is_mobile = $_POST['is_mobile'];
        $trade_type = I('trade_type', 0);
        IF ($trade_type == 0) {
            $list = M('fck')->field('month_shop_trade_money as team_trade_money,user_name,user_id,name,avatar')
                ->where(' id!=1 and user_name is not null  ')
                ->order('month_shop_trade_money desc')
                ->limit(0, 20)
                ->select();
            
            foreach ($list as $key => $row) {
                if (EMPTY($row['avatar'])) {
                    $list[$key]['avatar'] = 'app/img/logo.png';
                } else {
                    $list[$key]['avatar'] = $row['avatar'];
                }
                
                $list[$key]['user_name'] = mytelsubstr($row['user_name'], 1);
            }
        }
        IF ($trade_type == 1) {
            $list = M('fck')->field('month_shop_trade2_money as team_trade_money,user_name,user_id,name,avatar')
                ->where(' id!=1 and user_name is not null  ')
                ->order('month_shop_trade2_money desc')
                ->limit(0, 20)
                ->select();
            
            foreach ($list as $key => $row) {
                if (EMPTY($row['avatar'])) {
                    $list[$key]['avatar'] = 'app/img/logo.png';
                } else {
                    $list[$key]['avatar'] = $row['avatar'];
                }
                
                $list[$key]['user_name'] = mytelsubstr($row['user_name'], 1);
            }
        }
        $data['info'] = '获取成功！';
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $list;
        $this->ajaxReturn($data);
        exit();
    }

    function get_terminal_rank()
    {
        $is_mobile = $_POST['is_mobile'];
        
        $list = M('fck')->alias('t')
            ->field('t.user_name,t.user_id,t.name,t.avatar,month_terminal_active_count AS terminal_count')
            ->where(' T.id!=1       ')
            ->order(' month_terminal_active_count desc')
            ->limit(0, 20)
            ->select();
        
        foreach ($list as $key => $row) {
            if (EMPTY($row['avatar'])) {
                $list[$key]['avatar'] = 'app/img/logo.png';
            } else {
                $list[$key]['avatar'] = $row['avatar'];
            }
            $list[$key]['user_name'] = mytelsubstr($row['user_name'], 1);
        }
        
        $data['info'] = '获取成功！';
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $list;
        $this->ajaxReturn($data);
        exit();
    }

    public function usercode_list()
    {
        
        // 列表过滤器，生成查询Map对象
        $fck = M('user_code');
        
        $is_mobile = I('is_mobile');
        $user_id = I('user_id');
        $map['id'] = array(
            'gt',
            0
        );
        
        if ($_POST['is_mobile'] == 1) {
            $map['user_id'] = array(
                'eq',
                $user_id
            );
        }
        
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
            ->order('add_time desc,id desc')
            ->page($p . ',' . C('ONE_PAGE_RE'))
            ->select();
        
        foreach ($list as $key => $vo) {
            // $user = M('fck')->where('id=' . $vo['user_id'])->find();
            
            // $list[$key]['user_name'] = $user['user_id'];
            $list[$key]['add_time_str'] = date("Y-m-d H:i", $vo['add_time']);
            $list[$key]['reply_time_str'] = date("Y-m-d H:i", $vo['reply_time']);
            
            if ($vo['type'] == 'logincode') {
                $list[$key]['type_str'] = '登陆验证码';
            }
            if ($vo['type'] == 'usercode') {
                $list[$key]['type_str'] = '注册验证码';
            }
            if ($vo['type'] == 'applycode') {
                $list[$key]['type_str'] = '变更验证码';
            }
        }
        
        $this->assign('list', $list);
        if ($_POST['is_mobile'] == 1) {
            $data['info'] = '获取成功！';
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $this->ajaxReturn($data);
            exit();
        } else {
            $this->display();
        }
        return;
    }

    public function user_chat_list()
    {
        
        // 列表过滤器，生成查询Map对象
        $user_message = M('user_message');
        
        $is_mobile = I('is_mobile');
        $user_id = I('user_id');
        $page_index = I('page_index');
        $page_num = I('page_num');
        $map['t.id'] = array(
            'gt',
            0
        );
        
        if ($_POST['is_mobile'] == 1) {
            $map['_string'] = "   (t.user_id=" . $user_id . " or t.chat_user_id='" . $user_id . "')";
        }
        
        $field = 'max(t.id) id ,t.*,g.user_name,g.avatar,max(T.ID) AS max_id,sum(is_read) as no_read_count';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $user_message->alias('t')
            ->join("xt_fck AS g ON g.id = t.user_id ", 'LEFT')
            ->where($map)
            ->count(); // 总页数
        
        $list = $user_message->alias('t')
            ->join("xt_fck AS g ON g.id = t.user_id ", 'LEFT')
            ->where($map)
            ->field($field)
            ->group('t.pid')
            ->order('t.addtime desc,t.id desc')
            ->page($page_index . ',' . $page_num)
            ->select();
        
        foreach ($list as $key => $vo) {
            
            if (EMPTY($vo['avatar'])) {
                $list[$key]['avatar'] = 'app/img/logo.png';
            } else {
                $list[$key]['avatar'] = $vo['avatar'];
            }
            
            $where = array();
            $where['id'] = $list[$key]['max_id'];
            $new_msg = M('user_message')->where($where)
                ->order('id desc ')
                ->find();
            
            $list[$key]['new_msg'] = $new_msg['content'];
            
            if ($new_msg['type'] == 'sound') {
                $list[$key]['new_msg'] = '[语音消息]';
            }
            if ($new_msg['type'] == 'image') {
                $list[$key]['new_msg'] = '[图片]';
            }
            $list[$key]["new_time"] = time_str($new_msg['addtime']);
            
            // $user = M('fck')->where('id=' . $vo['user_id'])->find();
            
            // $list[$key]['user_name'] = $user['user_id'];
            // $list[$key]['add_time_str'] = date("Y-m-d H:i", $vo['add_time']);
            // $list[$key]['reply_time_str'] = date("Y-m-d H:i", $vo['reply_time']);
            
            // if ($vo['type'] == 'logincode') {
            // $list[$key]['type_str'] = '登陆验证码';
            // }
            // if ($vo['type'] == 'usercode') {
            // $list[$key]['type_str'] = '注册验证码';
            // }
            // if ($vo['type'] == 'applycode') {
            // $list[$key]['type_str'] = '变更验证码';
            // }
        }
        
        $this->assign('list', $list);
        if ($_POST['is_mobile'] == 1) {
            $data['info'] = '获取成功！';
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $data['current_count'] = count($list);
            $this->ajaxReturn($data);
            exit();
        } else {
            $this->display();
        }
        return;
    }

    public function send_msg()
    {
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        $user = M('fck')->where("id=" . $user_id . "")->find();
        
        $chat_user_id = I('post.chat_user_id');
        $trade_id = I('post.trade_id');
        $msg_id = I('post.msg_id');
        // $data = array(
        // 'status' => 0,
        
        // 'info' => $msg_id,
        // 'url' => U('Home/System/main')
        // );
        // $this->ajaxReturn($data);
        if ($user_id < $chat_user_id) {
            
            $msg_id = $user_id . '@' . $chat_user_id;
        }
        if ($user_id > $chat_user_id) {
            $msg_id = $chat_user_id . '@' . $user_id;
        }
        file_put_contents("msg_id.txt", $msg_id);
        
        $user_id = I('post.user_id');
        $content = I('post.content');
        $type = I('post.type');
        
        $xsj_log["real_content"] = $content;
        $xsj_log["chat_user_id"] = $chat_user_id;
        $xsj_log["user_id"] = $user_id;
        $xsj_log["type"] = $type;
        $xsj_log["trade_id"] = $trade_id;
        $xsj_log["msg_id"] = $msg_id;
        $xsj_log["is_read"] = 1;
        $xsj_log["addtime"] = time();
        $xsj_log["content"] = str_replace("&quot;", "", $content);
        $xsj_log["content"] = stripslashes($xsj_log["content"]);
        if ($type != 'sound' && $type != 'image') {
            $xsj_log["content"] = str_replace("g+c", "g c", urlencode($content));
            $xsj_log["content"] = str_replace("+s", " s", $xsj_log["content"]);
        }
        $msg = array();
        $msg['msg_id'] = $msg_id;
        $member = M("user_message")->where($msg)
            ->order('addtime asc')
            ->find();
        $pid = 0;
        if ($member != null) {
            $pid = $member['id'];
        }
        $xsj_log["pid"] = $pid;
        $xsj_re = M("user_message")->add($xsj_log);
        
        if ($member == null) {
            $xsj_re = M("user_message")->where('id=' . $xsj_re)->setField('pid', $xsj_re);
        }
        
        $member = M("user_message")->where($msg)
            ->order('addtime ASC')
            ->select();
        
        foreach ($member as $k => &$v) {
            $member[$k]["addtime_str"] = time_str($member[$k]['addtime']);
            $user = M('fck')->where('id=' . $v["user_id"])->find();
            
            if (EMPTY($user['nickname'])) {
                $member[$k]["nickname"] = $user['nickname'];
            } else {
                $member[$k]["nickname"] = $user['nickname'];
            }
            // if (empty($user['avatar'])) {
            // $member[$k]['avatar'] = __ROOT__ . '/Public/' . MODULE_NAME . '/images' . '/avatar.png';
            // } else {
            // $member[$k]['avatar'] = $user['avatar'];
            // }
            
            // $member[$k]['avatar'] = str_replace("../", "", $member[$k]['avatar']);
        }
        // $redis = new CacheRedis();
        // $result = $redis->set($msg_id . 'get_user_message', json_encode($member));
        $user_message = M("user_message")->where('id=' . $xsj_re)->find();
        $data = array();
        $data['type'] = "update_chat_list";
        $data['msg_id'] = $msg_id;
        $data['msg'] = $user_message;
        $data['uid'] = C('chat_id');
        
        push_msg($data, C('chat_id'));
        $data = array(
            'status' => 1,
            
            'info' => '发送成功',
            'url' => U('Home/System/main')
        );
        $this->ajaxReturn($data);
    }

    public function get_user_message_list()
    {
        $arrUser = array();
        $user_id = I('post.user_id');
        $chat_user_id = I('post.chat_user_id');
        $trade_detail_id = I('post.trade_detail_id');
        $msg_id = I('post.msg_id');
        $user_id = I('post.user_id');
        $is_read = I('post.is_read');
        $arrUser = M("user_message")->where(' msg_id="' . $msg_id . '"   ')
            ->order('addtime ASC')
            ->select();
        
        $is_set = 0;
        foreach ($arrUser as $k => $v) {
            $arrUser[$k]["addtime_str"] = time_str($arrUser[$k]['addtime']);
            $arrUser[$k]["sender"] = 'zs';
            if ($user_id == $v['user_id']) {
                $arrUser[$k]["sender"] = 'self';
            }
            
            if ($user_id == $v['user_id']) {
                M("user_message")->where('id=' . $v['id'])->setField('chat_is_read', 0);
            }
            if ($user_id == $v['chat_user_id']) {
                M("user_message")->where('id=' . $v['id'])->setField('is_read', 0);
            }
        }
        if ($arrUser == '') {
            $arrUser = array();
        }
        
        $this->ajaxReturn(array(
            'status' => 1,
            'info' => '获取成功',
            'data' => $arrUser
        ));
    }

    PUBLIC function profit_del()
    {
        $PTid = I('get.id');
        $upload_type = I('get.upload_type');
        $url = urldecode(I('get.url'));
        // 删除会员
        $fck = M('trade_orders');
        if ($upload_type == 'excel') {
            $fck = M('trade_orders_record');
        }
        $rs = $fck->where('id=' . $PTid)->find();
        if ($rs) {
            $whe['is_money'] = 1;
            $whe['order_no'] = $rs['order_no'];
            $rss = $fck->where($whe)->find();
            if ($rss) {
                $bUrl = __URL__ . '/test_excel';
                $this->error('此交易已经分润，不能删除！');
                exit();
            } else {
                $where['id'] = $PTid;
                
                $a = $fck->where($where)->delete();
                
                // $this->_box(1,'删除成功！',$bUrl,1);
                $this->success('删除成功!', $url);
            }
        }
    }

    public function user_list()
    {
        
        // 列表过滤器，生成查询Map对象
        $fck = M('fck');
        
        $is_mobile = I('is_mobile');
        $page_index = I('page_index');
        $page_num = I('page_num');
        $is_mobile = I('is_mobile');
        $user_id = I('user_id');
        $map['id'] = array(
            'gt',
            0
        );
        if ($is_mobile == 0) {
            $field = '*';
            // =====================分页开始==============================================
            import("@.ORG.Page"); // 导入分页类
            $count = $fck->where($map)->count(); // 总页数
            $Page = new Page($count, $page_num);
            // ===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show(); // 分页变量
            $this->assign('page', $show); // 分页变量输出到模板
            $p = $this->_get('p', true, '1');
            $list = $fck->where($map)
            ->field($field)
            ->order('re_nums desc')
            ->page($page_index . ',' . $page_num)
            ->select();
            
            foreach ($list as $key => $vo) {
                $user = M('fck')->where('id=' . $vo['user_id'])->find();
                
                // $list[$key]['user_name'] = $user['user_id'];
                $list[$key]['add_time_str'] = date("Y-m-d h:i", $vo['add_time']);
                $list[$key]['reply_time_str'] = date("Y-m-d h:i", $vo['reply_time']);
                
                $list[$key]['content'] = trim($vo['content']);
                $list[$key]['user_name'] = mytelsubstr($vo['user_name'], 1);
            }
            
            $this->assign('list', $list);
        }
        if ($is_mobile == 1) {
            $fee = M('fee');
            
            $fenhong_max_day = $fee->where('id=1')->getField('fenhong_max_day');
            $s18 = $fee->where('id=1')->getField('s18');
            if (! empty($s18)) {
                $s18 = explode(",", $s18);
            }
            if (! empty($fenhong_max_day)) {
                $fenhong_max_day = explode(",", $fenhong_max_day);
            }
            $recommend_list = array();
            
            foreach ($s18 as $key => $rs) {
                $item = array();
                $item['image_url'] = $rs;
                $item['txt'] = $fenhong_max_day[$key];
                ;
                $recommend_list[] = $item;
            }
            $fck = M('fck')->field('id, user_id, user_name,u_level,re_id,avatar,re_nums,weixinlogo,headimgurl')
            ->where('id=' . $user_id)
            ->find();
            
            $data['info'] = '获取成功！';
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['user'] = $fck;
            $data['tip'] = '未来已来,邀请你来';
            $data['data'] = $list;
            $data['recommend_images_list'] = $recommend_list;
            $data['current_count'] = count($list);
            $this->ajaxReturn($data);
            exit();
        } else {
            $this->display();
        }
        return;
    }

    PUBLIC function update_user_wx()
    {
        $user_id = I('user_id');
        // 删除会员
        $fck = M('fck');
        $fck->where('id=' . $user_id)->setField('weixinlogo', '');
        $fck->where('id=' . $user_id)->setField('gzh_openid', '');
        $fck->where('id=' . $user_id)->setField('gzh_unionid', '');
        $fck = M('fck')->where('id=' . $user_id)->find();
        
        $data['info'] = '更新成功,请进行授权！';
        $data['msg'] = '更新成功,请进行授权！';
        $data['status'] = 1;
        $data['user'] = $fck;
        $this->ajaxReturn($data);
        exit();
    }

    // 获取用户某一天的收益提现和返现记录
    PUBLIC function detail_userday_money_list()
    {
        $user_id = I('user_id', 10941);
        $time = I('time', '2018-12-25');
        
        // 分润数据
        $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m-%d")="' . $time . '" AND t.uid=' . $user_id . '   AND t.bz LIKE "%分润-%"  ';
        $sum_trade_list = M('history')->alias('t')
            ->join("xt_trade_orders AS g ON   g.id = t.project_id", 'LEFT')
            ->where($str)
            ->field('sum(g.trade_money) as trade_money,sum(t.epoints) as money,FROM_UNIXTIME(g.trade_time,"%Y-%m-%d") as time_str')
            ->group('FROM_UNIXTIME(g.trade_time,"%Y-%m-%d")')
            ->order('g.trade_time desc')
            ->select();
        $sum_money = 0;
        foreach ($sum_trade_list as $k => $item) {
            
            $sum_money = $sum_money + $item['money'];
        }
        // 个税数据
        $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m-%d")="' . $time . '" AND t.uid=' . $user_id . '   AND t.bz LIKE "%分润扣%"  ';
        $sum_shui_list = M('history')->alias('t')
            ->join("xt_trade_orders AS g ON   g.order_no = t.order_no", 'LEFT')
            ->where($str)
            ->field('sum(g.trade_money) as trade_money,sum(t.epoints) as money,FROM_UNIXTIME(g.trade_time,"%Y-%m-%d") as time_str')
            ->group('FROM_UNIXTIME(g.trade_time,"%Y-%m-%d")')
            ->order('g.trade_time desc')
            ->select();
        $sum_shui = 0;
        foreach ($sum_shui_list as $k => $item) {
            
            $sum_shui = $sum_shui + $item['money'];
        }
        // 提现数据
        $str = 'FROM_UNIXTIME(t.rdt,"%Y-%m-%d")="' . $time . '" AND t.uid=' . $user_id . '  and  t.is_pay!=2  ';
        $sum_tiqu_list = M('tiqu')->alias('t')
            ->where($str)
            ->field('sum(t.money) as trade_money, sum(t.money) as money, FROM_UNIXTIME(t.rdt,"%Y-%m-%d %H:%i:%S") as time_str,t.wx_nickname,t.wx_logo')
            ->group('FROM_UNIXTIME(t.rdt,"%Y-%m-%d %H:%i:%S")')
            ->order('t.rdt desc')
            ->select();
        // 返现数据
        $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m-%d")="' . $time . '" AND t.uid=' . $user_id . ' and t.epoints>0   AND t.bz LIKE "%机器返现%"  ';
        $sum_fanxian_list = M('history')->alias('t')
            ->join("xt_trade_orders AS g ON   g.id = t.project_id", 'LEFT')
            ->join("xt_seller AS q ON   q.seller_no = g.shop_id", 'LEFT')
            ->join("xt_user_terminal AS h ON   h.sn =q.sn", 'LEFT')
            ->where($str)
            ->field('G.money_time,sum(g.trade_money) as trade_money, h.sn,sum(t.epoints) as money, FROM_UNIXTIME(g.money_time,"%Y-%m-%d %H:%i:%S") as time_str,h.sn,G.id')
            ->group('FROM_UNIXTIME(t.pdt,"%Y-%m-%d")')
            ->order('t.pdt desc')
            ->select();
        foreach ($sum_fanxian_list as $k => $item) {
            
            $sum = M('history')->where(' project_id=' . $item['id'] . ' and  epoints<0   AND  bz LIKE "%机器返现%"  ')->sum('epoints');
            if ($sum == null) {
                $sum = 0;
            }
            $sum_fanxian_list[$k]['shui'] = $sum;
        }
        
        $sum_fan_money = 0;
        foreach ($sum_fanxian_list as $k => $item) {
            
            $sum_fan_money = $sum_fan_money + $item['money'] + $item['shui'];
        }
        
        $data['info'] = '获取成功！';
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['sum_fan_money'] = $sum_fan_money;
        $data['sum_money'] = $sum_money;
        $sum_shouru_money = $sum_money + $sum_shui + $sum_fan_money;
        $data['sum_shouru_money'] = $sum_shouru_money;
        $data['sum_shui'] = $sum_shui;
        IF ($sum_shui_list == NULL) {
            $sum_shui_list = ARRAY();
        }
        $data['sum_shui_list'] = $sum_shui_list;
        IF ($sum_fanxian_list == NULL) {
            $sum_fanxian_list = ARRAY();
        }
        $data['sum_fanxian_list'] = $sum_fanxian_list;
        IF ($sum_tiqu_list == NULL) {
            $sum_tiqu_list = ARRAY();
        }
        $data['sum_tiqu_list'] = $sum_tiqu_list;
        
        IF ($sum_trade_list == NULL) {
            $sum_trade_list = ARRAY();
        }
        
        $data['sum_trade_list'] = $sum_trade_list;
        $data['current_count'] = 2000;
        $this->ajaxReturn($data);
        exit();
    }

    function apply_saoma()
    {
        $user_id = $_POST['user_id'];
        
        $is_mobile = $_POST['is_mobile'];
        $pay_type = I('pay_type');
        
        $form = M('huikui');
        
        $fee = M('fee');
        $fee_rs = $fee->field('str13')->find();
        
        $data['pay_type'] = $pay_type;
        $data['money'] = $fee_rs['str13'];
        $data['uid'] = $user_id;
        $data['is_pay'] = 0;
        
        $data['add_time'] = time();
        
        $cart = M('huikui')->where('uid=' . $user_id . '')->find();
        if ($cart != null) {
            if ($cart['is_pay'] == 1) {
                $this->ajaxError('对不起，已支付成功无需再次支付！');
                exit();
            }
            $order_no = $cart['order_no'];
            $rs = $form->where('id=' . $cart['id'])->save($data);
        } else {
            $order_no = 'BD' . rand(1000000000, 2000000000);
            $data['order_no'] = $order_no;
            $rs = $form->add($data);
        }
        
        $data = array();
        $data['msg'] = '申请成功,开始支付！';
        $data['status'] = 1;
        $data['order_no'] = $order_no;
        $this->ajaxReturn($data);
    }

    function check_user_saoma_pay()
    {
        $user_id = $_POST['user_id'];
        $is_pay = 0;
        $user_terminal = null;
        $huikui = M('huikui')->where('uid=' . $user_id . '')->find();
        if ($huikui != null) {
            $is_pay = $huikui['is_pay'];
            $user_terminal = M('user_terminal')->where('huikui_id=' . $huikui['id'] . '')->find();
            $seller = M('seller')->where('huikui_id=' . $huikui['id'] . '')->find();
        }
        $fee = M('fee');
        $fee_rs = $fee->field('str13')->find();
        $data = array();
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['is_pay'] = $is_pay;
        $data['huikui'] = $huikui;
        $data['user_terminal'] = $user_terminal;
        $data['seller'] = $seller;
        $data['data'] = $fee_rs;
        $this->ajaxReturn($data);
    }

    function get_user_seller_info()
    {
        $form = M('seller');
        $user_id = I('user_id');
        $type_list = array();
        for ($i = 0; $i < 3; $i ++) {
            
            $map = array();
            $map['status'] = 0;
            $map['user_id'] = array(
                'eq',
                $user_id
            );
            $map['seller_type'] = $i;
            // 已审核
            $count1 = $form->where($map)->count();
            $map = array();
            $map['status'] = array(
                'eq',
                1
            );
            $map['user_id'] = array(
                'eq',
                $user_id
            );
            $map['seller_type'] = $i;
            // 未审核
            $count2 = $form->where($map)->count();
            
            $item = array();
            $item['count1'] = $count1;
            $item['count2'] = $count2;
            $item['count3'] = 0;
            $type_list[] = $item;
        }
        
        $data = array();
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $type_list;
        $this->ajaxReturn($data);
    }

    function del_trade()
    {
        $upload_type = I('get.upload_type');
        $type = I('get.type');
        $content = M('trade_orders_record')->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id", 'LEFT')
            ->field(' t.*,G.user_id,G.user_name')
            ->order(' T.trade_time asc ')
            ->select();
        
        $userids = '';
        
        foreach ($content as $key => $rs) {
            
            $content[$key]['order_no_color'] = 'color:black';
            $content[$key]['trade_money_color'] = 'color:black';
            if ($rs['upload_type'] == 'excel') {
                $trade_orders = M('trade_orders')->field('order_no,trade_money')
                    ->where(array(
                    'order_no' => $rs['order_no']
                ))
                    ->find();
                IF ($trade_orders != NULL) {
                    M('trade_orders_record')->where('id=' . $rs['id'])->delete();
                }
            }
        }
        
        M('fee')->where('id=1')->setField('i7', 0);
        M('fee')->where('id=1')->setField('i8', 0);
        M('fee')->where('id=1')->setField('i9', '');
        $url = (U('User/user_profit', array(
            'type' => $type,
            'upload_type' => $upload_type
        )));
        $this->success('删除成功！', $url);
    }

    function all_del_trade()
    {
        $upload_type = I('get.upload_type');
        $type = I('get.type');
        M('trade_orders_record')->where('id>0')->delete();
        
        $userids = '';
        
        M('fee')->where('id=1')->setField('i7', 0);
        M('fee')->where('id=1')->setField('i8', 0);
        M('fee')->where('id=1')->setField('i9', '');
        $url = (U('User/user_profit', array(
            'type' => $type,
            'upload_type' => $upload_type
        )));
        $this->success('全部删除成功！', $url);
    }

    public function set_bank()
    {
        $fck = M('fck');
        $is_mobile = I('is_mobile');
        $id = I('user_id');
        $bank_name = I('bank_name');
        $bank_card = I('bank_card');
        $bankusername = I('bank_username');
        $bank_address = I('bank_address');
        
        if (Empty($bank_name)) {
            
            $this->ajaxError('对不起，请输入银行名称！');
            exit();
        }
        if (Empty($bank_card)) {
            
            $this->ajaxError('对不起，请输入银行卡号！');
            exit();
        }
        
        $feedback['bank_address'] = $bank_address;
        $feedback['bankusername'] = $bankusername;
        $feedback['user_name'] = $bankusername;
        $feedback['bank_name'] = $bank_name;
        $feedback['bank_card'] = $bank_card;
        $fck->where('id=' . $id)->save($feedback);
        $feedback = M('fck')->where('id=' . $id)->find();
        
        $feedback = get_user_info($feedback);
        
        $data['info'] = '更新成功！';
        $data['msg'] = '更新成功！';
        $data['status'] = 1;
        $data['data'] = $feedback;
        $this->ajaxReturn($data);
        exit();
    }

    public function add_trade_order()
    {
        $fck = M('fck');
        $is_mobile = I('is_mobile');
        $id = I('seller_id');
        $user_id = I('user_id');
        $trade_money = I('money');
        $user = M('fck')->where(array(
            'id' => $user_id
        ))->find();
        if ($user['agent_use'] < $trade_money) {
            $this->ajaxError(C('agent_use') . '不足!');
            exit();
        }
        
        $seller = M('seller')->where(array(
            'id' => $id
        ))->find();
        $order_no = 'KN' . time();
        $trade_time = date('Y-m-d H:i:s', time());
        $new_id = create_trade_order($seller['seller_no'], $trade_money, $order_no, $trade_time, '', 0, $seller['title'], $user_id);
       
        if ($new_id > 0) {
            $fck = D('Fck');
            $kt_cont = '商户付款成功,扣除' . $trade_money;
            $fck->addencAdd($user['id'], $user['user_id'], - $trade_money, 19, 0, 0, 0, $kt_cont . '-' . C('agent_use'), 0);
            
            $result = $fck->where('id=' . $user['id'])->setDec('agent_use', $trade_money);
        }
        
        $data['info'] = '提交成功！';
        $data['msg'] = '提交成功！';
        $data['status'] = 1;
        $this->ajaxReturn($data);
        exit();
    }
}
?>
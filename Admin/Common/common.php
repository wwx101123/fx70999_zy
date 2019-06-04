<?php

// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/* --------------------公共函数---------------------- */
  
// 通过玩家AutoId找到玩家编号user_id
function user_id($id)
{
    $rs = M('fck')->where('id =' . $id)
        ->field('user_id')
        ->find();
    return $rs['user_id'];
}

// 通过玩家AutoId找到玩家编号user_name
function user_name($id)
{
    $rs = M('fck')->where('id =' . $id)
        ->field('user_name')
        ->find();
    return $rs['user_name'];
}

function cp_name($cid)
{
    $rs = M('cptype')->where('id =' . $cid)
        ->field('tpname')
        ->find();
    if ($rs) {
        return $rs['tpname'];
    } else {
        return "无";
    }
}

// 左接点人及业绩
function left_yeji($cid)
{
    $fees9 = M('fee')->where(array(
        'id' => 1
    ))->getField('s9');
    $s9 = explode('|', $fees9);
    $one = $s9[0];
    $rs = M('fck')->where('treeplace=0 and father_id =' . $cid)
        ->field('id,user_id,l,r,left_yeji')
        ->find();
    $user = M('fck')->where(' id =' . $cid)
        ->field('id,user_id,l,r,left_yeji')
        ->find();
    if ($rs) {
        $yeji = ($rs['l'] + $rs['r']) * 100;
        return $rs['user_id'] . '业绩:' . $user['left_yeji'];
    } else {
        return "无";
    }
}

// 左接点人及业绩
function right_yeji($cid)
{
    $fees9 = M('fee')->where(array(
        'id' => 1
    ))->getField('s9');
    $s9 = explode('|', $fees9);
    $one = $s9[0];
    $rs = M('fck')->where('treeplace=1 and father_id =' . $cid)
        ->field('id,user_id,l,r,right_yeji')
        ->find();
    $user = M('fck')->where(' id =' . $cid)
        ->field('id,user_id,l,r,right_yeji')
        ->find();
    if ($rs) {
        $yeji = ($rs['l'] + $rs['r']) * 100;
        return $rs['user_id'] . '业绩:' . $user['right_yeji'];
    } else {
        return "无";
    }
}

function sum_yeji($user_id)
{
    $rs = M('fck')->where(" user_id='" . $user_id . "'")
        ->field('id,user_id,u_level,live_gupiao,cpzj,b1,father_id')
        ->find();
    if ($rs != NULL) {
        $parent_user = M('fck')->where("  ID=" . $rs['father_id'])
            ->field('id,user_id,u_level,live_gupiao,cpzj,b1,treeplace,father_id')
            ->find();
        if ($parent_user != NULL) {
            sum_parent_yeji($parent_user['id'], $rs);
        }
    }
}

function sum_parent_yeji($user_id, $laiyuan)
{
    $fees9 = M('fee')->where(array(
        'id' => 1
    ))->getField('s9');
    $s9 = explode('|', $fees9);
    if ($user_id != NULL) {
        $user = M('fck')->where(" id=" . $user_id)
            ->field('id,user_id,u_level,live_gupiao,cpzj,b1,treeplace,father_id')
            ->find();
        if ($user != null) {
            $father_user = M('fck')->where(" id=" . $user['father_id'])
                ->field('id,user_id,u_level,live_gupiao,cpzj,b1,treeplace')
                ->find();
            $one = $s9[$laiyuan['u_level'] - 1];
            
            if ($user['treeplace'] == 0) {
                M('fck')->where('id=' . $father_user['id'])->setInc('left_yeji', $one);
            }
            if ($user['treeplace'] == 1) {
                M('fck')->where('id=' . $father_user['id'])->setInc('right_yeji', $one);
            }
            sum_parent_yeji($father_user['id'], $laiyuan);
        }
    }
}

// 离封顶差距
function fdjuli($cid)
{
    $feestr5 = M('fee')->where(array(
        'id' => 1
    ))->getField('str5');
    $gpone = M('fee')->where(array(
        'id' => 1
    ))->getField('gp_one');
    $str5 = explode('|', $feestr5);
    $rs = M('fck')->where("id>0 and id=" . $cid)
        ->field('id,user_id,u_level,get_level,live_gupiao,cpzj,b1')
        ->find();
    if ($rs) {
        $mylv = $rs['get_level'];
        $fdbs = $str5[$mylv - 1];
        
        $s9 = M('fee')->where(array(
            'id' => 1
        ))->getField('s9');
        $s9 = explode('|', $s9);
        
        $fdmoney = $fdbs * $s9[$mylv - 1];
        $dqmoney = $rs['b1'];
        $dqmoney = M('t_trans')->where('TYPE=0 AND  UID=' . $cid)->sum('price*num');
        
        $gp_inm = M('split_gp')->where('  user_id=' . $cid)->sum('gp_inm');
        // 分账复投积分
        $gp_inn = M('split_gp')->where('user_id=' . $cid)->sum('gp_inn');
        // 分账幸运积分
        $gp_ino = M('split_gp')->where('user_id=' . $cid)->sum('gp_ino');
        // 税收
        $gp_shui = M('split_gp')->where('user_id=' . $cid)->sum('gp_shui');
        
        $fdjuli = $fdmoney - $dqmoney - $gp_inm - $gp_inn - $gp_ino - $gp_shui;
        
        if ($dqmoney == NULL) {
            $dqmoney = 0;
        }
        $fdjuli = $fdmoney - $dqmoney - $gp_inm - $gp_inn - $gp_ino - $gp_shui;
        if ($fdjuli < 0) {
            $fdjuli = 0;
        }
        // $zhidao_money =$rs['zhidao_money'];
        // $fdjuli = $fdjuli -$zhidao_money;
        // if ($fdjuli < 0) {
        // $fdjuli = 0;
        // }
        
        return $fdjuli;
    } else {
        return "0.00";
    }
}

function xf_remind($cid)
{
    $s16 = M('fee')->getField('s16');
    $cpzj = M('fck')->where(array(
        'id' => $cid
    ))->getField('cpzj');
    $xf_money = M('fck')->where(array(
        'id' => $cid
    ))->getField('xf_money');
    $sfmoney = $cpzj * (100 - $s16) * 0.01;
    if ($xf_money < $sfmoney) {
        return "冻结积分释放达到" . $s16 . "%须续费，不续费将冻结综合补贴，释放完剩余冻结积分后变成消费会员";
    } else {
        return "";
    }
}

function mysubstr($string, $sublen, $start = 0, $code = 'UTF-8')
{
    // 字符串截取函数 默认UTF-8
    if ($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        
        if (count($t_string[0]) - $start > $sublen)
            return join('', array_slice($t_string[0], $start, $sublen)) . "...";
        return join('', array_slice($t_string[0], $start, $sublen));
    } else {
        $start = $start * 2;
        $sublen = $sublen * 2;
        $strlen = strlen($string);
        $tmpstr = '';
        
        for ($i = 0; $i < $strlen; $i ++) {
            if ($i >= $start && $i < ($start + $sublen)) {
                if (ord(substr($string, $i, 1)) > 129) {
                    $tmpstr .= substr($string, $i, 2);
                } else {
                    $tmpstr .= substr($string, $i, 1);
                }
            }
            if (ord(substr($string, $i, 1)) > 129)
                $i ++;
        }
        if (strlen($tmpstr) < $strlen)
            $tmpstr .= "...";
        return $tmpstr;
    }
}

function mytelsubstr($string, $sublen, $start = 0, $code = 'UTF-8')
{
    // 字符串截取函数 默认UTF-8
    if ($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        
        if (count($t_string[0]) - $start > $sublen)
            return join('', array_slice($t_string[0], $start, $sublen)) . "****";
        return join('', array_slice($t_string[0], $start, $sublen));
    } else {
        $start = $start * 2;
        $sublen = $sublen * 2;
        $strlen = strlen($string);
        $tmpstr = '';
        
        for ($i = 0; $i < $strlen; $i ++) {
            if ($i >= $start && $i < ($start + $sublen)) {
                if (ord(substr($string, $i, 1)) > 129) {
                    $tmpstr .= substr($string, $i, 2);
                } else {
                    $tmpstr .= substr($string, $i, 1);
                }
            }
            if (ord(substr($string, $i, 1)) > 129)
                $i ++;
        }
        if (strlen($tmpstr) < $strlen)
            $tmpstr .= "****";
        return $tmpstr;
    }
}

// 如果user_id等于800000就返回公司
function conname($n)
{
    $rs = M('fck')->where('id =1')
        ->field('user_id')
        ->find();
    if ($n == $rs['user_id']) {
        return '公司';
    } else {
        return $n;
    }
}

function pwdHash($password, $type = 'md5')
{
    return hash($type, $password);
}

// 对密码进行加密
function pwdHash_pass($password, $type = 'md5')
{
    return hash($type, $password);
}

function noHTML($content)
{
    $content = strip_tags($content);
    $content = preg_replace("/<a[^>]*>/i", '', $content);
    $content = preg_replace("/<\/a>/i", '', $content);
    $content = preg_replace("/<div[^>]*>/i", '', $content);
    $content = preg_replace("/<\/div>/i", '', $content);
    $content = preg_replace("/<font[^>]*>/i", '', $content);
    $content = preg_replace("/<\/font>/i", '', $content);
    $content = preg_replace("/<p[^>]*>/i", '', $content);
    $content = preg_replace("/<\/p>/i", '', $content);
    $content = preg_replace("/<span[^>]*>/i", '', $content);
    $content = preg_replace("/<\/span>/i", '', $content);
    $content = preg_replace("/<\?xml[^>]*>/i", '', $content);
    $content = preg_replace("/<\/\?xml>/i", '', $content);
    $content = preg_replace("/<o:p[^>]*>/i", '', $content);
    $content = preg_replace("/<\/o:p>/i", '', $content);
    $content = preg_replace("/<u[^>]*>/i", '', $content);
    $content = preg_replace("/<\/u>/i", '', $content);
    $content = preg_replace("/<b[^>]*>/i", '', $content);
    $content = preg_replace("/<\/b>/i", '', $content);
    $content = preg_replace("/<meta[^>]*>/i", '', $content);
    $content = preg_replace("/<\/meta>/i", '', $content);
    $content = preg_replace("/<!--[^>]*-->/i", '', $content);
    $content = preg_replace("/<p[^>]*-->/i", '', $content);
    $content = preg_replace("/style=.+?['|\"]/i", '', $content);
    $content = preg_replace("/class=.+?['|\"]/i", '', $content);
    $content = preg_replace("/id=.+?['|\"]/i", '', $content);
    $content = preg_replace("/lang=.+?['|\"]/i", '', $content);
    $content = preg_replace("/width=.+?['|\"]/i", '', $content);
    $content = preg_replace("/height=.+?['|\"]/i", '', $content);
    $content = preg_replace("/border=.+?['|\"]/i", '', $content);
    $content = preg_replace("/face=.+?['|\"]/i", '', $content);
    $content = preg_replace("/face=.+?['|\"]/", '', $content);
    $content = preg_replace("/face=.+?['|\"]/", '', $content);
    $content = str_replace(" ", "", $content);
    $content = str_replace("&nbsp;", "", $content);
    return $content;
}

/**
 * 查询信誉
 */
function cx_usrate($myid)
{
    $fck = M('fck');
    $mrs = $fck->where('id=' . $myid)
        ->field('id,seller_rate')
        ->find();
    $mrate = (int) $mrs['seller_rate'];
    $s_img = "";
    if ($mrate > 0) {
        for ($i = 1; $i <= $mrate; $i ++) {
            $s_img .= '<img class="star" src="__PUBLIC__/Images/star.png" />';
        }
    }
    unset($fck, $mrs);
    return $s_img;
}

/**
 * 给出兑换货币
 * *
 */
function Jx_cname($brmb)
{
    $fee = M('fee');
    $fee_rs = $fee->field('str10,str11')->find();
    $prii = $fee_rs['str10'];
    $ormb = $brmb * $prii;
    $ormb = number_format($ormb, 2);
    $in_r = "￥" . $ormb;
    unset($fee, $fee_rs);
    return $in_r;
}

function cx_cname($brmb)
{
    $fee = M('fee');
    $fee_rs = $fee->field('str10,str11')->find();
    $prii = $fee_rs['str11'];
    $ormb = $brmb * $prii;
    $ormb = number_format($ormb, 2);
    $out_r = "￥" . $ormb;
    unset($fee, $fee_rs);
    return $out_r;
}

function zh_filesize($fsize)
{
    $mbb = 1024;
    $gbb = 1024 * 1024;
    if ($fsize >= $gbb) {
        $out_s = $fsize / $gbb;
        $out_s = ((int) ($out_s * 100)) / 100;
        $last_o = $out_s . " GB";
    } elseif ($fsize >= $mbb) {
        $out_s = $fsize / $mbb;
        $out_s = ((int) ($out_s * 100)) / 100;
        $last_o = $out_s . " MB";
    } else {
        $out_s = number_format($fsize, 2);
        $last_o = $out_s . " KB";
    }
    return $last_o;
}

function payStatus($value = '')
{
    switch ($value) {
        case '0':
            return '<span class="label label-danger">未付款</span>';
            break;
        case '1':
            return '<span class="label label-success">已付款</span>';
            break;
        default:
            // code...
            break;
    }
}

function getProType($id)
{
    $type = M('cptype');
    $name = $type->where(array(
        'id' => $id
    ))->getField('tpname');
    unset($type);
    return $name;
}

function getProType2($id)
{
    $type = M('swaptype');
    $name = $type->where(array(
        'id' => $id
    ))->getField('tpname');
    unset($type);
    return $name;
}

function getUserLevel($id)
{
    $fee = M('fee');
    $name = $fee->where(array(
        'id' => 1
    ))->getField('s10');
    $list = explode('|', $name);
    $level = $list[$id - 1];
    unset($fee, $list, $name);
    return $level;
}

function agentD($uid, $level, $uplevel)
{
    $fck = M('fck');
    $district = M('district');
    $city = M('city');
    $province = M('province');
    
    $info = $fck->where(array(
        'id' => $uid
    ))
        ->field('shop_a,shop_b,shop_c')
        ->find();
    switch ($uplevel) {
        case '1':
            $d = $district->where(array(
                'd_code' => $info['shop_c']
            ))->find();
            $c = $city->where(array(
                'c_code' => $d['c_id']
            ))->find();
            $p = $province->where(array(
                'p_code' => $c['p_id']
            ))->find();
            unset($fck, $district, $city, $province, $info);
            return $p['p_name'] . "/" . $c['c_name'] . "/" . $d['d_name'];
            break;
        case '2':
            $c = M('city')->where(array(
                'c_code' => $info['shop_b']
            ))->find();
            $p = $province->where(array(
                'p_code' => $c['p_id']
            ))->find();
            unset($fck, $district, $city, $province, $info);
            return $p['p_name'] . "/" . $c['c_name'];
            break;
        case '3':
            $p = $province->where(array(
                'p_code' => $info['shop_a']
            ))->find();
            unset($fck, $district, $city, $province, $info);
            return $p['p_name'];
            break;
        default:
            // code...
            break;
    }
}

function chanpin($id)
{
    $name = M('product')->where('id=' . $id)->getField('name');
    // $name=substr($name,0,20);
    return $name;
}

function chkInt($value)
{
    if (ceil($value) == $value) {
        return true;
    } else {
        
        return false;
    }
}

function chkNum($n)
{
    $n = trim($n);
    if (empty($n))
        return false;
    if (! is_numeric($n))
        return false;
    if (floatval($n) <= 0)
        return false;
    return true;
}

function chkStr($str)
{
    $str = trim($str);
    if (empty($str))
        return false;
    return true;
}

function chkArr($arr)
{
    if (empty($arr))
        return false;
    if (! is_array($arr))
        return false;
    if (count($arr) <= 0)
        return false;
    foreach ($arr as $k => $v) {
        if ($v === false) {
            return false;
        }
    }
    return true;
}

function get_new_user_number()
{
    $user_add = M('fee')->where(array(
        'id' => 1
    ))->getField('user_add');
    if (EMPTY($user_add)) {
        $user_add = 1;
    }
    $max_user = M('fck')->field("max(user_number) AS user_number")->find();
    $user_number = $max_user['user_number'];
    $user_number = $user_number + $user_add;
    return $user_number;
}

function validation_filter_id_card($id_card)
{
    if (strlen($id_card) == 18) {
        return idcard_checksum18($id_card);
    } elseif ((strlen($id_card) == 15)) {
        $id_card = idcard_15to18($id_card);
        return idcard_checksum18($id_card);
    } else {
        return false;
    }
}

// 计算身份证校验码，根据国家标准GB 11643-1999
function idcard_verify_number($idcard_base)
{
    if (strlen($idcard_base) != 17) {
        return false;
    }
    // 加权因子
    $factor = array(
        7,
        9,
        10,
        5,
        8,
        4,
        2,
        1,
        6,
        3,
        7,
        9,
        10,
        5,
        8,
        4,
        2
    );
    // 校验码对应值
    $verify_number_list = array(
        '1',
        '0',
        'X',
        '9',
        '8',
        '7',
        '6',
        '5',
        '4',
        '3',
        '2'
    );
    $checksum = 0;
    for ($i = 0; $i < strlen($idcard_base); $i ++) {
        $checksum += substr($idcard_base, $i, 1) * $factor[$i];
    }
    $mod = $checksum % 11;
    $verify_number = $verify_number_list[$mod];
    return $verify_number;
}

// 将15位身份证升级到18位
function idcard_15to18($idcard)
{
    if (strlen($idcard) != 15) {
        return false;
    } else {
        // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
        if (array_search(substr($idcard, 12, 3), array(
            '996',
            '997',
            '998',
            '999'
        )) !== false) {
            $idcard = substr($idcard, 0, 6) . '18' . substr($idcard, 6, 9);
        } else {
            $idcard = substr($idcard, 0, 6) . '19' . substr($idcard, 6, 9);
        }
    }
    $idcard = $idcard . idcard_verify_number($idcard);
    return $idcard;
}

// 18位身份证校验码有效性检查
function idcard_checksum18($idcard)
{
    if (strlen($idcard) != 18) {
        return false;
    }
    $idcard_base = substr($idcard, 0, 17);
    if (idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))) {
        return false;
    } else {
        return true;
    }
}

// 排在我前面的配股数量
function peigu_count($cid)
{
    $rs = M('peisong')->where("id>0 AND agent_gp>=1 and uid=" . $cid)
        ->field('*')
        ->find();
    $count = 0;
    if ($rs != NULL) {
        $count = M('peisong')->where(" agent_gp>=1 and addtime<" . $rs['addtime'])->count();
    }
    if ($count == NULL) {
        $count = 0;
    }
    return $count;
}

function check_email($email)
{
    $pattern = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
    if (! preg_match($pattern, $email)) {
        
        return true;
    } else {
        return false;
    }
}

function check_phone($mobile)
{
    $regex = '/^1[345678]\d{9}$/';
    if (! preg_match($regex, $mobile)) {
        return false;
    } else {
        return true;
    }
}

function get_terminal_status($_id)
{
    $_title = "";
    $model = M('user_terminal')->where('id=' . $_id)->find();
    
    switch ($model['status']) {
        case 1:
            $_title = "待激活";
            break;
        
        case 0:
            $_title = "已激活";
            break;
    }
    switch ($goods_crowd['status']) {
        case 2: // 如果订单为已确认状态，则进入发货状态
            $_title = "投诉中";
            break;
    }
    
    return $_title;
}

function get_order_payment($_id)
{
    $order_payment = M('payment')->where('id=' . $_id)->find();
    
    $payment_str = $order_payment['title'];
    RETURN $payment_str;
}

function get_order_status($_id, $user_id = 0)
{
    $_title = "";
    $model = M('orders')->where('id=' . $_id)->find();
    $goods_crowd = M('goods_crowd_users')->where('user_id=' . $user_id . ' AND order_id=' . $_id)->find();
    
    switch ($model['status']) {
        case 1: // 如果是线下支付，支付状态为0，如果是线上支付，支付成功后会自动改变订单状态为已确认
            if ($model['payment_status'] == 0) {
                $_title = "待付款";
                if ($model['is_give'] == 1) {
                    $_title = "待赠送";
                }
            }
            if ($model['is_money'] == 1) {
                $_title = "已付款";
            }
            if ($model['money_user_id'] > 0 && $model['money_user_id'] != $model['user_id']) {
                $_title = "等待代付人付款";
            }
            
            break;
        case 2: // 如果订单为已确认状态，则进入发货状态
            if ($model['express_status'] > 1) {
                $_title = "已发货";
            } else {
                $_title = "待发货";
            }
            break;
        case 3:
            $_title = "交易完成";
            break;
        case 4:
            $_title = "已取消";
            break;
        case 5:
            $_title = "已作废";
        case 6:
            if ($model['back_status'] == 1) {
                $_title = "退货申请中";
            } else {
                if ($model['back_check_status'] == 1) {
                    $_title = "驳回申请";
                }
                if ($model['back_check_status'] == 0) {
                    $_title = "同意退货";
                }
            }
            break;
            break;
    }
    switch ($goods_crowd['status']) {
        case 2: // 如果订单为已确认状态，则进入发货状态
            $_title = "投诉中";
            break;
    }
    
    return $_title;
}

function recommend_tree($user_id)
{
    $fee = M('fee');
    $fee_rs = $fee->field('recommend_tree_sql')->find();
    $recommend_tree_sql = $fee_rs['recommend_tree_sql'];
    $recommend_tree_sql = str_replace("{ID}", $user_id, $recommend_tree_sql);
    
    $tree = M()->query($recommend_tree_sql);
    
    return $tree;
}

function GetChilds($oldData, &$newData, $parent_id, $channel_id)
{
    $form = M('article_category');
    $dr = $form->where("parent_id=" . $parent_id . ' AND channel_id=' . $channel_id . ' AND IS_LOCK=0')->select();
    
    foreach ($dr as $i => $goods) {
        $row = $goods; // 创建新行
                       // 循环查找列数量赋值
        $newData[] = $row;
        // 调用自身迭代
        GetChilds($oldData, $newData, $goods["id"], $channel_id);
    }
}

function UpdateChilds($parent_id)
{
    // 查找父节点信息
    $model = M('article_category')->where(array(
        'id' => $parent_id
    ))->find();
    if ($model != null) {
        // 查找子节点
        $ds = M('article_category')->where(array(
            'parent_id' => $parent_id
        ))->select();
        foreach ($ds as $dr) {
            // 修改子节点的ID列表及深度
            $id = $dr["id"];
            $class_list = $model['class_list'] . $id . ",";
            $class_layer = $model['class_layer'] + 1;
            
            $rs = M('article_category')->where('id=' . $id . ' ')->setField('class_list', $class_list);
            $rs = M('article_category')->where('id=' . $id . ' ')->setField('class_layer', $class_layer);
            
            // 调用自身迭代
            UpdateChilds($id); // 带事务
        }
    }
}

function StringOfChar($strLong, $str)
{
    $ReturnStr = "";
    for ($i = 0; $i < $strLong; $i ++) {
        $ReturnStr = $ReturnStr . $str;
    }
    
    return $ReturnStr;
}

function project_status($project_id)
{
    $project = M('project')->where("id='" . $project_id . "'")->find();
    $status_str = '';
    if ($project["status"] == 0) {
        $status_str = '正在接单';
        if ($project["is_lock"] == 1) {
            $status_str = '任务结束';
        }
        
        $now_time = time();
        if ($now_time < $project['start_time']) {
            $status_str = '未开始';
        }
        if ($now_time > $project['end_time']) {
            $status_str = '任务结束';
        }
    } else if ($project["status"] == 1) {
        
        $status_str = "待审核";
    } else if ($project["status"] == 2) {
        $status_str = "审核不通过";
    }
    
    return $status_str;
}

function user_level_check($user_id, $where = '', $begintime = '')
{
    
    // 升级检测
    $fee_rs = M('fee')->field('s10,s9,s8,s11,user_num')->find();
    
    $user = M('fck')->where("id='" . $user_id . "'")->find();
    $s9 = explode('|', $fee_rs['s9']);
    $s8 = explode('|', $fee_rs['s8']);
    $s11 = explode('|', $fee_rs['s11']);
    $user_num = explode('|', $fee_rs['user_num']);
    $user_list = M('fck')->field(' id,user_id,u_level ')
        ->where(' id in (0' . $user['re_path'] . '0) or   id=' . $user_id)
        ->order(' re_level desc ')
        ->select();
    
    foreach ($user_list as $key => $rs) {
        $user = M('fck')->field('id,u_level,get_level')
            ->where("id='" . $rs['id'] . "'")
            ->find();
        if (($rs['u_level']) < count($s9)) {
            // 获取伞下交易量
            $trade_money = get_all_trade_money($user['id'], $where);
            // 获取直推人数
            $user_num = M('fck')->where("re_id='" . $rs['id'] . "'")->count();
            // 获取伞下人数
            $up_level = $user['u_level'];
            $team_user_num = M('fck')->where(" re_path like '%," . $rs['id'] . ",%' AND  u_level>=" . $up_level)->count();
            $fck = D('Fck');
            
            IF ($s9[$up_level] <= $trade_money && $s8[$up_level] <= $user_num && $s11[$up_level] <= $team_user_num) {
                
                $result = $fck->where('id=' . $user['id'])->setField('u_level', $up_level + 1);
                $result = $fck->where('id=' . $user['id'])->setField('get_level', $up_level + 1);
            }
        }
        // set_user_trade_money($user, $where, $begintime);
    }
}

function user_award($voo, $money, $inUserid)
{
    $dan = $money;
    $fee = M('fee');
    $fee_rs = $fee->find();
    $s9 = explode('|', $fee_rs['s9']);
    
    $fck = D('Fck');
    // 统计单数
    $fck->xiangJiao($voo['id'], $dan);
    
    // 算出奖金
    $fck->getusjj($voo['id'], 1, $dan, $inUserid);
    
    // 算出注册积分奖励
    $s9 = explode('|', $fee_rs['s9']);
    // $fck->add_register_award($voo, '注册赠送', $dan);
    
    $fck->getduipeng($voo['id'], 1, $dan);
    // $fck->add_peisong($vo['id'], $in_gp);
    
    $s9 = explode('|', $fee_rs['s9']);
    
    $where = array();
    $where['user_id'] = $voo['user_id'];
    
    $frs = $fck->where($where)->find();
    $ul = $dan;
    $buy_point = explode('|', $fee_rs['buy_point']);
    $agent_use = explode('|', $fee_rs['agent_use']);
    $fck = D('Fck');
    // $buy_point = $buy_point[$frs['get_level'] - 1] * 0.01 * $ul;
    // $fck->buy_pointAdd($frs['id'], $buy_point);
    
    $agent_use = $buy_point[$frs['get_level'] - 1] * 0.01 * $ul;
    // $fck->agent_useAdd($frs['id'], $agent_use);
    
    $fee = M('fee')->find();
    $live_gupiao = explode('|', $fee['live_gupiao']);
    $live_gupiao = $live_gupiao[$frs['get_level'] - 1] * 0.01 * $ul;
    if ($live_gupiao > 0) {
        // $fck->gupiaoAdd($frs['id'], $live_gupiao);
    }
    if ($frs['register_type'] == 1) {
        $ssb = explode('|', $fee['ssb']);
        $ssb = $ssb[$frs['get_level'] - 1] * 0.01 * $ul;
        if ($ssb > 0) {
            // $fck->ssbAdd($frs['id'], $ssb);
        }
    }
}

function get_month_diff($start, $end = FALSE)
{
    $end or $end = time();
    $start = new DateTime("@$start");
    $end = new DateTime("@$end");
    $diff = $start->diff($end);
    return $diff->format('%y') * 12 + $diff->format('%m');
}

function timediff($begin_time, $end_time)
{
    if ($begin_time < $end_time) {
        $starttime = $begin_time;
        $endtime = $end_time;
    }
    if ($begin_time >= $end_time) {
        $starttime = $end_time;
        $endtime = $begin_time;
    }
    // 计算天数
    $timediff = $endtime - $starttime;
    $days = intval($timediff / 86400);
    // 计算小时数
    $remain = $timediff % 86400;
    $hours = intval($remain / 3600);
    // 计算分钟数
    $remain = $remain % 3600;
    $mins = intval($remain / 60);
    // 计算秒数
    $secs = $remain % 60;
    $res = array(
        "day" => $days,
        "hour" => $hours,
        "min" => $mins,
        "sec" => $secs
    );
    return $res;
}

function get_mobile_area($mobile)
{
    // $sms = array(
    // 'province' => '',
    // 'supplier' => ''
    // ); // 初始化变量
    // // 根据淘宝的数据库调用返回值
    // $url = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=" . $mobile . "&t=" . time();
    
    // $content = file_get_contents($url);
    // $content = str_replace("__GetZoneResult_", "", $content);
    // $content = str_replace("=", "", $content);
    // $content = json_encode($content);
    return '';
}

function time_str($time)
{
    $str = '';
    if (date('Ymd', $time) == date('Ymd')) {
        $str = date("H:i", $time);
    } else if (date('Ymd', $time) == date("Ymd", strtotime("-1 day"))) {
        $str = '昨天' . date("H:i", $time);
    } else {
        $str = date("m月d日", $time);
    }
    return $str;
}

function array_sort($array, $keys, $type = 'asc')
{
    // $array为要排序的数组,$keys为要用来排序的键名,$type默认为升序排序
    $keysvalue = $new_array = array();
    foreach ($array as $k => $v) {
        $keysvalue[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($keysvalue);
    } else {
        arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k => $v) {
        $new_array[$k] = $array[$k];
    }
    return $new_array;
}

function get_project_info($id, $rs, $user_id)
{
    project_check($id);
    $rs["end_time_str"] = date("Y-m-d H:i", $rs["end_time"]);
    $project_albums = M('project_albums')->where(array(
        'project_id' => $id
    ))->select();
    $rs['project_albums'] = $project_albums;
    
    $project_user_imgs = M('project_user_albums')->where(array(
        'project_id' => $id,
        'user_id' => $user_id
    ))->count();
    $rs['project_user_img_count'] = $project_user_imgs;
    $project_user_count = M('project_users')->where(array(
        'project_id' => $id
    ))->count();
    $rs['project_user_count'] = $project_user_count;
    $rs['remain'] = $rs['num'] - $project_user_count;
    
    $category = M('article_category')->where(array(
        'id' => $rs['category_id']
    ))->find();
    $rs['category'] = $category['title'];
    $rs['call_index'] = $category['call_index'];
    $rs['seo_title'] = $category['seo_title'];
    $rs['category_subtitle'] = $category['sub_title'];
    $rs['package'] = $category['package'];
    
    $publish_user = M('fck')->where("id='" . $rs["user_id"] . "'")->find();
    $rs["member_id"] = $publish_user['user_id'];
    $rs["name"] = $publish_user['user_id'];
    $category = M('article_category')->where(array(
        'id' => $rs['category_id']
    ))->find();
    $rs['category'] = $category['title'];
    $rs['category_seo_title'] = $category['seo_title'];
    $rs['category_subtitle'] = $category['sub_title'];
    $rs['category_icon'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $category['img_url']);
    
    $rs["per_money"] = $rs["money"] / $rs["num"];
    
    $rs["status_str"] = project_status($rs['id']);
    
    $rs["add_time_str"] = date("Y-m-d H:i", $rs["add_time"]);
    $rs["end_time_str"] = date("Y-m-d H:i", $rs["end_time"]);
    if (empty($rs["check_time"])) {
        $rs["check_time"] = '';
    } else {
        $rs["check_time"] = date("Y-m-d H:i:s", $rs["check_time"]);
    }
    
    if ($rs["lock_time"] > 0) {
        $rs["end_time"] = $rs["lock_time"];
    }
    if ($rs["over_time"] > 0) {
        $rs["over_time"] = date("Y-m-d H:i:s", $rs["over_time"]);
    } else {
        $rs["over_time"] = '';
    }
    // 总数
    $user_count = 0;
    // 进行中
    $being_user_count = 0;
    // 待审核数量
    $check_user_count = 0;
    // 接了但是没做
    $join_user_count = 0;
    // 已审核完成
    $complete_user_count = 0;
    // 已上传未审核
    $upload_user_count = 0;
    // 申述
    $complain_user_count = 0;
    // 不合格
    $error_user_count = 0;
    project_count($rs["id"], $user_count, $check_user_count, $join_user_count, $complete_user_count, $complain_user_count, $error_user_count, $being_user_count, $upload_user_count);
    $rs["user_count"] = $user_count;
    $rs["check_user_count"] = $check_user_count;
    $rs["join_user_count"] = $join_user_count;
    $rs["complain_user_count"] = $complain_user_count;
    $rs["complete_user_count"] = $complete_user_count;
    $rs["error_user_count"] = $error_user_count;
    $rs["being_user_count"] = $being_user_count;
    $rs["upload_user_count"] = $upload_user_count;
    
    $project_albums = M('project_albums')->where(array(
        'project_id' => $rs["id"]
    ))->select();
    
    $project_users = M('project_users')->where(array(
        'project_id' => $rs["id"]
    ))->select();
    $complain_time = M('fee')->where(array(
        'id' => 1
    ))->getField('complain_time');
    foreach ($project_users as $i => $value) {
        $project_users[$i]['can_complain'] = 1;
        $end = strtotime('+' . $complain_time . ' hours', $value['check_time']);
        if ($end < time()) {
            $project_users[$i]['can_complain'] = 0;
        }
    }
    
    $rs['project_albums'] = $project_albums;
    $rs['project_users'] = $project_users;
    $rs['is_end'] = 0;
    if ($rs["end_time"] < time()) {
        $rs['is_end'] = 1;
    }
    
    if ($rs['is_lock'] == 1) {
        $rs["join_user_count"] = 0;
    }
    $rs["is_show"] = 1;
    $rs["user_count"] = ($check_user_count + $join_user_count + $complete_user_count + $complain_user_count);
    $project_user_count = M('project_users')->where(array(
        'project_id' => $id,
        'user_id' => $user_id
    ))->count();
    if ($rs["user_count"] >= $rs["num"] && $project_user_count == 0) {
        $rs["is_show"] = 0;
    }
    if ($rs["complete_time"] > 0) {
        $rs["is_show"] = 0;
    }
    
    $project_user_count = M('project_users')->where(array(
        'project_id' => $id,
        'user_id' => $user_id,
        'status' => 2
    ))->count();
    if ($project_user_count > 0) {
        $rs["is_show"] = 0;
    }
    
    $rs['back_money'] = get_project_back_money($rs, $id);
    $rs["user_project_status_str"] = '';
    $rs["user_project_status"] = 0;
    
    $project_albums = M('project_albums')->where(array(
        'project_id' => $id
    ))->select();
    
    $project_users = M('project_users')->where(array(
        'project_id' => $id
    ))->select();
    
    $rs['project_albums'] = $project_albums;
    $rs['project_users'] = $project_users;
    $rs['real_money'] = $rs['real_money'];
    
    RETURN $rs;
}

function get_project_back_money($rs, $id)
{
    $back_money = 0;
    $success_money = M('history')->where('project_id=' . $id . ' AND bz like "%完成任务%" ')->sum('epoints');
    
    if ($success_money == null) {
        $success_money = 0;
    }
    $back_money = $rs['all_money'] - $success_money;
    return $back_money;
}

function get_user_remain_award($user)
{
    return $user['limit_money'];
}

function is_chuju($user)
{
    $is_chuju = FALSE;
    
    $chuju = get_user_remain_award($user);
    if ($chuju > 0) {
        $is_chuju = true;
    }
    
    return $is_chuju;
}

function is_today_fenhong($user)
{
    $is_today_fenhong = FALSE;
    
    $count = M('fenhong')->where('uid=' . $user['id'])->count();
    
    if ($count > 0) {
        
        $timediff = timediff($user['fanli_time'], time());
        if ($timediff['day'] <= 0) {
            $is_today_fenhong = true;
        }
    }
    
    return $is_today_fenhong;
}

function set_user_count($userInfo)
{
    
    // // 获取本月新增盟友
    // $month_user_count = M('fck')->where(" re_path like '%," . $userInfo['id'] . ",%' AND FROM_UNIXTIME(rdt,'%Y-%m')= FROM_UNIXTIME(unix_timestamp(now()),'%Y-%m') ")->count();
    // if ($month_user_count == null) {
    // $month_user_count = 0;
    // }
    // 获取本月新增激活盟友
    $month_user_active_count = M('fck')->where(" re_path like '%," . $userInfo['id'] . ",%' AND   FROM_UNIXTIME(pdt,'%Y-%m')= FROM_UNIXTIME(unix_timestamp(now()),'%Y-%m') ")->count();
    if ($month_user_active_count == null) {
        $month_user_active_count = 0;
    }
    // 本月新增盟友
    $month_user_count = M('fck')->alias('t')
        ->where(" (t.re_id   = " . $userInfo['id'] . " AND  t.auth_status=0 and t.shop_type=2)       and FROM_UNIXTIME(t.pdt , '%Y-%m') like '%" . date("Y-m") . "%'")
        ->count();
    
    // 本月新增团队盟友
    $month_team_use_count = M('fck')->alias('t')
        ->where("           (   (t.re_path   like '%," . $userInfo['id'] . ",%' AND  t.auth_status=0 and t.shop_type=2)   OR t.id=" . $userInfo['id'] . ")    and FROM_UNIXTIME(t.pdt , '%Y-%m') like '%" . date("Y-m") . "%'")
        ->count();
    
    // 盟友总数量
    $use_count = M('fck')->alias('t')
        ->where("  t.re_id=" . $userInfo['id'] . " AND  t.auth_status=0 and t.shop_type=2     ")
        ->count();
    // 团队盟友总数量
    $team_use_count = M('fck')->alias('t')
        ->where(" ((t.re_path   like '%," . $userInfo['id'] . ",%' AND  t.auth_status=0 and t.shop_type=2)   OR t.id=" . $userInfo['id'] . ")   ")
        ->count();
    
    M('fck')->where('id=' . $userInfo['id'])->setField('team_user_count', $team_use_count);
    M('fck')->where('id=' . $userInfo['id'])->setField('user_count', $use_count);
    M('fck')->where('id=' . $userInfo['id'])->setField('month_user_count', $month_user_count);
    M('fck')->where('id=' . $userInfo['id'])->setField('month_team_user_count', $month_team_use_count);
    
    $ret = array();
    $ret['team_user_count'] = $team_use_count;
    $ret['user_count'] = $use_count;
    $ret['month_user_count'] = $month_user_count;
    $ret['month_team_user_count'] = $month_team_use_count;
    
    $begintime_str = date('Y-m-01', strtotime(date("Y-m-d")));
    $begintime = strtotime($begintime_str);
    $endtime_str = date('Y-m-d', strtotime("$begintime_str +1 month -1 day"));
    $endtime = strtotime($endtime_str);
    
    $ret['begintime'] = $begintime;
    $ret['endtime'] = $endtime;
    
    set_month_data($userInfo['id'], $begintime, $endtime, $ret);
}

function set_user_shop($userInfo)
{
    // 获取本月新增商户
    $month_shop_count = M('seller')->where("  status=0 and    user_id =" . $userInfo['id'] . "  AND   FROM_UNIXTIME(add_time,'%Y-%m')= FROM_UNIXTIME(unix_timestamp(now()),'%Y-%m') ")->count();
    if ($month_shop_count == null) {
        $month_shop_count = 0;
    }
    
    // 获取本月团队新增商户
    $month_team_shop_count = M('seller')->alias('t')
        ->join("xt_fck AS g ON   g.id = t.user_id     ", 'left')
        ->where(" t.status=0 and (g.re_path   like '%," . $userInfo['id'] . ",%'   OR  T.user_id =" . $userInfo['id'] . ")  AND   FROM_UNIXTIME(add_time,'%Y-%m')= FROM_UNIXTIME(unix_timestamp(now()),'%Y-%m') ")
        ->count();
    if ($month_team_shop_count == null) {
        $month_team_shop_count = 0;
    }
    
    // 获取直营商户总数量
    $shop_count = M('seller')->where("  status=0 and user_id =" . $userInfo['id'] . "   ")->count();
    if ($shop_count == null) {
        $shop_count = 0;
    }
    ;
    
    // 获取团队商户数量
    $team_shop_count = M('seller')->alias('t')
        ->join("xt_fck AS g ON   g.id = t.user_id     ", 'left')
        ->where(" t.status=0 and (g.re_path   like '%," . $userInfo['id'] . ",%'   OR  T.user_id=" . $userInfo['id'] . ")   ")
        ->count();
    if ($team_shop_count == null) {
        $team_shop_count = 0;
    }
    
    M('fck')->where('id=' . $userInfo['id'])->setField('team_shop_count', $team_shop_count);
    M('fck')->where('id=' . $userInfo['id'])->setField('month_shop_count', $month_shop_count);
    M('fck')->where('id=' . $userInfo['id'])->setField('shop_count', $shop_count);
    M('fck')->where('id=' . $userInfo['id'])->setField('month_team_shop_count', $month_team_shop_count);
    $ret = array();
    $ret['team_shop_count'] = $team_shop_count;
    $ret['month_shop_count'] = $month_shop_count;
    $ret['shop_count'] = $shop_count;
    $ret['month_team_shop_count'] = $month_team_shop_count;
    
    $begintime_str = date('Y-m-01', strtotime(date("Y-m-d")));
    $begintime = strtotime($begintime_str);
    $endtime_str = date('Y-m-d', strtotime("$begintime_str +1 month -1 day"));
    $endtime = strtotime($endtime_str);
    
    $ret['begintime'] = $begintime;
    $ret['endtime'] = $endtime;
    
    set_month_data($userInfo['id'], $begintime, $endtime, $ret);
}

function set_month_data($uid, $begintime, $endtime, $ret = array())
{
    $user = M('fck')->field('id, user_id,u_level,re_id,u_level')
        ->where('id  =' . $uid)
        ->find();
    
    $detail = M('user_detail')->where('uid=' . $uid . ' AND begintime=' . $begintime . ' AND endtime=' . $endtime)->find();
    if ($detail == null) {
        $data = array();
        $data['begintime'] = $begintime;
        $data['endtime'] = $endtime;
        $data['begintime_str'] = date('Y-m-d', $begintime);
        $data['endtime_str'] = date('Y-m-d', $endtime);
        $data['uid'] = $uid;
        $data['u_level'] = $user['u_level'];
        $data['add_time'] = time();
        M('user_detail')->add($data);
        $detail = M('user_detail')->where('uid=' . $uid . ' AND begintime=' . $begintime . ' AND endtime=' . $endtime)->find();
    }
    if ($detail != null) {
        $ret['u_level'] = $user['u_level'];
        $goods = M("user_detail");
        $goods->where('id=' . $detail['id'])
            ->data($ret)
            ->save();
    }
}

function set_user_trade_money($userInfo, $where = '', $begintime = '')
{
    $begintime_str = date('Y-m-01', strtotime(date("Y-m-d")));
    $monthtime_str = date('Y-m');
    IF (! EMPTY($begintime)) {
        
        $begintime_str = date('Y-m-01', $begintime);
        $monthtime_str = date('Y-m', $begintime);
    }
    
    // 获取团队交易总金额
    $team_trade_money = get_all_trade_money($userInfo['id'], $where);
    // 获取直营交易总金额
    $user_trade_money = get_trade_money($userInfo['id']);
    
    // 本月直营累计交易额
    $str = 'FROM_UNIXTIME(trade_time,"%Y-%m")="' . $monthtime_str . '" AND user_id=' . $userInfo['id'];
    
    $month_shop_trade_money = M('trade_orders')->where($str)->sum('trade_money');
    if ($month_shop_trade_money == null) {
        $month_shop_trade_money = 0;
    }
    // 本月团队商户交易额
    
    $str = 'FROM_UNIXTIME(t.trade_time,"%Y-%m")="' . $monthtime_str . '"  and (g.re_path like "%,' . $userInfo['id'] . ',%" )';
    
    $month_team_trade_money = M('trade_orders')->alias('t')
        ->join("xt_fck AS g ON   g.id = t.user_id  ", 'LEFT')
        ->where($str)
        ->sum('t.trade_money');
    if ($month_team_trade_money == null) {
        $month_team_trade_money = 0;
    }
    IF ($monthtime_str == date('Y-m')) {
        // $userInfo['month_shop_num'] = $month_shop_num;
        M('fck')->where('id=' . $userInfo['id'])->setField('team_trade_money', $team_trade_money);
        M('fck')->where('id=' . $userInfo['id'])->setField('user_trade_money', $user_trade_money);
        M('fck')->where('id=' . $userInfo['id'])->setField('month_shop_trade_money', $month_shop_trade_money);
        M('fck')->where('id=' . $userInfo['id'])->setField('month_team_trade_money', $month_team_trade_money);
    }
    // M('fck')->where('id='.$userInfo['id'])->setField('month_shop_num',$month_shop_num);
    // M('fck')->where('id='.$userInfo['id'])->setField('month_user_count',$month_user_count);
    
    // 本月团队商户交易额
    
    $str = 'FROM_UNIXTIME(t.trade_time,"%Y-%m")="' . $monthtime_str . '"  and (g.re_path like "%,' . $userInfo['id'] . ',%" OR  g.id=' . $userInfo['id'] . ')';
    
    $month_team_trade_money = M('trade_orders')->alias('t')
        ->join("xt_fck AS g ON   g.id = t.user_id  ", 'LEFT')
        ->where($str)
        ->sum('t.trade_money');
    if ($month_team_trade_money == null) {
        $month_team_trade_money = 0;
    }
    
    $ret = array();
    $ret['team_trade_money'] = $team_trade_money;
    $ret['user_trade_money'] = $user_trade_money;
    $ret['month_shop_trade_money'] = $month_shop_trade_money;
    $ret['month_team_trade_money'] = $month_team_trade_money;
    
    $begintime = strtotime($begintime_str);
    $endtime_str = date('Y-m-d', strtotime("$begintime_str +1 month -1 day"));
    $endtime = strtotime($endtime_str);
    
    $ret['begintime'] = $begintime;
    $ret['endtime'] = $endtime;
    
    set_month_data($userInfo['id'], $begintime, $endtime, $ret);
}

function get_user_info($userInfo, $user_id)
{
    $userInfo['out_money_value'] = $userInfo['out_money'];
    if ($userInfo['is_money'] == 0 && $userInfo['get_level'] > 0) {
        $userInfo['out_money'] = $userInfo['uLevel'] . '【' . $userInfo['cpzj'] . '】';
    }
    $userInfo['is_chuju'] = is_chuju($userInfo);
    $userInfo['is_today_fenhong'] = is_today_fenhong($userInfo);
    $userInfo['agent_use_txt'] = C('agent_use');
    $userInfo['buy_point_txt'] = C('buy_point');
    $userInfo['agent_kt_txt'] = C('agent_kt');
    $userInfo['limit_money_txt'] = C('limit_money');
    
    $userInfo['today_time'] = date("Y-m-d");
    $shop = M('fck')->field('id,  app_logo ,userinfo_logo')
        ->where('id=' . $userInfo['shop_id'])
        ->find();
    if ($shop == NULL) {
        $shop['app_logo'] = '';
    }
    $userInfo['shop'] = $shop;
    $order_num1 = M('order_read')->alias('t')
        ->join("xt_orders AS g ON   g.id = t.order_id ", 'LEFT')
        ->where('t.user_id=' . $userInfo['id'] . ' and t.is_read=0   and  g.payment_status=1 and    g.status=1  ')
        ->count();
    // 待发货
    $order_num2 = M('order_read')->alias('t')
        ->join("xt_orders AS g ON   g.id = t.order_id ", 'LEFT')
        ->where('t.user_id=' . $userInfo['id'] . ' and t.is_read=0  and  g.express_status=1 and    g.status=2    ')
        ->count();
    // 待收货
    $order_num3 = M('order_read')->alias('t')
        ->join("xt_orders AS g ON   g.id = t.order_id ", 'LEFT')
        ->where('t.user_id=' . $userInfo['id'] . ' and t.is_read=0  and  g.express_status=2 and    g.status=2')
        ->count();
    // 已完成
    $order_num4 = M('order_read')->alias('t')
        ->join("xt_orders AS g ON   g.id = t.order_id ", 'LEFT')
        ->where('t.user_id=' . $userInfo['id'] . '  and t.is_read=0 and  g.status=4 ')
        ->count();
    $userInfo['order_num1'] = ($order_num1);
    $userInfo['order_num2'] = ($order_num2);
    $userInfo['order_num3'] = ($order_num3);
    $userInfo['order_num4'] = ($order_num4);
    // 待付款数量
    // $order_num1 = M('orders')->where('user_id=' . $userInfo['id'] . ' AND order_type=0 and status=1 and payment_status=0')->count();
    
    // // 待发货数量
    // $order_num2 = M('orders')->where('user_id=' . $userInfo['id'] . ' AND order_type=0 and status=2 and express_status=1')->count();
    
    // // 待收货数量
    // $order_num3 = M('orders')->where('user_id=' . $userInfo['id'] . ' AND order_type=0 and status=2 and express_status=2')->count();
    // // 退换货数量
    // $order_num5 = M('orders')->where('user_id=' . $userInfo['id'] . ' AND order_type=0 and status=6 AND back_status=1 ')->count();
    
    // $userInfo['order_num1'] = $order_num1;
    // $userInfo['order_num2'] = $order_num2;
    // $userInfo['order_num3'] = $order_num3;
    // $userInfo['order_num5'] = $order_num5;
    
    // 待付款数量
    // $shop_order_num1 = M('orders')->where('user_id=' . $userInfo['id'] . ' AND order_type=1 and status=1 and payment_status=0')->count();
    
    // // 待发货数量
    // $shop_order_num2 = M('orders')->where('user_id=' . $userInfo['id'] . ' AND order_type=1 and status=2 and express_status=1')->count();
    
    // // 待收货数量
    // $shop_order_num3 = M('orders')->where('user_id=' . $userInfo['id'] . ' AND order_type=1 and status=2 and express_status=2')->count();
    // // 退换货数量
    // $shop_order_num5 = M('orders')->where('user_id=' . $userInfo['id'] . ' AND order_type=1 and status=6 AND back_status=1 ')->count();
    
    // $userInfo['shop_order_num1'] = $shop_order_num1;
    // $userInfo['shop_order_num2'] = $shop_order_num2;
    // $userInfo['shop_order_num3'] = $shop_order_num3;
    // $userInfo['shop_order_num5'] = $shop_order_num5;
    
    $article_category = M('article_category');
    
    $category_ids = $userInfo['category_ids'];
    $category_title = '';
    if (! EMPTY($category_ids)) {
        $category_ids = explode(',', $category_ids);
        foreach ($category_ids as $k => $item) {
            $category = $article_category->field('id,title')
                ->where('  id=' . $item)
                ->find();
            $userInfo['category' . $k] = $category;
            $category_title = $category_title . $category['title'] . ' ';
            $userInfo['category_title'] = $category_title;
        }
    }
    $newstr = '';
    $auth_error = '';
    $auth_check_status = $userInfo['auth_check_status'];
    $auth_check_label = $userInfo['auth_check_label'];
    $auth_check_label = explode("|", $auth_check_label);
    if (! EMPTY($auth_check_status)) {
        
        $auth_check_status = explode("|", $auth_check_status);
        foreach ($auth_check_status as $k => $item) {
            if ($item == 1) {
                $auth_error = $auth_error . $auth_check_label[$k] . '不通过,';
            }
        }
    }
    if (! EMPTY($auth_error)) {
        $newstr = substr($auth_error, 0, strlen($auth_error) - 1);
    }
    $userInfo['auth_error'] = $newstr;
    // 获取总收益
    $all_epoints = M('history')->where(" bz   like '%pos分润%'   and uid=" . $userInfo['id'] . " AND epoints>0")->sum('epoints');
    if ($all_epoints == null) {
        $all_epoints = 0;
    }
    
    $userInfo['all_epoints'] = $all_epoints;
    // 获取当天收益
    $today_epoints = M('history')->where(" bz   like '%pos分润%' and uid=" . $userInfo['id'] . " AND epoints>0 AND FROM_UNIXTIME(pdt,'%Y-%m-%d')= FROM_UNIXTIME(unix_timestamp(now()),'%Y-%m-%d') ")->sum('epoints');
    if ($today_epoints == null) {
        $today_epoints = 0;
    }
    $userInfo['today_epoints'] = $today_epoints;
    // 获取昨日收益
    $yestoday_epoints = M('history')->where(" bz   like '%pos分润%' and uid=" . $userInfo['id'] . " AND epoints>0 AND FROM_UNIXTIME(pdt,'%Y-%m-%d')=  DATE_SUB(curdate(),INTERVAL 1 DAY) ")->sum('epoints');
    if ($yestoday_epoints == null) {
        $yestoday_epoints = 0;
    }
    $userInfo['yestoday_epoints'] = $yestoday_epoints;
    
    // 获取头条新闻
    $form = M('form')->field('id,title')
        ->alias('t')
        ->where(" t.type=1  and t.user_id=0   ")
        ->select();
    
    $userInfo['form'] = $form;
    
    $userInfo['auth_status_str'] = get_auth_status($userInfo);
    
    $str = '';
    IF (! EMPTY($userInfo['seller_sn'])) {
        $str = ' AND sn not in (' . $userInfo['seller_sn'] . ') ';
    }
    // 获取头条新闻
    $form = M('user_terminal')->field('id,sn,sn_type,terminal_type,huikui_id')
        ->where("   uid=" . $userInfo['id'] . "  " . $str)
        ->select();
    
    $userInfo['user_terminal'] = $form;
    
    return $userInfo;
}

function get_trade_money($id)
{
    $trade_money = M('trade_orders')->alias('t')
        ->where("    T.user_id =  " . $id . "        ")
        ->sum('T.trade_money');
    if ($trade_money == null) {
        $trade_money = 0;
    }
    
    return $trade_money;
}

function get_all_trade_money($id, $where = '')
{
    $all_trade_money = M('orders')->alias('t')
        ->join("xt_fck AS g ON   g.id = t.user_id     ", 'left')
        ->where("  (  g.re_path like '%," . $id . ",%' OR   t.user_id =  " . $id . "   )    " . $where)
        ->sum('T.trade_money');
    if ($all_trade_money == null) {
        $all_trade_money = 0;
    }
    
    RETURN $all_trade_money;
}

function get_auth_status($userInfo)
{
    $str = '';
    if ($userInfo['auth_status'] == 0 && $userInfo['u_level'] != 3) {
        $str = '完善资料';
    }
    if ($userInfo['auth_status'] == 0 && $userInfo['u_level'] == 3) {
        $str = '已审核';
    }
    if ($userInfo['auth_status'] == 1) {
        
        $str = '等待审核';
    }
    if ($userInfo['auth_status'] == 2) {
        
        $str = '认证失败';
    }
    return $str;
}

function get_seller_info($userInfo)
{
    $newstr = '';
    $auth_error = '';
    $auth_check_status = $userInfo['auth_check_status'];
    $auth_check_label = $userInfo['auth_check_label'];
    $auth_check_label = explode("|", $auth_check_label);
    if (! EMPTY($auth_check_status)) {
        
        $auth_check_status = explode("|", $auth_check_status);
        foreach ($auth_check_status as $k => $item) {
            if ($item == 1) {
                $auth_error = $auth_error . $auth_check_label[$k] . '不通过,';
            }
        }
    }
    if (! EMPTY($auth_error)) {
        $newstr = substr($auth_error, 0, strlen($auth_error) - 1);
    }
    $userInfo['auth_error'] = $newstr;
    $all_money = 0;
    $all_month_money = 0;
    $map = 'shop_id="' . $userInfo['seller_no'] . '"';
    $field = ' ';
    
    $map['_string'] = "  ( t.uid=" . $userInfo['id'] . "  ) and  t.bz like '%分润%'  and   FROM_UNIXTIME(t.pdt, '%Y-%m-%d')  = '" . $value['time'] . "'";
    
    $all_money = M('history')->alias('t')
        ->join("xt_trade_orders AS g ON   g.id = t.project_id ", 'LEFT')
        ->join("xt_fck AS h ON   h.id = t.uid ", 'LEFT')
        ->where($map)
        ->sum('sum(t.epoints) as real_fen_money');
    if ($all_money == NULL) {
        $all_money = 0;
    }
    $userInfo['all_money'] = $all_money;
    $map = $map . ' AND DATE_FORMAT(FROM_UNIXTIME(trade_time, "%Y-%m-%d"), "%Y%m" )=DATE_FORMAT( CURDATE( ) , "%Y%m" )';
    
    $all_month_money = M('trade_orders')->where($map)->sum('trade_money');
    if ($all_month_money == NULL) {
        $all_month_money = 0;
    }
    $userInfo['all_month_money'] = $all_month_money;
    $userInfo['month_trade_money'] = $all_month_money;
    
    $map = 'shop_id="' . $userInfo['seller_no'] . '"';
    
    $map = $map . ' AND  DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(FROM_UNIXTIME(trade_time, "%Y-%m-%d"))';
    
    $week_trade_money = M('trade_orders')->where($map)->sum('trade_money');
    if ($week_trade_money == NULL) {
        $week_trade_money = 0;
    }
    $userInfo['week_trade_money'] = $week_trade_money;
    
    $map = 'shop_id="' . $userInfo['seller_no'] . '"';
    
    $map = $map . ' AND  FROM_UNIXTIME(trade_time, "%Y-%m-%d")  =FROM_UNIXTIME(unix_timestamp(now()), "%Y-%m-%d") ';
    
    $day_trade_money = M('trade_orders')->where($map)->sum('trade_money');
    if ($day_trade_money == NULL) {
        $day_trade_money = 0;
    }
    $userInfo['day_trade_money'] = $day_trade_money;
    
    return $userInfo;
}

function get_my_join_project_map($map, $user_id, $type)
{
    IF ($type == 2) {
        $map['status'] = 1;
        
        $join_ids = '';
        $join_id = M('project_user_albums')->where('user_id=' . $user_id)
            ->field('item_id')
            ->select();
        IF (COUNT($join_id) > 0) {
            FOR ($i = 0; $i < COUNT($join_id); $i ++) {
                
                $join_ids = $join_ids . $join_id[$i]['item_id'] . ',';
            }
        }
        $join_ids = $join_ids . '0';
        $map['id'] = array(
            'in',
            $join_ids
        );
    }
    IF ($type == 3) {
        $map['status'] = 2;
        $join_ids = '';
        $join_id = M('project_user_albums')->where('user_id=' . $user_id)
            ->field('item_id')
            ->select();
        IF (COUNT($join_id) > 0) {
            FOR ($i = 0; $i < COUNT($join_id); $i ++) {
                
                $join_ids = $join_ids . $join_id[$i]['item_id'] . ',';
            }
            $join_ids = $join_ids . '0';
            $map['id'] = array(
                'in',
                $join_ids
            );
        }
    }
    return $map;
}

// region 发送手机短信验证码OK===========================
function send_verify_sms_code($mobile, $TemplateCode)
{
    // 检查手机
    if (empty($mobile)) {
        return "{\"status\":0 ,\"info\":\"发送失败，请填写手机号码！\"}";
    }
    // 检查是否过期
    $cookie = session(C('COOKIE_USER_MOBILE'));
    $user_code = M('user_code');
    
    $sms_template = M('sms_template')->where(array(
        'template_id' => array(
            'eq',
            $TemplateCode
        )
    ))->find();
    
    if ($sms_template == null) {
        
        // return "{\"status\":0 ,\"info\":\"发送失败，短信模板不存在，请联系管理员！\"}";
    }
    $usercode = $user_code->where("user_name='" . $mobile . "' AND type='" . $sms_template['call_index'] . "'")
        ->order('id desc')
        ->find();
    
    if ($usercode != null) {
        $now = time();
        if ($usercode['eff_time'] > $now) {
            
            return "{\"status\":0,\"time\":" . C('regsmsexpired') . ",\"info\":\"已发送短信，" . C('regsmsexpired') . "分钟后再试！\"}";
        }
    }
    
    $strcode = GetRandStr(6); // 随机验证码
                              // 替换标签
    $msgContent = $sms_template['content'];
    $msgContent = str_replace("{webname}", __ROOT__, $msgContent);
    $msgContent = str_replace("{weburl}", C('weburl'), $msgContent);
    $msgContent = str_replace("{code}", $strcode, $msgContent);
    $msgContent = str_replace("{valid}", C('regsmsexpired'), $msgContent);
    // 发送短信
    $tipMsg = '';
    $is_lock = $sms_template['is_lock'];
    
    $ret = _sendaliyu_SMS($sms_template, $mobile, $strcode, $TemplateCode, $tipMsg);
    if ($ret == 'false') {
        return "{\"status\":0, \"info\":\"发送失败，" . $tipMsg . "\"}";
    }
    // 写入SESSION，保存验证码
    session(C('SESSION_SMS_CODE'), $strcode);
    session(C('COOKIE_USER_MOBILE'), $mobile);
    // 规定时间内无重复发送
    return "success";
}

// endregion
function _sendSMS($content, $user_tel, &$tipMsg, $is_lock, $sms_template, $strcode)
{
    $System_sign = C('System_sign'); //
    
    import("@.ORG.phpSMS.QsendSMS");
    $sendSMS = new sendSMS();
    $content_post = $content;
    $content1 = "" . $content_post;
    $contentUrlEncode = ($content1); //
    
    if (empty($content_post)) {
        return "{\"status\":0, \"msg\":\"内容不能为空\"}";
    }
    
    $mobile = $user_tel;
    if ($is_lock == 0) {
        $params = ARRAY(
            
            $strcode,
            C('regsmsexpired')
        );
        
        $result = $sendSMS->sendWithParam('86', $mobile, '222783', $params); // 进行发送
        if (strstr($result, "OK")) {
            $now = time();
            $regsmsexpired = C('regsmsexpired');
            $eff_time = strtotime('+' . $regsmsexpired . ' minute');
            $user_code = M('user_code');
            $data['user_id'] = 0;
            $data['user_name'] = $user_tel;
            $data['eff_time'] = $eff_time;
            $data['add_time'] = time();
            $data['count'] = 0;
            $data['status'] = 1;
            $data['str_code'] = $strcode;
            $data['type'] = $sms_template['call_index'];
            $user_code->add($data);
            return "success";
        } else {
            $tipMsg = $result;
            return "false";
        }
    } else {
        
        return "false";
    }
}

// endregion
function _sendaliyu_SMS($sms_template, $user_tel, $strcode, $TemplateCode, &$tipMsg)
{
    $System_sign = C('System_sign'); //
    
    import("@.ORG.aliyun_sms.SmsDemo");
    $sendSMS = new SmsDemo();
    
    $result = $sendSMS->sendSms($user_tel, $System_sign, $strcode, $TemplateCode); // 进行发送
    if ($result->Code == "OK") {
        $now = time();
        $regsmsexpired = C('regsmsexpired');
        $eff_time = strtotime('+' . $regsmsexpired . ' minute');
        $user_code = M('user_code');
        $data['user_id'] = 0;
        $data['user_name'] = $user_tel;
        $data['eff_time'] = $eff_time;
        $data['add_time'] = time();
        $data['count'] = 0;
        $data['status'] = 1;
        $data['str_code'] = $strcode;
        $data['type'] = $sms_template['call_index'];
        $user_code->add($data);
        return "success";
    } else {
        $tipMsg = $result->Message;
        return "false";
    }
}

// region 发送手机短信验证码OK===========================
function test_sms_code($mobile)
{
    // 检查手机
    if (empty($mobile)) {
        return "{\"status\":0 ,\"info\":\"发送失败，请填写手机号码！\"}";
    }
    // 检查是否过期
    $cookie = session(C('COOKIE_USER_MOBILE'));
    $user_code = M('user_code');
    
    $sms_template = M('sms_template')->where(array(
        'id' => array(
            'eq',
            '1'
        )
    ))->find();
    
    if ($sms_template == null) {
        
        // return "{\"status\":0 ,\"info\":\"发送失败，短信模板不存在，请联系管理员！\"}";
    }
    $usercode = $user_code->where("user_name='" . $mobile . "' AND type='" . $sms_template['call_index'] . "'")
        ->order('id desc')
        ->find();
    
    if ($usercode != null) {
        $now = time();
        if ($usercode['eff_time'] > $now) {
            
            return "{\"status\":0,\"time\":" . C('regsmsexpired') . ",\"info\":\"已发送短信，" . C('regsmsexpired') . "分钟后再试！\"}";
        }
    }
    
    $strcode = GetRandStr(4); // 随机验证码
                              
    // 发送短信
    $tipMsg = '';
    $is_lock = $sms_template['is_lock'];
    $TemplateCode = C('ali_sms_tid');
    $ret = _sendaliyu_SMS($sms_template, $mobile, $strcode, $TemplateCode, $tipMsg);
    if ($ret == 'false') {
        
        $ret = "{\"status\":\"0\", \"info\":\"发送失败," . $tipMsg . "\"}";
        return $ret;
    }
    // 写入SESSION，保存验证码
    session(C('SESSION_SMS_CODE'), $strcode);
    session(C('COOKIE_USER_MOBILE'), $mobile);
    // 规定时间内无重复发送
    return "success";
}

function GetRandStr($length)
{
    // 密码字符集，可任意添加你需要的字符
    $chars = array(
        
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9'
    );
    // 在 $chars 中随机取 $length 个数组元素键名
    $keys = array_rand($chars, $length);
    $password = '';
    for ($i = 0; $i < $length; $i ++) {
        // 将 $length 个数组元素连接成字符串
        $password .= $chars[$keys[$i]];
    }
    return $password;
}

function login_recommend_award($authInfo, $money)
{
    $fck = D('Fck');
    $re = $fck->where('id=' . $authInfo['re_id'])
        ->field('id,user_id')
        ->find();
    
    if ($re != NULL) {
        
        $item = M('history')->where("uid=" . $re['id'] . " AND ( bz like '%推荐" . $authInfo['user_id'] . "%'  OR  bz like '%推荐" . $authInfo['id'] . "%')")
            ->field('id')
            ->find();
        if ($item == NULL) {
            $result = $fck->where('id=' . $re['id'])->setInc('agent_kt', $money);
            $kt_cont = "推荐" . $authInfo['id'];
            $fck->addencAdd($re['id'], $re['user_id'], $money, 19, 0, 0, 0, $kt_cont . '-获得' . C('agent_kt') . $money, 0); // 激活积分扣除历史记录
        }
    }
}

function varify_url($url)
{
    $check = @fopen($url, "r");
    if ($check) {
        $status = true;
    } else {
        $status = false;
    }
    return $status;
}

function httpcode($url)
{
    $ch = curl_init();
    $timeout = 3;
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    file_put_contents("httpcode.txt", $httpcode, FILE_APPEND);
    
    if ($httpcode == 200 || $httpcode == 301 || $httpcode == 302 || $httpcode == 403 || strpos($url, 'xinggoubuy') !== false) {
        $status = true;
    } else {
        $status = false;
    }
    curl_close($ch);
    return $status;
}

function getui_update_project_count()
{
    import("@.ORG.getui.GeTui");
    // if (! strstr($HTTP_HOST, "localhost") && ! strstr($HTTP_HOST, "127.0.0.1")) {
    $gt = new GeTui();
    $type = "project";
    // 已审核完成
    $complete_user_count = M('project_users')->where('STATUS=0 ')->count();
    
    $content = "{\"type\":\"{$type}\",\"tixian_money\":\"{$complete_user_count}\"}";
    $gt->pushMessageToApp($content);
    
    // } else {
    // $is_ok = 0;
    // }
}

function getui_update_tixian_list()
{
    import("@.ORG.getui.GeTui");
    // if (! strstr($HTTP_HOST, "localhost") && ! strstr($HTTP_HOST, "127.0.0.1")) {
    $gt = new GeTui();
    $type = "tiqu";
    $content = array();
    $content['type'] = $type;
    // $content['content']=$list;
    // $content['tixian_money']=$type;
    // $content['tixian_user_name']=$type;
    
    $gt->pushMessageToApp(json_encode($content));
    
    // } else {
    // $is_ok = 0;
    // }
}

function get_lirun($user, $money)
{
    $fee = M('fee');
    $fee_rs = $fee->find(1);
    
    $user_money = explode('|', $fee_rs['user_money']);
    
    $money_percent = $user_money[0];
    
    $money = $money_percent * $money * 0.0001;
    
    return $money;
}

function get_tiqu_list()
{
    $tiqu = M('tiqu')->where('is_pay=1')
        ->order(' rdt DESC ')
        ->select();
    foreach ($tiqu as $key => $value) {
        
        $fck = M('fck')->field('wx_nickname')
            ->where(' id= ' . $value['uid'])
            ->find();
        $tiqu[$key]['nickname'] = $fck['wx_nickname'];
        $tiqu[$key]['time'] = date("H:i", $value['rdt']);
    }
    return $tiqu;
}

/**
 * * 读取excel转换成数组
 * *
 * * @param string $excelFile 文件路径
 * * @param int $startRow 开始读取的行数
 * * @param int $endRow 结束读取的行数
 * * @return array
 */
function readFromExcel($excelFile, $startRow = 1, $endRow = 1000)
{
    import("@.ORG.PHPExcel.PHPExcel");
    // import("@.ORG.PHPExcel.PHPExcel.Writer.Excel5", '', '.php');
    import("@.ORG.PHPExcel.PHPExcel.IOFactory", '', '.php');
    import("@.ORG.PHPExcel.PHPExcel.PHPExcelReadFilter", '', '.php');
    $excelType = \PHPExcel_IOFactory::identify($excelFile);
    $excelReader = \PHPExcel_IOFactory::createReader($excelType);
    
    if (strtoupper($excelType) == 'CSV') {
        $excelReader->setInputEncoding('GBK');
    }
    
    if ($startRow && $endRow) {
        $excelFilter = new \PHPExcelReadFilter();
        $excelFilter->startRow = $startRow;
        $excelFilter->endRow = $endRow;
        $excelReader->setReadFilter($excelFilter);
    }
    
    $phpexcel = $excelReader->load($excelFile);
    $activeSheet = $phpexcel->getActiveSheet();
    $highestColumn = $activeSheet->getHighestColumn(); // 最后列数所对应的字母，例如第1行就是A
    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn); // 总列数
    
    $data = array();
    for ($row = $startRow; $row <= $endRow; $row ++) {
        for ($col = 0; $col < $highestColumnIndex; $col ++) {
            
            if ($col == 2) {
                if (stripos($activeSheet->getCellByColumnAndRow($col, $row)->getValue(), 'e') === false) {
                    $data[$row][] = (string) ($activeSheet->getCellByColumnAndRow($col, $row)->getValue()) . '';
                } else {
                    
                    $data[$row][] = (string) number_format($activeSheet->getCellByColumnAndRow($col, $row)->getValue(), 0, '', '') . '';
                }
            } else {
                
                $data[$row][] = (string) ($activeSheet->getCellByColumnAndRow($col, $row)->getValue()) . '';
            }
        }
        if (implode($data[$row], '') == '') {
            unset($data[$row]);
        }
    }
    return $data;
}

function NumToStr($num)
{
    if (stripos($num, 'e') === false)
        return $num;
    $num = trim(preg_replace('/[=\'"]/', '', $num, 1), '"'); // 出现科学计数法，还原成字符串
    $result = "";
    while ($num > 0) {
        $v = $num - floor($num / 10) * 10;
        $num = floor($num / 10);
        $result = $v . $result;
    }
    return $result;
}

/**
 * Created by PhpStorm.
 * User: 飞
 * Date: 2017/6/13
 * Time: 10:20
 * 导入excel文件，对表格进行解析
 */
function importExecl($file, $filetype)
{
    if (! file_exists($file)) {
        return array(
            "error" => 0,
            'message' => 'file not found!'
        );
    }
    // 判断文档类型，使用相应的方法，可以解析多种文件，这只是判断两个，其余的自己判断
    if ($filetype == 'xlsx') {
        $filetype = 'Excel2007';
    } elseif ($filetype == 'xls') {
        $filetype = 'Excel5';
    }
    import("@.ORG.PHPExcel.PHPExcel");
    // import("@.ORG.PHPExcel.PHPExcel.Writer.Excel5", '', '.php');
    import("@.ORG.PHPExcel.PHPExcel.IOFactory", '', '.php');
    $objReader = \PHPExcel_IOFactory::createReader($filetype);
    $objReader->setReadDataOnly(TRUE);
    
    try {
        $PHPReader = $objReader->load($file);
    } catch (Exception $e) {
        return array(
            "error" => 0,
            'message' => $e['msg']
        );
    }
    if (! isset($PHPReader))
        return array(
            "error" => 0,
            'message' => 'read error!'
        );
    // 获得所有的sheets表格
    $allWorksheets = $PHPReader->getAllSheets();
    $i = 0;
    // 对sheet表格遍历分析
    foreach ($allWorksheets as $objWorksheet) {
        // 获得sheet表格的标题
        $sheetname = $objWorksheet->getTitle();
        // 获得总行数
        $allRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        // 获得总列数
        $allColumn = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $array[$i]["Title"] = $sheetname;
        $array[$i]["Cols"] = $allColumn;
        $array[$i]["Rows"] = $allRow;
        $arr = array();
        // 对合并的单元格进行分析
        $isMergeCell = array();
        foreach ($objWorksheet->getMergeCells() as $cells) { // merge cells
            foreach (\PHPExcel_Cell::extractAllCellReferencesInRange($cells) as $cellReference) {
                $isMergeCell[$cellReference] = true;
            }
        }
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow ++) {
            $row = array();
            for ($currentColumn = 0; $currentColumn < $allColumn; $currentColumn ++) {
                ;
                $cell = $objWorksheet->getCellByColumnAndRow($currentColumn, $currentRow);
                $afCol = \PHPExcel_Cell::stringFromColumnIndex($currentColumn + 1);
                $bfCol = \PHPExcel_Cell::stringFromColumnIndex($currentColumn - 1);
                $col = \PHPExcel_Cell::stringFromColumnIndex($currentColumn);
                $address = $col . $currentRow;
                $value = $objWorksheet->getCell($address)->getValue();
                if (substr($value, 0, 1) == '=') {
                    return array(
                        "error" => 0,
                        'message' => 'can not use the formula!'
                    );
                    exit();
                }
                if ($cell->getDataType() == \PHPExcel_Cell_DataType::TYPE_NUMERIC) {
                    // $cellstyleformat=$cell->getParent()->getStyle( $cell->getCoordinate() )->getNumberFormat();
                    // $formatcode=$cellstyleformat->getFormatCode();
                    if (preg_match('/^([$[A-Z]*-[0-9A-F]*])*[hmsdy]/i', $formatcode)) {
                        $value = gmdate("Y-m-d", \PHPExcel_Shared_Date::ExcelToPHP($value));
                    } else {
                        $value = \PHPExcel_Style_NumberFormat::toFormattedString($value, $formatcode);
                    }
                }
                if ($isMergeCell[$col . $currentRow] && $isMergeCell[$afCol . $currentRow] && ! empty($value)) {
                    $temp = $value;
                } elseif ($isMergeCell[$col . $currentRow] && $isMergeCell[$col . ($currentRow - 1)] && empty($value)) {
                    $value = $arr[$currentRow - 1][$currentColumn];
                } elseif ($isMergeCell[$col . $currentRow] && $isMergeCell[$bfCol . $currentRow] && empty($value)) {
                    $value = $temp;
                }
                $row[$currentColumn] = $value;
            }
            $arr[$currentRow] = $row;
        }
        $array[$i]["Content"] = $arr;
        $i ++;
    }
    // spl_autoload_register('Think');//must, resolve ThinkPHP and PHPExcel conflicts
    unset($objWorksheet);
    unset($PHPReader);
    unset($PHPExcel);
    unlink($file);
    return array(
        "error" => 1,
        "data" => $array
    );
}

function get_img_url($path)
{
    if (strpos($path, '__PUBLIC__') === false) {
        $path = '__PUBLIC__/' . strstr($path, 'Upload');
    } else {}
    
    $path = str_replace('__PUBLIC__/', '/Public/', $path);
    
    $attach3 = explode("Upload", $path);
    if (count($attach3) > 1) {} else {
        
        $path = '';
    }
    
    return $path;
}

function update_auth_status($id)
{
    $fck = M('fck');
    $user = $fck->where('id=' . $id)->find();
    $auth_error = '';
    $auth_check_status = $user['auth_check_status'];
    $auth_check_label = $user['auth_check_label'];
    $auth_check_label = explode("|", $auth_check_label);
    if (! EMPTY($auth_check_status)) {
        
        $auth_check_status = explode("|", $auth_check_status);
        foreach ($auth_check_status as $k => $item) {
            if ($item == 1) {
                $auth_check_status[$k] = 0;
                $auth_error = $auth_error . $auth_check_label[$k];
            }
        }
        
        if (EMPTY($auth_error)) {
            $user['auth_status'] = 1;
            
            $fck->where('id=' . $id)->setField('auth_status', 1);
        }
    }
}

function update_seller_auth_status($id)
{
    $fck = M('seller');
    $user = $fck->where('id=' . $id)->find();
    $auth_error = '';
    $auth_check_status = $user['auth_check_status'];
    $auth_check_label = $user['auth_check_label'];
    $auth_check_label = explode("|", $auth_check_label);
    if (! EMPTY($auth_check_status)) {
        
        $auth_check_status = explode("|", $auth_check_status);
        foreach ($auth_check_status as $k => $item) {
            if ($item == 1) {
                $auth_check_status[$k] = 0;
                $auth_error = $auth_error . $auth_check_label[$k];
            }
        }
        
        if (EMPTY($auth_error)) {
            $user['auth_status'] = 1;
            
            $fck->where('id=' . $id)->setField('auth_status', 1);
            $fck->where('id=' . $id)->setField('status', 1);
        }
    }
}

function Getzimu($str)
{
    $str = iconv("UTF-8", "gb2312", $str); // 如果程序是gbk的，此行就要注释掉
    if (preg_match("/^[\x7f-\xff]/", $str)) {
        $fchar = ord($str{0});
        if ($fchar >= ord("A") and $fchar <= ord("z"))
            return strtoupper($str{0});
        $a = $str;
        $val = ord($a{0}) * 256 + ord($a{1}) - 65536;
        if ($val >= - 20319 and $val <= - 20284)
            return "A";
        if ($val >= - 20283 and $val <= - 19776)
            return "B";
        if ($val >= - 19775 and $val <= - 19219)
            return "C";
        if ($val >= - 19218 and $val <= - 18711)
            return "D";
        if ($val >= - 18710 and $val <= - 18527)
            return "E";
        if ($val >= - 18526 and $val <= - 18240)
            return "F";
        if ($val >= - 18239 and $val <= - 17923)
            return "G";
        if ($val >= - 17922 and $val <= - 17418)
            return "H";
        if ($val >= - 17417 and $val <= - 16475)
            return "J";
        if ($val >= - 16474 and $val <= - 16213)
            return "K";
        if ($val >= - 16212 and $val <= - 15641)
            return "L";
        if ($val >= - 15640 and $val <= - 15166)
            return "M";
        if ($val >= - 15165 and $val <= - 14923)
            return "N";
        if ($val >= - 14922 and $val <= - 14915)
            return "O";
        if ($val >= - 14914 and $val <= - 14631)
            return "P";
        if ($val >= - 14630 and $val <= - 14150)
            return "Q";
        if ($val >= - 14149 and $val <= - 14091)
            return "R";
        if ($val >= - 14090 and $val <= - 13319)
            return "S";
        if ($val >= - 13318 and $val <= - 12839)
            return "T";
        if ($val >= - 12838 and $val <= - 12557)
            return "W";
        if ($val >= - 12556 and $val <= - 11848)
            return "X";
        if ($val >= - 11847 and $val <= - 11056)
            return "Y";
        if ($val >= - 11055 and $val <= - 10247)
            return "Z";
    } else {
        return false;
    }
}

function set_out_terminal_ids($user_id)
{
    $join_id = M('user_terminal_out')->where('uid=' . $user_id . ' AND (status=1 OR STATUS=0) and type=0  ')
        ->field('sn')
        ->select();
    $join_ids = '';
    IF (count($join_id) > 0) {
        FOR ($i = 0; $i < COUNT($join_id); $i ++) {
            
            $where['sn'] = array(
                'like',
                '%' . $join_id[$i]['sn'] . '%'
            );
            $where['status'] = array(
                'eq',
                0
            );
            $check_count = M('user_terminal_back')->where($where)->count();
            
            if ($check_count == 0) {
                
                $join_ids = $join_ids . '"' . $join_id[$i]['sn'] . '",';
            }
        }
        $join_ids = $join_ids . '"0"';
    }
    M('fck')->where('id=' . $user_id)->setField('out_terminal_sn', $join_ids);
}

function set_seller_sns($user_id)
{
    $sn_id = M('seller')->where('user_id=' . $user_id . ' AND sn is not null AND status=0 ')
        ->field('sn')
        ->select();
    $sn_ids = '';
    IF (count($sn_id) > 0) {
        FOR ($i = 0; $i < COUNT($sn_id); $i ++) {
            $sn_ids = $sn_ids . '"' . trim($sn_id[$i]['sn']) . '",';
        }
        $sn_ids = $sn_ids . '"0"';
    }
    M('fck')->where('id=' . $user_id)->setField('seller_sn', $sn_ids);
}

function set_terminal_sns($user_id)
{
    $join_id = M('user_terminal')->where('uid=' . $user_id . '  ')
        ->field('sn')
        ->select();
    $join_ids = '';
    IF (count($join_id) > 0) {
        FOR ($i = 0; $i < COUNT($join_id); $i ++) {
            // $shop_count = M('seller')->where(ARRAY(
            // 'sn' => trim($join_id[$i]['sn'])
            // ))->count();
            
            // if ($shop_count==0) {
            $join_ids = $join_ids . '"' . trim($join_id[$i]['sn']) . '",';
            // }
        }
        $join_ids = $join_ids . '"0"';
    }
    $month_terminal_active_count = M('user_terminal')->where('uid=' . $user_id . ' and trade_money>= min_fan_money   AND   FROM_UNIXTIME(add_time,"%Y-%m")= FROM_UNIXTIME(unix_timestamp(now()),"%Y-%m")  ')->count();
    $month_terminal_count = M('user_terminal')->where('uid=' . $user_id . '  AND   FROM_UNIXTIME(add_time,"%Y-%m")= FROM_UNIXTIME(unix_timestamp(now()),"%Y-%m")    ')->count();
    $terminal_active_count = M('user_terminal')->where('uid=' . $user_id . '    ')->count();
    $terminal_count = M('user_terminal')->where('uid=' . $user_id . '      ')->count();
    M('fck')->where('id=' . $user_id)->setField('terminal_sn', $join_ids);
    M('fck')->where('id=' . $user_id)->setField('month_terminal_active_count', $month_terminal_active_count);
    M('fck')->where('id=' . $user_id)->setField('month_terminal_count', $month_terminal_count);
    M('fck')->where('id=' . $user_id)->setField('terminal_active_count', $terminal_active_count);
    M('fck')->where('id=' . $user_id)->setField('terminal_count', $terminal_count);
}

function get_expire_status_str($vo)
{
    $str = '';
    $now = time();
    $ff = timediff($now, $vo['expire_time']);
    
    if ($vo['min_fan_money'] > $vo['trade_money']) {
        $str = '未激活';
        if ($vo['expire_time'] > 0) {
            if ($now < $vo['expire_time']) {
                $str = '还剩' . $ff['day'] . '天';
            }
            if ($now >= $vo['expire_time']) {
                $str = '逾期' . $ff['day'] . '天';
            }
        }
        if ($vo['order_update'] == 0 && ! EMPTY($vo['order_no'])) {
            
            $str = '虚拟';
        }
    } else {
        $str = '已激活';
    }
    return $str;
}

function get_terminal_active_status_str($vo)
{
    $str = '未激活';
    $now = time();
    
    if ($vo['min_fan_money'] > $vo['trade_money']) {} else {
        $str = '已激活';
    }
    return $str;
}

// 返现
function back_pos_money($money, $seller_id, $trade_id)
{
    IF ($seller_id == '8622319180') {
        $KK = 0;
    }
    
    $shop = M('seller')->where(array(
        'seller_no' => $seller_id
    ))->find();
    $fee_rs = M('fee')->field('str29,bankcard,bankusername,jq_shui')->find();
    $jq_shui = explode('|', $fee_rs['jq_shui']);
    if ($shop != null) {
        $user_terminal = M('user_terminal')->where(array(
            'sn' => $shop['sn']
        ))->find();
        $trade_money = M('trade_orders')->where(array(
            'shop_id' => $shop['seller_no']
        ))->sum('trade_money');
        if ($trade_money == NULL) {
            $trade_money = 0;
        }
        
        if ($user_terminal != null) {
            if ($user_terminal['is_fan'] == 0 && $trade_money >= $user_terminal['min_fan_money'] && time() < $user_terminal['expire_time']) {
                
                $user = M('fck')->where(array(
                    'id' => $user_terminal['order_uid']
                ))->find();
                $fan_money = $user_terminal['fan_money'];
                $award_money = $user_terminal['award_money'];
                if ($fan_money > 0) {
                    $fck = D('Fck');
                    $result = $fck->where('id=' . $user['id'])->setInc('agent_use', $fan_money);
                    $kt_cont = "编号" . $user_terminal['sn'] . "机器返现";
                    $fck->addencAdd($user['id'], $user['user_id'], $fan_money, 19, 0, 0, 0, $kt_cont . '', $trade_id);
                    
                    if ($award_money > 0) {
                        $result = $fck->where('id=' . $user['id'])->setInc('agent_use', $award_money);
                        $kt_cont = "编号" . $user_terminal['sn'] . "机器返现-奖励";
                        $fck->addencAdd($user['id'], $user['user_id'], $award_money, 19, 0, 0, 0, $kt_cont . '', $trade_id);
                        $shui = $jq_shui[$user['u_level'] - 1] * 0.01 * $award_money;
                        $fck = D('Fck');
                        $result = $fck->where('id=' . $user['id'])->setDec('agent_use', $shui);
                        $kt_cont = "机器返现奖励所得税";
                        $fck->addencAdd($user['id'], $user['user_id'], - $shui, 19, 0, 0, 0, $kt_cont . '', $trade_id);
                    }
                    
                    M('user_terminal')->where(array(
                        'id' => $user_terminal['id']
                    ))->setField('is_fan', 1);
                    
                    M('user_terminal')->where(array(
                        'id' => $user_terminal['id']
                    ))->setField('fan_time', time());
                }
            }
        }
    }
}

function set_shop_trade_status($money, $seller_id)
{
    $all_trade_money = M('trade_orders')->where(array(
        'shop_id' => $seller_id
    ))->sum('trade_money');
    if ($all_trade_money == null) {
        $all_trade_money = 0;
    }
    $shop = M('seller')->where(array(
        'seller_no' => $seller_id
    ))->find();
    
    $shop = M('seller')->where(array(
        'seller_no' => $seller_id
    ))->find();
    
    // 增加商户的的交易金额
    M('seller')->where(array(
        'id' => $shop['id']
    ))->setField('trade_money', $all_trade_money);
    
    // 增加SN机器的交易金额
    M('user_terminal')->where(array(
        'sn' => $shop['sn']
    ))->setField('trade_money', $all_trade_money);
    
    // 更新SN机器的交易状态
    M('user_terminal')->where(array(
        'sn' => $shop['sn']
    ))->setField('trade_status', 1);
    
    // 更新商户激活状态
    $shop_add_time = $shop['add_time'];
    $max_time = strtotime('+' . $shop['min_day'] . ' days', $shop_add_time);
    
    $now = time();
    
    if ($max_time > $now && $shop['active_status'] == 0) {
        $all_trade_money = M('seller')->where(array(
            'id' => $shop['id']
        ))->sum('trade_money');
        if ($all_trade_money > $shop['min_money']) {
            M('seller')->where(array(
                'id' => $shop['id']
            ))->setField('active_status', 1);
            M('seller')->where(array(
                'id' => $shop['id']
            ))->setField('active_time', $now);
        }
    }
}

function get_terminal_status_str($vo)
{
    $status_str = '已登记';
    if ($vo['uid'] == $vo['order_uid']) {
        if ($vo['seller_id'] == 0) {
            $status_str = '未登记';
        }
        
        $where['sn'] = array(
            'like',
            '%' . $vo['sn'] . '%'
        );
        $where['status'] = array(
            'eq',
            1
        );
        $check_count = M('user_terminal_out')->where($where)->count();
        IF ($check_count > 0) {
            
            $status_str = '下发审核';
        }
    } else {
        $status_str = '已下发';
        
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
            
            $status_str = '回收待审核';
        }
    }
    return $status_str;
}

function get_terminal_fan_status_str($vo)
{
    $is_fan_status_str = '未返现';
    if ($vo['is_fan'] == 1) {
        
        $is_fan_status_str = '已返现';
    }
    RETURN $is_fan_status_str;
}

function get_shop_fan_status_str($vo)
{
    $is_fan_status_str = '未达标';
    if ($vo['trade_money'] >= $vo['min_money']) {
        if ($vo['is_fan'] == 0) {
            $is_fan_status_str = '已达标未返现';
        }
        if ($vo['is_fan'] == 1) {
            $is_fan_status_str = '已达标已返现';
        }
    }
    RETURN $is_fan_status_str;
}

/**
 * 推送
 */
function push_msg($data, $uid = '')
{
    // $data = array();
    // $data['type'] = "update_order_num";
    // $data['order_num'] = 1;
    $string = urldecode(json_encode($data));
    import("@.ORG.Socket");
    $push = new Socket();
    
    $push->setUser($uid)
        ->setContent($string)
        ->push();
}

function bqwhits($hits)
{
    $b = 1000;
    $c = 10000;
    $d = 100000000;
    if ($hits >= $b && $hits < $c) {
        return floor($hits / $b) . '千';
    } else if ($hits >= $c && $hits < $d) {
        return floor($hits / $c) . '万';
    } else {
        
        return floor($hits / $c) . '亿';
    }
}

function trade_money_ulevel($trade_money, $trade)
{
    $array = array();
    $array['u_level'] = 1;
    
    $fee = M('fee');
    $fee_rs = $fee->field('user_money,s9,s10')->find();
    $fee_trade_money = explode('|', $fee_rs['s9']);
    $fee_u_level = explode('|', $fee_rs['s10']);
    $fee_user_money = explode('|', $fee_rs['user_money']);
    $array['u_level_str'] = $fee_u_level[0];
    $array['user_money'] = $fee_user_money[0];
    $fee_trade_money = ($fee_trade_money);
    foreach ($fee_trade_money as $key => $value) {
        IF ($value * 10000 <= $trade_money) {
            $u_level = $key + 1;
            $array['u_level_str'] = $fee_u_level[$key];
            $array['user_money'] = $fee_user_money[$key];
            $array['trade_money' . $u_level] = $trade_money;
        }
        if ($trade['is_boss'] == 2) {
            $u_level = count($fee_trade_money) - 1;
            $array['u_level_str'] = $fee_u_level[$u_level - 1];
            $array['user_money'] = $fee_user_money[$u_level - 1];
            $array['trade_money' . $u_level] = $trade_money;
        }
    }
    $array['u_level'] = $u_level;
    RETURN $array;
}

function init_month_data($userInfo)
{
    $u_level = 1;
    $id = $userInfo['id'];
    
    if ($userInfo['is_boss'] == 2) {
        $u_level = 8;
    }
    
    if ($userInfo['is_gd'] == 1) {
        
        $fck_level = M('fck_level')->where('uid=' . $id . '')
            ->order(' id desc ')
            ->find();
        if ($fck_level != null) {
            $u_level = $fck_level['new_level'];
        }
    }
    
    $begintime_str = date('Y-m-01', strtotime(date("Y-m-d")));
    $begintime = strtotime($begintime_str);
    $endtime_str = date('Y-m-d', strtotime("$begintime_str +1 month -1 day"));
    $endtime = strtotime($endtime_str);
    $user_detail_count = M('user_detail')->where('uid=' . $id . ' AND begintime_str="' . $begintime_str . '"')->count();
    if ($user_detail_count == 0) {
        
        $data = array();
        $data['begintime'] = $begintime;
        $data['endtime'] = $endtime;
        $data['begintime_str'] = date('Y-m-d', $begintime);
        $data['endtime_str'] = date('Y-m-d', $endtime);
        $data['uid'] = $id;
        $data['u_level'] = $u_level;
        $data['add_time'] = time();
        M('user_detail')->add($data);
    }
    
    M('fck')->where('id=' . $id)->setField('u_level', $u_level);
    M('fck')->where('id=' . $id)->setField('get_level', $u_level);
    // M('fck')->where('id=' . $id)->setField('team_shop_count', 0);
    M('fck')->where('id=' . $id)->setField('month_shop_count', 0);
    M('fck')->where('id=' . $id)->setField('month_team_shop_count', 0);
    // M('fck')->where('id=' . $id)->setField('user_count', 0);
    // M('fck')->where('id=' . $id)->setField('team_user_count', 0);
    M('fck')->where('id=' . $id)->setField('month_user_count', 0);
    // M('fck')->where('id=' . $id)->setField('user_trade_money', 0);
    M('fck')->where('id=' . $id)->setField('month_team_user_count', 0);
    // M('fck')->where('id=' . $id)->setField('team_trade_money', 0);
    M('fck')->where('id=' . $id)->setField('month_shop_trade_money', 0);
    M('fck')->where('id=' . $id)->setField('month_team_trade_money', 0);
    M('fck')->where('id=' . $id)->setField('month_terminal_active_count', 0);
    M('fck')->where('id=' . $id)->setField('month_terminal_count', 0);
    
    // set_user_count($userInfo);
    // set_user_shop($userInfo);
    // set_user_trade_money($userInfo);
}

function getCoverImages($fileUrl)
{
    $result = array();
    
    if (! empty($fileUrl)) {
        $filePath = str_replace("http://img.baidu.cn/", "/data/images/", $fileUrl);
        if (is_file($filePath)) {
            $result = execCommandLine($filePath);
        }
    }
    return json_encode($result);
}

function execCommandLine($file)
{
    $result = array();
    
    $pathParts = pathinfo($file);
    $filename = $pathParts['dirname'] . "/" . $pathParts['filename'] . "_";
    
    $times = array(
        8,
        15,
        25
    );
    foreach ($times as $k => $v) {
        $destFilePath = $filename . $v . ".jpg";
        $command = "/usr/bin/ffmpeg -i {$file} -y -f image2 -ss {$v} -vframes 1 -s 640x360 {$destFilePath}";
        exec($command);
        // chmod($filename.$v."jpg",0644);
        $destUrlPath = str_replace("/data/images/", "http://img.baidu.cn/", $destFilePath);
        $selected = $k == 0 ? "1" : "0"; // 默认将第一张图片作为封面图
        array_push($result, array(
            $destUrlPath,
            $selected
        ));
    }
    
    return $result;
}

function init_order_read($user_id, $id)
{
    if ($user_id > 0) {
        $count = M('order_read')->where('user_id=' . $user_id . ' AND order_id=' . $id)->count();
        if ($count == 0) {
            $data['user_id'] = $user_id;
            $data['order_id'] = $id;
            $data['add_time'] = time();
            $data['is_read'] = 0;
            M('order_read')->add($data);
        }
    }
}

function init_read($user_id, $id)
{
    if ($user_id > 0) {
        $count = M('form_read')->where('user_id=' . $user_id . ' AND form_id=' . $id)->count();
        if ($count == 0) {
            $data['user_id'] = $user_id;
            $data['form_id'] = $id;
            $data['add_time'] = time();
            $data['is_read'] = 0;
            M('form_read')->add($data);
        }
    }
}

function create_form($user_id, $title)
{
    if ($user_id > 0) {
        
        $data['user_id'] = $user_id;
        $data['title'] = $title;
        $data['content'] = $title;
        $data['status'] = 1;
        $data['type'] = 1;
        $data['create_time'] = time();
        M('form')->add($data);
    }
}

function create_order_terminal($rs, $terminal_type)
{
    $chongzhi = M('orders');
    $order = $chongzhi->alias('t')
        ->join("xt_order_goods AS g ON   g.order_id = t.id", 'left')
        ->where('T.ID=' . $rs['id'])
        ->field('t.*,g.article_id,g.goods_id')
        ->find();
    
    $article_goods = M('article_goods')->alias('t')
        ->where('T.ID=' . $order['goods_id'])
        ->field('t.*')
        ->find();
    $fee = M('fee');
    $fee_rs = $fee->find(1);
    $s1 = explode('|', $fee_rs['s1']);
    $s5 = explode('|', $fee_rs['s5']);
    $s6 = explode('|', $fee_rs['s6']);
    $s7 = explode('|', $fee_rs['s7']);
    $count = M('user_terminal')->where('order_no="' . $order['order_no'] . '"')->count();
    if ($count == 0) {
        IF ($article_goods != NULL) {
            IF ($article_goods['sn_num'] != NULL) {
                FOR ($i = 0; $i < $article_goods['sn_num']; $i ++) {
                    
                    create_terminal($order, $s5, $s6, $s7, $terminal_type);
                }
            }
        }
    }
}

function create_terminal($order, $s5, $s6, $s7, $terminal_type)
{
    $sn = "B" . date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    
    $terminal['terminal_type'] = $terminal_type;
    $terminal['uid'] = $order['user_id'];
    $terminal['use_uid'] = $order['user_id'];
    $terminal['order_uid'] = $order['user_id'];
    $terminal['price'] = 0;
    $terminal['sn'] = $sn;
    $terminal['sn_type'] = '虚拟';
    $terminal['status'] = 1;
    $expire_day = $s5[0];
    // $shop_expire_day = $rs[5];
    $terminal['expire_day'] = $expire_day;
    // $terminal['shop_expire_day'] = $shop_expire_day;
    
    $expire_time = strtotime('+' . $expire_day . ' day');
    
    $expire_time_str = date('Y-m-d H:i:s', strtotime('+' . $expire_day . ' day'));
    
    $fan_money = $s7[0];
    $min_fan_money = $s6[0];
    $terminal['fan_money'] = $fan_money;
    $terminal['min_fan_money'] = $min_fan_money;
    $terminal['expire_time'] = $expire_time;
    $terminal['expire_time_str'] = $expire_time_str;
    $terminal['add_time'] = time();
    $terminal['type'] = 1;
    $count = M('user_terminal')->where('sn="' . $sn . '"')->count();
    $order_no = $order['order_no'];
    
    $terminal['order_no'] = $order_no;
    if ($terminal_type == 1) {
        $terminal['huikui_id'] = $order['id'];
    }
    if ($count == 0) {
        if ($terminal['uid'] > 0) {
            M('user_terminal')->add($terminal);
        }
    }
}

function create_saoma_order_terminal($rs, $terminal_type)
{
    $chongzhi = M('huikui');
    $order = $chongzhi->alias('t')
        ->where('T.ID=' . $rs['id'])
        ->field('t.*')
        ->find();
    
    $fee = M('fee');
    $fee_rs = $fee->find(1);
    $s1 = explode('|', $fee_rs['s1']);
    $s5 = explode('|', $fee_rs['s5']);
    $s6 = explode('|', $fee_rs['s6']);
    $s7 = explode('|', $fee_rs['s7']);
    $count = M('user_terminal')->where('order_no="' . $order['order_no'] . '"')->count();
    if ($count == 0) {
        $order['user_id'] = $order['uid'];
        // $order['order_no']=$order['trade_no'];
        create_terminal($order, $s5, $s6, $s7, $terminal_type);
    }
}

function create_user_money($user_id, $time_str)
{
    $day['time'] = $time_str;
    $str = 'FROM_UNIXTIME(pdt,"%Y-%m-%d")="' . $day['time'] . '" AND uid=' . $user_id;
    // $list = M('history')->field('*,FROM_UNIXTIME(pdt,"%Y-%m-%d %H:%i:%S") AS add_time_str')
    // ->where($str)
    // ->select();
    $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m-%d")="' . $day['time'] . '" AND t.uid=' . $user_id . '   AND t.bz LIKE "%分润-%"  ';
    
    $fenrun_sum = M('history')->alias('t')
        ->join("xt_trade_orders AS g ON   g.id = t.project_id", 'LEFT')
        ->where($str)
        ->sum('T.epoints');
    if ($fenrun_sum == NULL) {
        $fenrun_sum = 0;
    }
    $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m-%d")="' . $day['time'] . '" AND t.uid=' . $user_id . '   AND t.bz LIKE "%分润扣%"  ';
    
    $shui_sum = M('history')->alias('t')
        ->join("xt_trade_orders AS g ON   g.order_no = t.order_no", 'LEFT')
        ->where($str)
        ->sum('T.epoints');
    if ($shui_sum == NULL) {
        $shui_sum = 0;
    }
    // 返现总计
    $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m-%d")="' . $day['time'] . '" AND t.uid=' . $user_id . ' and  t.epoints>0  AND t.bz LIKE "%机器返现%"  ';
    
    $fanxian_sum = M('history')->alias('t')
        ->join("xt_trade_orders AS g ON   g.id = t.project_id", 'LEFT')
        ->where($str)
        ->sum('t.epoints');
    if ($fanxian_sum == NULL) {
        $fanxian_sum = 0;
    }
    // 所得税总计
    $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m-%d")="' . $day['time'] . '" AND t.uid=' . $user_id . ' and  t.epoints<0   AND t.bz LIKE "%机器返现%"  ';
    
    $tax_sum = M('history')->alias('t')
        ->join("xt_trade_orders AS g ON   g.id = t.project_id", 'LEFT')
        ->where($str)
        ->sum('t.epoints');
    if ($tax_sum == NULL) {
        $tax_sum = 0;
    }
    
    // 提现总计
    $str = ' FROM_UNIXTIME(rdt,"%Y-%m-%d")="' . $day['time'] . '" AND uid=' . $user_id . '  and  is_pay!=2 ';
    
    $tx_sum = M('tiqu')->where($str)->sum('money');
    if ($tx_sum == NULL) {
        $tx_sum = 0;
    }
    
    // 充值总计
    $str = 'FROM_UNIXTIME(pdt,"%Y-%m-%d")="' . $day['time'] . '" AND uid=' . $user_id . '   AND bz LIKE "%' . C('cz_txt') . '%"  ';
    
    $cz_sum = M('history')->where($str)->sum('epoints');
    if ($cz_sum == NULL) {
        $cz_sum = 0;
    }
    
    $array = array();
    $array['cz_sum'] = $cz_sum;
    $array['tx_sum'] = $tx_sum;
    $array['tax_sum'] = $tax_sum;
    $array['fanxian_sum'] = $fanxian_sum;
    $array['shui_sum'] = $shui_sum;
    $array['fenrun_sum'] = $fenrun_sum;
    $array['day_time'] = $time_str;
    $array['time'] = strtotime($time_str);
    $array['uid'] = $user_id;
    $array['type'] = 'day';
    $user_detail_money = M('user_detail_money')->where('day_time="' . $time_str . '"  and uid=' . $user_id)->find();
    
    if ($user_detail_money == NULL) {
        
        $array['add_time'] = time();
        $newid = M('user_detail_money')->add($array);
    } else {
        $newid = $user_detail_money['id'];
        M('user_detail_money')->where('id=' . $newid)->save($array);
        $user_detail_money = M('user_detail_money')->where('day_time="' . $time_str . '"  and uid=' . $user_id)->find();
    }
    return $newid;
}

function create_user_month_money($user_id, $time_str)
{
    $newid = 0;
    if ($user_id > 0) {
        // $time_str = date("Y-m", $time_str);
        $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m")="' . $time_str . '" AND t.uid=' . $user_id . '   AND t.bz LIKE "%分润-%"  ';
        
        $fenrun_sum = M('history')->alias('t')
            ->join("xt_trade_orders AS g ON   g.id = t.project_id", 'LEFT')
            ->where($str)
            ->sum('T.epoints');
        if ($fenrun_sum == NULL) {
            $fenrun_sum = 0;
        }
        // 个税总计
        $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m")="' . $time_str . '" AND t.uid=' . $user_id . '   AND t.bz LIKE "%分润扣%"  ';
        
        $shui_sum = M('history')->alias('t')
            ->join("xt_trade_orders AS g ON   g.order_no = t.order_no", 'LEFT')
            ->where($str)
            ->sum('T.epoints');
        if ($shui_sum == NULL) {
            $shui_sum = 0;
        }
        
        // 机器返现总计
        $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m")="' . $time_str . '" AND t.uid=' . $user_id . ' AND t.epoints>0   AND t.bz LIKE "%机器返现%"  ';
        
        $pos_fx_sum = M('history')->alias('t')
            ->join("xt_trade_orders AS g ON   g.id = t.project_id", 'LEFT')
            ->where($str)
            ->sum('T.epoints');
        if ($pos_fx_sum == NULL) {
            $pos_fx_sum = 0;
        }
        // 返现扣税总计
        $str = 'FROM_UNIXTIME(t.pdt,"%Y-%m")="' . $time_str . '" AND t.uid=' . $user_id . ' AND t.epoints<0   AND t.bz LIKE "%机器返现%"  ';
        
        $pos_fx_shui_sum = M('history')->alias('t')
            ->join("xt_trade_orders AS g ON   g.id = t.project_id", 'LEFT')
            ->where($str)
            ->sum('T.epoints');
        if ($pos_fx_shui_sum == NULL) {
            $pos_fx_shui_sum = 0;
        }
        
        // 提现总计
        $str = ' FROM_UNIXTIME(rdt,"%Y-%m")="' . $time_str . '" AND uid=' . $user_id . '  and  is_pay!=2 ';
        
        $tx_sum = M('tiqu')->where($str)
            ->group(' FROM_UNIXTIME(rdt,"%Y-%m-%d") ')
            ->sum('money');
        if ($tx_sum == NULL) {
            $tx_sum = 0;
        }
        
        $array = array();
        $array['tx_sum'] = $tx_sum;
        $array['shui_sum'] = $shui_sum;
        $array['fenrun_sum'] = $fenrun_sum;
        $array['pos_fx_sum'] = $pos_fx_sum;
        $array['pos_fx_shui_sum'] = $pos_fx_shui_sum;
        $array['day_time'] = $time_str;
        $array['time'] = strtotime($time_str);
        $array['uid'] = $user_id;
        $array['type'] = 'month';
        $user_detail_money = M('user_detail_money')->where('day_time="' . $time_str . '"  and uid=' . $user_id)->find();
        
        if ($user_detail_money == NULL) {
            
            $array['add_time'] = time();
            $newid = M('user_detail_money')->add($array);
        } else {
            $newid = $user_detail_money['id'];
            M('user_detail_money')->where('id=' . $newid)->save($array);
            $user_detail_money = M('user_detail_money')->where('day_time="' . $time_str . '"  and uid=' . $user_id)->find();
        }
    }
    return $newid;
}

function init_user_money($user_id, $time_str)
{
    $day = array();
    
    $user_detail_money = M('user_detail_money')->where('day_time="' . $time_str . '"  and uid=' . $user_id)->find();
    
    if ($user_detail_money == NULL) {
        $newid = create_user_money($user_id, $time_str);
        // $array = array();
        // $array['cz_sum'] = $cz_sum;
        // $array['tx_sum'] = $tx_sum;
        // $array['tax_sum'] = $tax_sum;
        // $array['fanxian_sum'] = $fanxian_sum;
        // $array['shui_sum'] = $shui_sum;
        // $array['fenrun_sum'] = $fenrun_sum;
        // $array['day_time'] = $time_str;
        // $array['time'] = strtotime($time_str) ;
        // $array['uid'] = $user_id;
        
        $user_detail_money = M('user_detail_money')->where('id=' . $newid)->find();
    }
    
    return $user_detail_money;
}

function init_user_month_money($user_id, $time_str)
{
    $day = array();
    
    $user_detail_money = M('user_detail_money')->where('day_time="' . $time_str . '"  and uid=' . $user_id)->find();
    
    file_put_contents("user_detail_money.txt", $time_str);
    if ($user_detail_money == NULL) {
        $newid = create_user_month_money($user_id, $time_str);
        
        $user_detail_money = M('user_detail_money')->where('id=' . $newid)->find();
    }
    
    return $user_detail_money;
}

/**
 * 处理日期加一段时间，兼容闰年和二月份
 *
 * @author shaodong.li@monph.com at 2016-11-15 11:00:00
 * @param string $date
 *            日期
 * @param string $type
 *            add-往后推移 sub-往前推移
 * @param string $changenum
 *            改变的数值，跟changesuffix一起使用
 * @param string $changesuffix
 *            月-month 天-day
 * @param int $suffix
 *            生成的日期日固定 如果suffix=8 则生成的日期为：'YYYY-MM-08'
 * @param bool $indate
 *            是否包含当天 如：date=2016-11-05,indate=false,结果为:2016-12-05,indate=true,结果为:2016-12-04
 * @return boolean
 */
function yzy_date($date, $changenum = 0, $changesuffix = 'month', $suffix = 0, $type = 'add', $indate = false)
{
    if (! $date || $date == '') {
        return false;
    }
    if ($changenum == 0) {
        if ((int) $suffix == 0) {
            return $date;
        } else {
            return date("Y-m-d", strtotime(date("Y-m-" . $suffix, strtotime($date))));
        }
    } else {
        if ($changesuffix == 'day') {
            $tempday = date("d", strtotime($date));
            $tempday += $changenum;
        } elseif ($changesuffix == 'month') {
            $tempday = date("d", strtotime($date));
        }
        if ($type == 'add') {
            $change = '+' . $changenum . ' ' . $changesuffix;
        } else {
            $change = '-' . $changenum . ' ' . $changesuffix;
        }
        $tempdate = date("Y-m-01", strtotime($date));
        $microtempdate = strtotime($change, strtotime($tempdate));
        $enddateallday = date('t', $microtempdate);
        if ($tempday > $enddateallday) {
            if ($suffix > 0) {
                if ($enddateallday > $suffix) {
                    $enddatetemp = date("Y-m-d", strtotime(date('Y-m-' . $suffix, $microtempdate)));
                } else {
                    $enddatetemp = date("Y-m-d", strtotime(date('Y-m-' . $enddateallday, $microtempdate)));
                }
            } else {
                if ($changesuffix == 'day') {
                    $enddatetemp = date("Y-m-d", strtotime($change, strtotime($date)) - 86400);
                } else {
                    $enddatetemp = date('Y-m-' . $enddateallday, $microtempdate);
                }
            }
        } else {
            if ($suffix > 0) {
                if ($tempday > $suffix) {
                    $enddatetemp = date('Y-m-' . $suffix, $microtempdate);
                } else {
                    $enddatetemp = date('Y-m-' . $tempday, $microtempdate);
                }
            } else {
                if ($indate) {
                    $enddatetemp = date("Y-m-d", strtotime(date('Y-m-' . $tempday, $microtempdate)) - 86400);
                } else {
                    $enddatetemp = date('Y-m-' . $tempday, $microtempdate);
                }
            }
        }
        return $enddatetemp;
    }
}

FUNCTION get_seller_type($seller_type_)
{
    $seller_type_str = '';
    IF ($seller_type_ == 0) {
        
        $seller_type_str = '传统商户';
    }
    IF ($seller_type_ == 1) {
        
        $seller_type_str = '二维码小微商户';
    }
    
    IF ($seller_type_ == 2) {
        $seller_type_str = '二维码快速商户';
    }
    return $seller_type_str;
}

/**
 * 模拟post进行url请求
 *
 * @param string $url            
 * @param array $post_data            
 */
function request_get($url = '', $post_data = array())
{
    try {
        
        $postUrl = $url;
        $curlPost = $post_data;
        
        $curl = curl_init(); // 初始化curl
        curl_setopt($curl, CURLOPT_URL, $url . '?' . http_build_query($post_data)); // 抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0); // 设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1); // post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 不验证证书下同
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($curl); // 运行curl
        curl_close($curl);
        // 显示获得的数据
        
        return $data;
    } catch (Exception $e) {
        
        file_put_contents("Exception.txt", json_encode($e));
    }
}

/**
 * 模拟post进行url请求
 *
 * @param string $url            
 * @param array $post_data            
 */
function request_post($url = '', $ispost = true, $post_data = array())
{
    try {
        
        if (empty($url) || empty($post_data)) {
            return false;
        }
        $header = array();
        $header[] = 'Accept:application/json';
        $header[] = 'Content-Type:application/json; charset=utf-8';
        
        $postUrl = $url;
        $curlPost = $post_data;
        $ch = curl_init(); // 初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); // 抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); // 设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 要求结果为字符串且输出到屏幕上
        
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_POST, 1); // post提交方式
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 不验证证书下同
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        $data = curl_exec($ch); // 运行curl
        curl_close($ch);
        
        return $data;
    } catch (Exception $e) {
        
        file_put_contents("Exception.txt", json_encode($e));
    }
}

function create_trade_order($seller_no, $trade_money, $order_no, $trade_time, $card_type, $user_money, $shop_name, $user_id)
{
    $trade_orders = M('trade_orders');
    
    $array = array();
    $array['seller_no'] = $seller_no;
    
    // $trade_money = trim($rs[4]);
    $shop = M('seller')->field('title,user_id')
        ->where($array)
        ->find();
    
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
            // $user_id = $fck['id'];
            $re_id = $fck['re_id'];
            
            $profit = $trade_money * $user_money[$fck['u_level'] - 1] * 0.0001;
            
            $money = $profit;
            // $userlist = recommend_tree($fck['id']);
            // foreach ($userlist as $k => $r) {
            
            // $r = get_user_info($r, $r['id']);
            // // 获取总的下线交易额
            
            // if ($r['u_level'] > $fck['u_level']) {
            
            // $recommend_fen_money = $recommend_fen_money + $trade_money * ($user_money[$r['u_level'] - 1] - $user_money[$fck['u_level'] - 1]) * 0.0001;
            // }
            // }
        }
        // $recommend_fen_money = $rs[17] * $gap_profit * 0.0001;
    }
    
    $content[$key]['user_name'] = $user_name;
    $content[$key]['money'] = $money;
    
    $item = array();
    $item['order_no'] = $order_no;
    $count = M('trade_orders')->where($item)->count();
    $item['shop_id'] = $array['seller_no'];
    $item['shop_name'] = $shop_name;
    $item['shop_title'] = $shop['title'];
    // $trade_money = $rs[17];
    $item['trade_money'] = $trade_money;
    $item['trade_time'] = strtotime($trade_time);
    $item['trade_time_str'] = $trade_time;
    $item['real_fen_money'] = $money;
    
    $item['real_fen_money2'] = 0;
    
    // $item['fen_money'] = $rs[22];
    $item['card_type'] = $card_type;
    $item['user_id'] = $user_id;
    $item['money_user_id'] = $user_id;
    $item['recommend_fen_money'] = $recommend_fen_money;
    $fck = M('fck')->field('id, user_id,u_level,re_id,re_level,re_path')
        ->where('id=' . $user_id)
        ->find();
    if ($fck != null) {
        $item['re_path'] = $fck['re_path'] . $user_id . ',';
        $item['re_id'] = $fck['re_id'];
    }
    
    $recommend_fen_money = 0;
    $item['gap_profit'] = $gap_profit;
    
    // if ($item['order_no'] > 0) {
    
    $item1 = array();
    $item1['order_no'] = $item['order_no'];
    $count = M('trade_orders')->where($item1)->count();
    IF ($count == 0) {
        
        $item['add_time'] = time();
        $item['is_upload'] = 1;
        
        $item['upload_type'] = $upload_type;
        
        if ($upload_type == 'excel') {
            $trade_orders = M('trade_orders_record');
        }
        $item1 = array();
        $item1['order_no'] = $item['order_no'];
        $count = $trade_orders->where($item1)->count();
        IF ($count == 0) {
            $id = $trade_orders->add($item);
        }
        $item1 = array();
        $item1['id'] = $id;
        
        // 更新交易金额
        set_shop_trade_status($trade_money, $array['seller_no']);
    } else {
        
        $item1 = array();
        $item1['order_no'] = $item['order_no'];
        $item1['is_upload'] = 1;
        M('trade_orders')->where($item1)->save($item);
        
        if ($upload_type == 'excel') {
            $item['id'] = null;
            $item['upload_type'] = $upload_type;
            $item['is_upload'] = 1;
            $trade_orders = M('trade_orders_record');
            
            $item1 = array();
            $item1['order_no'] = $item['order_no'];
            $count = $trade_orders->where($item1)->count();
            IF ($count == 0) {
                $id = $trade_orders->add($item);
            }
        }
    }
    // }
    $fck = D('Fck');
    $fee = M('fee');
    $fee_rs = $fee->field('user_money,user_shui')->find(1);
    $user_shui = explode('|', $fee_rs['user_shui']);
    
    $fee_user_money = explode('|', $fee_rs['user_money']);
    $item1 = array();
    $item1['order_no'] = $item['order_no'];
    $rs = M('trade_orders')->where($item1)->find();
    
    return $rs['id'];
}

function trade_profit($rs, $user_shui, $fee_user_money)
{
    if ($rs['upload_type'] == 'excel') {
        $rs['id'] = null;
        $count = M('trade_orders')->where(array(
            'order_no' => $rs['order_no']
        ))->count();
        IF ($count == 0) {
            $rs['is_money'] = 0;
            $new_id = M('trade_orders')->add($rs);
            if ($new_id > 0) {
                $rs = M('trade_orders')->where(array(
                    'id' => $new_id
                ))->find();
                // M('trade_orders_record')->where(array(
                // 'order_no' => $rs['order_no']
                // ))->delete();
            }
        }
    }
    
    $count = M('trade_orders')->where(array(
        'order_no' => $rs['order_no'],
        'is_money' => 0
    ))->count();
    
    if ($count == 1) {
        if ($rs['is_money'] == 0) {
            $fck = D('Fck');
            $bei = 10000;
            $project_id = $rs['id'];
            M('trade_orders')->where('id=' . $rs['id'])->setField('is_upload', 0);
            
            $user = M('fck')->field('id, user_id,u_level,re_id,u_level,re_path,is_boss,is_gd')
                ->where('id  =' . $rs['user_id'])
                ->find();
            
            $u_level = 1;
            if ($user['is_boss'] == 2) {
                
                $u_level = $user['u_level'];
            }
            
            if ($user['is_gd'] == 1) {
                
                $u_level = $user['u_level'];
            }
            $seller = M('seller')->where(array(
                'seller_no' => $rs['shop_id']
            ))
                ->field('id,seller_type')
                ->find();
            $fee = M('fee')->field('user_ew_money')->find();
            $user_money = $fee_user_money[$u_level - 1];
            
            if ($seller['seller_type'] > 0) {
                $fee_user_money = $fee['user_ew_money'];
                $fee_user_money = explode('|', $fee['user_ew_money']);
                $user_money = $fee_user_money[$u_level - 1];
            }
            
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
            $boss_list = M('fck')->field('id, user_id,u_level,re_id,u_level,re_path')
                ->where('id  in (0' . $user['re_path'] . '0) and is_boss=2')
                ->select();
            
            foreach ($boss_list as $key1 => $rs1) {
                
                $user_money = $fee_user_money[$rs1['u_level'] - 1] - $fee_user_money[$u_level - 1];
                $fen_money = $user_money * $rs['trade_money'] / $bei;
                IF ($fen_money > 0) {
                    $money = $fen_money;
                    $result = $fck->where('id=' . $rs1['id'])->setInc('agent_use', $money);
                    $kt_cont = "" . C('pos_txt') . "";
                    $fck->addencProfitAdd($rs1['id'], $kt_cont . '-' . C('agent_use'), $money, $rs['order_no'], $rs['trade_time'], $rs['shop_title'], $fen_money, $all_trade_money, $user['u_level'], 0, $project_id); // 激活积分扣除历史记录
                                                                                                                                                                                                                       
                    // 计算个人所得税
                    $tax = $money * ($user_shui[$u_level - 1] / 100);
                    
                    $result = $fck->where('id=' . $rs1['id'])->setDec('agent_use', $tax);
                    $kt_cont = "" . C('pos_txt') . "扣" . C('tax_txt');
                    $fck->addencProfitAdd($rs1['id'], $kt_cont . '-' . C('agent_use'), - $tax, $rs['order_no'], $rs['trade_time'], $rs['shop_title'], 0); // 激活积分扣除历史记录
                }
            }
            
            // 进行升级检测
            $this_month = strtotime(date('Y-m-01', $rs['trade_time']));
            
            user_level_check($user['id'], ' and trade_time>=' . $this_month . ' AND trade_time<=' . $rs['trade_time'], $this_month);
            M('trade_orders')->where('id=' . $rs['id'])->setField('tax', $tax);
            M('trade_orders')->where('id=' . $rs['id'])->setField('is_money', 1);
            M('trade_orders')->where('id=' . $rs['id'])->setField('money_time', time());
            // 返现
            back_pos_money($rs['trade_money'], $rs['shop_id'], $rs['id']);
        }
    }
    create_user_money($rs['user_id'], date('Y-m-d', $rs['trade_time']));
    create_user_month_money($rs['user_id'], date('Y-m', $rs['trade_time']));
}

function init_user_level($re_path)
{
    $list = M('fck')->field('id, user_id,u_level,re_id,u_level,re_path')
        ->where('    id  in(0' . $re_path . '0) ')
        ->select();
    $fck = D('Fck');
    foreach ($list as $key => $rs) {
        user_level_check($rs['id'], '', '');
    }
}

function input_csv($handle)
{
    $out = array();
    $n = 0;
    while ($data = fgetcsv($handle, 10000)) {
        
        $num = count($data);
        for ($i = 0; $i < $num; $i ++) {
            $data[$i] = iconv('gb2312', 'utf-8', $data[$i]);
            $out[$n][$i] = $data[$i];
        }
        $n ++;
    }
    return $out;
}

function init_user_error()
{
    $sql = C('get_error_list');
    $list = M()->query($sql);
    $userlist = $list;
    $uids = '';
    
    $sql = C('get_detail_error_list');
    $list = M()->query($sql);
    $fck = D('Fck');
    foreach ($list as $key => $rs) {
        
        $result = $fck->where('id=' . $rs['uid'])->setDec('agent_use', $rs['扣除的金额']);
        $kt_cont = "系统异常,处理数据";
        $fck->addencAdd($rs['uid'], $rs['用户名'], - $rs['扣除的金额'], 19, 0, 0, 0, $kt_cont . '', 0);
        
        $array = array();
        $array['uid'] = $rs['uid'];
        $array['user_id'] = $rs['用户名'];
        $array['error_count'] = $rs['错误次数'];
        $array['user_name'] = $rs['姓名'];
        $array['pdt'] = $rs['pdt'];
        $array['pdt_time'] = $rs['发生时间'];
        $array['order_no'] = $rs['订单号'];
        $array['money'] = $rs['扣除的金额'];
        $array['agent_use'] = $rs['目前余额'];
        $array['add_time'] = time();
        
        $ret = M('fck_error')->add($array);
        
        $ret = M('history')->where(ARRAY(
            'order_no' => $array['order_no'],
            'uid' => $array['uid']
        ))
            ->limit(($array['error_count'] + 1))
            ->order(' id desc ')
            ->delete();
    }
    foreach ($userlist as $key => $rs) {
        $day_time = date('Y-m-d', strtotime($rs['time_str']));
        
        create_user_money($rs['uid'], $day_time);
        $month_time = date('Y-m', strtotime($rs['time_str']));
        create_user_month_money($rs['uid'], $month_time);
    }
}

/**
 * 水印
 *
 * @param
 *            $img_path
 */
function waterImage($img_path)
{
    require_once 'vendor/topthink/think-image/src/Image.php';
    require_once 'vendor/topthink/think-image/src/image/Exception.php';
    if (strstr(strtolower($img_path), '.gif')) {
        require_once 'vendor/topthink/think-image/src/image/gif/Encoder.php';
        require_once 'vendor/topthink/think-image/src/image/gif/Decoder.php';
        require_once 'vendor/topthink/think-image/src/image/gif/Gif.php';
    }
    
    $image = \think\Image::open($img_path);
    $water = tpCache('water'); // 水印配置
    $return_data['mark_type'] = $water['mark_type'];
    if ($water['is_mark'] == 1 && $image->width() > $water['mark_width'] && $image->height() > $water['mark_height']) {
        if ($water['mark_type'] == 'text') {
            $ttf = './hgzb.ttf';
            if (file_exists($ttf)) {
                $size = $water['mark_txt_size'] ? $water['mark_txt_size'] : 30;
                $color = $water['mark_txt_color'] ?: '#000000';
                if (! preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
                    $color = '#000000';
                }
                $transparency = intval((100 - $water['mark_degree']) * (127 / 100));
                $color .= dechex($transparency);
                $water['mark_txt'] = $this->to_unicode($water['mark_txt']);
                $image->open($img_path)
                    ->text($water['mark_txt'], $ttf, $size, $color, $water['sel'])
                    ->save($img_path);
                $return_data['mark_txt'] = $water['mark_txt'];
            }
        } else {
            $waterPath = "." . $water['mark_img'];
            $quality = $water['mark_quality'] ? $water['mark_quality'] : 80;
            $waterTempPath = dirname($waterPath) . '/temp_' . basename($waterPath);
            $image->open($waterPath)->save($waterTempPath, null, $quality);
            $image->open($img_path)
                ->water($waterTempPath, $water['sel'], $water['mark_degree'])
                ->save($img_path);
            @unlink($waterTempPath);
        }
    }
}

/**
 * http://www.dzc.me/2017/07/php%E6%8A%A5imagettfbbox-any2eucjp-invalid-code-in-input-string%E7%9A%84%E4%B8%A4%E4%B8%AA%E8%A7%A3%E5%86%B3%E5%8A%9E%E6%B3%95/
 * 报imagettfbbox(): any2eucjp(): invalid code in input string的两个解决办法
 *
 * @param
 *            $string
 * @return string
 */
function to_unicode($string)
{
    $str = mb_convert_encoding($string, 'UCS-2', 'UTF-8');
    $arrstr = str_split($str, 2);
    $unistr = '';
    foreach ($arrstr as $n) {
        $dec = hexdec(bin2hex($n));
        $unistr .= '&#' . $dec . ';';
    }
    return $unistr;
}

/**
 * 保存上传的图片
 *
 * @param
 *            $file
 * @param
 *            $save_path
 * @return array
 */
function saveUploadImage($file, $save_path)
{
    $return_url = '';
    $state = "SUCCESS";
    $new_path = $save_path . date('Y') . '/' . date('m-d') . '/';
    
    $waterPaths = [
        'goods/',
        'water/'
    ]; // 哪种路径的图片需要放oss
    if (in_array($save_path, $waterPaths) && tpCache('oss.oss_switch')) {
        // 商品图片可选择存放在oss
        $object = UPLOAD_PATH . $new_path . md5(time()) . '.' . pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
        $ossClient = new \app\common\logic\OssLogic();
        $return_url = $ossClient->uploadFile($file->getRealPath(), $object);
        $real_path = $file->getRealPath();
        $file = null; // 关闭文件句柄，不然无法删除
        @unlink($real_path); // 上传后删除
        if (! $return_url) {
            $state = "ERROR" . $ossClient->getError();
            $return_url = '';
        }
    } else {
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->rule(function ($file) {
            return md5(mt_rand()); // 使用自定义的文件保存规则
        })->move(UPLOAD_PATH . $new_path);
        if (! $info) {
            $state = "ERROR" . $file->getError();
        } else {
            $return_url = '/' . UPLOAD_PATH . $new_path . $info->getSaveName();
            $pos = strripos($return_url, '.');
            $filetype = substr($return_url, $pos);
            if ($save_path == 'goods/' && $filetype != '.gif') { // 只有商品图才打水印，GIF格式不打水印
                $this->waterImage("." . $return_url); // 水印
            }
            
            $state = uploadWechatImage($save_path, $return_url);
            if ($state != 'SUCCESS') {
                $info = null; // 关闭文件句柄，不然无法删除
                @unlink('.' . $return_url);
                $return_url = '';
            }
        }
    }
    
    return [
        'state' => $state,
        'url' => $return_url
    ];
}

/**
 * 上传微信公众号图片
 *
 * @param
 *            $save_path
 * @param
 *            $return_url
 * @return string
 */
function uploadWechatImage($save_path, $return_url)
{
    $state = "SUCCESS";
    
    // 微信公众号图片,weixin_mp_image存放永久图片，weixin_mp_news存放文章图片，两者不能共用
    if ($save_path == 'weixin_mp_image/') {
        $wechat = new \app\common\logic\wechat\WechatUtil();
        $data = $wechat->uploadMaterial('.' . $return_url, 'image');
        if ($data === false) {
            $state = $wechat->getError();
        } else {
            WxMaterial::create([
                'type' => WxMaterial::TYPE_IMAGE,
                'key' => md5($return_url),
                'media_id' => $data['media_id'],
                'update_time' => time(),
                'data' => [
                    'url' => $return_url,
                    'mp_url' => $data['url']
                ]
            ]);
        }
    } elseif ($save_path == 'weixin_mp_news/') {
        $wechat = new \app\common\logic\wechat\WechatUtil();
        $news_img_url = $wechat->uploadNewsImage('.' . $return_url);
        if ($news_img_url === false) {
            $state = $wechat->getError();
        } else {
            WxMaterial::create([
                'type' => WxMaterial::TYPE_NEWS_IMAGE,
                'key' => md5($return_url),
                'update_time' => time(),
                'data' => [
                    'url' => $return_url,
                    'mp_url' => $news_img_url
                ]
            ]);
        }
    }
    
    return $state;
}

/**
 * 验证数据
 *
 * @access protected
 * @param array $data
 *            数据
 * @param string|array $validate
 *            验证器名或者验证规则数组
 * @param array $message
 *            提示信息
 * @param bool $batch
 *            是否批量验证
 * @param mixed $callback
 *            回调方法（闭包）
 * @return array|string|true
 * @throws ValidateException
 */
function validate($data, $validate, $message = [], $batch = false, $callback = null)
{
    
    /**
     *
     * @var bool 是否批量验证
     */
    $batchValidate = false;
    $v = import("@.ORG.Validate");
    $v = new Validate();
    if (is_array($validate)) {
        
        $v->rule($validate);
    } else {
        // 支持场景
        if (strpos($validate, '.')) {
            list ($validate, $scene) = explode('.', $validate);
        }
        
        ! empty($scene) && $v->scene($scene);
    }
    
    // 批量验证
    if ($batch || $batchValidate)
        $v->batch(true);
    // 设置错误信息
    if (is_array($message))
        $v->message($message);
    // 使用回调验证
    if ($callback && is_callable($callback)) {
        call_user_func_array($callback, [
            $v,
            &$data
        ]);
    }
    
    if (! $v->check($data)) {
        if ($v->failException) {
            throw new ValidateException($v->getError());
        }
        
        return $v->getError();
    }
    
    return true;
}

function get_user_no_read_notice_count($userInfo)
{
    $notice_count = M('form')->count();
    $notice_read_count = M('form_read')->where('user_id=' . $userInfo['id'] . '  and is_read=1')->count();
    $notice_no_read_count = $notice_count - $notice_read_count;
    $userInfo['notice_no_read_count'] = $notice_no_read_count;
    $userInfo['notice_read_count'] = $notice_read_count;
    $userInfo['notice_count'] = $notice_count;
    RETURN $userInfo;
}

function get_navigation_childs(&$html = '', $oldData, $parent_id, $role_type, $ls)
{
    $dr = ARRAY();
    
    foreach ($oldData as $key => $rs) {
        IF ($parent_id == $rs['parent_id']) {
            $dr[] = $rs;
        }
    }
    $isWrite = false; // 是否输出开始标签
    for ($i = 0; $i < count($dr); $i ++) {
        // 检查是否显示在界面上====================
        $isActionPass = true;
        if ((int) ($dr[$i]["is_lock"]) == 1) {
            $isActionPass = false;
        }
        // 检查管理员权限==========================
        if ($isActionPass && $role_type > 1) {
            $actionTypeArr = explode(',', $dr[$i]["action_type"]);
            foreach ($actionTypeArr as $action_type_str) {
                // 如果存在显示权限资源，则检查是否拥有该权限
                if ($action_type_str == "Show") {
                    $modelt = NULL;
                    
                    foreach ($ls as $key => $rs) {
                        IF ($rs['nav_name'] == $dr[$i]["name"] && $rs['action_type'] == "Show") {
                            $modelt = $rs;
                        }
                    }
                    
                    if ($modelt == null) {
                        $isActionPass = false;
                    }
                }
            }
        }
        // 如果没有该权限则不显示
        if (! $isActionPass) {
            if ($isWrite && $i == (count($dr) - 1) && $parent_id > 0) {
                $html = $html . ("</ul>\n");
            }
            continue;
        }
        // 如果是顶级导航
        if ($parent_id == 0) {
            $html = $html . ("<div class=\"list-group\">\n");
            $html = $html . ("<h1 title=\"" . $dr[$i]["sub_title"] . "\">");
            if (! empty($dr[$i]["icon_url"])) {
                // if ($dr[$i]["icon_url"].StartsWith("."))
                // {
                // $html=$html.("<i class=\"iconfont " + $dr[$i]["icon_url"].Trim('.') + "\"></i>");
                // }
                // else
                // {
                $html = $html . ("<img src=\"" . $dr[$i]["icon_url"] . "\" />");
                // }
            }
            $html = $html . ("</h1>\n");
            $html = $html . ("<div class=\"list-wrap\">\n");
            $html = $html . ("<h2>" . $dr[$i]["title"] . "<i class=\"iconfont icon-arrow-down\"></i></h2>\n");
            // 调用自身迭代
            get_navigation_childs($html, $oldData, ($dr[$i]["id"]), $role_type, $ls);
            $html = $html . ("</div>\n");
            $html = $html . ("</div>\n");
        } else // 下级导航
{
            if (! $isWrite) {
                $isWrite = true;
                $html = $html . ("<ul>\n");
            }
            $html = $html . ("<li>\n");
            $html = $html . ("<a navid=\"" . $dr[$i]["name"] . "\"");
            if (! empty($dr[$i]["link_url"])) {
                if (($dr[$i]["channel_id"]) > 0) {
                    $html = $html . (" href=\"" . $dr[$i]["link_url"] . "?channel_id=" . $dr[$i]["channel_id"] . "\" target=\"mainframe\"");
                } else {
                    $html = $html . (" href=\"" . $dr[$i]["link_url"] . "\" target=\"mainframe\"");
                }
            }
            if (! empty($dr[$i]["icon_url"])) {
                $html = $html . (" icon=\"" . $dr[$i]["icon_url"] . "\"");
            }
            $html = $html . (" target=\"mainframe\">\n");
            $html = $html . ("<span>" . $dr[$i]["title"] . "</span>\n");
            $html = $html . ("</a>\n");
            // 调用自身迭代
            get_navigation_childs($html, $oldData, ($dr[$i]["id"]), $role_type, $ls);
            $html = $html . ("</li>\n");
            
            if ($i == (count($dr) - 1)) {
                $html = $html . ("</ul>\n");
            }
        }
    }
}

function GetNavigationChilds($oldData, &$newData = ARRAY(), $parent_id, $class_layer)
{
    $class_layer ++;
    $dr = array();
    for ($i = 0; $i < count($oldData); $i ++) {
        if ($parent_id == $oldData[$i]['parent_id']) {
            
            $dr[] = $oldData[$i];
        }
    }
    
    for ($i = 0; $i < count($dr); $i ++) {
        $dr[$i]["class_layer"] = $class_layer; // 赋值深度字段
        $newData[] = $dr[$i];
        GetNavigationChilds($oldData, $newData, $dr[$i]["id"], $class_layer);
    }
}

function GetTypeName($role_type)
{
    $str = "";
    switch ($role_type) {
        case 1:
            $str = "超级用户";
            break;
        default:
            $str = "系统用户";
            break;
    }
    return $str;
}

function ActionType()
{
    $dic = ARRAY();
    $dic[] = ARRAY(
        "Show",
        "显示"
    );
    $dic[] = ARRAY(
        "View",
        "查看"
    );
    $dic[] = ARRAY(
        "Add",
        "添加"
    );
    $dic[] = ARRAY(
        "Edit",
        "修改"
    );
    $dic[] = ARRAY(
        "Delete",
        "删除"
    );
    $dic[] = ARRAY(
        "Audit",
        "审核"
    );
    $dic[] = ARRAY(
        "Reply",
        "回复"
    );
    $dic[] = ARRAY(
        "Confirm",
        "确认"
    );
    $dic[] = ARRAY(
        "Cancel",
        "取消"
    );
    $dic[] = ARRAY(
        "Invalid",
        "作废"
    );
    $dic[] = ARRAY(
        "Build",
        "生成"
    );
    $dic[] = ARRAY(
        "Instal",
        "安装"
    );
    $dic[] = ARRAY(
        "Unload",
        "卸载"
    );
    $dic[] = ARRAY(
        "Back",
        "备份"
    );
    $dic[] = ARRAY(
        "Restore",
        "还原"
    );
    $dic[] = ARRAY(
        "Replace",
        "替换"
    );
    return $dic;
}

function GetActionType($ActionType)
{
    $str = '';
    $list = ActionType();
    for ($i = 0; $i < count($list); $i ++) {
        if ($list[$i][0] == $ActionType) {
            $str = $list[$i][1];
        }
    }
    
    return $str;
}

function copy_goods($goods, $uid, $stock)
{
    $count = M('goods')->where('pid=' . $goods['id'] . ' and user_id=' . $uid)->count();
    
    
    
    $seller = M('seller')->where('user_id=' . $uid)->find();
    
    
    
    
    $goods['shop_id'] = $seller['id'];
    
    if ($count > 0) {
        
        M('goods')->where('pid=' . $goods['id'] . ' and user_id=' . $uid)->setInc('stock', $stock);
    } else {
        $goods['stock'] = $stock;
        $goods['pid'] = $goods['id'];
        $goods['type'] = 0;
        $goods['user_id'] = $uid;
        $goods['addtime'] = time();
        $goods['id'] = 0;
        M('goods')->add($goods);
    }
}

function give_agent_cash($money_count, $myid, $inUserID)
{
    $fck = D('Fck');
    if ($money_count > 0) {
        $jjbz = "赠送" . $money_count . '-' . C('agent_cash');
        $usqlc = "agent_cash=agent_cash+" . $money_count;
        // 加到记录表
        $fck->execute("UPDATE __TABLE__ set " . $usqlc . "  where id=" . $myid);
        
        $fck->addencAdd($myid, $inUserID, $money_count, 1, 0, 0, 0, $jjbz);
    }
}

/**
 * 格式化字节大小
 * 
 * @param number $size
 *            字节数
 * @param string $delimiter
 *            数字和单位分隔符
 * @return string 格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = array(
        'B',
        'KB',
        'MB',
        'GB',
        'TB',
        'PB'
    );
    for ($i = 0; $size >= 1024 && $i < 5; $i ++)
        $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

function set_user_goods_num($user)
{
    $userInfo = array();
    
    // 已审核
    $goods_num1 = M('goods')->where('user_id=' . $user['id'] . ' and check_status=0   ')->count();
    // 待审核
    $goods_num2 = M('goods')->where('user_id=' . $user['id'] . '  and  check_status=1   ')->count();
    // 审核不通过
    $goods_num3 = M('goods')->where('user_id=' . $user['id'] . '  and  check_status=2   ')->count();
    
    $userInfo['goods_num1'] = $goods_num1;
    
    $userInfo['goods_num2'] = $goods_num2;
    
    $userInfo['goods_num3'] = $goods_num3;
    
    set_user_num($user['id'], $userInfo);
}

function set_user_num($uid, $ret = array())
{
    $count = M('fck_num')->where('uid=' . $uid)->count();
    if ($count == 0) {
        $data = array();
        $data['uid'] = $uid;
        $data['add_time'] = time();
        M('fck_num')->add($data);
    }
    $goods = M("fck_num");
    $goods->where('uid=' . $uid)
        ->data($ret)
        ->save();
}

/*
 * Author @ Huoty
 * Date @ 2015-11-24 16:59:26
 * Brief @
 */
function isImage($filename)
{
    $file = fopen($fileName, "rb");
    $bin = fread($file, 2); // 只读2字节
    
    fclose($file);
    $strInfo = @unpack("C2chars", $bin);
    $typeCode = intval($strInfo['chars1'] . $strInfo['chars2']);
    $fileType = '';
    
    if ($typeCode == 255216 /*jpg*/ || $typeCode == 7173 /*gif*/ || $typeCode == 13780 /*png*/)
    {
        return $typeCode;
    } else {
        // echo '"仅允许上传jpg/jpeg/gif/png格式的图片！';
        return false;
    }
}

　?>
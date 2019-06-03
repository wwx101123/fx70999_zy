<?php

class PayAction extends Action
{

    public function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
    }

    function recharge()
    {
        // 获取支付金额
        $amount = $_POST['total'];
        $out_trade_no = $_POST['trade_no'];
        $paytype = $_POST['paytype'];
        $total = ($amount);
        // $return = array();
        // $return['status'] = 0;
        // $return['info'] = ($total);
        // echo json_encode($return);
        // return;
        $user_id = $_POST['user_id'];
        if (empty($user_id)) {
            echo json_encode('请先登录');
            return;
        }
        
        $fck = M('fck'); //
        $where = array();
        $where['id'] = $user_id;
        $rs = $fck->where($where)->find();
        if ($rs['is_lock'] == 1) {
            $this->error(C('LOCK_MSG'));
            exit();
        }
        // 商品名称
        $subject = '充值' . C('agent_use');
        // 订单号，示例代码使用时间值作为唯一的订单ID号
        
        $chongzhi = M('chongzhi');
        $data = array();
        $data['uid'] = $rs['id'];
        $data['user_id'] = $rs['user_id'];
        $data['huikuan'] = $amount;
        $data['zhuanghao'] = 0;
        $data['rdt'] = time();
        $data['epoint'] = $amount;
        $data['is_pay'] = 0;
        $data['stype'] = 0;
        $data['status'] = 1;
        $data['trade_no'] = $out_trade_no;
        $data['paytype'] = $paytype;
        $data['bz'] = $subject;
        
        $fee = M('fee');
        $fee_rs = $fee->field('recharge_yh')->find();
        $recharge_yh = explode("|", $fee_rs['recharge_yh']);
        
        // foreach ($recharge_yh as $key => $vo) {
        // $money = explode(":", $vo);
        // if ($money[0] == $amount) {
        $data['yh'] = $recharge_yh[1];
        // }
        // }
        
        $rs2 = $chongzhi->add($data);
        $return = array();
        $return['status'] = 1;
        $return['info'] = json_encode($data);
        $this->ajaxReturn($return);
    }

    function recharge_notify()
    {
        $msg = array();
        $trade_no = $_POST['trade_no'];
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $data = array();
        $data['remark'] = 'DHGFHFG';
        $data['trade_no'] = $xml;
        
        // $rs2 = $chongzhi->add($data);
        libxml_disable_entity_loader(true);
        $data2 = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        
        $chongzhi = M('chongzhi'); //
        $where = array();
        $where['trade_no'] = $data2['out_trade_no'];
        $where['is_pay'] = 0;
        $rs = $chongzhi->where($where)->find();
        if ($rs != NULL) {
            
            $this->update_chongzhi($where, $rs);
        }
    }

    function alirecharge_notify()
    {
        $msg = array();
        $trade_no = $_POST['out_trade_no'];
        $chongzhi = M('chongzhi'); //
        $where = array();
        $where['trade_no'] = $trade_no;
        $where['is_pay'] = 0;
        $rs = $chongzhi->where($where)->find();
        if ($rs != NULL) {
            
            $this->update_chongzhi($where, $rs);
        }
    }

    function update_chongzhi($where, $rs)
    {
        $chongzhi = M('chongzhi'); //
        $chongzhi->where($where)->setField('is_pay', 1);
        $chongzhi->where($where)->setField('pdt', time());
        $money = $rs['epoint'] * C('recharge_parm');
        
        $yh_money = 0;
        
        $fee = M('fee');
        $fee_rs = $fee->field('recharge_yh,recharge_award')->find();
        $recharge_yh = explode("|", $fee_rs['recharge_yh']);
        
        if ($recharge_yh[0] <= $money && $money > 0) {
            
            $yh_money = $money * $rs['yh'] * 0.01;
            $yh_txt = '优惠' . $yh_money;
        }
        
        $real_money = $money + $yh_money;
        
        $fck = M('fck'); //
        $where = array();
        $where['id'] = $rs['uid'];
        $user = $fck->where($where)->find();
        // $yh_txt = '';
        // if ($rs['epoint'] > 100 && $money > 0) {
        
        // $yh_txt = '优惠' . $yh_money;
        // }
        
        $kt_cont = "充值" . C('agent_use') . ',到账' . $money . $yh_txt;
        $fck = D('Fck');
        $fck->addencAdd($user['id'], $user['user_id'], $real_money, 19, 0, 0, 0, $kt_cont . '');
        
        M('fck')->where($where)->setInc('agent_use', $real_money);
        
        $money = $rs['epoint'] * C('recharge_parm');
        
        $money = $fee_rs['recharge_award'] * 0.01 * $money;
        $where = array();
        $where['id'] = $user['re_id'];
        $user = $fck->where($where)->find();
        if ($user != NULL) {
            $kt_cont = "下线充值" . C('agent_use') . ',返点' . $money;
            $fck = D('Fck');
            $fck->addencAdd($user['id'], $user['user_id'], $money, 19, 0, 0, 0, $kt_cont . '');
            
            M('fck')->where($where)->setInc('agent_kt', $money);
        }
    }

    /**
     * 将xml转为array
     *
     * @param string $xml            
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
        if (! $xml) {
            throw new WxPayException("xml数据异常！");
        }
        // 将XML转为array
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

    function zfb_order_notify()
    {
        $msg = array();
        file_put_contents("zfb_order_notify.txt", json_encode($_POST));
        $trade_no = $_POST['out_trade_no'];
        $trade_id = $_POST['trade_no'];
        $chongzhi = M('orders'); //
        $where = array();
        $where['order_no'] = $trade_no;
        file_put_contents("trade_no.txt", $trade_no);
        
        $result = ($_POST);
        
        IF ($result['trade_status'] == 'TRADE_SUCCESS') {
            
            $rs = $chongzhi->where($where)->find();
            file_put_contents("json_encode.txt", json_encode($rs));
            if ($rs != NULL) {
                $this->update_order($where, $rs, $trade_id);
                return 'success';
            }
        }
        echo "success";
    }

    function order_notify()
    {
        $msg = array();
        $trade_no = $_POST['trade_no'];
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        file_put_contents("trade_no.txt", $trade_no);
        $data = array();
        $data['remark'] = 'DHGFHFG';
        $data['trade_no'] = $xml;
        
        // $rs2 = $chongzhi->add($data);
        libxml_disable_entity_loader(true);
        $data2 = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        
        file_put_contents("HTTP_RAW_POST_DATA.txt", $data2['out_trade_no']);
        $trade_no = $data2['out_trade_no'];
        $chongzhi = M('orders'); //
        $where = array();
        $where['order_no'] = $trade_no;
        $rs = $chongzhi->where($where)->find();
        if ($rs != NULL) {
            
            $this->update_order($where, $rs);
        }
    }

    function update_order($where, $rs, $transaction_id)
    {
        $chongzhi = M('orders');
        $order = $chongzhi->where($where)->find();
        IF ($order['payment_status'] == 0) {
            $chongzhi->where($where)->setField('trade_no', $transaction_id);
            $chongzhi->where($where)->setField('payment_status', 2);
            $chongzhi->where($where)->setField('payment_time', time());
            $chongzhi->where($where)->setField('status', 2);
            $chongzhi->where($where)->setField('express_status', 1);
        }
        $order = $chongzhi->where($where)->find();
        IF ($order['payment_status'] == 2) {
            create_order_terminal($rs, 0);
        }
    }

    function zfb_apply_saoma_notify()
    {
        $msg = array();
        file_put_contents("zfb_order_notify.txt", json_encode($_POST));
        $out_trade_no = $_POST['out_trade_no'];
        $trade_no = $_POST['trade_no'];
        $chongzhi = M('huikui'); //
        $where = array();
        $where['order_no'] = $out_trade_no;
        
        file_put_contents("out_trade_no.txt", $where);
        $rs = $chongzhi->where($where)->find();
        if ($rs != NULL) {
            file_put_contents("huikuan.txt", 12312312321);
            $this->update_saoma_order_status($where, $rs, $trade_no);
            return 'success';
        }
        
        echo "success";
    }

    function apply_saoma_notify()
    {
        $msg = array();
        $trade_no = $_POST['trade_no'];
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        file_put_contents("trade_no.txt", $trade_no);
        $data = array();
        $data['remark'] = 'DHGFHFG';
        $data['trade_no'] = $xml;
        
        // $rs2 = $chongzhi->add($data);
        libxml_disable_entity_loader(true);
        $data2 = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        
        $trade_no = $data2['out_trade_no'];
        $chongzhi = M('huikui'); //
        $where = array();
        $where['order_no'] = $trade_no;
        $rs = $chongzhi->where($where)->find();
        if ($rs != NULL) {
            
            $this->update_saoma_order_status($where, $rs);
        }
    }

    function update_saoma_order_status($where, $rs, $transaction_id)
    {
        $chongzhi = M('huikui');
        $order = $chongzhi->where($where)->find();
        IF ($order['is_pay'] == 0) {
            $chongzhi->where($where)->setField('trade_no', $transaction_id);
            $chongzhi->where($where)->setField('is_pay', 1);
            $chongzhi->where($where)->setField('payment_time', time());
        }
        $order = $chongzhi->where($where)->find();
        IF ($order['is_pay'] == 1) {
            create_saoma_order_terminal($rs, 1);
        }
    }
}

?>
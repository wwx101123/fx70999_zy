<?php

class TaskAction extends Action
{

    function _initialize()
    {}

    public function init_data($PTid = 0)
    {
        // 删除会员
        $fck = M('fck');
        $list = $fck->field('id,is_boss,is_gd')
            ->where('team_trade_money>0')
            ->select();
        foreach ($list as $k => $userInfo) {
            init_month_data($userInfo);
        }
    }
    public  function  test_award(){
        $userModel = M('fck')->where('id=11221')->find();
        user_award($userModel, 100,$userModel['user_id']);
    }
    public function init_trade_data($PTid = 0)
    {
        set_time_limit(3600);
        $url = 'https://ykagent.yeahka.com//ykagent/statistic/queryTradeOrderList.do';
        $post_data = array();
        $post_data['startTime'] = date("Y-m-d", strtotime("-1 day"));
        $post_data['endTime'] = date('Y-m-d');
        $post_data['pageNum'] = 1;
        $post_data['pageSize'] = 1000;
        $post_data['token'] = 'ykagent-token-ccd775da-d254-4e88-b212-8ab9c44dd066';
        $list = request_get($url, $post_data);
        
        file_put_contents("ykagent.txt", $list);
        
        $list = json_decode($list, true);
        
        $order_list = $list['data'];
        $fee = M('fee');
        $fee_rs = $fee->find(1);
        $trade_money_fee = explode('|', $fee_rs['s9']);
        $user_num_fee = explode('|', $fee_rs['user_num']);
        $user_money = explode('|', $fee_rs['user_money']);
        foreach (array_reverse($order_list) as $i => $rs) {
            
            
          
            
            
            
            $seller_no = trim($rs['customerNo']);
            $trade_money = trim($rs['amount']);
            $order_no = trim($rs['summaryId']);
            $trade_time = trim($rs['createTime']);
            
            $card_type = trim($rs['cardType']);
            if ($card_type == 2) {
                $card_type = '信用卡';
            }
            $shop_name = trim($rs['custShortName']);
            $upload_type = 'web';
            $trade_money = $trade_money / 100;
            if ($order_no=='1902220756418418'||$order_no==1902222107452953)
            {
                $TT=0;
            }
            $count = M('trade_orders')->where(ARRAY(
                'order_no' => $order_no
            ))->count();
            if ($count == 0&&$rs['status']==5) {
                create_trade_order($seller_no, $trade_money, $order_no, $trade_time, $card_type, $user_money, $shop_name, $upload_type);
            }
        }
    }
}
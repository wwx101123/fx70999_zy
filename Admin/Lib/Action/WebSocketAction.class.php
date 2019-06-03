<?php

class WebSocketAction extends CommonAction
{

    public function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
    }

    /**
     * 推送一个字符串
     */
    public function push_msg()
    {
       
        $data = array();
        $data['type'] = "update_terminal_num";
        $data['msg'] = "您有机器已下发";
        push_msg($data, 10956);
    }

    /**
     * 推送一个字符串
     */
    public function push_update_hot_goods()
    {
        $field = 'g.title as category,t.* ';
        $list = M('goods')->alias('t')
            ->join("xt_article_category AS g ON   g.id = t.category_id     ", 'left')
            ->where('t.is_hot=1')
            ->field($field)
            ->order(' t.id desc,T.is_hot desc')
            ->select();
        foreach ($list as $i => $goods) {
            
            $list[$i]['remain_end_time'] = $goods['real_end_time'] - time();
            
            $list[$i]['addtime_str'] = date("Y-m-d H:i:s", $goods["addtime"]);
            
            $list[$i]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img']);
            if (strpos($list[$i]['img'], 'http') !== false) {} else {
                $list[$i]['img'] = C('oss_url') . $list[$i]['img'];
            }
            
            $remain_end_time = timediff(time(), $goods['real_end_time']);
            
            $list[$i]['remain_end_time'] = $goods['real_end_time'] - time();
        }
        $data = array();
        $data['type'] = "update_hot_goods";
        $data['goods_list'] = $list;
        push_msg($data);
        
        $this->ajaxSuccess('发送成功！');
        exit();
    }
}
?>
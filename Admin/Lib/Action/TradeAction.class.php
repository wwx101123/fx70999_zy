<?php

class TradeAction extends CommonAction
{

    function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        $this->_inject_check(0); // 调用过滤函数
        $this->_checkUser();
        
        // $market_type = array();
        // $coin_on = array();
        // $market = M('Market')->select();
        // foreach ($market as $k => $v) {
        // $v['new_price'] = round($v['new_price'], $v['round']);
        // $v['buy_price'] = round($v['buy_price'], $v['round']);
        // $v['sell_price'] = round($v['sell_price'], $v['round']);
        // $v['min_price'] = round($v['min_price'], $v['round']);
        // $v['max_price'] = round($v['max_price'], $v['round']);
        // $v['xnb'] = explode('_', $v['name'])[0];
        // $v['rmb'] = explode('_', $v['name'])[1];
        // $v['xnbimg'] = C('coin')[$v['xnb']]['img'];
        // $v['rmbimg'] = C('coin')[$v['rmb']]['img'];
        // $v['volume'] = $v['volume'] * 1;
        // $v['change'] = $v['change'] * 1;
        // $v['title'] = C('coin')[$v['xnb']]['title'] . '(' . strtoupper($v['xnb']) . '/' . strtoupper($v['rmb']) . ')';
        
        // $market_type[$v['xnb']] = $v['name'];
        // $coin_on[] = $v['xnb'];
        
        // $marketList['market'][$v['name']] = $v;
        // }
        // C($marketList);
        
        if ($_POST['is_mobile'] == 1) {
            
            $_SESSION[C('USER_AUTH_KEY')] = $_POST['user_id'];
        }
        $this->_Config_name();
    }

    public function sell()
    {
        // $this->checkUpdata();
        $where = array();
        
        // if ($field && $name) {
        // if ($field == 'username') {
        // $where['userid'] = M('fck')->where(array('user_id' => $name))->getField('id');
        // }
        // else {
        // $where[$field] = $name;
        // }
        // }
        
        // if ($market) {
        // $where['market'] = $market;
        // }
        
        $where['userid'] = array(
            'gt',
            0
        );
        $where['type'] = array(
            'eq',
            2
        );
        if ($status) {
            $where['status'] = $status;
        }
        
        if ($status == 0 && $status != null) {
            $where['status'] = 0;
        }
        
        $count = M('Trade')->where($where)->count();
        
        import("@.ORG.Page"); // 导入分页类
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $list = M('Trade')->where($where)
            ->order('id desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        
        foreach ($list as $k => $v) {
            $list[$k]['username'] = M('fck')->where(array(
                'id' => $v['userid']
            ))->getField('user_id');
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display('sell');
    }

    public function index()
    {
        // $this->checkUpdata();
        $where = array();
        $type = $_GET['type'];
        $keyword = $_GET['keywords'];
        $name = $_GET['username'];
        $s_time = $this->_get('time1');
        $e_time = $this->_get('time2');
        $status = $_GET['status'];
        
        if (! EMPTY($s_time)) {
            $where['t.addtime'] = array(
                array(
                    'egt',
                    strtotime($s_time)
                )
            );
        }
        if (! EMPTY($e_time)) {
            $where['t.addtime'] = array(
                
                array(
                    'elt',
                    strtotime($e_time)
                )
            );
        }
        
        $this->assign('s_time', $s_time);
        $this->assign('e_time', $e_time);
        if (! EMPTY($name)) {
            
            $where['t.userid'] = M('fck')->where(array(
                'user_id' => $name
            ))->getField('id');
        }
        $this->assign('username', $name);
        if (! EMPTY($keyword)) {
            
            $where['h.title'] = array(
                'like' => '%' . $keyword . '%'
            );
        }
        $this->assign('keyword', $keyword);
        $type_str = '';
        
        IF ($type == 1) {
            $type_str = '交易中';
            $where['t.shouquan_id'] = array(
                'eq',
                0
            );
        }
        IF ($type == 2) {
            // $type_str = '已成交';
            $where['t.shouquan_id'] = array(
                'gt',
                0
            );
        }
        $this->assign('type', $type);
        IF ($status == 1) {
            
            $where['t.status'] = array(
                'eq',
                1
            );
            $where['t.is_yd'] = array(
                'eq',
                0
            );
        }
        IF ($status == 2) {
            
            $where['t.status'] = array(
                'eq',
                1
            );
            $where['t.is_yd'] = array(
                'eq',
                1
            );
        }
        IF ($status == 3) {
            
            $where['t.status'] = array(
                'eq',
                0
            );
        }
        IF ($status == 4) {
            
            $where['t.is_cancel'] = array(
                'eq',
                1
            );
        }
        // if ($market) {
        // $where['market'] = $market;
        // }
        
        // $where['userid'] = array(
        // 'gt',
        // 0
        // );
        $where['t.type'] = array(
            'eq',
            2
        );
        $where['t.payment'] = array(
            'eq',
            0
        );
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        
        $where['_string'] = "1=1   and (g.re_path LIKE '%," . $user_id . ",%' OR g.id=" . $user_id . " )  ";
        $size = C('ONE_PAGE_RE');
        $p = $this->_param('p', true, 1);
        $count = M('Trade')->alias('t')
            ->join("xt_fck AS g ON   g.id = t.userid", 'LEFT')
            ->join("xt_goods AS h ON  h.id = t.goods_id", 'LEFT')
            ->where($where)
            ->count();
        
        import("@.ORG.Page"); // 导入分页类
        $Page = new Page($count, $size);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $list = M('Trade')->alias('t')
            ->join("xt_fck AS g ON   g.id = t.userid", 'LEFT')
            ->join("xt_goods AS h ON  h.id = t.goods_id", 'LEFT')
            ->join("xt_shouquan AS P ON P.id = t.shouquan_id", 'LEFT')
            ->where($where)
            ->field('P.re_id,(SELECT user_id from xt_fck where xt_fck.ID=P.re_id) AS  shouquan_user_id,T.*,h.title,(SELECT sum(price) FROM xt_trade_log where xt_trade_log.trade_id=t.id) as all_money  ,(SELECT COUNT(ID) FROM xt_trade_log where xt_trade_log.trade_id=t.id) as trade_num ')
            ->order('t.sort_id desc,t.id desc')
            ->page($p . ',' . $size)
            ->select();
        
        foreach ($list as $k => $v) {
            $list[$k]['username'] = M('fck')->where(array(
                'id' => $v['userid']
            ))->getField('user_id');
            $list[$k]['trade_status'] = get_trade_status($v);
            
            $MONEY = get_help_money($v);
            
            $list[$k]['deal_num'] = ($v['deal'] / $v['max']);
            $list[$k]['goods_num'] = ($v['price'] / $v['max']);
            
            $list[$k]['shouquan_user'] = '';
            IF ($v['shouquan_id'] > 0) {
                $list[$k]['shouquan_user'] = '';
                $list[$k]['trade_type'] = '授权单' . '【授权方:' . $list[$k]['shouquan_user_id'] . '】';
            } else {
                $list[$k]['trade_type'] = '普通单';
            }
            
            IF ($v['is_tiqu'] == 1) {
                $list[$k]['tiqu_status_str'] = '已提取';
            } else {
                $list[$k]['tiqu_status_str'] = '未提取';
            }
            
            $list[$k]['manager_user_id'] = '';
            $list[$k]['manager_user_name'] = '';
            $manager = get_manager($v['userid']);
            if ($manager != null) {
                
                $list[$k]['manager_user_id'] = $manager['user_id'];
                $list[$k]['manager_user_name'] = $manager['user_name'];
            }
        }
        $this->assign('status', $status);
        $this->assign('type_str', $type_str);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display('index');
    }

    public function user_trade_huikuan()
    {
        $order_id = I("post.trade_id", 284652);
        $payment = I("post.payment", 1);
        $form = M('trade_log');
        if ($payment == 1) {
            $form = M('trade');
        }
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['is_user_collect'] == 2) {
            
            $this->ajaxError('已回款，不能重复处理！');
            return;
        }
        
        $model['user_collect_time'] = time();
        $model['is_user_collect'] = 1;
        $ret = $form->where('id=' . $order_id)->save($model);
        
        $bUrl = __URL__ . '/express';
        $this->ajaxSuccess('回款成功！', $bUrl);
    }

    public function user_trade_express()
    {
        $order_id = I("post.trade_id", 284652);
        $payment = I("post.payment", 1);
        $form = M('trade_log');
        if ($payment == 1) {
            $form = M('trade');
        }
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['is_express'] == 2) {
            
            $this->ajaxError('已收货，不能重复处理！');
            return;
        }
        
        $fee = M('fee')->field('s7')->find();
        
        $money_time1 = $fee['s7'];
        $end_money_time = strtotime("+" . $money_time1 . " minute", $model["express_time"]);
        $now = time();
        if ($end_money_time > $now) {
            $this->ajaxError('对不起,您暂时不能收货！');
            return;
        }
        if ($payment == 0) {
            
            $trade = M('trade')->where('id=' . $model['trade_id'])->find();
            // $trade_count = M('trade')->where(' addtime>'.$trade['addtime'].' AND status=1 and is_yd=1 and userid=' . $trade['userid'])->count();
            // if($trade_count==0)
            // {
            // // $this->ajaxError('对不起,您必须再进货一单打完预付款,才可收货！');
            // // return;
            // }
            $goods = M('goods')->field('price,agent_use')
                ->where('id=' . $trade['goods_id'])
                ->find();
            $count = M('trade_log')->where('trade_id=' . $trade['id'] . ' AND status!=0 ')->count();
            if ($trade['deal'] == $trade['price'] && $count == 0) {
                
                M('trade')->where('ID=' . $trade['id'])->setField('is_express', 2);
                M('trade')->where('ID=' . $trade['id'])->setField('status', 0);
                M('trade')->where('ID=' . $trade['id'])->setField('endtime', time());
                
                M('trade')->where('ID=' . $trade['id'])->setField('complete_time', time());
            }
        }
        if ($payment == 1) {
            
            $trade = M('trade')->where('id=' . $model['id'])->find();
            $sell = M('fck')->field('*')
                ->where('id=' . $trade['userid'])
                ->find();
            $goods = M('goods')->field('price,agent_use,agent_bi,limit_money')
                ->where('id=' . $trade['goods_id'])
                ->find();
            // $agent_bi = (int) ($trade['price'] / $goods['agent_bi']);
            
            $ssb = $goods['agent_use'] * $trade['num'];
            IF ($ssb > 0) {
                $jjbz = '进货成功,获得' . C('agent_use') . $ssb;
                $fck = D('Fck');
                $news_id = $fck->addencAdd($sell['id'], $sell['user_id'], $ssb, 1, 0, 0, 0, $jjbz);
                update_goods_history($news_id, $trade['id'], $trade_detail['id']);
                
                M('fck')->execute('UPDATE __TABLE__ SET `agent_use`=agent_use+' . $ssb . '      where  id  =' . $sell['id']);
            }
        }
        $model['user_express_time'] = time();
        $model['is_express'] = 2;
        $ret = $form->where('id=' . $order_id)->save($model);
        $user = M('fck')->field('id')
            ->where('id=' . $model['sell_userid'])
            ->find();
        set_user_order_num($user);
        // 推送数量
        
        $fck_num = M('fck_num')->where('uid=' . $user['id'])->find();
        
        $data = array();
        $data['type'] = "update_order_num";
        $data['order_num'] = $fck_num;
        $data['uid'] = $user['id'];
        
        push_msg($data, $user['id']);
        
        $user = M('fck')->field('id')
            ->where('id=' . $model['userid'])
            ->find();
        set_user_help_order_num($user);
        // 推送数量
        
        $fck_num = M('fck_num')->where('uid=' . $user['id'])->find();
        
        $data = array();
        $data['type'] = "update_order_num";
        $data['order_num'] = $fck_num;
        $data['uid'] = $user['id'];
        
        push_msg($data, $user['id']);
        
        if (! $ret) {
            $this->error('订单发货失败！');
            
            return;
        }
        $bUrl = __URL__ . '/express';
        $this->ajaxSuccess('收货成功！', $bUrl);
    }

    public function user_trade_tiqu()
    {
        $order_id = I("post.trade_id", 284948);
        $payment = $this->_post("payment", 0);
        $form = M('trade');
        // if ($payment == 1) {
        // $form = M('trade');
        // }
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['is_tiqu'] == 1) {
            
            $this->ajaxError('已提取，不能重复处理！');
            return;
        }
        
        $fee = M('fee')->field('s7')->find();
        
        $money_time1 = $fee['s7'];
        // $end_money_time = strtotime("+" . $money_time1 . " minute", $model["express_time"]);
        // $now = time();
        // if ($end_money_time > $now) {
        // $this->ajaxError('对不起,您暂时不能收货！');
        // return;
        // }
        if ($payment == 0) {
            // 压单判断
            $trade = $model;
            $userid = $trade['userid'];
            $sell = M('fck')->field('*')
                ->where('id=' . $userid)
                ->find();
            if ($sell['is_lock'] == 1) {
                $this->ajaxError('对不起，您已被冻结！');
                return;
            }
            $new_trade = M('trade')->where(' is_cancel=0 AND is_yd=0 and  addtime>' . $trade['addtime'] . '    and   userid=' . $userid)->find();
            
            if ($new_trade!=null) {
                $new_trade_log = M('trade_log')->where(' trade_id=' . $new_trade['id'])->count();
                if($new_trade_log==0) { 
                    $data = array();
                    $data['msg'] = '对不起,您已进货,准备下单吧！';
                    $data['info'] ='对不起,您已进货,准备下单吧！';
                    
                    $data['is_goods'] = 1;
                    $data['status'] = 0;
                    $this->ajaxReturn($data);
                }
                if($new_trade_log>0) { 
                    
                    $data = array();
                    $data['msg'] = '对不起,您已下单,请打完预付款再进行提取积分！';
                    $data['info'] ='对不起,您已下单,请打完预付款再进行提取积分！';
                    
                    $data['status'] = 0;
                    $this->ajaxReturn($data);
                }
            }
            
            $trade_count = get_new_trade($trade['addtime'], $trade['userid']);
            if ($trade_count == NULL) {
                $data = array();
                $data['msg'] = '对不起,您必须再进货一单不能小于' . $model['price'] . ',并且打完预付款,才可提取' . C('agent_use');
                $data['info'] = '对不起,您必须再进货一单不能小于' . $model['price'] . ',并且打完预付款,才可提取' . C('agent_use');
                
                $data['is_tiqu'] = 0;
                $data['status'] = 0;
                $this->ajaxReturn($data);
            }
            $trade_log = M('trade_log')->field('goods_id,num')
                ->where('trade_id=' . $trade['id'])
                ->order('price asc ')
                ->find();
            $goods = M('goods')->field('price,agent_use,agent_bi,limit_money,crown_price')
                ->where('id=' . $trade_log['goods_id'])
                ->find();
            $trade_count = M('trade')->where('userid=' . $trade['userid'] . ' AND complete_time>0 and id<' . $trade['id'])->count();
            
            $money = $goods['limit_money'];
            IF ($trade_count > 0) {
                $money = $goods['crown_price'];
            }
            
            if ($trade['deal'] == $trade['price']) {
                
                $agent_bi = (int) ($trade['price'] / $goods['agent_bi']);
                $percent = 100;
                $shouquan_user = null;
                $shouquan = null;
                if ($trade['shouquan_id'] > 0) {
                    $shouquan = M('shouquan')->where('id=' . $trade['shouquan_id'])->find();
                    $userid = $shouquan['re_id'];
                    $shouquan_user = M('fck')->field('*')
                        ->where('id=' . $userid)
                        ->find();
                    $percent = $shouquan['profit'];
                }
                
                $limit_money = $money * $agent_bi * $percent * 0.01;
                $agent_use = $money * $agent_bi * $percent * 0.01;
                if ($agent_use > 0) {
                    
                    if ($shouquan_user != NULL) {
                        IF ($shouquan_user['u_level'] == 1) {
                            
                            $agent_use = $goods['agent_use'] * $trade_log['num'];
                            
                            $jjbz = '进货成功,获得' . C('agent_use') . $agent_use;
                            $fck = D('Fck');
                            $news_id = $fck->addencAdd($shouquan_user['id'], $shouquan_user['user_id'], $agent_use, 1, 0, 0, 0, $jjbz, 0);
                            update_goods_history($shouquan_user, $trade['id'], $trade['id']);
                            
                            M('fck')->execute('UPDATE __TABLE__ SET `agent_use`=agent_use+' . $agent_use . ' where id =' . $shouquan_user['id']);
                        }
                        $jjbz = '进货成功,获得' . C('agent_use') . $agent_use;
                        $fck = D('Fck');
                        $news_id = $fck->addencAdd($sell['id'], $sell['user_id'], $agent_use, 1, 0, 0, 0, $jjbz, 0);
                        update_goods_history($news_id, $trade['id'], $trade['id']);
                        M('fck')->execute('UPDATE __TABLE__ SET `agent_use`=agent_use+' . $agent_use . ' where id =' . $sell['id']);
                    } else {
                        
                        // $count = M('trade_log')
                        // ->where('trade_id=' . $trade['id'].' AND money_uid>0')
                        // ->();
                        
                        $jjbz = '进货成功,获得' . C('agent_use') . $agent_use;
                        $fck = D('Fck');
                        $news_id = $fck->addencAdd($sell['id'], $sell['user_id'], $agent_use, 1, 0, 0, 0, $jjbz, 0);
                        update_goods_history($news_id, $trade['id'], $trade['id']);
                        
                        M('fck')->execute('UPDATE __TABLE__ SET `agent_use`=agent_use+' . $agent_use . ' where id =' . $sell['id']);
                    }
                    // $trade = M('trade')->where('id=' . $model['id'])->find();
                    
                    IF ($limit_money > 0) {
                        
                        // $jjbz = '进货成功,获得' . C('agent_use') . $limit_money;
                        // $fck = D('Fck');
                        // $news_id = $fck->addencAdd($sell['id'], $sell['user_id'], $limit_money, 1, 0, 0, 0, $jjbz);
                        // update_goods_history($news_id, $trade['id'], $trade['id']);
                        
                        // M('fck')->execute('UPDATE __TABLE__ SET `agent_use`=agent_use+' . $limit_money . ' where id =' . $sell['id']);
                    }
                }
                $agent_bi = (int) ($trade['price'] / $goods['agent_bi']);
                
                $userid = $trade['userid'];
                
                $money = $trade['price'];
                $ssb = $money;
                if ($ssb > 0) {
                    $shouquan = null;
                    $buy = M('fck')->field('*')
                        ->where('id=' . $userid)
                        ->find();
                    IF ($trade['shouquan_id'] > 0) {
                        $shouquan = M('shouquan')->where('id=' . $trade['shouquan_id'])->find();
                        $userid = $shouquan['re_id'];
                        $buy = M('fck')->where('id=' . $userid)->find();
                        $trade_sum = M('trade')->where('shouquan_id=' . $trade['shouquan_id'] . ' ')->sum('price');
                        
                        if ($trade_sum == NULL) {
                            $trade_sum = 0;
                        }
                        IF ($trade_sum >= $shouquan['money']) {
                            M('shouquan')->where('id=' . $shouquan['id'])->setField('is_back', 1);
                        }
                    }
                    $max_price = 0;
                    $money = $ssb - $max_price;
                    $max_trade = M('trade')->where(' userid=' . $sell['id'] . ' and is_yd=1 AND ID!=' . $trade['id'] . ' AND addtime<' . $trade['addtime'])
                        ->order('price desc')
                        ->find();
                    IF ($max_trade != null) {
                        $max_price = $max_trade['price'];
                        
                        $money = $trade['price'] - $max_trade['price'];
                    }
                    // IF ($trade['shouquan_id'] == 0) {
                    user_award($sell, $money, $ssb);
                    // }
                }
                
                // 资助人获得本金
                $trade_log = M('trade_log')->where('trade_id=' . $trade['id'] . ' AND money_uid>0')->select();
                
                foreach ($trade_log as $key => $rs) {
                    $jjbz = '本金返还,资助人获得' . C('agent_use') . '本金' . $rs['need_money'];
                    $fck = D('Fck');
                    $news_id = $fck->addencAdd($rs['money_uid'], $rs['money_user_id'], $rs['need_money'], 1, 0, 0, 0, $jjbz, 0);
                    update_goods_history($news_id, $trade['id'], $trade['id']);
                    
                    M('fck')->execute('UPDATE __TABLE__ SET `agent_use`=agent_use+' . $rs['need_money'] . ' where id =' . $rs['money_uid']);
                    
                    $price = $rs['price'] - $rs['need_money'];
                    
                    $jjbz = '本金返还,本人获得' . C('agent_use') . '本金' . $price;
                    $fck = D('Fck');
                    $news_id = $fck->addencAdd($rs['sell_userid'], $rs['money_user_id'], $price, 1, 0, 0, 0, $jjbz, 0);
                    update_goods_history($news_id, $trade['id'], $trade['id']);
                    
                    M('fck')->execute('UPDATE __TABLE__ SET `agent_use`=agent_use+' . $price . ' where id =' . $rs['sell_userid']);
                }
                
                M('trade')->where('ID=' . $trade['id'])->setField('is_tiqu', 1);
                // M('trade')->where('ID=' . $trade['id'])->setField('status', 0);
                M('trade')->where('ID=' . $trade['id'])->setField('tiqu_time', time());
            }
        }
        $user = $fck->where('id=' . $sell['id'])->find();
        $data = array();
        $data['type'] = "update_user_money";
        $data['user'] = $user;
        $data['uid'] = $user['id'];
        push_msg($data, $user['id']);
        $bUrl = __URL__ . '/express';
        $this->ajaxSuccess('提取成功！', $bUrl);
    }

    public function trade_express()
    {
        $order_id = $this->_get("id");
        $form = M('trade');
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['is_express'] == 1) {
            
            $this->ajaxError('已发货，不能重复处理！');
            return;
        }
        // $express = $this->_post("express");
        // $express_no = $this->_post("express_no");
        // if ($express == '') {
        // $this->error('请输入快递公司！');
        // return;
        // }
        // if ($express_no == '') {
        // $this->error('请输入快递编号！');
        // return;
        // }
        // $model['express'] = $express;
        // $model['express_no'] = $express_no;
        // $model['express_status'] = 2;
        $model['express_time'] = time();
        $model['is_express'] = 1;
        $ret = $form->where('id=' . $order_id)->save($model);
        
        if (! $ret) {
            $this->ajaxError('订单发货失败！');
            
            return;
        }
        $bUrl = __URL__ . '/express';
        $this->ajaxSuccess('发货成功！', $bUrl);
    }

    public function express()
    {
        // $this->checkUpdata();
        $where = array();
        $field = $_GET['field'];
        $name = $_GET['name'];
        $status = $_GET['status'];
        
        if (! EMPTY($field) && ! EMPTY($name)) {
            if ($field == 'username') {
                $where['t.userid'] = M('fck')->where(array(
                    'user_id' => $name
                ))->getField('id');
            } else {
                $where[$field] = $name;
            }
        }
        $type_str = '';
        
        IF ($status == 0) {
            $type_str = '交易中';
            $where['t.status'] = array(
                'eq',
                0
            );
        }
        IF ($status == 1) {
            $type_str = '已成交';
            $where['t.status'] = array(
                'eq',
                1
            );
        }
        IF ($status == 2) {
            $type_str = '已撤销';
            $where['t.status'] = array(
                'eq',
                2
            );
        }
        // if ($market) {
        // $where['market'] = $market;
        // }
        
        // $where['userid'] = array(
        // 'gt',
        // 0
        // );
        $where['t.type'] = array(
            'eq',
            2
        );
        
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        
        $where['_string'] = "1=1 and t.status=0 AND T.payment=1    and (g.re_path LIKE '%," . $user_id . ",%' OR g.id=" . $user_id . " ) ";
        $size = 20;
        $p = $this->_param('p', true, 1);
        $count = M('Trade')->alias('t')
            ->join("xt_fck AS g ON   g.id = t.userid", 'LEFT')
            ->join("xt_goods AS h ON  h.id = t.goods_id", 'LEFT')
            ->where($where)
            ->count();
        
        import("@.ORG.Page"); // 导入分页类
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $list = M('Trade')->alias('t')
            ->join("xt_fck AS g ON   g.id = t.userid", 'LEFT')
            ->join("xt_goods AS h ON  h.id = t.goods_id", 'LEFT')
            ->where($where)
            ->field('T.*,h.title,(SELECT sum(price) FROM xt_trade_log where xt_trade_log.trade_id=t.id) as all_money  ,(SELECT COUNT(ID) FROM xt_trade_log where xt_trade_log.trade_id=t.id) as trade_num ')
            ->order('t.sort_id desc,t.id desc')
            ->page($p . ',' . $size)
            ->select();
        
        foreach ($list as $k => $v) {
            $list[$k]['username'] = M('fck')->where(array(
                'id' => $v['userid']
            ))->getField('user_id');
            $list[$k]['trade_status'] = get_trade_status($v);
        }
        $this->assign('status', $status);
        $this->assign('type_str', $type_str);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display('express');
    }

    public function log()
    {
        $field = $_GET['field'];
        $name = $_GET['name'];
        $where = array();
        // $where['status']=0;
        if ($field && $name) {
            if ($field == 'username') {
                $where['userid'] = M('fck')->where(array(
                    'user_id' => $name
                ))->getField('id');
            } else if ($field == 'peername') {
                $where['sell_userid'] = M('fck')->where(array(
                    'user_id' => $name
                ))->getField('id');
            } else {
                $where[$field] = $name;
            }
        }
        
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        $where['_string'] = ' 1=1     and (g.re_path LIKE "%,' . $user_id . ',%" OR g.id=' . $user_id . ' )';
        // $where['userid'] = array(
        // 'gt',
        // 0
        // );
        
        // if ($market) {
        // $where['market'] = $market;
        // }
        
        $count = M('trade_log')->alias('t')
            ->join("xt_fck AS g ON   g.id = t.userid", 'LEFT')
            ->where($where)
            ->count();
        
        import("@.ORG.Page"); // 导入分页类
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $list = M('trade_log')->alias('t')
            ->join("xt_fck AS g ON   g.id = t.userid", 'LEFT')
            ->where($where)
            ->order('t.id desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        
        foreach ($list as $k => $v) {
            $list[$k]['username'] = M('fck')->where(array(
                'id' => $v['userid']
            ))->getField('user_id');
            $list[$k]['peername'] = M('fck')->where(array(
                'id' => $v['sell_userid']
            ))->getField('user_id');
            
            $list[$k]['trade_status'] = get_trade_log_status($v);
            // $list[$k]['img_url'] = 'http://www.hmboli.com/' . $list[$k]['img_url'];
            
            check_status($v['id']);
            
            $list[$k]['manager_user_id'] = '';
            $list[$k]['manager_user_name'] = '';
            $manager = get_manager($v['userid']);
            if ($manager != null) {
                
                $list[$k]['manager_user_id'] = $manager['user_id'];
                $list[$k]['manager_user_name'] = $manager['user_name'];
            }
            $list[$k]['manager_user_id1'] = '';
            $list[$k]['manager_user_name1'] = '';
            $manager = get_manager($v['sell_userid']);
            if ($manager != null) {
                
                $list[$k]['manager_user_id1'] = $manager['user_id'];
                $list[$k]['manager_user_name1'] = $manager['user_name'];
            }
        }
        
        $this->assign('list', $list);
        $this->assign('page', $show);
        
        $this->display('log');
    }

    public function tousu_log()
    {
        $where = array();
        
        // if ($field && $name) {
        // if ($field == 'username') {
        // $where['userid'] = M('User')->where(array('username' => $name))->getField('id');
        // }
        // else if ($field == 'peername') {
        // $where['peerid'] = M('User')->where(array('username' => $name))->getField('id');
        // }
        // else {
        // $where[$field] = $name;
        // }
        // }
        
        $where['userid'] = array(
            'gt',
            0
        );
        $where['is_tousu'] = array(
            'eq',
            1
        );
        // if ($market) {
        // $where['market'] = $market;
        // }
        
        $count = M('trade_log')->where($where)->count();
        
        import("@.ORG.Page"); // 导入分页类
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $list = M('trade_log')->where($where)
            ->order('id desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        
        foreach ($list as $k => $v) {
            $list[$k]['username'] = M('fck')->where(array(
                'id' => $v['userid']
            ))->getField('user_id');
            $list[$k]['peername'] = M('fck')->where(array(
                'id' => $v['sell_userid']
            ))->getField('user_id');
            
            $list[$k]['trade_status'] = get_trade_log_status($v);
            check_status($v['id']);
            
            $list[$k]['tousu_img_url'] = '/' . $v['tousu_img_url'];
        }
        
        $this->assign('list', $list);
        $this->assign('page', $show);
        
        $this->display('tousu_log');
    }

    public function chexiao($id = NULL)
    {
        if ($_POST['is_mobile'] == 1) {
            $id = $_POST['id'];
        }
        
        $rs = D('Trade')->chexiao($id);
        
        if ($rs[0]) {
            
            if ($_POST['is_mobile'] == 1) {
                
                $this->success($rs[1], '', true);
            }
            
            $this->success($rs[1]);
        } else {
            $this->ajaxError($rs[1], '', false);
        }
    }

    public function chadui($id = NULL)
    {
        if ($_POST['is_mobile'] == 1) {
            $id = $_POST['id'];
        }
        
        $sort_id = M('Trade')->max('sort_id');
        M('Trade')->where('ID=' . $id)->setField('sort_id', ($sort_id + 1));
        
        $this->success('插队成功');
    }

    public function market()
    {
        $where = array();
        
        if ($field && $name) {
            if ($field == 'username') {
                $where['userid'] = M('User')->where(array(
                    'username' => $name
                ))->getField('id');
            } else {
                $where[$field] = $name;
            }
        }
        import("@.ORG.Page"); // 导入分页类
        $count = M('Market')->where($where)->count(); // 总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        
        $list = M('Market')->where($where)
            ->order('id desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        
        foreach ($list as $k => $v) {
            if ($v['begintrade']) {
                $begintradeqq_3479015851 = substr($v['begintrade'], 0, 5);
            } else {
                $begintradeqq_3479015851 = "00:00";
            }
            if ($v['endtrade']) {
                $endtradeqq_3479015851 = substr($v['endtrade'], 0, 5);
            } else {
                $endtradeqq_3479015851 = "23:59";
            }
            
            $list[$k]['tradetimeqq3479015851'] = $begintradeqq_3479015851 . "-" . $endtradeqq_3479015851;
        }
        
        $this->assign('list', $list);
        $this->assign('page', $show);
        
        $this->display('market');
    }

    public function marketEdit($id = NULL)
    {
        if (empty($_POST)) {
            if (empty($id)) {
                $this->data = array();
                
                $beginshi = "00";
                $beginfen = "00";
                $endshi = "23";
                $endfen = "59";
            } else {
                $market_xt = M('Market')->where(array(
                    'id' => $id
                ))->find();
                $this->data = $market_xt;
                
                if ($market_xt['begintrade']) {
                    $beginshi = explode(":", $market_xt['begintrade'])[0];
                    $beginfen = explode(":", $market_xt['begintrade'])[1];
                } else {
                    $beginshi = "00";
                    $beginfen = "00";
                }
                
                if ($market_xt['endtrade']) {
                    $endshi = explode(":", $market_xt['endtrade'])[0];
                    $endfen = explode(":", $market_xt['endtrade'])[1];
                } else {
                    $endshi = "23";
                    $endfen = "59";
                }
            }
            
            $this->assign('beginshi', $beginshi);
            $this->assign('beginfen', $beginfen);
            $this->assign('endshi', $endshi);
            $this->assign('endfen', $endfen);
            $this->display();
        } else {
            
            $round = array(
                0,
                1,
                2,
                3,
                4,
                5,
                6
            );
            
            if (! in_array($_POST['round'], $round)) {
                $this->error('小数位数格式错误！');
            }
            
            if ($_POST['id']) {
                $rs = M('Market')->where('id=' . $_POST['id'])->save($_POST);
            } else {
                $_POST['name'] = $_POST['sellname'] . '_' . $_POST['buyname'];
                unset($_POST['buyname']);
                unset($_POST['sellname']);
                
                if (M('Market')->where(array(
                    'name' => $_POST['name']
                ))->find()) {
                    $this->error('市场存在！');
                }
                
                $rs = M('Market')->add($_POST);
            }
            
            if ($rs) {
                $this->success('操作成功！');
            } else {
                $this->error('操作失败！');
            }
        }
    }

    public function getJsonTop($market = NULL, $ajax = 'json')
    {
        $market = $_POST['market'];
        $data = (APP_DEBUG ? null : S('getJsonTop' . $market));
        
        if (! $data) {
            if ($market) {
                $xnb = explode('_', $market)[0];
                $rmb = explode('_', $market)[1];
                
                foreach (C('market') as $k => $v) {
                    $v['xnb'] = explode('_', $v['name'])[0];
                    $v['rmb'] = explode('_', $v['name'])[1];
                    $data['list'][$k]['name'] = $v['name'];
                    $data['list'][$k]['img'] = $v['xnbimg'];
                    $data['list'][$k]['title'] = $v['title'];
                    $data['list'][$k]['new_price'] = $v['new_price'];
                }
                
                $data['info']['img'] = C('market')[$market]['xnbimg'];
                $data['info']['title'] = C('market')[$market]['title'];
                $data['info']['new_price'] = C('market')[$market]['new_price'];
                $data['info']['max_price'] = C('market')[$market]['max_price'];
                $data['info']['min_price'] = C('market')[$market]['min_price'];
                $data['info']['buy_price'] = C('market')[$market]['buy_price'];
                
                $zhmoney = C('market')[$market]['volume'] * C('market')[$market]['new_price'];
                if ($zhmoney >= 10000) {
                    $data['info']['all_price'] = (round($zhmoney / 10000, 2) * 1) . "万";
                } else {
                    $data['info']['all_price'] = round($zhmoney, 2) * 1;
                }
                
                $data['info']['sell_price'] = C('market')[$market]['sell_price'];
                $data['info']['volume'] = C('market')[$market]['volume'];
                $data['info']['change'] = C('market')[$market]['change'];
                S('getJsonTop' . $market, $data);
            }
        }
        
        if ($ajax) {
            
            $this->ajaxReturn($data);
            return;
        } else {
            return $data;
        }
    }

    public function upTrade($paypassword = NULL, $market = NULL, $price, $num, $type)
    {
        $price = $_POST['price'];
        $num = $_POST['num'];
        $type = $_POST['type'];
        $user_id = $_POST['user_id'];
        $market = $_POST['market'];
        $paypassword = $_POST['paypassword'];
        
        if (! check($price, 'double')) {
            $this->error('交易价格格式错误');
        }
        
        if (! check($num, 'double')) {
            $this->error('交易数量格式错误');
        }
        
        if (($type != 1) && ($type != 2)) {
            $this->error('交易类型格式错误');
        }
        
        $user = M('fck')->where(array(
            'id' => $user_id
        ))->find();
        
        if (md5($paypassword) != $user['passopen']) {
            // $this->error('交易密码错误！');
        }
        
        if (! C('market')[$market]) {
            $this->error('交易市场错误');
        } else {
            $xnb = explode('_', $market)[0];
            $rmb = explode('_', $market)[1];
        }
        
        if (! C('market')[$market]['trade']) {
            $this->error('当前市场禁止交易');
        }
        // TODO: SEPARATE
        
        $price = round(floatval($price), C('market')[$market]['round']);
        
        if (! $price) {
            $this->error('交易价格错误' . $price);
        }
        
        $num = round($num, 8 - C('market')[$market]['round']);
        
        if (! check($num, 'double')) {
            $this->error('交易数量错误');
        }
        
        if ($type == 1) {
            $min_price = (C('market')[$market]['buy_min'] ? C('market')[$market]['buy_min'] : 1.0E-8);
            $max_price = (C('market')[$market]['buy_max'] ? C('market')[$market]['buy_max'] : 10000000);
        } else if ($type == 2) {
            $min_price = (C('market')[$market]['sell_min'] ? C('market')[$market]['sell_min'] : 1.0E-8);
            $max_price = (C('market')[$market]['sell_max'] ? C('market')[$market]['sell_max'] : 10000000);
        } else {
            $this->error('交易类型错误');
        }
        
        if ($max_price < $price) {
            $this->error('交易价格超过最大限制！');
        }
        
        if ($price < $min_price) {
            $this->error('交易价格超过最小限制！');
        }
        
        $hou_price = C('market')[$market]['hou_price'];
        
        if ($hou_price) {
            if (C('market')[$market]['zhang']) {
                // TODO: SEPARATE
                $zhang_price = round(($hou_price / 100) * (100 + C('market')[$market]['zhang']), C('market')[$market]['round']);
                
                if ($zhang_price < $price) {
                    $this->error('交易价格超过今日涨幅限制！');
                }
            }
            
            if (C('market')[$market]['die']) {
                // TODO: SEPARATE
                $die_price = round(($hou_price / 100) * (100 - C('market')[$market]['die']), C('market')[$market]['round']);
                
                if ($price < $die_price) {
                    $this->error('交易价格超过今日跌幅限制！');
                }
            }
        }
        
        $user_coin = M('fck')->where(array(
            'id' => $user_id
        ))->find();
        
        if ($type == 1) {
            $trade_fee = C('market')[$market]['fee_buy'];
            
            if ($trade_fee) {
                $fee = round((($num * $price) / 100) * $trade_fee, 8);
                $mum = round((($num * $price) / 100) * (100 + $trade_fee), 8);
            } else {
                $fee = 0;
                $mum = round($num * $price, 8);
            }
            
            if ($user_coin[$rmb] < $mum) {
                $this->error(C('coin')[$rmb]['title'] . '余额不足！');
            }
        } else if ($type == 2) {
            $trade_fee = C('market')[$market]['fee_sell'];
            
            if ($trade_fee) {
                $fee = round((($num * $price) / 100) * $trade_fee, 8);
                $mum = round((($num * $price) / 100) * (100 - $trade_fee), 8);
            } else {
                $fee = 0;
                $mum = round($num * $price, 8);
            }
            
            if ($user_coin[$xnb] < $num) {
                $this->error(C('coin')[$xnb]['title'] . '余额不足！');
            }
        } else {
            $this->error('交易类型错误');
        }
        
        if (C('coin')[$xnb]['fee_bili']) {
            if ($type == 2) {
                // TODO: SEPARATE
                $bili_user = round($user_coin[$xnb] + $user_coin[$xnb . 'd'], C('market')[$market]['round']);
                
                if ($bili_user) {
                    // TODO: SEPARATE
                    $bili_keyi = round(($bili_user / 100) * C('coin')[$xnb]['fee_bili'], C('market')[$market]['round']);
                    
                    if ($bili_keyi) {
                        $bili_zheng = M()->query('select id,price,sum(num-deal)as nums from xt_trade where userid=' . $user_id . ' and status=0 and type=2 and market like \'%' . $xnb . '%\' ;');
                        
                        if (! $bili_zheng[0]['nums']) {
                            $bili_zheng[0]['nums'] = 0;
                        }
                        
                        $bili_kegua = $bili_keyi - $bili_zheng[0]['nums'];
                        
                        if ($bili_kegua < 0) {
                            $bili_kegua = 0;
                        }
                        
                        if ($bili_kegua < $num) {
                            $this->error('您的挂单总数量超过系统限制，您当前持有' . C('coin')[$xnb]['title'] . $bili_user . '个，已经挂单' . $bili_zheng[0]['nums'] . '个，还可以挂单' . $bili_kegua . '个', '', 5);
                        }
                    } else {
                        $this->error('可交易量错误');
                    }
                }
            }
        }
        
        if (C('coin')[$xnb]['fee_meitian']) {
            if ($type == 2) {
                $bili_user = round($user_coin[$xnb] + $user_coin[$xnb . 'd'], 8);
                
                if ($bili_user < 0) {
                    $this->error('可交易量错误');
                }
                
                $kemai_bili = ($bili_user / 100) * C('coin')[$xnb]['fee_meitian'];
                
                if ($kemai_bili < 0) {
                    $this->error('您今日只能再卖' . C('coin')[$xnb]['title'] . 0 . '个', '', 5);
                }
                
                $kaishi_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                $jintian_sell = M('Trade')->where(array(
                    'userid' => $user_id,
                    'addtime' => array(
                        'egt',
                        $kaishi_time
                    ),
                    'type' => 2,
                    'status' => array(
                        'neq',
                        2
                    ),
                    'market' => array(
                        'like',
                        '%' . $xnb . '%'
                    )
                ))->sum('num');
                
                if ($jintian_sell) {
                    $kemai = $kemai_bili - $jintian_sell;
                } else {
                    $kemai = $kemai_bili;
                }
                
                if ($kemai < $num) {
                    if ($kemai < 0) {
                        $kemai = 0;
                    }
                    
                    $this->error('您的挂单总数量超过系统限制，您今日只能再卖' . C('coin')[$xnb]['title'] . $kemai . '个', '', 5);
                }
            }
        }
        
        if (C('market')[$market]['trade_min']) {
            if ($mum < C('market')[$market]['trade_min']) {
                $this->error('交易总额不能小于' . C('market')[$market]['trade_min']);
            }
        }
        
        if (C('market')[$market]['trade_max']) {
            if (C('market')[$market]['trade_max'] < $mum) {
                $this->error('交易总额不能大于' . C('market')[$market]['trade_max']);
            }
        }
        
        if (! $rmb) {
            $this->error('数据错误1');
        }
        
        if (! $xnb) {
            $this->error('数据错误2');
        }
        
        if (! $market) {
            $this->error('数据错误3');
        }
        
        if (! $price) {
            $this->error('数据错误4');
        }
        
        if (! $num) {
            $this->error('数据错误5');
        }
        
        if (! $mum) {
            $this->error('数据错误6');
        }
        
        if (! $type) {
            $this->error('数据错误7');
        }
        
        $mo = M();
        $mo->execute('set autocommit=0');
        $mo->execute('lock tables xt_trade write ,xt_fck write ,xt_finance write');
        $rs = array();
        $user_coin = $mo->table('xt_fck')
            ->where(array(
            'id' => $user_id
        ))
            ->find();
        
        if ($type == 1) {
            if ($user_coin[$rmb] < $mum) {
                $this->error(C('coin')[$rmb]['title'] . '余额不足！');
            }
            
            $finance = $mo->table('xt_finance')
                ->where(array(
                'id' => $user_id
            ))
                ->order('id desc')
                ->find();
            $finance_num_user_coin = $mo->table('xt_fck')
                ->where(array(
                'id' => $user_id
            ))
                ->find();
            $rs[] = $mo->table('xt_fck')
                ->where(array(
                'id' => $user_id
            ))
                ->setDec($rmb, $mum);
            $rs[] = $mo->table('xt_fck')
                ->where(array(
                'id' => $user_id
            ))
                ->setInc($rmb . 'd', $mum);
            
            $rs[] = $finance_nameid = $mo->table('xt_trade')->add(array(
                'userid' => $user_id,
                'market' => $market,
                'price' => $price,
                'num' => $num,
                'mum' => $mum,
                'fee' => $fee,
                'type' => 1,
                'addtime' => time(),
                'status' => 0
            ));
            
            $finance_mum_user_coin = $mo->table('xt_fck')
                ->where(array(
                'id' => $user_id
            ))
                ->find();
            $finance_hash = md5($user_id . $mum . MSCODE . 'auth.btchanges.com');
            
            $finance_num = $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb . 'd'];
            
            if ($finance['mum'] < $finance_num) {
                $finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
            } else {
                $finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
            }
            
            $rs[] = $mo->table('xt_finance')->add(array(
                'userid' => $user_id,
                'coinname' => $rmb,
                'num_a' => $finance_num_user_coin[$rmb],
                'num_b' => $finance_num_user_coin[$rmb . 'd'],
                'num' => $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb . 'd'],
                'fee' => $mum,
                'type' => 2,
                'name' => 'trade',
                'nameid' => $finance_nameid,
                'remark' => '交易中心-委托买入-市场' . $market,
                'mum_a' => $finance_mum_user_coin[$rmb],
                'mum_b' => $finance_mum_user_coin[$rmb . 'd'],
                'mum' => $finance_mum_user_coin[$rmb] + $finance_mum_user_coin[$rmb . 'd'],
                'move' => $finance_hash,
                'addtime' => time(),
                'status' => $finance_status
            ));
        } else if ($type == 2) {
            if ($user_coin[$xnb] < $num) {
                $this->error(C('coin')[$xnb]['title'] . '余额不足！');
            }
            
            $rs[] = $mo->table('xt_fck')
                ->where(array(
                'id' => $user_id
            ))
                ->setDec($xnb, $num);
            $rs[] = $mo->table('xt_fck')
                ->where(array(
                'id' => $user_id
            ))
                ->setInc($xnb . 'd', $num);
            $rs[] = $mo->table('xt_trade')->add(array(
                'userid' => $user_id,
                'market' => $market,
                'price' => $price,
                'num' => $num,
                'mum' => $mum,
                'fee' => $fee,
                'type' => 2,
                'addtime' => time(),
                'status' => 0
            ));
        } else {
            $mo->execute('rollback');
            $mo->execute('unlock tables');
            $this->error('交易类型错误');
        }
        
        if (check_arr($rs)) {
            $mo->execute('commit');
            $mo->execute('unlock tables');
            S('getDepth', null);
            $this->matchingTrade($market);
            $this->success('进货成功！', '', true);
        } else {
            $mo->execute('rollback');
            $mo->execute('unlock tables');
            $this->error('交易失败！');
        }
    }

    public function matchingTrade($market = NULL)
    {
        if (! $market) {
            return false;
        } else {
            $xnb = explode('_', $market)[0];
            $rmb = explode('_', $market)[1];
        }
        
        $fee_buy = C('market')[$market]['fee_buy'];
        $fee_sell = C('market')[$market]['fee_sell'];
        $invit_buy = C('market')[$market]['invit_buy'];
        $invit_sell = C('market')[$market]['invit_sell'];
        $invit_1 = C('market')[$market]['invit_1'];
        $invit_2 = C('market')[$market]['invit_2'];
        $invit_3 = C('market')[$market]['invit_3'];
        $mo = M();
        $new_trade_btchanges = 0;
        
        for (; true;) {
            $buy = $mo->table('xt_trade')
                ->where(array(
                'market' => $market,
                'type' => 1,
                'status' => 0
            ))
                ->order('price desc,id asc')
                ->find();
            $sell = $mo->table('xt_trade')
                ->where(array(
                'market' => $market,
                'type' => 2,
                'status' => 0
            ))
                ->order('price asc,id asc')
                ->find();
            
            if ($sell['id'] < $buy['id']) {
                $type = 1;
            } else {
                $type = 2;
            }
            
            if ($buy && $sell && (0 <= floatval($buy['price']) - floatval($sell['price']))) {
                $rs = array();
                
                if ($buy['num'] <= $buy['deal']) {}
                
                if ($sell['num'] <= $sell['deal']) {}
                
                $amount = min(round($buy['num'] - $buy['deal'], 8 - C('market')[$market]['round']), round($sell['num'] - $sell['deal'], 8 - C('market')[$market]['round']));
                $amount = round($amount, 8 - C('market')[$market]['round']);
                
                if ($amount <= 0) {
                    $log = '错误1交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . "\n";
                    $log .= 'ERR: 成交数量出错，数量是' . $amount;
                    M('Trade')->where(array(
                        'id' => $buy['id']
                    ))->setField('status', 1);
                    M('Trade')->where(array(
                        'id' => $sell['id']
                    ))->setField('status', 1);
                    break;
                }
                
                if ($type == 1) {
                    $price = $sell['price'];
                } else if ($type == 2) {
                    $price = $buy['price'];
                } else {
                    break;
                }
                
                if (! $price) {
                    $log = '错误2交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . "\n";
                    $log .= 'ERR: 成交价格出错，价格是' . $price;
                    break;
                } else {
                    // TODO: SEPARATE
                    $price = round($price, C('market')[$market]['round']);
                }
                
                $mum = round($price * $amount, 8);
                
                if (! $mum) {
                    $log = '错误3交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . "\n";
                    $log .= 'ERR: 成交总额出错，总额是' . $mum;
                    mlog($log);
                    break;
                } else {
                    $mum = round($mum, 8);
                }
                
                if ($fee_buy) {
                    $buy_fee = round(($mum / 100) * $fee_buy, 8);
                    $buy_save = round(($mum / 100) * (100 + $fee_buy), 8);
                } else {
                    $buy_fee = 0;
                    $buy_save = $mum;
                }
                
                if (! $buy_save) {
                    $log = '错误4交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";
                    $log .= 'ERR: 买家更新数量出错，更新数量是' . $buy_save;
                    mlog($log);
                    break;
                }
                
                if ($fee_sell) {
                    $sell_fee = round(($mum / 100) * $fee_sell, 8);
                    $sell_save = round(($mum / 100) * (100 - $fee_sell), 8);
                } else {
                    $sell_fee = 0;
                    $sell_save = $mum;
                }
                
                if (! $sell_save) {
                    $log = '错误5交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";
                    $log .= 'ERR: 卖家更新数量出错，更新数量是' . $sell_save;
                    mlog($log);
                    break;
                }
                
                $user_buy = M('fck')->where(array(
                    'id' => $buy['userid']
                ))->find();
                
                if (! $user_buy[$rmb . 'd']) {
                    $log = '错误6交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";
                    $log .= 'ERR: 买家财产错误，冻结财产是' . $user_buy[$rmb . 'd'];
                    mlog($log);
                    break;
                }
                
                $user_sell = M('fck')->where(array(
                    'id' => $sell['userid']
                ))->find();
                
                if (! $user_sell[$xnb . 'd']) {
                    $log = '错误7交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";
                    $log .= 'ERR: 卖家财产错误，冻结财产是' . $user_sell[$xnb . 'd'];
                    mlog($log);
                    break;
                }
                
                if ($user_buy[$rmb . 'd'] < 1.0E-8) {
                    $log = '错误88交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";
                    $log .= 'ERR: 买家更新冻结人民币出现错误,应该更新' . $buy_save . '账号余额' . $user_buy[$rmb . 'd'] . '进行错误处理';
                    mlog($log);
                    M('Trade')->where(array(
                        'id' => $buy['id']
                    ))->setField('status', 1);
                    break;
                }
                
                if ($buy_save <= round($user_buy[$rmb . 'd'], 8)) {
                    $save_buy_rmb = $buy_save;
                } else if ($buy_save <= round($user_buy[$rmb . 'd'], 8) + 1) {
                    $save_buy_rmb = $user_buy[$rmb . 'd'];
                    $log = '错误8交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";
                    $log .= 'ERR: 买家更新冻结人民币出现误差,应该更新' . $buy_save . '账号余额' . $user_buy[$rmb . 'd'] . '实际更新' . $save_buy_rmb;
                    mlog($log);
                } else {
                    $log = '错误9交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";
                    $log .= 'ERR: 买家更新冻结人民币出现错误,应该更新' . $buy_save . '账号余额' . $user_buy[$rmb . 'd'] . '进行错误处理';
                    mlog($log);
                    M('Trade')->where(array(
                        'id' => $buy['id']
                    ))->setField('status', 1);
                    break;
                }
                // TODO: SEPARATE
                
                if ($amount <= round($user_sell[$xnb . 'd'], C('market')[$market]['round'])) {
                    $save_sell_xnb = $amount;
                } else {
                    // TODO: SEPARATE
                    
                    if ($amount <= round($user_sell[$xnb . 'd'], C('market')[$market]['round']) + 1) {
                        $save_sell_xnb = $user_sell[$xnb . 'd'];
                        $log = '错误10交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";
                        $log .= 'ERR: 卖家更新冻结虚拟币出现误差,应该更新' . $amount . '账号余额' . $user_sell[$xnb . 'd'] . '实际更新' . $save_sell_xnb;
                        mlog($log);
                    } else {
                        $log = '错误11交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";
                        $log .= 'ERR: 卖家更新冻结虚拟币出现错误,应该更新' . $amount . '账号余额' . $user_sell[$xnb . 'd'] . '进行错误处理';
                        mlog($log);
                        M('Trade')->where(array(
                            'id' => $sell['id']
                        ))->setField('status', 1);
                        break;
                    }
                }
                
                if (! $save_buy_rmb) {
                    $log = '错误12交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";
                    $log .= 'ERR: 买家更新数量出错错误,更新数量是' . $save_buy_rmb;
                    mlog($log);
                    M('Trade')->where(array(
                        'id' => $buy['id']
                    ))->setField('status', 1);
                    break;
                }
                
                if (! $save_sell_xnb) {
                    $log = '错误13交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount . '成交价格' . $price . '成交总额' . $mum . "\n";
                    $log .= 'ERR: 卖家更新数量出错错误,更新数量是' . $save_sell_xnb;
                    mlog($log);
                    M('Trade')->where(array(
                        'id' => $sell['id']
                    ))->setField('status', 1);
                    break;
                }
                $mo->execute('set autocommit=0');
                $mo->execute('lock tables xt_trade write ,xt_trade_log write ,xt_fck write ');
                $rs[] = $mo->table('xt_trade')
                    ->where(array(
                    'id' => $buy['id']
                ))
                    ->setInc('deal', $amount);
                $rs[] = $mo->table('xt_trade')
                    ->where(array(
                    'id' => $sell['id']
                ))
                    ->setInc('deal', $amount);
                $rs[] = $finance_nameid = $mo->table('xt_trade_log')->add(array(
                    'userid' => $buy['userid'],
                    'peerid' => $sell['userid'],
                    'market' => $market,
                    'price' => $price,
                    'num' => $amount,
                    'mum' => $mum,
                    'type' => $type,
                    'fee_buy' => $buy_fee,
                    'fee_sell' => $sell_fee,
                    'addtime' => time(),
                    'status' => 1
                ));
                $rs[] = $mo->table('xt_fck')
                    ->where(array(
                    'id' => $buy['userid']
                ))
                    ->setInc($xnb, $amount);
                $finance = $mo->table('xt_finance')
                    ->where(array(
                    'userid' => $buy['userid']
                ))
                    ->order('id desc')
                    ->find();
                $finance_num_user_coin = $mo->table('xt_fck')
                    ->where(array(
                    'id' => $buy['userid']
                ))
                    ->find();
                $rs[] = $mo->table('xt_fck')
                    ->where(array(
                    'id' => $buy['userid']
                ))
                    ->setDec($rmb . 'd', $save_buy_rmb);
                $finance_mum_user_coin = $mo->table('xt_fck')
                    ->where(array(
                    'id' => $buy['userid']
                ))
                    ->find();
                // 财务明细
                $finance_hash = md5($buy['userid'] . $finance_num_user_coin[$rmb] . $finance_num_user_coin[$rmb . 'd'] . $mum . $finance_mum_user_coin[$rmb] . $finance_mum_user_coin[$rmb . 'd'] . MSCODE . 'auth.btchanges.com');
                $finance_num = $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb . 'd'];
                
                if ($finance['mum'] < $finance_num) {
                    $finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
                } else {
                    $finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
                }
                
                $rs[] = $mo->table('xt_finance')->add(array(
                    'userid' => $buy['userid'],
                    'coinname' => $rmb,
                    'num_a' => $finance_num_user_coin[$rmb],
                    'num_b' => $finance_num_user_coin[$rmb . 'd'],
                    'num' => $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb . 'd'],
                    'fee' => $save_buy_rmb,
                    'type' => 2,
                    'name' => 'tradelog',
                    'nameid' => $finance_nameid,
                    'remark' => '交易中心-成功买入-市场' . $market,
                    'mum_a' => $finance_mum_user_coin[$rmb],
                    'mum_b' => $finance_mum_user_coin[$rmb . 'd'],
                    'mum' => $finance_mum_user_coin[$rmb] + $finance_mum_user_coin[$rmb . 'd'],
                    'move' => $finance_hash,
                    'addtime' => time(),
                    'status' => $finance_status
                ));
                $finance = $mo->table('xt_finance')
                    ->where(array(
                    'userid' => $buy['userid']
                ))
                    ->order('id desc')
                    ->find();
                $finance_num_user_coin = $mo->table('xt_fck')
                    ->where(array(
                    'id' => $sell['userid']
                ))
                    ->find();
                $rs[] = $mo->table('xt_fck')
                    ->where(array(
                    'id' => $sell['userid']
                ))
                    ->setInc($rmb, $sell_save);
                $finance_mum_user_coin = $mo->table('xt_fck')
                    ->where(array(
                    'id' => $sell['userid']
                ))
                    ->find();
                $finance_hash = md5($sell['userid'] . $finance_num_user_coin[$rmb] . $finance_num_user_coin[$rmb . 'd'] . $mum . $finance_mum_user_coin[$rmb] . $finance_mum_user_coin[$rmb . 'd'] . MSCODE . 'auth.btchanges.com');
                $finance_num = $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb . 'd'];
                
                if ($finance['mum'] < $finance_num) {
                    $finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
                } else {
                    $finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
                }
                
                $rs[] = $mo->table('xt_finance')->add(array(
                    'userid' => $sell['userid'],
                    'coinname' => $rmb,
                    'num_a' => $finance_num_user_coin[$rmb],
                    'num_b' => $finance_num_user_coin[$rmb . 'd'],
                    'num' => $finance_num_user_coin[$rmb] + $finance_num_user_coin[$rmb . 'd'],
                    'fee' => $save_buy_rmb,
                    'type' => 1,
                    'name' => 'tradelog',
                    'nameid' => $finance_nameid,
                    'remark' => '交易中心-成功卖出-市场' . $market,
                    'mum_a' => $finance_mum_user_coin[$rmb],
                    'mum_b' => $finance_mum_user_coin[$rmb . 'd'],
                    'mum' => $finance_mum_user_coin[$rmb] + $finance_mum_user_coin[$rmb . 'd'],
                    'move' => $finance_hash,
                    'addtime' => time(),
                    'status' => $finance_status
                ));
                $rs[] = $mo->table('xt_fck')
                    ->where(array(
                    'id' => $sell['userid']
                ))
                    ->setDec($xnb . 'd', $save_sell_xnb);
                $buy_list = $mo->table('xt_trade')
                    ->where(array(
                    'id' => $buy['id'],
                    'status' => 0
                ))
                    ->find();
                
                if ($buy_list) {
                    if ($buy_list['num'] <= $buy_list['deal']) {
                        $rs[] = $mo->table('xt_trade')
                            ->where(array(
                            'id' => $buy['id']
                        ))
                            ->setField('status', 1);
                    }
                }
                
                $sell_list = $mo->table('xt_trade')
                    ->where(array(
                    'id' => $sell['id'],
                    'status' => 0
                ))
                    ->find();
                
                if ($sell_list) {
                    if ($sell_list['num'] <= $sell_list['deal']) {
                        $rs[] = $mo->table('xt_trade')
                            ->where(array(
                            'id' => $sell['id']
                        ))
                            ->setField('status', 1);
                    }
                }
                
                if ($price < $buy['price']) {
                    $chajia_dong = round((($amount * $buy['price']) / 100) * (100 + $fee_buy), 8);
                    $chajia_shiji = round((($amount * $price) / 100) * (100 + $fee_buy), 8);
                    $chajia = round($chajia_dong - $chajia_shiji, 8);
                    
                    if ($chajia) {
                        $chajia_user_buy = $mo->table('xt_fck')
                            ->where(array(
                            'id' => $buy['userid']
                        ))
                            ->find();
                        
                        if ($chajia <= round($chajia_user_buy[$rmb . 'd'], 8)) {
                            $chajia_save_buy_rmb = $chajia;
                        } else if ($chajia <= round($chajia_user_buy[$rmb . 'd'], 8) + 1) {
                            $chajia_save_buy_rmb = $chajia_user_buy[$rmb . 'd'];
                            mlog('错误91交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount, '成交价格' . $price . '成交总额' . $mum . "\n");
                            mlog('交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '成交数量' . $amount . '交易方式：' . $type . '卖家更新冻结虚拟币出现误差,应该更新' . $chajia . '账号余额' . $chajia_user_buy[$rmb . 'd'] . '实际更新' . $chajia_save_buy_rmb);
                        } else {
                            mlog('错误92交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '交易方式：' . $type . '成交数量' . $amount, '成交价格' . $price . '成交总额' . $mum . "\n");
                            mlog('交易市场' . $market . '出错：买入订单:' . $buy['id'] . '卖出订单：' . $sell['id'] . '成交数量' . $amount . '交易方式：' . $type . '卖家更新冻结虚拟币出现错误,应该更新' . $chajia . '账号余额' . $chajia_user_buy[$rmb . 'd'] . '进行错误处理');
                            $mo->execute('rollback');
                            $mo->execute('unlock tables');
                            M('Trade')->where(array(
                                'id' => $buy['id']
                            ))->setField('status', 1);
                            M('Trade')->execute('commit');
                            break;
                        }
                        
                        if ($chajia_save_buy_rmb) {
                            $rs[] = $mo->table('xt_fck')
                                ->where(array(
                                'id' => $buy['userid']
                            ))
                                ->setDec($rmb . 'd', $chajia_save_buy_rmb);
                            $rs[] = $mo->table('xt_fck')
                                ->where(array(
                                'id' => $buy['userid']
                            ))
                                ->setInc($rmb, $chajia_save_buy_rmb);
                        }
                    }
                }
                
                $you_buy = $mo->table('xt_trade')
                    ->where(array(
                    'market' => array(
                        'like',
                        '%' . $rmb . '%'
                    ),
                    'status' => 0,
                    'userid' => $buy['userid']
                ))
                    ->find();
                $you_sell = $mo->table('xt_trade')
                    ->where(array(
                    'market' => array(
                        'like',
                        '%' . $xnb . '%'
                    ),
                    'status' => 0,
                    'userid' => $sell['userid']
                ))
                    ->find();
                
                if (! $you_buy) {
                    $you_user_buy = $mo->table('xt_fck')
                        ->where(array(
                        'id' => $buy['userid']
                    ))
                        ->find();
                    
                    if (0 < $you_user_buy[$rmb . 'd']) {
                        $rs[] = $mo->table('xt_fck')
                            ->where(array(
                            'id' => $buy['userid']
                        ))
                            ->setField($rmb . 'd', 0);
                        $rs[] = $mo->table('xt_fck')
                            ->where(array(
                            'id' => $buy['userid']
                        ))
                            ->setInc($rmb, $you_user_buy[$rmb . 'd']);
                    }
                }
                
                if (! $you_sell) {
                    $you_user_sell = $mo->table('xt_fck')
                        ->where(array(
                        'id' => $sell['userid']
                    ))
                        ->find();
                    
                    if (0 < $you_user_sell[$xnb . 'd']) {
                        $rs[] = $mo->table('xt_fck')
                            ->where(array(
                            'id' => $sell['userid']
                        ))
                            ->setField($xnb . 'd', 0);
                        $rs[] = $mo->table('xt_fck')
                            ->where(array(
                            'id' => $sell['userid']
                        ))
                            ->setInc($rmb, $you_user_sell[$xnb . 'd']);
                    }
                }
                
                $invit_buy_user = $mo->table('xt_fck')
                    ->where(array(
                    'id' => $buy['userid']
                ))
                    ->find();
                $invit_sell_user = $mo->table('xt_fck')
                    ->where(array(
                    'id' => $sell['userid']
                ))
                    ->find();
                
                if ($invit_buy) {
                    if ($invit_1) {
                        if ($buy_fee) {
                            if ($invit_buy_user['invit_1']) {
                                $invit_buy_save_1 = round(($buy_fee / 100) * $invit_1, 6);
                                
                                if ($invit_buy_save_1) {
                                    $rs[] = $mo->table('xt_fck')
                                        ->where(array(
                                        'id' => $invit_buy_user['invit_1']
                                    ))
                                        ->setInc($rmb, $invit_buy_save_1);
                                    $rs[] = $mo->table('xt_invit')->add(array(
                                        'userid' => $invit_buy_user['invit_1'],
                                        'invit' => $buy['userid'],
                                        'name' => '一代买入赠送',
                                        'type' => $market . '买入交易赠送',
                                        'num' => $amount,
                                        'mum' => $mum,
                                        'fee' => $invit_buy_save_1,
                                        'addtime' => time(),
                                        'status' => 1
                                    ));
                                }
                            }
                            
                            if ($invit_buy_user['invit_2']) {
                                $invit_buy_save_2 = round(($buy_fee / 100) * $invit_2, 6);
                                
                                if ($invit_buy_save_2) {
                                    $rs[] = $mo->table('xt_user_coin')
                                        ->where(array(
                                        'userid' => $invit_buy_user['invit_2']
                                    ))
                                        ->setInc($rmb, $invit_buy_save_2);
                                    $rs[] = $mo->table('xt_invit')->add(array(
                                        'userid' => $invit_buy_user['invit_2'],
                                        'invit' => $buy['userid'],
                                        'name' => '二代买入赠送',
                                        'type' => $market . '买入交易赠送',
                                        'num' => $amount,
                                        'mum' => $mum,
                                        'fee' => $invit_buy_save_2,
                                        'addtime' => time(),
                                        'status' => 1
                                    ));
                                }
                            }
                            
                            if ($invit_buy_user['invit_3']) {
                                $invit_buy_save_3 = round(($buy_fee / 100) * $invit_3, 6);
                                
                                if ($invit_buy_save_3) {
                                    $rs[] = $mo->table('xt_user_coin')
                                        ->where(array(
                                        'userid' => $invit_buy_user['invit_3']
                                    ))
                                        ->setInc($rmb, $invit_buy_save_3);
                                    $rs[] = $mo->table('xt_invit')->add(array(
                                        'userid' => $invit_buy_user['invit_3'],
                                        'invit' => $buy['userid'],
                                        'name' => '三代买入赠送',
                                        'type' => $market . '买入交易赠送',
                                        'num' => $amount,
                                        'mum' => $mum,
                                        'fee' => $invit_buy_save_3,
                                        'addtime' => time(),
                                        'status' => 1
                                    ));
                                }
                            }
                        }
                    }
                    
                    if ($invit_sell) {
                        if ($sell_fee) {
                            if ($invit_sell_user['invit_1']) {
                                $invit_sell_save_1 = round(($sell_fee / 100) * $invit_1, 6);
                                
                                if ($invit_sell_save_1) {
                                    $rs[] = $mo->table('xt_user_coin')
                                        ->where(array(
                                        'userid' => $invit_sell_user['invit_1']
                                    ))
                                        ->setInc($rmb, $invit_sell_save_1);
                                    $rs[] = $mo->table('xt_invit')->add(array(
                                        'userid' => $invit_sell_user['invit_1'],
                                        'invit' => $sell['userid'],
                                        'name' => '一代卖出赠送',
                                        'type' => $market . '卖出交易赠送',
                                        'num' => $amount,
                                        'mum' => $mum,
                                        'fee' => $invit_sell_save_1,
                                        'addtime' => time(),
                                        'status' => 1
                                    ));
                                }
                            }
                            
                            if ($invit_sell_user['invit_2']) {
                                $invit_sell_save_2 = round(($sell_fee / 100) * $invit_2, 6);
                                
                                if ($invit_sell_save_2) {
                                    $rs[] = $mo->table('xt_user_coin')
                                        ->where(array(
                                        'userid' => $invit_sell_user['invit_2']
                                    ))
                                        ->setInc($rmb, $invit_sell_save_2);
                                    $rs[] = $mo->table('xt_invit')->add(array(
                                        'userid' => $invit_sell_user['invit_2'],
                                        'invit' => $sell['userid'],
                                        'name' => '二代卖出赠送',
                                        'type' => $market . '卖出交易赠送',
                                        'num' => $amount,
                                        'mum' => $mum,
                                        'fee' => $invit_sell_save_2,
                                        'addtime' => time(),
                                        'status' => 1
                                    ));
                                }
                            }
                            
                            if ($invit_sell_user['invit_3']) {
                                $invit_sell_save_3 = round(($sell_fee / 100) * $invit_3, 6);
                                
                                if ($invit_sell_save_3) {
                                    $rs[] = $mo->table('xt_user_coin')
                                        ->where(array(
                                        'userid' => $invit_sell_user['invit_3']
                                    ))
                                        ->setInc($rmb, $invit_sell_save_3);
                                    $rs[] = $mo->table('xt_invit')->add(array(
                                        'userid' => $invit_sell_user['invit_3'],
                                        'invit' => $sell['userid'],
                                        'name' => '三代卖出赠送',
                                        'type' => $market . '卖出交易赠送',
                                        'num' => $amount,
                                        'mum' => $mum,
                                        'fee' => $invit_sell_save_3,
                                        'addtime' => time(),
                                        'status' => 1
                                    ));
                                }
                            }
                        }
                    }
                }
                
                if (check_arr($rs)) {
                    $mo->execute('commit');
                    $mo->execute('unlock tables');
                    $new_trade_btchanges = 1;
                    $coin = $xnb;
                    S('allsum', null);
                    S('getJsonTop' . $market, null);
                    S('getTradelog' . $market, null);
                    S('getDepth' . $market . '1', null);
                    S('getDepth' . $market . '3', null);
                    S('getDepth' . $market . '4', null);
                    S('ChartgetJsonData' . $market, null);
                    S('allcoin', null);
                    S('trends', null);
                } else {
                    $mo->execute('rollback');
                    $mo->execute('unlock tables');
                }
            } else {
                break;
            }
            
            unset($rs);
        }
        
        if ($new_trade_btchanges) {
            $new_price = round(M('trade_log')->where(array(
                'market' => $market,
                'status' => 1
            ))
                ->order('id desc')
                ->getField('price'), 6);
            $buy_price = round(M('Trade')->where(array(
                'type' => 1,
                'market' => $market,
                'status' => 0
            ))->max('price'), 6);
            $sell_price = round(M('Trade')->where(array(
                'type' => 2,
                'market' => $market,
                'status' => 0
            ))->min('price'), 6);
            $min_price = round(M('trade_log')->where(array(
                'market' => $market,
                'addtime' => array(
                    'gt',
                    time() - (60 * 60 * 24)
                )
            ))->min('price'), 6);
            $max_price = round(M('trade_log')->where(array(
                'market' => $market,
                'addtime' => array(
                    'gt',
                    time() - (60 * 60 * 24)
                )
            ))->max('price'), 6);
            $volume = round(M('trade_log')->where(array(
                'market' => $market,
                'addtime' => array(
                    'gt',
                    time() - (60 * 60 * 24)
                )
            ))->sum('num'), 6);
            $sta_price = round(M('trade_log')->where(array(
                'market' => $market,
                'status' => 1,
                'addtime' => array(
                    'gt',
                    time() - (60 * 60 * 24)
                )
            ))
                ->order('id asc')
                ->getField('price'), 6);
            $Cmarket = M('Market')->where(array(
                'name' => $market
            ))->find();
            
            if ($Cmarket['new_price'] != $new_price) {
                $upCoinData['new_price'] = $new_price;
            }
            
            if ($Cmarket['buy_price'] != $buy_price) {
                $upCoinData['buy_price'] = $buy_price;
            }
            
            if ($Cmarket['sell_price'] != $sell_price) {
                $upCoinData['sell_price'] = $sell_price;
            }
            
            if ($Cmarket['min_price'] != $min_price) {
                $upCoinData['min_price'] = $min_price;
            }
            
            if ($Cmarket['max_price'] != $max_price) {
                $upCoinData['max_price'] = $max_price;
            }
            
            if ($Cmarket['volume'] != $volume) {
                $upCoinData['volume'] = $volume;
            }
            
            $change = round((($new_price - $Cmarket['hou_price']) / $Cmarket['hou_price']) * 100, 2);
            $upCoinData['change'] = $change;
            
            if ($upCoinData) {
                M('Market')->where(array(
                    'name' => $market
                ))->save($upCoinData);
                M('Market')->execute('commit');
                S('home_market', null);
            }
        }
    }

    function edit_trade()
    {
        $fee = M('fee');
        $fee_rs = $fee->field('str18,s3,recommend_gupiao,recommend_order,jjbb,s16,ssb_money,ssb_shouxu,trade_bs,str13,str14,s7,s13')->find(1);
        // $this->_Admin_checkUser();
        $fck = M('fck');
        if (! $fck->autoCheckToken($_POST)) {
            $this->ajaxError('页面过期，请刷新页面！');
            exit();
        }
        $price = $this->_post('price');
        $UserID = $this->_post('user_id');
        $quantity = $this->_post('quantity');
        $market = I('post.market', 'ssb');
        $num = I('post.num', '1');
        $type = I('post.type', '2');
        $payment = I('post.payment', 0);
        $bankcard = $this->_post('bankcard');
        $remark = $this->_post('remark');
        $min = $this->_post('min', true, 0);
        $is_password = $this->_post('is_password', true, '0');
        $password = $this->_post('password', true, '0');
        $area = $this->_post('area');
        $currency = $this->_post('currency');
        $opentype = $this->_post('opentype');
        $goods_id = $this->_post('goods_id');
        
        $goods = M('goods')->where(array(
            'id' => $goods_id
        ))
            ->field('*')
            ->find();
        
        $price = $goods['price'];
        $max = $this->_post('max', true, $price);
        $crown_price = $goods['crown_price1'] * $num;
        if ($payment == 1) {
            $crown_price = $goods['price'] * $num;
        }
        $fee = I('post.fee', '1');
        
        if (is_numeric($price) == false) {
            $this->ajaxError('金额错误，请重新输入！');
            exit();
        }
        if ($type == 0) {
            $this->ajaxError('请选择交易类型！');
            exit();
        }
        
        if ($payment == 1) {
            if ($goods['is_payment'] == 0) {
                $this->ajaxError('此商品不允许使用' . C('limit_money') . '进货！');
                exit();
            }
        }
        
        if (($goods['stock'] - $num) <= 0) {
            $this->ajaxError('此商品库存不足！');
            exit();
        }
        
        if (empty($market)) {
            // $this->error('请选择市场方式！');
            // exit();
        }
        if (empty($bankcard)) {
            // $this->error('请输入收款卡号！');
            // exit();
        }
        if (is_numeric($min) == false) {
            $this->ajaxError('请输入最小额度！');
            exit();
        }
        if (is_numeric($max) == false) {
            $this->ajaxError('请输入最大额度！');
            exit();
        }
        if ($max < $min) {
            $this->ajaxError('最小值不能大于最大值');
            exit();
        }
        
        if ($min > $price) {
            $this->ajaxError('最小值不能小于' . $price);
            exit();
        }
        if ($max > $price) {
            $this->ajaxError('最大值不能大于' . $price);
            exit();
        }
        if ($min % $fee_rs['trade_bs'] != 0) {
            $this->ajaxError('最小值必须是' . $fee_rs['trade_bs'] . '的倍数');
        }
        if ($max % $fee_rs['trade_bs'] != 0) {
            // $this->ajaxError('最大值必须是' . $fee_rs['trade_bs'] . '的倍数');
        }
        if ($payment == 0) {
            if ($num % $fee_rs['trade_bs'] != 0) {
                $this->ajaxError('进货必须是' . $fee_rs['trade_bs'] . '的倍数');
            }
        }
        $str18 = $fee_rs['str18'];
        $str18 = explode('-', $str18);
        $s13 = $fee_rs['s13'];
        $s13 = explode('-', $s13);
        
        if (! empty($UserID) && ! empty($price)) {
            $where = array();
            $where['id'] = $UserID;
            
            $frs = $fck->where($where)
                ->field('id,re_path,u_level,is_lock,nickname,is_agent,user_id,user_name,ssb,agent_use,limit_money,is_pay,bank_card,app_version')
                ->find();
            
            if ($frs) {
                if ($frs['is_lock'] == 1) {
                    $this->ajaxError('您已冻结,不能下单');
                    exit();
                }
                if ($frs['is_pay'] == 0) {
                    $this->ajaxError('您不是VIP会员,不能下单');
                    exit();
                }
                if ($frs['app_version'] < C('app_version')) {
                    $this->ajaxError('您不是最新版本,不能下单');
                    exit();
                }
                
                if ($payment == 0) {
                    if ($frs['agent_use'] < $crown_price) {
                        $this->ajaxError(C('agent_use') . '不足');
                        exit();
                    }
                }
                if ($payment == 1) {
                    if ($frs['limit_money'] < $crown_price) {
                        $this->ajaxError(C('limit_money') . '不足');
                        exit();
                    }
                }
                
                if ($payment == 0) {
                    if ($frs['u_level'] == 1) {
                        if ($price * $num < $s13[0]) {
                            $this->ajaxError('会员进货总价不能少于' . $s13[0] . '');
                        }
                        if ($price * $num > $s13[1]) {
                            $this->ajaxError('会员进货总价不能大于' . $s13[1] . '');
                        }
                    }
                    
                    if ($frs['u_level'] == 2) {
                        if ($price * $num < $str18[0]) {
                            $this->ajaxError('代理商进货总价不能少于' . $str18[0] . '');
                        }
                        if ($price * $num > $str18[1]) {
                            $this->ajaxError('代理商进货总价不能大于' . $str18[1] . '');
                        }
                    }
                }
                
                if ($payment == 0 && $frs['u_level'] == 1) {
                    $shouquan = M('shouquan')->where('uid=' . $frs['id'] . ' AND is_back=0 and status=0 ')
                        ->order('id desc')
                        ->find();
                    
                    $trade_sum = M('trade')->where('shouquan_id=' . $shouquan['id'] . ' ')->sum('price');
                    
                    if ($trade_sum == NULL) {
                        $trade_sum = 0;
                    }
                    
                    if ($shouquan != null) {
                        if ($shouquan['money'] < ($price * $num + $trade_sum)) {
                            $this->ajaxError('您只能进货满' . $shouquan['money']);
                            exit();
                        }
                    }
                }
                $address_count = M('user_addr_book')->where('user_id=' . $UserID)->count();
                
                if ($address_count == 0) {
                    
                    $ret_data['status'] = 0;
                    $ret_data['is_address'] = 1;
                    $ret_data['info'] = '请先编辑收货地址';
                    $ret_data['msg'] = '请先编辑收货地址';
                    $this->ajaxReturn($ret_data);
                    exit();
                }
                if (EMPTY($frs['bank_card'])) {
                    
                    $ret_data['status'] = 0;
                    $ret_data['is_bank'] = 1;
                    $ret_data['info'] = '请编辑您的银行卡号！';
                    $ret_data['msg'] = '请编辑您的银行卡号！';
                    $this->ajaxReturn($ret_data);
                    exit();
                }
                if (EMPTY($frs['nickname'])) {
                    $this->ajaxError('请编辑您的昵称再进行发布！');
                    exit();
                }
                $mobile = $this->_post('mobile', $frs['user_id']);
                if (EMPTY($mobile)) {
                    // $this->error('请编辑您的联系方式！'.$mobile);
                    // exit();
                }
                $chongzhi = M('trade');
                
                if ($payment == 0) {
                    // 判断有没有正在交易的记录
                    $trade_count = $chongzhi->where('userid=' . $frs['id'] . '   AND status=1')->count();
                    if ($trade_count > 0) {
                        $this->ajaxError('您有正在进货的订单,不可重复提交！');
                        exit();
                    }
                }
                
                // if ($payment == 0) {
                // $new_trade = $chongzhi->where('userid=' . $frs['id'])
                // ->order(' ID DESC ')
                // ->find();
                // if ($new_trade != null) {
                // if ($new_trade['status'] == 0 && $new_trade['price'] > $price * $num) {
                // $this->ajaxError('您必须预购一款不低于' . $new_trade['price'] . '的产品！');
                // exit();
                // }
                // }
                // }
                $ssb_money = $fee_rs['ssb_money'];
                $ssb_shouxu = $fee_rs['ssb_shouxu'];
                $data = array();
                $data['userid'] = $frs['id'];
                $data['user_name'] = $frs['user_name'];
                $data['addtime'] = time();
                $data['market'] = $market;
                $data['goods_id'] = $goods_id;
                $data['price'] = $price * $num;
                $new_trade = M('trade')->where('  status=0 and   userid=' . $frs['id'])
                    ->order(' id desc ')
                    ->find();
                
                if ($payment == 0) {
                    if ($new_trade != NULL) {
                        IF ($new_trade['price'] > $data['price']) {
                            $this->ajaxError('您必须进货不小于' . $new_trade['price'] . '！');
                            exit();
                        }
                    }
                }
                if ($frs['u_level'] == 1) {
                    $trade_count = M('trade')->where('userid=' . $frs['id'])->count();
                    $shouquan_count = M('shouquan')->where('uid=' . $frs['id'])->count();
                    if ($trade_count == 0 && $shouquan_count == 0) {
                        
                        $vo = M('fck')->where('id in (0' . $frs['re_path'] . '0) AND u_level=2 ')
                            ->order(' re_level desc ')
                            ->find();
                        if ($vo != NULL) {
                            if ($vo['live_gupiao'] < $price * $num) {
                                $this->ajaxError($vo['user_id'] . '代理商' . C('live_gupiao') . '不足');
                                exit();
                            }
                        }
                        $shouquan_model['uid'] = $frs['id'];
                        $shouquan_model['re_id'] = $vo['id'];
                        $shouquan_model['add_time'] = time();
                        $shouquan_model['money'] = $price * $num;
                        
                        // $s1 = explode(':', $fee_rs['s1']);
                        // $fee_rs['s1'] = $s1[0] / $s1[1];
                        // $fee_rs['s1'] = $s1[0];
                        
                        $profit = 100;
                        
                        $shouquan_model['profit'] = $profit;
                        $shouquan_model['status'] = 0;
                        M('shouquan')->add($shouquan_model);
                        
                        $fck = D('Fck');
                        $kt_cont = '授权分享,' . $frs['user_id'] . ',扣除' . $price * $num;
                        $news_id = $fck->addencAdd($vo['id'], $vo['user_id'], - ($price * $num), 19, 0, 0, 0, $kt_cont . '-' . C('live_gupiao'));
                        
                        $fck->where(array(
                            'id' => $vo['id']
                        ))->setDec('live_gupiao', ($price * $num));
                        
                        update_goods_history($news_id, 0, 0);
                    }
                }
                if ($payment == 0 && $frs['u_level'] == 1) {
                    $shouquan = M('shouquan')->where('uid=' . $frs['id'] . ' AND is_back=0 and status=0 ')
                        ->order('id desc')
                        ->find();
                    
                    $trade_sum = M('trade')->where('shouquan_id=' . $shouquan['id'] . ' ')->sum('price');
                    
                    if ($trade_sum == NULL) {
                        $trade_sum = 0;
                    }
                    
                    if ($shouquan != null) {
                        if ($shouquan['money'] < ($price * $num + $trade_sum)) {
                            $this->ajaxError('您只能进货满' . $shouquan['money']);
                            exit();
                        }
                    }
                }
                $data['num'] = $num;
                $data['deal'] = 0;
                
                if ($payment == 1) {
                    
                    $data['deal'] = $data['price'];
                }
                $data['mum'] = $num;
                $data['stock'] = $num;
                $data['type'] = $type;
                $data['fee'] = $fee_rs['ssb_shouxu'];
                $data['payment'] = $payment;
                $data['bankcard'] = $bankcard;
                $data['min'] = $min;
                
                $data['max'] = $max;
                $data['remark'] = $remark;
                $data['area'] = $area;
                $data['currency'] = $currency;
                $data['opentype'] = $opentype;
                $data['ssb_money'] = $ssb_money;
                $data['ssb_shouxu'] = $ssb_shouxu;
                $data['all_price'] = (100 - $ssb_shouxu) * 0.01 * $price;
                $data['status'] = 1;
                if ($payment == 1) {
                    
                    $data['status'] = 0;
                }
                $data['is_hot'] = 1;
                $data['is_password'] = $is_password;
                $data['password'] = $password;
                $data['mobile'] = $mobile;
                $data['guarantee_money'] = $guarantee_money;
                
                $shouquan = get_shouquan($frs['id']);
                
                if ($shouquan != NULL) {
                    $data['shouquan_id'] = $shouquan['id'];
                }
                
                $result = $chongzhi->add($data);
                if ($payment == 0) {
                    if ($crown_price > 0) {
                        $fck = D('Fck');
                        $kt_cont = '预购产品,扣除' . C('agent_use') . $crown_price;
                        $news_id = $fck->addencAdd($frs['id'], $frs['user_id'], - ($crown_price), 19, 0, 0, 0, $kt_cont);
                        
                        $fck->where(array(
                            'id' => $frs['id']
                        ))->setDec('agent_use', ($crown_price));
                    }
                }
                if ($payment == 1) {
                    if ($crown_price > 0) {
                        $fck = D('Fck');
                        $kt_cont = '预购产品,扣除' . C('limit_money') . $crown_price;
                        $news_id = $fck->addencAdd($frs['id'], $frs['user_id'], - ($crown_price), 19, 0, 0, 0, $kt_cont);
                        
                        $fck->where(array(
                            'id' => $frs['id']
                        ))->setDec('limit_money', ($crown_price));
                    }
                    
                    // 更新销售者的数据
                    set_user_order_num($frs);
                    // 推送数量
                    
                    $fck_num = M('fck_num')->where('uid=' . $frs['id'])->find();
                    
                    $data = array();
                    $data['type'] = "update_order_num";
                    $data['order_num'] = $fck_num;
                    $data['uid'] = $frs['id'];
                    
                    push_msg($data, $frs['id']);
                }
                
                $rearray[] = $result;
                unset($data, $chongzhi);
            } else {
                $this->ajaxError('没有该会员，请重新输入!');
            }
            unset($fck, $frs, $where, $UserID, $ePoints);
        } else {
            $this->ajaxError('系统繁忙!');
        }
        
        $this->ajaxSuccess('确认提交！');
    }

    function new_edit_trade()
    {
        $fee = M('fee');
        $fee_rs = $fee->field('str18,s3,recommend_gupiao,recommend_order,jjbb,s16,ssb_money,ssb_shouxu,trade_bs,str13,str14,s7,s13,i7,s17')->find(1);
        // $this->_Admin_checkUser();
        $fck = M('fck');
        if (! $fck->autoCheckToken($_POST)) {
            $this->ajaxError('页面过期，请刷新页面！');
            exit();
        }
        $price = $this->_post('price');
        $UserID = $this->_post('user_id');
        $quantity = $this->_post('quantity');
        $market = I('post.market', 'ssb');
        $num = I('post.num', '1');
        $type = I('post.type', '2');
        $payment = I('post.payment', 0);
        $bankcard = $this->_post('bankcard');
        $remark = $this->_post('remark');
        $min = $this->_post('min', true, 0);
        $is_password = $this->_post('is_password', true, '0');
        $password = $this->_post('password', true, '0');
        $area = $this->_post('area');
        $currency = $this->_post('currency');
        $opentype = $this->_post('opentype');
        $goods_id = $this->_post('goods_id');
        
        if ($goods_id > 0) {
            $goods = M('goods')->where(array(
                'id' => $goods_id
            ))
                ->field('*')
                ->find();
            
            $price = $goods['price'];
            $max = $this->_post('max', true, $price);
            $crown_price = $goods['crown_price1'] * $num;
            if ($payment == 1) {
                $crown_price = $goods['price'] * $num;
            }
        } else {
            // 新的进货功能
            $price = $this->_post('price');
            
            $crown_price = $price * $num;
        }
        $fee = I('post.fee', '1');
        
        if (is_numeric($price) == false) {
            $this->ajaxError('金额错误，请重新输入！');
            exit();
        }
        if ($type == 0) {
            $this->ajaxError('请选择交易类型！');
            exit();
        }
        
        if ($payment == 1) {
            if ($goods['is_payment'] == 0) {
                $this->ajaxError('此商品不允许使用' . C('limit_money') . '进货！');
                exit();
            }
        }
        if ($goods_id > 0) {
            if (($goods['stock'] - $num) <= 0) {
                $this->ajaxError('此商品库存不足！');
                exit();
            }
        }
        
        if (empty($market)) {
            // $this->error('请选择市场方式！');
            // exit();
        }
        if (empty($bankcard)) {
            // $this->error('请输入收款卡号！');
            // exit();
        }
        // if (is_numeric($min) == false) {
        // $this->ajaxError('请输入最小额度！');
        // exit();
        // }
        // if (is_numeric($max) == false) {
        // $this->ajaxError('请输入最大额度！');
        // exit();
        // }
        // if ($max < $min) {
        // $this->ajaxError('最小值不能大于最大值');
        // exit();
        // }
        
        // if ($min > $price) {
        // $this->ajaxError('最小值不能小于' . $price);
        // exit();
        // }
        // if ($max > $price) {
        // $this->ajaxError('最大值不能大于' . $price);
        // exit();
        // }
        if ($min % $fee_rs['trade_bs'] != 0) {
            $this->ajaxError('最小值必须是' . $fee_rs['trade_bs'] . '的倍数');
        }
        if ($max % $fee_rs['trade_bs'] != 0) {
            // $this->ajaxError('最大值必须是' . $fee_rs['trade_bs'] . '的倍数');
        }
        if ($goods_id > 0) {
            if ($payment == 0) {
                if ($num % $fee_rs['trade_bs'] != 0) {
                    $this->ajaxError('进货必须是' . $fee_rs['trade_bs'] . '的倍数');
                }
            }
        }
        
        $str18 = $fee_rs['str18'];
        $str18 = explode('-', $str18);
        $s13 = $fee_rs['s13'];
        $s13 = explode('-', $s13);
        
        if (! empty($UserID) && ! empty($price)) {
            $where = array();
            $where['id'] = $UserID;
            
            $frs = $fck->where($where)
                ->field('id,re_path,u_level,is_lock,nickname,is_agent,user_id,user_name,ssb,agent_use,limit_money,is_pay,bank_card,register_type')
                ->find();
            
            if ($frs) {
                if ($frs['is_lock'] == 1) {
                    $this->ajaxError('您已冻结,不能下单');
                    exit();
                }
                if ($frs['is_pay'] == 0) {
                    $this->ajaxError('您不是VIP会员,不能下单');
                    exit();
                }
                $cart_count = M('cart')->where('uid=' . $frs['id'] . '')->count();
                if ($cart_count > 0) {
                    $this->ajaxError('购物车有未结算的商品,不能进货');
                    exit();
                }
                
                if ($payment == 0) {
                    if ($frs['agent_use'] < $crown_price) {
                        // $this->ajaxError(C('agent_use') . '不足');
                        // exit();
                    }
                }
                if ($payment == 1) {
                    if ($frs['limit_money'] < $crown_price) {
                        // $this->ajaxError(C('limit_money') . '不足');
                        // exit();
                    }
                }
                
                if ($payment == 0) {
                    if ($frs['u_level'] == 1) {
                        if ($price * $num < $s13[0]) {
                            $this->ajaxError('会员进货总价不能少于' . $s13[0] . '');
                        }
                        if ($price * $num > $s13[1]) {
                            $this->ajaxError('会员进货总价不能大于' . $s13[1] . '');
                        }
                    }
                    
                    if ($frs['u_level'] == 2) {
                        if ($price * $num < $str18[0]) {
                            $this->ajaxError('代理商进货总价不能少于' . $str18[0] . '');
                        }
                        if ($price * $num > $str18[1]) {
                            $this->ajaxError('代理商进货总价不能大于' . $str18[1] . '');
                        }
                    }
                }
                
                if ($payment == 0 && $frs['u_level'] == 1) {
                    $shouquan = M('shouquan')->where('uid=' . $frs['id'] . ' AND is_back=0 and status=0 ')
                        ->order('id desc')
                        ->find();
                    
                    $trade_sum = M('trade')->where('shouquan_id=' . $shouquan['id'] . ' ')->sum('price');
                    
                    if ($trade_sum == NULL) {
                        $trade_sum = 0;
                    }
                    
                    // if ($shouquan != null) {
                    // if ($shouquan['money'] < ($price * $num + $trade_sum)) {
                    // $this->ajaxError('您只能进货满' . $shouquan['money']);
                    // exit();
                    // }
                    // }
                }
                $address_count = M('user_addr_book')->where('user_id=' . $frs['id'])->count();
                
                if ($address_count == 0) {
                    
                    $ret_data['status'] = 0;
                    $ret_data['is_address'] = 1;
                    $ret_data['info'] = '请先编辑收货地址';
                    $ret_data['msg'] = '请先编辑收货地址';
                    $this->ajaxReturn($ret_data);
                    exit();
                }
                if (EMPTY($frs['bank_card'])) {
                    
                    $ret_data['status'] = 0;
                    $ret_data['is_bank'] = 1;
                    $ret_data['info'] = '请编辑您的银行卡号！';
                    $ret_data['msg'] = '请编辑您的银行卡号！';
                    $this->ajaxReturn($ret_data);
                    exit();
                }
                if (EMPTY($frs['nickname'])) {
                    $this->ajaxError('请编辑您的昵称再进行发布！');
                    exit();
                }
                $mobile = $this->_post('mobile', $frs['user_id']);
                if (EMPTY($mobile)) {
                    // $this->error('请编辑您的联系方式！'.$mobile);
                    // exit();
                }
                $chongzhi = M('trade');
                
                if ($payment == 0) {
                    // 判断有没有正在交易的记录
                    $trade_count = $chongzhi->where('userid=' . $frs['id'] . '   AND status=1')->count();
                    if ($trade_count > 0&&$frs['register_type']==1) {
                        $this->ajaxError('您有正在进货的订单,不可重复提交！');
                        exit();
                    }
                }
                
                // 判断有没有未确认收货的记录
                $trade_count = M('trade_log')->where('sell_userid=' . $frs['id'] . '   AND is_express=1  AND is_collect=1')->count();
                if ($trade_count > 0&&$frs['register_type']==1) {
                    $this->ajaxError('您有未确认收货的订单,不允许下单！');
                    exit();
                }
                
                $trade = M("trade")->where("  userid=" . $frs['id'] . '    AND  status=0  and id!=284869 ')
                    ->order(' id desc ')
                    ->find();
                
                if ($trade != null) {
                    
                    $money_time1 = $fee_rs['s17'];
                    if ($trade['shouquan_id'] == 0) {
                        $money_time1 = $fee_rs['i7'];
                    }
                    $end_money_time = strtotime("+" . $money_time1 . " minute", $trade["complete_time"]);
                    $now = time();
                    if ($end_money_time > $now&&$trade['is_tiqian']==0&&$frs['register_type']==1) {
                        
                        $this->ajaxError('您有订单提取倒计时未结束,不能再次进货');
                        exit();
                    }
                }
                
                // if ($payment == 0) {
                // $new_trade = $chongzhi->where('userid=' . $frs['id'])
                // ->order(' ID DESC ')
                // ->find();
                // if ($new_trade != null) {
                // if ($new_trade['status'] == 0 && $new_trade['price'] > $price * $num) {
                // $this->ajaxError('您必须预购一款不低于' . $new_trade['price'] . '的产品！');
                // exit();
                // }
                // }
                // }
                $ssb_money = $fee_rs['ssb_money'];
                $ssb_shouxu = $fee_rs['ssb_shouxu'];
                $data = array();
                $data['userid'] = $frs['id'];
                $data['user_name'] = $frs['user_name'];
                $data['addtime'] = time();
                $data['market'] = $market;
                $data['goods_id'] = $goods_id;
                $data['price'] = $price * $num;
                $new_trade = M('trade')->where('  status=0 and   userid=' . $frs['id'])
                    ->order(' id desc ')
                    ->find();
                
                if ($payment == 0) {
                    if ($new_trade != NULL) {
                        IF ($new_trade['price'] > $data['price']) {
                            $this->ajaxError('您必须进货不小于' . $new_trade['price'] . '！');
                            exit();
                        }
                    }
                }
                if ($frs['u_level'] == 1) {
                    $trade_count = M('trade')->where('userid=' . $frs['id'])->count();
                    $shouquan_count = M('shouquan')->where('uid=' . $frs['id'])->count();
                    
                    if ($trade_count == 0 && $shouquan_count == 0) {
                        
                        $vo = M('fck')->where('id in (0' . $frs['re_path'] . '0) AND u_level=2 ')
                            ->order(' re_level desc ')
                            ->find();
                        if ($vo != NULL) {
                            if ($vo['live_gupiao'] < $price * $num) {
                                $this->ajaxError($vo['user_id'] . '代理商' . C('live_gupiao') . '不足');
                                exit();
                            }
                        }
                        $shouquan_model['uid'] = $frs['id'];
                        $shouquan_model['re_id'] = $vo['id'];
                        $shouquan_model['add_time'] = time();
                        $shouquan_model['money'] = $price * $num;
                        
                        // $s1 = explode(':', $fee_rs['s1']);
                        // $fee_rs['s1'] = $s1[0] / $s1[1];
                        // $fee_rs['s1'] = $s1[0];
                        
                        $profit = 100;
                        
                        $shouquan_model['profit'] = $profit;
                        $shouquan_model['status'] = 0;
                        M('shouquan')->add($shouquan_model);
                        
                        $fck = D('Fck');
                        $kt_cont = '授权分享,' . $frs['user_id'] . ',扣除' . $price * $num;
                        $news_id = $fck->addencAdd($vo['id'], $vo['user_id'], - ($price * $num), 19, 0, 0, 0, $kt_cont . '-' . C('live_gupiao'));
                        
                        $fck->where(array(
                            'id' => $vo['id']
                        ))->setDec('live_gupiao', ($price * $num));
                        
                        update_goods_history($news_id, 0, 0);
                    }
                }
                if ($payment == 0 && $frs['u_level'] == 1) {
                    $shouquan = M('shouquan')->where('uid=' . $frs['id'] . ' AND is_back=0 and status=0 ')
                        ->order('id desc')
                        ->find();
                    
                    $trade_sum = M('trade')->where('shouquan_id=' . $shouquan['id'] . ' ')->sum('price');
                    
                    if ($trade_sum == NULL) {
                        $trade_sum = 0;
                    }
                    
                    if ($shouquan != null) {
                        if ($shouquan['money'] < ($price * $num + $trade_sum)) {
                            // $this->ajaxError('您只能进货满' . $shouquan['money']);
                            // exit();
                        }
                    }
                }
                $data['num'] = $num;
                $data['deal'] = 0;
                
                if ($payment == 1) {
                    
                    $data['deal'] = $data['price'];
                }
                $data['mum'] = $num;
                $data['stock'] = $num;
                $data['type'] = $type;
                $data['fee'] = $fee_rs['ssb_shouxu'];
                $data['payment'] = $payment;
                $data['bankcard'] = $bankcard;
                $data['min'] = $min;
                
                $data['max'] = $max;
                $data['remark'] = $remark;
                $data['area'] = $area;
                $data['currency'] = $currency;
                $data['opentype'] = $opentype;
                $data['ssb_money'] = $ssb_money;
                $data['ssb_shouxu'] = $ssb_shouxu;
                $data['all_price'] = (100 - $ssb_shouxu) * 0.01 * $price;
                $data['status'] = 1;
                if ($payment == 1) {
                    
                    $data['status'] = 0;
                }
                $data['is_hot'] = 1;
                $data['is_password'] = $is_password;
                $data['password'] = $password;
                $data['mobile'] = $mobile;
                $data['guarantee_money'] = $guarantee_money;
                if ($frs['u_level'] == 1) {
                    $shouquan = get_shouquan($frs['id']);
                    
                    $trade_count = M('trade')->where('userid=' . $frs['id'] . ' AND shouquan_id>0 ')->count();
                    if ($shouquan != NULL && $trade_count == 0) {
                        $data['shouquan_id'] = $shouquan['id'];
                    }
                }
                $result = $chongzhi->add($data);
                if ($payment == 0) {
                    if ($crown_price > 0) {
                        // $fck = D('Fck');
                        // $kt_cont = '预购产品,扣除' . C('agent_use') . $crown_price;
                        // $news_id = $fck->addencAdd($frs['id'], $frs['user_id'], - ($crown_price), 19, 0, 0, 0, $kt_cont);
                        
                        // $fck->where(array(
                        // 'id' => $frs['id']
                        // ))->setDec('agent_use', ($crown_price));
                    }
                }
                if ($payment == 1) {
                    if ($crown_price > 0) {
                        // $fck = D('Fck');
                        // $kt_cont = '预购产品,扣除' . C('limit_money') . $crown_price;
                        // $news_id = $fck->addencAdd($frs['id'], $frs['user_id'], - ($crown_price), 19, 0, 0, 0, $kt_cont);
                        
                        // $fck->where(array(
                        // 'id' => $frs['id']
                        // ))->setDec('limit_money', ($crown_price));
                    }
                    
                    // 更新销售者的数据
                    set_user_order_num($frs);
                    // 推送数量
                    
                    $fck_num = M('fck_num')->where('uid=' . $frs['id'])->find();
                    
                    $data = array();
                    $data['type'] = "update_order_num";
                    $data['order_num'] = $fck_num;
                    $data['uid'] = $frs['id'];
                    
                    push_msg($data, $frs['id']);
                }
                
                $rearray[] = $result;
            } else {
                $this->ajaxError('没有该会员，请重新输入!');
            }
        } else {
            $this->ajaxError('系统繁忙!');
        }
        $all_money = 0;
        $trade = M("trade")->where("  userid=" . $UserID . '    AND  deal!=price and is_cancel=0 and  is_yd=1')
            ->order(' id desc ')
            ->find();
        if ($trade != NULL) {
            $all_money = get_help_money($trade);
        }
        $data = array();
        $data['info'] = '确认提交！';
        $data['msg'] = '确认提交！';
        $data['all_money'] = $all_money;
        $data['status'] = 1;
        $this->ajaxReturn($data);
        return;
    }

    function trade_goods_list()
    {
        $form = M('Trade');
        
        $is_mobile = I('post.is_mobile', 1);
        $type = I('post.type', 0);
        $time = I('post.time', 0);
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 10);
        
        $where = ' and t.is_hot=1  and  t.is_cancel=0 and  t.payment=0 ';
        if ($time != 0) {
            $where = ' AND  FROM_UNIXTIME(t.addtime, "%Y-%m-%d")="' . $time . '" and t.is_hot=0  and  t.is_cancel=0 ';
        }
        
        $list = $form->alias('t')
            ->join("xt_goods AS g ON   g.id = t.goods_id  ", 'LEFT')
            ->where('  (t.price-t.deal)>0  AND t.status=1 and t.is_cancel=0 ' . $where)
            ->field('t.*,g.title,g.img,g.price as goods_price,FROM_UNIXTIME(t.addtime, "%Y-%m-%d %H:%i:%S") AS addtime_str, (t.price-t.deal) as all_money  ,(SELECT COUNT(ID) FROM xt_trade_log where xt_trade_log.trade_id=t.id   and xt_trade_log.is_cancel=0 ) as trade_num ')
            ->order('t.sort_id desc ,t.addtime asc')
            ->page($page_index . ',' . $page_size)
            ->select();
        
        foreach ($list as $key => $value) {
            
            $list[$key]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$key]['img']);
            if (strpos($list[$key]['img'], 'http') !== false) {} else {
                $list[$key]['img'] = C('oss_url') . $list[$key]['img'];
            }
            
            $list[$key]['all_money'] = get_help_money($value);
            $list[$key]['goods_num'] = $list[$key]['all_money'] / ($value['goods_price']);
        }
        
        $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 1) {
            
            $money = $form->alias('t')
                ->where('   1=1 ' . $where)
                ->select();
            
            // if ($money == NULL) {
            // $money = 0;
            // }
            
            $all_money = 0;
            
            foreach ($money as $key1 => $value1) {
                $all_money = $all_money + get_help_money($value1);
            }
            
            if ($time == 0) {
                $time = date("Y-m-d", strtotime("+0 day"));
                // $time='2018-11-12'
            }
            
            $mtime = strtotime("+1 day", strtotime($time));
            
            $remain_time = $mtime - time();
            
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $data['mtime'] = $mtime;
            $data['remain_time'] = $remain_time;
            $data['all_money'] = $all_money;
            
            $user_count = M('trade')->alias('t')
                ->where('   1=1 and t.status=0 and T.payment=0 ')
                ->count();
            
            $data['order_tip'] = '当前已有' . $user_count . '人进货成功';
            
            $data['current_count'] = count($list);
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    function trade_data_list()
    {
        $now = time();
        $days = array();
        
        $form = M('Trade');
        $list = $form->alias('t')
            ->field('t.addtime  ')
            ->where('t.payment=0')
            ->group('FROM_UNIXTIME(t.addtime, "%Y-%m-%d") ')
            ->select();
        $money = $form->alias('t')
            ->where('   t.is_hot =1 ')
            ->select();
        
        $all_money = 0;
        
        foreach ($money as $key1 => $value1) {
            $all_money = $all_money + get_help_money($value1);
        }
        $item = array();
        $item['money'] = $all_money;
        $item['day'] = '正在求购';
        $item['time'] = 0;
        $days[] = $item;
        
        foreach ($list as $key => $value) {
            // $t = strtotime("+$i day");
            
            $item = array();
            
            $item['day'] = date("m月d日", $value['addtime']);
            $item['time'] = date("Y-m-d", $value['addtime']);
            
            $money = $form->alias('t')
                ->where('     FROM_UNIXTIME(t.addtime, "%Y-%m-%d")="' . $item['time'] . '"   and  t.is_hot=0 and  t.is_cancel=0 AND
                     t.payment=0')
                ->select();
            
            // if ($money == NULL) {
            // $money = 0;
            // }
            
            $all_money = 0;
            
            foreach ($money as $key1 => $value1) {
                $all_money = $all_money + get_help_money($value1);
            }
            
            $item['money'] = $all_money;
            
            $days[] = $item;
        }
        
        $data = array();
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $days;
        $this->ajaxReturn($data);
    }

    function trade_list()
    {
        $form = M('Trade');
        
        $type = $_POST['type'];
        $p = $this->_get('p', true, '1');
        
        $list = $form->where('is_password=' . $type . ' and  payment=0 AND (price-deal)>0  AND status=0 and is_cancel=0 and EXISTS(SELECT ID FROM XT_FCK WHERE XT_FCK.ID=XT_TRADE.userid)')
            ->order('sort_id desc ,addtime asc')
            ->page($p . ',10')
            ->select();
        foreach ($list as $key => $value) {
            
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["addtime"]);
            
            $user = M('fck')->where("id=" . $value['userid'] . "")->find();
            $list[$key]['user'] = $user;
            $list[$key]['user_id'] = $user["user_id"];
            $list[$key]['nickname'] = $user["nickname"];
            $trade_count = M('trade')->where("userid=" . $value['userid'] . "")->count();
            
            $list[$key]['trade_count'] = $trade_count;
            $list[$key]['price'] = round($list[$key]['price'], 2);
            
            $payment = explode('|', C('payment'));
            
            $list[$key]['payment_str'] = $payment[$value["payment"]];
            $list[$key]['avatar'] = $user["avatar"];
            $list[$key]['DAS'] = C("DAS");
            
            $trade_count = M('trade_log')->where("trade_id=" . $value['id'] . "")->count();
            
            $list[$key]['farvirate'] = $trade_count;
            $list[$key]['trade_price'] = round($value['price'] - $value['deal']);
            
            if ($type == 1) {
                
                $list[$key]['type_str'] = '出售';
            }
            if ($type == 2) {
                
                $list[$key]['type_str'] = '购买';
            }
            
            $list[$key]['is_time1'] = 0;
            $list[$key]['is_time2'] = 0;
            $list[$key]['is_cancel_btn'] = 0;
            $list[$key]['is_payment_btn'] = 0;
            $list[$key]['is_collect_btn'] = 0;
            if ($value['is_payment'] == 0) {
                $list[$key]['is_time1'] = 1;
                $list[$key]['is_payment_btn'] = 1;
            }
            if ($value['is_payment'] == 1 && $value['is_collect'] == 0) {
                $list[$key]['is_time2'] = 1;
                $list[$key]['is_collect_btn'] = 1;
            }
            if ($value['status'] != 0) {
                $list[$key]['is_cancel_btn'] = 1;
            }
        }
        
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        if ($_POST['is_mobile'] == 1) {
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    function trade_user()
    {
        $form = M('Trade');
        $fck = M('fck');
        
        $trade_user_id = $_POST['trade_user_id'];
        $user_id = $_POST['user_id'];
        $trade_detail_id = $_POST['trade_detail_id'];
        
        $user = $fck->where('id=' . $trade_user_id)->find();
        $list = $form->where('userid=' . $trade_user_id)->select();
        
        foreach ($list as $key => $value) {
            
            $payment = explode('|', C('payment'));
            
            $list[$key]['payment_str'] = $payment[$value["payment"]];
        }
        
        $Trade_log = M('Trade_log')->where('(userid=' . $user_id . ' AND  sell_userid= ' . $trade_user_id . ') OR (userid=' . $trade_user_id . ' AND  sell_userid= ' . $user_id . ')')->select();
        
        foreach ($Trade_log as $key => $value) {
            
            $trade = $form->where('id=' . $value['trade_id'])->find();
            $payment = explode('|', C('payment'));
            
            $Trade_log[$key]['payment_str'] = $payment[$trade["payment"]];
            $Trade_log[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["addtime"]);
            $Trade_log[$key]['trade_status'] = get_trade_log_status($value);
        }
        
        if ($_POST['is_mobile'] == 1) {
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $user;
            $data['trade_list'] = $list;
            $data['trade_log_list'] = $Trade_log;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    function trade_detail()
    {
        $fee = M('fee')->where('id=1')->find();
        $form = M('Trade');
        
        $id = $_POST['trade_id'];
        $trade_detail_id = $_POST['trade_detail_id'];
        
        $trade = $form->where('id=' . $id)->find();
        $Trade_log = M('Trade_log')->where('id=' . $trade_detail_id)->find();
        
        // 打款倒计时
        $Trade_log['end_money_time1'] = strtotime('+' . $fee['money_time1'] . ' minute', $Trade_log['addtime']);
        $end_money_time1_str = date('Y-m-d H:i:s', $Trade_log['end_money_time1']);
        $Trade_log['end_money_time1_str'] = $end_money_time1_str;
        $end_money_time2_str = '';
        if ($Trade_log['is_payment'] == 1) {
            // 收款倒计时
            $Trade_log['end_money_time2'] = strtotime('+' . $fee['money_time2'] . ' minute', $Trade_log['payment_time']);
            $end_money_time2_str = date('Y-m-d H:i:s', $Trade_log['end_money_time2']);
            $Trade_log['end_money_time2_str'] = $end_money_time2_str;
        }
        $trade['addtime_str'] = date("Y-m-d H:i:s", $trade["addtime"]);
        
        $user = M('fck')->where("id=" . $trade['userid'] . "")->find();
        $trade['user_id'] = $user["user_id"];
        $trade_count = M('trade')->where("userid=" . $trade['userid'] . "")->count();
        
        $trade['trade_count'] = $trade_count;
        $trade['trade_price'] = round($trade['price'] - $trade['deal']);
        $trade['price'] = round($trade['price'], 2);
        $trade['trade_status'] = get_trade_log_status($Trade_log);
        // $trade['min'] = ($trade['min']);
        // $trade['max'] = ($trade['max']);
        
        $payment = explode('|', C('payment'));
        
        $trade['payment_str'] = $payment[$trade["payment"]];
        
        $buyer = M('fck')->where("id=" . $Trade_log['userid'] . "")->find();
        
        $seller = M('fck')->where("id=" . $Trade_log['sell_userid'] . "")->find();
        
        $Trade_log['seller'] = $seller['user_id'];
        $Trade_log['buyer'] = $buyer['user_id'];
        
        $time1 = $Trade_log['end_money_time1'] - time();
        $time2 = $Trade_log['end_money_time2'] - time();
        
        $Trade_log['time1'] = $time1;
        $Trade_log['time2'] = $time2;
        
        $trade['log'] = $Trade_log;
        $trade['trade_num'] = 0;
        $trade['farvirate'] = 0;
        $trade['trust_num'] = 0;
        $trade['history_num'] = 0;
        $trade['CNY'] = C("CNY");
        $trade['DAS'] = C("DAS");
        
        if ($_POST['is_mobile'] == 1) {
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $trade;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function new_trade()
    {
        $fee = M('fee');
        $fee_rs = $fee->field('s3,recommend_gupiao,recommend_order,jjbb,s16,ssb_money,ssb_shouxu,s12,trade_bs,str13')->find(1);
        
        $ssb_shouxu = $fee_rs['ssb_shouxu'];
        
        $form = M('Trade');
        $trade_log = M('trade_log');
        
        $num = I('num', 1);
        $id = I('trade_id', 284883);
        $user_id = I('user_id', 82505);
        $goodids = I('goodids');
        
        $user = M('fck')->where('id=' . $user_id)->find();
        $cart_num = M('cart')->where('uid=' . $user_id)->sum('price*quantity');
        
        if ($cart_num == null) {
            $cart_num = 0;
        }
        
        $price = $cart_num;
        
        if ($user['is_trade'] == 0) {
            // $this->ajaxError('您不可以销售！');
        }
        if ($user['is_lock'] == 1) {
            $this->ajaxError('您已冻结');
            exit();
        }
        if ($id == 0) {
            $this->ajaxError('请填写进货单');
            exit();
        }
        
        // $ssb = $_POST['ssb'];
        
        $trade = $form->where('id=' . $id . '')->find();
        
        // $goods = M('goods')->where(array(
        // 'id' => $trade['goods_id']
        // ))
        // ->field('*')
        // ->find();
        // $price = $goods['price'] * $num;
        
        // if ($trade['userid'] == $user_id) {
        // $this->ajaxError('不可以自己进行交易！');
        // }
        
        if ($trade['status'] == 2) {
            $this->ajaxError('交易订单已取消');
            exit();
        }
        if ($trade['min'] > $price) {
            $this->ajaxError('交易金额不得小于' . $trade['min']);
            exit();
        }
        
        // if ($trade['max'] * $num < $price) {
        // $this->ajaxError('交易金额不得大于' . $trade['max']); exit();
        // }
        $all_money = get_help_money($trade);
        // $this->ajaxError('此预购暂时不能销售'.$all_money);
        // return;
        if ($all_money == 0) {
            $this->ajaxError('此预购暂时不能销售');
            exit();
        }
        if ($price != $all_money) {
            $this->ajaxError('目前只能进货' . $all_money);
            exit();
        }
        
        if ($trade['price'] * $fee_rs['s12'] * 0.01 == $trade['deal'] && $trade['is_hot'] == 0) {
            
            $this->ajaxError('您剩余正在备货中,请耐心等待');
            exit();
        }
        
        $fee_buy = 0;
        $fee_sell = 0;
        $type = 0;
        
        $buy = M('fck')->where('id=' . $trade['userid'])->find();
        $money = 0;
        if ($trade['type'] == 1) {
            $fee_buy = $price;
            $fee_sell = $trade['price'];
            $type = 1;
            $money = $fee_buy * $fee_rs['s12'] * 0.01;
            if (($money + $fee_buy) > $user['agent_use']) {}
            if ($fee_buy > $user['agent_use']) {
                // $this->error(C('agent_use') . '不足');
                // exit();
            }
        }
        if ($buy['is_lock'] == 1) {
            $this->ajaxError('您已冻结');
            exit();
        }
        if (EMPTY($user['nickname'])) {
            $this->ajaxError('请编辑您的昵称再进行交易！');
            exit();
        }
        $address_count = M('user_addr_book')->where('user_id=' . $user['id'])->count();
        
        if ($address_count == 0) {
            
            $ret_data['status'] = 0;
            $ret_data['is_address'] = 1;
            $ret_data['info'] = '请先编辑收货地址';
            $ret_data['msg'] = '请先编辑收货地址';
            $this->ajaxReturn($ret_data);
            exit();
        }
        if ($trade['type'] == 2) {
            $fee_sell = $price;
            $fee_buy = $trade['price'];
            $type = 2;
        }
        
        $cart_num = M('cart')->where('uid=' . $user_id)->sum('price*num');
        
        if ($cart_num == null) {
            $cart_num = 0;
        }
        
        $price = $cart_num;
        if (($trade['deal'] + $price) > $trade['price']) {
            $this->ajaxError('已超过最大交易金额！');
        }
        $money = $price * $fee_rs['str13'] * 0.01;
        
        if (($user['agent_use'] - $money) < 0) {
            $this->ajaxError('您的' . C('agent_use') . '不足！');
            exit();
        }
        $money = $price * $fee_rs['ssb_shouxu'] * 0.01;
        
        if (($user['limit_money'] - $money) < 0) {
            $this->ajaxError('您的' . C('limit_money') . '不足！');
            exit();
        }
        $new_trade = $form->where('userid=' . $user_id)
            ->order(' ID DESC ')
            ->find();
        
        if ($new_trade != null) {
            if ($new_trade['status'] == 1) {
                
                $goods = M('goods')->where('id=' . $new_trade['goods_id'])->find();
                $guarantee_money = $goods['price'] * $fee_rs['s12'] * 0.01;
                $payment_price = $trade_log->where('trade_id=' . $new_trade['id'] . ' and is_collect=1 ')->sum('price');
                if ($payment_price < $guarantee_money&&$user['register_type']==1) {
                    $this->ajaxError('您必须打完保证金才能销售');
                    exit();
                }
            }
        }
        
        $stock = M('trade')->where('userid=' . $user_id . ' AND goods_id=' . $trade['goods_id'])->sum('stock');
        if ($stock != null) {
            $sell_stock = M('trade_log')->where('userid=' . $user_id . ' AND goods_id=' . $trade['goods_id'])->sum('num');
            
            IF ($stock < ($sell_stock + $num)) {
                $this->ajaxError('此商品您的库存不足');
                exit();
            }
        }
        
        $cart = M('cart')->alias('t')
            ->join("xt_goods AS g ON   g.id = t.article_id ", 'LEFT')
            ->where('t.uid=' . $user_id)
            ->field('T.*,IFNULL(G.user_id,1) AS user_id,G.stock')
            ->select();
        $goodsid = '0';
        foreach ($cart as $i => $goods) {
            IF (($goods['stock'] - $goods['quantity']) < 0) {
                $this->ajaxError('此商品的库存不足');
                exit();
            }
            
            $fee_sell = $goods['price'] * $goods['quantity'];
            $data = array();
            
            if ($type == 1) {
                $data['sell_userid'] = $user_id;
                $data['userid'] = $trade['userid'];
            }
            if ($type == 2) {
                $data['sell_userid'] = $trade['userid'];
                $data['userid'] = $goods['user_id'];
            }
            $data['trade_id'] = $id;
            $data['addtime'] = time();
            $data['market'] = $trade['market'];
            $data['price'] = $fee_sell;
            $data['num'] = $goods['quantity'];
            $data['mum'] = $goods['quantity'];
            $data['fee_buy'] = $fee_buy;
            $data['fee_sell'] = $fee_sell;
            $data['type'] = $trade['type'];
            // $data['ssb'] = $ssb;
            $data['status'] = 1;
            $data['goods_id'] = $goods['article_id'];
            $data['payment_id'] = $buy['payment_id'];
            
            $data['ssb_shouxu'] = $ssb_shouxu;
            $data['all_price'] = (100 - $ssb_shouxu) * 0.01 * $price;
            
            // 1银行转账2支付宝3微信
            if (EMPTY($user['bank_card'])) {
                
                $data = array();
                $data['msg'] = '您的银行卡信息未上传！';
                $data['info'] = '您的银行卡信息未上传！';
                $data['status'] = 0;
                $data['type'] = 'no_bank';
                $this->ajaxReturn($data);
            }
            if ($trade['payment'] == 2 && EMPTY($user['alipay_img'])) {
                
                // $data = array();
                // $data['msg'] = '您的支付宝二维码未上传！';
                // $data['info'] = '您的支付宝二维码未上传！';
                // $data['status'] = 0;
                // $data['type'] = 'no_alipay';
                // $this->ajaxReturn($data);
            }
            if ($trade['payment'] == 3 && EMPTY($user['weixin_img'])) {
                
                // $data = array();
                // $data['msg'] = '您的微信二维码未上传！';
                // $data['info'] = '您的微信二维码未上传！';
                // $data['status'] = 0;
                // $data['type'] = 'no_weixin';
                // $this->ajaxReturn($data);
            }
            
            if ($price % $fee_rs['trade_bs'] != 0) {
                // $this->ajaxError('交易金额必须是' . $fee_rs['trade_bs'] . '的倍数');
            }
            // 判断有没有正在交易的记录
            $trade_count = $trade_log->where('sell_userid=' . $user_id . '   AND is_collect=0 AND is_payment>=0')->count();
            
            if ($trade_count > 0) {
                // $this->error('您有未确认收款的订单,不可卖出！');
                // exit();
            }
            
            $fee = M('fee'); // 参数表
            $fee_rs = $fee->field('jifen_time1,money_time2,s12')->find();
            
            $result = $trade_log->add($data);
            
            $goodsid = $goodsid . ',' . $goods['article_id'];
            
            $user = M('fck')->where('id=' . $data['userid'])->find();
            
            // trade_money_func($trade, $fee_sell, $user['id'], $user, $price);
        }
        $form->where('id=' . $id)->setField('goods_id', $goodsid);
        $cart_num = M('cart')->where('uid=' . $user_id)->sum('price*quantity');
        
        if ($cart_num == null) {
            $cart_num = 0;
        }
        
        $price = $cart_num;
        if (($trade['deal'] + $price) > $trade['price']) {
            $this->ajaxError('已超过最大交易金额！');
            exit();
        }
        $money = $price * $fee_rs['str13'] * 0.01;
        
        $form->where('id=' . $id)->setInc('deal', $price);
        
        $trade = $form->where('id=' . $id)->find();
        
        check_help_money($trade);
        // 更新销售者的数据
        set_user_order_num($buy);
        
        // 扣除库存
        $cart = $trade_log->where('trade_id=' . $id)->select();
        foreach ($cart as $i => $goods) {
            M('goods')->where('id=' . $goods['goods_id'])->setDec('stock', $goods['num']);
        }
        
        M('cart')->where('uid=' . $user_id)->delete();
        
        // 推送数量
        
        $fck_num = M('fck_num')->where('uid=' . $buy['id'])->find();
        
        $data = array();
        $data['type'] = "update_order_num";
        $data['is_push'] = 1;
        $data['push_msg'] = '您有1条新的进货订单,请注意查看';
        $data['order_num'] = $fck_num;
        $data['uid'] = $buy['id'];
        push_msg($data, $buy['id']);
        
        $all_money = get_help_jh_money($trade['userid']);
        
        // send_trade_msg($buy['user_id']);
        if ($type == 1) {}
        // 发送短信
        send_order_msg($buy['user_id'], 'tradecode');
        
        $data = array();
        $data['msg'] = '完成订单！';
        $data['info'] = '完成订单！';
        $data['status'] = 1;
        $data['trade_id'] = $id;
        $data['all_money'] = $all_money;
        $data['is_now_payment'] = 1;
        $this->ajaxReturn($data);
    }

    function trade()
    {
        $fee = M('fee');
        $fee_rs = $fee->field('s3,recommend_gupiao,recommend_order,jjbb,s16,ssb_money,ssb_shouxu,s12,trade_bs,str13')->find(1);
        
        $ssb_shouxu = $fee_rs['ssb_shouxu'];
        
        $form = M('Trade');
        $trade_log = M('trade_log');
        
        $num = I('num');
        $id = I('trade_id');
        $user_id = I('user_id');
        $goodids = I('goodids');
        
        $user = M('fck')->where('id=' . $user_id)->find();
        
        if ($user['is_trade'] == 0) {
            // $this->ajaxError('您不可以销售！');
        }
        if ($user['app_version'] < C('app_version')) {
            $this->ajaxError('您不是最新版本');
            exit();
        }
        if ($user['is_lock'] == 1) {
            $this->ajaxError('您已冻结');
            exit();
        }
        $price = $_POST['price'];
        $ssb = $_POST['ssb'];
        
        $trade = $form->where('id=' . $id)->find();
        
        $goods = M('goods')->where(array(
            'id' => $trade['goods_id']
        ))
            ->field('*')
            ->find();
        $price = $goods['price'] * $num;
        
        if ($trade['userid'] == $user_id) {
            $this->ajaxError('不可以自己进行交易！');
        }
        if ($trade['status'] == 2) {
            $this->ajaxError('交易订单已取消');
        }
        if ($trade['min'] > $price) {
            $this->ajaxError('交易金额不得小于' . $trade['min']);
        }
        
        if ($trade['max'] * $num < $price) {
            $this->ajaxError('交易金额不得大于' . $trade['max']);
        }
        $all_money = get_help_money($trade);
        // $this->ajaxError('此预购暂时不能销售'.$all_money);
        // return;
        if ($all_money == 0) {
            $this->ajaxError('此预购暂时不能销售');
        }
        if ($price > $all_money) {
            $this->ajaxError('目前只能销售' . $all_money);
        }
        
        if ($trade['price'] * $fee_rs['s12'] * 0.01 == $trade['deal'] && $trade['is_hot'] == 0) {
            
            $this->ajaxError('进货方必须先支付完预付款' . $trade['deal']);
        }
        
        $fee_buy = 0;
        $fee_sell = 0;
        $type = 0;
        
        $buy = M('fck')->where('id=' . $trade['userid'])->find();
        $money = 0;
        if ($trade['type'] == 1) {
            $fee_buy = $price;
            $fee_sell = $trade['price'];
            $type = 1;
            $money = $fee_buy * $fee_rs['s12'] * 0.01;
            if (($money + $fee_buy) > $user['agent_use']) {}
            if ($fee_buy > $user['agent_use']) {
                // $this->error(C('agent_use') . '不足');
                // exit();
            }
        }
        if ($buy['is_lock'] == 1) {
            $this->ajaxError('您已冻结');
            exit();
        }
        if (EMPTY($user['nickname'])) {
            $this->ajaxError('请编辑您的昵称再进行交易！');
            exit();
        }
        
        if ($trade['type'] == 2) {
            $fee_sell = $price;
            $fee_buy = $trade['price'];
            $type = 2;
        }
        
        $data = array();
        
        if ($type == 1) {
            $data['sell_userid'] = $user_id;
            $data['userid'] = $trade['userid'];
        }
        if ($type == 2) {
            $data['sell_userid'] = $trade['userid'];
            $data['userid'] = $user_id;
        }
        $data['trade_id'] = $id;
        $data['addtime'] = time();
        $data['market'] = $trade['market'];
        $data['price'] = $price;
        $data['num'] = $num;
        $data['mum'] = $num;
        $data['fee_buy'] = $fee_buy;
        $data['fee_sell'] = $fee_sell;
        $data['type'] = $trade['type'];
        $data['ssb'] = $ssb;
        $data['status'] = 1;
        $data['goods_id'] = $trade['goods_id'];
        $data['payment_id'] = $buy['payment_id'];
        
        $data['ssb_shouxu'] = $ssb_shouxu;
        $data['all_price'] = (100 - $ssb_shouxu) * 0.01 * $price;
        
        if (($trade['deal'] + $price) > $trade['price']) {
            $this->ajaxError('已超过最大交易金额！');
        }
        // 1银行转账2支付宝3微信
        if (EMPTY($user['bank_card'])) {
            
            $data = array();
            $data['msg'] = '您的银行卡信息未上传！';
            $data['info'] = '您的银行卡信息未上传！';
            $data['status'] = 0;
            $data['type'] = 'no_bank';
            $this->ajaxReturn($data);
        }
        if ($trade['payment'] == 2 && EMPTY($user['alipay_img'])) {
            
            $data = array();
            $data['msg'] = '您的支付宝二维码未上传！';
            $data['info'] = '您的支付宝二维码未上传！';
            $data['status'] = 0;
            $data['type'] = 'no_alipay';
            $this->ajaxReturn($data);
        }
        if ($trade['payment'] == 3 && EMPTY($user['weixin_img'])) {
            
            $data = array();
            $data['msg'] = '您的微信二维码未上传！';
            $data['info'] = '您的微信二维码未上传！';
            $data['status'] = 0;
            $data['type'] = 'no_weixin';
            $this->ajaxReturn($data);
        }
        
        if ($price % $fee_rs['trade_bs'] != 0) {
            // $this->ajaxError('交易金额必须是' . $fee_rs['trade_bs'] . '的倍数');
        }
        // 判断有没有正在交易的记录
        $trade_count = $trade_log->where('sell_userid=' . $user_id . '   AND is_collect=0 AND is_payment>=0')->count();
        
        if ($trade_count > 0) {
            // $this->error('您有未确认收款的订单,不可卖出！');
            // exit();
        }
        $money = $price * $fee_rs['str13'] * 0.01;
        
        if (($user['agent_use'] - $money) < 0) {
            $this->ajaxError('您的' . C('agent_use') . '不足！');
            exit();
        }
        $money = $price * $fee_rs['ssb_shouxu'] * 0.01;
        
        if (($user['limit_money'] - $money) < 0) {
            $this->ajaxError('您的' . C('limit_money') . '不足！');
            exit();
        }
        
        $new_trade = $form->where('userid=' . $user_id)
            ->order(' ID DESC ')
            ->find();
        if ($new_trade != null) {
            if ($new_trade['status'] == 0) {
                // $this->ajaxError('您必须预购一款不低于' . $new_trade['price'] . '的产品！');
                // exit();
            }
        }
        
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('jifen_time1,money_time2,s12')->find();
        
        if ($new_trade != null) {
            if ($new_trade['status'] == 1) {
                
                $goods = M('goods')->where('id=' . $new_trade['goods_id'])->find();
                $guarantee_money = $goods['price'] * $fee_rs['s12'] * 0.01;
                $payment_price = $trade_log->where('trade_id=' . $new_trade['id'] . ' and is_collect=1 ')->sum('price');
                if ($payment_price < $guarantee_money) {
                    $this->ajaxError('您必须打完保证金才能销售');
                    exit();
                }
            }
        }
        
        $stock = M('trade')->where('userid=' . $user_id . ' AND goods_id=' . $trade['goods_id'])->sum('stock');
        if ($stock != null) {
            $sell_stock = M('trade_log')->where('userid=' . $user_id . ' AND goods_id=' . $trade['goods_id'])->sum('num');
            
            IF ($stock < ($sell_stock + $num)) {
                $this->ajaxError('此商品您的库存不足');
                exit();
            }
        }
        
        trade_money_func($trade, $money, $user['id'], $user, $price);
        
        $result = $trade_log->add($data);
        
        // $sell = M('fck')->where('id= ' . $user_id)->find();
        import("@.ORG.getui.GeTui");
        $gt = new GeTui();
        if (! EMPTY($user['cid'])) {
            // $gt->pushMessageToSingle1($user['cid'], 'update_trade_num');
            $user_ids = array(
                $user['cid']
            );
            // PushInfoMatter($user_ids, 'update_trade_num');
        }
        
        if (! EMPTY($buy['cid'])) {
            // $gt->pushMessageToSingle1($buy['cid'], 'update_trade_num');
            $user_ids = array(
                $buy['cid']
            );
            // PushInfoMatter($user_ids, 'update_trade_num');
        }
        
        $form->where('id=' . $id)->setInc('deal', $price);
        
        $trade = $form->where('id=' . $id)->find();
        
        check_help_money($trade);
        // 更新销售者的数据
        set_user_order_num($buy);
        
        // 推送数量
        
        $fck_num = M('fck_num')->where('uid=' . $buy['id'])->find();
        
        $data = array();
        $data['type'] = "update_order_num";
        $data['is_push'] = 1;
        $data['push_msg'] = '您有1条新的进货订单,请注意查看';
        $data['order_num'] = $fck_num;
        $data['uid'] = $buy['id'];
        push_msg($data, $buy['id']);
        
        // send_trade_msg($buy['user_id']);
        if ($type == 1) {}
        // 发送短信
        send_order_msg($buy['user_id'], 'tradecode');
        
        $data = array();
        $data['msg'] = '完成订单！';
        $data['info'] = '完成订单！';
        $data['status'] = 1;
        $data['trade_detail_id'] = $result;
        $data['trade_id'] = $id;
        $this->ajaxReturn($data);
    }

    function get_trade_num()
    {
        $form = M('trade_log');
        $status = 0;
        $user_id = $_POST['user_id'];
        
        $list = $form->where('  (sell_userid=' . $user_id . '  and  is_sell_read=0  )    OR  (user_id=' . $user_id . '  AND is_buy_read=0) ')
            ->order(' addtime desc')
            ->select();
        
        // =================================================
        
        if ($_POST['is_mobile'] == 1) {
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = count($list);
            $data['trade_num'] = count($list);
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    // 获取用户销售记录
    function get_trade_list()
    {
        $page_index = I('post.pageNum', 1);
        $page_size = I('post.pageSize', 1000);
        $form = M('trade_log');
        $status = 0;
        $type = $_POST['type'];
        $trade_type = I('post.trade_type', 1);
        $status_str = $_POST['status'];
        $user_id = $_POST['user_id'];
        $func = $_POST['func'];
        $STR = ' G.is_password>=0';
        $STR1 = '';
        if ($status_str == 'develop') {
            $status = '0 OR t.is_cancel=1)';
        }
        if ($status_str == 'online') {
            $status = '0  )';
        }
        // 2待确认3已完成4投诉
        $where = '';
        if ($type == 1) {}
        if ($type == 2) {
            $where = ' and t.is_express!=2   and t.is_payment=1    and t.is_cancel=0    and t.is_tousu=0   ';
        }
        if ($type == 3) {
            
            $where = ' and t.is_express=2   and t.is_payment=1   and t.is_cancel=0  ';
        }
        if ($type == 4) {
            $where = ' and  t.is_tousu=1    and t.is_cancel=0 ';
        }
        // if ($trade_type == 1) {
        
        // $STR = $STR . ' and (t.userid=' . $user_id . ') ' . ' ' . $STR1;
        // }
        // if ($trade_type == 2) {
        
        $STR = $STR . ' and ( t.userid=' . $user_id . ') ' . '  ' . $STR1;
        // }
        $list = $form->alias('t')
            ->join("xt_trade AS g ON   g.id = t.trade_id", 'LEFT')
            ->join("xt_goods AS h ON   h.id = t.goods_id", 'LEFT')
            ->where(' (t.status>=' . $status . ' AND ' . $STR . $where)
            ->field('t.*,G.remark,G.payment,G.shouquan_id,h.title')
            ->page($page_index . ',' . $page_size)
            ->order('T.addtime desc')
            ->select();
        
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('jifen_time1,money_time2,money_time1,s18')->find();
        $jifen_time1 = $fee_rs['jifen_time1'];
        $money_time2 = $fee_rs['money_time2'];
        
        $express = explode('|', $fee_rs['s18']);
        foreach ($list as $key => $value) {
            $list[$key]['express_list'] = $express;
            
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["addtime"]);
            
            // $user = M('fck')->where("id=" . $value['userid'] . "")->find();
            // $list[$key]['user_id'] = $user["user_id"];
            
            // $list[$key]['user_tel'] = $user["user_tel"];
            // $trade_count = M('trade')->where("userid=" . $value['userid'] . "")->count();
            
            // $list[$key]['trade_count'] = $trade_count;
            $list[$key]['price'] = round($list[$key]['price'], 2);
            $list[$key]['cny'] = C('CNY');
            
            $list[$key]['trade_status'] = get_trade_log_status($value);
            
            // 打款倒计时
            $list[$key]['end_money_time1'] = strtotime('+' . $fee_rs['money_time1'] . ' minute', $value['addtime']);
            $end_money_time1_str = date('Y-m-d H:i:s', $list[$key]['end_money_time1']);
            $list[$key]['end_money_time1_str'] = $end_money_time1_str;
            $end_money_time2_str = '';
            if ($list[$key]['is_payment'] == 1) {
                // 收款倒计时
                $list[$key]['end_money_time2'] = strtotime('+' . $fee_rs['money_time2'] . ' minute', $list[$key]['payment_time']);
                $end_money_time2_str = date('Y-m-d H:i:s', $list[$key]['end_money_time2']);
                $list[$key]['end_money_time2_str'] = $end_money_time2_str;
            }
            $trade['addtime_str'] = date("Y-m-d H:i:s", $trade["addtime"]);
            
            // $user = M('fck')->where("id=" . $trade['userid'] . "")->find();
            // $trade['user_id'] = $user["user_id"];
            // $trade_count = M('trade')->where("userid=" . $trade['userid'] . "")->count();
            
            // $trade['trade_count'] = $trade_count;
            $trade['price'] = round($trade['price'], 2);
            $trade['trade_status'] = get_trade_log_status($list[$key]);
            // $trade['min'] = ($trade['min']);
            // $trade['max'] = ($trade['max']);
            
            $payment = explode('|', C('payment'));
            
            $trade['payment_str'] = $payment[$trade["payment"]];
            
            $buyer = M('fck')->field('user_name,nickname,user_tel,alipay_img,weixin_img,bank_card,user_id')
                ->where("id=" . $list[$key]['userid'] . "")
                ->find();
            
            $seller = M('fck')->field('user_name,nickname,user_tel,alipay_img,weixin_img,bank_card,user_id')
                ->where("id=" . $list[$key]['sell_userid'] . "")
                ->find();
            
            $add_userid = $list[$key]['sell_userid'];
            // $shouquan = get_shouquan($add_userid);
            
            $shouquan = M('shouquan')->where('id=' . $list[$key]['shouquan_id'])
                ->order('id desc')
                ->find();
            
            if ($shouquan != NULL) {
                $add_userid = $shouquan['re_id'];
            }
            
            $user_address = M('user_addr_book')->where("user_id=" . $add_userid . "")
                ->order(' is_default desc ')
                ->find();
            
            $list[$key]['user_address'] = $user_address;
            $list[$key]['seller'] = $seller['user_name'];
            $list[$key]['buyer'] = $buyer['user_name'];
            
            $time1 = $list[$key]['end_money_time1'] - time();
            $time2 = $list[$key]['end_money_time2'] - time();
            
            $list[$key]['time1'] = $time1;
            $list[$key]['time2'] = $time2;
            
            $list[$key]['is_time1'] = 0;
            $list[$key]['is_time2'] = 0;
            $list[$key]['is_cancel_btn'] = 0;
            $list[$key]['is_payment_btn'] = 0;
            $list[$key]['is_collect_btn'] = 0;
            $list[$key]['is_tousu_btn'] = 0;
            if ($value['is_payment'] == 0) {
                $list[$key]['is_time1'] = 1;
                $list[$key]['is_payment_btn'] = 1;
            }
            if ($value['is_payment'] == 1 && $value['is_collect'] == 0 && $value['is_tousu'] == 0) {
                $list[$key]['is_time2'] = 1;
                $list[$key]['is_collect_btn'] = 1;
                $list[$key]['is_cancel_btn'] = 0;
                // $list[$key]['is_cancel_btn'] = 1;
            }
            if ($value['is_payment'] == 1 && $value['is_collect'] == 0) {
                
                $list[$key]['is_tousu_btn'] = 1;
            }
            if ($value['status'] != 0) {
                // $list[$key]['is_cancel_btn'] = 1;
            }
            if ($value['is_cancel'] == 1) {
                $list[$key]['is_cancel_btn'] = 0;
                $list[$key]['is_payment_btn'] = 0;
                $list[$key]['is_collect_btn'] = 0;
                $list[$key]['is_tousu_btn'] = 0;
                $list[$key]['is_time1'] = 0;
                $list[$key]['is_time2'] = 0;
            }
            $tip = '';
            IF ($value['userid'] == $user_id && $func == '') {
                $list[$key]['user_tel'] = $seller["user_tel"];
                $list[$key]['user_tel'] = $seller["user_id"];
                $list[$key]['user_id'] = $seller["user_name"];
                $list[$key]['alipay'] = $seller["alipay_img"];
                $list[$key]['weixin'] = $seller["weixin_img"];
                $list[$key]['bank_card'] = $seller["bank_card"];
                $tip = '进货方';
                $list[$key]['user_sf'] = $tip;
                M('trade_log')->where('id=' . $value['id'])->setField('is_buy_read', 1);
            }
            
            IF ($value['sell_userid'] == $user_id && $func == '') {
                
                $list[$key]['user_tel'] = $buyer["user_tel"];
                $list[$key]['user_tel'] = $buyer["user_id"];
                $list[$key]['user_id'] = $buyer["user_name"];
                $list[$key]['alipay'] = $buyer["alipay_img"];
                $list[$key]['weixin'] = $buyer["weixin_img"];
                $list[$key]['bank_card'] = $buyer["bank_card"];
                $tip = '销售方';
                $list[$key]['user_sf'] = $tip;
                M('trade_log')->where('id=' . $value['id'])->setField('is_sell_read', 1);
            }
            $list[$key]['remain_money_time'] = 0;
            
            $list[$key]['end_money_time'] = strtotime("+" . $money_time2 . " minute", $value["payment_time"]);
            if ((time() < $list[$key]['end_money_time'])) {
                
                $list[$key]['remain_money_time'] = $list[$key]['end_money_time'] - time();
            }
            
            // check_status($value['id']);
        }
        
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        if ($_POST['is_mobile'] == 1) {
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $data['trade_num'] = count($list);
            $data['current_count'] = count($list);
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    // 获取预购管理 2待付款3待发货4待收货5已完成6被投诉7已取消
    function get_buy_trade_list()
    {
        $is_mobile = I('post.is_mobile', 1);
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 1000);
        $form = M('trade');
        $status = 0;
        $type = I('post.type', 3);
        $trade_type = I('post.trade_type', 4);
        $user_id = I('post.user_id', 82527);
        $STR1 = '';
        
        $STR = ' ';
        
        $STR = $STR . ' and  (t.userid=' . $user_id . ' OR (money_uid=' . $user_id . ' AND money_status=0)) ';
        
        // 2待付款3待发货4待收货5已完成6被投诉7已取消
        $where = '';
        if ($type == 1) {}
        if ($type == 2) {
            $where = ' and  t.price>=T.deal  and t.is_cancel=0  AND t.status!=0   ';
        }
        if ($type == 3) {
            
            $where = '   and T.is_express=0  ';
        }
        if ($type == 4) {
            $where = ' and    T.is_express=1  ';
        }
        if ($type == 5) {
            
            $where = ' and T.is_express=2   ';
        }
        if ($type == 6) {
            
            $where = ' and   exists(SELECT ID FROM XT_TRADE_LOG WHERE XT_TRADE_LOG.trade_id= t.id and XT_TRADE_LOG.is_tousu=1 )  ';
        }
        if ($type == 7) {
            
            $where = ' and t.status=2 and t.is_cancel=1 ';
        }
        if ($type == 1 || $type == 2) {
            
            $list = $form->alias('t')
                ->join("xt_trade_log AS h ON   h.trade_id = t.id", 'LEFT')
                ->join("xt_goods AS g ON   g.id =h.goods_id", 'LEFT')
                ->where('t.payment=0 and   t.status>=' . $status . '   ' . $STR . $where)
                ->field('t.*,h.trade_id,t.price as trade_price,G.title,G.img as img_url,g.limit_money,g.crown_price,G.agent_use,G.price as real_price,g.agent_bi,  FROM_UNIXTIME(t.addtime, "%Y-%m-%d %H:%i:%S") AS add_time_str')
                ->page($page_index . ',' . $page_size)
                ->order('T.addtime desc')
                ->group('H.trade_id')
                ->select();
        }
        if ($type == 3 || $type == 4 || $type == 5) {
            
            $list = M('trade_log')->alias('t')
                ->join("xt_goods AS g ON   g.id = t.goods_id", 'LEFT')
                ->join("xt_trade AS h ON   h.id = t.trade_id", 'LEFT')
                ->union(array(
                'SELECT t.is_tiqu,0 as is_hui,0 as is_user_collect,t.shouquan_id,t.complete_time,t.payment,t.id as trade_id,t.express,t.express_no,0 as is_payment,t.is_express,0 as is_collect,t.express_time,0 as payment_time,0 as collection_time,t.id,t.addtime,t.num,t.userid ,G.title,G.img as img_url,G.price as real_price,  FROM_UNIXTIME(t.addtime, "%Y-%m-%d %H:%i:%S")
                    AS add_time_str FROM xt_trade t left join xt_goods g on  g.id = t.goods_id where   t.status>=' . $status . ' and  t.payment=1  and t.userid=' . $user_id . '  ' . $where
            ), true)
                ->where('  t.status>=' . $status . ' and  t.is_payment=1  and t.sell_userid=' . $user_id . '  ' . $where)
                ->field('h.is_tiqu,t.is_hui,t.is_user_collect,h.shouquan_id,h.complete_time,h.payment,t.trade_id,t.express,t.express_no,t.is_payment,t.is_express,t.is_collect,t.express_time,t.payment_time,T.collection_time,t.id,t.addtime,t.num,t.userid as userid,G.title,G.img as img_url,G.price as real_price,  FROM_UNIXTIME(t.addtime, "%Y-%m-%d %H:%i:%S") AS add_time_str')
                ->page($page_index . ',' . $page_size)
                ->order(' addtime desc')
                ->select();
        }
        
        $fck = D('Fck');
        $fee = M('fee')->field('s7,s15,s17,i7,s12')->find();
        
        foreach ($list as $key => $value) {
            
            if ($type == 1 || $type == 2) {
                $trade_num = M('trade_log')->where('trade_id=' . $value['id'])->count();
                $list[$key]['trade_num'] = $trade_num;
                $price1 = 0;
                $price2 = 0;
                
                $price = M('trade_log')->where('trade_id=' . $value['id'] . ' AND (status=1 OR status=0) ')->sum('price');
                if ($price == null) {
                    $price1 = $value['trade_price'] * $fee['s12'] * 0.01;
                    $price2 = $value['trade_price'] - $price1;
                } else {
                    $price1 = $price;
                    $price2 = $value['trade_price'] - $price1;
                }
                
                $list[$key]['price1'] = get_help_money($value);
                $list[$key]['price2'] = $price2;
            } else {
                
                $list[$key]['trade_num'] = 0;
                
                // set_user_order_num($buyer);
            }
            $user = M('fck')->field('user_tel,nickname,alipay_img,weixin_img,bank_name,bank_card,user_name,user_id,id')
                ->where("id=" . $list[$key]['userid'] . "")
                ->find();
            $list[$key]['user_name'] = $user['user_id'];
            $list[$key]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$key]['img_url']);
            if (strpos($list[$key]['img_url'], 'http') !== false) {} else {
                $list[$key]['img_url'] = C('oss_url') . $list[$key]['img_url'];
            }
            $list[$key]['shop_logo'] = 'app/img/logo.png';
            if ($list[$key]['payment'] == 0) {
                $list[$key]['shop_logo'] = 'app/img/money.png';
            }
            if ($list[$key]['payment'] == 1) {
                $list[$key]['shop_logo'] = 'app/img/money1.png';
            }
            
            $list[$key]['trade_status'] = get_trade_status($value);
            
            $payment_trade_num = M('trade_log')->where('trade_id=' . $value['id'] . ' AND is_cancel=0 and is_payment=0 and status=1')->count();
            
            $list[$key]['shop_name'] = '拍客商城';
            $list[$key]['payment_trade_num'] = $payment_trade_num;
            
            $list[$key]['end_money_time'] = 0;
            $list[$key]['is_show_express_btn'] = 0;
            $end_money_time = 0;
            if ($value['is_express'] == 1) {
                $money_time1 = $fee['s7'];
                $end_money_time = strtotime("+" . $money_time1 . " minute", $value["express_time"]);
                $now = time();
                if ($end_money_time < $now) {
                    $list[$key]['is_show_express_btn'] = 1;
                    $list[$key]['end_money_time'] = $end_money_time;
                } else {
                    
                    $list[$key]['remain_express_time'] = $end_money_time - $now;
                }
                $list[$key]['end_money_time'] = $end_money_time;
            }
            $money_time1 = $fee['s15'];
            
            $list[$key]['is_tiqu_btn'] = 0;
            $end_money_time = 0;
            if ($value['is_tiqu'] == 0) {
                $end_money_time = strtotime("+" . $money_time1 . " minute", $value["complete_time"]);
                $now = time();
                if ($end_money_time < $now) {
                    $list[$key]['is_tiqu_btn'] = 1;
                } else {
                    
                    $list[$key]['remain_tiqu_time'] = $end_money_time - $now;
                }
            }
            
            $list[$key]['agent_use_str'] = C('agent_use');
            $list[$key]['limit_money_str'] = C('agent_use');
            
            $list[$key]['is_tiqu_btn'] = 0;
            if ($value['is_tiqu'] == 0) {
                $money_time1 = $fee['s17'];
                if ($value['shouquan_id'] == 0) {
                    $money_time1 = $fee['i7'];
                }
                $end_money_time = strtotime("+" . $money_time1 . " minute", $value["complete_time"]);
                $now = time();
                
                $list[$key]['remain_tiqu_award_time_str'] = date('Y-m-d H:i', $end_money_time);
                if ($end_money_time < $now) {
                    $list[$key]['is_tiqu_btn'] = 1;
                } else {
                    $list[$key]['remain_tiqu_award_time'] = $end_money_time - $now;
                }
            }
            
            $agent_bi = (int) ($value['trade_price'] / $value['agent_bi']);
            $percent = 100;
            $shouquan_user = null;
            $shouquan = null;
            if ($value['shouquan_id'] > 0) {
                $shouquan = M('shouquan')->where('id=' . $value['shouquan_id'])->find();
                $userid = $shouquan['re_id'];
                $shouquan_user = M('fck')->field('id,u_level,user_id')
                    ->where('id=' . $userid)
                    ->find();
                $percent = $shouquan['profit'];
            }
            
            $limit_money = $value['limit_money'] * $agent_bi * $percent * 0.01;
            
            $trade_count = M('trade')->where('userid=' . $value['userid'] . ' AND complete_time>0 and id<' . $value['id'])->count();
            
            if ($trade_count > 0) {
                $limit_money = $value['crown_price'] * $agent_bi * $percent * 0.01;
            }
            
            $agent_use = $value['agent_use'] * $value['num'];
            if ($agent_use > 0) {
                
                if ($shouquan_user != NULL) {
                    // $agent_use = 0;
                    IF ($shouquan_user['u_level'] == 1) {
                        
                        $agent_use = $value['agent_use'] * $value['num'];
                    }
                } else {}
                
                IF ($limit_money > 0) {}
            }
            
            $list[$key]['award_limit_money'] = 0;
            $list[$key]['award_agent_use'] = $limit_money;
            $list[$key]['payment_time_str'] = date('Y-m-d H:i', $value['payment_time']);
            ;
            $list[$key]['express_time_str'] = date('Y-m-d H:i', $value['express_time']);
            ;
            $list[$key]['collect_time_str'] = date('Y-m-d H:i', $value['collection_time']);
            ;
            
            $where = '';
            $group = 't.id';
            // 待发货
            if ($type == 3) {
                $where = ' AND T.is_express=0 and T.is_payment=1 and T.is_collect=0 AND T.id=' . $value['id'];
            }
            if ($type == 4) {
                $where = ' AND T.is_express=1 and T.is_payment=1 and T.is_collect=1';
                $group = 't.trade_id';
            }
            if ($type == 5) {
                $where = ' AND T.is_express=2  AND T.id=' . $value['id'];
                $group = 't.id';
            }
            
            $goods_list = M('trade_log')->alias('t')
                ->join("xt_goods AS g ON   g.id = t.goods_id     ", 'left')
                ->where('t.trade_id=' . $value['trade_id'] . '  ' . $where)
                ->field('t.*,g.price AS real_price,g.title,g.img as img_url')
                ->group($group)
                ->select();
            if ($type == 1 || $type == 2) {
                foreach ($goods_list as $i => $item) {
                    if ($item['is_cancel'] == 1) {
                        $goods_list[$i]['is_payment'] = 1;
                    }
                }
            }
            $list[$key]['goods_list'] = $goods_list;
            
            if ( $value['is_tiqian'] ==1) {
                
                $list[$key]['is_tiqu_btn'] = 1;
            }
            
            if ($value['is_user_collect'] == 0) {
                
                $list[$key]['user_collect_str'] = '确定收款';
            }
            if ($value['is_user_collect'] == 1) {
                
                $list[$key]['user_collect_str'] = '已回款';
            }
        }
        
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        if ($is_mobile == 1) {
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $data['trade_num'] = count($list);
            $data['current_count'] = count($list);
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    // 获取销售详细列表
    function get_buy_trade_user_list()
    {
        $form = M('trade_log');
        $status = 0;
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 1000);
        $trade_id = I('post.trade_id', 284948);
        $user_id = I('post.user_id', 82824);
        $func = $_POST['func'];
        $STR1 = '';
        
        $STR = ' ';
        
        $STR = $STR . ' and  t.trade_id  in (' . $trade_id . ') AND  (t.money_uid=' . $user_id . ' or t.sell_userid=' . $user_id . ' )';
        
        $list = $form->alias('t')
            ->join("xt_goods AS g ON   g.id = t.goods_id", 'LEFT')
            ->where('  t.status>=' . $status . '   ' . $STR)
            ->field('t.*,G.title,G.price as real_price,  FROM_UNIXTIME(t.addtime, "%Y-%m-%d %H:%i:%S") AS add_time_str')
            ->page($page_index . ',' . $page_size)
            ->order('T.addtime desc')
            ->select();
        
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('jifen_time1,money_time1')->find();
        $jifen_time1 = $fee_rs['jifen_time1'];
        $money_time1 = $fee_rs['money_time1'];
        $profit_list = ARRAY();
        for ($x = 1; $x < 11; $x ++) {
            $profit_list[] = $x * 10;
        }
        foreach ($list as $key => $value) {
            $trade_num = M('trade_log')->where('trade_id=' . $value['id'])->count();
            
            $list[$key]['profit_list'] = $profit_list;
            $list[$key]['trade_num'] = $trade_num;
            // $list[$key]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$key]['img_url']);
            // if (strpos($list[$key]['img_url'], 'http') !== false) {} else {
            // $list[$key]['img_url'] = C('oss_url') . $list[$key]['img_url'];
            // }
            $list[$key]['trade_status'] = get_trade_log_status($value);
            
            if ($user_id == $value['money_uid']) {
                $user_id = $value['sell_userid'];
            }
            
            $tip = '';
            IF ($value['sell_userid'] == $user_id && $func == '') {
                $seller = M('fck')->field('user_tel,nickname,alipay_img,weixin_img,bank_name,bank_card,user_id,user_name')
                    ->where("id=" . $list[$key]['userid'] . "")
                    ->find();
                $list[$key]['user_tel'] = $seller["user_tel"];
                $list[$key]['user_id'] = $seller["user_name"];
                $list[$key]['alipay'] = $seller["alipay_img"];
                $list[$key]['weixin'] = $seller["weixin_img"];
                $list[$key]['bank'] = $seller["bank_name"];
                $list[$key]['bank_card'] = $seller["bank_card"];
                $tip = '销售方';
                $list[$key]['user_sf'] = $tip;
                M('trade_log')->where('id=' . $value['id'])->setField('is_buy_read', 1);
            }
            
            IF ($value['userid'] == $user_id && $func == '') {
                $buyer = M('fck')->field('user_tel,nickname,alipay_img,weixin_img,bank_name,bank_card,user_id,user_name')
                    ->where("id=" . $list[$key]['sell_userid'] . "")
                    ->find();
                
                $list[$key]['user_tel'] = $buyer["user_tel"];
                $list[$key]['user_id'] = $buyer["user_name"];
                $list[$key]['alipay'] = $buyer["alipay_img"];
                $list[$key]['weixin'] = $buyer["weixin_img"];
                $list[$key]['bank'] = $buyer["bank_name"];
                $list[$key]['bank_card'] = $buyer["bank_card"];
                $tip = '进货方';
                $list[$key]['user_sf'] = $tip;
                M('trade_log')->where('id=' . $value['id'])->setField('is_sell_read', 1);
            }
            
            $list[$key]['remain_money_time'] = 0;
            
            $list[$key]['end_money_time'] = strtotime("+" . $money_time1 . " minutes", $value["addtime"]);
            if ((time() < $list[$key]['end_money_time'])) {
                
                $list[$key]['remain_money_time'] = $list[$key]['end_money_time'] - time();
            }
            
            $list[$key]['is_show_need'] = 1;
            $count = M('trade')->where('userid=' . $user_id . ' AND is_cancel=0 ')->count();
            if ($count == 1) {
                
                $list[$key]['is_show_need'] = 0;
            }
            $count = M('trade')->where('userid=' . $user_id . ' AND is_cancel=0 and is_yd=1 ')->count();
            if ($count == 1) {
                
                $list[$key]['is_show_need'] = 0;
            }
        }
        
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        if ($_POST['is_mobile'] == 1) {
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $data['trade_num'] = count($list);
            $data['current_count'] = count($list);
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    function get_my_trade_list()
    {
        $form = M('trade');
        $status = 0;
        $type = $_POST['type'];
        $user_id = $_POST['user_id'];
        $status_str = $_POST['status'];
        if ($status_str == 'develop') {
            $status = 0;
        }
        if ($status_str == 'online') {
            $status = 1;
        }
        $list = $form->where(' userid=' . $user_id)
            ->order(' addtime desc')
            ->select();
        foreach ($list as $key => $value) {
            
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["addtime"]);
            
            $user = M('fck')->where("id=" . $value['userid'] . "")->find();
            $list[$key]['user'] = $user;
            $list[$key]['user_id'] = $user["user_id"];
            $list[$key]['nickname'] = $user["nickname"];
            $trade_count = M('trade')->where("userid=" . $value['userid'] . "")->count();
            
            $list[$key]['trade_count'] = $trade_count;
            $list[$key]['price'] = round($list[$key]['price'], 2);
            
            $payment = explode('|', C('payment'));
            
            $list[$key]['payment_str'] = $payment[$value["payment"]];
            $list[$key]['avatar'] = $user["avatar"];
            $list[$key]['DAS'] = C("DAS");
            
            $trade_count = M('trade_log')->where("trade_id=" . $value['id'] . "")->count();
            
            $list[$key]['farvirate'] = $trade_count;
            $list[$key]['trade_price'] = round($value['price'] - $value['deal']);
            
            if ($type == 1) {
                
                $list[$key]['type_str'] = '出售';
            }
            if ($type == 2) {
                
                $list[$key]['type_str'] = '购买';
            }
            
            $list[$key]['is_time1'] = 0;
            $list[$key]['is_time2'] = 0;
            $list[$key]['is_cancel_btn'] = 0;
            $list[$key]['is_payment_btn'] = 0;
            $list[$key]['is_collect_btn'] = 0;
            if ($value['is_payment'] == 0) {
                $list[$key]['is_time1'] = 1;
                $list[$key]['is_payment_btn'] = 1;
            }
            if ($value['is_payment'] == 1 && $value['is_collect'] == 0) {
                $list[$key]['is_time2'] = 1;
                $list[$key]['is_collect_btn'] = 1;
            }
            if ($value['status'] != 0) {
                $list[$key]['is_cancel_btn'] = 1;
            }
        }
        
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        if ($_POST['is_mobile'] == 1) {
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    function comfirm_trade()
    {
        $user_id = I('user_id', 82503);
        $type = I('type', 'shoukuan');
        $trade_detail_id = I('trade_detail_id', 263173);
        
        $trade_detail = M('trade_log')->where("id=" . $trade_detail_id . "")->find();
        $trade = M('trade')->where("id=" . $trade_detail['trade_id'] . "")->find();
        
        $buyid = $trade_detail['userid'];
        $sellid = $trade_detail['sell_userid'];
        $buy = M('fck')->field('*')
            ->where('id=' . $buyid)
            ->find();
        $sell = M('fck')->field('*')
            ->where('id=' . $sellid)
            ->find();
        if ($type == 'fukuan') {
            if ($trade_detail['is_tousu'] == 1) {
                $this->ajaxError('正在投诉,不可付款！');
                exit();
            }
            if ($trade_detail['is_cancel'] == 1) {
                $this->ajaxError('订单已取消,不可付款！');
                exit();
            }
            $img_url = $_POST['img_url'];
            
            if (empty($img_url)) {
                $this->ajaxError('请上传截图！');
                exit();
            }
            
            if ($user_id == $trade_detail['sell_userid']) {
                IF ($trade_detail['money_uid'] > 0) {
                    IF ($trade_detail['money_status'] == 1) {
                        $this->ajaxError('资助申请未审核通过,先不用付款！');
                        exit();
                    }
                }
                
                M('trade_log')->where('ID=' . $trade_detail_id)->setField('img_url', $img_url);
            }
            if ($user_id == $trade_detail['money_uid']) {
                M('trade_log')->where('ID=' . $trade_detail_id)->setField('money_img_url', $img_url);
                M('trade_log')->where('ID=' . $trade_detail_id)->setField('is_money', 1);
                M('trade_log')->where('ID=' . $trade_detail_id)->setField('money_time', time());
            }
            
            $trade_detail = M('trade_log')->where("id=" . $trade_detail_id . "")->find();
            
            if ($trade_detail['money_uid'] > 0) {
                if (! EMPTY($trade_detail['img_url']) && ! EMPTY($trade_detail['money_img_url'])) {
                    M('trade_log')->where('ID=' . $trade_detail_id)->setField('is_payment', 1);
                    M('trade_log')->where('ID=' . $trade_detail_id)->setField('payment_time', time());
                    M('trade_log')->where('id=' . $trade_detail_id)->setField('is_sell_read', 0);
                }
            } else {
                
                M('trade_log')->where('ID=' . $trade_detail_id)->setField('is_payment', 1);
                M('trade_log')->where('ID=' . $trade_detail_id)->setField('payment_time', time());
                M('trade_log')->where('id=' . $trade_detail_id)->setField('is_sell_read', 0);
            }
            import("@.ORG.getui.GeTui");
            $gt = new GeTui();
            if (! EMPTY($sell['cid'])) {
                // $gt->pushMessageToSingle1($sell['cid'], 'update_trade_num');
                $user_ids = array(
                    $sell['cid']
                );
                // PushInfoMatter($user_ids, 'update_trade_num');
            }
            set_user_order_num($sell);
            set_user_help_order_num($buy);
            // 推送数量
            
            $fck_num = M('fck_num')->where('uid=' . $buy['id'])->find();
            
            $data = array();
            $data['type'] = "update_order_num";
            $data['order_num'] = $fck_num;
            $data['uid'] = $buy['id'];
            $data['is_push'] = 1;
            $data['push_msg'] = '您有新的收款订单,请及时收款';
            
            push_msg($data, $buy['id']);
            
            // 推送数量
            
            $fck_num = M('fck_num')->where('uid=' . $sell['id'])->find();
            
            $data = array();
            $data['type'] = "update_order_num";
            $data['order_num'] = $fck_num;
            $data['uid'] = $sell['id'];
            
            push_msg($data, $sell['id']);
            
            // 发送短信
            send_order_msg($buy['user_id'], 'order_express');
        }
        
        if ($type == 'shoukuan') {
            
            $express_no = I('express_no', '22');
            
            $express = I('express', '2');
            if (EMPTY($express)) {
                $this->ajaxError('请输入快递公司！');
                exit();
            }
            // if (EMPTY($express)) {
            // $this->ajaxError('请输入快递编号！');
            // exit();
            // }
            if (EMPTY($express_no)) {
                if (strpos($express, '当面') !== false) {} else {
                    $this->ajaxError('请输入快递编号！');
                    return;
                }
            }
            $fee_rs = M('fee')->field('s3,recommend_gupiao,recommend_order,jjbb,s16,ssb_money,ssb_shouxu,s12,trade_bs')->find(1);
            $money = $trade_detail['price'] * $fee_rs['s12'] * 0.01;
            if ($trade_detail['is_tousu'] == 1) {
                $this->ajaxError('正在投诉,不可收款！');
                exit();
            }
            if ($sell['agent_use'] < $money) {
                // $this->error(C('agent_use') . '不足');
                // exit();
            }
            if ($trade_detail['is_collect'] == 1) {
                $this->ajaxError('已收款,不能重复收款');
                exit();
            }
            $shouquan = get_shouquan($sell['id']);
            
            // 扣除授权方授权金额
            if ($shouquan != NULL) {
                $fck = D('Fck');
                $shouquan_user = M('fck')->field('*')
                    ->where('id=' . $shouquan['re_id'])
                    ->find();
                
                if ($shouquan_user['live_gupiao'] < $money) {
                    // $this->error('授权方' . C('live_gupiao') . '不足');
                    // exit();
                }
            }
            
            M('trade')->where('id=' . $trade['id'])->setField('express', $express);
            M('trade')->where('id=' . $trade['id'])->setField('express_no', $express_no);
            M('trade')->where('id=' . $trade['id'])->setField('is_express', 1);
            M('trade')->where('id=' . $trade['id'])->setField('express_time', time());
            
            M('trade_log')->where('id=' . $trade_detail_id)->setField('express', $express);
            M('trade_log')->where('id=' . $trade_detail_id)->setField('express_no', $express_no);
            M('trade_log')->where('id=' . $trade_detail_id)->setField('is_express', 1);
            M('trade_log')->where('id=' . $trade_detail_id)->setField('express_time', time());
            M('trade_log')->where('id=' . $trade_detail_id)->setField('is_buy_read', 0);
            buy_shoukuan($trade_detail_id, $trade_detail, $trade, $buy);
            // M('goods')->where('id=' . $trade['goods_id'])->setDec('stock', $trade['num']);
            
            set_user_order_num($sell);
            set_user_help_order_num($buy);
            
            // 推送数量
            
            $fck_num = M('fck_num')->where('uid=' . $buy['id'])->find();
            
            $data = array();
            $data['type'] = "update_order_num";
            $data['order_num'] = $fck_num;
            $data['uid'] = $buy['id'];
            
            push_msg($data, $buy['id']);
            
            // 推送数量
            
            $fck_num = M('fck_num')->where('uid=' . $sell['id'])->find();
            
            $data = array();
            $data['type'] = "update_order_num";
            $data['order_num'] = $fck_num;
            $data['uid'] = $sell['id'];
            $data['is_push'] = 1;
            $data['push_msg'] = '您有新的收货订单,请及时收货';
            
            push_msg($data, $sell['id']);
            // 发送短信
            send_order_msg($sell['user_id'], 'order_express3');
        }
        if ($type == 'cancel') {
            if ($trade_detail['is_tousu'] == 1) {
                $this->ajaxError('正在投诉,不可取消！');
                exit();
            }
            M('trade_log')->where('ID=' . $trade_detail_id)->setField('is_cancel', 1);
            M('trade_log')->where('ID=' . $trade_detail_id)->setField('cancel_time', time());
            
            M('trade')->where('ID=' . $trade['id'])->setDec('deal', $trade_detail['price']);
            
            set_user_order_num($sell);
            set_user_help_order_num($buy);
            
            // 推送数量
            
            $fck_num = M('fck_num')->where('uid=' . $buy['id'])->find();
            
            $data = array();
            $data['type'] = "update_order_num";
            $data['order_num'] = $fck_num;
            $data['uid'] = $buy['id'];
            
            push_msg($data, $buy['id']);
            
            // 推送数量
            
            $fck_num = M('fck_num')->where('uid=' . $sell['id'])->find();
            
            $data = array();
            $data['type'] = "update_order_num";
            $data['order_num'] = $fck_num;
            $data['uid'] = $sell['id'];
            
            push_msg($data, $sell['id']);
        }
        if ($type == 'tousu') {
            $img_url = $_POST['tousu_remark'];
            
            if (empty($img_url)) {
                $this->ajaxError('请输入投诉说明！');
                exit();
            }
            if ($trade_detail['is_payment'] == 0) {
                $this->ajaxError('买家未确认付款,不可投诉！');
                exit();
            }
            
            M('trade_log')->where('ID=' . $trade_detail_id)->setField('tousu_remark', $img_url);
            M('trade_log')->where('ID=' . $trade_detail_id)->setField('is_tousu', 1);
            M('trade_log')->where('ID=' . $trade_detail_id)->setField('tousu_add_time', time());
            
            set_user_order_num($sell);
            set_user_help_order_num($buy);
            $this->ajaxSuccess('投诉成功,请等待审核！');
            return;
        }
        $this->ajaxSuccess('操作成功！');
    }

    public function Tousu()
    {
        // 处理提交按钮
        $action = $this->_get('action');
        // 获取复选框的值
        $user_id = $this->_get('user_id');
        $PTid = $this->_get('order_id');
        if (! isset($PTid) || empty($PTid)) {
            echo $action = $_POST['action'];
            echo $PTid = $_POST['id'];
        }
        switch ($action) {
            case 'buy':
                $this->_buyer_win($PTid, $user_id);
                break;
            case 'sell':
                $this->_seller_win($PTid, $user_id);
                break;
            
            default:
                // $bUrl = __URL__.'/adminMenber';
                // $this->_box(0,'没有该会员！',$bUrl,1);
                $this->Success('没有该会员！');
                break;
        }
    }

    // 开启会员
    private function _buyer_win($PTid = 0, $user_id = 0)
    {
        $where['id'] = array(
            'in',
            $PTid
        );
        $ret = FALSE;
        
        $trade_detail_id = $PTid;
        
        $trade_detail = M('trade_log')->where("id=" . $trade_detail_id . "")->find();
        $trade = M('trade')->where("id=" . $trade_detail['trade_id'] . "")->find();
        
        $buyid = $trade_detail['userid'];
        $buy = M('fck')->field('*')
            ->where('id=' . $buyid)
            ->find();
        $rs = M('trade_log')->where('id=' . $trade_detail_id)->setField('is_tousu', 0);
        $rs = M('trade_log')->where('id=' . $trade_detail_id)->setField('tousu_check_time', time());
        buy_shoukuan($trade_detail_id, $trade_detail, $trade, $buy);
        
        $this->ajaxSuccess('操作买家赢成功');
    }

    // 开启会员
    private function _seller_win($PTid = 0, $user_id = 0)
    {
        $fck = M('trade_log');
        $trade_detail_id = $PTid;
        
        $trade_detail = M('trade_log')->where("id=" . $trade_detail_id . "")->find();
        $trade = M('trade')->where("id=" . $trade_detail['trade_id'] . "")->find();
        
        $buyid = $trade_detail['userid'];
        $sell_userid = $trade_detail['sell_userid'];
        $buy = M('fck')->field('*')
            ->where('id=' . $buyid)
            ->find();
        $sell = M('fck')->field('*')
            ->where('id=' . $sell_userid)
            ->find();
        $rs = $fck->where('id=' . $trade_detail_id)->setField('is_tousu', 0);
        $rs = $fck->where('id=' . $trade_detail_id)->setField('is_lock', 1);
        $rs = $fck->where('id=' . $trade_detail_id)->setField('is_cancel', 1);
        $rs = $fck->where('id=' . $trade_detail_id)->setField('cancel_time', time());
        $rs = $fck->where('id=' . $trade_detail_id)->setField('tousu_check_time', time());
        if ($rs) {
            
            $ssb = $trade_detail['price'];
            $fee_rs = M('fee')->field('s3,recommend_gupiao,recommend_order,jjbb,s16,ssb_money,ssb_shouxu,s12,trade_bs')->find(1);
            
            $money = $ssb * $fee_rs['s12'] * 0.01;
            $ssb = $money + $ssb;
            
            M('fck')->execute('UPDATE __TABLE__ SET `agent_kt`=agent_kt-' . $ssb . '      where  id  =' . $sell['id']);
            
            $jjbz = '投诉完成扣除' . C('agent_kt') . $ssb;
            $fck = D('Fck');
            $fck->addencAdd($sell['id'], $sell['user_id'], - $ssb, 1, 0, 0, 0, $jjbz);
            
            M('fck')->execute('UPDATE __TABLE__ SET `agent_use`=agent_use+' . $ssb . '      where  id  =' . $sell['id']);
            
            $jjbz = '投诉完成返还' . C('agent_use') . $ssb;
            $fck = D('Fck');
            $fck->addencAdd($sell['id'], $sell['user_id'], - $ssb, 1, 0, 0, 0, $jjbz);
            
            $fck = M('fck');
            
            $rs = $fck->where('id=' . $buyid)->setField('is_lock', 1);
            $rs = M('trade')->where('id=' . $trade['id'])->setField('status', 2);
            $rs = M('trade')->where('id=' . $trade['id'])->setField('is_cancel', 1);
            $rs = M('trade')->where('id=' . $trade['id'])->setField('cancel_time', time());
            
            $this->ajaxSuccess('操作卖家赢成功');
        } else {
            $this->ajaxError('开启失败');
        }
    }

    public function dialog_goods_help()
    {
        $hide_goods_id = $_GET['goods_id'];
        $trade_id = I('get.trade_id', 0);
        
        $goods = all_goods_help_users($hide_goods_id, $trade_id);
        
        $this->assign('list', $goods);
        
        $this->display('dialog_goods_help');
    }

    public function dialog_goods_help_moneyflows()
    {
        $hide_goods_id = $_GET['goods_id'];
        $trade_id = I('get.trade_id', 0);
        
        $goods = all_goods_help_moneyflows($hide_goods_id, $trade_id);
        
        $this->assign('list', $goods);
        
        $this->display('dialog_goods_help_moneyflows');
    }

    public function goods_redis()
    {
        $form = M('goods');
        
        $list = $form->where('id>0 and type=1')->select();
        $XgRedis = D('XgRedis');
        foreach ($list as $i => $goods) {
            $XgRedis->set_redis_page_info('help_goods_list', $goods['id'], $goods);
        }
        
        $this->success('初始化成功');
    }

    // 前台新闻
    public function Goods()
    {
        $category_id = I('post.category_id', 0);
        $is_mobile = I('post.is_mobile', 0);
        $keyword = I('post.keyword', '');
        $user_id = I('post.user_id', 1);
        $min = $_POST['min'];
        $max = $_POST['max'];
        $check_status = I('get.check_status', 0);
        $this->assign('check_status', $check_status);
        $page_index = I('post.page_index', 1);
        $page_size = 10;
        $manager_id = 1;
        $map = array();
        $map['t.type'] = 1;
        $map['t.check_status'] = $check_status;
        $str = ' and 1=1 and t.type=1 and t.check_status=' . $check_status;
        if ($is_mobile == 1) {
            
            $page_size = I('post.page_size', 10000);
            $category_str = ' ';
            if ($category_id > 0) {
                $category_str = ' AND t.status=1  and t.is_show=1  AND T.category_id=' . $category_id;
                if ($category_id == 1000) {
                    
                    $category_str = ' AND t.status=1  and t.is_show=1  AND T.category_id>0 ';
                }
            } else {
                $str = ' AND  T.is_hot=1  AND t.status=1 AND t.stock>0  and t.is_show=1  ';
            }
            if ($min > 0 && $max > 0) {
                $map = ' t.status=1 AND T.stock>0  AND (T.crown_price1>=' . $min . ' AND T.crown_price1<=' . $max . ') ' . $str;
            } else {
                
                $map['_string'] = ' T.stock>0 ' . $category_str . $str;
            }
            
            $manager = get_manager($user_id);
            if ($manager != NULL) {
                if ($manager['is_trade'] == 1) {
                    $manager_id = $manager['id'];
                    $map['_string'] = ' T.stock>0 ' . $category_str . ' and  (h.re_path like  "%' . $manager_id . '%"   or h.id=' . $manager_id . ') ' . $str;
                } else {
                    $map['h.is_trade'] = array(
                        'eq',
                        0
                    );
                }
            } else {
                $map['h.is_trade'] = array(
                    'eq',
                    0
                );
            }
        }
        if (! empty($keyword)) {
            $map['T.title'] = array(
                'like',
                "%" . $keyword . "%"
            );
            
            // $map['user_id'] = array('eq',$user_id);
        }
        $form = M('goods');
        $now = time();
        
        $field = 'g.title as category,h.user_id as publish_user_id,h.user_name as user_name, t.user_id, t.id,t.title,t.img,T.is_payment,T.addtime,T.is_show,ROUND(T.agent_use,0) as agent_use,T.price,T.stock ,T.stock ,T.market_price,T.crown_price,T.crown_price1,floor(T.limit_money) AS limit_money,floor(T.agent_bi) as agent_bi,T.is_hot';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_article_category AS g ON   g.id = t.category_id     ", 'left')
            ->join("xt_fck AS h ON   h.id = t.user_id", 'left')
            ->where($map)
            ->count(); // 总页数
        
        $Page = new Page($count, $page_size);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        
        IF ($is_mobile == 1) {
            $p = $page_index;
        }
        
        $list = $form->alias('t')
            ->join("xt_article_category AS g ON   g.id = t.category_id     ", 'left')
            ->join("xt_fck AS h ON   h.id = t.user_id     ", 'left')
            ->where($map)
            ->field($field)
            ->order(' t.id desc,T.is_hot desc')
            ->page($p . ',' . $page_size)
            ->select();
        // if ($is_mobile == 1) {
        // foreach ($list as $i => $goods) {
        
        // }
        // }
        foreach ($list as $i => $goods) {
            // $list[$i] = remain_end_time($goods);
            
            $list[$i]['remain_end_time'] = $goods['real_end_time'] - time();
            // IF ($list[$i]['remain_end_time'] < 0) {
            // $max_time = M('goods_crowd_users')->field('id,all_crowd_price,add_time,user_name AS nickname,user_id,goods_crown_id')
            // ->where('goods_id=' . $goods['id'])
            // ->order('id
            // desc ')
            // ->max('add_time');
            
            // if ($max_time == null) {
            // $max_time = time();
            // }
            
            // $real_end_time = strtotime('+' . $goods['end_minute'] . ' minute', $max_time);
            
            // if ($real_end_time < time()) {
            // $real_end_time = strtotime('+' . $goods['end_minute'] . ' minute', time());
            // }
            
            // $list[$i]['remain_end_time'] = $real_end_time - time();
            // }
            
            $list[$i]['addtime_str'] = date("Y-m-d H:i:s", $goods["addtime"]);
            
            // $user = M('fck')->field('user_id')->where(array(
            // 'id' => $goods['user_id']
            // ))->find();
            // $list[$i]['user_name'] = $user['user_id'];
            
            // // 检测倒计时
            // check_crown($list[$i], $list[$i]['goods_crown']);
            // // 检测抵押倒计时
            // check_crown_status();
            
            // $list[$i]["is_collect"] = 0;
            // $count = M("goods_collect")->where("user_id=" . $user_id . " AND pid=" . $goods["id"])->count();
            // if ($count > 0) {
            // $list[$i]["is_collect"] = 1;
            // }
            $list[$i]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img']);
            if (strpos($list[$i]['img'], 'http') !== false) {} else {
                $list[$i]['img'] = C('oss_url') . $list[$i]['img'];
            }
            IF ($goods['user_id'] > 1) {
                
                $list[$i]['img'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $goods['img'];
            }
            
            $list[$i]['agent_use'] = round($list[$i]['crown_price']);
            $list[$i]['limit_money_str'] = C('limit_money');
            $list[$i]['agent_use_str'] = C('agent_use');
            $order_num = M('trade')->where('goods_id=' . $list[$i]['id'])->sum('num');
            if ($order_num == null) {
                $order_num = 0;
            }
            $list[$i]['order_num'] = $order_num;
            IF ($goods['publish_user_id'] == null) {
                
                $list[$i]['publish_user_id'] = '后台';
                $list[$i]['user_name'] = 'admin';
            }
            // $list[$i]['img'] = 'http://liubin1989.oss-cn-shanghai.aliyuncs.com/Public/2018091018541753.jpg?Expires=1540979987&OSSAccessKeyId=TMP.AQG6nE5BPsVvYHutc7-KuKPFBErk6s-iOXE3M3pUVG8IGsgGlvkhha8tRP7yADAtAhRZODFp8drIxQV1lQEN-cXCc5bC_QIVAKnRo0eHp4bhYWvN6w-Xt6_CCkXW&Signature=0w6WIeag2Xwb4%2FeH185%2FhR0hNqQ%3D';
        }
        
        $this->assign('list', $list); // 数据输出到模板
        
        $this->assign('keyword', $keyword);
        if ($is_mobile == 1) {
            $slider = ARRAY();
            $slider[0]['img'] = __ROOT__ . '/Public/Images/slides/s1.jpg';
            // $slider[1]['img'] = __ROOT__ . '/Public/Images/slides/s3.jpg';
            
            // IF ($category_id > 0) {
            // $category_list = M('article_category')->where('class_list like "%,' . $category_id . ',%"')->select();
            
            // foreach ($category_list as $i => $goods) {
            // $category_list[$i]['img'] = get_img_url($goods['img_url']);
            // // $category_list[$i]['img'] = $category_list[$i]['img'] ;
            // }
            // }
            $main_category = array();
            $all = array();
            $all['id'] = 1000;
            $all['title'] = '全部';
            $main_category = M('article_category')->field('id,title')
                ->where('channel_id=1')
                ->order('   sort_id asc,id desc')
                ->select();
            
            $all_money = get_help_jh_money($user_id);
            $main_category[] = $all;
            $data = array();
            $data['data'] = $list;
            $data['main_category'] = array_reverse($main_category);
            // $data['category_list'] = $category_list;
            $data['all_money'] = $all_money;
            $data['slider'] = $slider;
            $data['current_count'] = count($list);
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->assign('type', 1);
            
            $this->display();
        }
    }

    public function TradeHui()
    {
        $id = $this->_get('id');
        $status = $this->_get('is_hot');
        
        $goods_crown = M("trade_log")->where("  id=" . $id)->find();
        
        if ($goods_crown['is_hui'] == 1) {
            $this->ajaxError('已回款的订单,不允许操作！');
            exit();
        }
        
        $count = M("trade_log")->where("  id=" . $id . '   AND  is_payment=0 ')->count();
        if ($count > 0) {
            $this->ajaxError('有未完成的订单,不允许操作！');
            exit();
        }
        
        $User = M('trade_log');
        $where['id'] = $id;
        $rs = $User->where($where)->setField('is_hui', 1);
        $rs = $User->where($where)->setField('hui_time', time());
        if ($rs) {
            $this->ajaxSuccess('操作成功！');
            exit();
        } else {
            $this->ajaxError('操作失败');
            exit();
        }
    }

    public function TradeHot()
    {
        $id = $this->_get('id');
        $status = $this->_get('is_hot');
        
        $goods_crown = M("trade")->where("  id=" . $id)->find();
        
        if ($goods_crown['is_cancel'] == 1) {
            $this->ajaxError('取消的订单,不允许操作！');
            exit();
        }
        
        $count = M("trade_log")->where("  trade_id=" . $id . '   AND  is_cancel=0 and  is_collect=0 ')->count();
        if ($count > 0 && $status == 1) {
            $this->ajaxError('有未完成的订单,不允许操作！');
            exit();
        }
        
        $User = M('trade');
        $where['id'] = $id;
        $rs = $User->where($where)->setField('is_hot', $status);
        $rs = $User->where($where)->setField('open_hot_time', time());
        if ($rs) {
            if ($status == 1) {
                
                $user = M("fck")->where("  id=" . $goods_crown['userid'])
                    ->field('id,user_id,user_name')
                    ->find();
                
                $all_money = get_help_jh_money($goods_crown['userid']);
                $data = array();
                $data['type'] = "update_trade_money";
                $data['user'] = $user;
                $data['uid'] = $user['id'];
                $data['all_money'] = $all_money;
                push_msg($data, $user['id']);
                
                $sms_template = M("sms_template")->where("  id=8")->find();
                
                $TemplateCode = $sms_template['template_id'];
                $result = send_sms_code($user['user_id'], $TemplateCode, $user['user_name']);
            }
            
            $this->success('操作成功！');
            exit();
        } else {
            $this->error('操作失败');
            exit();
        }
    }

    public function TradeTq()
    {
        $id = $this->_get('id');
        $status = $this->_get('is_tiqian');
        
        $goods_crown = M("trade")->where("  id=" . $id)->find();
        $count = M("trade")->where("  id=" . $id . '   AND  is_express!=2')->count();
        if ($count > 0) {
            $this->ajaxError('订单未完成,不允许操作！');
            exit();
        }
        $count = M("trade")->where("  id=" . $id . '   AND  is_tiqu=1')->count();
        if ($count > 0) {
            $this->ajaxError('已提取积分,不允许操作！');
            exit();
        }
        
        $User = M('trade');
        $where['id'] = $id;
        $rs = $User->where($where)->setField('is_tiqian', $status);
        $rs = $User->where($where)->setField('tiqian_time', time());
        
        $this->success('操作成功！');
        exit();
    }

    public function TradeTest()
    {
        $id = $this->_get('id');
        $status = $this->_get('is_hot');
        
        $this->ajaxError('取消的订单,不允许操作！');
        exit();
    }

    public function order_edit()
    {
        $id = $this->_get('id');
        $map = array();
        $map['id'] = $id;
        $form = M('trade');
        $rs = $form->where($map)->find();
        $map = array();
        $map['id'] = $rs['goods_id'];
        $list = M('goods')->where($map)
            ->field(' title as goods_title , price as goods_price ')
            ->select();
        
        foreach ($list as $key => $vo) {
            // $list[$key]['goods_title'] = ($vo['title'] );
            // $list[$key]['goods_price'] = ($vo['price'] );
            $list[$key]['all_real_price'] = ($vo['goods_price']);
        }
        $user = M('fck')->where('id=' . $rs['userid'])->find();
        $rs['mobile'] = $user['mobile'];
        $user_address = M('user_addr_book')->where('user_id=' . $rs['userid'])->find();
        if ($user_address != null) {
            
            $rs['area'] = $user_address['province'] . $user_address['city'] . $user_address['area'];
            $rs['address'] = $user_address['address'];
            $rs['mobile'] = $user_address['mobile'];
        }
        $rs['order_no'] = $rs['addtime'];
        $rs['accept_name'] = $user['user_id'];
        $rs['order_goods'] = $list;
        $rs['add_time_str'] = date("Y-m-d H:i:s", $rs["add_time"]);
        $rs['payment_time_str'] = date("Y-m-d H:i:s", $rs["payment_time"]);
        $rs['confirm_time_str'] = date("Y-m-d H:i:s", $rs["confirm_time"]);
        $rs['express_time_str'] = date("Y-m-d H:i:s", $rs["express_time"]);
        $rs['complete_time_str'] = date("Y-m-d H:i:s", $rs["complete_time"]);
        $this->assign('rs', $rs);
        
        $this->display();
    }

    public function order_express()
    {
        $order_id = $this->_post("order_id");
        $form = M('trade');
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['is_express'] == 1) {
            
            $this->ajaxError('订单已完成或已发货，不能重复处理！');
            
            return;
        }
        $express = $this->_post("express");
        $express_no = $this->_post("express_no");
        if ($express == '') {
            $this->ajaxError('请输入快递公司！');
            return;
        }
        if ($express_no == '') {
            if (strpos($express, '当面') !== false) {} else {
                $this->ajaxError('请输入快递编号！');
                return;
            }
        }
        $model['express'] = $express;
        $model['express_no'] = $express_no;
        $model['is_express'] = 1;
        $model['express_time'] = time();
        $ret = $form->where('id=' . $order_id)->save($model);
        
        $user = M('fck')->where('id=' . $model['userid'])->find();
        set_user_order_num($user);
        
        // 推送数量
        
        $fck_num = M('fck_num')->where('uid=' . $user['id'])->find();
        
        $data = array();
        $data['type'] = "update_order_num";
        $data['order_num'] = $fck_num;
        $data['uid'] = $user['id'];
        
        push_msg($data, $user['id']);
        
        if (! $ret) {
            $this->error('订单发货失败！');
            
            return;
        }
        $bUrl = __URL__ . '/express';
        $this->ajaxSuccess('订单发货成功！', $bUrl);
    }

    public function UpdateField()
    {
        $fck = M('trade_log');
        $id = I('post.id');
        $field = I('post.field');
        $value = I('post.value');
        
        $fck->where('id=' . $id)->setField($field, $value);
        
        $this->success('修改成功！');
    }

    public function Trade_Update()
    {
        $trade = M('trade');
        $fck = M('fck');
        
        $content = $trade->alias('t')
            ->join("xt_fck AS g ON   g.id = t.userid", 'LEFT')
            ->where('t.addtime>=unix_timestamp("2018-11-27") and t.shouquan_id=0 and g.u_level=1   ')
            ->field('T.*')
            ->select();
        
        foreach ($content as $key => $rs) {
            
            $where['id'] = $rs['userid'];
            $frs = $fck->where($where)
                ->field('id,re_path,u_level,nickname,is_agent,user_id,user_name,ssb,agent_use,limit_money,is_pay,bank_card')
                ->find();
            $vo = M('fck')->where('id in (0' . $frs['re_path'] . '0) AND u_level=2 ')
                ->order(' re_level desc ')
                ->find();
            // if($vo!=NULL){
            // if ($vo['live_gupiao'] < $price * $num ) {
            // $this->ajaxError($vo['user_id'].'代理商'.C('live_gupiao') . '不足');
            // exit();
            // }
            // }
            $shouquan_model['uid'] = $frs['id'];
            $shouquan_model['re_id'] = $vo['id'];
            $shouquan_model['add_time'] = time();
            $shouquan_model['money'] = $rs['price'];
            
            // $s1 = explode(':', $fee_rs['s1']);
            // $fee_rs['s1'] = $s1[0] / $s1[1];
            // $fee_rs['s1'] = $s1[0];
            
            $profit = 100;
            
            $shouquan_model['profit'] = $profit;
            $shouquan_model['status'] = 0;
            $ret = M('shouquan')->add($shouquan_model);
            
            $trade->where('id=' . $rs['id'])->setField('shouquan_id', $ret);
        }
        
        $this->success('修改成功！');
    }

    public function apply_trade_money()
    {
        $user_id = $this->_post("user_id");
        $order_id = $this->_post("trade_id");
        $form = M('trade_log');
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['money_uid'] > 0 && $model['money_status'] < 2) {
            
            $this->ajaxError('订单已申请资助,不能重复处理！');
            
            return;
        }
        $money_user_id = $this->_post("money_user_id");
        $need_money = $this->_post("need_money");
        $lixi = $this->_post("lixi");
        
        $count = M('trade')->where('userid=' . $user_id . ' AND is_cancel=0 ')->count();
        if ($count == 1) {
            $this->ajaxError('第一单不允许资助！');
            return;
        }
        $count = M('trade')->where('userid=' . $user_id . ' AND is_cancel=0 and is_yd=1 ')->count();
        if ($count == 1) {
            $fee = M('fee');
            $fee_rs = $fee->field('s12')->find(1);
            $this->ajaxError('第二单的' . $fee_rs['s12'] . '%不允许资助！');
            return;
        }
        
        if ($money_user_id == '') {
            $this->ajaxError('请输入资助人编号！');
            return;
        }
        if ($need_money == '') {
            
            $this->ajaxError('请输入申请金额！');
            return;
        }
        
        if ($need_money > $model['price']) {
            
            $this->ajaxError('申请金额不能大于订单金额' . $model['price'] . '！');
            return;
        }
        
        if ($lixi == '') {
            
            $this->ajaxError('请输入本人所得利息！');
            return;
        }
        if ($lixi > 100) {
            
            $this->ajaxError('本人所得利息不能大于100！');
            return;
        }
        $fck = M('fck')->where('user_id="' . $money_user_id . '"')
            ->field('id,user_id')
            ->find();
        if ($fck == NULL) {
            
            $this->ajaxError('资助人编号不存在！');
            return;
        }
        
        if ($fck != NULL) {
            if ($fck['id'] == $user_id) {
                $this->ajaxError('资助人不允许为本人！');
                return;
            }
        }
        
        $model['money_user_id'] = $money_user_id;
        $model['money_uid'] = $fck['id'];
        $model['need_money'] = $need_money;
        $model['lixi'] = $lixi;
        $model['is_money'] = 0;
        $model['money_status'] = 1;
        $model['money_add_time'] = time();
        $ret = $form->where('id=' . $order_id)->save($model);
        
        if (! $ret) {
            $this->error('订单操作失败！');
            
            return;
        }
        
        set_user_order_num($fck);
        set_user_help_order_num($fck);
        // 推送数量
        
        $fck_num = M('fck_num')->where('uid=' . $fck['id'])->find();
        
        $trade_ids = '0';
        
        if ($fck_num['need_order_num'] > 0) {
            $trade_log = M('trade_log')->where('money_uid=' . $fck['id'] . ' AND money_status=1 ')
                ->field('trade_id')
                ->select();
            foreach ($trade_log as $k => $item2) {
                $trade_ids = $trade_ids . ',' . $item2['trade_id'];
            }
        }
        $fck_num['trade_ids'] = $trade_ids;
        
        $data = array();
        $data['type'] = "update_order_num";
        $data['order_num'] = $fck_num;
        $data['uid'] = $fck['id'];
        $data['is_push'] = 1;
        $data['push_msg'] = '您有资助订单,请及时确认';
        
        push_msg($data, $fck['id']);
        
        $bUrl = __URL__ . '/express';
        $this->ajaxSuccess('申请成功！', $bUrl);
    }

    public function confirm_trade_money()
    {
        $status = $this->_post("status");
        $user_id = $this->_post("user_id");
        $order_id = $this->_post("trade_id");
        $form = M('trade_log');
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['status'] == 0) {
            
            $this->ajaxError('资助已同意！');
            
            return;
        }
        if ($model['status'] == 2) {
            $this->ajaxError('资助已拒绝！');
            
            return;
        }
        
        $model['money_status'] = $status;
        
        $ret = $form->where('id=' . $order_id)->save($model);
        
        if (! $ret) {
            $this->error('订单操作失败！');
            
            return;
        }
        
        $fck = M('fck')->where('id=' . $user_id . '')
            ->field('id,user_id')
            ->find();
        
        set_user_order_num($fck);
        set_user_help_order_num($fck);
        // 推送数量
        
        $fck_num = M('fck_num')->where('uid=' . $fck['id'])->find();
        
        $data = array();
        $data['type'] = "update_order_num";
        $data['order_num'] = $fck_num;
        $data['uid'] = $fck['id'];
        $data['is_push'] = 1;
        $data['push_msg'] = '您需要资助订单,请及时打款';
        
        push_msg($data, $fck['id']);
        
        $bUrl = __URL__ . '/express';
        $this->ajaxSuccess('提交成功！', $bUrl);
    }
}
?>
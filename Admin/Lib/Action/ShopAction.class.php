<?php

class ShopAction extends CommonAction
{

    function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        
        // $this->_inject_check(0); // 调用过滤函数
        
        $this->_checkUser();
        
        header("Content-Type:text/html; charset=utf-8");
    }

    public function order_apply_daifu()
    {
        $order_id = $this->_post("order_id");
        $user_id = $this->_post("user_id");
        $daifu_user_name = $this->_post("daifu_user_name");
        $order = M('orders')->where('id="' . $order_id . '"')->find();
        
        $daifu_user = M('fck')->where('user_id="' . $daifu_user_name . '"')->find();
        if ($daifu_user == NULL) {
            $this->error('代付用户名不存在,请重新输入！');
            return;
        }
        if ($daifu_user['id'] == $order['user_id']) {
            $this->error('代付用户不可以是本人！');
            return;
        }
        
        $form = M('orders');
        $model = $form->where('id=' . $order_id)->find();
        
        $ret = $form->where('id=' . $order_id)->setField('money_user_id', $daifu_user['id']);
        $ret = $form->where('id=' . $order_id)->setField('is_daifu', 1);
        
        if (! $ret) {
            $this->error('订单发货失败！');
            
            return;
        }
        
        $bUrl = __URL__ . '/order_list';
        $this->ajaxSuccess('设置成功,请等待对方代付', $bUrl);
    }

    public function order_upload_img()
    {
        $order_id = $this->_post("order_id");
        $user_id = $this->_post("user_id");
        $img_url = $this->_post("img_url");
        $form = M('goods_crowd_users');
        $model = $form->where('order_id=' . $order_id . ' AND user_id=' . $user_id)->find();
        
        $ret = $form->where('order_id=' . $order_id . ' AND user_id=' . $user_id)->setField('tousu_img_url', $img_url);
        
        if (! $ret) {
            $this->error('订单发货失败！');
            
            return;
        }
        
        $bUrl = __URL__ . '/order_list';
        $this->ajaxSuccess('上传成功,请等待审核', $bUrl);
    }

    public function order_tousu()
    {
        $order_id = $this->_post("order_id");
        $user_id = $this->_post("user_id");
        $form = M('goods_crowd_users');
        $model = $form->where('order_id=' . $order_id . ' AND user_id=' . $user_id)->find();
        
        if ($model['status'] == 2) {
            
            $this->error('订单已经投诉，不能重复处理！');
            
            return;
        }
        
        $model['status'] = 2;
        $model['tousu_time'] = time();
        $ret = $form->where('order_id=' . $order_id . ' AND user_id=' . $user_id)->setField('status', 2);
        $ret = $form->where('order_id=' . $order_id . ' AND user_id=' . $user_id)->setField('is_tousu', 1);
        $ret = $form->where('order_id=' . $order_id . ' AND user_id=' . $user_id)->setField('tousu_time', time());
        
        if (! $ret) {
            $this->error('订单发货失败！');
            
            return;
        }
        
        $bUrl = __URL__ . '/order_list';
        $this->ajaxSuccess('投诉成功,请等待审核', $bUrl);
    }

    public function order_complete()
    {
        $order_id = $this->_post("order_id");
        $form = M('orders');
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['status'] > 2) {
            
            $this->error('订单已经完成，不能重复处理！');
            
            return;
        }
        
        $model['status'] = 3;
        $model['complete_time'] = time();
        $ret = $form->where('id=' . $order_id)->save($model);
        
        if (! $ret) {
            $this->error('订单发货失败！');
            
            return;
        }
        
        $bUrl = __URL__ . '/order_list';
        $this->ajaxSuccess('订单收货成功！', $bUrl);
    }

    public function order_express()
    {
        $order_id = $this->_post("order_id");
        $form = M('orders');
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['status'] > 2 || $model['express_status'] == 2) {
            
            $this->error('订单已完成或已发货，不能重复处理！');
            
            return;
        }
        $express = $this->_post("express");
        $express_no = $this->_post("express_no");
        if ($express == '') {
            $this->error('请输入快递公司！');
            return;
        }
        if ($express_no == '') {
            $this->error('请输入快递编号！');
            return;
        }
        $model['express'] = $express;
        $model['express_no'] = $express_no;
        $model['express_status'] = 2;
        $model['express_time'] = time();
        $ret = $form->where('id=' . $order_id)->save($model);
        
        if (! $ret) {
            $this->error('订单发货失败！');
            
            return;
        }
        $bUrl = __URL__ . '/order_list';
        $this->ajaxSuccess('订单发货成功！', $bUrl);
    }

    public function order_payment()
    {
        $order_id = $this->_post("order_id");
        $form = M('orders');
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['status'] > 1 || $model['payment_status'] == 1) {
            
            $this->error('订单已付款，不能重复处理！');
            
            return;
        }
        $model['status'] = 6;
        $model['payment_status'] = 2;
        $model['payment_time'] = time();
        $ret = $form->where('id=' . $order_id)->save($model);
        
        if (! $ret) {
            $this->error('订单付款失败！');
            
            return;
        }
        $bUrl = __URL__ . '/order_list';
        $this->ajaxSuccess('订单付款成功！', $bUrl);
    }

    // 确认收款
    public function order_money()
    {
        $order_id = $this->_post("order_id");
        $user_id = $this->_post("user_id");
        // $order_id=34;
        // $user_id=1;
        
        $goods_crowd_users = M('goods_crowd_users');
        $form = M('orders');
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['confirm_status'] == 1) {
            
            $this->error('订单已收款，不能重复处理！');
            
            return;
        }
        
        $ret = $goods_crowd_users->where('order_id=' . $order_id . ' AND user_id=' . $user_id)->setField('status', 0);
        $ret = $goods_crowd_users->where('order_id=' . $order_id . ' AND user_id=' . $user_id)->setField('money_status', 1);
        $ret = $goods_crowd_users->where('order_id=' . $order_id . ' AND user_id=' . $user_id)->setField('money_time', time());
        $data = $goods_crowd_users->where('order_id=' . $order_id . ' AND user_id=' . $user_id . ' AND money_status=1')->sum('all_crowd_price');
        $fck_rs = M('fck')->where('id=' . $user_id)->find();
        user_award($fck_rs, $data);
        $data = $goods_crowd_users->where('order_id=' . $order_id . '  AND money_status=0')->find();
        if ($data == null) {
            $model['status'] = 2;
            $model['confirm_status'] = 1;
            $model['express_status'] = 1;
            $model['confirm_time'] = time();
            $ret = $form->where('id=' . $order_id)->save($model);
        }
        if (! $ret) {
            $this->error('订单付款失败！');
            
            return;
        }
        $bUrl = __URL__ . '/order_list';
        $this->ajaxSuccess('订单收款成功！', $bUrl);
    }

    public function spec_edit()
    {
        if ($_POST) {
            $model = ARRAY();
            $model['channel_id'] = 0;
            $model['parent_id'] = 0;
            $model['title'] = $this->_post('title');
            $model['remark'] = htmlspecialchars_decode($this->_post('content'));
            $model['sort_id'] = $this->_post('sort_id');
            
            // region 保存规格选项
            $itemIdArr = $_POST["item_id"];
            $itemTitleArr = $_POST["item_title"];
            $itemImgUrlArr = $_POST["item_imgurl"];
            $itemSortIdArr = $_POST["item_sortid"];
            $ls = ARRAY();
            if ($itemIdArr != null && $itemTitleArr != null && $itemSortIdArr != null) {
                if (count($itemIdArr) == count($itemTitleArr) && (count($itemTitleArr) == count($itemSortIdArr))) {
                    
                    for ($i = 0; $i < count($itemIdArr); $i ++) {
                        $modelt = ARRAY();
                        $modelt['id'] = $itemIdArr[$i];
                        $modelt['title'] = $itemTitleArr[$i];
                        $modelt['sort_id'] = $itemSortIdArr[$i];
                        $modelt['addtime'] = time();
                        
                        $ls[] = $modelt;
                    }
                }
            }
            // endregion
            $id = $this->_post('spec_id');
            if ($id > 0) {
                
                $model['id'] = $id;
                $rs = M('article_spec')->where('id=' . $id)->save($model);
            } else {
                
                $model['addtime'] = time();
                
                $rs = M('article_spec')->add($model);
                $id = $rs;
            }
            
            M('article_spec')->where('parent_id=' . $id)->delete();
            
            foreach ($ls as $i => $goods) {
                $model = array();
                $model['channel_id'] = 0;
                $model['title'] = $goods['title'];
                $model['parent_id'] = $id;
                $model['sort_id'] = $goods['sort_id'];
                
                M('article_spec')->add($model);
            }
            $bUrl = __URL__ . '/spec_list';
            $this->ajaxSuccess('提交成功！', $bUrl);
            
            RETURN;
        } else {
            $id = $_GET['id'];
            if ($id > 0) {
                $form = M('article_spec');
                $map['id'] = $id;
                $model = $form->where($map)->find();
                
                $spec_list = $form->where('parent_id=' . $id)->select();
                $model['spec'] = $spec_list;
                
                $this->assign('vo', $model);
            }
            
            $this->display();
        }
    }

    public function spec_list()
    {
        $is_mobile = $_POST['is_mobile'];
        
        $map = array();
        $map['parent_id'] = 0;
        if ($is_mobile == 1) {}
        
        $form = M('article_spec');
        
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->where($map)->count(); // 总页数
        
        $Page = new Page($count, 1000);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $form->where($map)
            ->field($field)
            ->order('  sort_id asc,id desc')
            ->page($p . ',1000')
            ->select();
        foreach ($list as $i => $goods) {
            $spec_list = $form->where('parent_id=' . $goods['id'])->select();
            $list[$i]['spec'] = $spec_list;
        }
        
        if ($is_mobile == 1) {
            foreach ($list as $i => $goods) {
                $list[$i]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img']);
            }
        }
        $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            
            $this->display();
        }
    }

    public function dialog_category_spec()
    {
        $map = array();
        $map['parent_id'] = 0;
        
        $form = M('article_spec');
        
        $list = $form->where($map)->select();
        
        $this->assign('list', $list);
        
        $this->display('dialog_category_spec');
    }

    public function dialog_article_spec()
    {
        $hide_spec_id = $_GET['hide_spec_id'];
        $map = array();
        $map['id'] = array(
            'in',
            $hide_spec_id
        );
        
        $form = M('article_spec');
        
        $list = $form->where($map)->select();
        foreach ($list as $i => $goods) {
            $spec_list = $form->where('parent_id=' . $goods['id'])->select();
            $list[$i]['spec'] = $spec_list;
        }
        
        $this->assign('list', $list);
        
        $this->display('dialog_article_spec');
    }

    public function dialog_spec()
    {
        $PTid = $this->_get('id');
        $this->assign('id', $PTid);
        
        $this->display('dialog_spec');
    }

    public function spec_del()
    {
        $form = M('article_spec');
        
        $PTid = $this->_get('id');
        $where['id'] = $PTid;
        $rs = $form->where($where)->find();
        
        $form->where('id=' . $rs['id'])->delete();
        
        $this->ajaxSuccess('删除成功');
    }

    public function category_del()
    {
        $form = M('article_category');
        
        $PTid = $this->_get('id');
        $where['id'] = $PTid;
        $rs = $form->where($where)->find();
        $list = $form->where('id>0')->select();
        $channel_id = 0;
        $parent_id = $PTid;
        $newData = array();
        $newData[] = $rs;
        GetChilds($list, $newData, $parent_id, $channel_id);
        foreach ($newData as $i => $goods) {
            $form->where('id=' . $goods['id'])->delete();
        }
        
        $this->ajaxSuccess('删除成功');
    }

    public function category_list()
    {
        $form = M('article_category');
        $shop_user_id = 0;
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        $is_mobile = $_POST['is_mobile'];
        IF ($is_mobile == 1) {
            $user_id = $_POST['user_id'];
            if ($user_id == NULL) {
                $user_id = 1;
            }
        }
        $top = $_POST['top'];
        // $top=10;
        $is_hot = $_POST['is_hot'];
        // $is_hot=1;
        // $is_mobile=1;
        $parent_id = 0;
        $channel_id = 1;
        $map = array();
        $map['channel_id'] = $channel_id;
        $model = M('fck')->field('id,shop_id,get_level')
            ->where('id=' . $user_id)
            ->find();
        IF ($user_id > 10) {
            
            $str = '';
            if ($model['get_level'] != 3) {
                $shop_user_id = $model['shop_id'];
            } else {
                $shop_user_id = $model['id'];
            }
        } else {
            
            $shop_user_id = $model['id'];
        }
        if ($is_mobile == 1) {
            $parent_id = $_POST['parent_id'];
            // $parent_id=40;
            $map['parent_id'] = $parent_id;
            
            $where['id'] = $parent_id;
            
            $rs = $form->where($where)->find();
            
            $user_category = M('user_category')->where('category_id=' . $parent_id . ' AND user_id=' . $shop_user_id)->find();
            
            $rs['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $user_category['img_url']);
            $rs['img1'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $user_category['img1']);
        }
        
        $field = 't.*,g.img_url AS user_img_url,g.img1 AS user_img1,g.category_title as  category_title,g.id as user_category_id,g.sort_id as user_category_sort_id';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_user_category AS g ON   g.category_id = t.id and (g.user_id=" . $shop_user_id . '  )', 'LEFT')
            ->where('g.is_lock =0 and  t.channel_id=' . $channel_id)
            ->count(); // 总页数
        
        $Page = new Page($count, 1000);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $top1 = 1000;
        if ($top > 0) {
            $top1 = $top;
        }
        
        $list = $form->alias('t')
            ->join("xt_user_category AS g ON g.is_lock =0 and    g.category_id = t.id and g.user_id=" . $shop_user_id, 'LEFT')
            ->where('g.is_lock =0 and  t.channel_id=' . $channel_id . ' AND T.parent_id=' . $parent_id)
            ->field($field)
            ->order('  g.sort_id asc,t.id desc')
            ->page($p . ',' . $top1)
            ->select();
        if ($is_hot == 1) {
            $list = $form->alias('t')
                ->join("xt_user_category AS g ON g.is_lock =0 and    g.category_id = t.id and g.user_id=" . $shop_user_id, 'LEFT')
                ->where('g.is_lock =0 and  t.channel_id=' . $channel_id . ' AND g.is_hot=' . $is_hot)
                ->field($field)
                ->order('  g.sort_id asc,t.id desc')
                ->page($p . ',' . $top1)
                ->select();
            $hotlist = $list;
        }
        // $parent_id = 0;
        $newData = array();
        if ($is_hot == 0) {
            GetChilds($list, $newData, $parent_id, $channel_id, $shop_user_id);
        }
        if ($is_mobile == 1) {
            foreach ($list as $i => $goods) {
                $model = M('fck')->field('bank_card,name,user_name,user_id,bank_name,wx_img,zfb_img')
                    ->where('id=' . $goods['user_id'])
                    ->find();
                if ($model == NULL) {
                    $model['user_id'] = C('sys_category');
                }
                
                $list[$i]['user_name'] = $model['user_id'];
                $newData1 = array();
                GetChilds($list, $newData1, $goods['id'], $channel_id, $shop_user_id);
                $list[$i]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img_url']);
                $list[$i]['img1'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img1']);
                $list[$i]['user_img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['user_img_url']);
                $list[$i]['user_img1'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['user_img1']);
                $list[$i]['child'] = $newData1;
            }
        }
        foreach ($hotlist as $ii => $category) {
            $goods_list = M('goods')->where('user_id=' . $shop_user_id . ' AND category_id IN (0' . $category['class_list'] . '0)  AND  IS_HOT=1 ')
                ->order('   id desc ')
                ->page($p . ',' . 10)
                ->select();
            
            foreach ($goods_list as $iii => $goodss) {
                $goods_list[$iii]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goodss['img']);
            }
            
            $hotlist[$ii]['goods'] = $goods_list;
            $hotlist[$ii]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $category['img_url']);
            $hotlist[$ii]['img1'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $category['img1']);
            $hotlist[$ii]['user_img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $category['user_img_url']);
            $hotlist[$ii]['user_img1'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $category['user_img1']);
        }
        $this->assign('list', $newData); // 数据输出到模板
        $rs['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs['img_url']);
        $rs['img1'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs['img1']);
        $rs['child'] = $newData;
        
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = array_reverse($list);
            $data['child'] = $newData;
            // foreach ($hotlist as $i => $goods) {
            // GetChilds($hotlist, $newData, $goods['id'], $channel_id);
            // $hotlist[$i]['child'] = $newData;
            // }
            
            $data['rs'] = $rs;
            $data['hotlist'] = array_reverse($hotlist);
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            
            $this->display();
        }
    }

    public function category_edit()
    {
        $channel_id = 1;
        $is_mobile = $_POST['is_mobile'];
        $action = $this->_get('action');
        $this->assign('action', $action);
        
        // $this->_Admin_checkUser(); // 后台权限检测
        if (! IS_POST) {
            
            $user_cate_id = $this->_get('user_cate_id');
            $this->assign('user_cate_id', $user_cate_id);
            $id = $this->_get('id');
            $action = $this->_get('action');
            
            $rs = M('user_category')->where(array(
                'id' => $id
            ))
                ->field('*')
                ->find();
            $rs['title'] = $rs['category_title'];
            
            $category = M('article_category')->where(array(
                'id' => $rs['category_id']
            ))
                ->field('*')
                ->find();
            $rs['parent_id'] = $category['parent_id'];
            $rs['class_layer'] = $category['class_layer'];
            $user_id = $_SESSION[C('USER_AUTH_KEY')];
            IF ($user_id > 10) {
                $model = M('fck')->field('id,shop_id,get_level')
                    ->where('id=' . $user_id)
                    ->find();
                $str = '';
                if ($model['get_level'] != 3) {
                    $map['user_id'] = $model['shop_id'];
                } else {
                    $map['user_id'] = $model['id'];
                }
            }
            $map['channel_id'] = $channel_id;
            $dt = M('article_category')->where($map)->select();
            
            $parent_id = 0;
            $newData = array();
            GetChilds($dt, $newData, $parent_id, $channel_id, $user_id);
            $category = array();
            $item = array();
            $item['id'] = 0;
            $item['title'] = '无父级分类';
            $category[] = $item;
            foreach ($newData as $dr) {
                $Id = $dr["id"];
                $ClassLayer = $dr["class_layer"];
                $Title = $dr["category_title"];
                $dr["title"] = $Title;
                if ($ClassLayer == 1) {
                    $category[] = $dr;
                } else {
                    $Title = "├ " . $Title;
                    $Title = StringOfChar($ClassLayer - 1, "　") . $Title;
                    $dr["title"] = $Title;
                    $category[] = $dr;
                }
            }
            $a = "Uploads";
            if (! strstr($rs['img_url'], $a)) {
                
                $rs['img_url'] = '/Public/images/等待上传.png';
            }
            if (! strstr($rs['img1'], $a)) {
                
                $rs['img1'] = '/Public/images/等待上传.png';
            }
            $this->assign('category', $category);
            if ($rs) {
                $this->assign('vo', $rs);
                unset($id, $rs);
            }
            
            $dt = array();
            $item = array();
            $item['id'] = 0;
            $item['user_id'] = '默认';
            $dt[] = $item;
            $newData = M('fck')->where(array(
                'get_level' => 3
            ))->select();
            foreach ($newData as $dr) {
                
                $dt[] = $dr;
            }
            
            $this->assign('userlist', $dt);
            
            $this->display('category_edit');
        } else {
            
            $user_cate_id = $_POST['user_cate_id'];
            $form = M('article_category');
            $data['title'] = $this->_post('title');
            $data['category_title'] = $this->_post('title');
            $data['parent_id'] = $this->_post('ddlParentId');
            
            // $data['sort_id'] = $this->_post('sort_id');
            // $data['img'] = get_img_url($_POST['img']);
            $data['img_url'] = get_img_url($this->_post('img'));
            $data['img1'] = get_img_url($this->_post('img1'));
            $data['content'] = $_POST['cate_detail'];
            $cate_id = 0;
            if ($_POST['action'] == 'edit') {
                $data['id'] = $_POST['id'];
                $cate_id = $data['id'];
            }
            
            $data['is_lock'] = 0;
            $data['channel_id'] = $channel_id;
            $data['user_id'] = $_POST['ddlUserId'];
            $data['is_main'] = $_POST['is_main'];
            
            $user_id = $_SESSION[C('USER_AUTH_KEY')];
            if ($user_id > 10) {
                $data['user_id'] = $user_id;
            } else {
                
                $data['user_id'] = $user_id;
            }
            $user_category = M('user_category')->where(array(
                'id' => $user_cate_id
            ))
                ->field('*')
                ->find();
            $Node = M('article_category')->where("  id=" . $user_category['category_id'])->find();
            IF ($user_category['is_sys'] == 1 && $data['parent_id'] != $Node['parent_id']) {
                $this->error('无法编辑类别！');
                exit();
            }
            
            ;
            $data['site_id'] = 1;
            $cate_id = $data['id'];
            if ($data['id'] > 0) {
                $ContainNode = M('article_category')->where("class_list like '%," . $data['id'] . ",%' and id=" . $data['parent_id'])->find();
                if ($ContainNode != NULL) {
                    // 查找旧数据
                    $oldModel = M('article_category')->where(" id=" . $data['id'])->find();
                    // 查找旧父节点数据
                    $class_list = "," . $data['parent_id'] . ",";
                    $class_layer = 1;
                    if ($oldModel['parent_id'] > 0) {
                        $oldParentModel = M('article_category')->where(" id=" . $oldModel['parent_id'])->find();
                        $class_list = $oldParentModel['class_list'] . $data['parent_id'] . ",";
                        $class_layer = $oldParentModel['class_layer'] + 1;
                    }
                    // $data['parent_id'] = $oldModel['parent_id'];
                    $data['class_list'] = $class_list;
                    $data['class_layer'] = $class_layer;
                    $rs = M('article_category')->where('id=' . $data['parent_id'] . ' ')->setField('parent_id', $oldModel['parent_id']);
                    $rs = M('article_category')->where('id=' . $data['parent_id'] . ' ')->setField('class_list', $class_list);
                    $rs = M('article_category')->where('id=' . $data['parent_id'] . ' ')->setField('class_layer', $class_layer);
                    UpdateChilds($data['parent_id']); // 带事务
                }
                if ($data['parent_id'] > 0) {
                    $model2 = M('article_category')->where('id=' . $data['parent_id'])->find();
                    $data['class_list'] = $model2['class_list'] . $data['id'] . ",";
                    $data['class_layer'] = $model2['class_layer'] + 1;
                } else {
                    $data['class_list'] = "," . $data['id'] . ",";
                    
                    $data['class_layer'] = 1;
                }
                
                $rs = M('article_category')->where('id=' . $data['id'])->save($data);
                UpdateChilds($data['id']);
            } else {
                
                $data['addtime'] = time();
                $rs = M('article_category')->add($data);
                $cate_id = $rs;
                if ($data['parent_id'] > 0) {
                    $model2 = M('article_category')->where('id=' . $data['parent_id'])->find();
                    $data['class_list'] = $model2['class_list'] . $rs . ",";
                    $data['class_layer'] = $model2['class_layer'] + 1;
                } else {
                    $data['class_list'] = "," . $rs . ",";
                    
                    $data['class_layer'] = 1;
                }
                // $data['id'] = $cate_id;
                $rs = M('article_category')->where('id=' . $rs)->save($data);
            }
            
            $user_category = M('user_category')->where(array(
                'id' => $user_cate_id
            ))
                ->field('*')
                ->find();
            
            $data = array();
            
            $data['category_title'] = $this->_post('title');
            
            $data['img_url'] = get_img_url($this->_post('img'));
            $data['img1'] = get_img_url($this->_post('img1'));
            $data['content'] = $_POST['cate_detail'];
            $data['is_lock'] = 0;
            $data['user_id'] = $user_id;
            $data['add_time'] = time();
            
            $user_category = M('user_category')->where(array(
                'user_id' => $user_id,
                'category_id' => $cate_id
            ))->find();
            $user_category_id = 0;
            if ($user_category != NULL) {
                
                $rs = M('user_category')->where('id=' . $user_cate_id)->save($data);
                $user_category_id = $user_cate_id;
            } else {
                $data['category_id'] = $cate_id;
                $rs = M('user_category')->add($data);
                $user_category_id = $rs;
            }
            
            // 图片上传
            IF ($user_id == 1) {
                M('article_category')->where('id=' . $data['id'])->setField('img_url', '');
                M('category_albums')->where('category_id=' . $cate_id . ' AND user_category_id=0 and channel_id=0')->delete();
            }
            $goodsAblumsJsonStr = $this->_post('img');
            M('user_category')->where('id=' . $user_category_id)->setField('img_url', '');
            M('category_albums')->where('category_id=' . $cate_id . ' AND user_category_id=' . $user_cate_id . ' and channel_id=0')->delete();
            for ($i = 0; $i < count($goodsAblumsJsonStr); $i ++) {
                $goods_albums = ARRAY();
                $goods_albums['category_id'] = $cate_id;
                $goods_albums['thumb_path'] = get_img_url($goodsAblumsJsonStr[$i]);
                $goods_albums['original_path'] = get_img_url($goodsAblumsJsonStr[$i]);
                $goods_albums['add_time'] = time();
                $goods_albums['user_category_id'] = $user_cate_id;
                $goods_albums['channel_id'] = 0;
                M('category_albums')->add($goods_albums);
                if ($user_id == 1) {
                    $goods_albums = ARRAY();
                    $goods_albums['category_id'] = $cate_id;
                    $goods_albums['thumb_path'] = get_img_url($goodsAblumsJsonStr[$i]);
                    $goods_albums['original_path'] = get_img_url($goodsAblumsJsonStr[$i]);
                    $goods_albums['add_time'] = time();
                    $goods_albums['user_category_id'] = 0;
                    $goods_albums['channel_id'] = 0;
                    M('category_albums')->add($goods_albums);
                    if ($i == 0) {
                        M('article_category')->where('id=' . $cate_id)->setField('img_url', get_img_url($goodsAblumsJsonStr[$i]));
                    }
                }
                if ($i == 0) {
                    M('user_category')->where('id=' . $user_category_id)->setField('img_url', get_img_url($goodsAblumsJsonStr[$i]));
                }
            }
            
            $url = __URL__ . '/category_list';
            $this->ajaxSuccess('编辑成功！', $url);
            
            $url = __URL__ . '/category_list';
            $this->ajaxSuccess('编辑成功！', $url);
        }
    }

    function GetChilds($oldData, &$newData, $parent_id, $channel_id)
    {
        $str = '';
        $form = M('article_category');
        
        $dr = $form->alias('t')
            ->where("    t.parent_id=" . $parent_id . ' AND t.channel_id=' . $channel_id)
            ->field('t.*   ')
            ->order('t.sort_id asc ')
            ->select();
        
        foreach ($dr as $i => $goods) {
            $goods['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods['img_url']);
            $goods['img1'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods['img1']);
            
            $form->where("id=" . $goods['id'])->setInc('click', 1);
            
            $row = $goods; // 创建新行
                           // 循环查找列数量赋值
            $newData[] = $row;
            // 调用自身迭代
            $this->GetChilds($oldData, $newData, $goods["id"], $channel_id);
        }
    }

    // 会员表
    public function orderDaoChu_MM()
    {
        // 导出excel
        set_time_limit(0);
        
        header("Content-Type:   application/vnd.ms-excel");
        header("Content-Disposition:   attachment;   filename=订单.xls");
        header("Pragma:   no-cache");
        header("Content-Type:text/html; charset=utf-8");
        header("Expires:   0");
        
        $fck = M('order'); // 奖金表
        
        $map = array();
        $map['id'] = array(
            'gt',
            0
        );
        $field = '*';
        $list = $fck->where($map)
            ->field($field)
            ->order('addtime asc')
            ->select();
        
        $title = "订单表 导出时间:" . date("Y-m-d   H:i:s");
        
        echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
        // 输出标题
        echo '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">' . $title . '</td></tr>';
        // 输出字段名
        echo '<tr  align=center>';
        echo "<td>序号</td>";
        echo "<td>订单号</td>";
        echo "<td>商品名称</td>";
        echo "<td>价格</td>";
        echo "<td>下单时间</td>";
        echo "<td>状态</td>";
        echo "<td>联系地址</td>";
        echo "<td>购买人</td>";
        echo "<td>姓名</td>";
        echo "<td>手机号码</td>";
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
            echo '<td>' . chr(28) . $num . '</td>';
            
            echo "<td>" . $row['order_no'] . "</td>";
            echo "<td>" . $row['goodstitle'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . date("Y-m-d H:i:s", $row['addtime']) . "</td>";
            
            if ($row['status'] == 1) {
                $status_str = '未发货';
            } else if ($row['status'] == 2) {
                $status_str = '已发货';
            } else if ($row['status'] == 3) {
                
                $status_str = '已签收';
            }
            
            echo "<td>" . $status_str . "</td>";
            
            $rs = M('fck')->where(array(
                'id' => $row['uid']
            ))
                ->field('*')
                ->find();
            
            echo "<td>" . $rs['user_address'] . "</td>";
            echo "<td>" . $rs['user_id'] . "</td>";
            echo "<td>" . $rs['name'] . "</td>";
            echo "<td>" . $rs['user_tel'] . "</td>";
            echo '</tr>';
        }
        echo '</table>';
    }

    // 前台新闻
    public function order_edit()
    {
        $id = $this->_get('id');
        $map = array();
        $map['id'] = $id;
        $form = M('orders');
        $rs = order_info($id);
        $this->assign('rs', $rs);
        
        $this->display();
    }

    public function order_list()
    {
        $type = $this->_get('type');
        $this->assign('type', $type);
        $map = array();
        $map['t.order_update'] = $type;
        $map['_string'] = ' T.order_no is not null  AND T.order_no != ""';
        $form = M('user_terminal');
        $field = 'G.order_no,count(t.id) as count,H.goods_price,H.quantity,g.id,g.id as order_id,k.user_name,g.add_time';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_orders AS g ON   g.order_no = t.order_no ", 'LEFT')
            ->join("xt_order_goods AS h ON   H.order_id =g.id ", 'LEFT')
            ->join("xt_fck AS k ON  k.id = g.user_id ", 'LEFT')
            ->group('T.order_no')
            ->where($map)
            ->count(); // 总页数
        
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $form->alias('t')
            ->join("xt_orders AS g ON   g.order_no = t.order_no ", 'LEFT')
            ->join("xt_order_goods AS h ON   H.order_id =g.id ", 'LEFT')
            ->join("xt_fck AS k ON  k.id = g.user_id ", 'LEFT')
            ->where($map)
            ->field($field)
            ->group('T.order_no')
            ->order('t.add_time desc, T.status asc,T.id desc')
            ->page($p . ',10')
            ->select();
        
        foreach ($list as $key => $vo) {
            $list[$key]['order_status'] = get_order_status($vo['order_id']);
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $vo["add_time"]);
            $shop = M('seller')->where('id=' . $vo['goods_shop_id'] . '  ')->find();
            
            $list[$key]['shop_name'] = $shop['title'];
            $list[$key]['order_amount'] = $vo['goods_price'] * $vo['quantity'];
        }
        
        $this->assign('list', $list); // 数据输出到模板
        $this->display();
    }

    public function order_list1()
    {
        $map = array();
        
        $form = M('huikui');
        $field = 't.*,G.user_name';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
            ->where($map)
            ->count(); // 总页数
        
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
            ->where($map)
            ->field($field)
            ->order('t.add_time desc, t.is_pay desc,t.id desc')
            ->page($p . ',10')
            ->select();
        
        foreach ($list as $key => $vo) {
            $list[$key]['order_status'] = '未支付';
            if ($vo['is_pay'] == 1) {
                
                $list[$key]['order_status'] = '已支付';
            }
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $vo["add_time"]);
        }
        
        $this->assign('list', $list); // 数据输出到模板
        $this->display();
    }

    public function order_list2()
    {
        $map = array();
        $map['status'] = 2;
        $form = M('goods_crowd_users');
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
            ->order('add_time desc, status asc,id desc')
            ->page($p . ',10')
            ->select();
        
        foreach ($list as $key => $vo) {
            $list[$key]['order_status'] = '投诉中';
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $vo["tousu_time"]);
            $order = M('orders')->where('id=' . $vo['order_id'])->find();
            $order_goods = M('order_goods')->where('order_id=' . $vo['order_id'])->select();
            $list[$key]['order_no'] = $order['order_no'];
            $list[$key]['order_amount'] = $vo['all_crowd_price'];
        }
        
        $this->assign('list', $list); // 数据输出到模板
        $this->display();
    }

    public function menberOpenShop()
    {
        $data = $_POST;
        $ajax = false;
        $is_mobile = $_POST['is_mobile'];
        ;
        if ($is_mobile == 1) {
            $ajax = true;
        }
        
        $fck = D('Fck');
        $seller = M('seller');
        $fee = M('fee');
        $gouwu = M('gouwu');
        $shouru = M('shouru');
        $fenhong = M('fenhong');
        
        // 被开通会员参数
        $where = array();
        $where['id'] = $data['id'];
        $field = '*';
        
        $fee_rs = $fee->field('s3,s1,str6,s9,buy_point,agent_use,cjbs,give_ssb,ssb_money')->find();
        $s1 = explode("|", $fee_rs['s1']);
        $s3 = explode("|", $fee_rs['s3']);
        $str6 = $fee_rs['str6'] / 100;
        
        $result = true;
        if ($result) {
            if ($reg_money == 0) {
                $kt_cont = "审核会员";
            }
            
            $fck = D('Fck');
            // $fck->where(array(
            // 'id' => $rs['id']
            // ))->setDec('agent_kt', $money_a);
            // $fck->addencAdd($rs['id'], $rs['user_id'], - $money_a, 19, 0, 0, 0, $kt_cont . '-'.C('agent_kt')); // 激活积分扣除历史记录
            // $fck->addencAdd($rs['id'], $vo['user_id'], -$money_b, 19,0,0,0,$kt_cont.'-注册积分');//注册积分扣除历史记录
            
            $status = $data['auth_status'];
            $data['is_pay'] = 1;
            if ($status == 0) {
                 
                
                $data['get_level'] = 3;
                $result = $seller->where('id=' . $data['id'])->setField('status', 0);
                $result = $seller->where('id=' . $data['id'])->setField('auth_status', 0);
            }
            if ($status == 2) {
                
            }
            
            $data['pdt'] = time();
            // $data['fanli_time'] = time(); // 记录分红时间
            $data['auth_status'] = $status;
            $result = $seller->where('id=' . $data['id'])->setField('auth_status', $status);
            $data['auth_check_time'] = time();
            $result = $seller->where('id=' . $data['id'])->setField('auth_check_time', time());
            $result = $seller->where('id=' . $data['id'])->setField('auth_check_status', $data['auth_check_status']);
            $result = $seller->where('id=' . $data['id'])->setField('auth_check_label', $data['auth_check_label']);
            
            $fck = D('Fck');
            if ($status == 2) {
                if ($data['is_sn_error'] == 0) {
                   
                }
                
                if ($data['is_sn_error'] == 1) {}
                $result = $seller->where('id=' . $data['id'])->setField('status', 4);
                $model = $seller->field('user_id')
                    ->where('id=' . $data['id'])
                    ->find();
                $fck = M('fck')->where(array(
                    'id' => $model['user_id']
                ))->find();
                $data = array();
                $data['is_push'] = 1;
                $data['push_msg'] = '您的商户审核不通过,请及时查看';
                $data['uid'] = $model['user_id'];
                push_msg($data, $model['user_id']);
                
                $this->success('实名认证审核不通过');
                return;
            }
             
            $model = $seller->where('id=' . $data['id'])->find();
            $user = M('fck')->field('id,user_id,re_path')
                ->where('id=' . $model['user_id'])
                ->find();
            $userlist = M('fck')->field('id,user_id')
                ->where(' id  in (0' . $user['re_path'] . '' . $user['id'] . ')')
                ->select();
            
            foreach ($userlist as $k => $v) {
                // 增加商户数据
                set_user_shop($v);
            }
            $data = array();
            $data['is_push'] = 1;
            $data['push_msg'] = '您的商户审核通过,请及时查看';
            // 新增消息
            create_form($user['id'], $data['push_msg']);
            $data['uid'] = $user['id'];
            push_msg($data, $user['id']);
            $this->success('实名认证成功');
        } else {
            $this->error('开通会员失败');
        }
        unset($fck, $where, $where_two, $rs);
    }

    public function seller()
    {
        $category_id = $_POST['category_id'];
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        $is_mobile = $_POST['is_mobile'];
        $keyword = $_POST['keyword'];
        $type = $_POST['type'];
        $city = $_POST['city'];
        $auth_status = $_GET['auth_status'];
        $seller_type_ = I('get.seller_type', 0);
        $this->assign('seller_type_', $seller_type_);
        $seller_type_str = get_seller_type($seller_type_);
        
        $this->assign('seller_type_str', $seller_type_str);
        
        $seller_no = $_GET['seller_no'];
        $this->assign('seller_no', $seller_no);
        $seller_username = $_GET['seller_username'];
        $this->assign('seller_username', $seller_username);
        $UserID = $_GET['username'];
        $this->assign('username', $UserID);
        $sn = $_GET['sn'];
        $this->assign('sn', $sn);
        $this->assign('auth_status', $auth_status);
        $this->assign('seller_type', $seller_type);
        $seller_type = C('seller_type');
        
        $seller_type = explode('|', $seller_type);
        $map = array();
        IF ($auth_status != 2) {
            // $map['auth_status'] = $auth_status;
            IF ($auth_status == 0) {
                
                $map['t.status'] = ARRAY(
                    'eq',
                    0
                );
            }
            IF ($auth_status == 1) {
                
                $map['t.status'] = ARRAY(
                    'eq',
                    1
                );
            }
            IF ($auth_status == 3) {
                
                $map['t.status'] = ARRAY(
                    'eq',
                    4
                );
            }
        } else {
            $map['t.status'] = ARRAY(
                'eq',
                0
            );
        }
        $map['t.seller_type'] = $seller_type_;
        
        // $map['_string'] = ' (province like "%' . $city . '%" or city like "%' . $city . '%" or area like "%' . $city . '%" ) ';
        IF ($user_id > 10) {
            $model = M('fck')->field('id,shop_id,get_level')
                ->where('id=' . $user_id)
                ->find();
            $str = '';
            if ($model['get_level'] != 3) {
                $map['t.user_id'] = $model['shop_id'];
            } else {
                $map['t.user_id'] = $model['id'];
            }
        }
        if ($is_mobile == 1) {
            $map['title'] = array(
                'like',
                '%' . $keyword . '%'
            );
            if ($category_id > 0) {
                $map['category_id'] = $category_id;
            }
            if ($type > 0) {
                $map['type'] = $type;
            }
        }
        if (! empty($seller_username)) {
            
            $map['_string'] = "  (t.seller_no LIKE '%" . $seller_no . "%' ) and   (g.user_id LIKE '%" . $UserID . "%' OR  g.user_name LIKE '%" . $UserID . "%' ) and (g.user_name LIKE '%" . $seller_username . "%' ) and  (t.sn LIKE '%" . $sn . "%' )";
        }
        if (! empty($UserID)) {
            
            $map['_string'] = "  (t.seller_no LIKE '%" . $seller_no . "%' ) and   (g.user_id LIKE '%" . $UserID . "%' OR  g.user_name LIKE '%" . $UserID . "%' ) and (g.user_name LIKE '%" . $seller_username . "%' ) and  (t.sn LIKE '%" . $sn . "%' )";
        }
        if (! empty($sn)) {
            
            $map['_string'] = "   (t.seller_no LIKE '%" . $seller_no . "%' ) and  (g.user_id LIKE '%" . $UserID . "%' OR  g.user_name LIKE '%" . $UserID . "%' ) and (g.user_name LIKE '%" . $seller_username . "%' ) and  (t.sn LIKE '%" . $sn . "%' )";
        }
        if (! empty($seller_no)) {
            
            $map['_string'] = "  (t.seller_no LIKE '%" . $seller_no . "%' ) and  (g.user_id LIKE '%" . $UserID . "%' OR  g.user_name LIKE '%" . $UserID . "%' ) and (g.user_name LIKE '%" . $seller_username . "%' ) and  (t.sn LIKE '%" . $sn . "%' )";
        }
        
        $form = M('seller');
        
        $field = 't.*,g.shop_name,g.shop_name as shop_user_name ,g.shop_id as my_shop_id ,g.user_id as my_user_id,g.user_name as my_user_name,H.min_fan_money  ';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id ", 'LEFT')
            ->join("xt_user_terminal AS H ON  h.id = t.user_terminal_id ", 'LEFT')
            ->where($map)
            ->order(' t.id desc')
            ->count(); // 总页数
        $top = 10;
        $Page = new Page($count, $top);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        
        if ($is_mobile == 1) {
            $top = 10000;
        }
        
        $list = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id ", 'LEFT')
            ->join("xt_user_terminal AS H ON  h.id = t.user_terminal_id ", 'LEFT')
            ->where($map)
            ->field($field)
            ->group(' t.id ')
            ->order(' t.id desc')
            ->page($p . ',' . $top)
            ->select();
        if ($is_mobile == 1) {
            foreach ($list as $i => $goods) {
                $list[$i]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img']);
                $sell_count = M('order_goods')->where(array(
                    'article_id' => $goods['id']
                ))->sum('quantity');
                IF ($sell_count == null) {
                    $sell_count = 0;
                }
                $list[$i]['sell_count'] = $sell_count;
                $percent = (int) $sell_count / $list[$i]['all_stock'] * 100;
                $percent = round($percent, 2);
                $list[$i]['percent'] = $percent;
                $gupiao_award = 100;
                
                $goods_zd = M('goods_zd')->where(array(
                    'goods_id' => $list[$i]['id']
                ))->select();
                
                foreach ($goods_zd as $K => $item) {
                    if ($item['min'] <= $sell_count && $item['max'] > $sell_count) {
                        $gupiao_award = $item['gupiao_award'];
                    }
                }
                
                $list[$i]['trade_money'] = 0;
                $list[$i]['gupiao_award'] = (int) $gupiao_award;
                $list[$i]['all_fhb'] = $list[$i]['all_stock'] * $list[$i]['fhb'];
                
                $list[$i]['agent_bi'] = number_format($list[$i]['fhb'], 4);
                $list[$i]['fhb'] = number_format($list[$i]['fhb'], 4);
            }
        }
        foreach ($list as $i => $goods) {
            $category = M('article_category')->where(array(
                'id' => $goods['category_id']
            ))->find();
            $list[$i]['category'] = $category['title'];
            $list[$i]['addtime_str'] = date("Y-m-d H:i:s", $goods["addtime"]);
            $list[$i]['fan_time_str'] = date("Y-m-d H:i:s", $goods["fan_time"]);
            $list[$i]['type_str'] = '众筹';
            if ($list[$i]['type'] == 2) {
                
                $list[$i]['type_str'] = '拍卖';
            }
            if ($list[$i]['type'] == 3) {
                
                $list[$i]['type_str'] = '自营商城';
            }
            $list[$i]['seller_name'] = '无';
            $list[$i]['seller_user_name'] = '无';
            $user = M('fck')->field('user_id,user_name')
                ->where(array(
                'id' => $list[$i]['user_id']
            ))
                ->find();
            IF ($user != null) {
                $list[$i]['seller_name'] = $user['user_id'];
                $list[$i]['seller_user_name'] = $user['user_name'];
            }
            if ($list[$i]['status'] == 1) {
                
                $list[$i]['status_str'] = '等待审核';
            }
            if ($list[$i]['status'] == 2) {
                
                $list[$i]['status_str'] = '审核不通过';
            }
            if ($list[$i]['status'] == 0) {
                
                $list[$i]['status_str'] = '已审核';
            }
            if ($list[$i]['status'] == 4) {
                
                $list[$i]['status_str'] = '待完善';
            }
            if ($list[$i]['shop_type'] == 0) {
                
                $list[$i]['shop_type_str'] = '普通';
            }
            if ($list[$i]['shop_type'] == 1) {
                
                $list[$i]['shop_type_str'] = '固定万' . $list[$i]['shop_profit'];
            }
            
            // if ($list[$i]['active_status'] == 0) {
            if ($goods['id'] == 11487) {
                $dl = 0;
            }
            $list[$i]['active_status_str'] = get_terminal_active_status_str($goods);
            // }
            // if ($list[$i]['active_status'] == 1) {
            
            // $list[$i]['active_status_str'] = '已激活';
            // }
            $today_trade_money = M('trade_money')->where(" shop_id='" . $goods['seller_no'] . "' and FROM_UNIXTIME(trade_time,'%Y-%m-%d')= FROM_UNIXTIME(unix_timestamp(now()),'%Y-%m-%d')")->sum('trade_money');
            
            if ($today_trade_money == null) {
                
                $today_trade_money = 0;
            }
            $list[$i]['today_trade_money'] = $today_trade_money;
            $list[$i]['shop_user_name'] = '';
            $user = M('fck')->field('user_id,user_name')
                ->where(array(
                'user_id' => $list[$i]['shop_name']
            ))
                ->find();
            IF ($user != null) {
                $list[$i]['shop_user_name'] = $user['user_name'];
            }
            if (empty($goods['shop_name']) || $goods['my_shop_id'] == 1) {
                
                $list[$i]['shop_name'] = $goods['my_user_id'];
                $list[$i]['shop_user_name'] = $goods['my_user_name'];
            }
            
            $fan_type_str = '';
            if ($list[$i]['fan_type'] == 1) {
                $fan_type_str = '商户';
            }
            if ($list[$i]['fan_type'] == 0) {
                $fan_type_str = '盟友';
            }
            
            $list[$i]['fan_type_str'] = $fan_type_str;
            
            $list[$i]['shop_fan_status_str'] = get_shop_fan_status_str($goods);
            $is_show_fan_btn = 0;
            $list[$i]['is_show_fan_btn'] = $is_show_fan_btn;
            if ($list[$i]['shop_fan_status_str'] == '已达标未返现') {
                $is_show_fan_btn = 1;
                $list[$i]['is_show_fan_btn'] = $is_show_fan_btn;
            }
            $str = '机器未激活';
            
            $trade_money = M('trade_orders')->where('shop_id="' . $goods['seller_no'] . '"')
                ->order('trade_time')
                ->find();
            if ($trade_money != NULL) {
                if ($goods['fan_money'] <= $trade_money['trade_money']) {
                    
                    $now = time();
                    $time = strtotime("" . date('Y-m-d', $trade_money['trade_time']) . " +" . $goods['min_day'] . " day");
                    ;
                    $ff = timediff($now, $time);
                    
                    if ($now < $time) {
                        $str = '还剩' . $ff['day'] . '天';
                    }
                    if ($now >= $time) {
                        $str = '逾期' . $ff['day'] . '天';
                    }
                }
            }
            $list[$i]['min_day'] = $str;
            $list[$i]['seller_type'] = $seller_type[$goods['seller_type']];
            
            IF (empty($goods['update_time'])) {
                
                $list[$i]['update_time'] = $goods['add_time'];
            }
        }
        
        $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 1) {
            $slider = ARRAY();
            $slider[0]['img'] = __ROOT__ . '/Public/Images/slides/1.png';
            $slider[1]['img'] = __ROOT__ . '/Public/Images/slides/2.png';
            $slider[2]['img'] = __ROOT__ . '/Public/Images/slides/3.png';
            $slider[3]['img'] = __ROOT__ . '/Public/Images/slides/5.png';
            $slider[4]['img'] = __ROOT__ . '/Public/Images/slides/6.png';
            
            $slider1 = ARRAY();
            $slider1[0]['img'] = __ROOT__ . '/Public/Images/slides/4.png';
            
            $slider2 = ARRAY();
            $slider2[0]['img'] = __ROOT__ . '/Public/Images/slides/7.png';
            $slider2[1]['img'] = __ROOT__ . '/Public/Images/slides/8.png';
            $slider2[2]['img'] = __ROOT__ . '/Public/Images/slides/9.png';
            
            $data = array();
            $data['data'] = $list;
            $data['city'] = $city;
            $data['slider'] = $slider;
            $data['slider1'] = $slider1;
            $data['slider2'] = $slider2;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            
            $this->display();
        }
    }

    public function my_seller()
    {
        $category_id = $_POST['category_id'];
        $seller_type = I('post.seller_type', 1);
        $huikui_id = I('post.huikui_id', 0);
        $user_id = I('post.user_id', 10942);
        $is_mobile = I('post.is_mobile', 1);
        $keyword = $_POST['keyword'];
        $type = $_POST['type'];
        $city = $_POST['city'];
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 10);
        
        $map = array();
        
        // $map['_string'] = ' (province like "%' . $city . '%" or city like "%' . $city . '%" or area like "%' . $city . '%" ) ';
        
        if ($is_mobile == 1) {}
        
        $form = M('seller');
        
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->where($map)->count(); // 总页数
        
        $Page = new Page($count, $page_size);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        
        $top = 10;
        if ($is_mobile == 1) {
            $top = 10000;
        }
        
        // $list = $form->where($map)
        // ->field($field)
        // ->order(' id desc')
        // ->page($page_index . ',' . $page_size)
        // ->select();
        // if ($is_mobile == 1) {
        // foreach ($list as $i => $goods) {
        // $list[$i]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img']);
        // $sell_count = M('order_goods')->where(array(
        // 'article_id' => $goods['id']
        // ))->sum('quantity');
        // IF ($sell_count == null) {
        // $sell_count = 0;
        // }
        // $list[$i]['sell_count'] = $sell_count;
        // $percent = (int) $sell_count / $list[$i]['all_stock'] * 100;
        // $percent = round($percent, 2);
        // $list[$i]['percent'] = $percent;
        // $gupiao_award = 100;
        
        // $list[$i]['add_time_str'] = date("Y-m-d H:i:s", $goods["add_time"]);
        // }
        // }
        // foreach ($list as $i => $goods) {
        // $category = M('article_category')->where(array(
        // 'id' => $goods['category_id']
        // ))->find();
        // $list[$i]['category'] = $category['title'];
        // $list[$i]['add_time_str'] = date("Y-m-d H:i:s", $goods["add_time"]);
        // $list[$i]['check_time_str'] = date("Y-m-d H:i:s", $goods["check_time"]);
        // $list[$i]['type_str'] = '众筹';
        // if ($list[$i]['type'] == 2) {
        
        // $list[$i]['type_str'] = '拍卖';
        // }
        // if ($list[$i]['type'] == 3) {
        
        // $list[$i]['type_str'] = '自营商城';
        // }
        // $list[$i]['seller_name'] = '无';
        // $user = M('fck')->field('user_id')
        // ->where(array(
        // 'id' => $list[$i]['user_id']
        // ))
        // ->find();
        // IF ($user != null) {
        // $list[$i]['seller_name'] = $user['user_id'];
        // }
        // if ($list[$i]['status'] == 1) {
        
        // $list[$i]['status_str'] = '等待审核';
        // }
        // if ($list[$i]['status'] == 2) {
        
        // $list[$i]['status_str'] = '审核不通过';
        // }
        // if ($list[$i]['status'] == 0) {
        
        // $list[$i]['status_str'] = '已审核';
        // }
        // }
        
        // $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 1) {
            
            $map = array();
            // $map['user_id'] = $user_id;
            // $map['seller_type'] = $seller_type;
            
            $map['status'] = 0;
            if (! empty($keyword)) {
                
                $map['_string'] = ' ( title like "%' . $keyword . '%" or sn like "%' . $keyword . '%" ) ';
            }
            
            $type = I('post.type', 1);
            
            $str = "   t.status=0   ";
            if ($type == 1) {
                $str = "T.user_id=     " . $user_id . " ";
            }
            if ($type == 2) {
                $str = "T.user_id=     " . $user_id;
            }
            if ($type == 3) {
                $str = "(g.re_path   like '%," . $user_id . ",%' )";
            }
            
            $list1 = $form->alias('t')
                ->
            where($str . "  AND t.status=0 AND t.title like '%" . $keyword . "%'  ")
                ->field(' t.*   ')
                ->order(' t.id desc')
                ->page($page_index . ',' . $page_size)
                ->select();
            foreach ($list1 as $i => $goods) {
                
                $list1[$i]['add_time_str'] = date("Y-m-d H:i:s", $goods["add_time"]);
                $list1[$i]['check_time_str'] = date("Y-m-d H:i:s", $goods["check_time"]);
                $list1[$i] = get_seller_info($goods);
                $list1[$i]['add_time_str'] = date("Y-m-d H:i:s", $goods["add_time"]);
                // $list1[$i]['user_id'] = $goods["user_id"];
                $list1[$i]['user_name'] = '结算人:' . $goods["user_name"];
                $list1[$i]['mobile'] = $goods["mobile"];
                $list1[$i]['km'] = '20km';
                
                $tip = explode(',', $goods['remark']);
                
                $remark1 = $tip[0];
                $remark2 = $tip[1];
                $list1[$i]['remark1'] = $remark1;
                $list1[$i]['remark2'] = $remark2;
            }
            $category_item = M('article_category')->where(array(
                'id' => $category_id
            ))->find();
            if ($category_item != NULL) {
                $cate_slider = $category_item['slide'];
                $cate_slider = explode(",", $cate_slider);
            }
            $data = array();
            
            $data['cate_slider'] = $cate_slider;
            $data['over_count'] = 0;
            $data['data'] = $list1;
            $data['current_count'] = count($list1);
            $data['data1'] = $list1;
            $data['data1_count'] = count($list1);
            $data['city'] = $city;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            
            $this->display();
        }
    }

    public function shop_order_list()
    {
        $map = array();
        
        $page_index = I('page_index', 1);
        $page_num = I('page_size', 100);
        $seller_no = $_POST['seller_no'];
        $map['shop_id'] = $seller_no;
        $form = M('trade_orders');
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->where($map)->count(); // 总页数
        
        $Page = new Page($count, $page_num);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $form->where($map)
            ->field($field)
            ->order('trade_time desc, id desc')
            ->page($page_index . ',' . $page_num)
            ->select();
        
        $data = array();
        $data['current_count'] = count($list);
        $data['data'] = $list;
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    // 前台新闻
    public function Goods()
    {
        $category_id = $_POST['category_id'];
        $is_mobile = $_POST['is_mobile'];
        $keyword = $_POST['keyword'];
        $keywords = $_GET['keywords'];
        $is_pin = $_GET['is_pin'];
        $this->assign('keywords', $keywords);
        $type = $_POST['type'];
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        
        $map = array();
        $map['status'] = 1;
        $map['is_pin'] = $is_pin;
        if ($is_pin == 0) {
            
            $this->assign('page_type', 'shop');
        }
        if ($is_pin == 1) {
            
            $this->assign('page_type', 'shop_pin');
        }
        $map['type'] = 2;
        if ($is_mobile == 1) {
            $map['title'] = array(
                'like',
                '%' . $keyword . '%'
            );
            if ($category_id > 0) {
                $map['category_id'] = $category_id;
            }
            if ($type > 0) {
                $map['type'] = $type;
            }
        }
        if (! empty($keywords)) {
            
            $map['title'] = array(
                'like',
                '%' . $keywords . '%'
            );
        }
        
        // $map['user_id'] = array(
        // 'gt',
        // 0
        // );
        
        $this->assign('user_id', $user_id);
        IF ($user_id > 10) {
            $join_ids = '';
            $shop_id = M('seller')->where('user_id=' . $user_id . ' ')
                ->field('id')
                ->select();
            IF (COUNT($shop_id) > 0) {
                FOR ($i = 0; $i < COUNT($shop_id); $i ++) {
                    $join_ids = $join_ids . $shop_id[$i]['id'] . ',';
                }
                $join_ids = $join_ids . '0';
                
                $map['shop_id'] = array(
                    'in',
                    $join_ids
                );
            }
            
            // $model = M('fck')->field('id,shop_id,get_level')
            // ->where('id=' . $user_id)
            // ->find();
            // $str = '';
            // if ($model['get_level'] != 3) {
            // $map['user_id'] = $model['shop_id'];
            // } else {
            // $map['user_id'] = $model['id'];
            // }
        }
        
        $form = M('goods');
        
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
            ->order(' id desc')
            ->page($p . ',10')
            ->select();
        if ($is_mobile == 1) {
            foreach ($list as $i => $goods) {
                $list[$i]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img']);
                $sell_count = M('order_goods')->where(array(
                    'article_id' => $goods['id']
                ))->sum('quantity');
                IF ($sell_count == null) {
                    $sell_count = 0;
                }
                $list[$i]['sell_count'] = $sell_count;
                $percent = (int) $sell_count / $list[$i]['all_stock'] * 100;
                $percent = round($percent, 2);
                $list[$i]['percent'] = $percent;
                $gupiao_award = 100;
                
                $goods_zd = M('goods_zd')->where(array(
                    'goods_id' => $list[$i]['id']
                ))->select();
                
                foreach ($goods_zd as $K => $item) {
                    if ($item['min'] <= $sell_count && $item['max'] > $sell_count) {
                        $gupiao_award = $item['gupiao_award'];
                    }
                }
                
                $list[$i]['gupiao_award'] = (int) $gupiao_award;
                $list[$i]['all_fhb'] = $list[$i]['all_stock'] * $list[$i]['fhb'];
                
                $list[$i]['agent_bi'] = number_format($list[$i]['fhb'], 4);
                $list[$i]['fhb'] = number_format($list[$i]['fhb'], 4);
            }
        }
        foreach ($list as $i => $goods) {
            $category = M('article_category')->where(array(
                'id' => $goods['category_id']
            ))->find();
            $list[$i]['category'] = $category['title'];
            $list[$i]['addtime_str'] = date("Y-m-d H:i:s", $goods["addtime"]);
            $list[$i]['type_str'] = '众筹';
            if ($list[$i]['type'] == 2) {
                
                $list[$i]['type_str'] = '拍卖';
            }
            if ($list[$i]['type'] == 3) {
                
                $list[$i]['type_str'] = '自营商城';
            }
            $shop = M('seller')->where(array(
                'id' => $goods['shop_id']
            ))->find();
            $list[$i]['shop_name'] = $shop['title'];
            
            $list[$i]['is_pin_str'] = '否';
            
            if ($list[$i]['is_pin'] == 1) {
                
                $list[$i]['is_pin_str'] = '<span style="color:red" >是</span>';
            }
        }
        
        $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 1) {
            $slider = ARRAY();
            $slider[0]['img'] = __ROOT__ . '/Public/Images/slides/1.png';
            $slider[1]['img'] = __ROOT__ . '/Public/Images/slides/2.png';
            $slider[2]['img'] = __ROOT__ . '/Public/Images/slides/3.png';
            $slider[3]['img'] = __ROOT__ . '/Public/Images/slides/5.png';
            $slider[4]['img'] = __ROOT__ . '/Public/Images/slides/6.png';
            
            $slider1 = ARRAY();
            $slider1[0]['img'] = __ROOT__ . '/Public/Images/slides/4.png';
            
            $slider2 = ARRAY();
            $slider2[0]['img'] = __ROOT__ . '/Public/Images/slides/7.png';
            $slider2[1]['img'] = __ROOT__ . '/Public/Images/slides/8.png';
            $slider2[2]['img'] = __ROOT__ . '/Public/Images/slides/9.png';
            
            $data = array();
            $data['data'] = $list;
            $data['slider'] = $slider;
            $data['slider1'] = $slider1;
            $data['slider2'] = $slider2;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            
            $this->display();
        }
    }

    // 编辑
    public function goods_edit()
    {
        $is_mobile = $_POST['is_mobile'];
        $channel_id = 1;
        // $this->_Admin_checkUser(); // 后台权限检测
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        if (! IS_POST) {
            
            $id = $this->_get('id');
            
            $rs = M('goods')->where(array(
                'id' => $id
            ))
                ->field('*')
                ->find();
            $dt = M('article_category')->where(array(
                'channel_id' => 1
            ))->select();
            $parent_id = 0;
            $newData = array();
            GetChilds($dt, $newData, $parent_id, $channel_id, $user_id);
            $category = array();
            
            $item['id'] = 0;
            $item['title'] = '请选择类别...';
            $category[] = $item;
            foreach ($newData as $dr) {
                $Id = $dr["id"];
                $ClassLayer = $dr["class_layer"];
                $Title = $dr["title"];
                if ($ClassLayer == 1) {
                    $category[] = $dr;
                } else {
                    $Title = "├ " . $Title;
                    $Title = StringOfChar($ClassLayer - 1, "　") . $Title;
                    $dr["title"] = $Title;
                    $category[] = $dr;
                }
            }
            
            $this->assign('category', $category);
            
            $dt = M('article_goods')->where(array(
                'article_id' => $id
            ))->select();
            
            $this->assign('list', $dt);
            $dt = M('article_goods_spec')->where(array(
                'article_id' => $id,
                'parent_id' => 0
            ))->select();
            
            $this->assign('spec', $dt);
            $dt = M('article_goods_spec')->where(array(
                'article_id' => $id
            ))->select();
            $dt1 = M('goods_zd')->where(array(
                'goods_id' => $id
            ))
                ->order('id asc ')
                ->select();
            $a = "Uploads";
            $this->assign('goods_zd', $dt1);
            if (! strstr($rs['img'], $a)) {
                
                $rs['img'] = '/Public/images/等待上传.png';
            }
            if (! strstr($rs['img1'], $a)) {
                
                $rs['img1'] = '/Public/images/等待上传.png';
            }
            if (! strstr($rs['img2'], $a)) {
                
                $rs['img2'] = '/Public/images/等待上传.png';
            }
            if (! strstr($rs['img3'], $a)) {
                
                $rs['img3'] = '/Public/images/等待上传.png';
            }
            
            $item = array();
            $item['id'] = 0;
            $item['title'] = '请选择商家...';
            $shop_list[] = $item;
            $newData = M('seller')->select();
            if ($user_id > 1) {
                
                $newData = M('seller')->where('user_id=' . $user_id)->select();
            }
            
            foreach ($newData as $dr) {
                if ($user_id == 1) {
                    $shop_user = M('fck')->field('user_id')
                        ->where('id=' . $dr['user_id'])
                        ->find();
                    if ($dr['user_id'] > 1) {
                        $dr['title'] = $dr['title'] . '【合伙人' . $shop_user['user_id'] . '】';
                    }
                }
                
                $shop_list[] = $dr;
            }
            $this->assign('shop_list', $shop_list);
            
            if ($rs) {
                $rs['hide_goods_spec_list'] = urlencode(json_encode($dt));
                $rs['goods_zd'] = $dt1;
                $rs = get_goods_detail($rs);
                $this->assign('vo', $rs);
                unset($id, $rs);
                $this->display('goods_edit');
            } else {
                $rs['addtime'] = time();
                $rs['fhb'] = 0;
                $rs['pin_time'] = 24;
                $rs['goods_no'] = 'BD' . rand(1000000000, 2000000000);
                $this->assign('vo', $rs);
                // $this->error('没有该新闻！');
                // exit;
                if ($is_mobile == 1) {
                    $data = array();
                    $data['data'] = $rs;
                    $data['data2'] = $rs;
                    $data['status'] = 1;
                    $this->ajaxReturn($data);
                } else {
                    
                    $this->display('goods_edit');
                }
            }
        } else {
            if ($is_mobile == 1) {
                $id = $_POST['id'];
                $url = $_POST['url'];
                $user_id = $_POST['user_id'];
                $pin_order_id = $_POST['pin_order_id'];
                
                if (EMPTY($user_id)) {
                    $this->error("请先登录");
                    exit();
                }
                
                $user = M('fck')->where(array(
                    'id' => $user_id
                ))
                    ->field('sex')
                    ->find();
                
                $rs = M('goods')->where(array(
                    'id' => $id
                ))
                    ->field('*')
                    ->find();
                
                $rs['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs['img']);
                
                // if (strpos($rs['img1'], 'Uploads') !== false) {} else {
                // $rs['img1'] = '';
                // }
                // if (strpos($rs['img2'], 'Uploads') !== false) {} else {
                // $rs['img2'] = '';
                // }
                // if (strpos($rs['img3'], 'Uploads') !== false) {} else {
                // $rs['img3'] = '';
                // }
                $rs['addtime_str'] = date("Y-m-d H:i:s", $rs["addtime"]);
                $rs['content'] = str_replace("/ueditor", $url . "/ueditor", $rs['content']);
                
                $rs['content'] = htmlspecialchars_decode($rs['content']);
                
                // $rs['content'] = str_replace("ueditor", $url . "/ueditor", $rs['content']);
                
                $goods_spec = M('article_goods_spec');
                $goods_spec_list = $goods_spec->where(array(
                    'article_id' => $id
                ))
                    ->order('spec_id asc')
                    ->select();
                $rs['goods_spec'] = $goods_spec_list;
                $dt2 = M('goods_crowd_users')->where('goods_id=' . $id . ' AND is_cancel=0')->select();
                foreach ($dt2 as $key => $vo) {
                    
                    $dt2[$key]['percent'] = ($vo['all_crowd_price'] / $rs['all_crown_price'] * 100);
                    $dt2[$key]['agent_use'] = C('agent_use');
                }
                
                $rs['goods_crowd_users'] = $dt2;
                $goods_crowd_users_count = M('goods_crowd_users')->where('goods_id=' . $id . ' AND is_cancel=0')->sum('num*crowd_price');
                $rs['goods_crowd_users_count'] = count($dt2);
                M('goods')->where(array(
                    'id' => $id
                ))->setInc('click', 1);
                
                if ($user['sex'] == '男') {
                    $rs['crowd_price'] = round($rs['boy_price']);
                }
                if ($user['sex'] == '女') {
                    $rs['crowd_price'] = round($rs['girl_price']);
                }
                
                $rs['crowd_status'] = get_goods_crowd_status($id);
                
                $rs['remain_crowd_price'] = $rs['all_crown_price'] - $goods_crowd_users_count;
                $goods_albums = M('goods_albums')->where('goods_id=' . $id)->select();
                
                foreach ($goods_albums as $key1 => $vo) {
                    $goods_albums[$key1]['original_path'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $vo['original_path']);
                }
                $rs['goods_albums'] = $goods_albums;
                $rs['pin_order_id'] = $pin_order_id;
                
                $data = array();
                
                $rs = get_goods_detail($rs);
                $data['data'] = $rs;
                $data['status'] = 1;
                $this->ajaxReturn($data);
            } else {
                
                $form = M('goods');
                $data['category_id'] = $this->_post('ddlParentId');
                $data['shop_id'] = $this->_post('ddlShopId');
                $data['type'] = $this->_post('ddlTypeId');
                
                if ($data['category_id'] == 0) {
                    
                    $this->error('请选择类别！');
                    exit();
                }
                if ($data['shop_id'] == 0) {
                    
                    $this->error('请选择商家！');
                    exit();
                }
                $data['pin_time'] = $this->_post('pin_time');
                $data['title'] = $this->_post('title');
                $data['max_num1'] = $this->_post('max_num1');
                $data['max_num2'] = $this->_post('max_num2');
                $data['max_num'] = $this->_post('max_num');
                $data['content'] = $this->_post('goods_content');
                $data['stock'] = $this->_post('stock');
                
                $data['price'] = $this->_post('price');
                $data['market_price'] = $this->_post('market_price');
                $data['crowd_price'] = $this->_post('crowd_price');
                $data['girl_price'] = $this->_post('girl_price');
                $data['boy_price'] = $this->_post('boy_price');
                
                $data['status'] = 1;
                $data['id'] = $this->_post('id');
                $data['is_pin'] = $this->_post('is_pin');
                
                $data['img'] = get_img_url($_POST['img']);
                
                $data['img1'] = get_img_url($_POST['img1']);
                $data['img2'] = get_img_url($_POST['img2']);
                $data['img3'] = get_img_url($_POST['img3']);
                // $data['type'] = $_POST['type'];
                $data['agent_bi'] = $_POST['agent_bi'];
                $data['agent_use'] = $_POST['agent_use'];
                $data['goods_no'] = $_POST['goods_no'];
                $data['boy_price'] = 1000000000;
                $data['girl_price'] = 1000000000;
                
                $id = $data['id'];
                if ($data['id'] > 0) {
                    
                    $data['goods_no'] = 'BD' . rand(1000000000, 2000000000);
                    $data['edittime'] = strtotime($this->_post('create_time'));
                    $rs = M('goods')->save($data);
                } else {
                    $data['all_stock'] = $this->_post('stock');
                    $data['addtime'] = strtotime($this->_post('create_time'));
                    $data['addtime'] = time();
                    $rs = M('goods')->add($data);
                    $id = $rs;
                }
                
                M('article_goods')->where('article_id=' . $id)->delete();
                
                // region 保存规格====================
                // 保存商品规格
                $goodsSpecJsonStr = $this->_post('hide_goods_spec_list');
                
                if ($goodsSpecJsonStr == '') {
                    $goodsSpecJsonStr = array();
                } else {
                    $goodsSpecJsonStr = json_decode(urldecode($goodsSpecJsonStr));
                }
                
                $data['specs'] = $goodsSpecJsonStr;
                M('article_goods_spec')->where('article_id=' . $id)->delete();
                for ($i = 0; $i < count($goodsSpecJsonStr); $i ++) {
                    $goods_spec = ARRAY();
                    $goods_spec['channel_id'] = 0;
                    $goods_spec['article_id'] = $id;
                    $goods_spec['spec_id'] = $goodsSpecJsonStr[$i]->spec_id;
                    $goods_spec['parent_id'] = $goodsSpecJsonStr[$i]->parent_id;
                    $goods_spec['title'] = $goodsSpecJsonStr[$i]->title;
                    M('article_goods_spec')->add($goods_spec);
                }
                
                // 保存商品信息
                $specGoodsIdArr = $this->_post("hide_goods_id");
                $specGoodsNoArr = $this->_post("spec_goods_no");
                $specMarketPriceArr = $this->_post("spec_market_price");
                $specSellPriceArr = $this->_post("spec_sell_price");
                $specCrownPriceArr = $this->_post("spec_crown_price");
                $specBoyPriceArr = $this->_post("spec_boy_price");
                $specGirlPriceArr = $this->_post("spec_girl_price");
                $specStockQuantityArr = $this->_post("spec_stock_quantity");
                $specSpecIdsArr = $this->_post("hide_spec_ids");
                $specTextArr = $this->_post("hide_spec_text");
                
                if ($specGoodsIdArr != null && $specGoodsNoArr != null && $specMarketPriceArr != null && $specSellPriceArr != null && $specStockQuantityArr != null && $specSpecIdsArr != null && $specTextArr != null && count($specGoodsIdArr) > 0 && count($specGoodsNoArr) > 0 && count($specMarketPriceArr) > 0 && count($specSellPriceArr) > 0 && count($specStockQuantityArr) > 0 && count($specSpecIdsArr) > 0 && count($specTextArr) > 0) {
                    $goodsList = ARRAY();
                    for ($i = 0; $i < count($specGoodsNoArr); $i ++) {
                        $article_goods = ARRAY();
                        $article_goods['channel_id'] = 0;
                        $article_goods['article_id'] = $id;
                        $article_goods['goods_no'] = $specGoodsNoArr[$i];
                        $article_goods['spec_ids'] = $specSpecIdsArr[$i];
                        $article_goods['spec_text'] = $specTextArr[$i];
                        $article_goods['stock_quantity'] = $specStockQuantityArr[$i];
                        $article_goods['market_price'] = $specMarketPriceArr[$i];
                        $article_goods['crown_price'] = $specBoyPriceArr[$i];
                        $article_goods['boy_price'] = $specBoyPriceArr[$i];
                        $article_goods['girl_price'] = $specGirlPriceArr[$i];
                        $article_goods['sell_price'] = $specSellPriceArr[$i];
                        M('article_goods')->add($article_goods);
                        
                        $goods = M('goods')->field('boy_price,girl_price')
                            ->where('id=' . $id)
                            ->find();
                        
                        if ($goods['boy_price'] > $specBoyPriceArr[$i]) {
                            
                            M('goods')->where('id=' . $id)->setField('boy_price', $specBoyPriceArr[$i]);
                        }
                        if ($goods['girl_price'] > $specGirlPriceArr[$i]) {
                            
                            M('goods')->where('id=' . $id)->setField('girl_price', $specGirlPriceArr[$i]);
                        }
                    }
                } else {
                    
                    // M('goods')->where('id=' . $id)->setField('boy_price', 0);
                    // M('goods')->where('id=' . $id)->setField('girl_price', 0);
                }
                
                $minArr = $this->_post("min");
                $maxArr = $this->_post("max");
                $gupiao_awardArr = $this->_post("gupiao_award");
                
                M('goods_zd')->where('goods_id=' . $id)->delete();
                for ($i = 0; $i < count($minArr); $i ++) {
                    $goods_zd = ARRAY();
                    $goods_zd['goods_id'] = $id;
                    $goods_zd['min'] = $minArr[$i];
                    $goods_zd['max'] = $maxArr[$i];
                    $goods_zd['gupiao_award'] = $gupiao_awardArr[$i];
                    $goods_zd['add_time'] = time();
                    M('goods_zd')->add($goods_zd);
                }
                // 图片上传
                M('goods')->where('id=' . $id)->setField('img', '');
                $goodsAblumsJsonStr = $this->_post('img');
                M('goods_albums')->where('goods_id=' . $id)->delete();
                for ($i = 0; $i < count($goodsAblumsJsonStr); $i ++) {
                    $goods_albums = ARRAY();
                    $goods_albums['goods_id'] = $id;
                    $goods_albums['thumb_path'] = get_img_url($goodsAblumsJsonStr[$i]);
                    $goods_albums['original_path'] = get_img_url($goodsAblumsJsonStr[$i]);
                    $goods_albums['add_time'] = time();
                    M('goods_albums')->add($goods_albums);
                    if ($i == 0) {
                        M('goods')->where('id=' . $id)->setField('img', get_img_url($goodsAblumsJsonStr[$i]));
                    }
                }
                // endregion
                
                if (! $rs) {
                    $this->error('编辑失败！');
                    exit();
                } else {
                    unset($form, $data);
                    $url = __URL__ . '/Goods';
                    $this->ajaxSuccess('编辑成功！', $url);
                }
            }
        }
    }

    // 编辑
    public function pin_show()
    {
        $is_mobile = $_POST['is_mobile'];
        $channel_id = 1;
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        
        if ($is_mobile == 1) {
            $id = $_POST['id'];
            $url = $_POST['url'];
            $user_id = $_POST['user_id'];
            $pin_order_id = $_POST['pin_order_id'];
            
            if (EMPTY($user_id)) {
                $this->error("请先登录");
                exit();
            }
            
            $user = M('fck')->where(array(
                'id' => $user_id
            ))
                ->field('sex')
                ->find();
            
            $rs = M('goods')->where(array(
                'id' => $id
            ))
                ->field('*')
                ->find();
            
            $rs['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs['img']);
            
            // if (strpos($rs['img1'], 'Uploads') !== false) {} else {
            // $rs['img1'] = '';
            // }
            // if (strpos($rs['img2'], 'Uploads') !== false) {} else {
            // $rs['img2'] = '';
            // }
            // if (strpos($rs['img3'], 'Uploads') !== false) {} else {
            // $rs['img3'] = '';
            // }
            $rs['addtime_str'] = date("Y-m-d H:i:s", $rs["addtime"]);
            $rs['content'] = str_replace("/ueditor", $url . "/ueditor", $rs['content']);
            
            $rs['content'] = htmlspecialchars_decode($rs['content']);
            
            // $rs['content'] = str_replace("ueditor", $url . "/ueditor", $rs['content']);
            
            $goods_spec = M('article_goods_spec');
            $goods_spec_list = $goods_spec->where(array(
                'article_id' => $id
            ))
                ->order('spec_id asc')
                ->select();
            $rs['goods_spec'] = $goods_spec_list;
            $dt2 = M('goods_crowd_users')->where('goods_id=' . $id . ' AND is_cancel=0')->select();
            foreach ($dt2 as $key => $vo) {
                
                $dt2[$key]['percent'] = ($vo['all_crowd_price'] / $rs['all_crown_price'] * 100);
                $dt2[$key]['agent_use'] = C('agent_use');
            }
            
            $rs['goods_crowd_users'] = $dt2;
            $goods_crowd_users_count = M('goods_crowd_users')->where('goods_id=' . $id . ' AND is_cancel=0')->sum('num*crowd_price');
            $rs['goods_crowd_users_count'] = count($dt2);
            M('goods')->where(array(
                'id' => $id
            ))->setInc('click', 1);
            
            if ($user['sex'] == '男') {
                $rs['crowd_price'] = round($rs['boy_price']);
            }
            if ($user['sex'] == '女') {
                $rs['crowd_price'] = round($rs['girl_price']);
            }
            
            $rs['crowd_status'] = get_goods_crowd_status($id);
            
            $rs['remain_crowd_price'] = $rs['all_crown_price'] - $goods_crowd_users_count;
            $goods_albums = M('goods_albums')->where('goods_id=' . $id)->select();
            
            foreach ($goods_albums as $key1 => $vo) {
                $goods_albums[$key1]['original_path'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $vo['original_path']);
            }
            $rs['goods_albums'] = $goods_albums;
            $rs['pin_order_id'] = $pin_order_id;
            
            $data = array();
            
            $rs = get_pin_detail($rs);
            $data['data'] = $rs;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        }
    }

    public function seller_edit()
    {
        $is_mobile = $_POST['is_mobile'];
        $auth_status = $this->_get('auth_status');
        
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        $this->assign('user_id', $user_id);
        // $this->_Admin_checkUser(); // 后台权限检测
        if (! IS_POST) {
            
            $id = $this->_get('id');
            
            $rs = M('seller')->alias('t')
                ->where(array(
                't.id' => $id
            ))
                ->field('t.*')
                ->find();
            
            if ($rs) {
                $user = M('fck')->where(array(
                    'id' => $rs['user_id']
                ))->find();
                
                $rs['shop_user_name'] = $user['user_id'];
                
                $shop = M('fck')->field('user_id,user_name, shop_id as my_shop_id, shop_name as my_shop_name')
                    ->where(array(
                    'id' => $user['shop_id']
                ))
                    ->find();
                
                $rs['gudong'] = $shop['user_name'] . '(' . $shop['user_id'] . ')';
                // $sn = M('user_terminal')->field('sn_type')
                // ->where(array(
                // 'sn' => $rs['sn']
                // ))
                // ->find();
                
                // $rs['sn_type'] = $sn['sn_type'];
                if ($user['shop_id'] == 1) {
                    
                    $rs['gudong'] = $user['user_id'] . '(' . $user['user_name'] . ')';
                }
                
                if (empty($rs['lat'])) {
                    
                    $rs['lat'] = 32;
                    $rs['lng'] = 119;
                }
                $this->assign('vo', $rs);
                unset($id, $rs);
                
                $wenti = M('fee')->where(array(
                    'id' => 1
                ))->getField('userbank');
                $wentilist = explode('|', $wenti);
                $this->assign('banklist', $wentilist);
                $fee = M('fee');
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
                
                $this->display();
            }
        } else {
            if ($is_mobile == 1) {
                $id = $_POST['id'];
                $rs = M('seller')->where(array(
                    'user_id' => $id
                ))
                    ->field('*')
                    ->find();
                if($rs!=null)
                {
                
                $rs['addtime_str'] = date("Y-m-d H:i:s", $rs["addtime"]);
                
                $rs['content'] = htmlspecialchars_decode($rs['content']);
                
                $seller_albums = M('seller_albums')->where('seller_id=' . $id)->select();
                
                foreach ($seller_albums as $key1 => $vo) {
                    $seller_albums[$key1]['original_path'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $vo['original_path']);
                }
                
                $rs['seller_albums'] = $seller_albums;
                $rs['fans_num'] = 0;
                
                M('seller')->where(array(
                    'id' => $id
                ))->setInc('click', 1);
                
//                 $goods = M('goods')->where('shop_id=' . $id)->select();
                
//                 foreach ($goods as $key => $rs1) {
                    
//                     set_img($goods[$key]);
//                 }
//                 $rs = get_seller_info($rs);
                $rs['goods'] = $goods;
                }
                $data = array();
                
                $app_icon = ARRAY();
                $fee = M('fee');
                $str30 = $fee->where('id=1')->getField('str30');
                $str99 = $fee->where('id=1')->getField('str99');
                
                $app_icon[0]['img'] = $str30;
                $app_icon[1]['img'] = $str99;
                
//                 $goods=M('goods')->where('user_id='. $rs['user_id'])->select();
//                 $new=M('goods')->where('user_id='. $rs['user_id'])->limit(10)->select();
//                 $hot=M('goods')->where('user_id='. $rs['user_id'])->limit(10)->select();
                
                
                
                
                $data['all'] = $goods;
                $data['hot'] = $hot;
                $data['new'] = $new;
                $data['images'] = $app_icon;
                $data['data'] = $rs;
                $data['status'] = 1;
                $this->ajaxReturn($data);
            } else {
                
                $form = M('seller');
                $item = $_POST;
                $id = I('id');
                if (! empty($id)) {
                    if ($id > 0) {
                        $data = $form->where('id=' . $id)->find();
                    }
                    
                    if (empty($data['shop_user_name'])) {
                        
                        // $this->error('请输入所属盟友！');
                        // exit();
                    }
                    
                    $user = M('fck')->where(array(
                        'id' => $_SESSION[C('USER_AUTH_KEY')]
                    ))->find();
                    
                    if ($user == NULL) {
                        
                        // $this->error('所属盟友不存在！');
                        // exit();
                    }
                    
                    $data['remark'] = $this->_post('remark');
                    
                    $data['price'] = $this->_post('price');
                    $data['img'] = $this->_post('img');
                    $data['title'] = $this->_post('title');
                    $data['seller_no'] = $this->_post('seller_no');
                    $data['content'] = $this->_post('content');
                    $data['address'] = $this->_post('address');
                    $data['lat'] = $this->_post('lat');
                    $data['lng'] = $this->_post('lng');
                    
                    $data['user_id'] = $user['id'];
                    // $data['user_name'] =$user['user_name'];
                    if ($data['type'] == 0) {}
                    // $data['credit_mobile'] = $this->_post('credit_mobile');
                    // if (empty($data['credit_mobile'])) {
                    
                    // $this->error('请输入信用卡预留电话！');
                    // exit();
                    // }
                    
                    $data['fan_money'] = $this->_post('shop_fan_money');
                    $data['min_day'] = $this->_post('shop_expire_day');
                    $data['min_money'] = $this->_post('shop_min_fan_money');
                    if ($id > 0) {
                        
                        $data['update_time'] = time();
                        $rs = M('seller')->save($data);
                    } else {
                        $data['seller_no'] = 'BD' . rand(1000000000, 2000000000);
                        // $data['all_stock'] = $this->_post('stock');
                        $data['add_time'] = time();
                        $rs = M('seller')->add($data);
                    }
                    
                    if (! $rs) {
                        $this->error('编辑失败！');
                        exit();
                    } else {
                        $url = __URL__ . '/seller';
                        $this->success('编辑成功！', $url);
                    }
                }
            }
        }
    }

    public function seller_add()
    {
        $is_mobile = $_POST['is_mobile'];
        
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        $this->assign('user_id', $user_id);
        // $this->_Admin_checkUser(); // 后台权限检测
        if (! IS_POST) {
            
            $id = $this->_get('id');
            $seller_type_ = I('get.seller_type', 0);
            $seller_type_str = get_seller_type($seller_type_);
            $this->assign('seller_type_str', $seller_type_str);
            $this->assign('seller_type_', $seller_type_);
            $rs = M('seller')->where(array(
                'id' => $id
            ))
                ->field('*')
                ->find();
            $rs = get_seller_info($rs);
            $rs['address_str'] = $rs['address'];
            $address = M('seller_address')->where('item_id=' . $id)->select();
            $province = M('seller_address')->where('item_id=' . $id)->getField('province', true);
            $city = M('seller_address')->where('item_id=' . $id)->getField('city', true);
            $rs['address'] = $address;
            $rs['province_str'] = implode(',', $province);
            $rs['city_str'] = implode(',', $city);
            $rs['shop_type'] = 0;
            
            if ($rs) {
                
                $user = M('fck')->where(array(
                    'id' => $rs['user_id']
                ))->find();
                
                $rs['shop_user_name'] = $user['user_id'];
                
                $item['id'] = 0;
                $item['user_id'] = '请选择合伙人...';
                $user_list[] = $item;
                
                $newData = M('fck')->order('id asc ')->select();
                foreach ($newData as $dr) {
                    $user_list[] = $dr;
                }
                $this->assign('user_list', $user_list);
                // $rs['seller_no'] = 'BD' . rand(1000000000, 2000000000);
                $this->assign('vo', $rs);
                unset($id, $rs);
                
                $wenti = M('fee')->where(array(
                    'id' => 1
                ))->getField('userbank');
                $wentilist = explode('|', $wenti);
                $this->assign('banklist', $wentilist);
                $fee = M('fee');
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
                $seller_type = C('seller_type');
                $seller_type = explode('|', $seller_type);
                $this->assign('seller_type', $seller_type);
                $this->display('seller_add');
            } else {
                $rs['addtime'] = time();
                $rs['fhb'] = 0;
                // $rs['seller_no'] = 'BD' . rand(1000000000, 2000000000);
                $this->assign('vo', $rs);
                
                if ($is_mobile == 1) {
                    $data = array();
                    $data['data'] = $rs;
                    $data['data2'] = $rs;
                    $data['status'] = 1;
                    $this->ajaxReturn($data);
                } else {
                    $seller_type = C('seller_type');
                    $seller_type = explode('|', $seller_type);
                    $this->assign('seller_type', $seller_type);
                    $this->display('seller_add');
                }
            }
        } else {
            
            $form = M('seller');
            $data = $_POST;
            
            $data['shop_user_name'] = $this->_post('shop_user_name');
            if (empty($data['shop_user_name'])) {
                
                $this->error('请输入所属盟友！');
                exit();
            }
            
            $user = M('fck')->where(array(
                'user_id' => $data['shop_user_name']
            ))->find();
            
            if ($user == NULL) {
                
                $this->error('所属盟友不存在！');
                exit();
            }
            
            $data['user_id'] = $user['id'];
            // $data['user_name'] =$user['user_name'];
            
            $data['mobile'] = $this->_post('mobile');
            if (empty($data['mobile'])) {
                
                // $this->error('请输入联系方式！');
                // exit();
            }
            $data['card_no'] = $this->_post('card_no');
            if (empty($data['card_no'])) {
                
                // $this->error('请输入身份证号！');
                // exit();
            }
            
            $data['bank'] = $this->_post('bank');
            if (empty($data['bank'])) {
                
                // $this->error('请选择开户行！');
                // exit();
            }
            $data['bank_address'] = $this->_post('bank_address');
            if (empty($data['bank_address'])) {
                
                // $this->error('请输入开户行地址！');
                // exit();
            }
            $data['bank_card'] = $this->_post('bank_card');
            if (empty($data['bank_card'])) {
                
                // $this->error('请输入银行卡号！');
                // exit();
            }
            
            if (empty($data['min_day'])) {
                
                // $this->error('请输入逾期天数！');
                // exit();
            }
            if (empty($data['title'])) {
                
                $this->error('请输入商户名称！');
                exit();
            }
            
            $id = $_POST['id'];
            
            $seller_type = $_POST['seller_type'];
            $shop_expire_day = $_POST['shop_expire_day'];
            $data['min_day'] = $shop_expire_day;
            
            // $data['seller_no'] = 'BD' . rand(1000000000, 2000000000);
            // $data['all_stock'] = $this->_post('stock');
            $data['add_time'] = time();
            
            $shop_min_fan_money = $_POST['shop_min_fan_money'];
            // $min_money = $_POST['min_money'];
            $data['min_money'] = $shop_min_fan_money;
            $shop_fan_money = $_POST['shop_fan_money'];
            $data['fan_money'] = $shop_fan_money;
            // min_fan_money
            $data['type'] = 1;
            
            $rs = M('seller')->add($data);
            
            // M('seller')->where('id=' . $id)->setField('is_address', 1);
            // M('seller_address')->where('item_id=' . $id)->delete();
            
            $province = explode(',', $province);
            $city = explode(',', $city);
            
            if (! $rs) {
                $this->error('编辑失败！');
                exit();
            } else {
                unset($form, $data);
                $url = __URL__ . '/seller/auth_status/2/seller_type/' . $seller_type;
                $this->ajaxSuccess('新增成功！', $url);
            }
        }
    }

    public function GoodsDel()
    {
        $id = $this->_get('id');
        $User = M('Goods');
        $where['id'] = $id;
        $rs = $User->where($where)->delete();
        if ($rs) {
            
            $this->ajaxSuccess('删除成功！');
            exit();
        } else {
            $this->ajaxError('删除失败');
            exit();
        }
    }

    public function cart_goods_delete()
    {
        $user_id = $_POST['user_id'];
        if ($user_id == 0) {
            $this->error('请先登录！', 'user.html', true);
            exit();
        }
        $is_mobile = $_POST['is_mobile'];
        $article_id = $_POST['article_id'];
        $goods_id = $_POST['goods_id'];
        $quantity = $_POST['quantity'];
        $cart = M('cart')->where('uid=' . $user_id . ' and article_id=' . $article_id . ' and goods_id=' . $goods_id)->delete();
        
        $sum_num = M('cart')->where('uid=' . $user_id)->sum('quantity');
        if ($sum_num == NULL) {
            $sum_num = 0;
        }
        $sum_amount = M('cart')->where('uid=' . $user_id)->sum('price');
        
        if ($sum_amount == NULL) {
            $sum_amount = 0;
        }
        
        $data = array();
        $data['msg'] = '商品移除成功！';
        $data['status'] = 1;
        
        $data['quantity'] = $sum_num;
        $data['amount'] = $sum_amount;
        $this->ajaxReturn($data);
    }

    public function cart_items()
    {
        $user_id = $_POST['user_id'];
        $cart = M('cart')->where('uid=' . $user_id)->select();
        $total_quantity = 0;
        $totalAmount = 0;
        foreach ($cart as $key => $rs) {
            
            $goods = M('goods')->where('id=' . $rs['article_id'])->find();
            
            $cart[$key]['stock'] = $goods['stock'];
            IF ($rs['goods_id'] > 0) {
                $goodsModel = M('article_goods')->where('id=' . $rs['goods_id'])->find();
                $cart[$key]['stock'] = $goodsModel['stock_quantity'];
            }
            
            $cart[$key]['title'] = $goods['title'];
            $cart[$key]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods['img']);
            
            if ($rs['spec_text'] == null) {
                $cart[$key]['spec_text'] = '';
            }
            
            $total_quantity = $total_quantity + $rs['quantity'];
            $totalAmount = $totalAmount + $rs['price'] * $rs['quantity'];
        }
        $data = array();
        $data['msg'] = '商品已成功添加到购物车！';
        $data['status'] = 1;
        $data['data'] = $cart;
        $data['total_quantity'] = $total_quantity;
        $data['totalAmount'] = $totalAmount;
        $this->ajaxReturn($data);
    }

    public function cart_goods_add()
    {
        $user_id = $_POST['user_id'];
        if ($user_id == 0) {
            $this->error('请先登录！', 'user.html');
            exit();
        }
        $is_mobile = $_POST['is_mobile'];
        $article_id = $_POST['article_id'];
        $goods_id = $_POST['goods_id'];
        $quantity = $_POST['quantity'];
        
        if ($article_id == 0) {
            $this->error('您提交的商品参数有误！');
            exit();
        }
        $fee = M('fee');
        $fee_rs = $fee->field('is_goods')->find();
        if ($fee_rs['is_goods'] == 0) {
            $this->error('对不起，系统暂时禁止购物！');
            exit();
        }
        $form = M('cart');
        $data['uid'] = $user_id;
        $data['article_id'] = $article_id;
        $data['quantity'] = $quantity;
        $data['goods_id'] = $goods_id;
        
        $data['createtime'] = time();
        
        $rs = M('goods')->where(array(
            'id' => $article_id
        ))
            ->field('price')
            ->find();
        $price = $rs['price'];
        if ($goods_id > 0) {
            
            $rs = M('article_goods')->where(array(
                'id' => $goods_id
            ))
                ->field('sell_price')
                ->find();
            $price = $rs['sell_price'];
        }
        
        $data['price'] = $price;
        
        if ($goods_id > 0) {
            $rs = M('article_goods')->where(array(
                'id' => $goods_id
            ))
                ->field('sell_price,spec_text')
                ->find();
            
            $data['price'] = $rs['sell_price'];
            $data['spec_text'] = $rs['spec_text'];
        }
        $ret = check_goods_zd($article_id, $quantity);
        IF ($ret) {
            // $this->error('超过商品区间！');
            // exit();
        }
        
        $cart = M('cart')->where('uid=' . $user_id . ' and article_id=' . $article_id . ' and goods_id=' . $goods_id)->find();
        if ($cart != null) {
            $cart['quantity'] = $cart['quantity'] + $quantity;
            
            $rs = $form->save($cart);
        } else {
            
            $rs = $form->add($data);
        }
        $sum_num = M('cart')->where('uid=' . $user_id)->sum('quantity');
        $sum_amount = M('cart')->where('uid=' . $user_id)->sum('price');
        
        $data = array();
        $data['msg'] = '商品已成功添加到购物车！';
        $data['status'] = 1;
        $data['quantity'] = $sum_num;
        $data['amount'] = $sum_amount * $sum_num;
        $data['url'] = 'cart.html';
        $this->ajaxReturn($data);
    }

    public function get_article_goods_info()
    {
        $article_id = $_POST["article_id"];
        $spec_ids = $_POST["ids"];
        
        $user_id = $_POST["user_id"];
        if (Empty($spec_ids)) {
            
            $this->error('对不起，传输参数不正确');
            exit();
        }
        // 查询商品信息
        $goodsModel = M('article_goods')->where('article_id=' . $article_id . ' AND spec_ids="' . $spec_ids . '"')->find();
        if ($goodsModel == null) {
            
            $this->error($spec_ids . '对不起，暂无查到商品信息' . $article_id);
            exit();
        }
        // 查询是否登录，有则查询会员价格
        $userModel = M('fck')->find($user_id);
        if ($userModel != null) {}
        $data = array();
        $data['msg'] = '商品已成功添加到购物车！';
        $data['status'] = 1;
        $data['id'] = $goodsModel['id'];
        $data['article_id'] = $goodsModel['article_id'];
        $data['goods_id'] = $goodsModel['id'];
        $data['goods_no'] = $goodsModel['goods_no'];
        $data['spec_ids'] = $goodsModel['spec_ids'];
        $data['spec_text'] = $goodsModel['spec_text'];
        $data['stock_quantity'] = $goodsModel['stock_quantity'];
        $data['market_price'] = $goodsModel['market_price'];
        $data['sell_price'] = $goodsModel['sell_price'];
        $data['url'] = 'cart.html';
        $this->ajaxReturn($data);
        // 以JSON格式输出商品信息
    }

    public function cart_goods_update()
    {
        $user_id = $_POST['user_id'];
        if ($user_id == 0) {
            $this->error('请先登录！', 'user.html', true);
            exit();
        }
        $is_mobile = $_POST['is_mobile'];
        $article_id = $_POST['article_id'];
        $goods_id = $_POST['goods_id'];
        $quantity = $_POST['quantity'];
        
        if ($article_id == 0) {
            $this->error('您提交的商品参数有误！', '', true);
            exit();
        }
        $fee = M('fee');
        $fee_rs = $fee->field('is_goods')->find();
        if ($fee_rs['is_goods'] == 0) {
            $this->error('对不起，系统暂时禁止购物！');
            exit();
        }
        $form = M('cart');
        $data['uid'] = $user_id;
        $data['article_id'] = $article_id;
        $data['quantity'] = $quantity;
        $data['goods_id'] = $goods_id;
        $data['createtime'] = time();
        
        $rs = M('goods')->where(array(
            'id' => $article_id
        ))
            ->field('price')
            ->find();
        
        $data['price'] = $rs['price'];
        
        if ($goods_id > 0) {
            $rs = M('article_goods')->where(array(
                'id' => $goods_id
            ))
                ->field('sell_price')
                ->find();
            
            $data['price'] = $rs['sell_price'];
        }
        
        $cart = M('cart')->where('uid=' . $user_id . ' and article_id=' . $article_id . ' and goods_id=' . $goods_id)->find();
        if ($cart != null) {
            $cart['quantity'] = $quantity;
            
            $rs = $form->save($cart);
        }
        $sum_num = M('cart')->sum('quantity');
        $sum_amount = M('cart')->sum('price');
        
        $data = array();
        $data['msg'] = '商品数量修改成功！';
        $data['status'] = 1;
        $data['quantity'] = $sum_num;
        $data['amount'] = $sum_amount;
        $this->ajaxReturn($data);
    }

    public function cart_goods_buy()
    {
        $jsondata = $_POST['jsondata'];
        
        $data = array();
        $data['msg'] = '商品数量修改成功！';
        $data['status'] = 1;
        $data['quantity'] = $sum_num;
        $data['amount'] = $sum_amount;
        $data['jsondata'] = $jsondata;
        $this->ajaxReturn($data);
    }

    public function get_user_addr_book_list()
    {
        $user_id = $_POST['user_id'];
        if ($user_id == 0) {
            $this->error('请先登录！', 'user.html', true);
            exit();
        }
        $user_addr_book = M('user_addr_book')->where('user_id=' . $user_id)->select();
        
        $data = array();
        $data['msg'] = '获取成功';
        $data['status'] = 1;
        $data['data'] = $user_addr_book;
        $this->ajaxReturn($data);
    }

    public function order_save()
    {
        $fee = M('fee');
        $fee_rs = $fee->field('is_goods')->find();
        if ($fee_rs['is_goods'] == 0) {
            $this->error('对不起，系统暂时禁止购物！');
            exit();
        }
        
        // 获取传参信息===================================
        $hideGoodsJson = $_POST['jsondata'];
        
        $goodsList = json_decode($hideGoodsJson);
        
        $hot_id = $_POST["hot_id"];
        // 获取商品JSON数据
        $book_id = $_POST["book_id"];
        $payment_id = $_POST["payment_id"];
        $express_id = $_POST["express_id"];
        $is_invoice = $_POST["is_invoice"];
        $accept_name = $_POST["accept_name"];
        $province = $_POST["province"];
        
        $city = $_POST["city"];
        $area = $_POST["area"];
        
        $address = $_POST["address"];
        $telphone = $_POST["telphone"];
        $mobile = $_POST["mobile"];
        $email = $_POST["email"];
        $post_code = $_POST["post_code"];
        $message = $_POST["message"];
        $invoice_title = $_POST["invoice_title"];
        
        // //检查传参信息===================================
        if (empty($hideGoodsJson)) {
            $this->error('对不起，无法获取商品信息！');
            exit();
        }
        // if (express_id == 0)
        // {
        // context.Response.Write("{\"status\":0, \"msg\":\"对不起，请选择配送方式！\"}");
        // return;
        // }
        // if (payment_id == 0)
        // {
        // context.Response.Write("{\"status\":0, \"msg\":\"对不起，请选择支付方式！\"}");
        // return;
        // }
        // $model.express = new BLL() . express() . GetModel(express_id);
        // if (expModel == null)
        // {
        // context.Response.Write("{\"status\":0, \"msg\":\"对不起，配送方式不存在或已删除！\"}");
        // return;
        // }
        // //检查支付方式
        // $model.payment = new BLL() . payment() . GetModel(payment_id);
        // if (payModel == null)
        // {
        // context.Response.Write("{\"status\":0, \"msg\":\"对不起，支付方式不存在或已删除！\"}");
        // return;
        // }
        // //检查收货人
        if (empty($accept_name)) {
            
            $this->error('对不起，请输入收货人姓名！');
            exit();
        }
        // //检查手机和电话
        if (empty($telphone) && empty($mobile)) {
            
            $this->error('对不起，请输入收货人联系电话或手机！');
            exit();
        }
        // //检查地区
        if (empty($area)) {
            $this->error('对不起，请选择您所在的省市区！');
            exit();
        }
        // //检查地址
        if (empty($address)) {
            $this->error('对不起，请输入详细的收货地址！');
            exit();
        }
        
        // //如果开启匿名购物则不检查会员是否登录
        $user_id = $_POST["user_id"];
        ;
        $user_group_id = 0;
        $user_name = '';
        // //检查用户是否登录
        $userModel = M('fck')->find($user_id);
        if ($userModel != null) {
            $user_id = $userModel['id'];
            $user_group_id = $userModel['group_id'];
            $user_name = $userModel['user_name'];
            // 检查是否需要添加会员地址
            if ($book_id == 0) {
                
                $addrModel['user_id'] = $userModel['id'];
                $addrModel['user_name'] = $userModel['user_name'];
                $addrModel['accept_name'] = $accept_name;
                $addrModel['area'] = $area;
                $addrModel['address'] = $address;
                $addrModel['mobile'] = $mobile;
                $addrModel['telphone'] = $telphone;
                $addrModel['email'] = $email;
                $addrModel['post_code'] = $post_code;
                M('user_addr_book')->add($addrModel);
            }
        }
        if ($userModel == null) {
            $this->error('对不起，用户尚未登录或已超时！');
            exit();
        }
        
        // 获取商品信息==================================
        // List<Model.cart_keys> iList = (List<Model.cart_keys>)JsonHelper.JSONToObject<List<Model.cart_keys>>(hideGoodsJson);
        // List<Model.cart_items> goodsList = ShopCart.ToList(iList, user_group_id); //商品列表
        
        $total_num = 0;
        $payable_amount = 0;
        $real_amount = 0;
        $total_point = 0;
        $total_quantity = 0;
        $goodsTotal = array();
        
        foreach ($goodsList as $key => $rs) {
            
            $total_quantity = $total_quantity + $rs->quantity;
            if ($rs->goods_id > 0) {
                $goods_item = M('article_goods')->find($rs->goods_id);
                $payable_amount = $payable_amount + $goods_item['sell_price'] * $rs->quantity;
                $real_amount = $real_amount + $goods_item['sell_price'] * $rs->quantity;
            } else {
                $goods = M('goods')->find($rs->article_id);
                $payable_amount = $payable_amount + $goods['price'] * $rs->quantity;
                $real_amount = $real_amount + $goods['price'] * $rs->quantity;
            }
            
            $goodsTotal['total_quantity'] = $total_quantity;
            $goodsTotal['real_amount'] = $real_amount;
            $goodsTotal['payable_amount'] = $payable_amount;
            $goodsTotal['total_point'] = $total_point;
        }
        // $model.cart_total goodsTotal = ShopCart . GetTotal(goodsList); // 商品统计
        // if (goodsList == null)
        // {
        // context.Response.Write("{\"status\":0, \"msg\":\"对不起，商品为空，无法结算！\"}");
        // return;
        // }
        
        // //保存订单=======================================
        
        $model['order_no'] = "B" . date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        ; // 订单号B开头为商品订单
        $model['user_id'] = $user_id;
        $model['user_name'] = $user_name;
        $model['payment_id'] = $payment_id;
        $model['express_id'] = $express_id;
        $model['accept_name'] = $accept_name;
        $model['area'] = $area; // 省市区以逗号相隔
        
        $model['address'] = $address;
        $model['telphone'] = $telphone;
        $model['mobile'] = $mobile;
        $model['message'] = $message;
        $model['email'] = $email;
        $model['post_code'] = $post_code;
        $model['is_invoice'] = $is_invoice;
        $model['payable_amount'] = $goodsTotal['payable_amount'];
        $model['real_amount'] = $goodsTotal['real_amount'];
        $model['express_status'] = 0;
        $model['payment_status'] = 0;
        // $model['payment_time'] = time();
        // $model['express_fee'] = expModel . express_fee; // 物流费用
        // //是否先款后货
        
        // 是否开具发票
        
        // 订单总金额=实付商品金额+运费+支付手续费+税金
        $model['order_amount'] = $model['real_amount'];
        // 购物积分,可为负数
        $model['point'] = $goodsTotal['total_point'];
        $model['add_time'] = time();
        // //商品详细列表
        // List<Model.order_goods> gls = new List<Model.order_goods>();
        $gls = ARRAY();
        $goods_id = 0;
        foreach ($goodsList as $k => $rs) {
            
            $goods = M('goods')->where('id=' . $rs->article_id)->find();
            $goods_id = $goods['id'];
            $goodsList[$k]->spec_text = '';
            $goodsList[$k]->sell_price = $goods['price'];
            $goodsList[$k]->user_price = $goods['price'];
            
            $goodsList[$k]->point = 0;
            
            if ($rs->goods_id > 0) {
                $goods_item = M('article_goods')->where('id=' . $rs->goods_id)->find();
                $StockQuantity = $goods_item['stock_quantity'];
                $goodsList[$k]->goods_no = $goods_item['goods_no'];
                $goodsList[$k]->spec_text = $goods_item['spec_text'];
                $goodsList[$k]->sell_price = $goods_item['sell_price'];
                $goodsList[$k]->user_price = $goods_item['sell_price'];
            } else {
                $StockQuantity = $goods['stock'];
                
                $goodsList[$k]->goods_no = $goods['goods_no'];
                $goodsList[$k]->sell_price = $goods['price'];
                $goodsList[$k]->user_price = $goods['price'];
            }
            $goodsList[$k]->goods_title = $goods['title'];
            $goodsList[$k]->title = $goods['title'];
            $goodsList[$k]->img_url = $goods['img'];
            
            if (((int) $StockQuantity < ($rs->quantity))) {
                
                $this->error('订单中某个商品库存不足，请修改重试！');
                exit();
            }
        }
        $model['status'] = 1;
        $model['confirm_time'] = time();
        
        if ($model['order_amount'] > $userModel['agent_use']) {
            
            $this->error('拍币不足！');
            exit();
        }
        $order_amount = $model['order_amount'];
        
        $fck = D('Fck');
        $fck->execute("UPDATE __TABLE__ set  agent_use=agent_use-" . $order_amount . " where id=" . $userModel['id']);
        
        $kt_cont = "购物下单";
        
        $fck = D('Fck');
        
        $fck->addencAdd($userModel['id'], $userModel['user_id'], - $order_amount, 19, 0, 0, 0, $kt_cont . '-' . C('buy_point'));
        
        $model['goods_hot_id'] = $hot_id;
        
        $result = M('orders')->add($model);
        
        M('goods_crowd_users')->where('goods_id=' . $goods_id . ' and hot_id=' . $hot_id)->setField('order_id', $result);
        
        foreach ($goodsList as $kk => $rs) {
            $item['channel_id'] = 0;
            $item['article_id'] = $rs->article_id;
            $item['goods_id'] = $rs->goods_id;
            $item['goods_title'] = $rs->goods_title;
            $item['goods_no'] = $rs->goods_no;
            $item['title'] = $rs->title;
            $item['img_url'] = $rs->img_url;
            $item['spec_text'] = $rs->spec_text;
            $item['goods_price'] = $rs->sell_price;
            $item['real_price'] = $rs->user_price;
            $item['quantity'] = $rs->quantity;
            $item['point'] = $rs->point;
            $item['order_id'] = $result;
            M('order_goods')->add($item);
            
            $cart = M('cart')->where('uid=' . $user_id . ' and article_id=' . $rs->article_id . ' and goods_id=' . $rs->goods_id)->delete();
            
            // 减少库存
            if ($rs->goods_id > 0) {
                M('article_goods')->where('id=' . $item['goods_id'] . ' and article_id=' . $item['article_id'])->setDec('stock_quantity', $item['quantity']);
                $stock_quantity = M('article_goods')->where('  article_id=' . $item['article_id'])->sum('quantity');
                M('goods')->where('id=' . $item['article_id'])->setField('stock', $stock_quantity);
            } else {
                M('goods')->where('id=' . $item['article_id'])->setDec('stock', $item['quantity']);
            }
        }
        
        $sum_num = M('cart')->where('uid=' . $user_id)->sum('quantity');
        if ($sum_num == NULL) {
            $sum_num = 0;
        }
        
        $form = M('order_goods');
        $map = array();
        $map['order_id'] = $result;
        $list = $form->where($map)->select();
        
        foreach ($list as $i => $item) {
            $order_num = M('order_goods')->where('article_id=' . $item['article_id'] . ' AND     EXISTS ( SELECT * FROM xt_orders WHERE ID=order_id ) ')->count();
            $goods_zd = M('goods_zd')->where('goods_id=' . $goods['id'] . '   ( min< ' . $order_num . '   AND max>=' . $order_num . ')')->find();
            if ($goods_zd == NULL) {
                $goods_zd = M('goods_zd')->where('goods_id=' . $goods['id'] . '')
                    ->order(' min asc ')
                    ->find();
            }
            
            $gupiao_award = $goods_zd['gupiao_award'];
            
            $gupiao_award = $gupiao_award * 0.01 * $item['goods_price'] * $item['quantity'];
            // $fck = D('Fck');
            // $fck->execute("UPDATE __TABLE__ set live_gupiao=live_gupiao+" . $gupiao_award . " where id=" . $model['user_id']);
            
            // $kt_cont = "购物下单";
            
            // $fck = D('Fck');
            
            // $model = $fck->where('id=' . $model['user_id'])->find();
            // $fck->addencAdd($model['id'], $model['user_id'], $gupiao_award, 19, 0, 0, 0, $kt_cont . '-赠送' . C('live_gupiao'));
            
            // $agent_bi = $goods['agent_bi'] * $item['quantity'];
            
            // $fck = D('Fck');
            // $fck->execute("UPDATE __TABLE__ set agent_bi=agent_bi+" . $agent_bi . " where id=" . $model['id']);
            
            // $kt_cont = "购物下单";
            
            // $fck = D('Fck');
            // if ($agent_bi > 0) {
            // $fck->addencAdd($model['id'], $model['user_id'], $agent_bi, 19, 0, 0, 0, $kt_cont . '-赠送' . C('ssb'));
            // }
            
            // $agent_use = $goods['agent_use'] * $item['quantity'];
            
            // sum_user_remain_award($userModel, $agent_use);
            
            // $jjbb_agent_use = 0;
            // $jjbb_gupiao = 0;
            // $fee_rs = $fee->field('s16,jjbb')->find();
            // get_jjbb($fee_rs, $userModel['get_level'] - 1, $agent_use, $jjbb_agent_use, $jjbb_gupiao);
            
            // $fck->execute("UPDATE __TABLE__ set live_gupiao=live_gupiao+" . $jjbb_gupiao . ",agent_use=agent_use+" . $jjbb_agent_use . ",award_money=award_money+" . $agent_use . ",limit_money=limit_money-" . $agent_use . " where id=" . $model['id']);
            
            // $kt_cont = "购物下单";
            
            // $fck = D('Fck');
            // if ($jjbb_agent_use > 0) {
            // $fck->addencAdd($model['id'], $model['user_id'], $jjbb_agent_use, 19, 0, 0, 0, $kt_cont . '-赠送' . C('agent_use'));
            // }
            // $kt_cont = "购物下单";
            
            // $fck = D('Fck');
            // if ($jjbb_gupiao > 0) {
            // $fck->addencAdd($model['id'], $model['user_id'], $jjbb_gupiao, 19, 0, 0, 0, $kt_cont . '-赠送' . C('live_gupiao'));
            // }
        }
        
        // 下架抢购商品
        $goods_hot = M('goods_hot');
        $goods_hot->where('id=' . $hot_id)->setField('status', 0);
        
        $data = array();
        $data['msg'] = '恭喜您，订单已成功提交';
        $data['status'] = 1;
        $data['cart_num'] = $sum_num;
        $data['is_shopping'] = 1;
        $data['url'] = 'index.html';
        $this->ajaxReturn($data);
    }

    public function user_address_edit()
    {
        $user_id = $_POST['user_id'];
        // 检查用户是否登录
        $userModel = M('fck')->find($user_id);
        
        if ($userModel == null) {
            $this->error('对不起，用户尚未登录或已超时！');
            exit();
        }
        $id = $_POST["id"];
        if ($id > 0) {
            
            $user_addr_book = M('user_addr_book')->where('id=' . $id . '  ')->find();
            
            if ($user_addr_book == null) {
                
                $this->error('对不起，收货地址不存在或已删除！');
                exit();
            }
        }
        
        $accept_name = $this->_post("txtAcceptName");
        $province = $this->_post("txtProvince");
        $city = $this->_post("txtCity");
        $area = $this->_post("txtArea");
        $address = $this->_post("txtAddress");
        $mobile = $this->_post("txtMobile");
        $telphone = $this->_post("txtTelphone");
        $email = $this->_post("txtEmail");
        $post_code = $this->_post("txtPostCode");
        
        // 校检验证码
        
        // 检查收件人
        if (Empty($accept_name)) {
            $this->error('对不起，请输入收件人姓名！');
            exit();
        }
        // 检查省市区
        if (Empty($area)) {
            $this->error('对不起，请选择您所在的省市区！' . $area);
            return;
        }
        // 检查手机
        if (Empty($mobile)) {
            $this->error('对不起，请输入收件人的手机！');
            return;
        }
        // 保存数据
        $model['user_id'] = $userModel['id'];
        $model['user_name'] = $userModel['user_name'];
        $model['accept_name'] = $accept_name;
        $model['area'] = $area;
        $model['address'] = $address;
        $model['mobile'] = $mobile;
        $model['telphone'] = $telphone;
        $model['email'] = $email;
        $model['post_code'] = $post_code;
        if ($id > 0) {
            
            M('user_addr_book')->where('id=' . $id)->save($model);
            
            $data = array();
            $data['msg'] = '修改收货地址成功';
            $data['status'] = 1;
            $data['url'] = 'useraddress.html';
            $this->ajaxReturn($data);
        } else {
            $model['add_time'] = time();
            M('user_addr_book')->add($model);
            
            $data = array();
            $data['msg'] = '新增收货地址成功';
            $data['status'] = 1;
            $data['url'] = 'useraddress.html';
            $this->ajaxReturn($data);
        }
        return;
    }

    public function get_user_addr_book()
    {
        $user_id = $_POST['user_id'];
        $user_addr_book = M('user_addr_book')->where('user_id=' . $user_id)->select();
        
        $data = array();
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $user_addr_book;
        $this->ajaxReturn($data);
    }

    public function get_user_addr_book_info()
    {
        $user_id = $_POST['user_id'];
        $id = $_POST['id'];
        $user_addr_book = M('user_addr_book')->where('id=' . $id)->find();
        
        $data = array();
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $user_addr_book;
        $this->ajaxReturn($data);
    }

    public function get_user_order()
    {
        $user_id = $_POST['user_id'];
        $type = $_POST['type'];
        $user_id = $_POST['user_id'];
        $order_type = 1;
        // 全部订单
        if ($type == 1) {
            $orders = M('orders')->where(' (user_id=' . $user_id . ' or money_user_id=  ' . $user_id . ')  AND is_give=0 and order_type=' . $order_type)
                ->order(' add_time DESC  ')
                ->select();
        } // 待付款
else if ($type == 2) {
            $orders = M('orders')->where(' (user_id=' . $user_id . ' or money_user_id=  ' . $user_id . ')  AND is_give=0 and order_type=' . $order_type . '  and  payment_status=0 and status=1 ')
                ->order(' add_time DESC  ')
                ->select();
        } // 待发货
else if ($type == 3) {
            $orders = M('orders')->where(' (user_id=' . $user_id . ' or money_user_id=  ' . $user_id . ')  AND is_give=0 and order_type=' . $order_type . '  and  express_status=1 and status=2 ')
                ->order(' add_time DESC  ')
                ->select();
        } // 待收货
else if ($type == 4) {
            
            $orders = M('orders')->where(' (user_id=' . $user_id . ' or money_user_id=  ' . $user_id . ')  AND is_give=0 and order_type=' . $order_type . '  and  express_status=2 and status=2 ')
                ->order(' add_time DESC  ')
                ->select();
        } // 取消的订单
else if ($type == 5) {
            
            $orders = M('orders')->where(' (user_id=' . $user_id . ' or money_user_id=  ' . $user_id . ')  AND is_give=0 and order_type=' . $order_type . '  and  status=4 ')
                ->order(' add_time DESC  ')
                ->select();
        } // 退换货
else if ($type == 6) {
            
            $orders = M('orders')->where(' (user_id=' . $user_id . ' or money_user_id=  ' . $user_id . ')  AND is_give=0 and order_type=' . $order_type . '  and  status=6 ')
                ->order(' add_time DESC  ')
                ->select();
        }
        $fee = M('fee'); // 参数表
        $fee_rs = $fee->field('jifen_time1')->find();
        $jifen_time1 = $fee_rs['jifen_time1'];
        foreach ($orders as $key => $rs) {
            check_order_status($rs["id"]);
            $order_goods = M('order_goods')->where('order_id=' . $rs['id'])->select();
            
            // if ($type == 5) {
            // $order = M('orders')->where('id=' . $rs['order_id'])->find();
            // $order_goods = M('order_goods')->where('order_id=' . $rs['order_id'])->select();
            // $orders[$key]['order_no'] = $order['order_no'];
            // }
            foreach ($order_goods as $key1 => $rs1) {
                
                $order_goods[$key1]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs1['img_url']);
                $goods = M('goods')->where('id=' . $rs1['article_id'])->find();
                $supplier = M('seller')->where('id=' . $goods['shop_id'])->find();
                
                $order_goods[$key1]['shop_title'] = $supplier['title'];
                $goods = get_pin_count($goods, $rs1['article_id'], $rs1['order_id']);
                $order_goods[$key1]['pin_list'] = $goods['pin_list'];
            }
            $supplier = M('seller')->where('id=' . $rs['goods_shop_id'])->find();
            
            $orders[$key]['shop_name'] = $supplier['title'];
            $orders[$key]['order_goods'] = $order_goods;
            $orders[$key]['status_str'] = get_order_status($rs['id']);
            // if ($type == 5) {
            // $orders[$key]['status_str'] = get_order_status($rs['order_id']);
            // }
            // if ($type == 5) {
            // $goods_crowd = M('goods_crowd_users')->where('Id=' . $rs['id'])->find();
            // if (EMPTY($rs['id'])) {
            // $orders[$key]['status_str'] = '投诉中,等待上传凭证';
            // } else {
            // $orders[$key]['status_str'] = '投诉中,等待审核';
            // }
            // $orders[$key]['tousu_money'] = get_order_tousu_money($rs['order_id']);
            
            // $orders[$key]['tousutime_str'] = date("Y-m-d H:i:s", $goods_crowd["tousu_time"]);
            // $orders[$key]['collect_money'] = get_order_collect_money($rs['order_id']);
            // }
            if ($type == 2) {
                
                $orders[$key]['collect_money'] = get_order_collect_money($rs['id']);
            }
            $orders[$key]['addtime_str'] = date("Y-m-d H:i:s", $rs["add_time"]);
            
            $orders[$key]['order_crown_user'] = get_order_crown_user_list($rs['crowd_id'], $rs['id']);
            
            $orders[$key]['remain_money_time'] = 0;
            $orders[$key]['now_time'] = time();
            
            $orders[$key]['end_money_time'] = strtotime("+" . $jifen_time1 . " minute", $rs["add_time"]);
            if ((time() < $orders[$key]['end_money_time'])) {
                
                $orders[$key]['remain_money_time'] = $orders[$key]['end_money_time'] - time();
            }
            
            $shopModel = M('fck')->field('wx_img,zfb_img')->find($orders[$key]['shop_id']);
            $orders[$key]['wx_img'] = $shopModel['wx_img'];
            $orders[$key]['zfb_img'] = $shopModel['zfb_img'];
        }
        $data = array();
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $orders;
        $this->ajaxReturn($data);
    }

    public function get_user_crowd_order()
    {
        $type = $_POST['type'];
        $user_id = $_POST['user_id'];
        
        // $type = 2;
        // $user_id = 1;
        
        // 待收款
        if ($type == 1) {
            $order_ids = M('goods_crowd_users')->field('order_id')
                ->where('     user_id=' . $user_id . '     and  status=1 and payment_status=0  and money_status=0   ')
                ->select();
            $ids = '';
            foreach ($order_ids as $i => $value) {
                $ids = $ids . $value['order_id'] . ',';
            }
            $ids = $ids . '0';
            
            $orders = M('orders')->where(' id in (' . $ids . ') AND   payment_status<=2 AND express_status=0 AND confirm_status=0 AND   exists ( select id from xt_goods_crowd_users A WHERE A.hot_id=xt_orders.goods_hot_id and A.user_id=' . $user_id . ' AND (A.status=2 OR A.status=1  ) ) ')
                ->order(' add_time DESC  ')
                ->select();
        } else if ($type == 2) {
            $orders = M('goods_crowd_users')->field('id, goods_price, title, goods_id, user_id
                    , add_time, update_time, num, crowd_price, user_name, area , hot_id
                    , payment_status, status, order_id, crown_no, tousu_time, tousu_img_url, money_status, money_time, is_tousu
                    ,sum(all_crowd_price) AS all_crowd_price')
                ->where('     user_id=' . $user_id . '  and hot_id>0 and order_id=0 ')
                ->group('user_id')
                ->select();
        } else if ($type == 3) {
            $order_ids = M('goods_crowd_users')->field('order_id')
                ->where('     user_id=' . $user_id . '     and  status=2 ')
                ->select();
            $ids = '';
            foreach ($order_ids as $i => $value) {
                $ids = $ids . $value['order_id'] . ',';
            }
            $ids = $ids . '0';
            
            $orders = M('orders')->where('   id in (' . $ids . ') ')
                ->order(' add_time DESC  ')
                ->select();
        } else if ($type == 5) {
            $order_ids = M('goods_crowd_users')->field('order_id')
                ->where('     user_id=' . $user_id . '     and  status=1 ')
                ->select();
            $ids = '';
            foreach ($order_ids as $i => $value) {
                $ids = $ids . $value['order_id'] . ',';
            }
            $ids = $ids . '0';
            
            $orders = M('orders')->where('   id in (' . $ids . ')    AND   payment_status=1 AND confirm_status=1 AND express_status=0  AND STATUS=2')
                ->order(' add_time DESC  ')
                ->select();
        } else {
            $orders = M('orders')->where('  payment_status=1 AND express_status=0 AND  exists ( select id from xt_goods_crowd_users A WHERE A.hot_id=xt_orders.goods_hot_id and A.user_id=' . $user_id . ' AND (A.status=2 OR A.status=1  ) ) ')
                ->order(' add_time DESC  ')
                ->select();
        }
        
        foreach ($orders as $key => $rs) {
            if ($type != 2) {
                $order_goods = M('order_goods')->where('order_id=' . $rs['id'])->select();
                
                foreach ($order_goods as $key1 => $rs1) {
                    
                    $order_goods[$key1]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs1['img_url']);
                }
                $orders[$key]['order_goods'] = $order_goods;
                $orders[$key]['status_str'] = get_order_status($rs['id'], $user_id);
                $orders[$key]['addtime_str'] = date("Y-m-d H:i:s", $rs["add_time"]);
            } else {
                $goods = M('goods')->where('id=' . $rs['goods_id'])->find();
                $orders[$key]['goods'] = $goods;
                $orders[$key]['order_no'] = $rs['crown_no'];
                if ($type == 2) {
                    $orders[$key]['status_str'] = '等待抢购';
                    $goods = M('goods_hot')->where('id=' . $rs['hot_id'])->select();
                    foreach ($goods as $key2 => $rs2) {
                        $goods_item = M('goods')->field('img')
                            ->where('id=' . $rs2['goods_id'])
                            ->find();
                        
                        $goods[$key2]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods_item['img']);
                    }
                    $orders[$key]['order_goods'] = $goods;
                    $orders[$key]['addtime_str'] = date("Y-m-d H:i:s", $rs["add_time"]);
                }
            }
            $all_crowd_price = M('goods_crowd_users')->where('     user_id=' . $user_id . '     and  order_id=' . $rs['id'])->sum('all_crowd_price');
            if ($all_crowd_price == NULL) {
                $all_crowd_price = 0;
            }
            if ($type != 2) {
                $orders[$key]['all_crowd_price'] = $all_crowd_price;
            }
            $goods_crowd = M('goods_crowd_users')->where('user_id=' . $user_id . ' AND order_id=' . $rs['id'])->find();
            $orders[$key]['crowd_status'] = $goods_crowd['status'];
            $orders[$key]['crowd_money_status'] = $goods_crowd['money_status'];
        }
        $data = array();
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $orders;
        $this->ajaxReturn($data);
    }

    public function get_order_info()
    {
        $user_id = $_POST['user_id'];
        $order_id = $_POST['order_id'];
        $orders = M('orders')->where('id=' . $order_id)->find();
        
        $order_goods = M('order_goods')->where('order_id=' . $orders['id'])->select();
        
        foreach ($order_goods as $key1 => $rs1) {
            
            $order_goods[$key1]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs1['img_url']);
        }
        $orders['order_goods'] = $order_goods;
        $orders['status_str'] = get_order_status($orders['id'], $user_id);
        $orders['addtime_str'] = date("Y-m-d H:i:s", $orders["add_time"]);
        $orders['payment_time_str'] = date("Y-m-d H:i:s", $orders["payment_time"]);
        $orders['confirm_time_str'] = date("Y-m-d H:i:s", $orders["confirm_time"]);
        $orders['express_time_str'] = date("Y-m-d H:i:s", $orders["express_time"]);
        $orders['complete_time_str'] = date("Y-m-d H:i:s", $orders["complete_time"]);
        $orders['order_user'] = get_order_user($orders['goods_hot_id']);
        $goods_crowd = M('goods_crowd_users')->where('user_id=' . $user_id . ' AND order_id=' . $order_id)->find();
        $orders['crowd_status'] = $goods_crowd['status'];
        
        $sum_tousu_money = M('goods_crowd_users')->where('status=2  AND order_id=' . $order_id)->sum('all_crowd_price');
        IF ($sum_tousu_money == null) {
            $sum_tousu_money = 0;
        }
        
        $orders['tousu_money'] = $sum_tousu_money;
        
        $data = array();
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $orders;
        $this->ajaxReturn($data);
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
            echo "<script>window.parent.form1.img.value='" . $U_inpath . "';</script>";
            echo "<script>window.parent.form1.img.src='" . $U_inpath . "';</script>";
            
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function upload_fengcai_img1()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_img1();
        }
    }

    protected function _upload_fengcai_img1()
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
            $U_inpath = (str_replace('./Public/', '/Public/', $U_path)) . $U_nname;
            
            $name = $_POST;
            
            echo "<script>window.parent.form1." . $name['upload_type'] . "_value.value='" . $U_inpath . "';window.parent.form1." . $name['upload_type'] . ".src='" . $U_inpath . "';</script>";
            // echo "<script>window.parent.form1.".$name['upload_type'].".src='" . $U_inpath . "';</script>";
            
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function upload_fengcai_img2()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_img2();
        }
    }

    protected function _upload_fengcai_img2()
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
            
            echo "<script>window.parent.form1.img2.value='" . $U_inpath . "';</script>";
            echo "<script>window.parent.form1.img2.src='" . $U_inpath . "';</script>";
            
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function upload_fengcai_img3()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_img3();
        }
    }

    protected function _upload_fengcai_img3()
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
            
            echo "<script>window.parent.form1.img3.value='" . $U_inpath . "';</script>";
            echo "<script>window.parent.form1.img3.src='" . $U_inpath . "';</script>";
            
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function upload_fengcai_saoma_img()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_saoma_img();
        }
    }

    protected function _upload_fengcai_saoma_img()
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
            $U_inpath = (str_replace('./Public/', '/Public/', $U_path)) . $U_nname;
            
            echo "<script>window.parent.form1.saoma_img.value='" . $U_inpath . "';</script>";
            echo "<script>window.parent.form1.saoma_img_url.src='" . $U_inpath . "';</script>";
            
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function SellerDel()
    {
        $id = $this->_get('id');
        $User = M('seller');
        $where['id'] = $id;
        $rs = $User->where($where)->delete();
        if ($rs) {
            $this->ajaxSuccess('删除成功！');
            exit();
        } else {
            $this->ajaxError('删除失败');
            exit();
        }
    }

    public function SellerFan()
    {
        $id = $this->_get('id');
        $User = M('seller');
        $where['id'] = $id;
        $usrs = $User->where($where)
            ->field('id,is_fan')
            ->find();
        
        IF ($usrs['is_fan'] == 1) {
            $this->ajaxError('已返现,不能多次返现');
            exit();
        }
        $rs = $User->where($where)->setField('is_fan', 1);
        $User->where($where)->setField('fan_time', time());
        if ($rs) {
            $this->ajaxSuccess('返现成功！');
            exit();
        } else {
            $this->ajaxError('返现失败');
            exit();
        }
    }

    public function SellerCheck()
    {
        $id = $this->_get('id');
        $status = $this->_get('status');
        $User = M('seller');
        $where['id'] = $id;
        $rs = $User->where($where)->setField('check_time', time());
        $rs = $User->where($where)->setField('status', $status);
        
        $this->ajaxSuccess('操作成功！');
        exit();
    }

    public function UpdateCategoryField()
    {
        $fck = M('article_category');
        $id = I('post.id');
        
        $field = I('post.field');
        $value = I('post.value');
        
        $fck->where('id=' . $id)->setField($field, $value);
        
        $this->success('修改成功！');
    }

    function goods_fileupload()
    {
        $this->fileupload();
    }

    function goods_fileupdate()
    {
        $this->fileupdate();
    }

    function goods_filedel()
    {
        $this->filedel();
    }

    function goods_fileinit()
    {
        $goods_id = I('get.id', 0);
        
        $goods_albums = M('goods_albums')->where('goods_id=' . $goods_id)->select();
        
        $savePath = './Public/Uploads/';
        $file_db = "./upload/db.txt";
        $db = array();
        $rows = array();
        
        for ($i = 0; $i < count($goods_albums); $i ++) {
            
            $rows[] = array(
                'name' => $goods_albums[$i]['id'],
                'sort' => $i,
                'path' => str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods_albums[$i]['original_path']),
                'key' => $goods_albums[$i]['id'],
                'size' => 0
            );
        }

        function cmp($a, $b)
        {
            return $a['sort'] > $b['sort'] ? 1 : - 1;
        }
        usort($rows, 'cmp');
        
        echo json_encode($rows);
    }

    function category_fileinit()
    {
        $uid = $_SESSION[C('USER_AUTH_KEY')];
        $id = I('get.user_category_id', 0);
        $user_category_id = I('get.id', 0);
        
        $category_albums = M('category_albums')->where(' category_id=' . $id . ' AND  user_category_id=' . $user_category_id)->select();
        
        $savePath = './Public/Uploads/';
        $file_db = "./upload/db.txt";
        $db = array();
        $rows = array();
        
        for ($i = 0; $i < count($category_albums); $i ++) {
            
            $rows[] = array(
                'name' => $category_albums[$i]['id'],
                'sort' => $i,
                'path' => str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $category_albums[$i]['original_path']),
                'key' => $category_albums[$i]['id'],
                'size' => 0
            );
        }

        function cmp($a, $b)
        {
            return $a['sort'] > $b['sort'] ? 1 : - 1;
        }
        usort($rows, 'cmp');
        
        echo json_encode($rows);
    }

    function seller_fileinit()
    {
        $uid = $_SESSION[C('USER_AUTH_KEY')];
        $id = I('get.id', 0);
        
        $category_albums = M('seller_albums')->where(' seller_id=' . $id)->select();
        
        $savePath = './Public/Uploads/';
        $file_db = "./upload/db.txt";
        $db = array();
        $rows = array();
        
        for ($i = 0; $i < count($category_albums); $i ++) {
            
            $rows[] = array(
                'name' => $category_albums[$i]['id'],
                'sort' => $i,
                'path' => str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $category_albums[$i]['original_path']),
                'key' => $category_albums[$i]['id'],
                'size' => 0
            );
        }

        function cmp($a, $b)
        {
            return $a['sort'] > $b['sort'] ? 1 : - 1;
        }
        usort($rows, 'cmp');
        
        echo json_encode($rows);
    }

    public function pin_goods()
    {
        $category_id = $_POST['category_id'];
        $is_mobile = $_POST['is_mobile'];
        $keyword = $_POST['keyword'];
        $type = $_POST['type'];
        $city = $_POST['city'];
        
        $map = array();
        $map['_string'] = ' (  type=' . $type . ' and is_pin=1)  ';
        
        if ($is_mobile == 1) {
            $map['title'] = array(
                'like',
                '%' . $keyword . '%'
            );
            if ($category_id > 0) {
                $map['category_id'] = $category_id;
            }
            if ($type > 0) {
                $map['type'] = $type;
            }
        }
        
        $form = M('goods');
        
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->where($map)->count(); // 总页数
        
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $top = 10;
        if ($is_mobile == 1) {
            $top = 10000;
        }
        
        $list = $form->where($map)
            ->field($field)
            ->order(' id desc')
            ->page($p . ',' . $top)
            ->select();
        if ($is_mobile == 1) {
            foreach ($list as $i => $goods) {
                $list[$i]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img']);
                $sell_count = M('order_goods')->where(array(
                    'article_id' => $goods['id']
                ))->sum('quantity');
                IF ($sell_count == null) {
                    $sell_count = 0;
                }
                $list[$i]['sell_count'] = $sell_count;
                $percent = (int) $sell_count / $list[$i]['all_stock'] * 100;
                $percent = round($percent, 2);
                $list[$i]['percent'] = $percent;
                $pinstr = '';
                
                $goods = get_goods_detail($goods);
                
                $list[$i] = $goods;
            }
        }
        foreach ($list as $i => $goods) {
            $category = M('article_category')->where(array(
                'id' => $goods['category_id']
            ))->find();
            $list[$i]['category'] = $category['title'];
            $list[$i]['addtime_str'] = date("Y-m-d H:i:s", $goods["addtime"]);
            $list[$i]['type_str'] = '拼单';
            if ($list[$i]['type'] == 2) {
                
                $list[$i]['type_str'] = '拍卖';
            }
            if ($list[$i]['type'] == 3) {
                
                $list[$i]['type_str'] = '自营商城';
            }
        }
        
        $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 1) {
            $slider = ARRAY();
            $slider[0]['img'] = __ROOT__ . '/Public/Images/slides/1.png';
            $slider[1]['img'] = __ROOT__ . '/Public/Images/slides/2.png';
            $slider[2]['img'] = __ROOT__ . '/Public/Images/slides/3.png';
            $slider[3]['img'] = __ROOT__ . '/Public/Images/slides/5.png';
            $slider[4]['img'] = __ROOT__ . '/Public/Images/slides/6.png';
            
            $slider1 = ARRAY();
            $slider1[0]['img'] = __ROOT__ . '/Public/Images/slides/4.png';
            
            $slider2 = ARRAY();
            $slider2[0]['img'] = __ROOT__ . '/Public/Images/slides/7.png';
            $slider2[1]['img'] = __ROOT__ . '/Public/Images/slides/8.png';
            $slider2[2]['img'] = __ROOT__ . '/Public/Images/slides/9.png';
            
            $data = array();
            $data['data'] = $list;
            $data['slider'] = $slider;
            $data['slider1'] = $slider1;
            $data['slider2'] = $slider2;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            
            $this->display();
        }
    }

    // 压缩文件
    function zip()
    {
        $id = (int) $_GET['id'];
        $shop = M('seller')->where('id=' . $id)->find();
        
        $sn = M('user_terminal')->field('sn,sn_type')
            ->where(array(
            'sn' => $shop['sn']
        ))
            ->find();
        
        $txt_url = "info.txt";
        $myfile = fopen($txt_url, "w+") or die("Unable to open file!");
        $txt = "SN编号:" . $shop['sn'] . "\r\n" . "";
        
        fwrite($myfile, $txt);
        if ($sn != NULL) {
            $txt = "SN型号:" . $sn['sn_type'] . "\r\n" . "";
            fwrite($myfile, $txt);
        }
        
        $txt = "商户全称:" . $shop['title'] . "\r\n" . "";
        fwrite($myfile, $txt);
        $txt = "所属地区:" . $shop['province'] . $shop['city'] . $shop['area'] . $shop['address'] . "\r\n" . "";
        fwrite($myfile, $txt);
        $txt = "结算人姓名:" . $shop['user_name'] . "\r\n" . "";
        fwrite($myfile, $txt);
        $txt = "结算人手机号:" . $shop['mobile'] . "\r\n" . "";
        fwrite($myfile, $txt);
        $txt = "结算人身份证:" . $shop['card_no'] . "\r\n" . "";
        fwrite($myfile, $txt);
        $txt = "结算人身份证开始时间:" . $shop['card_begin_time'] . "\r\n" . "";
        fwrite($myfile, $txt);
        $txt = "结算人身份证到期时间:" . $shop['card_end_time'] . "\r\n" . "";
        fwrite($myfile, $txt);
        $txt = "银行卡号:" . preg_replace('# #', '', $shop['bank_card']) . "\r\n" . "";
        fwrite($myfile, $txt);
        $txt = "开户行:" . $shop['zhihang'] . "\r\n" . "";
        fwrite($myfile, $txt);
        $txt = "信用卡卡号:" . preg_replace('# #', '', $shop['credit_card_no']) . "\r\n" . "";
        fwrite($myfile, $txt);
        $txt = "信用卡预留手机号:" . $shop['credit_mobile'] . "\r\n" . "";
        fwrite($myfile, $txt);
        
        fclose($myfile);
        
        $files = array(
            $shop['card_img1'],
            $shop['card_img2'],
            $shop['card_img3'],
            $shop['card_img4'],
            $shop['card_img5'],
            $shop['card_img6'],
            $shop['card_img7'],
            $shop['card_img8'],
            $shop['card_img9'],
            $shop['card_img10'],
            $shop['card_img11'],
            $shop['card_img12'],
            $shop['card_img13'],
            $txt_url
        );
        // $files = array('upload/qrcode/1/1.jpg');
        $zipName = preg_replace('# #', '', $shop['id']) . '.zip';
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

    public function add_pos_func()
    {
        $min_fan_money = I("min_fan_money");
        $expire_day = I("expire_day");
        $user_name = $this->_post("user_name");
        $form = M('fck');
        $model = $form->where(array(
            'user_id' => $user_name
        ))->find();
        if ($user_name == '') {
            $this->error('请输入用户名！');
            return;
        }
        if ($model == NULL) {
            
            $this->error('此用户不存在');
            
            return;
        }
        
        if ($expire_day == '') {
            $this->error('请输入到期时间！');
            return;
        }
        $sn = $this->_post("sn");
        
        if ($sn == '') {
            $this->error('请上传sn编号！');
            return;
        }
        
        $sn = explode(",", $sn);
        
        foreach ($sn as $row) {
            
            if (! EMPTY($row)) {
                
                $count = M('user_terminal')->where(array(
                    'sn' => $row
                ))->count();
                if ($count > 0) {
                    $this->error($row . '编号已存在,请重新提交！');
                    return;
                }
            }
        }
        
        foreach ($sn as $row) {
            
            if (! EMPTY($row)) {
                $count = M('user_terminal')->where('sn="' . $row . '"')->count();
                if ($count == 0) {
                    $terminal['uid'] = $model['id'];
                    $terminal['use_uid'] = $model['id'];
                    $terminal['order_uid'] = $model['id'];
                    $terminal['price'] = 0;
                    $terminal['sn'] = $row;
                    $terminal['status'] = 1;
                    $terminal['expire_day'] = $expire_day;
                    
                    $expire_time = strtotime('+' . $expire_day . ' day');
                    
                    $expire_time_str = date('Y-m-d H:i:s', strtotime('+' . $expire_day . ' day'));
                    
                    $terminal['min_fan_money'] = $min_fan_money;
                    $terminal['expire_time'] = $expire_time;
                    $terminal['expire_time_str'] = $expire_time_str;
                    $terminal['add_time'] = time();
                    $terminal['type'] = 1;
                    M('user_terminal')->add($terminal);
                }
            }
        }
        set_terminal_sns($model['id']);
        
        if (count($_POST) > 0) {
            $_POST = array();
        }
        $bUrl = U('YouZi/adminTerminal');
        
        $this->success('下发成功！', $bUrl);
    }

    public function add_pos()
    {
        $uid = 0;
        $terminal_type = 0;
        
        $content = M('terminal_excel_record')->select();
        foreach ($content as $key => $rs) {
            $terminal_type = $rs['terminal_type'];
            if ($rs['is_record'] == 0) {
                $count = 0;
                if (! EMPTY($rs['order_no'])) {
                    $count = M('user_terminal')->where('order_no="' . $rs['order_no'] . '" and order_update=0  and uid=' . $rs['uid'])->count();
                }
                IF ($count >= 1) {
                    $rs['update_time'] = time();
                    
                    $user_terminal = M('user_terminal')->where('order_no="' . $rs['order_no'] . '" and order_update=0 ')
                        ->order(' id desc  ')
                        ->find();
                    
                    $rs['order_update'] = 1;
                    M('user_terminal')->where('id=' . $user_terminal['id'] . ' ')->save($rs);
                    
                    $seller = M('seller')->where('user_terminal_id=' . $user_terminal['id'])->find();
                    
                    if ($seller != null) {
                        M('seller')->where('id=' . $seller['id'])->setField('sn', $rs['sn']);
                    }
                } else {
                    $rs['add_time'] = time();
                    $rs['id'] = null;
                    $ret = M('user_terminal')->add($rs);
                }
            }
            $uid = $rs['uid'];
            set_terminal_sns($rs['uid']);
        }
        foreach ($content as $key => $rs) {
            M('terminal_excel_record')->delete($rs);
        }
        
        $model = M('fck')->where(array(
            'id' => $uid
        ))->find();
        $data = array();
        $data['is_push'] = 1;
        $data['push_msg'] = '您有' . count($content) . '台新的机器已下发,请注意查看';
        $data['terminal_num'] = count($content);
        $data['uid'] = $model['id'];
        push_msg($data, $model['id']);
        // 新增消息
        create_form($model['id'], $data['push_msg']);
        
        $re_model = M('fck')->where(array(
            'id' => $model['re_id']
        ))->find();
        if ($re_model != NULL) {
            // 给上级发
            $data = array();
            $data['is_push'] = 1;
            $data['push_msg'] = '您的盟友' . $model['user_name'] . '采购了' . count($content) . '台新的机器';
            $data['terminal_num'] = count($content);
            $data['uid'] = $re_model['id'];
            push_msg($data, $re_model['id']);
            
            // 新增消息
            create_form($re_model['id'], $data['push_msg']);
        }
        
        $bUrl = U('YouZi/adminTerminal', array(
            'terminal_type' => $terminal_type
        ));
        
        $this->success('下发成功！', $bUrl);
        
        $this->assign('sns', trim($sns));
    }

    public function del_pos()
    {
        $terminal_type = I('get.terminal_type');
        $uid = 0;
        
        $content = M('terminal_excel_record')->where('id>0')->delete();
        
        $bUrl = U('Shop/test_excel', array(
            'terminal_type' => $terminal_type
        ));
        $this->success('删除成功！', $bUrl);
    }

    // 会员表
    public function financeDaoChu_MM()
    {
        $time1 = $this->_post('time1');
        $time2 = $this->_post('time2');
        
        if (EMPTY($time1)) {
            $this->error('请选择开始时间！', '', false);
            exit();
        }
        
        if (EMPTY($time2)) {
            $this->error('请选择结束时间！', '', false);
            exit();
        }
        // 导出excel
        set_time_limit(0);
        $user_id = I('user_id', 0);
        header("Content-Type:   application/vnd.ms-excel");
        header("Content-Disposition:   attachment;   filename=Member.xls");
        header("Pragma:   no-cache");
        header("Content-Type:text/html; charset=utf-8");
        header("Expires:   0");
        
        $seller = M('seller'); // 奖金表
        
        $map = array();
        $map['T.id'] = array(
            'gt',
            0
        );
        $map['t.status'] = array(
            'eq',
            0
        );
        
        $map['t.active_time'] = array(
            'gt',
            strtotime($time1)
        );
        $map['t.active_time'] = array(
            'lt',
            strtotime($time1)
        );
        
        $field = 'T.*,G.active_money';
        $list = $seller->alias('t')
            ->join("xt_user_terminal AS g ON   g.sn = t.sn     ", 'left')
            ->where($map)
            ->field($field)
            ->select();
        $thismonth = $time1 . '到' . $time2;
        $title = $thismonth . "商户达标表 导出时间:" . date("Y-m-d   H:i:s");
        
        echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
        // 输出标题
        echo '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">' . $title . '</td></tr>';
        // 输出字段名
        echo '<tr  align=center>';
        echo "<td>序号</td>";
        echo "<td>商户编号</td>";
        echo "<td>商户名称</td>";
        echo "<td>入驻时间</td>";
        echo "<td>姓名</td>";
        echo "<td>电话</td>";
        echo "<td>银行</td>";
        echo "<td>卡号</td>";
        echo "<td>支行</td>";
        echo "<td>交易量</td>";
        echo "<td>达标时间</td>";
        echo "<td>返回押金金额</td>";
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
            echo '<td>' . chr(28) . $num . '</td>';
            echo "<td>" . $row['seller_no'] . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . date("Y-m-d H:i:s", $row['add_time']) . "</td>";
            echo "<td>" . $row['user_name'] . "</td>";
            echo "<td>" . $row['mobile'] . "</td>";
            echo "<td>" . $row['bank'] . "</td>";
            echo "<td>" . $row['bank_card'] . "</td>";
            echo "<td>" . $row['zhihang'] . "</td>";
            echo "<td>" . $row['trade_money'] . "</td>";
            echo "<td>" . date("Y-m-d H:i:s", $row['active_time']) . "</td>";
            echo "<td>" . $row['active_money'] . "</td>";
            echo '</tr>';
        }
        echo '</table>';
    }

    public function test_excel()
    {
        $terminal_type = I('post.terminal_type', 0);
        $this->assign('terminal_type', $terminal_type);
        $type = (int) $_GET['type'];
        $file = $_FILES[filexls][tmp_name];
        if ($type != 1) {
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
                $user_money = explode('|', $fee_rs['user_money']);
                foreach ($content as $key => $rs) {
                    $array = array();
                    $array['seller_no'] = trim($rs[14]);
                    $model = M('fck')->where(array(
                        'user_id' => trim($rs[0])
                    ))->find();
                    
                    if (! EMPTY($rs)) {
                        $sn = $rs[2];
                        
                        $count = M('user_terminal')->where('sn="' . $sn . '"')->count();
                        $terminal['is_record'] = 0;
                        if ($count > 0) {
                            $terminal['is_record'] = 1;
                        }
                        $terminal['uid'] = $model['id'];
                        $terminal['use_uid'] = $model['id'];
                        $terminal['order_uid'] = $model['id'];
                        $terminal['price'] = 0;
                        $terminal['sn'] = $sn;
                        $terminal['sn_type'] = $rs[1];
                        $terminal['status'] = 1;
                        $expire_day = $rs[3];
                        // $shop_expire_day = $rs[5];
                        $terminal['expire_day'] = $expire_day;
                        // $terminal['shop_expire_day'] = $shop_expire_day;
                        
                        $expire_time = strtotime('+' . $expire_day . ' day');
                        
                        $expire_time_str = date('Y-m-d H:i:s', strtotime('+' . $expire_day . ' day'));
                        
                        $fan_money = ($rs[5] == null ? 0 : $rs[5]);
                        $min_fan_money = ($rs[4] == null ? 0 : $rs[4]);
                        $terminal['fan_money'] = $fan_money;
                        $terminal['min_fan_money'] = $min_fan_money;
                        $terminal['expire_time'] = $expire_time;
                        $terminal['expire_time_str'] = $expire_time_str;
                        $terminal['add_time'] = time();
                        $terminal['type'] = 1;
                        $count = M('terminal_excel_record')->where('sn="' . $sn . '"')->count();
                        $order_no = $rs[6];
                        
                        $terminal['order_no'] = $order_no;
                        $terminal['award_money'] = $rs[7];
                        ;
                        $terminal['terminal_type'] = $terminal_type;
                        
                        if ($count == 0) {
                            if ($terminal['uid'] > 0) {
                                M('terminal_excel_record')->add($terminal);
                            }
                        }
                    }
                }
                
                // $content=M('trade_orders')->select();
                
                // $this->assign('list', $content);
            }
        }
        $map['uid'] = array(
            'gt',
            0
        );
        
        import("@.ORG.Page"); // 导入分页类
        $count = M('terminal_excel_record')->where($map)->count(); // 总页数
        $Page = new Page($count, 100000);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $content = M('terminal_excel_record')->where($map)
            ->page($p . ',' . '100000')
            ->select();
        
        $userids = '';
        
        foreach ($content as $key => $rs) {
            
            $fck = M('fck')->field('id, user_id,u_level,re_id')
                ->where('id=' . $rs['uid'])
                ->find();
            IF ($fck != NULL) {
                $user_name = $fck['user_id'];
            }
            
            $content[$key]['user_id'] = $user_name;
            
            $content[$key]['color'] = 'black';
            IF ($rs['is_record'] == 1) {
                $content[$key]['color'] = 'red';
            }
        }
        $userids = $userids . '0';
        
        $user = M('fck')->where('id  in (' . $userids . ')')->select();
        
        $this->assign('list', $content);
        
        $terminal_type = I('get.terminal_type', 0);
        $this->assign('terminal_type', $terminal_type);
        $this->display();
    }

    function init_trade_money()
    {
        $content = M('seller')->where(' seller_no>0 ')->select();
        
        foreach ($content as $key => $rs) {
            
            set_shop_trade_status(0, $rs['seller_no']);
        }
        $this->success('统计成功！');
        exit();
    }

    function init_terminal()
    {
        $content = M('user_terminal')->field('uid')
            ->group('uid')
            ->select();
        
        foreach ($content as $key => $rs) {
            set_terminal_sns($rs['uid']);
            set_out_terminal_ids($rs['uid']);
            set_seller_sns($rs['uid']);
            // set_terminal_sns($rs['uid']);
        }
        $this->success('统计成功！');
        exit();
    }

    PUBLIC function excel_terminal_del()
    {
        $PTid = I('get.id');
        // 删除会员
        $fck = M('terminal_excel_record');
        $rs = $fck->where('id=' . $PTid)->find();
        if ($rs) {
            
            $where['id'] = $PTid;
            $a = $fck->where($where)->delete();
            $bUrl = U('Shop/test_excel', array(
                'type' => 1
            ));
            // $this->_box(1,'删除成功！',$bUrl,1);
            $this->success('删除成功!', $bUrl);
        }
    }

    PUBLIC function init_seller_money()
    {
        $seller_no = I('get.seller_no');
        $auth_status = I('get.auth_status');
        // 删除会员
        $fck = M('seller');
        
        set_shop_trade_status(0, $seller_no);
        
        $bUrl = U('Shop/seller', array(
            'auth_status' => $auth_status
        ));
        // $this->_box(1,'删除成功！',$bUrl,1);
        $this->success('统计成功!', $bUrl);
    }
}
?>
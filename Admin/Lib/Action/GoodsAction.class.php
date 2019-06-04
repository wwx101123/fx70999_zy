<?php

class GoodsAction extends CommonAction
{

    function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        
        // $this->_inject_check(0); // 调用过滤函数
        
        $this->_Config_name(); // 调用参数
        
        header("Content-Type:text/html; charset=utf-8");
    }

    public function order_daifu()
    {
        $order_id = $this->_post("order_id");
        $user_name = $this->_post("user_name");
        if ($user_name == NULL) {
            $this->ajaxError('请输入用户名！');
            return;
        }
        
        $password = $this->_post("password");
        if ($password == NULL) {
            $this->ajaxError('请输入密码！');
            return;
        }
        $user = M('fck')->where(array(
            'user_id' => $user_name,
            'pwd1' => $password
        ))->find();
        
        if ($user == NULL) {
            $this->ajaxError('用户不存在！');
            return;
        }
        
        $form = M('orders');
        
        $list = $form->where('id in(' . $order_id . ')')->select();
        
        foreach ($list as $key1 => $vo) {
            
            $model = $form->where('id=' . $vo['id'])->find();
            if ($model['payment_status'] == 2) {
                
                $this->error('订单已支付，不能重复处理！');
                
                return;
            }
            $this->order_money($vo['payment_id'], $user, $model);
            
            $model['money_user_id'] = $user['id'];
            $model['status'] = 2;
            $model['express_status'] = 1;
            $model['payment_status'] = 2;
            $model['payment_time'] = time();
            $ret = $form->where('id=' . $vo['id'])->save($model);
        }
        
        $bUrl = __URL__ . '/order_list';
        $this->ajaxSuccess('订单代付成功！', $bUrl);
    }

    public function order_add()
    {
        if ($_POST) {
            $user_name = $this->_post("user_name");
            $goods_id = $this->_post("goods_id");
            $express = $this->_post("express");
            $express_no = $this->_post("express_no");
            $num = $this->_post("num");
            $form = M('fck');
            $user = $form->where(array(
                'user_id' => $user_name
            ))->find();
            if ($user == NULL) {
                $this->ajaxError('用户不存在！');
                return;
            }
            
            $model['order_no'] = "B" . date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            ; // 订单号B开头为商品订单
            $model['user_id'] = $user['id'];
            $model['user_name'] = $user['user_id'];
            $model['payment_id'] = 4;
            
            $model['express_status'] = 2;
            $model['payment_status'] = 2;
            $model['payment_time'] = time();
            $model['express_time'] = time();
            $model['confirm_time'] = time();
            $model['status'] = 2;
            $model['express'] = $express;
            $model['express_no'] = $express_no;
            
            // 订单总金额=实付商品金额+运费+支付手续费+税金
            // 购物积分,可为负数
            $model['add_time'] = time();
            // //商品详细列表
            // List<Model.order_goods> gls = new List<Model.order_goods>();
            $gls = ARRAY();
            
            $goods = M('goods')->where('id=' . $goods_id)->find();
            
            if (($goods['stock'] - $num) < 0) {
                
                $this->ajaxError('库存不足！');
                return;
            }
            
            $model['order_amount'] = $goods['price'] * $num;
            
            $result = M('orders')->add($model);
            
            $item['channel_id'] = 0;
            $item['article_id'] = $goods_id;
            $item['goods_id'] = 0;
            $item['goods_title'] = $goods['title'];
            $item['title'] = $goods['title'];
            $item['img_url'] = $goods['img'];
            $item['goods_price'] = $goods['price'];
            $item['real_price'] = $goods['price'];
            $item['quantity'] = $num;
            $item['spec_text'] = '';
            
            $item['order_id'] = $result;
            $item['user_id'] = $user['id'];
            
            M('order_goods')->add($item);
            
            // 减少库存
            M('goods')->where('id=' . $item['article_id'])->setDec('stock', $item['quantity']);
            
            $bUrl = __URL__ . '/order_list';
            $this->ajaxSuccess('创建成功', $bUrl);
        } else {
            
            $id = $this->_get("id");
            $model = M('goods')->where('id=' . $id)->find();
            
            $this->assign('vo', $model);
            
            $goods_list = M('goods')->where(' type=0 and  stock>0')->select();
            
            $this->assign('goods_list', $goods_list);
            $this->display();
        }
    }

    public function order_sn()
    {
        $sn = $this->_post("sn");
        $order_id = $this->_post("order_id");
        $form = M('orders');
        $model = $form->where('id=' . $order_id)->find();
        
        $model['sn'] = $sn;
        $model['sn_time'] = time();
        $ret = $form->where('id=' . $order_id)->save($model);
        
        if (! $ret) {
            $this->error('订单发货失败！');
            
            return;
        }
        
        $bUrl = __URL__ . '/order_list';
        $this->ajaxSuccess('sn编号上传成功！', $bUrl);
    }

    public function order_cancel()
    {
        $hot_id = $this->_post("hot_id");
        $order_id = $this->_post("order_id");
        $user_id = $this->_post("user_id");
        $cancel_reason = $this->_post("cancel_reason");
        $form = M('orders');
        $model = $form->where('id=' . $order_id)->find();
        
        if ($model['status'] == 3) {
            $this->ajaxError('订单已经完成，不能取消！');
            return;
        }
        $model['status'] = 4;
        $model['cancel_time'] = time();
        // $model['cancel_reason'] = $cancel_reason;
        $ret = $form->where('id=' . $order_id)->save($model);
        
        if (! $ret) {
            $this->ajaxError('订单取消失败！');
            
            return;
        }
        
        // $form->where('hot_id=' . $hot_id. ' AND user_id=' . $user_id)->delete();
        $bUrl = __URL__ . '/order_list';
        $this->ajaxSuccess('取消成功', $bUrl);
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
        
        $list = $form->where('id in(' . $order_id . ')')->select();
        
        foreach ($list as $key1 => $vo) {
            
            $model = $form->where('id=' . $vo['id'])->find();
            
            if ($model['status'] > 2 || $model['express_status'] == 2) {
                
                $this->error('订单已完成或已发货，不能重复处理！');
                
                return;
            }
            $express = $this->_post("express");
            $express_no = $this->_post("express_no");
            $sn = $this->_post("sn");
            $spanExpire = $this->_post("spanExpire");
            if ($express == '') {
                // $this->error('请输入快递公司！');
                // return;
            }
            if ($express_no == '') {
                // $this->error('请输入快递编号！');
                // return;
            }
            if ($sn == '') {
                // $this->error('请上传sn编号！');
                // return;
            }
            // $count = M('user_terminal')->where('sn IN (' . $sn . ')')->count();
            // if ($count > 0) {
            // $this->error('有sn编号已存在,请重新提交！');
            // return;
            // }
            
            $model['express'] = $express;
            $model['express_no'] = $express_no;
            $model['express_status'] = 2;
            $model['express_time'] = time();
            $ret = $form->where('id=' . $vo['id'])->save($model);
        }
        
        // $sn = explode(",", $sn);
        
        // foreach ($sn as $row) {
        
        // if (! EMPTY($row)) {
        // $count = M('user_terminal')->where('sn="' . $row . '"')->count();
        // if ($count == 0) {
        // $terminal['uid'] = $model['user_id'];
        // $terminal['use_uid'] = $model['user_id'];
        // $terminal['order_uid'] = $model['user_id'];
        // $terminal['price'] = $model['order_amount'];
        // $terminal['sn'] = $row;
        // $terminal['status'] = 1;
        // $terminal['expire_day'] = $spanExpire;
        // if ($spanExpire > 0) {
        // $expire_time = strtotime('+' . $spanExpire . ' day');
        
        // $expire_time_str = date('Y-m-d H:i:s', strtotime('+' . $spanExpire . ' day'));
        // }
        
        // $terminal['expire_time'] = $expire_time;
        // $terminal['expire_time_str'] = $expire_time_str;
        // $terminal['add_time'] = time();
        // M('user_terminal')->add($terminal);
        // }
        // }
        // }
        // set_terminal_sns($model['user_id']);
        
        // 推送数量
        // $user = M('fck')->where('id=' . $model['user_id'])->find();
        // $user = get_user_info($user, $user['id']);
        
        // $data = array();
        // $data['type'] = "update_order_num";
        // $data['order_num'] = $user;
        // $data['uid'] = $user['id'];
        
        // push_msg($data, $user['id']);
        
        // $data = array();
        // $data['is_push'] = 1;
        // $data['push_msg'] = '您的订单编号' . $model['order_no'] . '已发货,请注意查看';
        // $data['uid'] = $user['id'];
        // push_msg($data, $user['id']);
        
        $bUrl = __URL__ . '/order_list';
        $this->ajaxSuccess('订单发货成功！', $bUrl);
    }

    public function spec_edit()
    {
        if ($_POST) {
            $model = ARRAY();
            $model['channel_id'] = 0;
            $model['parent_id'] = 0;
            $model['title'] = $this->_post('title');
            $model['remark'] = htmlspecialchars_decode($this->_post('detail'));
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
            $this->success('提交成功！', $bUrl);
            
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

    public function dialog_express()
    {
        $express = M('express')->where('is_lock=0')->select();
        
        $this->assign('express', $express);
        
        $this->display();
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
        $is_mobile = I('post.is_mobile');
        $parent_id = I('post.parent_id', 0);
        $channel_id = 0;
        $map = array();
        $map['channel_id'] = $channel_id;
        IF ($parent_id > 0) {
            $map['parent_id'] = $parent_id;
        }
        if ($is_mobile == 1) {}
        
        $form = M('article_category');
        
        $field = '*,parent_id as pid,title as name';
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
        
        $newData = array();
        GetChilds($list, $newData, $parent_id, $channel_id);
        if ($is_mobile == 1) {
            foreach ($list as $i => $goods) {
                $list[$i]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img_url']);
                $list[$i]['picture'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $list[$i]['img_url'];
            }
        }
        $this->assign('list', $newData); // 数据输出到模板
        foreach ($list as $key => $item) {
            
            $list[$key]['label'] = $item['title'];
            $list[$key]['value'] = $item['title'];
            $list[$key]['id'] = $item['id'];
            
            $child = $form->where('parent_id=' . $item['id'])
                ->order('sort_id asc')
                ->select();
            foreach ($child as $i => $goods) {
                $child[$i]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $child[$i]['img_url']);
                $child[$i]['picture'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $child[$i]['img_url'];
            }
            $list[$key]['child'] = $child;
            
            if ($item['parent_id'] == 0) {
                $list[$key]['fid'] = $item['id'];
            }
            // $goods = M("goods")->alias('t')
            // ->join("xt_article_category AS g ON g.id = t.category_id ", 'LEFT')
            // ->where(' g.class_list like "%,' . $item['id'] . ',%" ')
            // ->field('t.id,t.title,t.img,t.price,t.stock')
            // ->select();
            
            // foreach ($goods as $key1 => $item1) {
            
            // $goods[$key1]['price'] = number_format($item1['price'], 0);
            
            // $goods[$key1]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $item1['img'];
            // $goods[$key1]['pid'] = $item['id'];
            // }
            // $list[$key]['goods'] = $goods;
        }
        $slide = array();
        $slide[0]['path'] = 'Public/Images/slides/dnzq.png';
        $slide[1]['path'] = 'Public/Images/slides/qmwk.png';
        // $slide[2]['path'] = 'Public/Images/slides/s9.png';
        if ($is_mobile == 1) {
            
            $currentId = M('article_category')->where('parent_id=0')
                ->order(' id desc')
                ->getField('id');
            
            $data = array();
            $data['currentId'] = $currentId;
            $data['slide'] = $slide;
            $data['data'] = $list;
            $data['current_count'] = count($list);
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function category_edit()
    {
        $is_mobile = $_POST['is_mobile'];
        
        // $this->_Admin_checkUser(); // 后台权限检测
        if (! IS_POST) {
            
            $id = $this->_get('id');
            $action = $this->_get('action');
            
            $rs = M('article_category')->where(array(
                'id' => $id
            ))
                ->field('*')
                ->find();
            
            $dt = M('article_category')->where(array(
                'channel_id' => 0
            ))->select();
            $channel_id = 0;
            $parent_id = 0;
            $newData = array();
            GetChilds($dt, $newData, $parent_id, $channel_id);
            $category = array();
            $item = array();
            $item['id'] = 0;
            $item['title'] = '无父级分类';
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
            $goods_images = $rs['slide'];
            if (! empty($goods_images)) {
                $goods_images = explode(",", $goods_images);
            }
            $list = array();
            
            foreach ($goods_images as $key => $rs1) {
                $item = array();
                $item['image_url'] = $rs1;
                $list[] = $item;
            }
            
            $this->assign('slide_list', $list);
            $this->assign('category', $category);
            if ($rs) {
                $this->assign('vo', $rs);
                unset($id, $rs);
            }
            $this->display('category_edit');
        } else {
            
            $form = M('article_category');
            $data['title'] = $this->_post('title');
            $data['parent_id'] = $this->_post('ddlParentId');
            $data['sort_id'] = $this->_post('sort_id');
            $data['img_url'] = $this->_post('img');
            $data['seo_title'] = $this->_post('seo_title');
            
            $img = $this->_post('goods_images2');
            
            $str = implode(",", $img);
            $newstr = substr($str, 0, strlen($str) - 1);
            $data['slide'] = $newstr;
            
            $data['content'] = $_POST['detail'];
            $data['id'] = $_POST['id'];
            $data['is_lock'] = 0;
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
                
                $data['update_time'] = time();
                $rs = M('article_category')->where('id=' . $data['id'])->save($data);
                UpdateChilds($data['id']);
            } else {
                
                $data['addtime'] = time();
                $rs = M('article_category')->add($data);
                
                if ($data['parent_id'] > 0) {
                    $model2 = M('article_category')->where('id=' . $data['parent_id'])->find();
                    $data['class_list'] = $model2['class_list'] . $rs . ",";
                    $data['class_layer'] = $model2['class_layer'] + 1;
                } else {
                    $data['class_list'] = "," . $rs . ",";
                    
                    $data['class_layer'] = 1;
                }
                $data['id'] = $rs;
                $data['update_time'] = time();
                
                $rs = M('article_category')->where('id=' . $rs)->save($data);
            }
            
            $goodsAblumsJsonStr = $this->_post('img');
            M('category_albums')->where('category_id=' . $data['id'] . '   and channel_id=0')->delete();
            for ($i = 0; $i < count($goodsAblumsJsonStr); $i ++) {
                $goods_albums = ARRAY();
                $goods_albums['category_id'] = $data['id'];
                $goods_albums['thumb_path'] = get_img_url($goodsAblumsJsonStr[$i]);
                $goods_albums['original_path'] = get_img_url($goodsAblumsJsonStr[$i]);
                $goods_albums['add_time'] = time();
                $goods_albums['user_category_id'] = 0;
                $goods_albums['channel_id'] = 0;
                M('category_albums')->add($goods_albums);
            }
            
            if (! $rs) {
                $this->error('编辑失败！');
                exit();
            } else {
                unset($form, $data);
                $url = __URL__ . '/category_list';
                $this->success('编辑成功！', $url);
            }
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

    public function daifu()
    {
        $is_mobile = I('is_mobile', 0);
        $id = $this->_get('id');
        if ($is_mobile == 1) {
            
            $id = $this->_post('id');
        }
        $map = array();
        $map['t.id'] = $id;
        $form = M('orders');
        $rs = $form->alias('t')
            ->join("xt_order_goods AS H ON   H.order_id = t.id ", 'LEFT')
            ->where($map)
            ->field('t.*,H.goods_price*h.quantity as all_price')
            ->find();
        $map = array();
        $map['order_id'] = $id;
        $list = M('order_goods')->where($map)->select();
        
        foreach ($list as $key => $vo) {
            $list[$key]['all_real_price'] = ($vo['real_price'] * $vo['quantity']);
            $list[$key]['img'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $vo['img_url'];
        }
        
        $rs['order_goods'] = $list;
        $rs['add_time_str'] = date("Y-m-d H:i:s", $rs["add_time"]);
        $rs['payment_time_str'] = date("Y-m-d H:i:s", $rs["payment_time"]);
        $rs['confirm_time_str'] = date("Y-m-d H:i:s", $rs["confirm_time"]);
        $rs['express_time_str'] = date("Y-m-d H:i:s", $rs["express_time"]);
        $rs['complete_time_str'] = date("Y-m-d H:i:s", $rs["complete_time"]);
        
        $user_terminal_count = M('user_terminal')->where('order_no="' . $rs['order_no'] . '"')->count();
        $rs['user_terminal_count'] = $user_terminal_count;
        $user_terminal = M('user_terminal')->where('order_no="' . $rs['order_no'] . '"')->select();
        
        $rs['user_terminal'] = $user_terminal;
        
        $this->assign('rs', $rs);
        
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
            $user_money = explode('|', $fee_rs['user_money']);
            $sns = '';
            foreach ($content as $key => $rs) {
                $sns = $sns . $rs[0] . ',';
            }
            
            $this->assign('sns', trim($sns));
        }
        if ($is_mobile == 1) {
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big84000.jpg';
            $swiperList[] = $array;
            
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big37006.jpg';
            $swiperList[] = $array;
            // #endif
            
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big39000.jpg';
            $swiperList[] = $array;
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big10001.jpg';
            $swiperList[] = $array;
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big25011.jpg';
            $swiperList[] = $array;
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big21016.jpg';
            $swiperList[] = $array;
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big99008.jpg';
            $swiperList[] = $array;
            
            $data = array();
            $data['swiperList'] = $swiperList;
            $data['re_pay_sub_title'] = C('re_pay_sub_title');
            $data['re_pay_title'] = C('re_pay_title');
            $data['data'] = $rs;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    // 前台新闻
    public function order_edit()
    {
        $is_mobile = I('is_mobile', 0);
        $id = $this->_get('id');
        if ($is_mobile == 1) {
            
            $id = $this->_post('id');
        }
        $map = array();
        $map['t.id'] = $id;
        $form = M('orders');
        $rs = $form->alias('t')
            ->join("xt_order_goods AS H ON   H.order_id = t.id ", 'LEFT')
            ->where($map)
            ->field('t.*,H.goods_price*h.quantity as all_price')
            ->find();
        $map = array();
        $map['order_id'] = $id;
        $list = M('order_goods')->where($map)->select();
        
        foreach ($list as $key => $vo) {
            $list[$key]['all_real_price'] = ($vo['real_price'] * $vo['quantity']);
        }
        
        $rs['order_goods'] = $list;
        $rs['add_time_str'] = date("Y-m-d H:i:s", $rs["add_time"]);
        $rs['payment_time_str'] = date("Y-m-d H:i:s", $rs["payment_time"]);
        $rs['confirm_time_str'] = date("Y-m-d H:i:s", $rs["confirm_time"]);
        $rs['express_time_str'] = date("Y-m-d H:i:s", $rs["express_time"]);
        $rs['complete_time_str'] = date("Y-m-d H:i:s", $rs["complete_time"]);
        
        $user_terminal_count = M('user_terminal')->where('order_no="' . $rs['order_no'] . '"')->count();
        $rs['user_terminal_count'] = $user_terminal_count;
        $user_terminal = M('user_terminal')->where('order_no="' . $rs['order_no'] . '"')->select();
        
        $rs['user_terminal'] = $user_terminal;
        
        $this->assign('rs', $rs);
        
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
            $user_money = explode('|', $fee_rs['user_money']);
            $sns = '';
            foreach ($content as $key => $rs) {
                $sns = $sns . $rs[0] . ',';
            }
            
            $this->assign('sns', trim($sns));
        }
        if ($is_mobile == 1) {
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big84000.jpg';
            $swiperList[] = $array;
            
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big37006.jpg';
            $swiperList[] = $array;
            // #endif
            
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big39000.jpg';
            $swiperList[] = $array;
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big10001.jpg';
            $swiperList[] = $array;
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big25011.jpg';
            $swiperList[] = $array;
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big21016.jpg';
            $swiperList[] = $array;
            $array = array();
            $array['id'] = 0;
            $array['type'] = 'image';
            $array['url'] = 'https://ossweb-img.qq.com/images/lol/web201310/skin/big99008.jpg';
            $swiperList[] = $array;
            
            $data = array();
            $data['swiperList'] = $swiperList;
            $data['re_pay_sub_title'] = C('re_pay_sub_title');
            $data['re_pay_title'] = C('re_pay_title');
            $data['data'] = $rs;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function order_list()
    {
        $UserID = $_GET['username'];
        $this->assign('username', $UserID);
        
        $type = $this->_get('type');
        $this->assign('type', $type);
        $map = array();
        
        if ($type == 2) {
            $map['payment_status'] = 0;
        }
        if ($type == 3) {
            $map['payment_status'] = 2;
            $map['express_status'] = 1;
        }
        if (! empty($UserID)) {
            
            $map['_string'] = "  ((G.user_id LIKE '%" . $UserID . "%' ) OR   (g.user_name LIKE '%" . $UserID . "%' ))  ";
        }
        $form = M('orders');
        $field = 't.*,g.user_id as order_user_id,g.user_name as order_user_name,H.goods_price*h.quantity as all_price,k.id as myshop_id,k.user_id as shop_user_id,k.user_name as shop_user_name';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->where($map)->count(); // 总页数
        
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id ", 'LEFT')
            ->join("xt_fck AS k ON   k.id = g.shop_id ", 'LEFT')
            ->join("xt_order_goods AS h ON   h.order_id = t.id ", 'LEFT')
            ->where($map)
            ->field($field)
            ->order('t.add_time desc, t.status asc,t.id desc')
            ->group('t.id')
            ->page($p . ',10')
            ->select();
        
        foreach ($list as $key => $vo) {
            $list[$key]['payment'] = get_order_payment($vo['payment_id']);
            $list[$key]['order_status'] = get_order_status($vo['id']);
            
            if (empty($vo["payment_time"])) {
                $list[$key]['paymenttime_str'] = '未付款';
            } else {
                $list[$key]['paymenttime_str'] = date("Y-m-d H:i:s", $vo["payment_time"]);
            }
            
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $vo["add_time"]);
            
            if ($vo['myshop_id'] == 1) {
                
                $list[$key]['shop_user_id'] = $vo['order_user_id'];
                $list[$key]['shop_user_name'] = $vo['order_user_name'];
            }
            // $list[$i]['seller_name'] = '无';
            // $list[$i]['seller_user_name'] = '无';
            // $list[$i]['seller_name'] = $vo['shop_user_id'];
            // $list[$i]['seller_user_name'] = $vo['shop_user_name'];
        }
        
        $this->assign('list', $list); // 数据输出到模板
        $this->display();
    }

    // 销售列表
    public function goods_detail_list()
    {
        $user_id = I('user_id', 0);
        $is_hot = I('is_hot', 0);
        $page_index = I('page_index', 1);
        $page_num = I('page_num', 10);
        $category_id = I('category_id', 118);
        $goods_type = I('goods_type', 0);
        $type = I('get.type', 0);
        $this->assign('type', $type);
        $is_mobile = $_POST['is_mobile'];
        $keyword = $_REQUEST['keyword'];
        $this->assign('keyword', $keyword);
        $order = I('order');
        if (EMPTY($order)) {
            $order = 'id desc ';
        }
        
        $map = array();
        $map['t.status'] = 1;
        $map['t.title'] = array(
            'like',
            '%' . $keyword . '%'
        );
        
        $map['type'] = 0;
        
        $form = M('goods');
        
        $field = 't.*,g.user_id as goods_user_id,g.user_name,ifnull(sum(h.quantity),0) as quantity';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->where($map)->count(); // 总页数
        
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        
        IF ($is_mobile == 1) {
            $p = $page_index;
        }
        $list = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id", 'LEFT')
            ->join("xt_order_goods AS h ON   h.article_id = t.id", 'LEFT')
            ->where($map)
            ->field($field)
            ->group('h.article_id')
            ->order(' ' . $order)
            ->page($p . ',' . $page_num)
            ->select();
        
        foreach ($list as $i => $goods) {
            $category = M('article_category')->where(array(
                'id' => $goods['category_id']
            ))->find();
            $list[$i]['category'] = $category['title'];
            $list[$i]['addtime_str'] = date("Y-m-d H:i:s", $goods["addtime"]);
            $percent = $goods['quantity'] / ($goods['quantity'] + $goods['stock']) * 100;
            $percent = round($percent, 2);
            $list[$i]['percent'] = $percent . '%';
            $sell_count = M('order_goods')->where(array(
                'article_id' => $goods['id']
            ))->sum('quantity');
            IF ($sell_count == null) {
                $sell_count = 0;
            }
            $list[$i]['sell_count'] = $sell_count;
        }
        
        $this->assign('list', $list); // 数据输出到模板
        
        IF ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['current_count'] = count($list);
            
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function Goods()
    {
        $shop_id = I('shop_id', 0);
        $user_id = I('user_id', 0);
        $is_hot = I('is_hot', 0);
        $page_index = I('page_index', 1);
        $page_num = I('page_num', 10);
        $category_id = I('category_id', 118);
        $goods_type = I('goods_type', 0);
        $type = I('get.type', 0);
        $this->assign('type', $type);
        $is_mobile = I('is_mobile', 0);
        $keyword = $_REQUEST['keyword'];
        $this->assign('keyword', $keyword);
        $order = I('order');
        $priceOrder = I('priceOrder');
        if (EMPTY($order)) {
            $order = 't.id desc ';
        } else {
            if ($order == 0) {
                
                $order = 't.id desc ';
            }
            if ($order == 1) {
                
                $order = '  t.addtime desc ';
            }
            if ($order == 2 && $priceOrder == 1) {
                
                $order = '  t.price asc ';
            }
            if ($order == 2 && $priceOrder == 2) {
                
                $order = '  t.price desc ';
            }
        }
        
        $map = array();
        $map['t.status'] = 1;
        $map['t.check_status'] = 0;
        $map['t.title'] = array(
            'like',
            '%' . $keyword . '%'
        );
        if ($is_mobile == 1) {
            
            $map['t.type'] = $goods_type;
            $map['t.stock'] = array(
                'egt',
                1
            );
            
            if ($category_id > 0) {
                $map['h.class_list'] = array(
                    'like',
                    '%,' . $category_id . ',%'
                );
            }
            if ($is_hot > 0) {
                $map['t.is_hot'] = $is_hot;
            }
            if ($user_id > 0) {
                $map['t.user_id'] = $user_id;
            }
            if (! empty($keyword)) {
                $item = array();
                $item['uid'] = $user_id;
                $item['goods_title'] = $keyword;
                
                $count = M('goods_seachar')->where($item)->count();
                if ($count == 0) {
                    $item['count'] = 1;
                    $item['add_time'] = time();
                    M('goods_seachar')->add($item);
                } else {
                    
                    M('goods_seachar')->where($item)->setInc('count', 1);
                }
            }
        } else {
            $map['type'] = $type;
        }
        $seller = array();
        IF ($shop_id > 0) {
            $seller = M('seller')->where(array(
                'id' => $shop_id
            ))->find();
            
            if ($seller['img'] != null) {
                $seller['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $seller['img'];
            } else {
                
                $seller['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/Public/Images/logo.png';
            }
            
            $seller['background'] = 'url(' . $seller['icon'] . ');background-size:100% 100%';
            $map['t.shop_id'] = $shop_id;
        }
        
        $form = M('goods');
        
        $field = 't.*,g.user_id as goods_user_id,g.user_name,h.title as category ';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id", 'LEFT')
            ->join("xt_article_category AS h ON   h.id = t.category_id", 'LEFT')
            ->where($map)
            ->count(); // 总页数
        
        $totalAmount = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id", 'LEFT')
            ->join("xt_article_category AS h ON   h.id = t.category_id", 'LEFT')
            ->where($map)
            ->sum('t.price');
        if ($totalAmount == null) {
            $totalAmount = 0;
        }
        
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        
        IF ($is_mobile == 1) {
            $p = $page_index;
        }
        $list = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id", 'LEFT')
            ->join("xt_article_category AS h ON   h.id = t.category_id", 'LEFT')
            ->where($map)
            ->field($field)
            ->order(' ' . $order)
            ->page($p . ',' . $page_num)
            ->select();
        if ($is_mobile == 1) {
            foreach ($list as $i => $goods) {
                $list[$i]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img']);
                $list[$i]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $list[$i]['img'];
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
                
                // $goods_zd = M('goods_zd')->where(array(
                // 'goods_id' => $list[$i]['id']
                // ))->select();
                
                // foreach ($goods_zd as $K => $item) {
                // if ($item['min'] <= $sell_count && $item['max'] > $sell_count) {
                // $gupiao_award = $item['gupiao_award'];
                // }
                // }
                
                $list[$i]['gupiao_award'] = (int) $gupiao_award;
                $list[$i]['all_fhb'] = $list[$i]['all_stock'] * $list[$i]['fhb'];
                
                $list[$i]['agent_bi'] = number_format($list[$i]['agent_bi'], 0);
                $list[$i]['agent_use'] = number_format($list[$i]['agent_use'], 0);
                $list[$i]['market_price'] = number_format($list[$i]['market_price'], 0);
                $list[$i]['price'] = number_format($list[$i]['price'], 0);
                $list[$i]['fhb'] = number_format($list[$i]['fhb'], 4);
            }
        }
        // foreach ($list as $i => $goods) {
        // $category = M('article_category')->where(array(
        // 'id' => $goods['category_id']
        // ))->find();
        // $list[$i]['category'] = $category['title'];
        // $list[$i]['addtime_str'] = date("Y-m-d H:i:s", $goods["addtime"]);
        // }
        
        $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 1) {
            $slider = ARRAY();
            
            $fee = M('fee');
            
            $goods_images = $fee->where('id=1')->getField('str29');
            
            if (! empty($goods_images)) {
                $goods_images = explode(",", $goods_images);
                
                // $list = array();
                
                foreach ($goods_images as $key => $rs) {
                    $item = array();
                    $item['img'] = $rs;
                    $item['src'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $rs;
                    $item['background'] = 'rgb(203, 87, 60)';
                    
                    $slider[] = $item;
                }
            }
            
            $slider1 = ARRAY();
            $slider1[0]['img'] = __ROOT__ . '/Public/Images/slides/4.png';
            
            $slider2 = ARRAY();
            $slider2[0]['img'] = __ROOT__ . '/Public/Images/slides/7.png';
            $slider2[1]['img'] = __ROOT__ . '/Public/Images/slides/8.png';
            $slider2[2]['img'] = __ROOT__ . '/Public/Images/slides/9.png';
            $category = M('article_category')->where(array(
                'parent_id' => 0,
                'is_lock' => 0
            ))->select();
            
            foreach ($category as $key => $goods) {
                $cate_list = M('article_category')->where('parent_id=' . $goods['id'])
                    ->order('   id desc')
                    ->limit(100)
                    ->select();
                $category[$key]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $goods['img_url'];
                
                $item_list = null;
                
                // $item_list = M('goods')->where('category_id=' . $goods['id'] . ' AND type=0 AND stock>0')
                // ->order(' id desc')
                
                // ->select();
                // foreach ($item_list as $key1 => $goods1) {
                // $item_list[$key1]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods1['img']);
                // $item_list[$key1]['url'] = 'goods_show.html';
                // $sell_count = 0;
                // $item_list[$key1]['sell_count'] = $sell_count;
                // $item_list[$key1]['percent'] = 90;
                // }
                
                $category[$key]['name'] = $goods['title'];
                $category[$key]['item_list'] = $item_list;
                $category[$key]['cate_list'] = $cate_list;
                $category[$key]['child'] = $cate_list;
            }
            
            // 获取头条新闻
            $form = M('form')->field('id,title')
                ->alias('t')
                ->where(" t.type=1  and t.user_id=0   ")
                ->select();
            $category_item = M('article_category')->where(array(
                'id' => $category_id
            ))->find();
            if ($category_item != NULL) {
                $cate_slider = $category_item['slide'];
                $cate_slider = explode(",", $cate_slider);
            }
            
            $video_img = $fee->where('id=1')->getField('str30');
            
            $dui_cart = M('dui_cart')->where(array(
                'user_id' => $user_id
            ))->select();
            $dui_cart_money = M('dui_cart')->where(array(
                'uid' => $user_id
            ))->sum('price*quantity');
            if ($dui_cart_money == null) {
                $dui_cart_money = 0;
            }
            
            $data = array();
            $data['seller'] = $seller;
            $data['data'] = $list;
            $data['totalAmount'] = $totalAmount;
            $data['current_count'] = $count;
            $data['all_count'] = $count;
            $data['category'] = $category;
            $data['form'] = $form;
            $data['dui_cart'] = $dui_cart;
            $data['dui_cart_money'] = $dui_cart_money;
            $data['cate_slider'] = $cate_slider;
            $data['video_img'] = $video_img;
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
        $func = $_POST['func'];
        
        // $this->_Admin_checkUser(); // 后台权限检测
        if (! IS_POST) {
            
            $id = $this->_get('id');
            $type = $this->_get('type');
            
            $rs = M('goods')->where(array(
                'id' => $id
            ))
                ->field('*')
                ->find();
            
            $dt = M('supplier')->select();
            
            $this->assign('supplier', $dt);
            $dt = M('seller')->where('  auth_status=0  ')->select();
            
            $this->assign('shop', $dt);
            
            $dt = M('article_category')->where(array(
                'call_index' => 'shop'
            ))->select();
            $channel_id = 0;
            $parent_id = 0;
            $newData = array();
            GetChilds($dt, $newData, $parent_id, $channel_id);
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
            
            $this->assign('goods_zd', $dt1);
            $list = array();
            if ($id > 0) {
                if (! empty($rs['content'])) {
                    $img_list = explode(",", $rs['content']);
                    
                    foreach ($img_list as $key => $rs1) {
                        $item = ARRAY();
                        $item['image_url'] = $rs1;
                        $list[] = $item;
                    }
                }
            }
            
            $this->assign('img_list', $list);
            
            $this->assign('type', $type);
            
            if ($rs) {
                $rs['hide_goods_spec_list'] = urlencode(json_encode($dt));
                $rs['goods_zd'] = $dt1;
                $this->assign('vo', $rs);
                unset($id, $rs);
                $this->display('goods_edit');
            } else {
                $rs['addtime'] = time();
                $rs['fhb'] = 0;
                $rs['goods_no'] = 'BD' . rand(1000000000, 2000000000);
                $this->assign('vo', $rs);
                // $this->error('没有该新闻！');
                // exit;
                if ($is_mobile == 1) {
                    
                    $data = array();
                    $data['data'] = $rs;
                    $data['status'] = 1;
                    $this->ajaxReturn($data);
                } else {
                    
                    $this->display('goods_edit');
                }
            }
        } else {
            if ($is_mobile == 1 && $func == 'show') {
                $id = $_POST['id'];
                $user_id = $_POST['user_id'];
                $rs = M('goods')->where(array(
                    'id' => $id
                ))
                    ->field('*')
                    ->find();
                
                $rs['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs['img']);
                $rs['img1'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs['img1']);
                $rs['img2'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs['img2']);
                $rs['img3'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs['img3']);
                
                $slider = ARRAY();
                IF (! EMPTY($rs['img1'])) {
                    
                    $item1 = array();
                    $item1['src'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $rs['img1'];
                    $slider[] = $item1;
                }
                
                IF (! EMPTY($rs['img2'])) {
                    $item1 = array();
                    $item1['src'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $rs['img2'];
                    $slider[] = $item1;
                }
                IF (! EMPTY($rs['img3'])) {
                    $item1 = array();
                    $item1['src'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $rs['img3'];
                    $slider[] = $item1;
                }
                
                $rs['slider'] = $slider;
                
                $rs['sell_count'] = 0;
                
                $rs['img'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $rs['img'];
                
                $rs['cart_img'] = 'http://' . $_SERVER['SERVER_NAME'] . '/Public/Images/tab-cart-current.png';
                
                $rs['addtime_str'] = date("Y-m-d H:i:s", $rs["addtime"]);
                
                $rs['content'] = htmlspecialchars_decode($rs['content']);
                $img_list = explode(",", $rs['content']);
                
                $rs['pc_content'] = str_replace('<p>', '<p style="line-height:0px">', $rs['pc_content']);
                $rs['pc_content'] = str_replace('<img', '<img style="width:100%" ', $rs['pc_content']);
                
                foreach ($img_list as $key => $rs1) {
                    $item = ARRAY();
                    $item['image_url'] = $rs1;
                    $list[] = $item;
                }
                $rs['image_list'] = $list;
                $goods_spec = M('article_goods_spec');
                $goods_spec_list = $goods_spec->where(array(
                    'article_id' => $id
                ))
                    ->order('spec_id asc')
                    ->select();
                $rs['goods_spec'] = $goods_spec_list;
                
                M('goods')->where(array(
                    'id' => $id
                ))->setInc('click', 1);
                $category_list = M('article_category')->where('channel_id=0 AND parent_id=0')
                    ->field('*')
                    ->select();
                
                $data = array();
                
                $seller = M("seller")->where("user_id=" . $rs['user_id'] . "")->find();
                
                if ($seller != NULL) {
                    
                    $goods_num = M('goods')->where(array(
                        'user_id' => $rs['user_id']
                    ))->count();
                    
                    $seller['fans_num'] = 1000;
                    $seller['goods_num'] = $goods_num;
                    $seller['score1'] = '9.50';
                    $seller['score2'] = '9.46';
                    $seller['score3'] = '9.68';
                    $seller['url'] = '/pages/shop/list?shopid=' . $seller['id'];
                    
                    $data['seller'] = $seller;
                }
                
                $form = M('cart');
                $cart['uid'] = $user_id;
                $cart['article_id'] = $rs['id'];
                $cart['goods_id'] = 0;
                
                $quantity = $form->where($cart)->sum('quantity');
                if ($quantity == null) {
                    $quantity = 0;
                }
                
                $rs['cartNum'] = $quantity;
                $cart = array();
                $cart['uid'] = $user_id;
                ;
                // $rs['pc_content'] = preg_replace('/src="///u','src="http://', $rs['pc_content'] );
                $quantity = $form->where($cart)->sum('quantity');
                IF ($quantity == NULL) {
                    $quantity = 0;
                }
                $item1 = array();
                $item1['uid'] = $user_id;
                $item1['add_time'] = time();
                $item1['goods_id'] = $rs['id'];
                M('goods_show')->add($item1);
                
                $data['category_list'] = $category_list;
                $data['data'] = $rs;
                $data['cart_count'] = $quantity;
                $data['status'] = 1;
                $this->ajaxReturn($data);
            } else {
                
                $form = M('goods');
                $data['category_id'] = $this->_post('ddlParentId');
                
                if ($data['category_id'] == 0) {
                    
                    if ($func == 'create_goods' && $is_mobile == 1) {
                        $this->ajaxError('请选择类别！');
                        exit();
                    } else {
                        $this->error('请选择类别！');
                        exit();
                    }
                }
                
                $data['supplier_id'] = $this->_post('ddlSupplierId');
                $data['shop_id'] = $this->_post('ddlShopId');
                $data['type'] = $this->_post('type');
                
                $data['title'] = $this->_post('title');
                if (empty($data['title'])) {
                    if ($func == 'create_goods' && $is_mobile == 1) {
                        $this->ajaxError('请输入标题！');
                        exit();
                    }
                }
                $data['content'] = I('detail_content');
                if ($func == 'create_goods' && $is_mobile == 1) {
                    
                    $data['check_status'] = 1;
                }
                // $this->error( $data['content'] );
                // exit();
                
                $str = implode(",", $data['content']);
                $newstr = substr($str, 0, strlen($str) - 1);
                $data['content'] = $newstr;
                $data['price'] = $this->_post('price');
                if ($func == 'create_goods' && $is_mobile == 1) {
                    if ($data['price'] == 0) {
                        $this->ajaxError('请输入价格！');
                        exit();
                    }
                }
                $data['stock'] = $this->_post('stock');
                
                $data['price'] = $this->_post('price');
                $data['market_price'] = $this->_post('market_price');
                IF ($is_mobile == 1) {
                    $data['status'] = 1;
                }
                $data['user_id'] = $this->_post('user_id');
                $data['user_id'] = $_SESSION[C('USER_AUTH_KEY')];
                
                $data['id'] = $this->_post('id');
                $data['img'] = $this->_post('img');
                $data['img1'] = $this->_post('img1');
                $data['img2'] = $this->_post('img2');
                $data['img3'] = $this->_post('img3');
                $data['type'] = $_POST['type'];
                $data['agent_bi'] = $_POST['agent_bi'];
                $data['agent_use'] = $_POST['agent_use'];
                $data['goods_no'] = $_POST['goods_no'];
                $data['sub_title'] = $_POST['sub_title'];
                $id = $data['id'];
                // $data['content'] = $this->_post('detail_content');
                
                if ($func == 'create_goods' && $is_mobile == 1) {
                    if (empty($data['img'])) {
                        $this->ajaxError('请上传封面图！');
                        exit();
                    }
                    $data['img'] = ($data['img']);
                } else {
                    
                    // $data['img'] = get_img_url($this->_post('img'));
                }
                if ($func == 'create_goods' && $is_mobile == 1) {
                    if (empty($data['img1'])) {
                        $this->ajaxError('请上传滚动图！');
                        exit();
                    }
                    $data['img1'] = ($data['img1']);
                } else {
                    // $data['img1'] = get_img_url(I('img1'));
                }
                if ($func == 'create_goods' && $is_mobile == 1) {
                    
                    $data['content'] = $this->_post('content_img1');
                    if (empty($data['content'])) {
                        $this->ajaxError('请上传详细图！');
                        exit();
                    }
                    if (empty($data['stock']) || $data['stock'] == 0) {
                        $this->ajaxError('库存必须大于0！');
                        exit();
                    }
                } else {
                    $data['pc_content'] = $_POST['pc_content'];
                }
                if ($data['id'] > 0) {
                    
                    $data['edittime'] = strtotime($this->_post('create_time'));
                    $rs = M('goods')->save($data);
                } else {
                    $data['goods_no'] = 'BD' . rand(1000000000, 2000000000);
                    $data['all_stock'] = $this->_post('stock');
                    $data['addtime'] = time();
                    if ($func == 'create_goods' && $is_mobile == 1) {
                        $data['check_status'] = 1;
                    }
                    ;
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
                $specStockQuantityArr = $this->_post("spec_stock_quantity");
                $sn_numArr = $this->_post("sn_num");
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
                        $article_goods['sell_price'] = $specSellPriceArr[$i];
                        $article_goods['sn_num'] = $sn_numArr[$i];
                        M('article_goods')->add($article_goods);
                    }
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
                if ($func == 'create_goods' && $is_mobile == 1) {
                    $user = M('fck')->where(array(
                        'id' => $user_id
                    ))
                        ->field('id')
                        ->find();
                    
                    $this->ajaxSuccess('编辑成功,等待后台审核！');
                    return;
                } else {
                    $this->success('编辑成功！', $url);
                    return;
                }
                // endregion
                
                if (! $rs) {
                    $this->error('编辑失败！');
                    exit();
                } else {
                    $url = __URL__ . '/Goods/type/' . $data['type'];
                    $this->success('编辑成功！', $url);
                }
            }
        }
    }

    public function GoodsCheck()
    {
        $id = $this->_get('id');
        $check_status = $this->_get('check_status');
        
        $goods = M("goods")->where("id=" . $id . "")->find();
        
        $User = M('goods');
        $where['id'] = $id;
        $rs = $User->where($where)->setField('check_status', $check_status);
        $rs = $User->where($where)->setField('check_time', time());
        if ($rs) {
            
            $user = M("fck")->field('id,user_id')
                ->where("id=" . $goods['user_id'] . "")
                ->find();
            set_user_goods_num($user);
            // 推送数量
            
            $fck_num = M('fck_num')->where('uid=' . $goods['user_id'])->find();
            
            $data = array();
            $data['type'] = "update_order_num";
            $data['order_num'] = $fck_num;
            
            $data['uid'] = $goods['user_id'];
            
            if ($check_status == 2) {}
            
            push_msg($data, $goods['user_id']);
            $txt = '审核通过';
            if ($check_status == 2) {
                $txt = '审核不通过';
            }
            $this->success($txt . '操作成功！');
            exit();
        } else {
            $this->error('操作失败');
            exit();
        }
    }

    public function myGoods()
    {
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 1000);
        $category_id = $_POST['category_id'];
        $is_mobile = I('is_mobile', 0);
        $keyword = I('post.keyword', '');
        $user_id = I('user_id', 82503);
        $check_status = I('get.check_status', 0);
        IF ($is_mobile == 1) {
            $check_status = I('post.check_status', 0);
        }
        
        $str = ' AND  1=1';
        $map = array();
        
        IF ($check_status >= 0) {
            
            $map['t.check_status'] = $check_status;
        }
        if ($is_mobile == 1) {
            // $map['status'] = 1;
            // $map['is_over'] = 0;
            // $map['title'] = array(
            // 'like',
            // '%' . $keyword . '%'
            // );
            // if ($category_id > 0) {
            // $map['category_id'] = $category_id;
            // }
            if ($user_id > 0) {
                $map['t.user_id'] = $user_id;
            }
        }
        
        $form = M('goods');
        
        $field = 't.*,g.user_id as goods_user_id,g.user_name ';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id", 'LEFT')
            ->where($map)
            ->count(); // 总页数
        
        $Page = new Page($count, $page_size);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id", 'LEFT')
            ->where($map)
            ->field($field)
            ->order(' t.addtime desc')
            ->page($page_index . ',' . $page_size)
            ->select();
        if ($is_mobile == 1) {
            foreach ($list as $i => $goods) {
                $list[$i]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img']);
                if (strpos($list[$i]['img'], 'http') !== false) {} else {
                    // $list[$i]['img'] = C('oss_url') . $list[$i]['img'];
                }
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
                // $list[$i]['price'] = number_format($list[$i]['all_crowd_price'], 2);
                $list[$i]['remain_end_time'] = strtotime('+' . $list[$i]['end_minute'] . ' minutes', $list[$i]['addtime']) - time();
            }
        }
        foreach ($list as $i => $goods) {
            $category = M('article_category')->where(array(
                'id' => $goods['category_id']
            ))->find();
            $list[$i]['category'] = $category['title'];
            $list[$i]['category_logo'] = $category['img_url'];
            $list[$i]['addtime_str'] = date("Y-m-d H:i:s", $goods["addtime"]);
            
            $user = M('fck')->where(array(
                'id' => $goods['user_id']
            ))->find();
            $list[$i]['user_name'] = $user['user_id'];
            
            // $list[$i] = get_goods_info($list[$i]);
            
            // 检测倒计时
            // check_crown($list[$i], $list[$i]['goods_crown']);
            $status_str = '已审核';
            if ($list[$i]['check_status'] == 1) {
                $status_str = '待审核';
            }
            if ($list[$i]['check_status'] == 2) {
                $status_str = '审核不通过';
            }
            $list[$i]['status_str'] = $status_str;
            
            $user = M('fck')->where(array(
                'id' => $goods['user_id']
            ))->find();
            
            $remain_end_time = timediff(time(), $goods['real_end_time']);
            
            $list[$i]['remain_end_time'] = $goods['real_end_time'] - time();
        }
        
        $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 1) {
            
            $data = array();
            $data['data'] = $list;
            $data['current_count'] = count($list);
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            
            $this->display();
        }
    }

    public function dui_cart_goods_delete()
    {
        $user_id = $_POST['user_id'];
        if ($user_id == 0) {
            $this->error('请先登录！', 'user.html', true);
            exit();
        }
        $is_mobile = $_POST['is_mobile'];
        $cart_ids = $_POST['cart_ids'];
        $article_id = $_POST['article_id'];
        $goods_id = $_POST['goods_id'];
        $quantity = $_POST['quantity'];
        $cart = M('dui_cart')->where('id in(' . $cart_ids . ' )')->delete();
        
        $sum_num = M('dui_cart')->where('uid=' . $user_id)->sum('quantity');
        if ($sum_num == NULL) {
            $sum_num = 0;
        }
        $sum_amount = M('dui_cart')->where('uid=' . $user_id)->sum('price');
        
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

    public function cart_goods_delete()
    {
        $user_id = $_POST['user_id'];
        if ($user_id == 0) {
            $this->error('请先登录！', 'user.html', true);
            exit();
        }
        $is_mobile = $_POST['is_mobile'];
        $cart_ids = $_POST['cart_ids'];
        $article_id = $_POST['article_id'];
        $goods_id = $_POST['goods_id'];
        $quantity = $_POST['quantity'];
        $cart = M('cart')->where('id in(' . $cart_ids . ' )')->delete();
        
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
        $payment_id = 2;
        $jsondata = array();
        $cart_ids = '';
        foreach ($cart as $key => $rs) {
            
            $goods = M('goods')->where('id=' . $rs['article_id'])->find();
            
            $cart[$key]['number'] = $rs['quantity'];
            $cart[$key]['stock'] = $goods['stock'];
            IF ($rs['goods_id'] > 0) {
                $goodsModel = M('article_goods')->where('id=' . $rs['goods_id'])->find();
                $cart[$key]['stock'] = $goodsModel['stock_quantity'];
            }
            
            $cart[$key]['title'] = $goods['title'];
            $cart[$key]['img_url'] = 'http://' . $_SERVER['SERVER_NAME'] . str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods['img']);
            $cart[$key]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods['img']);
            
            if ($rs['spec_text'] == null) {
                $cart[$key]['spec_text'] = '';
            }
            
            $total_quantity = $total_quantity + $rs['quantity'];
            $totalAmount = $totalAmount + $rs['price'] * $rs['quantity'];
            if ($goods['type'] == 1) {
                $payment_id = 3;
            }
            $jsondata[] = $cart[$key];
            $cart_ids .= $cart[$key]['id'] . ',';
        }
        $cart_ids .= '0';
        $payment = M('payment')->where('is_lock=0 ')->select();
        
        $user_addr_book = M('user_addr_book')->where('user_id=' . $user_id)->select();
        
        foreach ($payment as $key => $rs) {
            $payment[$key]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $rs['img_url'];
        }
        
        $data = array();
        $data['msg'] = '商品已成功添加到购物车！';
        $data['status'] = 1;
        $data['cart_ids'] = ($cart_ids);
        $data['jsondata'] = json_encode($jsondata);
        $data['data'] = $cart;
        $data['current_count'] = count($cart);
        $data['payment'] = $payment;
        $data['total_quantity'] = $total_quantity;
        $data['user_addr_book'] = $user_addr_book;
        $data['user_addr_book_count'] = count($user_addr_book);
        $data['totalAmount'] = $totalAmount;
        $data['total'] = $totalAmount;
        $this->ajaxReturn($data);
    }

    public function dui_cart_items()
    {
        $user_id = $_POST['user_id'];
        $cart = M('dui_cart')->where('uid=' . $user_id)->select();
        $total_quantity = 0;
        $totalAmount = 0;
        $payment_id = 2;
        $jsondata = array();
        $cart_ids = '';
        foreach ($cart as $key => $rs) {
            
            $goods = M('goods')->where('id=' . $rs['article_id'])->find();
            
            $cart[$key]['number'] = $rs['quantity'];
            $cart[$key]['stock'] = $goods['stock'];
            IF ($rs['goods_id'] > 0) {
                $goodsModel = M('article_goods')->where('id=' . $rs['goods_id'])->find();
                $cart[$key]['stock'] = $goodsModel['stock_quantity'];
            }
            
            $cart[$key]['title'] = $goods['title'];
            $cart[$key]['img_url'] = 'http://' . $_SERVER['SERVER_NAME'] . str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods['img']);
            $cart[$key]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods['img']);
            
            if ($rs['spec_text'] == null) {
                $cart[$key]['spec_text'] = '';
            }
            
            $total_quantity = $total_quantity + $rs['quantity'];
            $totalAmount = $totalAmount + $rs['price'] * $rs['quantity'];
            if ($goods['type'] == 1) {
                $payment_id = 3;
            }
            $jsondata[] = $cart[$key];
            $cart_ids .= $cart[$key]['id'] . ',';
        }
        $cart_ids .= '0';
        $payment = M('payment')->where('is_lock=0 ')->select();
        
        $user_addr_book = M('user_addr_book')->where('user_id=' . $user_id)->select();
        
        foreach ($payment as $key => $rs) {
            $payment[$key]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $rs['img_url'];
        }
        
        $data = array();
        $data['msg'] = '商品已成功添加到购物车！';
        $data['status'] = 1;
        $data['cart_ids'] = ($cart_ids);
        $data['jsondata'] = json_encode($jsondata);
        $data['data'] = $cart;
        $data['current_count'] = count($cart);
        $data['payment'] = $payment;
        $data['total_quantity'] = $total_quantity;
        $data['user_addr_book'] = $user_addr_book;
        $data['user_addr_book_count'] = count($user_addr_book);
        $data['totalAmount'] = $totalAmount;
        $data['total'] = $totalAmount;
        $this->ajaxReturn($data);
    }

    public function dui_cart_goods_add()
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
            $this->ajaxError('您提交的商品参数有误！');
            exit();
        }
        $fee = M('fee');
        $fee_rs = $fee->field('is_goods')->find();
        if ($fee_rs['is_goods'] == 0) {
            $this->ajaxError('对不起，系统暂时禁止购物！');
            exit();
        }
        $form = M('dui_cart');
        $data['uid'] = $user_id;
        $data['article_id'] = $article_id;
        $data['goods_id'] = $goods_id;
        
        // $form->where($data)->delete();
        $data['quantity'] = $quantity;
        
        $data['createtime'] = time();
        
        $rs = M('goods')->where(array(
            'id' => $article_id
        ))
            ->field('market_price')
            ->find();
        $price = $rs['market_price'];
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
        // $ret = check_goods_zd($article_id, $quantity);
        // IF ($ret) {
        // $this->error('超过商品区间！');
        // exit();
        // }
        
        $cart = M('dui_cart')->where('uid=' . $user_id . ' and article_id=' . $article_id . ' and goods_id=' . $goods_id)->find();
        if ($cart != null) {
            $cart['quantity'] = $cart['quantity'] + $quantity;
            
            $rs = $form->save($cart);
        } else {
            
            $rs = $form->add($data);
        }
        $sum_num = M('dui_cart')->where('uid=' . $user_id)->sum('quantity');
        $sum_amount = M('dui_cart')->where('uid=' . $user_id)->sum('price*quantity');
        $all_cart_num = M('dui_cart')->where('uid=' . $user_id)->sum('quantity');
        
        $data = array();
        $data['msg'] = '商品已成功添加到购物车！';
        $data['status'] = 1;
        $data['all_cart_num'] = $all_cart_num;
        $data['quantity'] = $sum_num;
        $data['amount'] = $sum_amount;
        $data['url'] = 'cart.html';
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
            $this->ajaxError('您提交的商品参数有误！');
            exit();
        }
        $fee = M('fee');
        $fee_rs = $fee->field('is_goods')->find();
        if ($fee_rs['is_goods'] == 0) {
            $this->ajaxError('对不起，系统暂时禁止购物！');
            exit();
        }
        $form = M('cart');
        $data['uid'] = $user_id;
        $data['article_id'] = $article_id;
        $data['goods_id'] = $goods_id;
        
        // $form->where($data)->delete();
        $data['quantity'] = $quantity;
        
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
        // $ret = check_goods_zd($article_id, $quantity);
        // IF ($ret) {
        // $this->error('超过商品区间！');
        // exit();
        // }
        
        $cart = M('cart')->where('uid=' . $user_id . ' and article_id=' . $article_id . ' and goods_id=' . $goods_id)->find();
        if ($cart != null) {
            $cart['quantity'] = $cart['quantity'] + $quantity;
            
            $rs = $form->save($cart);
        } else {
            
            $rs = $form->add($data);
        }
        $sum_num = M('cart')->where('uid=' . $user_id . ' and article_id=' . $article_id . ' and goods_id=' . $goods_id)->sum('quantity');
        $sum_amount = M('cart')->where('uid=' . $user_id . ' and article_id=' . $article_id . ' and goods_id=' . $goods_id)->sum('price');
        $all_cart_num = M('cart')->where('uid=' . $user_id)->sum('quantity');
        
        $data = array();
        $data['msg'] = '商品已成功添加到购物车！';
        $data['status'] = 1;
        $data['all_cart_num'] = $all_cart_num;
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
            
            $this->ajaxError('对不起，传输参数不正确');
            exit();
        }
        // 查询商品信息
        $goodsModel = M('article_goods')->where('article_id=' . $article_id . ' AND spec_ids="' . $spec_ids . '"')->find();
        if ($goodsModel == null) {
            
            $this->ajaxError($spec_ids . '对不起，暂无查到商品信息' . $article_id);
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
            $this->ajaxError('您提交的商品参数有误！', '', true);
            exit();
        }
        $fee = M('fee');
        $fee_rs = $fee->field('is_goods')->find();
        if ($fee_rs['is_goods'] == 0) {
            $this->ajaxError('对不起，系统暂时禁止购物！');
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

    public function dui_cart_goods_update()
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
            $this->ajaxError('您提交的商品参数有误！', '', true);
            exit();
        }
        $fee = M('fee');
        $fee_rs = $fee->field('is_goods')->find();
        if ($fee_rs['is_goods'] == 0) {
            $this->ajaxError('对不起，系统暂时禁止购物！');
            exit();
        }
        $form = M('dui_cart');
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
        
        $cart = M('dui_cart')->where('uid=' . $user_id . ' and article_id=' . $article_id . ' and goods_id=' . $goods_id)->find();
        if ($cart != null) {
            $cart['quantity'] = $quantity;
            
            $rs = $form->save($cart);
        }
        $sum_num = M('dui_cart')->sum('quantity');
        $sum_amount = M('dui_cart')->sum('price');
        
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
        $user_addr_book = M('user_addr_book')->field('*,user_name as name,area as addressName')
            ->where('user_id=' . $user_id)
            ->select();
        
        foreach ($user_addr_book as $key => $value) {
            $user_addr_book[$key]['default'] = 'false';
            $user_addr_book[$key]['address'] = $value['province'] . $value['city'] . $value['area'];
            IF ($value['is_default'] == 1) {
                
                $user_addr_book[$key]['default'] = 'true';
            }
        }
        $data = array();
        $data['msg'] = '获取成功';
        $data['status'] = 1;
        $data['data'] = $user_addr_book;
        $this->ajaxReturn($data);
    }

    public function order_save()
    {
        $fee = M('fee');
        $fee_rs = $fee->field('is_goods,min_limit_money,i1')->find();
        if ($fee_rs['is_goods'] == 0) {
            $this->ajaxError('对不起，系统暂时禁止购物！');
            exit();
        }
        
        // 获取传参信息===================================
        $hideGoodsJson = $_POST['jsondata'];
        
        $goodsList = json_decode($hideGoodsJson);
        
        $pin_order_id = I('post.pin_order_id', 0);
        $hot_id = $_POST["hot_id"];
        $is_daifu = $_POST["is_daifu"];
        
        // 获取商品JSON数据
        $book_id = $_POST["book_id"];
        $payment_id = $_POST["payment_id"];
        $is_give = $_POST["is_give"];
        $express_id = $_POST["express_id"];
        $is_invoice = $_POST["is_invoice"];
        $accept_name = $_POST["accept_name"];
        $pid = $_POST["pid"];
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
        $goods_order_type = $_POST["goods_order_type"];
        $is_pin = $_POST["is_pin"];
        
        if ($hot_id == 0) {
            // $this->error('对不起，自营商城暂未开放下单！');
            // exit();
        }
        // //检查传参信息===================================
        if (empty($hideGoodsJson)) {
            $this->ajaxError('对不起，无法获取商品信息！');
            exit();
        }
        // if (express_id == 0)
        // {
        // context.Response.Write("{\"status\":0, \"msg\":\"对不起，请选择配送方式！\"}");
        // return;
        // }
        
        if ($payment_id == 0) {
            $this->ajaxError('对不起，请选择支付方式！');
            exit();
        }
        
        // $model.express = new BLL() . express() . GetModel(express_id);
        // if (expModel == null)
        // {
        // context.Response.Write("{\"status\":0, \"msg\":\"对不起，配送方式不存在或已删除！\"}");
        // return;
        // }
        
        // //检查支付方式
        $payment = M('payment')->where('id=' . $payment_id)->find();
        if ($payment == null) {
            $this->ajaxError('对不起，支付方式不存在或已删除！');
            exit();
        }
        if ($is_pin == 0) {
            // //检查收货人
            if (empty($accept_name)) {
                
                $this->ajaxError('对不起，请输入收货人姓名！');
                exit();
            }
            // //检查手机和电话
            if (empty($mobile)) {
                
                $this->ajaxError('对不起，请输入收货人联系电话或手机！');
                exit();
            }
            // //检查地区
            if (empty($area)) {
                $this->ajaxError('对不起，请选择您所在的省市区！');
                exit();
            }
            // //检查地址
            if (empty($address)) {
                $this->ajaxError('对不起，请输入详细的收货地址！');
                exit();
            }
        }
        // //如果开启匿名购物则不检查会员是否登录
        $user_id = $_POST["user_id"];
        ;
        $user_group_id = 0;
        $user_name = '';
        // //检查用户是否登录
        $userModel = M('fck')->where('id=' . $user_id)->find();
        if ($userModel != null) {
            if ($userModel['is_pay'] == 0) {
                $this->ajaxError('对不起，正在审核中,不可以下单！');
                exit();
            }
            
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
            $this->ajaxError('对不起，用户尚未登录或已超时！');
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
        
        $field = 'price';
        // if ($userModel['sex'] == '男') {
        // $field = 'boy_price AS sell_price';
        // }
        // if ($userModel['sex'] == '女') {
        // $field = 'girl_price AS sell_price';
        // }
        
        foreach ($goodsList as $key => $rs) {
            
            $total_quantity = $total_quantity + $rs->quantity;
            if ($rs->goods_id > 0) {
                $goods_item = M('article_goods')->field('sell_price')->find($rs->goods_id);
                
                $payable_amount = $payable_amount + $goods_item['sell_price'] * $rs->quantity;
                $real_amount = $real_amount + $goods_item['sell_price'] * $rs->quantity;
            } else {
                $goods = M('goods')->field($field)->find($rs->article_id);
                $payable_amount = $payable_amount + $goods['price'] * $rs->quantity;
                $real_amount = $real_amount + $goods['price'] * $rs->quantity;
            }
            
            $goodsTotal['total_quantity'] = $total_quantity;
            $goodsTotal['real_amount'] = $real_amount;
            $goodsTotal['payable_amount'] = $payable_amount;
            $goodsTotal['total_point'] = $total_point;
        }
        
        // //保存订单=======================================
        
        $model['order_no'] = "B" . date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        ; // 订单号B开头为商品订单
        $model['user_id'] = $user_id;
        $model['user_name'] = $user_name;
        $model['payment_id'] = $payment_id;
        $model['order_type'] = $goods_order_type;
        $model['is_give'] = $is_give;
        if ($goods_order_type != 1) {
            if ($is_give == 1 && $payment_id == 1) {
                $this->ajaxError('对不起，赠送和到付只能选择一项！');
                exit();
            }
            if ($is_give == 1) {
                $model['give_user_id'] = $userModel['re_id'];
                
                if ($userModel['limit_money'] > 0) {
                    $this->ajaxError('对不起，您不满足赠送条件！');
                    exit();
                }
                
                $item = M('orders')->where('user_id=' . $userModel['id'] . ' and is_give=1 and status=1 ')->find();
                
                if ($item != null) {
                    $this->error('对不起，您当前有赠送订单,不能再次赠送！');
                    exit();
                }
            } else {
                $item = M('orders')->where('user_id=' . $userModel['id'] . ' and is_give=0 and payment_id=0')
                    ->order('id desc ')
                    ->find();
                
                if ($item != null) {
                    if ($item['status'] != 2 && $item['status'] != 4) {
                        
                        // $this->error('对不起，您当前有抢购订单未完成,不能再次抢购！');
                        // exit();
                    }
                }
                
                $item = M('orders')->where('user_id=' . $userModel['id'] . ' and is_give=0 and payment_id=1')
                    ->order('id desc ')
                    ->find();
                
                if ($item != null) {
                    if ($item['status'] != 3) {
                        
                        // $this->error('对不起，您当前有抢购代付订单未完成,不能再次抢购！');
                        // exit();
                    }
                }
            }
            $item = M('orders')->where('user_id=' . $userModel['id'] . ' and is_give=1 and status=1 ')->find();
            
            if ($item != null) {
                $this->ajaxError('对不起，您当前有赠送订单,不能再次下单！');
                exit();
            }
        }
        
        $model['is_daifu'] = $is_daifu;
        $model['pid'] = $pin_order_id;
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
        // $model['pid'] = $pid;
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
        if ($goods_order_type != 1) {
            if ($userModel['limit_money'] > $fee_rs['min_limit_money']) {
                $this->ajaxError('对不起，您当前' . C('limit_money') . '大于' . $fee_rs['min_limit_money'] . ',不能下单！');
                exit();
            }
        }
        foreach ($goodsList as $k => $rs) {
            
            $goods = M('goods')->where('id=' . $rs->article_id)->find();
            $goods_id = $goods['id'];
            $goodsList[$k]->spec_text = '';
            $goodsList[$k]->sell_price = $goods['price'];
            $goodsList[$k]->user_price = $goods['price'];
            $goodsList[$k]->crowd_price = $goods['crowd_price'];
            $goodsList[$k]->every_crowd_price = $goods['every_crowd_price'];
            
            $goodsList[$k]->point = 0;
            
            if ($rs->goods_id > 0) {
                $goods_item = M('article_goods')->where('id=' . $rs->goods_id)->find();
                $StockQuantity = $goods_item['stock_quantity'];
                $goodsList[$k]->goods_no = $goods_item['goods_no'];
                $goodsList[$k]->spec_text = $goods_item['spec_text'];
                $goodsList[$k]->sell_price = $goods_item['sell_price'];
                $goodsList[$k]->user_price = $goods_item['sell_price'];
                $goodsList[$k]->crowd_price = $goods_item['crown_price'];
            } else {
                $StockQuantity = $goods['stock'];
                
                $goodsList[$k]->goods_no = $goods['goods_no'];
                $goodsList[$k]->sell_price = $goods['price'];
                $goodsList[$k]->user_price = $goods['price'];
            }
            $goodsList[$k]->goods_title = $goods['title'];
            $goodsList[$k]->title = $goods['title'];
            $goodsList[$k]->img_url = $goods['img'];
            $goodsList[$k]->hot_id = $rs->hot_id;
            
            if (((int) $StockQuantity < ($rs->quantity))) {
                
                $this->ajaxError('订单中某个商品库存不足，请修改重试！');
                exit();
            }
            if ($goods_order_type != 1) {
                if ($is_give == 0) {
                    
                    $has_order_goods = M('order_goods')->where('article_id=' . $rs->article_id . ' AND EXISTS(SELECT ID FROM xt_orders WHERE xt_orders.ID=XT_order_goods.order_id and xt_orders.is_give=0)')->find();
                    
                    $has_order_goods = M('goods')->where('id=' . $rs->article_id . ' AND  stock=0 ')->find();
                    
                    if ($has_order_goods != NULL) {
                        $goods = M('goods')->where('id=' . $rs->article_id)->setField('is_lock', 1);
                        M('goods_hot')->where('goods_id=' . $rs->article_id)->setField('is_lock', 1);
                        $this->ajaxError('商品已抢购,不能再次抢购！');
                        exit();
                    }
                }
            }
        }
        $model['status'] = 1;
        $model['confirm_time'] = time();
        
        $order_amount = $model['order_amount'];
        if ($goods_order_type == 1 && $is_daifu == 0) {
            $this->order_money($payment_id, $userModel, $model);
            IF ($payment_id == 9) {
                $this->ajaxError('准备代付！');
                exit();
            }
        }
        
        $model['goods_hot_id'] = $hot_id;
        if ($is_daifu == 0) {
            if ($payment_id == 1 || $payment_id == 2 || $payment_id == 3) {
                
                $model['express_status'] = 1;
                $model['payment_status'] = 2;
                $model['payment_time'] = time();
                $model['status'] = 2;
            }
        }
        $model['shop_id'] = $userModel['shop_id'];
        
        $shopModel = M('fck')->field('wx_img,zfb_img')->find($model['shop_id']);
        
        if ($is_pin == 0) {
            if (EMPTY($shopModel['wx_img']) && EMPTY($shopModel['zfb_img'])) {
                // $this->error('合伙人未上传支付宝或者微信二维码,请联系他！'.$goods_order_type);
                // exit();
            }
        }
        $model['all_crowd_price'] = 0;
        $result = M('orders')->add($model);
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
            
            $item['hot_id'] = $rs->hot_id;
            $item['order_id'] = $result;
            $item['user_id'] = $user_id;
            
            M('order_goods')->add($item);
            M('orders')->where('id=' . $result)->setInc('all_crowd_price', $item['quantity'] * $item['crowd_price']);
            M('orders')->where('id=' . $result)->setField('crowd_id', $crowd_id['id']);
            
            // 更新订单的商家是谁
            IF ($goods_order_type == 1) {
                $goods = M('goods')->field('shop_id')
                    ->where('id=' . $item['article_id'])
                    ->find();
                M('orders')->where('id=' . $result)->setField('goods_shop_id', $goods['shop_id']);
            }
            $model['all_crowd_price'] = 0;
            $cart = M('cart')->where('uid=' . $user_id . ' and article_id=' . $rs->article_id . ' and goods_id=' . $rs->goods_id)->delete();
            
            // 减少库存
            if ($rs->goods_id > 0) {
                M('article_goods')->where('id=' . $item['goods_id'] . ' and article_id=' . $item['article_id'])->setDec('stock_quantity', $item['quantity']);
                $stock_quantity = M('article_goods')->where('  article_id=' . $item['article_id'])->sum('quantity');
                M('goods')->where('id=' . $item['article_id'])->setDec('stock', $item['quantity']);
            } else {
                M('goods')->where('id=' . $item['article_id'])->setDec('stock', $item['quantity']);
            }
            
            if ($is_give == 0) {
                M('goods_crowd_users')->where('goods_id=' . $item['article_id'] . ' and hot_id=' . $rs->hot_id)->setField('order_id', $item['order_id']);
                M('goods_crowd_users')->where('goods_id=' . $item['article_id'] . ' and hot_id=' . $rs->hot_id)->setField('goods_item_id', $rs->goods_id);
                M('goods_crowd_users')->where('goods_id=' . $item['article_id'] . ' and hot_id=' . $rs->hot_id)->setField('goods_price', $rs->sell_price);
                M('goods_crowd_users')->where('goods_id=' . $item['article_id'] . ' and hot_id=' . $rs->hot_id)->setField('goods_crowd_price', $rs->crowd_price);
            }
            // 给奖励
            $goods = M('goods')->where("id='" . $item['article_id'] . "'")->find();
            
            user_award($userModel, $item['quantity'] * $goods['price'] * $goods['agent_use'], $userModel['user_id']);
            
            IF ($payment_id == 3) {
                
                // copy_goods($goods, $userModel['id'], $item['quantity']);
            }
            // if ($payment_id == 2) {
            $money_count = $item['quantity'] * $goods['price'] * $fee_rs['i1'];
            give_agent_cash($money_count, $userModel['id'], $userModel['user_id']);
            // }
        }
        
        $sum_num = M('cart')->where('uid=' . $user_id)->sum('quantity');
        if ($sum_num == NULL) {
            $sum_num = 0;
        }
        
        $form = M('order_goods');
        $map = array();
        $map['order_id'] = $result;
        
        // if ($payment_id == 1 || $payment_id == 2) {
        
        // $order = M('orders')->where('id=' . $result)->find();
        // IF ($order['payment_status'] == 2) {
        // // 积分支付下发虚拟机器
        // create_order_terminal($order, 0);
        // }
        // }
        
        // 升级检测
        user_level_check($userModel['re_id']);
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
            
            if ($payment_id == 2) {}
        }
        
        // 下架抢购商品
        $goods_hot = M('goods_hot');
        
        // 推送数量
        $user = M('fck')->where('id=' . $model['user_id'])->find();
        $user = get_user_info($user, $user['id']);
        
        $data = array();
        $data['type'] = "update_order_num";
        $data['order_num'] = $user;
        $data['uid'] = $user['id'];
        
        push_msg($data, $user['id']);
        
        init_order_read($user['id'], $result);
        
        $data = array();
        $data['msg'] = '恭喜您，订单已成功提交';
        $data['info'] = '恭喜您，订单已成功提交';
        $data['status'] = 1;
        $data['cart_num'] = $sum_num;
        $data['zfb_img'] = $shopModel['zfb_img'];
        $data['wx_img'] = $shopModel['wx_img'];
        $data['is_shopping'] = 1;
        $data['goods_order_type'] = $goods_order_type;
        $payment_id = $_POST['payment_id'];
        $data['payment_id'] = $payment_id;
        $data['order_no'] = $model['order_no'];
        
        if ($payment_id == 2) {
            $data['url'] = '/pages/money/paySuccess?id=' . $result;
        }
        if ($is_daifu == 1) {
            $data['url'] = '/pages/money/help_pay?id=' . $result;
        }
        $this->ajaxReturn($data);
    }

    public function set_user_addr_book_default()
    {
        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        M('user_addr_book')->where('user_id=' . $user_id)->setField('is_default', 0);
        M('user_addr_book')->where('id=' . $id)->setField('is_default', 1);
        
        $data = array();
        $data['msg'] = '设置成功！';
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    public function user_address_edit()
    {
        $user_id = $_POST['user_id'];
        // 检查用户是否登录
        $userModel = M('fck')->find($user_id);
        
        if ($userModel == null) {
            $this->ajaxError('对不起，用户尚未登录或已超时！');
            exit();
        }
        $id = $_POST["id"];
        if ($id > 0) {
            
            $user_addr_book = M('user_addr_book')->where('id=' . $id . '  ')->find();
            
            if ($user_addr_book == null) {
                
                $this->ajaxError('对不起，收货地址不存在或已删除！');
                exit();
            }
        }
        
        $street = $this->_post("street");
        $accept_name = $this->_post("txtAcceptName");
        $province = $this->_post("txtProvince");
        $city = $this->_post("txtCity");
        $area = $this->_post("txtArea");
        $address = $this->_post("txtAddress");
        $mobile = $this->_post("txtMobile");
        $telphone = $this->_post("txtTelphone");
        $email = $this->_post("txtEmail");
        $post_code = $this->_post("txtPostCode");
        $is_default = $this->_post("is_default");
        $default = $this->_post("default");
        if ($default) {
            $is_default = 1;
        }
        // 校检验证码
        
        // 检查收件人
        if (Empty($accept_name)) {
            $this->ajaxError('对不起，请输入收件人姓名！');
            exit();
        }
        // 检查省市区
        if (Empty($area)) {
            $this->ajaxError('对不起，请选择您所在的省市区！' . $area);
            return;
        }
        // 检查手机
        if (Empty($mobile)) {
            $this->ajaxError('对不起，请输入收件人的手机！');
            return;
        }
        // 保存数据
        $model['user_id'] = $userModel['id'];
        $model['user_name'] = $userModel['user_name'];
        $model['street'] = $street;
        $model['accept_name'] = $accept_name;
        $model['area'] = $area;
        $model['province'] = $province;
        $model['city'] = $city;
        $model['address'] = $address;
        $model['mobile'] = $mobile;
        $model['telphone'] = $telphone;
        $model['email'] = $email;
        $model['post_code'] = $post_code;
        $model['is_default'] = $is_default;
        
        if ($is_default == 1) {
            M('user_addr_book')->where('user_id=' . $userModel['id'])->setField('is_default', 0);
        }
        
        $count = M('user_addr_book')->where('user_id=' . $userModel['id'])->count();
        
        if ($count == 0) {
            $model['is_default'] = 1;
        }
        
        if ($id > 0) {
            
            M('user_addr_book')->where('id=' . $id)->save($model);
            
            $data = array();
            $data['msg'] = '修改收货地址成功';
            $data['status'] = 1;
            $data['url'] = 'useraddress.html';
        } else {
            $model['add_time'] = time();
            M('user_addr_book')->add($model);
            
            $data = array();
            $data['msg'] = '新增收货地址成功';
            $data['status'] = 1;
            $data['url'] = 'useraddress.html';
        }
        
        $user_addr_book = M('user_addr_book')->field('*,user_name as name,address as addressName')
            ->where('user_id=' . $user_id)
            ->select();
        
        foreach ($user_addr_book as $key => $value) {
            $user_addr_book[$key]['default'] = false;
            $user_addr_book[$key]['address'] = $value['province'] . $value['city'] . $value['area'];
            IF ($value['is_default'] == 1) {
                
                $user_addr_book[$key]['default'] = true;
            }
        }
        $data['data'] = $user_addr_book;
        
        $this->ajaxReturn($data);
        return;
    }

    public function get_user_addr_book()
    {
        $user_id = $_POST['user_id'];
        $user_addr_book = M('user_addr_book')->field('*,user_name as name,address as addressName')
            ->where('user_id=' . $user_id)
            ->select();
        
        foreach ($user_addr_book as $key => $value) {
            $user_addr_book[$key]['default'] = false;
            $user_addr_book[$key]['address'] = $value['province'] . $value['city'] . $value['area'];
            IF ($value['is_default'] == 1) {
                
                $user_addr_book[$key]['default'] = true;
            }
        }
        
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
        
        if ($type == 1) {
            // $orders = M('orders')->where(' (user_id=' . $user_id . ' or money_user_id= ' . $user_id . ') ')
            // ->order(' add_time DESC ')
            // ->select();
        } // 待付款
else if ($type == 2) {
            $where = '  and  t.payment_status=0 and t.status=1 ';
        } // 待发货
else if ($type == 3) {
            $where = '   and   t.express_status=1 and t.status=2 ';
        } // 待收货
else if ($type == 4) {
            
            $where = '  and  t.express_status=2 and t.status=2 ';
        } // 已完成
else if ($type == 5) {
            $where = '    and  t.status=4 ';
            ;
        }
        
        $page_index = I('post.page_index', 1);
        $page_size = I('post.page_size', 10);
        $orders = M('orders')->alias('t')
            ->join("xt_order_goods AS g ON   g.order_id = t.id ", 'LEFT')
            ->where(' (t.user_id=' . $user_id . ' )   ' . $where)
            ->order(' t.add_time DESC  ')
            ->field('t.*,g.goods_price,g.quantity')
            ->group(' t.id  ')
            ->page($page_index . ',' . $page_size)
            ->select();
        foreach ($orders as $key => $rs) {
            
            $order_goods = M('order_goods')->where('order_id=' . $rs['id'])->select();
            foreach ($order_goods as $key1 => $rs1) {
                
                $order_goods[$key1]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $rs1['img_url']);
                $order_goods[$key1]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $order_goods[$key1]['img_url'];
            }
            
            $orders[$key]['order_goods'] = $order_goods;
            $orders[$key]['status_str'] = get_order_status($rs['id']);
            
            $orders[$key]['addtime_str'] = date("Y-m-d h:i:s", $rs["add_time"]);
            
            $orders[$key]['remain_money_time'] = 0;
            $orders[$key]['now_time'] = time();
            
            $orders[$key]['end_money_time'] = strtotime("+" . $jifen_time1 . " minute", $rs["add_time"]);
            if ((time() < $orders[$key]['end_money_time'])) {
                
                $orders[$key]['remain_money_time'] = $orders[$key]['end_money_time'] - time();
            }
            
            // $orders[$key]['order_amount'] = $rs['goods_price'] * $rs['quantity'];
            $order_quantity = M('order_goods')->where('order_id=' . $rs['id'])->sum('quantity');
            $orders[$key]['order_quantity'] = $order_quantity;
            
            $count = M('order_read')->where('user_id=' . $user_id . '  and order_id=' . $rs['id'])->count();
            IF ($count > 0) {
                M('order_read')->where('user_id=' . $user_id . '  and order_id=' . $rs['id'])->setField('is_read', 1);
                M('order_read')->where('user_id=' . $user_id . '  and order_id=' . $rs['id'])->setField('read_time', time());
            }
            
            $orders[$key]['is_show_daifu'] = 0;
            IF ($orders[$key]['is_daifu'] == 1 && $orders[$key]['status'] == 1 && $orders[$key]['payment_status'] == 0) {
                
                $orders[$key]['is_show_daifu'] = 1;
                $orders[$key]['help_pay_url'] = '/pages/money/help_pay?id=' . $rs['id'];
                ;
            }
        }
        $data = array();
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $orders;
        $data['current_count'] = count($orders);
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
        $orders['status_str'] = get_order_status($orders['id']);
        $orders['addtime_str'] = date("Y-m-d H:i:s", $orders["add_time"]);
        $orders['payment_time_str'] = date("Y-m-d H:i:s", $orders["payment_time"]);
        $orders['confirm_time_str'] = date("Y-m-d H:i:s", $orders["confirm_time"]);
        $orders['express_time_str'] = date("Y-m-d H:i:s", $orders["express_time"]);
        $orders['complete_time_str'] = date("Y-m-d H:i:s", $orders["complete_time"]);
        ;
        
        $data = array();
        $data['msg'] = '获取成功！';
        $data['status'] = 1;
        $data['data'] = $orders;
        $this->ajaxReturn($data);
    }

    public function user_address_delete()
    {
        $user_id = $_POST['user_id'];
        
        $is_mobile = $_POST['is_mobile'];
        $checkId = $_POST['checkId'];
        $arrId = explode(',', $checkId);
        
        foreach ($arrId as $rs1) {
            $id = $rs1;
            if ($id > 0) {
                M('user_addr_book')->where('id=' . $id)->delete();
            }
        }
        
        $data = array();
        $data['msg'] = '删除收货地址成功！';
        $data['status'] = 1;
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
            $U_inpath = (str_replace('./Public/', '__PUBLIC__/', $U_path)) . $U_nname;
            
            echo "<script>window.parent.form1.img1.value='" . $U_inpath . "';</script>";
            echo "<script>window.parent.form1.img1.src='" . $U_inpath . "';</script>";
            
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

    public function GoodsDel()
    {
        $id = $this->_get('id');
        $User = M('Goods');
        $where['id'] = $id;
        $rs = $User->where($where)->delete();
        if ($rs) {
            $this->success('删除成功！');
            exit();
        } else {
            $this->error('删除失败');
            exit();
        }
    }

    public function OrderExpress()
    {
        $id = $this->_get('id');
        $User = M('order');
        $where['id'] = $id;
        $rs = $User->where($where)->setField('status', 2);
        if ($rs) {
            $this->ajaxSuccess('发货成功！');
            exit();
        } else {
            $this->ajaxError('发货失败');
            exit();
        }
    }

    public function OrderDel()
    {
        $id = $this->_get('id');
        $User = M('order');
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
        
        $category_albums = M('category_albums')->where(' category_id=' . $id)->select();
        
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
        
        $category_albums = M('supplier_albums')->where(' supplier_id=' . $id)->select();
        
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

    public function order_list1()
    {
        $map = array();
        $map['is_give'] = 0;
        // $map['payment_id'] = 0;
        // $map['status'] = 2;
        // $map['payment_status'] = 2;
        $form = M('orders');
        
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        $this->assign('user_id', $user_id);
        IF ($user_id > 10) {
            $model = M('fck')->field('id,shop_id,get_level')
                ->where('id=' . $user_id)
                ->find();
            $str = '';
            if ($model['get_level'] != 3) {
                $map['shop_id'] = $model['shop_id'];
            } else {
                $map['shop_id'] = $model['id'];
            }
        }
        
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
            // check_order_status($vo["id"]);
            $list[$key]['order_status'] = get_order_status($vo['id']);
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $vo["add_time"]);
            
            $model = M('order_goods')->where('order_id=' . $vo['id'])->find();
            
            $list[$key]['goods_title'] = $model["goods_title"];
            
            $tousu_count = M('goods_crowd_users')->where('order_id=' . $vo['id'] . ' AND is_tousu=1')->count();
            
            $list[$key]['tousu_count'] = $tousu_count;
            $model = M('fck')->field('user_id')
                ->where('id=' . $vo['user_id'])
                ->find();
            $list[$key]['user_name'] = $model['user_id'];
            $list[$key]['payment_str'] = get_order_payment($vo['payment_id']);
        }
        
        $this->assign('list', $list); // 数据输出到模板
        $this->display();
    }

    public function upload_excel()
    {
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
            $user_money = explode('|', $fee_rs['user_money']);
            foreach ($content as $key => $rs) {
                $array = array();
                $array['seller_no'] = trim($rs[14]);
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
                    $user_name = $fck['user_id'];
                    $u_level = $fck['u_level'];
                    $money = get_lirun($fck, $rs[17]);
                    $user_id = $fck['id'];
                    $re_id = $fck['re_id'];
                    
                    $profit = $rs[17] * $user_money[$fck['u_level'] - 1] * 0.0001;
                    
                    $money = $profit;
                    $userlist = recommend_tree($fck['id']);
                    foreach ($userlist as $k => $r) {
                        
                        $r = get_user_info($r, $r['id']);
                        // 获取总的下线交易额
                        
                        if ($r['re_level'] > $r['u_level']) {
                            
                            $recommend_fen_money = $recommend_fen_money + $rs[17] * ($user_money[$r['re_level'] - 1] - $user_money[$r['u_level'] - 1]) * 0.0001;
                        }
                    }
                    // $recommend_fen_money = $rs[17] * $gap_profit * 0.0001;
                }
                
                $content[$key]['user_name'] = $user_name;
                $content[$key]['money'] = $money;
                
                $item = array();
                $item['order_no'] = $rs[0];
                $count = M('trade_orders')->where($item)->count();
                $item['shop_id'] = $array['seller_no'];
                $item['shop_name'] = $rs[15];
                $item['trade_money'] = $rs[17];
                $item['trade_time'] = strtotime($rs[18]);
                $item['trade_time_str'] = $rs[18];
                $item['real_fen_money'] = $money;
                $item['fen_money'] = $rs[22];
                $item['card_type'] = $rs[19];
                $item['user_id'] = $user_id;
                $item['money_user_id'] = $user_id;
                $item['recommend_fen_money'] = $recommend_fen_money;
                $recommend_fen_money = 0;
                $item['gap_profit'] = $gap_profit;
                
                if ($rs[0] > 0) {
                    IF ($count == 0) {
                        M('trade_orders')->add($item);
                    } else {
                        $item1 = array();
                        $item1['order_no'] = $rs[0];
                        M('trade_orders')->where($item1)->save($item);
                    }
                }
            }
            
            // $content=M('trade_orders')->select();
            
            // $this->assign('list', $content);
        }
        
        $map['money_user_id'] = array(
            'gt',
            0
        );
        $map['is_money'] = array(
            'eq',
            0
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
            $content[$key]['level_str'] = getUserLevel($fck['u_level']);
        }
        
        $this->assign('list', $content);
        
        $this->display();
    }

    public function GoodsHot()
    {
        $id = $this->_get('id');
        $is_hot = $this->_get('is_hot');
        $status = 0;
        if ($is_hot == 1) {
            $status = 0;
        }
        if ($is_hot == 0) {
            $status = 1;
        }
        $User = M('Goods');
        $where['id'] = $id;
        $rs = $User->where($where)->setField('is_hot', $status);
        if ($rs) {
            $this->success('操作成功！');
            exit();
        } else {
            $this->error('操作失败');
            exit();
        }
    }

    public function GoodsRed()
    {
        $id = $this->_get('id');
        $is_red = $this->_get('is_red');
        
        $status = 0;
        if ($is_red == 1) {
            $status = 0;
        }
        if ($is_red == 0) {
            $status = 1;
        }
        $User = M('Goods');
        $where['id'] = $id;
        $rs = $User->where($where)->setField('is_red', $status);
        if ($rs) {
            $this->success('操作成功！');
            exit();
        } else {
            $this->error('操作失败');
            exit();
        }
    }

    public function GoodsTop()
    {
        $id = $this->_get('id');
        $is_top = $this->_get('is_top');
        $status = 0;
        if ($is_top == 1) {
            $status = 0;
        }
        if ($is_top == 0) {
            $status = 1;
        }
        $User = M('Goods');
        $where['id'] = $id;
        $rs = $User->where($where)->setField('is_top', $status);
        if ($rs) {
            $this->success('操作成功！');
            exit();
        } else {
            $this->error('操作失败');
            exit();
        }
    }

    public function UpdateField()
    {
        $fck = M('goods');
        $id = I('post.id');
        $field = I('post.field');
        $value = I('post.value');
        
        $fck->where('id=' . $id)->setField($field, $value);
        
        $this->success('修改成功！');
    }

    public function CateUpdateField()
    {
        $fck = M('article_category');
        $id = I('post.id');
        $field = I('post.field');
        $value = I('post.value');
        
        $fck->where('id=' . $id)->setField($field, $value);
        
        $this->success('修改成功！');
    }

    function seller()
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
        
        $form = M('supplier');
        
        $field = 't.*,g.shop_name,g.shop_name as shop_user_name ,g.shop_id as my_shop_id ,g.user_id as my_user_id,g.user_name as my_user_name   ';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_fck AS g ON   g.id = t.user_id ", 'LEFT')
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

    function seller_edit()
    {
        $is_mobile = $_POST['is_mobile'];
        $auth_status = $this->_get('auth_status');
        
        $user_id = $_SESSION[C('USER_AUTH_KEY')];
        $this->assign('user_id', $user_id);
        // $this->_Admin_checkUser(); // 后台权限检测
        if (! IS_POST) {
            
            $id = $this->_get('id');
            
            $rs = M('supplier')->alias('t')
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
            }
            
            $manager_list = M('fck')->field('id,user_id,user_name, shop_id as my_shop_id, shop_name as my_shop_name')
                ->where(array(
                'role_id' => 3
            ))
                ->select();
            
            $this->assign('manager_list', $manager_list);
            
            $this->assign('id', $id);
            $this->display();
        } else {
            if ($is_mobile == 1) {
                $id = $_POST['id'];
                $rs = M('supplier')->where(array(
                    'user_id' => $id
                ))
                    ->field('*')
                    ->find();
                if ($rs != null) {
                    
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
                    
                    // $goods = M('goods')->where('shop_id=' . $id)->select();
                    
                    // foreach ($goods as $key => $rs1) {
                    
                    // set_img($goods[$key]);
                    // }
                    // $rs = get_seller_info($rs);
                    $rs['goods'] = $goods;
                }
                $data = array();
                
                $app_icon = ARRAY();
                $fee = M('fee');
                $str30 = $fee->where('id=1')->getField('str30');
                $str99 = $fee->where('id=1')->getField('str99');
                
                $app_icon[0]['img'] = $str30;
                $app_icon[1]['img'] = $str99;
                
                // $goods=M('goods')->where('user_id='. $rs['user_id'])->select();
                // $new=M('goods')->where('user_id='. $rs['user_id'])->limit(10)->select();
                // $hot=M('goods')->where('user_id='. $rs['user_id'])->limit(10)->select();
                
                $data['all'] = $goods;
                $data['hot'] = $hot;
                $data['new'] = $new;
                $data['images'] = $app_icon;
                $data['data'] = $rs;
                $data['status'] = 1;
                $this->ajaxReturn($data);
            } else {
                
                $form = M('supplier');
                $item = $_POST;
                $id = I('id');
                
                if ($id > 0) {
                    $data = $form->where('id=' . $id)->find();
                }
                
                if (empty($item['user_id'])) {
                    
                    $this->error('请选择所属管理员！');
                    exit();
                }
                
                $user = M('fck')->where(array(
                    'id' => $item['user_id']
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
                    $rs = M('supplier')->save($data);
                } else {
                    $data['seller_no'] = 'BD' . rand(1000000000, 2000000000);
                    // $data['all_stock'] = $this->_post('stock');
                    $data['add_time'] = time();
                    $rs = M('supplier')->add($data);
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

    public function SellerDel()
    {
        $id = $this->_get('id');
        $User = M('supplier');
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

    public function dui_shop()
    {
        $user_id = $_POST['user_id'];
        $map['id'] = $user_id;
        $user = M('fck')->where($map)->find();
        if ($user == null) {}
        $dui_amount = M('dui_cart')->where(array(
            'user_id' => $user_id
        ))->sum('price*quantity');
        if ($dui_amount == null) {
            $dui_amount = 0;
        }
        
        if ($dui_amount == 0) {
            
            $this->ajaxError('请选择需要兑换的商品！');
            exit();
        }
        if ($user['agent_cash'] < ($dui_amount)) {
            $this->ajaxError(C('agent_cash') . '不足!');
            exit();
        }
        
        $dui_cart = M('dui_cart')->where(array(
            'uid' => $user_id
        ))->select();
        
        foreach ($dui_cart as $key => $voo) {
            $goods = M('goods')->where('id=' . $voo['article_id'])->find();
            
            copy_goods($goods, $user_id, $voo['quantity']);
        }
        $fck = D('Fck');
        $kt_cont = '兑换商品,扣除' . $dui_amount;
        $fck->addencAdd($user['id'], $user['user_id'], - $dui_amount, 19, 0, 0, 0, $kt_cont . '-' . C('agent_cash'), 0);
        
        $result = $fck->where('id=' . $user['id'])->setDec('agent_cash', $dui_amount);
        M('dui_cart')->where(array(
            'uid' => $user_id
        ))->delete();
        
        $data = array();
        $data['msg'] = '兑换成功！';
        $data['info'] = '兑换成功！';
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    function main()
    {
        $slider = ARRAY();
        
        $fee = M('fee');
        
        $goods_images = $fee->where('id=1')->getField('str29');
        
        if (! empty($goods_images)) {
            $goods_images = explode(",", $goods_images);
            
            // $list = array();
            
            foreach ($goods_images as $key => $rs) {
                $item = array();
                $item['img'] = $rs;
                $item['src'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $rs;
                $item['background'] = 'rgb(203, 87, 60)';
                
                $slider[] = $item;
            }
        }
        
        $slider1 = ARRAY();
        $slider1[0]['img'] = __ROOT__ . '/Public/Images/slides/4.png';
        
        $slider2 = ARRAY();
        $slider2[0]['img'] = __ROOT__ . '/Public/Images/slides/7.png';
        $slider2[1]['img'] = __ROOT__ . '/Public/Images/slides/8.png';
        $slider2[2]['img'] = __ROOT__ . '/Public/Images/slides/9.png';
        $category = M('article_category')->where(array(
            'parent_id' => 0,
            'is_lock' => 0
        ))->select();
        
        foreach ($category as $key => $goods) {
            $cate_list = M('article_category')->where('parent_id=' . $goods['id'])
                ->order('   id desc')
                ->limit(100)
                ->select();
            $category[$key]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $goods['img_url'];
            
            $item_list = null;
            
            $item_list = M('goods')->alias('t')
                ->join("xt_article_category AS g ON   g.id = t.category_id", 'LEFT')
                ->field('  t.*')
                ->where('g.class_list like "%,' . $goods['id'] . ',%" AND t.type=0 AND t.stock>0')
                ->order(' t.sort_id asc,t.addtime desc ')
                ->limit(10)
                ->select();
            foreach ($item_list as $key1 => $goods1) {
                $item_list[$key1]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods1['img']);
                $item_list[$key1]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $goods1['img'];
                $item_list[$key1]['url'] = 'goods_show.html';
                $sell_count = 0;
                $item_list[$key1]['sell_count'] = $sell_count;
                $item_list[$key1]['percent'] = 90;
            }
            
            $category[$key]['name'] = $goods['title'];
            $category[$key]['item_list'] = $item_list;
            $category[$key]['item_list_count'] = count($item_list);
            $category[$key]['cate_list'] = $cate_list;
            $category[$key]['child'] = $cate_list;
        }
        
        // 获取头条新闻
        $form = M('form')->field('id,title')
            ->alias('t')
            ->where(" t.type=0  and t.user_id=0   ")
            ->select();
        $category_item = M('article_category')->where(array(
            'id' => $category_id
        ))->find();
        if ($category_item != NULL) {
            $cate_slider = $category_item['slide'];
            $cate_slider = explode(",", $cate_slider);
        }
        
        $video_img = $fee->where('id=1')->getField('str30');
        
        $dui_cart = M('dui_cart')->where(array(
            'user_id' => $user_id
        ))->select();
        $dui_cart_money = M('dui_cart')->where(array(
            'uid' => $user_id
        ))->sum('price*quantity');
        if ($dui_cart_money == null) {
            $dui_cart_money = 0;
        }
        
        $item = ARRAY();
        $item['id'] = 0;
        $item['name'] = '全部商品';
        
        $item_list = M('goods')->alias('t')
            ->join("xt_article_category AS g ON   g.id = t.category_id", 'LEFT')
            ->field('  t.*')
            ->where('  t.type=0 AND t.stock>0')
            ->order(' t.sort_id asc,t.addtime desc ')
            ->
        select();
        foreach ($item_list as $key1 => $goods1) {
            $item_list[$key1]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods1['img']);
            $item_list[$key1]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $goods1['img'];
            $item_list[$key1]['url'] = 'goods_show.html';
            $sell_count = 0;
            $item_list[$key1]['sell_count'] = $sell_count;
            $item_list[$key1]['percent'] = 90;
        }
        $item['item_list'] = $item_list;
        
        $category[] = $item;
        
        $category = array_reverse($category);
        
        $data = array();
        $data['seller'] = $seller;
        $data['data'] = $category;
        $data['current_count'] = count($category);
        $data['all_count'] = count($category);
        $data['category'] = $category;
        $data['form'] = $form;
        $data['dui_cart'] = $dui_cart;
        $data['dui_cart_money'] = $dui_cart_money;
        $data['cate_slider'] = $cate_slider;
        $data['video_img'] = $video_img;
        $data['slider'] = $slider;
        $data['slider1'] = $slider1;
        $data['slider2'] = $slider2;
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    function dui_list()
    {
        $slider = ARRAY();
        
        $fee = M('fee');
        
        $goods_images = $fee->where('id=1')->getField('str29');
        
        if (! empty($goods_images)) {
            $goods_images = explode(",", $goods_images);
            
            // $list = array();
            
            foreach ($goods_images as $key => $rs) {
                $item = array();
                $item['img'] = $rs;
                $item['src'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $rs;
                $item['background'] = 'rgb(203, 87, 60)';
                
                $slider[] = $item;
            }
        }
        
        $slider1 = ARRAY();
        $slider1[0]['img'] = __ROOT__ . '/Public/Images/slides/4.png';
        
        $slider2 = ARRAY();
        $slider2[0]['img'] = __ROOT__ . '/Public/Images/slides/7.png';
        $slider2[1]['img'] = __ROOT__ . '/Public/Images/slides/8.png';
        $slider2[2]['img'] = __ROOT__ . '/Public/Images/slides/9.png';
        $category = M('article_category')->where(array(
            'parent_id' => 0,
            'is_lock' => 0
        ))->select();
        
        foreach ($category as $key => $goods) {
            $cate_list = M('article_category')->where('parent_id=' . $goods['id'])
                ->order('   id desc')
                ->limit(100)
                ->select();
            $category[$key]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $goods['img_url'];
            
            $item_list = null;
            
            $item_list = M('goods')->alias('t')
                ->join("xt_article_category AS g ON   g.id = t.category_id", 'LEFT')
                ->field('  t.*')
                ->where('g.class_list like "%,' . $goods['id'] . ',%" AND t.type=1 AND t.status=1  AND t.stock>0')
                ->order(' t.sort_id asc')
                ->limit(10)
                ->select();
            foreach ($item_list as $key1 => $goods1) {
                $item_list[$key1]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $goods1['img']);
                $item_list[$key1]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $goods1['img'];
                $item_list[$key1]['url'] = 'goods_show.html';
                $sell_count = 0;
                $item_list[$key1]['sell_count'] = $sell_count;
                $item_list[$key1]['percent'] = 90;
            }
            
            $category[$key]['name'] = $goods['title'];
            $category[$key]['item_list'] = $item_list;
            $category[$key]['item_list_count'] = count($item_list);
            $category[$key]['cate_list'] = $cate_list;
            $category[$key]['child'] = $cate_list;
        }
        
        // 获取头条新闻
        $form = M('form')->field('id,title')
            ->alias('t')
            ->where(" t.type=0  and t.user_id=0   ")
            ->select();
        $category_item = M('article_category')->where(array(
            'id' => $category_id
        ))->find();
        if ($category_item != NULL) {
            $cate_slider = $category_item['slide'];
            $cate_slider = explode(",", $cate_slider);
        }
        
        $video_img = $fee->where('id=1')->getField('str30');
        
        $dui_cart = M('dui_cart')->where(array(
            'user_id' => $user_id
        ))->select();
        $dui_cart_money = M('dui_cart')->where(array(
            'uid' => $user_id
        ))->sum('price*quantity');
        if ($dui_cart_money == null) {
            $dui_cart_money = 0;
        }
        
        $data = array();
        $data['seller'] = $seller;
        $data['data'] = $category;
        $data['current_count'] = count($category);
        $data['all_count'] = count($category);
        $data['category'] = $category;
        $data['form'] = $form;
        $data['dui_cart'] = $dui_cart;
        $data['dui_cart_money'] = $dui_cart_money;
        $data['cate_slider'] = $cate_slider;
        $data['video_img'] = $video_img;
        $data['slider'] = $slider;
        $data['slider1'] = $slider1;
        $data['slider2'] = $slider2;
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }
}
?>